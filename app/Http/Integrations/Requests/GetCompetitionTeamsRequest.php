<?php

namespace App\Http\Integrations\Requests;

use Illuminate\Support\Facades\Cache;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;

class GetCompetitionTeamsRequest extends Request implements Cacheable
{
    use HasCaching;

    protected Method $method = Method::GET;

    public function __construct(protected readonly string $code) {}

    public function resolveEndpoint(): string
    {
        return "/v4/competitions/{$this->code}/teams";
    }

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(Cache::store('redis'));
    }

    public function cacheExpiryInSeconds(): int
    {
        return config(key: 'football.cache_duration');
    }
}
