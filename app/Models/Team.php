<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $tla
 * @property string|null $shortname
 * @property string|null $crest
 * @property string|null $address
 * @property string|null $website
 * @property int|null $founded
 * @property string|null $club_colors
 * @property string|null $venue
 * @property string|null $last_update
 * @property int|null $area_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Area|null $area
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Game> $awayMatches
 * @property-read int|null $away_matches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competition> $competitions
 * @property-read int|null $competitions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Game> $homeMatches
 * @property-read int|null $home_matches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competition> $runningCompetitions
 * @property-read int|null $running_competitions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereClubColors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCrest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereFounded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereLastUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereShortname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereTla($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereVenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereWebsite($value)
 * @mixin \Eloquent
 */
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

    public function homeMatches()
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }

    /**
     * @return Builder<Game>
     * @throws InvalidArgumentException
     */
    public function allMatches()
    {
        return Game::where('home_team_id', $this->id)
            ->orWhere('away_team_id', $this->id);
    }

    public function pastMatches()
    {
        return Game::where(function ($query) {
            $query->where('home_team_id', $this->id)
                ->orWhere('away_team_id', $this->id);
        })
            ->where('utc_date', '<', now())
            ->where('status', 'FINISHED')
            ->orderBy('utc_date', 'desc');
    }

    public function upcomingMatches()
    {
        return Game::where(function ($query) {
            $query->where('home_team_id', $this->id)
                ->orWhere('away_team_id', $this->id);
        })
            ->where('utc_date', '>=', now());
    }
    public static function sync(array $team)
    {
        static::updateOrCreate(
            ['id' => $team['id']],
            [
                'name' => $team['name'],
                'shortname' => $team['shortName'],
                'tla' => $team['tla'],
                'crest' => $team['crest'],
                'address' => $team['address'],
                'website' => $team['website'],
                'founded' => $team['founded'],
                'club_colors' => $team['clubColors'],
                'venue' => $team['venue'],
                'last_update' => $team['lastUpdated'],
                'area_id' => $team['area']['id']
            ]

        );
    }
}
