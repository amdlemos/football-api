<?php

namespace App\Http\Integrations;

use App\Http\Integrations\Requests\GetCompetitionMatchesRequest;
use App\Http\Integrations\Requests\GetCompetitionRequest;
use App\Http\Integrations\Requests\GetCompetitionsRequest;
use App\Http\Integrations\Requests\GetCompetitionTeamsRequest;
use App\Http\Integrations\Requests\GetMatchesByTeamRequest;
use App\Http\Integrations\Requests\GetMatchesRequest;
use App\Http\Integrations\Requests\GetTeamsRequest;
use LogicException;
use RuntimeException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Request;

/**
 * Handles communication with the external football API to fetch data such as matches, teams, and competitions.
 *
 * @package App\Http\Integrations
 */
class FootballApi
{
    protected FootballDataConnector $connector;

    /**
     * Initializes the FootballApi class by creating a new instance of the FootballDataConnector.
     *
     * @return void
     */
    public function __construct()
    {
        $this->connector = new FootballDataConnector();
    }

    /**
     * Sends an HTTP request using the Saloon PHP connector and returns the response as an array.
     *
     * @param Request $request The request to be sent.
     *
     * @return array The response data in array format.    *
     *
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
     * Fetches competition data from the external API using the provided competition code.
     *
     * @param string $code The unique code of the competition.
     *
     * @return array The competition data retrieved from the API.
     *
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
     * Fetches a list of all available competitions from the external API.
     *
     * @return array The list of competitions retrieved from the API.
     *
     * @throws FatalRequestException
     * @throws RequestException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function fetchCompetitions(): array
    {
        return $this->sendRequest(new GetCompetitionsRequest());
    }

    /**
     * Fetches the list of teams for a specific competition using the competition code.
     *
     * @param string $code The unique code of the competition.
     *
     * @return array The list of teams in the competition retrieved from the API.
     *
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
     * Fetches the list of matches for a specific competition within a date range, using the competition code.
     *
     * @param string $code The unique code of the competition.
     * @param string|null $dateFrom The start date for fetching matches (optional).
     * @param string|null $dateTo The end date for fetching matches (optional).
     *
     * @return array The list of matches in the competition retrieved from the API.
     *
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
     * Fetches a list of matches based on optional filters such as date range, status, and competition ID.
     *
     * @param string|null $dateFrom The start date for fetching matches (optional).
     * @param string|null $dateTo The end date for fetching matches (optional).
     * @param string|null $status The status of the matches to filter by (optional).
     * @param string|null $competitionsId The ID of the competition to filter by (optional).
     * @param string|null $ids A specific match ID or a list of match IDs to fetch (optional).
     *
     * @return array The list of matches retrieved from the API.
     *
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
     * Fetches a list of teams with optional pagination filters for limit and offset.
     *
     * @param string|null $limit The maximum number of teams to retrieve (optional).
     * @param string|null $offset The offset to start retrieving teams from (optional).
     *
     * @return array The list of teams retrieved from the API.
     *
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
     * Fetches a list of matches for a specific team, with optional filters such as date range, season,
     * competition, status, and venue.
     *
     * @param string $teamId The unique ID of the team.
     * @param string|null $dateFrom The start date for fetching matches (optional).
     * @param string|null $dateTo The end date for fetching matches (optional).
     * @param string|null $season The season to filter matches by (optional).
     * @param string|null $competitionsId The ID of the competition to filter by (optional).
     * @param string|null $status The status of the matches to filter by (optional).
     * @param string|null $venue The venue to filter matches by (optional).
     * @param string|null $limit The number of matches to return (optional).
     *
     * @return array The list of matches for the team retrieved from the API.
     *
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
