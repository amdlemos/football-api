<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class SeasonData extends Data
{
    public function __construct(
        public int $id,
        // public int $competition_id,
        public CarbonImmutable $start_date,
        public CarbonImmutable $end_date,
        // public int $current_matchday,
        // public int $current_season_id

    ) {}

    protected $fillable = ['id', 'competition_id', 'start_date', 'end_date', 'current_matchday', 'current_season_id'];
}
