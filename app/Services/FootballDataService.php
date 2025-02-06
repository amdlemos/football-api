<?php

namespace App\Services;

use App\Http\Integrations\FootballDataConnector;
use App\Http\Integrations\Requests\GetCompetitionsRequest;
use App\Http\Integrations\Requests\GetCompetitionTeamsRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use LogicException;
use Saloon\Http\Response;

/** @package App\Services */
class FootballDataService
{
    protected FootballDataConnector $connector;

    public function __construct()
    {
        $this->connector = new FootballDataConnector();
    }

    public function getCompetitions(): Response
    {
        $request = new GetCompetitionsRequest();

        return $this->connector->send(request: $request); //->json(key: 'results');
    }

    public function getCompetitionTeams(string $code): Response
    {
        $request = new GetCompetitionTeamsRequest($code);

        return $this->connector->send(request: $request); //->json(key: 'results');
    }
}
