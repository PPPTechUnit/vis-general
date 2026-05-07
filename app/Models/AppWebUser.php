<?php

namespace App\Models;
use App\Models\VoterListVotersInfoHistory;

use Illuminate\Database\Eloquent\Model;

class AppWebUser extends Model
{

    public function user_uc(){
        return $this->hasMany('App\Models\AppWebUserUnionCouncil','user_id','id')->select('id','user_id','uc');
    }
    public function district(){
        return $this->hasOne('App\Models\District','id','district_id')->select('id','name');
    }
    public function tehsil(){
        return $this->hasOne('App\Models\Tehsil','id','tehsil_id')->select('id','name');
    }



//    public function user_history(){
//        return $this->hasMany('App\Models\VoterListVotersInfoHistory','id','user_id');
//    }
    public function user_history()
    {
        return $this->hasMany(VoterListVotersInfoHistory::class,'user_id','id');
    }
}
