<?php

namespace App\Http\Resources\API;

use App\Helpers\SharpTools;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => SharpTools::groupByDateRange($this->collection),
        ];
    }
}
