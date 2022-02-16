<?php

namespace App\Http\Controllers\API;

use App\Helpers\SharpTools;
use App\Models\Alerta;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\API\AlertCollection;
use App\Models\Certificado;
use App\Models\Disputa;
use App\Models\Documento;
use Stevebauman\Location\Facades\Location;

class AlertController extends Controller
{
    public function getAlerts(Request $request)
    {
       $user = User::where('id' , $request->id)->first();

       $documents_to_send = [];
       $alerts_actual = [];
       $alerts_not_actual = [];

       
       
       foreach ($user->documents as $document) {
        array_push($documents_to_send , $document);
            foreach ($document->alerts as $alert) {
               if ($alert->actual == true) {
                array_push($alerts_actual , $alert);
               } else {
                array_push($alerts_not_actual , $alert);
               }
           }
           
       }



       $response = [
            'success' => true,
            'documents' => $documents_to_send,
            'alerts_actual' => $alerts_actual,
            'alerts_not_actual' => $alerts_not_actual,
        ];


        return $response;        
    }

    public function update($id)
    {

        $alert = Alerta::where('id', $id)->first();


        if (empty($alert)) {
            return response()->json(['success' => false, 'message' => 'Alert not found']);
        }

        if ($alert->actual == 1) {
            $alert->update([
                'actual' => 0,
            ]); 
        } else {

            $alert->update([
                'actual' => 1,
            ]);

        }

        $response = [
            'success' => true,
            'message'    => 'Alert updated successfully',
        ];
        return response()->json($response, 200);
    }

    public function getActualAlerts()
    {
       $alerts = Alerta::where('user_id', request()->user()->id)
                    ->where('documento_id', '!=', null)
                    ->where('actual' , true)->paginate(30);

        return response()->json(new AlertCollection($alerts), 200);        
    }

    public function getNotActualAlerts()
    {
       $alerts = Alerta::where('user_id', request()->user()->id)
                    ->where('documento_id', '!=', null)
                    ->where('actual' , false)->paginate(30);


        return response()->json(new AlertCollection($alerts), 200);        
    }

   /**
     *
     * @SWG\Get(
     *      path="Alert/wfgetActualAlerts",
     *      summary="Get the user ID of the actual alert request",
     *      tags={"Alerta"},
     *      description="Request user ID for alert",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="user_id",
     *          in="body",
     *          description="Alert that should be stored",
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
     *                  property="actual_alert",
     *                  type="object"
     *              ),
     *             
     *          )
     *      )
     * )
     */
    
    public function getActualAlertswf(Request $request)
    {
       $alerts = Alerta::where('user_id', request()->user()->id)
                    ->where('documento_id', '!=', null)
                    ->where('actual' , true)->get();

        return response()->json($alerts->toArray(), 200);        
    }

    /**
     *
     * @SWG\Get(
     *      path="Alert/wfgetNotActualAlert",
     *      summary="Get the user ID of the no actual alert request",
     *      tags={"Alerta"},
     *      description="Request user ID for no actual alert",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="user_id",
     *          in="body",
     *          description="Alert that should be stored",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Certificado")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *            
     *              @SWG\Property(
     *                  property="noactual_alert",
     *                  type="object"
     *              ),
     *             
     *          )
     *      )
     * )
     */
    
    public function getNotActualAlertswf()
    {
       $alerts = Alerta::where('user_id', request()->user()->id)
                    ->where('documento_id', '!=', null)
                    ->where('actual' , false)->get();


        return response()->json($alerts->toArray(), 200);        
    }

    /**
     *
     * @SWG\Get(
     *      path="Alert/Location/{alert_id}",
     *      summary="Alert ID will be used to return",
     *      tags={"Alerta"},
     *      description="The alert ID will be used to return the url, countryName, countryCode and timestamps",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="alert_id",
     *          in="body",
     *          description="Alert that should be stored",
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
     *                  property="url",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="countryName",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="countryCode",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="timestamps",
     *                  type="string"
     *   
     *              ),
     *          )
     *      )
     * )
     */
    
    public function showForm($alert_id, $documento_id)
    {
        $documento = Documento::find($documento_id);
        $ipfs = Certificado::where('uuid' , $documento->uuid)->pluck('ipfs')->first();

        $alert_to_react = Alerta::find($alert_id);
        $url = $alert_to_react->url;
        $host = parse_url($url);
        $url_formated = gethostbyname($host['host']);
        $position = Location::get($url_formated);

        $lastDisputeId = Disputa::where('alerta_id', $alert_id)->latest()->pluck('id')->first();

        $response = [
            'url' => $url,
            'countryName' => $position->countryName,
            'countryCode' => $position->countryCode,
            'timestamps' => $alert_to_react->created_at->format('Y-m-d'),
            'ipfs' => $ipfs,
            'dispute_id' => $lastDisputeId,
        ];

        return response()->json($response, 200);
    }
}
