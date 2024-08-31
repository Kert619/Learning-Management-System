<?php

namespace App\Models;

use Database\Factories\SchoolYearFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $school_year
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SchoolYearFactory factory($count = null, $state = [])
 * @method static Builder|SchoolYear newModelQuery()
 * @method static Builder|SchoolYear newQuery()
 * @method static Builder|SchoolYear open()
 * @method static Builder|SchoolYear query()
 * @method static Builder|SchoolYear whereCreatedAt($value)
 * @method static Builder|SchoolYear whereId($value)
 * @method static Builder|SchoolYear whereSchoolYear($value)
 * @method static Builder|SchoolYear whereStatus($value)
 * @method static Builder|SchoolYear whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SchoolYear extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function factory(): SchoolYearFactory
    {
        return SchoolYearFactory::new();
    }

    protected static function booted(): void
    {
        static::created(fn(SchoolYear $model) => self::closeSchoolYears($model));

        static::updated(fn(SchoolYear $model) => self::closeSchoolYears($model));
    }

    private static function closeSchoolYears(SchoolYear $model)
    {
        if ($model->status === 'open') {
            SchoolYear::query()->where('id', '!=', $model->id)->update(['status' => 'close']);
        }
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }
}
