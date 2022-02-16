<?php

namespace App\Http\Controllers\API;

use Response;
use App\Models\Documento;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Repositories\DocumentoRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateDocumentoAPIRequest;
use App\Http\Requests\API\UpdateDocumentoAPIRequest;
use App\Http\Resources\API\DocumentoCollection;

/**
 * Class DocumentoController
 * @package App\Http\Controllers\API
 */

class DocumentoAPIController extends AppBaseController
{
    /** @var  DocumentoRepository */
    private $documentoRepository;

    public function __construct(DocumentoRepository $documentoRepo)
    {
        $this->documentoRepository = $documentoRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/documentos",
     *      summary="Get a listing of the Documents.",
     *      tags={"Documento"},
     *      description="Get all Documents",
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
     *                  @SWG\Items(ref="#/definitions/Documento")
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
        $documentos = Documento::where('user_id', $request->user()->id)
            ->where('protegido', false)
            ->orderBy('id', 'desc')->paginate(30);
        return new DocumentoCollection($documentos);
        // return response()->json(['data' => $documentos->toArray(), 'message' => 'Documents retrieved successfully']);
    }

    /**
     * @param CreateDocumentoAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/documentos",
     *      summary="Store a newly created Document in storage",
     *      tags={"Documento"},
     *      description="Store Document",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Document that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Documento")
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
     *                  ref="#/definitions/Documento"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateDocumentoAPIRequest $request)
    {
        if (isset($request->session)) {
            session([
                'documento' => $request->except('session'),
            ]);

            return session()->all();
        }

        $input = $request->except('user_id', 'uuid' , 'family');
        $documento = Documento::create( array_merge($input, ['user_id' => $request->user_id, 'uuid' => Str::uuid() , 'family' => Str::random(128)]));
        return $this->sendResponse($documento->toArray(), 'Document saved successfully');
    }

    /**
     * @param int $uuid
     * @return Response
     *
     * @SWG\Get(
     *      path="/documentos/{uuid}",
     *      summary="Display the specified Document",
     *      tags={"Documento"},
     *      description="Get Document",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="uuid",
     *          description="uuid of Documento",
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
     *                  ref="#/definitions/Documento"
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
        /** @var Documento $documento */
        $documento = Documento::where('uuid', $uuid)->first();

        if (empty($documento)) {
            return $this->sendError('Document not found');
        }

        return $this->sendResponse($documento->toArray(), 'Document retrieved successfully');
    }

    /**
     * @param int $uuid
     * @param UpdateDocumentoAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/documentos/{uuid}",
     *      summary="Update the specified Document in storage",
     *      tags={"Documento"},
     *      description="Update Document",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="uuid",
     *          description="uuid of Document",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Document that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Documento")
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
     *                  ref="#/definitions/Documento"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($uuid , Request $request)
    {
        
        $input = $request->all();

        /** @var Documento $documento */
        
        $documento = Documento::where('uuid', $uuid)->first();
        if (empty($documento)) {
            return $this->sendError('Documento not found');
        }

        $documento->update($input);

        return $this->sendResponse($documento->toArray(), 'Documento updated successfully');

    }

    public function update_version($uuid , Request $request)
    {
        $input = $request->all();
        
        $documento = Documento::where('uuid', $uuid)->first();
        $update = $documento->update([
            'update_at' => now()
        ]);


        if(empty($documento->family)){
            $documento->family =  Str::random(128);
            $documento->save();
        }

        $documento = Documento::where('family', $documento->family)->get()->last();


        $documento = Documento::create( array_merge($input, ['user_id' => $request->user_id, 'uuid' => Str::uuid() , 'family' => $documento->family, 'version' => $documento->version + 1]));
        return $this->sendResponse($documento->toArray(), 'Documento updated successfully');

        
    }

    /**
     * @param int $uuid
     * @return Response
     *
     * @SWG\Delete(
     *      path="/documentos/{uuid}",
     *      summary="Remove the specified Document from storage",
     *      tags={"Documento"},
     *      description="Delete Document",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="uuid",
     *          description="uuid of Document",
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
    public function destroy($uuid)
    {
        /** @var Documento $documento */
        $documento = Documento::where('uuid', $uuid);

        if (empty($documento)) {
            return $this->sendError('Document not found');
        }

        $documento->delete();
        
        return response()->json('Document deleted successfully');
        return $this->sendSuccess('Document deleted successfully');
    }
    
    
    
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/documentos-eliminados",
     *      summary="Get a listing of the deleted Documents.",
     *      tags={"Documentos eliminados"},
     *      description="Get all deleted Documents",
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
     *                  @SWG\Items(ref="#/definitions/Documento")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function deleted(Request $request)
    {
        $documentos = Documento::onlyTrashed()->where('user_id', $request->user()->id)->orderBy('id', 'desc')->paginate(30);
        return new DocumentoCollection($documentos);
        // return response()->json(['data' => $documentos->toArray(), 'message' => 'Documents retrieved successfully']);
    }
    
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Delete(
     *      path="/documentos-eliminados",
     *      summary="Force document deletion",
     *      tags={"Documentos eliminados"},
     *      description="Get all deleted Documents",
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
     *                  @SWG\Items(ref="#/definitions/Documento")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function forceDestroy($uuid)
    {
        /** @var Documento $documento */
        $documento = Documento::onlyTrashed()->where('uuid', $uuid)->first();

        if (empty($documento)) {
            return $this->sendError('Document not found');
        }
        
        $documento->forceDelete();
        
        return response()->json('Document force deleted successfully');
    }
    
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Delete(
     *      path="/documentos-restaurar",
     *      summary="Restore Document",
     *      tags={"Documentos eliminados"},
     *      description="Get all deleted Documents",
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
     *                  @SWG\Items(ref="#/definitions/Documento")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function restore($uuid)
    {
        /** @var Documento $documento */
        $documento = Documento::onlyTrashed()->where('uuid', $uuid)->first();

        if (empty($documento)) {
            return $this->sendError('Document not found');
        }
        
        $documento->restore();
        
        return response()->json('Document restore successfully');
    }


    public function protectedTracking(Request $request)
    {
        $documentos = Documento::where('user_id', $request->user()->id)
            ->where('protegido', true)
            ->where('monitoring', true)
            ->orderBy('id', 'desc')->paginate(30);
        return new DocumentoCollection($documentos);
    }
    public function protectedNotTracking(Request $request)
    {
        $documentos = Documento::where('user_id', $request->user()->id)
            ->where('protegido', true)
            ->where('monitoring', false)
            ->orderBy('id', 'desc')->paginate(30);
        return new DocumentoCollection($documentos);
    }

    public function wfprotectedTracking(Request $request)
    {
        $documentos = Documento::where('user_id', $request->user()->id)
            ->where('protegido', true)
            ->where('monitoring', true)
            ->orderBy('id', 'desc')->get();
        return response()->json($documentos->toArray());
    }
    public function wfprotectedNotTracking(Request $request)
    {
        $documentos = Documento::where('user_id', $request->user()->id)
            ->where('protegido', true)
            ->where('monitoring', false)
            ->orderBy('id', 'desc')->get();
        return response()->json($documentos->toArray());
    }
}
