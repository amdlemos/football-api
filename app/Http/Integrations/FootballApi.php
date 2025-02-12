<?php

namespace App\Http\Integrations;

use App\Http\Integrations\Requests\GetCompetitionMatchesRequest;
use App\Http\Integrations\Requests\GetCompetitionRequest;
use App\Http\Integrations\Requests\GetCompetitionsRequest;
use App\Http\Integrations\Requests\GetCompetitionTeamsRequest;
use App\Http\Integrations\Requests\GetMatchesByTeamRequest;
use App\Http\Integrations\Requests\GetMatchesRequest;
use App\Http\Integrations\Requests\GetTeamsRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\HasTimeout;

use Saloon\Http\Request;

/** @package App\Connectors */
class FootballApi
{
    protected FootballDataConnector $connector;

    public function __construct()
    {
        $this->connector = new FootballDataConnector();
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

    public function fetchMatches(
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $status = null,
        ?string $competitionsId = null,
        ?string $ids = null
    ) {
        return $this->sendRequest(new GetMatchesRequest(
            $dateFrom,
            $dateTo,
            $status,
            $competitionsId,
            $ids
        ));
    }

    public function fetchTeams(
        ?string $limit = null,
        ?string $offset = null,
    ) {
        return $this->sendRequest(new GetTeamsRequest(
            $limit,
            $offset,
        ));
    }

    public function fetchMatchesByTeam(
        string $id,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $season = null,
        ?string $competitionsId = null,
        ?string $status = null,
        ?string $venue = null,
        ?string $limit = null,
    ) {
        return $this->sendRequest(new GetMatchesByTeamRequest(
            $id,
            $dateFrom,
            $dateTo,
            $season,
            $competitionsId,
            $status,
            $venue,
            $limit
        ));
    }
}
