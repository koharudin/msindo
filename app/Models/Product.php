<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setBuyDateAttribute($value)
    {
        $this->attributes['buy_date'] = $value;
        $this->attributes['year'] = date('Y', strtotime($value));
        $this->attributes['month'] = date('m', strtotime($value));
    }
}
