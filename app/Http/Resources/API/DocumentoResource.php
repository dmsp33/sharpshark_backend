<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DocumentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'version' => $this->version,
            'titulo' => $this->titulo,
            'contenido' => Str::limit($this->contenido, 100, '...'),
            'uuid' => $this->uuid,
            'protegido' => $this->protegido,
            'bloqueado' => $this->bloqueado,
            //'protected_at' => $this->protected_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
