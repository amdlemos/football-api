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
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    /** @return BelongsTo<Season>  */
    public function currentSeason()
    {
        return $this->belongsTo(Season::class, 'current_season_id', 'id');
    }

    /** @return HasMany<Season, Competition>  */
    public function seasons()
    {
        return $this->hasMany(Season::class);
    }
}
