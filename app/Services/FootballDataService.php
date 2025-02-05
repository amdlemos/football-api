<?php

namespace App\Services;

use App\Http\Integrations\FootballDataConnector;
use App\Http\Integrations\Requests\GetCompetitionsRequest;
use Saloon\Http\Response;

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
}
