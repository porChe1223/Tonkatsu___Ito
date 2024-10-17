<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RoomUser;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'room_user')
                    ->using(RoomUser::class)
                    ->withTimestamps();
    }
}
