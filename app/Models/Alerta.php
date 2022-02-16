<?php

namespace App\Models;

use App\Models\User;
use App\Models\Documento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Alerta",
 *      required={"title" , "content" , "url" , "user_id", "actual"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="content",
 *          description="content",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="url",
 *          description="url",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="actual",
 *          description="actual",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="documento_id",
 *          description="documento_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      
 * )
 */

class Alerta extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = ['title' , 'content' , 'url' , 'user_id', 'actual' , 'documento_id', 'reviewed'];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'title' => 'string',
        'content' => 'string',
        'url' => 'string',
        'actual' => 'boolean',
        'documento_id' => 'integer',
        'reviewed' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'title' => 'required|string',
        'content' => 'required|string',
        'url' => 'required|string',
        'actual' => 'required|boolean',
        'documento_id' => 'nullable'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Documento::class);
    }
}
