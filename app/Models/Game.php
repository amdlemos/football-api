<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $area_id
 * @property int $season_id
 * @property int $competition_id
 * @property int $home_team_id
 * @property int $away_team_id
 * @property string $utc_date
 * @property string $status
 * @property string|null $minute
 * @property int|null $injury_time
 * @property string|null $venue
 * @property int $matchday
 * @property string|null $stage
 * @property string $last_update
 * @property string|null $winner
 * @property string $duration
 * @property int|null $home_score_full_time
 * @property int|null $away_score_full_time
 * @property int|null $home_score_half_time
 * @property int|null $away_score_half_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $awayTeam
 * @property-read \App\Models\Competition $competition
 * @property-read \App\Models\Team $homeTeam
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereAwayScoreFullTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereAwayScoreHalfTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereAwayTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereCompetitionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereHomeScoreFullTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereHomeScoreHalfTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereHomeTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereInjuryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereLastUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereMatchday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereUtcDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereVenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Game whereWinner($value)
 * @mixin \Eloquent
 */
class Game extends Model
{
    protected $fillable = [
        'id',
        'utc_date',
        'status',
        'minute',
        'injury_time',
        'venue',
        'matchday',
        'stage',
        'last_update',
        'winner',
        'duration',
        'home_score_full_time',
        'away_score_full_time',
        'home_score_half_time',
        'away_score_half_time',
        'area_id',
        'season_id',
        'competition_id',
        'home_team_id',
        'away_team_id',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
