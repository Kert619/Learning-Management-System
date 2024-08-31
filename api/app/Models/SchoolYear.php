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
}
