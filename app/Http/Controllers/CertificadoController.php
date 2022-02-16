<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCertificadoRequest;
use App\Http\Requests\UpdateCertificadoRequest;
use App\Repositories\CertificadoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CertificadoController extends AppBaseController
{
    /** @var  CertificadoRepository */
    private $certificadoRepository;

    public function __construct(CertificadoRepository $certificadoRepo)
    {
        $this->certificadoRepository = $certificadoRepo;
    }

    /**
     * Display a listing of the Certificado.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $certificados = $this->certificadoRepository->paginate(20);

        return view('certificados.index')
            ->with('certificados', $certificados);
    }

    /**
     * Show the form for creating a new Certificado.
     *
     * @return Response
     */
    public function create()
    {
        return view('certificados.create');
    }

    /**
     * Store a newly created Certificado in storage.
     *
     * @param CreateCertificadoRequest $request
     *
     * @return Response
     */
    public function store(CreateCertificadoRequest $request)
    {
        $input = $request->all();

        $certificado = $this->certificadoRepository->create($input);

        Flash::success('Certificado saved successfully.');

        return redirect(route('certificados.index'));
    }

    /**
     * Display the specified Certificado.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $certificado = $this->certificadoRepository->find($id);

        if (empty($certificado)) {
            Flash::error('Certificado not found');

            return redirect(route('certificados.index'));
        }

        return view('certificados.show')->with('certificado', $certificado);
    }

    /**
     * Show the form for editing the specified Certificado.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $certificado = $this->certificadoRepository->find($id);

        if (empty($certificado)) {
            Flash::error('Certificado not found');

            return redirect(route('certificados.index'));
        }

        return view('certificados.edit')->with('certificado', $certificado);
    }

    /**
     * Update the specified Certificado in storage.
     *
     * @param int $id
     * @param UpdateCertificadoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCertificadoRequest $request)
    {
        $certificado = $this->certificadoRepository->find($id);

        if (empty($certificado)) {
            Flash::error('Certificado not found');

            return redirect(route('certificados.index'));
        }

        $certificado = $this->certificadoRepository->update($request->all(), $id);

        Flash::success('Certificado updated successfully.');

        return redirect(route('certificados.index'));
    }

    /**
     * Remove the specified Certificado from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $certificado = $this->certificadoRepository->find($id);

        if (empty($certificado)) {
            Flash::error('Certificado not found');

            return redirect(route('certificados.index'));
        }

        $this->certificadoRepository->delete($id);

        Flash::success('Certificado deleted successfully.');

        return redirect(route('certificados.index'));
    }
}
