<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $with = ['sender', 'receiver'];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'read',
        'read_at'
    ];

    protected $dates = ['read_at'];

    protected $casts = [
        'read' => 'boolean'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
