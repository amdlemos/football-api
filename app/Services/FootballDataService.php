<?php

namespace App\Services;

use App\Http\Integrations\FootballDataConnector;
use App\Http\Integrations\Requests\GetCompetitionMatchesRequest;
use App\Http\Integrations\Requests\GetCompetitionRequest;
use App\Http\Integrations\Requests\GetCompetitionsRequest;
use App\Http\Integrations\Requests\GetCompetitionTeamsRequest;
use App\Models\Area;
use App\Models\Competition;
use App\Models\Game;
use App\Models\Season;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Saloon\Http\Request;
use Saloon\Http\Response;

/** @package App\Services */
class FootballDataService
{
    protected FootballDataConnector $connector;

    private const CACHE_TTL = 3600;
    private const DB_REFRESH_INTERVAL = 24 * 3600;
    private const MIN_TEAMS_PER_COMPETITION = 10;

    public function __construct()
    {
        $this->connector = new FootballDataConnector();
    }

    public function getCompetitions(): Collection
    {
        // Cache::forget('competitions');
        return Cache::remember('competitions', self::CACHE_TTL, function () {
            $dbCompetitions = Competition::with(['area', 'currentSeason'])->where(
                'updated_at',
                '>=',
                Carbon::now()->subSeconds(self::DB_REFRESH_INTERVAL)
            )->get();

            if ($dbCompetitions->isNotEmpty()) {
                return $dbCompetitions;
            }

            $apiCompetitions = $this->fetchCompetitions();

            foreach ($apiCompetitions["competitions"] as $competition) {
                $this->syncArea($competition['area']);
                $this->syncSeason($competition['currentSeason']);
                $this->syncCompetition($competition);
            }

            return Competition::with('area', 'currentSeason')->get();
        });
    }

    public function getCompetitionTeams(string $code): Competition|null
    {
        $cacheKey = "competition_{$code}";
        // Cache::forget($cacheKey);
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($code) {
            $dbCompetition = Competition::with('teams')
                ->where('code', $code)
                ->where('updated_at', '>=', Carbon::now()->subSeconds(self::DB_REFRESH_INTERVAL))
                ->first();

            if ($dbCompetition) {
                return $this->hasEnoughTemas($dbCompetition);
            }

            $dbCompetitions = $this->getCompetitions();
            $dbCompetition = $dbCompetitions->where('code', $code)->firstOrFail();
            return $this->hasEnoughTemas($dbCompetition);
        });
    }

    public function getUpcomingGamesByCompetition(string $competitionCode)
    {
        $competition = Competition::where('code', $competitionCode)->firstOrFail();
        $today = Carbon::now()->format('Y-m-d');
        $endDate = $competition->currentSeason->end_date;
        $cacheKey = "competition_{$competitionCode}_{$today}_{$endDate}";
        // Cache::forget($cacheKey);
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($competition, $today, $endDate) {
            $dbMatches = Game::where('competition_id', $competition->id)
                ->whereDate('utc_date', '>=', $today)
                ->whereDate('utc_date', '<=', $endDate)
                ->where('updated_at', '>=', Carbon::now()->subSeconds(self::DB_REFRESH_INTERVAL))
                ->orderBy('utc_date', 'asc')
                ->get();

            // dd($dbMatches);
            if ($dbMatches->isNotEmpty()) {
                return $dbMatches;
            }

            $apiCompetition = $this->fetchCompetitionMatches($competition->code, $today, $endDate);

            // dd($apiCompetition);
            foreach ($apiCompetition["matches"] as $match) {
                $this->syncMatch($match);
            }

            $dbMatches = Game::where('competition_id', $competition->id)
                ->whereDate('utc_date', '>=', $today)
                ->whereDate('utc_date', '<=', $endDate)
                ->orderBy('utc_date', 'asc')
                ->get();
            return $dbMatches;
        });
    }

    public function hasEnoughTemas(Competition $dbCompetition)
    {
        $hasEnoughTeams = $dbCompetition->teams()
            ->select('id')
            ->limit(self::MIN_TEAMS_PER_COMPETITION)
            ->count() >= self::MIN_TEAMS_PER_COMPETITION;

        if (!$hasEnoughTeams) {
            $apiCompetition = $this->fetchCompetitionTeams($dbCompetition->code);

            if (!$apiCompetition) {
                return null;
            }

            foreach ($apiCompetition["teams"] as $team) {
                $this->syncArea($team['area']);
                $this->syncTeam($team);
                $dbCompetition->teams()->syncWithoutDetaching($team['id']);
            }
        }

        return $dbCompetition->load('teams');
    }

    public function syncArea(array $areaData)
    {
        return Area::updateOrCreate(
            ['id' => $areaData['id']],
            [
                'name' => $areaData['name'],
                'code' => $areaData['code'],
                'flag' => $areaData['flag'],
            ]
        );
    }

    public function syncSeason(array $seasonData)
    {
        Season::updateOrCreate(
            ['id' => $seasonData['id']],
            [
                'start_date' => $seasonData['startDate'],
                'end_date' => $seasonData['endDate'],
                'current_matchday' => $seasonData['currentMatchday'],
            ]

        );
    }

    public function syncTeam(array $team)
    {
        Team::updateOrCreate(
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

    public function syncCompetition(array $competition)
    {
        Competition::updateOrCreate(
            ['id' => $competition['id']],
            [
                'area_id' => $competition['area']['id'],
                'name' => $competition['name'],
                'code' => $competition['code'],
                'type' => $competition['type'],
                'emblem' => $competition['emblem'],
                'plan' => $competition['plan'],
                'current_season_id' => $competition['currentSeason']['id'],
                'current_matchday' => $competition['currentSeason']['currentMatchday'],
            ]
        );
    }

    public function syncMatch(array $match)
    {
        Game::updateOrCreate(
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


    public function updateCompetitionMatchday(int $competitionCode)
    {
        $competition = $this->fetchCompetition($competitionCode);

        Competition::updateOrCreate(
            ['id' => $competition['id']],
            [
                'current_matchday' => $competition['currentSeason']['currentMatchday'],
            ]
        );
    }

    private function sendRequest(Request $request): array
    {
        return $this->connector->send($request)->json();
    }

    public function fetchCompetition(string $code)
    {
        return $this->sendRequest(new GetCompetitionRequest($code));
    }

    public function fetchCompetitions(): array
    {
        return $this->sendRequest(new GetCompetitionsRequest());
    }

    public function fetchCompetitionTeams(string $code): array
    {
        return $this->sendRequest(new GetCompetitionTeamsRequest($code));
    }

    public function fetchCompetitionMatches(string $code, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->sendRequest(new GetCompetitionMatchesRequest($code, $dateFrom, $dateTo));
    }
}
