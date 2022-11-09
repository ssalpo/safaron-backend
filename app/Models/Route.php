<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'go_time',
        'car_id',
        'free_places',
        'fast_reservation',
        'baggage_transportation',
        'description',
        'price',
    ];

    protected $casts = [
        'price' => 'integer',
        'free_places' => 'integer',
        'fast_reservation' => 'boolean',
        'baggage_transportation' => 'boolean',
    ];

    protected $dates = ['go_time'];

    public function routeLocations()
    {
        return $this->hasMany(RouteLocation::class);
    }
}
