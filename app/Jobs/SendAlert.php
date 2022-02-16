<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::with('alerts')->get();

        foreach ($users as $user) {
            $alerts_to_send = [];
            foreach ($user->alerts as $alert) {
                if ($alert->reviewed == false) {
                    array_push($alerts_to_send , $alert);
                    $alert->update(['reviewed' => true]);
                }
            }

            if (count($alerts_to_send) > 0) {
                Log::alert("Sending email to user: " . $user->name);
                Mail::to($user->email)->send(new AlertMail($alerts_to_send , $user));
            } else {
                Log::alert("This user doesn't have alerts: " . $user->name);
            }

           
            
        }
        
    }
}
