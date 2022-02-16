<?php

namespace App\Http\Controllers\API;


use Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\API\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'repeat_password' => 'required|same:password',
            'google_id' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['uuid'] = Str::uuid();
        $user = User::create($input);

        $success['token'] =  $user->createToken('sharpShark')->plainTextToken;
        $success['user'] =  $user;

        return $this->sendResponse($success, 'User register successfully.');
    }



    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:api,google',
            'email' => 'required|email',
            'password' => 'required_if:provider,api|string',
            'google_id' => 'required_if:provider,google|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        

        if ($this->authWithProvider($request->all())) {

            $user = Auth::user();

            $success['token'] = $user->createToken('sharpShark')->plainTextToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function authWithProvider($data)
    {
        switch ($data['provider']) {
            case 'api':
                return Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);
            case 'google':
                
                $user = User::where('email',$data['email'])->first();
                $user->fill(['google_id' => $data['google_id']])->save(); //demo ocultar cuando pase a produccion

                if ($user->google_id == $data['google_id']) {
                    Auth::login($user);
                    return true;
                } else {
                    return false;
                }
            
            default:
                # code...
                break;
        }
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendResponse([], 'Logged out');
    }


    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {

        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
    
    
    public function userExists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);      
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    }
    
    
    
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        
        //Check if the user exists
        $user = User::where('email', $request->email)->first();
        if (blank($user)) {
            return $this->sendError('User does not exist', ['email' => 'User does not exist']);
        }

        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(60),
            'created_at' => now()
        ]);

        //Get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

        if ($this->sendResetEmail($user, $tokenData->token)) {
            return $this->sendResponse([], 'A reset link has been sent to your email address.');
        } else {
            return $this->sendError('A Network Error occurred. Please try again.');
        }
    }


    public function sendResetEmail(User $user, $token)
    {        
        try {
            Mail::to($user->email)->send(new ResetPassword($user, $token));
            return true;
        } catch (\Exception $e) {
            dd('errror', $e);
            return false;
        }
    }


    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed',
            'token' => 'required' 
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Please complete the form', $validator->errors());
        }

        $password = $request->password;
        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();
        if (!$tokenData) return $this->sendError('This token has expired, try again.');

        $user = User::where('email', $tokenData->email)->first();
        if (!$user) return $this->sendError('Email not found');
        
        $user->update(['password' => Hash::make($password)]);  //Hash and update the new password

        Auth::login($user); //login the user immediately they change password successfully

        DB::table('password_resets')->where('email', $user->email)->delete(); //Delete the token

        $success['token'] = $user->createToken('sharpShark')->plainTextToken;
        $success['user'] =  $user;

        return $this->sendResponse($success, 'Your password has been change.');

        // Send  password change email successfully
        // if ($this->sendSuccessEmail($tokenData->email)) {
        //     return view('index');
        // } else {
        //     return $this->sendError(trans('A Network Error occurred. Please try again.'));
        // }
    }
}
       
