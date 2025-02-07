<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @package App\Models
 * @property int $id
 * @property string $start_date
 * @property string $end_date
 * @property int|null $current_matchday
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Season newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Season newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Season query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Season whereCurrentMatchday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Season whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Season whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Season whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Season whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Season extends Model
{
    use HasFactory;

    protected $fillable = ['id',  'start_date', 'end_date', 'current_matchday'];
}
