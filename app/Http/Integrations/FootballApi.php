<?php

namespace App\Http\Integrations;

use App\Http\Integrations\Requests\GetCompetitionMatchesRequest;
use App\Http\Integrations\Requests\GetCompetitionRequest;
use App\Http\Integrations\Requests\GetCompetitionsRequest;
use App\Http\Integrations\Requests\GetCompetitionTeamsRequest;
use App\Http\Integrations\Requests\GetMatchesByTeamRequest;
use App\Http\Integrations\Requests\GetMatchesRequest;
use App\Http\Integrations\Requests\GetTeamsRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use LogicException;
use RuntimeException;
use Saloon\Http\Request;

/** @package App\Http\Integrations */
class FootballApi
{
    protected FootballDataConnector $connector;

    /** @return void  */
    public function __construct()
    {
        $this->connector = new FootballDataConnector;
    }

    /**
     * @param Request $request
     * @return array
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
    private function sendRequest(Request $request): array
    {
        return $this->connector->send($request)->json();
    }

    /**
     * @param string $code
     * @return array
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function fetchCompetition(string $code)
    {
        return $this->sendRequest(new GetCompetitionRequest($code));
    }

    /**
     * @return array
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function fetchCompetitions(): array
    {
        return $this->sendRequest(new GetCompetitionsRequest);
    }

    /**
     * @param string $code
     * @return array
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function fetchCompetitionTeams(string $code): array
    {
        return $this->sendRequest(new GetCompetitionTeamsRequest($code));
    }

    /**
     * @param string $code
     * @param null|string $dateFrom
     * @param null|string $dateTo
     * @return array
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function fetchCompetitionMatches(string $code, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->sendRequest(new GetCompetitionMatchesRequest($code, $dateFrom, $dateTo));
    }

    /**
     * @param null|string $dateFrom
     * @param null|string $dateTo
     * @param null|string $status
     * @param null|string $competitionsId
     * @param null|string $ids
     * @return array
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
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

    /**
     * @param null|string $limit
     * @param null|string $offset
     * @return array
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function fetchTeams(
        ?string $limit = null,
        ?string $offset = null,
    ) {
        return $this->sendRequest(new GetTeamsRequest(
            $limit,
            $offset,
        ));
    }

    /**
     * @param string $teamId
     * @param null|string $dateFrom
     * @param null|string $dateTo
     * @param null|string $season
     * @param null|string $competitionsId
     * @param null|string $status
     * @param null|string $venue
     * @param null|string $limit
     * @return array
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function fetchMatchesByTeam(
        string $teamId,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $season = null,
        ?string $competitionsId = null,
        ?string $status = null,
        ?string $venue = null,
        ?string $limit = null,
    ) {
        return $this->sendRequest(new GetMatchesByTeamRequest(
            $teamId,
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
