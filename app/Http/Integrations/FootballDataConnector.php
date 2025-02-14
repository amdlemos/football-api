<?php

namespace App\Http\Integrations;

use Illuminate\Contracts\Container\BindingResolutionException;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\HasTimeout;

/** @package App\Connectors */
class FootballDataConnector extends Connector
{
    use HasTimeout;

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    protected string $token;

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->token = config('football.api_key');
    }

    protected function defaultAuth(): HeaderAuthenticator
    {
        return new HeaderAuthenticator($this->token, 'X-Auth-Token');
    }


    /** @return string  */
    public function resolveBaseUrl(): string
    {
        return 'https://api.football-data.org';
    }
}
