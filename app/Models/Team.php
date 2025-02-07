<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'id',
        'name',
        'shortname',
        'tla',
        'crest',
        'address',
        'website',
        'founded',
        'clubColors',
        'venue',
        'area_id',
        'coach_id',
        'lastUpdate'
    ];

    /** @return BelongsTo<Area>  */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /** @return HasMany<Season, Competition>  */
    public function runningCompetitions()
    {
        return $this->hasMany(Competition::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class);
    }
}
