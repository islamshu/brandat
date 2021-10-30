<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
  public function user(){
  	return $this->belongsTo(User::class);
  }
 public function shop(){
  	return $this->belongsTo(Shop::class,'user_id');
  }
  public function payments(){
  	return $this->hasMany(Payment::class);
  }
   public function vendor_package()
  {
    return $this->belongsTo(Vendorpackege::class,'vendor_pakege_id');

  }
  
}
