<?php

namespace App\Console\Commands;

use App\Models\Alerta;
use Illuminate\Console\Command;
use App\Models\CopyLeaksScan;
use App\Models\CopyLeaksResponse;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendAlert as EnviarAlerta;

class SendAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send alert collected from copyleaks plagarism';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $copyleaks = CopyLeaksScan::where('audited', false)->get();
        foreach ($copyleaks as $copyleak) {
            $respuestaCopyLeaks = CopyLeaksResponse::where('copy_leaks_scan_id', $copyleak->id)->get();
            if (empty($copyleaks) || count($respuestaCopyLeaks) == 0) {
                Log::alert("No alerts to send");
            } else {
                if ($respuestaCopyLeaks->count() == 1 && $respuestaCopyLeaks->first()->plagarism == false)
                    Log::alert("We don't have plagarism for scans");
                else {
                    $array = [];
                    $matched = [];
                    if (env('PLAGIARISM_FILTER_ENABLED')) {

                        foreach ($respuestaCopyLeaks as $response) {
                            $prc = 100;
                            if ($response->totalWords)
                                $prc = $response->matchedWords / $response->totalWords * 100;
                            //			    array_push($matched , $response->matchedWords);
                            array_push($matched, $prc);
                        }
//                        $media = array_sum($matched) / count($matched);
                        $media = max($matched);
                        if ($media <= env('PLAGIARISM_FILTER_PERCENTAGE')) {
                            Log::alert("No alerts to send");
                        } else {

                            foreach ($respuestaCopyLeaks as $response) {

                                $same = Alerta::where('url', $response->url)->where('title', $copyleak->title)->first();
                                if (isset($same)) {
                                    Log::alert('This url already noticed ' . $response->url);
                                } else {

                                    Alerta::create([
                                        "url" => $response->url,
                                        "title" => $copyleak->title,
                                        "content" => $copyleak->body,
                                        "user_id" => $copyleak->user_id,
                                        "documento_id" => $copyleak->documento_id,
                                        'reviewed' => false
                                    ]);
                                }
                            }
                        }
                    } else {

                        foreach ($respuestaCopyLeaks as $response) {

                            $same = Alerta::where('url', $response->url)->where('title', $copyleak->title)->first();
                            if (isset($same)) {
                                Log::alert('This url already noticed ' . $response->url);
                            } else {

                                Alerta::create([
                                    "url" => $response->url,
                                    "title" => $copyleak->title,
                                    "content" => $copyleak->body,
                                    "user_id" => $copyleak->user_id,
                                    "documento_id" => $copyleak->documento_id,
                                    'reviewed' => false
                                ]);
                            }
                        }
                    }

                    Log::alert('Register alert from ' . $copyleak->user_id);
                }
            }
            $copyleak->update(['audited' => true]);
            Log::alert('Actualizando auditado de ' . $copyleak->scan_id);
        }
        EnviarAlerta::dispatch();
    }
}
