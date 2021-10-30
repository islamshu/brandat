<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $guarded=[];
    public $timestamps = false;
    public function seller(){
        return $this->belongsTo(Seller::class,'seller_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'seller_id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'user_id');
    }
}
