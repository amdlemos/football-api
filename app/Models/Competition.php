<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @package App\Models */
class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'area_id',
        'name',
        'code',
        'type',
        'emblem',
        'plan',
        'current_season_id'
    ];

    /** @return BelongsTo<Area>  */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /** @return BelongsTo<Season>  */
    public function currentSeason()
    {
        return $this->belongsTo(Season::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }
}
