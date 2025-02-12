<?php

namespace App\Http\Integrations\Requests;

use Illuminate\Support\Facades\Cache;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;

class GetMatchesRequest extends Request
{
    // use HasCaching;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $dateFrom = null,
        protected ?string $dateTo = null,
        protected ?string $status = null,
        protected ?string $competitionsId = null,
        protected ?string $ids = null
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/v4/matches";
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'status' => $this->status,
            'competitionsId' => $this->competitionsId,
            'ids' => $this->ids,
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
