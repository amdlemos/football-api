<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/** @typescript */
class TeamData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $shortname,
        public ?string $tla,
        public ?string $crest,
        public ?string $address,
        public ?string $website,
        public ?string $founded,
        public ?string $venue,
        public ?string $clubColors,
        // public AreaData $area,
    ) {}
}
