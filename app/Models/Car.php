<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'photo',
        'number_of_seats'
    ];

    public function scopeForUser($q, $userId = null): void
    {
        $q->where('user_id', $userId ?? auth()->id());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
