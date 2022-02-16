<?php

namespace App\Repositories;

use App\Models\Certificado;
use App\Repositories\BaseRepository;

/**
 * Class CertificadoRepository
 * @package App\Repositories
 * @version May 30, 2021, 11:59 am UTC
*/

class CertificadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'version',
        'autor',
        'titulo',
        'contenido',
        'uuid',
        'red',
        'traza',
        'ipfs',
        'clave',
        'bloqueado'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Certificado::class;
    }
}
