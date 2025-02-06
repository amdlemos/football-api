<?php

namespace App\Services;

use App\Http\Integrations\FootballDataConnector;
use App\Http\Integrations\Requests\GetCompetitionsRequest;
use App\Http\Integrations\Requests\GetCompetitionTeamsRequest;
use App\Models\Area;
use App\Models\Competition;
use App\Models\Season;
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

    public function __construct()
    {
        $this->connector = new FootballDataConnector();
    }

    public function getCompetitions(): Collection
    {
        // Cache::forget('competitions');
        // Primeiro, tenta buscar do cache
        return Cache::remember('competitions', self::CACHE_TTL, function () {
            // Se não está no cache, verifica o banco de dados
            $dbCompetitions = Competition::with(['area', 'currentSeason'])->where(
                'updated_at',
                '>=',
                Carbon::now()->subSeconds(self::DB_REFRESH_INTERVAL)
            )->get();

            if ($dbCompetitions->isNotEmpty()) {
                return $dbCompetitions;
            }

            $request = new GetCompetitionsRequest();
            // Se não está no banco ou está desatualizado, busca da API
            $apiCompetitions = $this->connector->send(request: $request);

            // Atualiza o banco de dados
            foreach ($apiCompetitions["competitions"] as $competition) {
                Area::updateOrCreate(
                    ['id' => $competition['area']['id']],
                    [
                        'name' => $competition['area']['name'],
                        'code' => $competition['area']['code'],
                        'flag' => $competition['area']['flag'],
                    ]
                );
                Season::updateOrCreate(
                    ['id' => $competition['currentSeason']['id']],
                    [
                        'start_date' => $competition['currentSeason']['startDate'],
                        'end_date' => $competition['currentSeason']['endDate'],
                        'current_matchday' => $competition['currentSeason']['currentMatchday'],
                    ]

                );
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

            return Competition::with('area', 'currentSeason')->get();
        });

        // $request = new GetCompetitionsRequest();
        //
        // return $this->connector->send(request: $request); //->json(key: 'results');
    }

    public function getCompetitionTeams(string $code): Response
    {
        $request = new GetCompetitionTeamsRequest($code);

        return $this->connector->send(request: $request); //->json(key: 'results');
    }
}
