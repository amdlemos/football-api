<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Season model
 *
 * Represents a season in a competition.
 *
 * @package App\Models
 * @property int $id
 * @property string $start_date The start date of the season.
 * @property string $end_date The end date of the season.
 * @property int|null $current_matchday The current matchday in the season.
 * @property \Illuminate\Support\Carbon|null $created_at The timestamp when the season was created.
 * @property \Illuminate\Support\Carbon|null $updated_at The timestamp when the season was last updated.
 * @method static \Illuminate\Database\Eloquent\Builder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereCurrentMatchday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereUpdatedAt($value)
 * @mixin \Eloquent
 * @psalm-suppress UndefinedDocblockClass
 */
class Season extends Model
{
    protected $fillable = ['id',  'start_date', 'end_date', 'current_matchday'];

    /**
     * Synchronize the season data with the provided season information.
     *
     * @param array $seasonData The season data to synchronize with.
     * @return \App\Models\Season The updated or created season instance.
     */
    public static function sync(array $seasonData)
    {
        return static::updateOrCreate(
            ['id' => $seasonData['id']],
            [
                'start_date' => $seasonData['startDate'],
                'end_date' => $seasonData['endDate'],
                'current_matchday' => $seasonData['currentMatchday'],
            ]
        );
    }
}
