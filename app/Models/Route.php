<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Route extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELED = 3;

    public const CANCEL_REASONS = [
        1 => 'Мои планы изменились',
        2 => 'Хочу изменить поездку',
        3 => 'Мало пассажиров',
        4 => 'Сломался автомобиль',
        5 => 'Другое',
    ];

    protected $fillable = [
        'go_time',
        'user_id',
        'car_id',
        'free_places',
        'fast_reservation',
        'baggage_transportation',
        'description',
        'price',
        'status',
        'cancel_reason',
        'cancel_description',
    ];

    protected $casts = [
        'price' => 'integer',
        'free_places' => 'integer',
        'fast_reservation' => 'boolean',
        'baggage_transportation' => 'boolean',
    ];

    protected $dates = ['go_time'];

    public function isCancel(): Attribute
    {
        return Attribute::get(
            static fn($value, $attributes) => $attributes['status'] === self::STATUS_CANCELED
        );
    }

    public function scopeForUser($q, $userId = null): void
    {
        $q->where('user_id', $userId ?? auth()->id());
    }

    public function scopeActive($q): void
    {
        $q->where('status', self::STATUS_ACTIVE);
    }

    public function scopeFilter($q): void
    {
        $q->when(request('status'), static fn($q, $v) => $q->whereStatus($v));
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function routeLocations()
    {
        return $this->hasMany(RouteLocation::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservationCounts()
    {
        return $this->hasMany(Reservation::class)
            ->selectRaw('route_id, sum(number_of_seats) as seats, count(route_id) passengers')
            ->groupBy('route_id');
    }
}
