<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * Game model
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

    /**
     * Get the competition associated with the game.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Get the home team associated with the game.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    /**
     * Get the away team associated with the game.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    /**
     * Synchronize the game data with the provided match information.
     *
     * @param array $match The match data to synchronize with.
     * @return void
     */
    public static function sync(array $match)
    {
        static::updateOrCreate(
            ['id' => $match['id']],
            [
                // 'utc_date' => $match['utcDate'],
                'utc_date' => Carbon::parse($match['utcDate'])->format('Y-m-d H:i:s'),
                'status' => $match['status'],
                // 'minute' => $match['minute'],
                // 'injury_time' => $match['injuryTime'],
                // 'venue' => $match['venue'],
                'matchday' => $match['matchday'],
                'stage' => $match['stage'],
                'last_update' => Carbon::parse($match['lastUpdated'])->format('Y-m-d H:i:s'),
                'duration' => $match['score']['duration'],
                'winner' => $match['score']['winner'],
                'home_score_full_time' => $match['score']['fullTime']['home'],
                'away_score_full_time' => $match['score']['fullTime']['away'],
                'home_score_half_time' => $match['score']['halfTime']['home'],
                'away_score_half_time' => $match['score']['halfTime']['away'],
                'area_id' => $match['area']['id'],
                'season_id' => $match['season']['id'],
                'competition_id' => $match['competition']['id'],
                'home_team_id' => $match['homeTeam']['id'],
                'away_team_id' => $match['awayTeam']['id'],
            ]
        );
    }

    /**
     * Scope to filter the query results to include upcoming matches for a specific team.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param int $teamId The team ID to filter the upcoming matches by.
     * @return \Illuminate\Database\Eloquent\Builder The query builder with the upcoming matches filter applied.
     */
    public function scopeUpcomingMatchesByTeam($query, $teamId)
    {
        return $query->where(function () use ($teamId, $query) {
            $query->where('away_team_id', $teamId)
                ->orWhere('home_team_id', $teamId);
        })
            ->whereDate('utc_date', '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('utc_date', 'asc');
    }

    /**
     * Scope to filter the query results by the competition ID.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param int $competitionId The competition ID to filter by.
     * @return \Illuminate\Database\Eloquent\Builder The query builder with the competition ID filter applied.
     */
    public function scopeOfCompetition($query, $competitionId)
    {
        return $query->where('competition_id', $competitionId);
    }

    /**
     * Scope to filter the query results to include only finished games.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @return \Illuminate\Database\Eloquent\Builder The query builder with the 'finished' status filter applied.
     */
    public function scopeFinished($query)
    {
        return $query->where('status', 'FINISHED');
    }

    /**
     * Scope to filter the query results by a date range for the 'utc_date' field.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string $startDate The start date of the range.
     * @param string $endDate The end date of the range.
     * @return \Illuminate\Database\Eloquent\Builder The query builder with the date range filter applied.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereDate('utc_date', '<=', $endDate)
            ->whereDate('utc_date', '>=', $startDate);
    }

    /**
     * Scope to order the query results by the 'utc_date' field.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string $direction The direction of the order ('asc' or 'desc'). Default is 'desc'.
     * @return \Illuminate\Database\Eloquent\Builder The query builder with the order applied.
     */
    public function scopeOrderByDate($query, $direction = 'desc')
    {
        return $query->orderBy('utc_date', $direction);
    }


    /**
     * Retrieves finished games within a specified date range for a given competition.
     *
     * @param int $competitionId The ID of the competition.
     * @param string $startDate The start date of the range.
     * @param string $endDate The end date of the range.
     * @return \Illuminate\Database\Eloquent\Collection The collection of finished games within the date range.
     */
    public static function getFinishedBetweenDates($competitionId, $startDate, $endDate)
    {
        return static::ofCompetition($competitionId)
            ->finished()
            ->betweenDates($startDate, $endDate)
            ->orderByDate()
            ->get();
    }

    /**
     * Retrieves games within a specified date range for a given competition.
     *
     * @param int $competitionId The ID of the competition.
     * @param string $startDate The start date of the range.
     * @param string $endDate The end date of the range.
     * @return \Illuminate\Database\Eloquent\Collection The collection of games within the date range.
     */
    public static function getBetweenDates($competitionId, $startDate, $endDate)
    {
        return static::ofCompetition($competitionId)
            ->betweenDates($startDate, $endDate)
            ->orderByDate()
            ->get();
    }
}
