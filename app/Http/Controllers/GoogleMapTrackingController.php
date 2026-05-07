<?php

namespace App\Http\Controllers;
use App\Models\AppWebUser;
use App\Models\UserLocation;
use App\Models\UserUnionCouncil;
use App\Models\VoterlistBlockcodeInformation;
use App\Models\VoterlistVoterInformation;
use DateTime;
use \DB;
use Validator;
use App\Models\UserBlockcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;

class GoogleMapTrackingController extends Controller
{

    public function searchVoters(Request $request){
        $na = isset($request['na'])?$request['na']:1384;
        $users_locations =array();
        $users =  AppWebUser::join('user_locations','user_locations.user_id','app_web_users.id')
        ->select('app_web_users.id','app_web_users.name','app_web_users.phone_number','app_web_users.polling_station','user_locations.longitude','user_locations.latitude');

        if(isset($request['user']) && $request['user'] !=""){
                $name = $request['user'];
            $users = $users->where('app_web_users.name', 'LIKE', "%$name%");
        }

        $users = $users->where('app_web_users.na_cons_id',  $na)
        ->whereNotNull('user_locations.longitude')->where('user_locations.longitude','!=',0)->whereNotNull('user_locations.latitude')->where('user_locations.latitude','!=',0)
        ->groupBy('user_locations.user_id')->orderBy('user_locations.id','desc')->get()->toArray();

        $userspolling =  DB::table('voter_list_polling_scheme_na_2018')
        ->select('id','latitude','longitude','polling_station','polling_type','blockcode','google_image')
        ->where('nat_cons_id',  $na)
        ->where('latitude','!=',0)->where('longitude','!=',0)
        ->groupBy('polling_station')
        ->get()->toArray();

        foreach ($users  as $i=> $user){
            $users_locations[$i]['id'] = $user['id'].'-user';
            $name  = isset($user['name'])?$user['name']:"";
            $phone_number  = isset($user['phone_number'])?$user['phone_number']:"";
            $polling_station  = isset($user['polling_station'])?$user['polling_station']:"";
            $users_locations[$i]['user_name'] = '<h3 style="margin:0px;">'.$name.'</h3> <p style="margin:0px;">'.$phone_number.'</p><p style="margin:0px;">'.$polling_station.'</p>';
            $users_locations[$i]['name'] = '<h3 style="margin:0px;">'.$name.'</h3> <p style="margin:0px;">'.$phone_number.'</p><p style="margin:0px;">'.$polling_station.'</p>';
            $users_locations[$i]['longitude'] = floatval($user['latitude']);
            $users_locations[$i]['latitude'] = floatval($user['longitude']);
            $users_locations[$i]['profile'] = url('public/member_pin.png');
        }

         if(isset($request['type']) && ($request['type'] == 'all' )){
            foreach($userspolling as $polling){
                if(strlen($polling->latitude) >5){
                    $img_url = 'https://techunitbucket.s3-ap-southeast-1.amazonaws.com/'.$polling->google_image;
                    $users_location['id'] = $polling->id.'-polling';
                    $users_location['user_name'] = '<h3 style="margin:0px;">'.$polling->polling_station.'</h3> <p style="margin:0px;">('.$polling->blockcode.')</p>-<p style="margin:0px;">'.$polling->polling_type.'</p>';
                    $users_location['name'] = '<img style="width:400px;height:300px;" src="'. $img_url.'"><h3 style="margin:0px;">'.$polling->polling_station.'</h3> <p style="margin:0px;">('.$polling->blockcode.')</p>-<p style="margin:0px;">'.$polling->polling_type.'</p>';
                    $users_location['longitude'] = floatval($polling->latitude);
                    $users_location['latitude'] = floatval($polling->longitude);
                    $users_location['profile'] = url('public/pollingStation.png');
                    $users_locations[] = $users_location;
                }
            }
         }

        //print_r($users);
       // print_r($users_locations); die;
        return  $users_locations;
    }

       public function indexLarkana(Request $request){
           $users_locations =array();
            if(empty($request['na'])){
              return Redirect('/tracking-system?na=1384&type=all');
            }

            $election_date = '2024-02-08';
           $na = isset($request['na'])?$request['na']:1384;

           if($request['type'] == 'all'){
                    $users =  AppWebUser::join('user_locations','user_locations.user_id','app_web_users.id')
                    ->select('app_web_users.id','app_web_users.name','app_web_users.phone_number','app_web_users.polling_station','user_locations.longitude','user_locations.latitude')
                    ->where('app_web_users.na_cons_id',  $na)
                    // ->whereDate('user_locations.created_at', '=', $election_date)
                    ->whereNotNull('user_locations.longitude')->where('user_locations.longitude','!=',0)->whereNotNull('user_locations.latitude')->where('user_locations.latitude','!=',0)
                    ->groupBy('user_locations.user_id')->orderBy('user_locations.id','desc')->get()->toArray();

                $userspolling =  DB::table('voter_list_polling_scheme_na_2018')
                ->select('id','latitude','longitude','polling_station','polling_type','blockcode','google_image')
                ->where('nat_cons_id',  $na)
                ->where('latitude','!=',0)->where('longitude','!=',0)
                ->groupBy('polling_station')
                ->get()->toArray();

                    foreach ($users  as $i=> $user){
                        $users_locations[$i]['id'] = $user['id'].'-user';
                        $name  = isset($user['name'])?$user['name']:"";
                        $phone_number  = isset($user['phone_number'])?$user['phone_number']:"";
                        $polling_station  = isset($user['polling_station'])?$user['polling_station']:"";
                        $users_locations[$i]['name'] = '<h3 style="margin:0px;">'.$name.'</h3> <p style="margin:0px;">'.$phone_number.'</p><p style="margin:0px;">'.$polling_station.'</p>';
                        $users_locations[$i]['longitude'] = floatval($user['latitude']);
                        $users_locations[$i]['latitude'] = floatval($user['longitude']);
                        $users_locations[$i]['profile'] = url('public/member_pin.png');
                    }


                    foreach($userspolling as $polling){
                        if(strlen($polling->latitude) >5){
                            $img_url = 'https://techunitbucket.s3-ap-southeast-1.amazonaws.com/'.$polling->google_image;
                            $users_location['id'] = $polling->id.'-polling';
                            $users_location['name'] = '<img style="width:400px;height:300px;" src="'. $img_url.'"><h3 style="margin:0px;">'.$polling->polling_station.'</h3> <p style="margin:0px;">('.$polling->blockcode.')</p>-<p style="margin:0px;">'.$polling->polling_type.'</p>';
                            $users_location['longitude'] = floatval($polling->latitude);
                            $users_location['latitude'] = floatval($polling->longitude);
                            $users_location['profile'] = url('public/pollingStation.png');
                            $users_locations[] = $users_location;
                        }
                    }

           }
           if($request['type'] == 'ps'){

                    $userspolling =  DB::table('voter_list_polling_scheme_na_2018')
                    ->select('id','latitude','longitude','polling_station','polling_type','blockcode','google_image')
                    ->where('nat_cons_id',  $na)
                    ->where('latitude','!=',0)->where('longitude','!=',0)
                    ->groupBy('polling_station')
                    ->get()->toArray();



                        foreach($userspolling as $polling){
                            if(strlen($polling->latitude) >5){
                                $img_url = 'https://techunitbucket.s3-ap-southeast-1.amazonaws.com/'.$polling->google_image;
                                $users_location['id'] = $polling->id.'-polling';
                                $users_location['name'] = '<img style="width:400px;height:300px;" src="'. $img_url.'"><h3 style="margin:0px;">'.$polling->polling_station.'</h3> <p style="margin:0px;">('.$polling->blockcode.')</p>-<p style="margin:0px;">'.$polling->polling_type.'</p>';
                                $users_location['longitude'] = floatval($polling->latitude);
                                $users_location['latitude'] = floatval($polling->longitude);
                                $users_location['profile'] = url('public/pollingStation.png');
                                $users_locations[] = $users_location;
                            }
                        }

           }
           if($request['type'] == 'workers'){


            $users =  AppWebUser::join('user_locations','user_locations.user_id','app_web_users.id')
                    ->select('app_web_users.id','app_web_users.name','user_locations.created_at','app_web_users.phone_number','app_web_users.polling_station','user_locations.longitude','user_locations.latitude')
                    ->where('app_web_users.na_cons_id',  $na)
                   // ->whereDate('user_locations.created_at', '=', $election_date)
                    ->whereNotNull('user_locations.longitude')->where('user_locations.longitude','!=',0)->whereNotNull('user_locations.latitude')->where('user_locations.latitude','!=',0)
                    ->groupBy('user_locations.user_id')->orderBy('user_locations.id','desc')->get()->toArray();


                    //echo "<pre>"; print_r($users);
                    foreach ($users  as $i=> $user){
                        $users_locations[$i]['id'] = $user['id'].'-user';
                        $name  = isset($user['name'])?$user['name']:"";
                        $phone_number  = isset($user['phone_number'])?$user['phone_number']:"";
                        $polling_station  = isset($user['polling_station'])?$user['polling_station']:"";
                        $users_locations[$i]['name'] = '<h3 style="margin:0px;">'.$name.'</h3> <p style="margin:0px;">'.$phone_number.'</p><p style="margin:0px;">'.$polling_station.'</p>';
                        $users_locations[$i]['longitude'] = floatval($user['latitude']);
                        $users_locations[$i]['latitude'] = floatval($user['longitude']);
                        $users_locations[$i]['profile'] = url('public/member_pin.png');
                    }


           }
          // echo "<pre>"; print_r($users_locations); die;

        //echo sizeof($users_locations);

         if($na == 1386){
            return view('google_maps.index_qambar',['users_json'=>$users_locations]);
         }else{
            return view('google_maps.index_larkana',['users_json'=>$users_locations]);

         }

       }




       public function userHistory(Request $request){
           $users_locations =array();
           $response = array('data'=>array(), 'type'=>'user');
                //print_r($request['user']); die;
            if(isset($request['user']['id'])){
            $arr =  explode("-",$request['user']['id']);

                if($arr[1] == 'user'){
                    $users =  UserLocation::with('user')->where('user_id',$arr[0])->whereNotNull('longitude')->where('longitude','!=',0)->whereNotNull('latitude')->where('latitude','!=',0)->orderBy('id','DESC')->get()->toArray();
                    foreach ($users  as $i=> $user){
                        $users_locations[$i]['id'] = $arr[0];
                        $users_locations[$i]['lat'] = floatval($user['latitude']);
                        $users_locations[$i]['lng'] = floatval($user['longitude']);
                         $date = new DateTime($user['created_at']);
                        // Format the date with AM/PM indicator
                        $formattedTime = $date->format('Y-m-d h:i:s A');
                        $users_locations[$i]['time'] =$formattedTime;
                        ;
                    }
                    $response = array('data'=>$users_locations, 'type'=>'user');
                }

                if($arr[1] == 'polling'){
                    $profile_path = 'https://techunitbucket.s3-ap-southeast-1.amazonaws.com/';
                    $userspolling =  DB::table('voter_list_polling_scheme_na_2018')
                    ->select('latitude','longitude','polling_station','polling_type','blockcode','google_image')
                   ->where('id',  $arr[0])
                   ->where('latitude','!=',0)->where('longitude','!=',0)
                   ->groupBy('polling_station')
                   ->get()->toArray();

                    //$users =  UserLocation::with('user')->where('user_id',$request['user']['id'])->whereNotNull('longitude')->where('longitude','!=',0)->whereNotNull('latitude')->where('latitude','!=',0)->orderBy('id','DESC')->get()->toArray();
                    foreach ($userspolling  as $i=> $user){

                        $users_locations[$i] =(array) $user;
                        $users_locations[$i]['lat'] = floatval($user->latitude);
                        $users_locations[$i]['lng'] = floatval($user->longitude);
                        //$users_locations[$i]['details'] =;
                        $img_url = 'https://techunitbucket.s3-ap-southeast-1.amazonaws.com/'.$user->google_image;
                        $users_locations[$i]['details'] = '<img style="width:500px;height:350px;" src="'. $img_url.'"><h3 style="margin:0px;">'.$user->polling_station.'</h3> <p style="margin:0px;">'.$user->blockcode.'</p>';

                        //$date = new DateTime($user['created_at']);
                        // Format the date with AM/PM indicator
                        //$formattedTime = $date->format('Y-m-d h:i:s A');
                        //$users_locations[$i]['time'] =$formattedTime;
                    }
                    $response = array('data'=>$users_locations, 'type'=>'polling');

                }

           }
           return $response;

       }
}
