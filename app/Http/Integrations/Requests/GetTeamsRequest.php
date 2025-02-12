<?php

namespace App\Http\Integrations\Requests;

use Illuminate\Support\Facades\Cache;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;

class GetTeamsRequest extends Request
{
    // use HasCaching;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $limit = null,
        protected ?string $offset = null,
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/v4/teams";
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'limit' => $this->limit,
            'offset' => $this->offset,
        ], fn($value) => !is_null($value));
    }

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(Cache::store('redis'));
    }

    public function cacheExpiryInSeconds(): int
    {
        return  config('football.cache_duration');
    }
}
