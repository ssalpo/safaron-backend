<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const STATUS_UNDER_CONSIDERATION = 1;
    public const STATUS_CONFIRMED = 2;

    protected $fillable = [
        'route_id',
        'user_id',
        'number_of_seats',
        'status',
    ];
}
