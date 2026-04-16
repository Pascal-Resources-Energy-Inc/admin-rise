<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'user_id',
        'notif_id',
        'type',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}