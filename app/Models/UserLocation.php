<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{


    public function user(){
        return $this->hasOne('App\Models\AppWebUser','id','user_id');
    }

}
