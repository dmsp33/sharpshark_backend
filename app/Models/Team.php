<?php

namespace App\Models;

use App\Models\Traits\Team as TraitsTeam;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model ;


class Team extends Model
{
    use SoftDeletes;
    use HasFactory;
    use TraitsTeam;

    public $table = 'teams';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'name',
        'personal_team',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string',
        'personal_team' => 'required|boolean',
    ];

    
}
