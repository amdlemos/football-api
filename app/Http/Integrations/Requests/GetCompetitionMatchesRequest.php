<?php

namespace App\Http\Integrations\Requests;

use Illuminate\Support\Facades\Cache;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;

class GetCompetitionMatchesRequest extends Request
{
    // use HasCaching;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $code,
        protected ?string $dateFrom = null,
        protected ?string $dateTo = null,
        protected ?string $stage = null,
        protected ?string $status = null,
        protected ?int $matchday = null,
        protected ?string $group = null,
        protected ?int $season = null
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/v4/competitions/{$this->code}/matches";
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'stage' => $this->stage,
            'status' => $this->status,
            'matchday' => $this->matchday,
            'group' => $this->group,
            'season' => $this->season,
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
