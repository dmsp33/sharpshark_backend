<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Certificado;

class CertificadoApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_certificado()
    {
        $certificado = Certificado::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/certificados', $certificado
        );

        $this->assertApiResponse($certificado);
    }

    /**
     * @test
     */
    public function test_read_certificado()
    {
        $certificado = Certificado::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/certificados/'.$certificado->id
        );

        $this->assertApiResponse($certificado->toArray());
    }

    /**
     * @test
     */
    public function test_update_certificado()
    {
        $certificado = Certificado::factory()->create();
        $editedCertificado = Certificado::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/certificados/'.$certificado->id,
            $editedCertificado
        );

        $this->assertApiResponse($editedCertificado);
    }

    /**
     * @test
     */
    public function test_delete_certificado()
    {
        $certificado = Certificado::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/certificados/'.$certificado->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/certificados/'.$certificado->id
        );

        $this->response->assertStatus(404);
    }
}
