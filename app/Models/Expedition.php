<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }
}
