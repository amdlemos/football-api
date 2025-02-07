<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $flag
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Area extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'code', 'flag'];

    // public function competitions()
    // {
    //     return $this->hasMany(Competition::class);
    // }
}
