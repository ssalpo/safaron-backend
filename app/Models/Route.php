<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELED = 3;

    protected $fillable = [
        'go_time',
        'user_id',
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

    public function routeLocations()
    {
        return $this->hasMany(RouteLocation::class);
    }
}
