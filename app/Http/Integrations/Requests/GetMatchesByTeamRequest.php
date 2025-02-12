<?php

namespace App\Http\Integrations\Requests;

use Illuminate\Support\Facades\Cache;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;

class GetMatchesByTeamRequest extends Request
{
    // use HasCaching;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $teamId,
        protected ?string $dateFrom = null,
        protected ?string $dateTo = null,
        protected ?string $season =  null,
        protected ?string $competitionsId = null,
        protected ?string $status = null,
        protected ?string $venue =  null,
        protected ?string $limit = null
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/v4/teams/{$this->teamId}/matches/";
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'season' => $this->season,
            'status' => $this->status,
            'competitionsId' => $this->competitionsId,
            'status' => $this->status,
            'limit' => $this->limit,
        ], fn($value) => !is_null($value));
    }

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(Cache::store('redis'));
    }

    public function cacheExpiryInSeconds(): int
    {
        return 3600;
        // return  config('football.cache_duration');
    }
}
