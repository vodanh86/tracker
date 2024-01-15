<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserFriendModel extends Model
{
    protected $table = 'user_friends';
    protected $hidden = [
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    protected $guarded = [];
}