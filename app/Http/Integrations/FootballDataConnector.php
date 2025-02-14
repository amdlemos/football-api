<?php

namespace App\Http\Integrations;

use Illuminate\Contracts\Container\BindingResolutionException;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\HasTimeout;

/**
 * A custom connector for interacting with the football data API,
 * extending the Saloon connector to manage requests and authentication.
 *
 * @package App\Http\Integrations
 */
class FootballDataConnector extends Connector
{
    use HasTimeout;

    protected int $connectTimeout = 60;

    protected int $requestTimeout = 120;

    protected string $token;

    /**
     * Initializes the FootballDataConnector and sets the API token from the configuration.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->token = config('football.api_key');
    }

    /**
     * Returns the default header authenticator using the API token for authentication.
     *
     * @return HeaderAuthenticator The authenticator instance with the API token in the header.
     */
    protected function defaultAuth(): HeaderAuthenticator
    {
        return new HeaderAuthenticator($this->token, 'X-Auth-Token');
    }


    /**
     * Resolves and returns the base URL for the football API.
     *
     * @return string The base URL of the football API.
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.football-data.org';
    }
}
