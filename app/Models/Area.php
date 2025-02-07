<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @package App\Models */
class Area extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'code', 'flag'];

    // public function competitions()
    // {
    //     return $this->hasMany(Competition::class);
    // }
}
