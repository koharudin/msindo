<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function setSellDateAttribute($value)
    {
        $this->attributes['sell_date'] = $value;
        $this->attributes['year'] = date('Y', strtotime($value));
        $this->attributes['month'] = date('m', strtotime($value));
    }


    public function getYearAttribute()
    {
        return $this->attributes['year'];
    }


    public function getMonthAttribute()
    {
        return $this->attributes['month'];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
