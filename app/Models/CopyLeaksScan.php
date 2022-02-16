<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CopyLeaksResponse;

class CopyLeaksScan extends Model
{
  use HasFactory;

  protected $fillable = [
    'scan_id',
    'title',
    'body',
    'user_id',
    'audited',
    'documento_id'
  ];

  public function copyLeaksResponse()
  {
    return $this->hasMany(CopyLeaksResponse::class);
  }
}