<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @package App\Models
 * @property int $id
 * @property int $area_id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property string|null $emblem
 * @property string $plan
 * @property int|null $current_season_id
 * @property int|null $current_matchday
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Area $area
 * @property-read \App\Models\Season|null $currentSeason
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereCurrentMatchday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereCurrentSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereEmblem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
