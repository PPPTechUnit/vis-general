<?php

namespace App\Http\Controllers\Backend;

use App\Models\AppWebUser;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;
use Validator;


class AppWebUsersController extends Controller{


    public function index(){
        $users  =AppWebUser::where('user_from',"GB")->orderBy('id',"desc")->get()->toArray();
        return view('backend.app_web_users.listing',['users'=>$users]);
    }

    public function getPPPUsers(){
        $users  =AppWebUser::where('user_from',"GB")->select('assigned_user',DB::raw('count(*) as total'))->orderBy('id',"desc")->groupBy('assigned_user')->get()->toArray();
        return view('backend.app_web_users.listing_ppp_users',['users'=>$users]);
    }




    public function searchedVoters(Request $request){
        $voters  =DB::table('voter_list_voters_cnic_histories')->where('user_from',"GB");
        //->where('user_id',$id )->where('local_user_id',$users['assigned_user'] )
        $voters =$voters  ->get()->toArray();

        //echo "<pre>"; print_r($voters); die;
        return view('backend.app_web_users.voters_listing',['users'=>$voters]);
    }

    public function show($id){
        $users  =AppWebUser::where('id',$id )->first()->toArray();
        $voters  =DB::table('voter_list_voters_cnic_histories')->where('user_from',"GB")->where('user_id',$id )->where('local_user_id',$users['assigned_user'] )->get()->toArray();

        //echo "<pre>"; print_r($voters); die;
        return view('backend.app_web_users.voters_listing',['users'=>$voters]);
    }

    public function userLocation($id){
        $locations  =DB::table('user_locations')->where('user_id',$id )->orderBy('id','desc')->get()->toArray();

        //echo "<pre>"; print_r($voters); die;
        return view('backend.app_web_users.user_locations',['locations'=>$locations]);
    }


}
