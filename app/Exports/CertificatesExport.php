<?php

namespace App\Exports;

use App\Models\Certificado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;

class CertificatesExport implements FromView
{

    use Exportable;
    /**
    * 
    */
    public function view(): View 
    {
      $certficado = Certificado::first();

        return view('certificados.showpdf', [
            'certificado' => $certficado
        ]);
    }
}
