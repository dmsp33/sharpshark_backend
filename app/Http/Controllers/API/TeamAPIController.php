<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTeamAPIRequest;
use App\Http\Requests\API\UpdateTeamAPIRequest;
use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Response;

/**
 * Class TeamController
 * @package App\Http\Controllers\API
 */

class TeamAPIController extends AppBaseController
{
    /** @var  TeamRepository */
    private $teamRepository;

    public function __construct(TeamRepository $teamRepo)
    {
        $this->teamRepository = $teamRepo;
    }

    
    public function index(Request $request)
    {
        $teams = $this->teamRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($teams->toArray(), 'Teams retrieved successfully');
    }

    
    public function store(CreateTeamAPIRequest $request)
    {
        $input = $request->all();
        $input = array_merge($input, ['user_id' => $request->user()->id]);
        
        $team = $this->teamRepository->create($input);

        return $this->sendResponse($team->load('owner', 'users', 'teamInvitations')->toArray(), 'Team saved successfully');
    }

    
    public function show($id)
    {
        /** @var Team $team */
        $team = $this->teamRepository->find($id);

        if (empty($team)) {
            return $this->sendError('Team not found');
        }

        return $this->sendResponse($team->load('owner', 'users','teamInvitations')->toArray(), 'Team retrieved successfully');
    }

    
    public function update($id, UpdateTeamAPIRequest $request)
    {
        $input = $request->all();

        /** @var Team $team */
        $team = $this->teamRepository->find($id);

        if (empty($team)) {
            return $this->sendError('Team not found');
        }

        $team = $this->teamRepository->update($input, $id);

        return $this->sendResponse($team->load('owner', 'users', 'teamInvitations')->toArray(), 'Team updated successfully');
    }

    
    public function destroy($id)
    {
        /** @var Team $team */
        $team = $this->teamRepository->find($id);

        if (empty($team)) {
            return $this->sendError('Team not found');
        }

        $team->delete();

        return $this->sendResponse([], 'Team deleted successfully');
    }
}
