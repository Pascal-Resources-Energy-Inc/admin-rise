<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stove extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'serial_number',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
