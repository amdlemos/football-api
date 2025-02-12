<?php

namespace App\Services;

use App\Http\Integrations\FootballApi;
use App\Models\Area;
use App\Models\Competition;
use App\Models\Game;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

/** @package App\Services */
class FootballDataService
{
    protected FootballApi $footballApi;

    private const CACHE_TTL = 3 * 3600;
    private const MIN_TEAMS_PER_COMPETITION = 10;

    public function __construct()
    {
        $this->footballApi = new FootballApi();
    }

    public function getCompetitions(): Collection
    {
        // Cache::forget('competitions');
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

    public function getCompetitionTeams(string $code): Competition|null
    {
        $cacheKey = "competition_{$code}";
        // Cache::forget($cacheKey);
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
    public function getPreviousGamesByCompetition(string $competitionCode)
    {
        $competition = Competition::where('code', $competitionCode)->firstOrFail();
        $today = Carbon::now()->format('Y-m-d');
        $startDate = $competition->currentSeason->start_date;
        $cacheKey = "previousMatches_{$competitionCode}_{$startDate}_{$today}";
        // Cache::forget($cacheKey);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($competition, $today, $startDate) {
            $dbMatches = Game::getFinishedBetweenDates($competition->id, $startDate, $today);

            if ($dbMatches->isNotEmpty()) {
                return $dbMatches;
            }

            $apiCompetition = $this->footballApi->fetchCompetitionMatches($competition->code, $startDate, $today);

            foreach ($apiCompetition["matches"] as $match) {
                Game::sync($match);
            }

            return Game::getFinishedBetweenDates($competition->id, $startDate, $today);
        });
    }
    public function getUpcomingGamesByCompetition(string $competitionCode)
    {
        $competition = Competition::where('code', $competitionCode)->firstOrFail();
        $today = Carbon::now()->format('Y-m-d');
        $endDate = $competition->currentSeason->end_date;
        $cacheKey = "upcomingMatches_{$competitionCode}_{$today}_{$endDate}";
        // Cache::forget($cacheKey);
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($competition, $today, $endDate) {
            $dbMatches = Game::getBetweenDates($competition->id, $today, $endDate);

            if ($dbMatches->isNotEmpty()) {
                return $dbMatches;
            }

            $apiCompetition = $this->footballApi->fetchCompetitionMatches($competition->code, $today, $endDate);

            foreach ($apiCompetition["matches"] as $match) {
                Game::sync($match);
            }

            return Game::getBetweenDates($competition->id, $today, $endDate);
        });
    }


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

                foreach ($apiMatches["matches"] as $match) {
                    Game::sync($match);
                }

                $dbMatches = Game::upcomingMatchesByTeam($teamId)->get();

                return $dbMatches;
            }
        );
    }


    public function hasEnoughTemas(Competition $dbCompetition)
    {
        $hasEnoughTeams = $dbCompetition->teams()
            ->select('id')
            ->limit(self::MIN_TEAMS_PER_COMPETITION)
            ->count() >= self::MIN_TEAMS_PER_COMPETITION;

        if (!$hasEnoughTeams) {
            $apiCompetition = $this->footballApi->fetchCompetitionTeams($dbCompetition->code);

            if (!$apiCompetition) {
                return null;
            }

            foreach ($apiCompetition["teams"] as $team) {
                Area::sync($team['area']);
                Team::sync($team);
                $dbCompetition->teams()->syncWithoutDetaching($team['id']);
            }
        }

        return $dbCompetition->load('teams');
    }

    public function updateCompetitionMatchday(int $competitionCode)
    {
        $competition = $this->footballApi->fetchCompetition($competitionCode);

        Competition::updateOrCreate(
            ['id' => $competition['id']],
            [
                'current_matchday' => $competition['currentSeason']['currentMatchday'],
            ]
        );
    }
}
