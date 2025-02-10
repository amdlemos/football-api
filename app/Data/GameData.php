<?php

namespace App\Data;

use App\Models\Game;
use DateTime;
use Spatie\LaravelData\Data;

/** @typescript */
class GameData extends Data
{
    public function __construct(
        public int $id,
        public string $utcDate,
        public TeamData $homeTeam,
        public TeamData $awayTeam,
        // public ?string $venue,
    ) {}

    public static function fromModel(Game $game): self
    {
        return new self(
            id: $game->id,
            utcDate: $game->utc_date,
            homeTeam: $game->homeTeam ? TeamData::from($game->homeTeam) : null,
            awayTeam: $game->awayTeam ? TeamData::from($game->awayTeam) : null,
        );
    }
}
