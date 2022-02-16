<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TeamInvitation;

class TeamInvitationApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_team_invitation()
    {
        $teamInvitation = TeamInvitation::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/team_invitations', $teamInvitation
        );

        $this->assertApiResponse($teamInvitation);
    }

    /**
     * @test
     */
    public function test_read_team_invitation()
    {
        $teamInvitation = TeamInvitation::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/team_invitations/'.$teamInvitation->id
        );

        $this->assertApiResponse($teamInvitation->toArray());
    }

    /**
     * @test
     */
    public function test_update_team_invitation()
    {
        $teamInvitation = TeamInvitation::factory()->create();
        $editedTeamInvitation = TeamInvitation::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/team_invitations/'.$teamInvitation->id,
            $editedTeamInvitation
        );

        $this->assertApiResponse($editedTeamInvitation);
    }

    /**
     * @test
     */
    public function test_delete_team_invitation()
    {
        $teamInvitation = TeamInvitation::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/team_invitations/'.$teamInvitation->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/team_invitations/'.$teamInvitation->id
        );

        $this->response->assertStatus(404);
    }
}
