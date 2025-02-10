<?php

namespace App\Data;

use App\Models\Area;
use App\Models\Competition;
use App\Models\Season;
use App\Data\TeamData;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

/** @package App\Data */
/** @typescript */
class CompetitionData extends Data
{
    /** @return void  */
    public function __construct(
        public int $id,
        public string $name,
        public string $code,
        public string $type,
        public string $emblem,
        public string $plan,
        public ?AreaData $area,
        public ?SeasonData $currentSeason,
        /** @var TeamData[] */
        public ?Collection $teams,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public ?CarbonImmutable $published_at
    ) {}

    public static function fromModel(Competition $competition): self
    {
        return new self(
            id: $competition->id,
            name: $competition->name,
            code: $competition->code,
            type: $competition->type,
            emblem: $competition->emblem,
            plan: $competition->plan,
            published_at: $competition->published_at,
            area: $competition->area ? AreaData::from($competition->area) : null,
            currentSeason: $competition->currentSeason ? SeasonData::from($competition->currentSeason) : null,
            teams: $competition->teams ? TeamData::collect($competition->teams) : null,
        );
    }
}
