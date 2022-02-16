<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertMail;
use App\Models\CopyLeaksScan;
use App\Models\CopyLeaksResponse;
use App\Models\Alerta;
use Illuminate\Support\Facades\Log;

class AlertBotController extends Controller
{
    public function collect(Request $request)
    {
        $user = User::find($request->userid);
        if(empty($user))return response()->json(['artisan collect' => false], 404);
        
        Artisan::call('user:collect', ['user' => $user->id]);
        return response()->json(['artisan collect' => true]);
    }

    public function send(Request $request)
    {
        $user = User::find($request->userid);
        if(empty($user))return response()->json(['artisan collect' => false], 404);

        $copyleaks = CopyLeaksScan::where('audited' , false)->where('user_id' , $user->id)->get();
        foreach ($copyleaks as $copyleak) {
            $respuestaCopyLeaks = CopyLeaksResponse::where('copy_leaks_scan_id', $copyleak->id)->get();
            if (empty($copyleaks) || count($respuestaCopyLeaks) == 0) {
                Log::alert("No alerts to send");
            } else {
                if ($respuestaCopyLeaks->count() == 1 && $respuestaCopyLeaks->first()->plagarism == false)
                   Log::alert("We don't have plagarism for scans");
                else {

                        $alerts = [];
                        foreach ($respuestaCopyLeaks as $response) {
        
                            $same = Alerta::where('url' , $response->url)->where('title' , $copyleak->title)->first();
                            if (isset($same)) {
                                Log::alert('This url already noticed ' . $response->url);
                            } else {

                                $alert = Alerta::create([
                                    "url" => $response->url,
                                    "title" => $copyleak->title,
                                    "content" => $copyleak->body,
                                    "user_id" => $copyleak->user_id,
                                    "documento_id" => $copyleak->documento_id,
                                    'reviewed' => false
                                   ]);

                                array_push($alerts , $alert);

                                   

                            }
                             

                           
                        }

                        Mail::to($user->email)->send(new AlertMail($alerts , $user));
                        Log::alert('Register alert from ' . $copyleak->user_id);
                            
    
                }
            }
            $copyleak->update(['audited' => true]);
            Log::alert('Actualizando auditado de ' . $copyleak->scan_id);
        } 

        
        return response()->json(['artisan send' => true]);
    }
}
