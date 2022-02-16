<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateTeamInvitationAPIRequest;
use App\Http\Requests\API\UpdateTeamInvitationAPIRequest;
use App\Mail\API\TeamInvitation;
use App\Models\Team;
use App\Models\TeamInvitation as ModelsTeamInvitation;
use Illuminate\Support\Facades\Mail;
use Response;

/**
 * Class TeamMemberAPIController
 * @package App\Http\Controllers\API
 */

class TeamMemberAPIController extends AppBaseController
{

    public function store(CreateTeamInvitationAPIRequest $request, Team $team)
    {
        $input = $request->all();

        //send invitation
        $invitation = $team->teamInvitations()->create([
            'email' => $input['email'],
            'name' => $input['name'],
        ]);
        Mail::to($input['email'])->send(new TeamInvitation($invitation));

        return $this->sendResponse([], 'invited team member.');
    }


    
    public function destroy(Request $request, Team $team, $email)
    {
        $teamInvitation = ModelsTeamInvitation::where('team_id', $team->id)
            ->where('email', $email)->first();
        
        if(blank($teamInvitation)) {
            return $this->sendResponse([], 'The invitation does not exist');
        }

        $teamInvitation->delete();

        return $this->sendResponse([], 'Team Member deleted successfully');
    }
}
