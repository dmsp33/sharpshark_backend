<?php namespace Tests\Repositories;

use App\Models\TeamInvitation;
use App\Repositories\TeamInvitationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TeamInvitationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TeamInvitationRepository
     */
    protected $teamInvitationRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->teamInvitationRepo = \App::make(TeamInvitationRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_team_invitation()
    {
        $teamInvitation = TeamInvitation::factory()->make()->toArray();

        $createdTeamInvitation = $this->teamInvitationRepo->create($teamInvitation);

        $createdTeamInvitation = $createdTeamInvitation->toArray();
        $this->assertArrayHasKey('id', $createdTeamInvitation);
        $this->assertNotNull($createdTeamInvitation['id'], 'Created TeamInvitation must have id specified');
        $this->assertNotNull(TeamInvitation::find($createdTeamInvitation['id']), 'TeamInvitation with given id must be in DB');
        $this->assertModelData($teamInvitation, $createdTeamInvitation);
    }

    /**
     * @test read
     */
    public function test_read_team_invitation()
    {
        $teamInvitation = TeamInvitation::factory()->create();

        $dbTeamInvitation = $this->teamInvitationRepo->find($teamInvitation->id);

        $dbTeamInvitation = $dbTeamInvitation->toArray();
        $this->assertModelData($teamInvitation->toArray(), $dbTeamInvitation);
    }

    /**
     * @test update
     */
    public function test_update_team_invitation()
    {
        $teamInvitation = TeamInvitation::factory()->create();
        $fakeTeamInvitation = TeamInvitation::factory()->make()->toArray();

        $updatedTeamInvitation = $this->teamInvitationRepo->update($fakeTeamInvitation, $teamInvitation->id);

        $this->assertModelData($fakeTeamInvitation, $updatedTeamInvitation->toArray());
        $dbTeamInvitation = $this->teamInvitationRepo->find($teamInvitation->id);
        $this->assertModelData($fakeTeamInvitation, $dbTeamInvitation->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_team_invitation()
    {
        $teamInvitation = TeamInvitation::factory()->create();

        $resp = $this->teamInvitationRepo->delete($teamInvitation->id);

        $this->assertTrue($resp);
        $this->assertNull(TeamInvitation::find($teamInvitation->id), 'TeamInvitation should not exist in DB');
    }
}
