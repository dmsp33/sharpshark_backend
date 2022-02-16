<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CopyLeaksScan;

class CopyLeaksResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'copy_leaks_scan_id',
        'url',
        'title',
        'introduction',
        'matchedWords',
        'plagarism',
	'totalWords',
    ];

    protected $hidden = 'matchedWords';

    public function copyLeaksScan()
    {
        return $this->belongsTo(CopyLeaksScan::class);
    }
}