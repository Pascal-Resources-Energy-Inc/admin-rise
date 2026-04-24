<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //
    public function dealer()
    {
        return $this->belongsTo(User::class,'dealer_id','id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function adDealer()
    {
        return $this->belongsTo(Dealer::class,'dealer_id','user_id');
    } 
}
