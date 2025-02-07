<?php

namespace App\Services;

use App\Http\Integrations\FootballDataConnector;
use App\Http\Integrations\Requests\GetCompetitionsRequest;
use App\Http\Integrations\Requests\GetCompetitionTeamsRequest;
use App\Models\Area;
use App\Models\Competition;
use App\Models\Season;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
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
                $hasEnoughTeams = $dbCompetition->teams()
                    ->select('id')
                    ->limit(self::MIN_TEAMS_PER_COMPETITION)
                    ->count() >= self::MIN_TEAMS_PER_COMPETITION;

                if (!$hasEnoughTeams) {
                    $apiCompetition = $this->fetchCompetitionTeams($code);

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
        });
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
            ]
        );
    }

    public function fetchCompetitionTeams(string $code): array
    {
        $request = new GetCompetitionTeamsRequest($code);
        return $this->connector->send(request: $request)->json();
    }

    public function fetchCompetitions(): array
    {
        $request = new GetCompetitionsRequest();
        return $this->connector->send(request: $request)->json();
    }
}
