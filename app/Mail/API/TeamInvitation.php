<?php

namespace App\Mail\API;

use App\Models\TeamInvitation as ModelsTeamInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The team invitation instance.
     *
     * @var $invitation
     */
    public $invitation;
    public $base_url;
    public $accept_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ModelsTeamInvitation $invitation)
    {
        $this->invitation = $invitation;
        $this->base_url = env('APP_ENV') === 'local' ? "http://localhost:4200": "https://go.sharpshark.io";
        $this->accept_url = '';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Team Invitation'))->view('mails.api.team-invitation');
    }
}
