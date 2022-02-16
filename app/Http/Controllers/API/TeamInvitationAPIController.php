<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTeamInvitationAPIRequest;
use App\Http\Requests\API\UpdateTeamInvitationAPIRequest;
use App\Models\TeamInvitation;
use App\Repositories\TeamInvitationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use Response;

/**
 * Class TeamInvitationController
 * @package App\Http\Controllers\API
 */

class TeamInvitationAPIController extends AppBaseController
{
    /** @var  TeamInvitationRepository */
    private $teamInvitationRepository;

    public function __construct(TeamInvitationRepository $teamInvitationRepo)
    {
        $this->teamInvitationRepository = $teamInvitationRepo;
    }

    
    public function accept($id)
    {
        /** @var TeamInvitation $teamInvitation */
        $teamInvitation = $this->teamInvitationRepository->find($id);
        //$teamInvitation->ower Verificar si tiene permiso para aceptar invitacion

        if (empty($teamInvitation)) {
            return $this->sendError('Team Invitation not found');
        }
        $team = $teamInvitation->team;
        $newTeamMember = User::where('email', $teamInvitation->email)->first();
        $team->users()->attach(
            $newTeamMember
        );
        $team->fresh();
        $teamInvitation->delete();
        
        return $this->sendResponse($team->load('owner', 'users', 'teamInvitations')->toArray(), 'Team Invitation retrieved successfully');
    }


    
    public function destroy($id)
    {
        // Verificar si tengo permisos para eliminar

        /** @var TeamInvitation $teamInvitation */
        $teamInvitation = $this->teamInvitationRepository->find($id);
        
        if (blank($teamInvitation)) {
            return $this->sendError('Team Invitation not found');
        }

        $teamInvitation->delete();

        return $this->sendResponse([], 'Team Invitation deleted successfully');
    }
}
