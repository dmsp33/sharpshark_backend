<?php namespace Tests\Repositories;

use App\Models\Certificado;
use App\Repositories\CertificadoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CertificadoRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CertificadoRepository
     */
    protected $certificadoRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->certificadoRepo = \App::make(CertificadoRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_certificado()
    {
        $certificado = Certificado::factory()->make()->toArray();

        $createdCertificado = $this->certificadoRepo->create($certificado);

        $createdCertificado = $createdCertificado->toArray();
        $this->assertArrayHasKey('id', $createdCertificado);
        $this->assertNotNull($createdCertificado['id'], 'Created Certificado must have id specified');
        $this->assertNotNull(Certificado::find($createdCertificado['id']), 'Certificado with given id must be in DB');
        $this->assertModelData($certificado, $createdCertificado);
    }

    /**
     * @test read
     */
    public function test_read_certificado()
    {
        $certificado = Certificado::factory()->create();

        $dbCertificado = $this->certificadoRepo->find($certificado->id);

        $dbCertificado = $dbCertificado->toArray();
        $this->assertModelData($certificado->toArray(), $dbCertificado);
    }

    /**
     * @test update
     */
    public function test_update_certificado()
    {
        $certificado = Certificado::factory()->create();
        $fakeCertificado = Certificado::factory()->make()->toArray();

        $updatedCertificado = $this->certificadoRepo->update($fakeCertificado, $certificado->id);

        $this->assertModelData($fakeCertificado, $updatedCertificado->toArray());
        $dbCertificado = $this->certificadoRepo->find($certificado->id);
        $this->assertModelData($fakeCertificado, $dbCertificado->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_certificado()
    {
        $certificado = Certificado::factory()->create();

        $resp = $this->certificadoRepo->delete($certificado->id);

        $this->assertTrue($resp);
        $this->assertNull(Certificado::find($certificado->id), 'Certificado should not exist in DB');
    }
}
