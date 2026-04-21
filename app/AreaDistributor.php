<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AreaDistributor extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'email', 'avatar', 'status', 'latitude', 'longitude', 'center_id'];

    public function areas()
    {
        return $this->hasMany(AreaAd::class, 'ad_id');
    }
}
