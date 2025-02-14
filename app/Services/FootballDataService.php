<?php

namespace App\Services;

use App\Http\Integrations\FootballApi;
use App\Models\Area;
use App\Models\Competition;
use App\Models\Game;
use App\Models\Team;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use LogicException;
use RuntimeException;

/**
 * Football Data Service
 */
class FootballDataService
{
    protected FootballApi $footballApi;

    private const CACHE_TTL = 180;

    private const MIN_TEAMS_PER_COMPETITION = 10;

    /**
     * Initializes the FootballDataService class.
     *
     * @return void  */
    public function __construct()
    {
        $this->footballApi = new FootballApi();
    }

    /**
     * Retrieves a list of available competitions.
     *
     * @return Collection  */
    public function getCompetitions(): Collection
    {
        return Cache::remember('competitions', self::CACHE_TTL, function () {
            $dbCompetitions = Competition::getLeaguesWithRelations();

            if ($dbCompetitions->isNotEmpty()) {
                return $dbCompetitions;
            }

            $apiCompetitions = $this->footballApi->fetchCompetitions();


            Competition::syncFromApi($apiCompetitions);

            return Competition::getLeaguesWithRelations();
        });
    }

    /**
     * Retrieves a competition along with its associated teams.
     *
     * @param string $code
     * @return null|Competition
     */
    public function getCompetitionTeams(string $code): ?Competition
    {
        $cacheKey = "competition_{$code}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($code) {
            $dbCompetition = Competition::getCompetitionWithTeams($code);

            if ($dbCompetition) {
                return $this->hasEnoughTemas($dbCompetition);
            }

            $dbCompetitions = $this->getCompetitions();
            $dbCompetition = $dbCompetitions->getCompetitionWithTeams($code);

            return $this->hasEnoughTemas($dbCompetition);
        });
    }

    /**
     * Retrieves all previous games for a given competition, from the start of the current season up to today.
     *
     * @param string $competitionCode
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function getPreviousGamesByCompetition(string $competitionCode)
    {
        $competition = Competition::where('code', $competitionCode)->firstOrFail();
        $today = Carbon::now()->format('Y-m-d');
        $startDate = $competition->currentSeason->start_date;
        $cacheKey = "previousMatches_{$competitionCode}_{$startDate}_{$today}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($competition, $today, $startDate) {
            $dbMatches = Game::getFinishedBetweenDates($competition->id, $startDate, $today);

            if ($dbMatches->isNotEmpty()) {
                return $dbMatches;
            }

            $apiCompetition = $this->footballApi->fetchCompetitionMatches($competition->code, $startDate, $today);

            foreach ($apiCompetition['matches'] as $match) {
                Game::sync($match);
            }

            return Game::getFinishedBetweenDates($competition->id, $startDate, $today);
        });
    }

    /**
     * Retrieves all upcoming games for a given competition, from today until the end of the current season.
     *
     * @param string $competitionCode
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function getUpcomingGamesByCompetition(string $competitionCode)
    {
        $competition = Competition::where('code', $competitionCode)->firstOrFail();
        $today = Carbon::now()->format('Y-m-d');
        $endDate = $competition->currentSeason->end_date;
        $cacheKey = "upcomingMatches_{$competitionCode}_{$today}_{$endDate}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($competition, $today, $endDate) {
            $dbMatches = Game::getBetweenDates($competition->id, $today, $endDate, 'asc');

            if ($dbMatches->isNotEmpty()) {
                return $dbMatches;
            }

            return $this->fetchAndSyncMatches($competition, $today, $endDate, 'asc');
        });
    }

    /**
     * Retrieves all upcoming games for a given team, from today until the end of the current season.
     *
     * @param string $teamId
     * @return mixed
     */
    public function getUpcomingGamesByTeam(string $teamId)
    {
        $today = Carbon::now()->format('Y-m-d');
        $cacheKey = "upcoming-matches-{$teamId}-{$today}";

        return Cache::remember(
            $cacheKey,
            self::CACHE_TTL,
            function () use ($teamId) {
                $dbMatches = Game::upcomingMatchesByTeam($teamId)->get();

                if ($dbMatches->isNotEmpty()) {
                    return $dbMatches;
                }

                $apiMatches = $this->footballApi->fetchMatchesByTeam($teamId);

                foreach ($apiMatches['matches'] as $match) {
                    Game::sync($match);
                }

                $dbMatches = Game::upcomingMatchesByTeam($teamId)->get();

                return $dbMatches;
            }
        );
    }

    /**
     * * Retrieves all previous games for a given team from the database.
     * If no matches are found, it fetches data from the external API and syncs it.
     * The results are cached for optimized performance.
     *
     * @param string $teamId
     * @return mixed
     */
    public function getPreviousGamesByTeam(string $teamId)
    {
        $today = Carbon::now()->format('Y-m-d');
        $cacheKey = "upcoming-matches-{$teamId}-{$today}";

        return Cache::remember(
            $cacheKey,
            self::CACHE_TTL,
            function () use ($teamId) {
                $dbMatches = Game::previousMatchesByTeam($teamId)->get();

                if ($dbMatches->isNotEmpty()) {
                    return $dbMatches;
                }

                $apiMatches = $this->footballApi->fetchMatchesByTeam($teamId);

                foreach ($apiMatches['matches'] as $match) {
                    Game::sync($match);
                }

                $dbMatches = Game::previousMatchesByTeam($teamId)->get();

                return $dbMatches;
            }
        );
    }

    /**
     * Checks if the given competition has at least 10 teams.
     * If not, it triggers a synchronization request to the API to update the data.
     *
     * @param Competition $dbCompetition
     * @return null|Competition
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function hasEnoughTemas(Competition $dbCompetition)
    {
        $hasEnoughTeams = $dbCompetition->teams()
            ->select('id')
            ->limit(self::MIN_TEAMS_PER_COMPETITION)
            ->count() >= self::MIN_TEAMS_PER_COMPETITION;

        if (! $hasEnoughTeams) {
            $apiCompetition = $this->footballApi->fetchCompetitionTeams($dbCompetition->code);

            if (! $apiCompetition) {
                return null;
            }

            foreach ($apiCompetition['teams'] as $team) {
                Area::sync($team['area']);
                Team::sync($team);
                $dbCompetition->teams()->syncWithoutDetaching($team['id']);
            }
        }

        return $dbCompetition->load('teams');
    }

    /**
     * Fetches matches for a given competition from the external API,
     * synchronizes them with the database, and retrieves the updated list of matches.
     *
     * @param Competition $competition
     * @param string $today
     * @param string $endDate
     * @param null|string $direction
     * @return Collection
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     * @throws InvalidFormatException
     */
    private function fetchAndSyncMatches(
        Competition $competition,
        string $today,
        string $endDate,
        ?string $direction = 'desc'
    ): Collection {
        $apiCompetition = $this->footballApi->fetchCompetitionMatches($competition->code, $today, $endDate);

        foreach ($apiCompetition['matches'] as $match) {
            Game::sync($match);
        }

        return Game::getBetweenDates($competition->id, $today, $endDate, $direction);
    }

    /**
     * @param Competition $competition
     * @param string $today
     * @param string $endDate
     * @return Collection
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     * @throws InvalidFormatException
     */
    public function refreshUpcomingMatchesByCompetition(
        Competition $competition,
        string $today,
        string $endDate
    ): Collection {
        $cacheKey = "matches_competition_{$competition->id}_{$today}_{$endDate}";

        Cache::forget($cacheKey);

        $matches = $this->fetchAndSyncMatches($competition, $today, $endDate);

        Cache::put($cacheKey, $matches, self::CACHE_TTL);

        return $matches;
    }
}
