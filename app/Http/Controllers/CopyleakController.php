<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Copyleaks\Copyleaks;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CopyLeaksScan;
use App\Models\CopyLeaksToken;
use App\Models\CopyLeaksResponse;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\SubmissionWebhooks;
use Copyleaks\SubmissionProperties;
use Copyleaks\CopyleaksFileSubmissionModel;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use App\Events\CopyleaksSent;
use Illuminate\Support\Facades\Auth;
use Log;

class CopyleakController extends Controller
{


	public function getAuthKey()
	{
		$this->getAuthKey2();
		$email = env('COPYLEAKS_EMAIL');
		$key = env('COPYLEAKS_KEY');

		$copyleaks = new Copyleaks();
		$loginResult = $copyleaks->login($email, $key);

		return response()->json($loginResult);
	}

	public function SubmitFile(Request $request)
	{
		$authToken = $this->getAuthKey2();
		$scanID = strtolower(Str::random(36));
		if (isset($request->console)) {
			CopyLeaksScan::create(['scan_id' => $scanID, 'title' => $request->title, 'body' => $request->body, 'audited' => false , 'user_id' => $request->user_id ?? Auth::id(), 'documento_id' => $request->documento_id]);
		} else {
        		CopyLeaksScan::create(['scan_id' => $scanID, 'title' => $request->title, 'body' => $request->body , 'user_id' => $request->user_id ?? Auth::id()]);
		}
		
		$product = 'businesses';
		if(isset($request->debug)){
			$properties = new SubmissionProperties(new SubmissionWebhooks(route("copyleaks.webhook") . "/{STATUS}"), true, null, true);
		} else {
			$properties = new SubmissionProperties(new SubmissionWebhooks(route("copyleaks.webhook") . "/{STATUS}"), true);
		}
			$body = new CopyleaksFileSubmissionModel(base64_encode($request->body), $request->title . ".txt", $properties);

		$copyleaks = new Copyleaks();
		$copyleaks->submitFile($product, new CopyleaksAuthToken($authToken->expires, $authToken->accessToken, ''), $scanID, $body);
		return response()->json($scanID);
	}

	private function getAuthKey2()
	{
		$CopyLeaksToken = CopyLeaksToken::latest()->first();
		$ahora = Carbon::now()->subMinute();
		if (empty($CopyLeaksToken) || $ahora->gt($CopyLeaksToken->expires)) {
			$email = env('COPYLEAKS_EMAIL');
			$key = env('COPYLEAKS_KEY');

			$copyleaks = new Copyleaks();
			$CopyLeaksToken = CopyLeaksToken::create((array) $copyleaks->login($email, $key));
		}
		return $CopyLeaksToken;
	}

	public function Webhook(Request $request)
	{
		$documentScanned = CopyLeaksScan::where('scan_id', $request->scannedDocument['scanId'])->first();

		if (empty($request->results['internet'])) {
			$response = $documentScanned->copyLeaksResponse()->create([
				'plagarism' => false
			]);
		} else {
			$totalWords = $request->scannedDocument['totalWords'];

			foreach ($request->results['internet'] as $result) {
				$response = $documentScanned->copyLeaksResponse()->create([
					'url' => $result['url'],
					'title' => $result['title'],
					'introduction' => $result['introduction'],
					'matchedWords' => $result['matchedWords'],
					'plagarism' => true,
					'totalWords' => $totalWords
				]);
				//broadcast(new CopyleaksSent($response->url, $response->title, $response->introduction, $response->matchedWords, $documentScanned->scan_id));
			}
		}
	}

	public function fakeBroadcast(Request $request)
	{
		$copyleaks = CopyLeaksScan::where('scan_id', $request->scan_id)->first();
		$respuestaCopyLeaks = CopyLeaksResponse::where('copy_leaks_scan_id', $copyleaks->id)->get();

		if ($copyleaks->user_id == env('DEMO_USER_ID')) {
			return ['completed' => true , "plagarism" => false];
		}

		// dd($respuestaCopyLeaks);
		if (empty($copyleaks) || count($respuestaCopyLeaks) == 0) {
			return response()->json(['completed' => false]);
		} else {
			if ($respuestaCopyLeaks->count() == 1 && $respuestaCopyLeaks->first()->plagarism == false)
				return response()->json(['completed' => true, "plagarism" => false]);
			else {
				$array =[];
				$matched = [];

				if (env('PLAGIARISM_FILTER_ENABLED')) {

					foreach ($respuestaCopyLeaks as $response) {
						$prc = 100;
						if($response->totalWords)
						    $prc = $response->matchedWords/$response->totalWords * 100;
//						array_push($matched , $response->matchedWords);	
						array_push($matched , $prc);	
					}
//					$media = round(array_sum($matched)/count($matched), 1);
					$media = max($matched);
					if ($media <= env('PLAGIARISM_FILTER_PERCENTAGE')) {
					    return response()->json(['completed' => true, 'plagarism' => false, 'percentage' => $media]);
					} else {
					    foreach ($respuestaCopyLeaks as $response) {
						$prc = 100;
						if($response->totalWords)
						    $prc = round($response->matchedWords/$response->totalWords * 100, 1);

					        array_push( $array , [
						    'url' => $response->url,
						    'title' => $response->title,
						    'introduction' => $response->introduction,
						    'matchedWords' => $response->matchedWords,
						    'plagarism' => $response->plagarism,
						    'percentage' => $prc,
						    'totalWords' => $response->totalWords,
						]);
					    }
					    return  response()->json(['completed' => true, 'plagarism' => true, 'percentage' => $media, 'response' => $array]);
	
					}
					
				} else {
				    foreach ($respuestaCopyLeaks as $response) {
					$prc = 100;
					if($response->totalWords)
					    $prc = round($response->matchedWords/$response->totalWords * 100, 1);
					array_push( $array , [
					    'url' => $response->url,
					    'title' => $response->title,
					    'introduction' => $response->introduction,
					    'matchedWords' => $response->matchedWords,
					    'plagarism' => $response->plagarism,
					    'percentage' => $prc,
					    'totalWords' => $response->totalWords,
					]);	
				    }

				    return  response()->json(['completed' => true, 'plagarism' => true, 'response' => $array]);
				}
				
			}
		}
	}
}
