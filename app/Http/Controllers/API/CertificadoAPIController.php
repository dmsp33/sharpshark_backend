<?php

namespace App\Http\Controllers\API;

use PDF;
use Response;
use App\Models\Documento;
use App\Models\Certificado;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Cloutier\PhpIpfsApi\IPFS;
use App\Exports\CertificatesExport;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AppBaseController;
use App\Repositories\CertificadoRepository;
use App\Http\Requests\API\CreateCertificadoAPIRequest;
use App\Http\Requests\API\UpdateCertificadoAPIRequest;
use App\Models\BlockchainNFT;

/**
 * Class CertificadoController
 * @package App\Http\Controllers\API
 */

class CertificadoAPIController extends AppBaseController
{
    /** @var  CertificadoRepository */
    private $certificadoRepository;

    public function __construct(CertificadoRepository $certificadoRepo)
    {
        $this->certificadoRepository = $certificadoRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/certificados",
     *      summary="Get a listing of the Certificates.",
     *      tags={"Certificado"},
     *      description="Get all Certificates",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Certificado")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
      
        $certficados = Certificado::where('user_id' , $request->user()->id)->orderBy('id' , 'desc')->paginate(30);
        return response()->json(['data' => $certficados->toArray(), 'message' => 'Certificates retrieved successfully']);
    }

    /**
     * @param CreateCertificadoAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/certificados",
     *      summary="Store a newly created Certificate in storage",
     *      tags={"Certificado"},
     *      description="Store Certificate",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Certificado that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Certificado")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Certificado" 
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateCertificadoAPIRequest $request)
    {

        $certificado = $this->certificadoRepository->create( $request->validated());

        Documento::where('uuid', $certificado->uuid)->update([
            'protegido' => true,
            'monitoring' => true
        ]);

        try {
            /**
             * Send to IPFS
             */
            $ipfs = new IPFS(config('ipfs.ipfs.base_url'));
            $pdf = PDF::loadView('certificados.showpdf', compact('certificado'))->download('archivo.pdf');
            if ($request->bloqueado) {
                $ruta = storage_path('app');
                $nombreTemporal = Str::random();
                $owner = Str::random();
                $user = Str::random();

                Storage::put($nombreTemporal. ".pdf", $pdf->content());

                exec("pdftk $ruta/{$nombreTemporal}.pdf output $ruta/{$nombreTemporal}-clave.pdf owner_pw $owner user_pw $user");
                $fileHash = $ipfs->add( Storage::get("{$nombreTemporal}-clave.pdf") );
                Storage::delete(["{$nombreTemporal}.pdf", "{$nombreTemporal}-clave.pdf"]);
                $certificado->update([
                    'ipfs' =>  $fileHash,
                    'clave' => $owner ?? null,
                    // 'traza' => $traza
                ]);
            } else {
                $fileHash = $ipfs->add($pdf->content());

                $certificado->update([
                    'ipfs' =>  $fileHash,
                    // 'traza' => $traza
                ]);
            }

            /**
             * Send to blockchain
             */

            $traza = ( new BlockchainNFT())->send($certificado->red, $fileHash);

            /**
             * Update fields IPFS and Blockchain to Certificate
             */        
            $certificado->update([
                'traza' => $traza
            ]);
        
            return response()->json(['data' => $certificado->toArray()]);

        } catch (\Exception $e) {
            return response()->json(['data' => '', 'error' => $e->getMessage()]);
        }
        
    }

    /**
     * @param int $uuid
     * @return Response
     *
     * @SWG\Get(
     *      path="/certificados/{uuid}",
     *      summary="Display the specified Certificate",
     *      tags={"Certificado"},
     *      description="Get Certificate",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="uuid",
     *          description="uuid of certificate",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Certificado")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($uuid)
    {
        /** @var Certificado $certificado */
        $certificado = Certificado::where('uuid', $uuid)->orderBy('version', 'DESC')->get();

        if (empty($certificado)) {
            return $this->sendError('Certificado not found');
        }

        return $this->sendResponse($certificado->unique('version')->toArray(), 'Certificado retrieved successfully');


    }

    public function export($uuid) 
    {
        $certificado = Certificado::where('uuid', $uuid)->first();


        return PDF::loadView('certificados.showpdf', compact('certificado'))
            ->stream('archivo.pdf');
    }


    /**
     * @param int $uuid
     * @param UpdateCertificadoAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/certificados/{uuid}",
     *      summary="Update the specified Certificates in storage",
     *      tags={"Certificado"},
     *      description="Update Certificate",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="uuid",
     *          description="uuid of certificate",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Certificates that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Certificado")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Certificado"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateCertificadoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Certificado $certificado */
        $certificado = $this->certificadoRepository->find($id);

        if (empty($certificado)) {
            return $this->sendError('Certificado not found');
        }

        $certificado = $this->certificadoRepository->update($input, $id);

        return $this->sendResponse($certificado->toArray(), 'Certificado updated successfully');
    }

    /**
     * @param int $uuid
     * @return Response
     *
     * @SWG\Delete(
     *      path="/certificados/{uuid}",
     *      summary="Remove the specified Certificates from storage",
     *      tags={"Certificado"},
     *      description="Delete Certificates",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="uuid",
     *          description="uuid of certificate",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Certificado $certificado */
        $certificado = $this->certificadoRepository->find($id);

        if (empty($certificado)) {
            return $this->sendError('Certificado not found');
        }

        $certificado->delete();

        return $this->sendSuccess('Certificado deleted successfully');
    }

    public function uploadImages(Request $request)
    {
        $ipfs = new IPFS(config('ipfs.ipfs.base_url'));
   
        try {
            $validated = $request->validate([
                'image' => 'mimes:jpeg,png|max:1014'
            ]);

            $url = $ipfs->add(file_get_contents($validated['image']));
            return ['url' => 'https://ipfs.io/ipfs/'.$url];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
