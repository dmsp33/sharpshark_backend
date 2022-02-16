<?php

namespace App\Http\Controllers\API;

use App\Helpers\SharpTools;
use App\Models\User;
use App\Mail\DisputaSent;
use App\Models\Disputa;
use App\Models\Publicacion;
use App\Http\Requests\DisputaRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DisputeController extends Controller
{
    public function index(Request $request)
    {
        $data = Disputa::where('user_id', $request->user()->id)->with('alerta')->get();
        return ['message' => "all disputes!" , 'data' => $data->toArray()];
    }

    /**
     *
     * @SWG\Post(
     *      path="/disputas",
     *      summary="Generating copyright claims in case of disputes.",
     *      tags={"Disputa"},
     *      description="Stores information given by a user to make a copyright infringement claim.",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          in="body",
     *          description="Identification of the user who has the dispute",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="in_quiestion_web_archive",
     *          in="body",
     *          description="",
     *          required=false,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="claim_for",
     *          in="body",
     *          description="",
     *          required=false,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="jurisdiction",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="discovered",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="screenshot",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="remove-content",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="acknowledge",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="pay-for-use",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="amount",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="money-type",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="conditions_default",
     *          in="body",
     *          description="",
     *          required=false,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="certificate_authorship",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="screenshot_draft",
     *          in="body",
     *          description="",
     *          required=false,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="in_question",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="your_publication",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Parameter(
     *          name="your_web_archive",
     *          in="body",
     *          description="",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *            
     *              @SWG\Property(
     *                  property="message",
     *                  description="dispute stored succesfully",
     *                  type="string"
     *              ),
     *          )
     *      )
     * )
     */
    
    public function store(DisputaRequest $request)
    {
        try {
            $disputa = Disputa::updateOrCreate(
                $request->only(['user_id', 'alerta_id']),
                $request->except(['user_id', 'alerta_id'])
            );
        
            return ['message' => "Disputa registered succesfully!" , 'data' => $disputa->toArray()];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

     
    }

    /**
     *
     * @SWG\Get(
     *      path="/disputas/{id}",
     *      summary="List disputes from user id.",
     *      tags={"Disputa"},
     *      description="Get a list of dispute has the user done.",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          in="body",
     *          description="Identification of the user who has the dispute",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *            
     *              @SWG\Property(
     *                  property="dispute",
     *                  description="disputes from user",
     *                  type="object"
     *              ),
     * 
     *          )
     *      )
     * )
     */

    public function show($id)
    {
        $disputas = Disputa::find($id)->get();

        return $disputas;
    }

     /**
     *
     * @SWG\Put(
     *      path="/disputas/{id}",
     *      summary="Update dispute from id.",
     *      tags={"Disputa"},
     *      description="Update a specific dispute.",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          in="body",
     *          description="Identification of the user who has the dispute",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *            
     *              @SWG\Property(
     *                  property="dispute",
     *                  description="disputes from user",
     *                  type="object"
     *              ),
     * 
     *          )
     *      )
     * )
     */

    public function update(Request $request , Disputa $disputa)
    {
        $input = $request->all();

        try {
            $updated = $disputa->update($input);
            return $updated;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

     /**
     *
     * @SWG\Delete(
     *      path="/disputas/{id}",
     *      summary="Delete dispute from id.",
     *      tags={"Disputa"},
     *      description="Delete a specific dispute.",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          in="body",
     *          description="DISPUTE ID",
     *          required=true,
     *          @SWG\Schema(ref="")
     *      ),
     *      
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *            
     *              @SWG\Property(
     *                  property="message",
     *                  description="disputes from user",
     *                  type="string"
     *              ),
     * 
     *          )
     *      )
     * )
     */

    public function destroy(Disputa $disputa)
    {
        $disputa->delete();

        return ['Message' => 'Deleted'];
    }

    public function sendClaim(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        Mail::to($request->email)->send(new DisputaSent($request->claim));

        return ['Message' => 'Send Claim Succesfully!'];


        
    }
}
