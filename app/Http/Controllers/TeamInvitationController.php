<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamInvitationRequest;
use App\Http\Requests\UpdateTeamInvitationRequest;
use App\Repositories\TeamInvitationRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TeamInvitationController extends AppBaseController
{
    /** @var  TeamInvitationRepository */
    private $teamInvitationRepository;

    public function __construct(TeamInvitationRepository $teamInvitationRepo)
    {
        $this->teamInvitationRepository = $teamInvitationRepo;
    }

    /**
     * Display a listing of the TeamInvitation.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $teamInvitations = $this->teamInvitationRepository->all();

        return view('team_invitations.index')
            ->with('teamInvitations', $teamInvitations);
    }

    /**
     * Show the form for creating a new TeamInvitation.
     *
     * @return Response
     */
    public function create()
    {
        return view('team_invitations.create');
    }

    /**
     * Store a newly created TeamInvitation in storage.
     *
     * @param CreateTeamInvitationRequest $request
     *
     * @return Response
     */
    public function store(CreateTeamInvitationRequest $request)
    {
        $input = $request->all();

        $teamInvitation = $this->teamInvitationRepository->create($input);

        Flash::success('Team Invitation saved successfully.');

        return redirect(route('teamInvitations.index'));
    }

    /**
     * Display the specified TeamInvitation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $teamInvitation = $this->teamInvitationRepository->find($id);

        if (empty($teamInvitation)) {
            Flash::error('Team Invitation not found');

            return redirect(route('teamInvitations.index'));
        }

        return view('team_invitations.show')->with('teamInvitation', $teamInvitation);
    }

    /**
     * Show the form for editing the specified TeamInvitation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $teamInvitation = $this->teamInvitationRepository->find($id);

        if (empty($teamInvitation)) {
            Flash::error('Team Invitation not found');

            return redirect(route('teamInvitations.index'));
        }

        return view('team_invitations.edit')->with('teamInvitation', $teamInvitation);
    }

    /**
     * Update the specified TeamInvitation in storage.
     *
     * @param int $id
     * @param UpdateTeamInvitationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTeamInvitationRequest $request)
    {
        $teamInvitation = $this->teamInvitationRepository->find($id);

        if (empty($teamInvitation)) {
            Flash::error('Team Invitation not found');

            return redirect(route('teamInvitations.index'));
        }

        $teamInvitation = $this->teamInvitationRepository->update($request->all(), $id);

        Flash::success('Team Invitation updated successfully.');

        return redirect(route('teamInvitations.index'));
    }

    /**
     * Remove the specified TeamInvitation from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $teamInvitation = $this->teamInvitationRepository->find($id);

        if (empty($teamInvitation)) {
            Flash::error('Team Invitation not found');

            return redirect(route('teamInvitations.index'));
        }

        $this->teamInvitationRepository->delete($id);

        Flash::success('Team Invitation deleted successfully.');

        return redirect(route('teamInvitations.index'));
    }
}
