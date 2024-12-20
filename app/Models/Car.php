<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Booking;

class Car extends Model
{

    protected $fillable = [
        'code_car',
        'image',
        'name',
        'year',
        'rating',
        'about',
        'price',
        'quantity',
        'feature1',
        'feature2',
        'feature3',
        'feature4',
    ];

    public function checkout(): MorphOne
    {
        return $this->morphOne(Booking::class, 'bookingtable');
    }
}
