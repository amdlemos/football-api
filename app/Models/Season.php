<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @package App\Models */
class Season extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'competition_id', 'start_date', 'end_date', 'current_matchday', 'current_season_id'];
}
