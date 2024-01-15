<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AlertModel extends Model
{
    protected $table = 'alerts';
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
    public function zone()
    {
        return $this->belongsTo(ZoneModel::class, 'zone_id');
    }

    protected $guarded = [];
}