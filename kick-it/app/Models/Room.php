<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'description',
        'creator_id',
        'max_users'
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function words() {
        return $this->hasMany(Word::class);
    }

    public function players() {
        return $this->belongsToMany(User::class, 'room_user')->withPivot('score');
    }
}
