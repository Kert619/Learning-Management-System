<?php

namespace App\Models;

use Database\Factories\SchoolYearFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        static::created(function (SchoolYear $model) {
            if ($model->status === 'open') {
                SchoolYear::query()->where('id', '!=', $model->id)->update(['status' => 'close']);
            }
        });

        static::updated(function (SchoolYear $model) {
            if ($model->status === 'open') {
                SchoolYear::query()->where('id', '!=', $model->id)->update(['status' => 'close']);
            }
        });
    }
}
