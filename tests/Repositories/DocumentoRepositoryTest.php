<?php namespace Tests\Repositories;

use App\Models\Documento;
use App\Repositories\DocumentoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DocumentoRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var DocumentoRepository
     */
    protected $documentoRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->documentoRepo = \App::make(DocumentoRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_documento()
    {
        $documento = Documento::factory()->make()->toArray();

        $createdDocumento = $this->documentoRepo->create($documento);

        $createdDocumento = $createdDocumento->toArray();
        $this->assertArrayHasKey('id', $createdDocumento);
        $this->assertNotNull($createdDocumento['id'], 'Created Documento must have id specified');
        $this->assertNotNull(Documento::find($createdDocumento['id']), 'Documento with given id must be in DB');
        $this->assertModelData($documento, $createdDocumento);
    }

    /**
     * @test read
     */
    public function test_read_documento()
    {
        $documento = Documento::factory()->create();

        $dbDocumento = $this->documentoRepo->find($documento->id);

        $dbDocumento = $dbDocumento->toArray();
        $this->assertModelData($documento->toArray(), $dbDocumento);
    }

    /**
     * @test update
     */
    public function test_update_documento()
    {
        $documento = Documento::factory()->create();
        $fakeDocumento = Documento::factory()->make()->toArray();

        $updatedDocumento = $this->documentoRepo->update($fakeDocumento, $documento->id);

        $this->assertModelData($fakeDocumento, $updatedDocumento->toArray());
        $dbDocumento = $this->documentoRepo->find($documento->id);
        $this->assertModelData($fakeDocumento, $dbDocumento->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_documento()
    {
        $documento = Documento::factory()->create();

        $resp = $this->documentoRepo->delete($documento->id);

        $this->assertTrue($resp);
        $this->assertNull(Documento::find($documento->id), 'Documento should not exist in DB');
    }
}
