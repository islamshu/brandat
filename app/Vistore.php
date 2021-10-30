<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vistore extends Model
{
    protected $table = 'vistors';
    protected $guarded=[];
    public $timestamps = false; 
}
