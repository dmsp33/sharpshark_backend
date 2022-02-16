<?php

namespace App\Repositories;

use App\Models\Documento;
use App\Repositories\BaseRepository;

/**
 * Class DocumentoRepository
 * @package App\Repositories
 * @version May 30, 2021, 11:58 am UTC
*/

class DocumentoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'version',
        'titulo',
        'contenido',
        'uuid',
        'protegido',
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
        return Documento::class;
    }
}
