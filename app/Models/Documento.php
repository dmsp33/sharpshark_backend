<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Alerta;

/**
 * @SWG\Definition(
 *      definition="Documento",
 *      required={"user_id", "version", "titulo", "contenido", "uuid", "protegido", "bloqueado"},
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
 *          property="protegido",
 *          description="protegido",
 *          type="boolean"
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
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Documento extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'documentos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'version',
        'titulo',
        'contenido',
        'uuid',
        'protegido',
        'bloqueado',
        'monitoring',
        'family'
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
        'titulo' => 'string',
        'contenido' => 'string',
        'uuid' => 'string',
        'protegido' => 'boolean',
        'bloqueado' => 'boolean',
        'monitoring' => 'boolean',
        'family' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'user_id' => 'required',
        // 'version' => 'required',
        // 'titulo' => 'required|string',
        // 'contenido' => 'required|string',
        // 'uuid' => 'nullable|string|max:255',
        // 'protegido' => 'required|boolean',
        // 'bloqueado' => 'required|boolean',
        // 'monitoring' => 'nullable|boolean',
        // 'created_at' => 'nullable',
        // 'updated_at' => 'nullable',
        // 'deleted_at' => 'nullable',
        // 'family' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function alerts()
    {
        return $this->hasMany(Alerta::class);
    }
}
