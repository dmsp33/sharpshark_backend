<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Certificado",
 *      required={"user_id", "version", "autor", "titulo", "contenido", "uuid", "red", "traza", "ipfs", "bloqueado"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="version",
 *          description="version",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="autor",
 *          description="autor",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="titulo",
 *          description="titulo",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="contenido",
 *          description="contenido",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="uuid",
 *          description="uuid",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="red",
 *          description="red",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="traza",
 *          description="traza",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="ipfs",
 *          description="ipfs",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="clave",
 *          description="clave",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="bloqueado",
 *          description="bloqueado",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Certificado extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'certificados';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'version' => 'integer',
        'autor' => 'string',
        'titulo' => 'string',
        'contenido' => 'string',
        'uuid' => 'string',
        'red' => 'string',
        'traza' => 'string',
        'ipfs' => 'string',
        'clave' => 'string',
        'bloqueado' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'version' => 'required',
        'autor' => 'required|string|max:255',
        'titulo' => 'required|string',
        'contenido' => 'required|string',
        'uuid' => 'required|string|max:255',
        'red' => 'required|string|max:255',
        'traza' => 'nullable|string|max:255',
        'ipfs' => 'nullable|string|max:255',
        'clave' => 'nullable|string|max:255',
        'bloqueado' => 'required|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // public function getContenidoAttribute()
    // {
    //     if ($this->bloqueado) {
    //         return '';
    //     }

    //     return $this->contenido;
    // }
}
