<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $fillable = [
        'room_id',
        'word',
        'is_used',
    ];

    public function room() {
        return $this->belongsTo(Room::class);
    }
}
