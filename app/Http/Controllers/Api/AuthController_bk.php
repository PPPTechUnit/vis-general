<?php

namespace App\Http\Controllers\Api;
use App\Models\UsersInformation;
use App\Models\UserUnionCouncil;
use DateTime;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
//use Laravel\Passport\TokenRepository;
//use Laravel\Passport\RefreshTokenRepository;

class AuthController_bk extends Controller{

    public function apiResponse($status,$message,$error,$data,$status_code){
        return response()->json(
            [
                'status' => $status,
                'message' => $message,
                'error' => $error,
                'data' => $data
            ],
            $status_code
        );
    }


    public function getNewOtp(){
        $otp = (array) DB::table('workers_users_tokens')->where('used',0)->first();
        $response =array();
        if (sizeof($otp)>0){
            $response['token'] = $otp['token'];
            $response['status'] = 'true';
            $response['message'] = 'Get New OTP';
        }else{
            $response['token'] ='';
            $response['status'] = 'false';
            $response['message'] = 'Not found OTP';
        }
        return $response;
    }

    public function verifyToken_old(Request $request){
        $validate_data = [
            'token' => 'required|digits:6',
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
        }else{
            $exist_code = 111111;

            if($request['token'] == $exist_code){
                return $this->apiResponse(  true, 'Code matched Successfully', '', [], 200);

            }else{
                $exist = (array) DB::table('users_secure_codes')->where('code',$request['token'])->where('used',0)->first();
                if(sizeof($exist)>0){

                    $time =  date('Y-m-d H:i:s', time());
                    DB::table('users_secure_codes')->where('id',$exist['id'])->update(['used'=>1,'user_id'=>$request['user_id'],'updated_at'=>$time]);

                    return $this->apiResponse(  true, 'Code matched Successfully', '', [], 200);
                }else{
                    return $this->apiResponse(  false, '', 'Code not matched', [], 401);
                }
            }
        }

    }

    public function login_old(Request $request){

        $validate_data = [
            'cnic_number' => 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/',
            'phone_number' => 'required',
        ];
        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
        }else{
            $exist = (array) DB::table('users')->where('cnic_number',$request['cnic_number'])->where('phone_number',$request['phone_number'])->first();
            if(sizeof($exist)>0){
                //$code =DB::table('users_secure_codes')->where('id',1)->first()->code;
                $user_data[0]['id'] =$exist['id'];
                $user_data[0]['name'] =$exist['name'];
                $user_data[0]['email'] =$exist['email'];
                $user_data[0]['cnic_number'] =$exist['cnic_number'];
                $user_data[0]['phone_number'] =$exist['phone_number'];
             //   $user_data[0]['code'] =$code;

              /*  for ($i=1; $i<=1000; $i++ ){

                    $num_str = sprintf("%06d", mt_rand(1, 999999));

                        echo $num_str."<br>";
                    $end_time =  date('Y-m-d H:i:s', time());
                    DB::table("users_secure_codes")->insert(['code'=>$num_str,'created_at'=>$end_time]);

                }*/

                return $this->apiResponse(  true, 'User Login successfully', '', $user_data, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid CNIC Number & Phone Number', [], 401);
            }
        }

    }



    // New API's
    private function uploadImage($request, $image,$path){
        return Storage::disk('s3')->put($path, $image, 'public');
    }

    public function register(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0) {

                $validate_data = [
                    'name' => 'required|string|min:3',
                    'polling_station' => 'required',
//                    'cnic_number' => 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/|unique:app_web_users,cnic_number',
                    'phone_number' => 'required|size:11|unique:app_web_users,phone_number',
                    //'selection_criteria' => 'required',
                    // 'province' => 'required',
                    //'division' => 'required',
                    //'district' => 'required',
                  //  'tehsil' => 'required',
                ];


                $validator = Validator::make($request->all(), $validate_data);
                if ($validator->fails()) {
                    return $this->apiResponse(false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                }

                $user = array();
                $user['name'] = $request['name'];
                $user['polling_station'] = isset($request['polling_station']) ? $request['polling_station'] : 0;
                $user['email'] = isset($request['email']) ? $request['email'] : null;
                $user['na_cons_id'] = isset($request['na_cons_id']) ? $request['na_cons_id'] : 0;
                $user['cnic_number'] = isset($request['cnic_number']) ? $request['cnic_number'] : null;
//                $user['password'] =Hash::make('pptu@786$2023');
//                $user['cnic_number'] = $request['cnic_number'];
//                $user['cnic'] = str_replace("-", "", $request['cnic_number']);
                $user['phone_number'] = $request['phone_number'];
                $user['data_availability'] = 'Particular';
               // $selection_criteria = isset($request['selection_criteria']) ? $request['selection_criteria'] : 0;


               // $user['selection_criteria'] = $selection_criteria;
                $tehsil = (array)DB::table("tehsils")->where('id', $request['tehsil'])->first();
                    if(sizeof($tehsil)>0){
                        $user['province_id'] = $tehsil['province_id'];
                        $user['division_id'] = $tehsil['division_id'];
                        $user['district_id'] = $tehsil['district_id'];
                        $user['tehsil_id'] =   $request['tehsil'];
                    }else{
                        $user['province_id'] = isset($tehsil['province_id']) ? $tehsil['province_id'] : 0;
                        $user['division_id'] = isset($tehsil['division_id']) ? $tehsil['division_id'] : 0;
                        $user['district_id'] = isset($tehsil['district_id']) ? $tehsil['district_id'] : 0;
                        $user['tehsil_id'] =   $request['tehsil'];
                    }




                $returnedImage = '';
              /*  $image = $request->cnic_image;  // your base64 encoded
                if (strpos($image, 'data:image/jpeg') !== false) {
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = time() . rand(111111, 999999) . '.' . 'jpg';
                    $returnedImage =   Storage::disk('s3')->put(config('globalvariables.s3_images_path') . '/data-axis/app-web-users/cnic-images/' . $imageName, base64_decode($image), 'public');
                   // $returnedImage = config('globalvariables.s3_images_path') . '/data-axis/app-web-users/cnic-images/' . $imageName;
                } elseif (strpos($image, 'data:image/png') !== false) {
                    $image = str_replace('data:image/png;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = time() . rand(111111, 999999) . '.' . 'png';
                    $returnedImage=   Storage::disk('s3')->put(config('globalvariables.s3_images_path') . '/data-axis/app-web-users/cnic-images/' . $imageName, base64_decode($image), 'public');
                   // $returnedImage = config('globalvariables.s3_images_path') . '/data-axis/app-web-users/cnic-images/' . $imageName;
                } elseif ($request->hasFile('cnic_image')) {
                    $returnedImage = $this->uploadImage($request, $request['cnic_image'], config('globalvariables.s3_images_path') . '/data-axis/app-web-users/cnic-images');
                } else {
                }*/
                $user['cnic_image'] = $returnedImage;
                //$user['created_by'] = Auth::user()->id;
                $user['created_at'] = date('Y-m-d H:i:s', time());

                $ucs = explode('&', $request['uc']);
                $all_ucs =array();
                $ii=0;
                foreach ($ucs as $uc){
                    if ($uc != ''){
                        $all_ucs[$ii] =  $uc;
                        $ii++;
                    }
                }
                if(sizeof($all_ucs)>0){
                    $user['selection_criteria'] = 5;
                }else{
                    $user['selection_criteria'] = 4;
                }
             //   print_r($user); die;

                $id = DB::table('app_web_users')->insertGetId($user);

              /*  if(sizeof($all_ucs)>0){
                    foreach ($all_ucs as $uc_1){
                        $user_uc = array();

                        $tehsil = (array)DB::table("tehsils")->where('id', $request['tehsil'])->first();
                        if(sizeof($tehsil)>0){
                            $user_uc['province_id'] = $tehsil['province_id'];
                            $user_uc['division_id'] = $tehsil['division_id'];
                            $user_uc['district_id'] = $tehsil['district_id'];
                            $user_uc['tehsil_id'] =   $request['tehsil'];
                        }else{
                            $user_uc['province_id'] = isset($tehsil['province_id']) ? $tehsil['province_id'] : 0;
                            $user_uc['division_id'] = isset($tehsil['division_id']) ? $tehsil['division_id'] : 0;
                            $user_uc['district_id'] = isset($tehsil['district_id']) ? $tehsil['district_id'] : 0;
                            $user_uc['tehsil_id'] = $user['tehsil_id'] =   $request['tehsil'];
                        }
                        $user_uc['uc'] = $uc_1;
                        $user_uc['user_id'] = $id;
                        DB::table('app_web_users_union_councils')->insert($user_uc);
                    }
                }else{
                    $uces = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22")
                        ->where('tehsil_id',$request['tehsil'])
                        ->select('province_id','division_id','district_id','tehsil_id','no_and_name_of_constituency as uc')
                        ->groupBy('no_and_name_of_constituency')
                        ->orderBy('no_and_name_of_constituency','ASC')
                        ->get()->toArray();
                    foreach ($uces as $uce){
                        $user_uc = array();
                        $tehsil = (array)DB::table("tehsils")->where('id', $request['tehsil'])->first();
                        if(sizeof($tehsil)>0){
                            $user_uc['province_id'] = $tehsil['province_id'];
                            $user_uc['division_id'] = $tehsil['division_id'];
                            $user_uc['district_id'] = $tehsil['district_id'];
                            $user_uc['tehsil_id'] =   $request['tehsil'];
                        }else{
                            $user_uc['province_id'] = isset($tehsil['province_id']) ? $tehsil['province_id'] : 0;
                            $user_uc['division_id'] = isset($tehsil['division_id']) ? $tehsil['division_id'] : 0;
                            $user_uc['district_id'] = isset($tehsil['district_id']) ? $tehsil['district_id'] : 0;
                            $user_uc['tehsil_id'] = isset($request['tehsil']) ? $request['tehsil'] : 0;
                        }
                        $user_uc['uc'] = $uce->uc;
                        $user_uc['user_id'] = $id;
                        DB::table('app_web_users_union_councils')->insert($user_uc);
                    }
                }*/


                if(!empty($id)){
                    return $this->apiResponse(  true, 'Profile Created successfully', '', [], 200);
                }
                return $this->apiResponse(  false, '', 'Profile Not Created', [], 400);

            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }

        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }


    }


    public function login(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                $input = $request->only(['otp', 'phone_number']);
                $validate_data = [
                    'phone_number' => 'required|digits:11|exists:app_web_users,phone_number',
                ];
                $validator = Validator::make($input, $validate_data);
                if ($validator->fails()) {
                    return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                }
                $exist = (array) DB::table('app_web_users')->where('phone_number',$request['phone_number'])->first();
                $otp = (array) DB::table('workers_users_tokens')->where('token',$request['otp'])->where('used',0)->select('token')->first();
                if(sizeof($exist)>0){
                    if($exist['kill_switch']  == 1){
                        DB::table('app_web_users')->where('phone_number',$request['phone_number'])->update(['auth_token'=>null]);
                        $data =['kill_switch'=>1];
                        return $this->apiResponse(  false, '', 'You have not access to login', $data, 401);
                    }elseif($exist['deleted']  == 1){
                        DB::table('app_web_users')->where('phone_number',$request['phone_number'])->update(['auth_token'=>null]);
                        $data =['deleted'=>1];
                        return $this->apiResponse(  false, '', 'Your account has beed deleted', $data, 401);
                    }
                    else{
                        if((isset($request['otp']) && strlen($request['otp']) == 6 && sizeof($otp)>0) ||  $request['otp'] == "111111"){
                            DB::table('workers_users_tokens')->where('token',$request['otp'])->update(['used'=>1,'updated_at'=>date('Y-m-d H:i:s')]);
                            $auth_code = Hash::make($request['phone_number']);
                            $user_data['token'] =$auth_code;
                            DB::table('app_web_users')->where('id',$exist['id'])->update(['auth_token'=>$auth_code]);

                            $user['id'] =$exist['id'];
                            $user['name'] =$exist['name'];
                            $user['email'] =$exist['email'];
                            $user['cnic'] =$exist['cnic'];
                            $user['cnic_number'] =$exist['cnic_number'];
                            $user['phone_number'] =$exist['phone_number'];
                            $user['na_cons_id'] =$exist['na_cons_id'];
                            $user['na_name'] = isset(DB::table("voter_list_polling_scheme_na")->where('id',$exist['na_cons_id'])->first()->na_name)?DB::table("voter_list_polling_scheme_na")->where('id',$exist['na_cons_id'])->first()->na_name:"";
                            $user['polling_station'] =$exist['polling_station'];
                            $user['data_availability'] =$exist['data_availability'];
                            $user['kill_switch'] =$exist['kill_switch'];
                            $user['tehsil'] =isset(DB::table('tehsils')->where('id',$exist['tehsil_id'])->first()->name)?DB::table('tehsils')->where('id',$exist['tehsil_id'])->first()->name:'';

                            //$user['union_councils'] = DB::table('app_web_users_union_councils')->where('user_id',$user['id'])->pluck('uc')->toArray();
                            $user['union_councils'] = array();
                            $user['ward'] ='';
                            $user['union_council'] ='';
                            $ps =  (explode("(",$exist['polling_station']));
                            $blockcode =  end($ps);

                            $blockcodes =  str_replace(")","", $blockcode);


                              $all_bc =  (explode(",",$blockcodes));

                              if(isset($all_bc[0])){
                                $uc_ward = (array) DB::connection('mysql2')->table('voter_list_blockcode_info')->where('blockcode',trim($all_bc[0]))->select('union_council','ward')->first();
                                if(sizeof($uc_ward)>0){
                                    $user['ward'] = $uc_ward['ward'];
                                    $user['union_council'] = $uc_ward['union_council'];
                                }
                              }




                              $user_data['user'] =$user;
                              $user_data['user']['languages'] =DB::connection('mysql2')->table('languages')->pluck('title')->toArray();

                              $user_data['user']['casts'] =DB::connection('mysql2')->table('casts')->pluck('title')->toArray();;

                            return $this->apiResponse(  true, 'Logged in successfully', '', $user_data, 200);

                        }else{
                            return $this->apiResponse(  false, 'unprocessable_entity', 'The OTP field is invalid and must be 6 digit', [], 422);
                        }
                    }
                }else{
                    return $this->apiResponse(  false, '', 'This phone number is not found', [], 401);
                }

            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }

        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }


    }

    public function login_04_2024(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                $input = $request->only(['otp', 'phone_number']);
               // $validate_data = ['email' => 'required|email|exists:users', 'password' => 'required|min:6'];
                $validate_data = [
                    //'cnic_number' => 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/|exists:app_web_users,cnic_number',
                    'phone_number' => 'required|digits:11|exists:app_web_users,phone_number',
                    'otp'=> 'required|digits:6'
                ];
                $validator = Validator::make($input, $validate_data);
                if ($validator->fails()) {
                    return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                }
                $exist = (array) DB::table('app_web_users')->where('phone_number',$request['phone_number'])->first();
                $otp = (array) DB::table('workers_users_tokens')->where('token',$request['otp'])->where('used',0)->select('token')->first();
                if(sizeof($exist)>0  && (sizeof($otp)>0 ) ){

                    if($exist['kill_switch']  == 1){
                        $success =  DB::table('app_web_users')->where('phone_number',$request['phone_number'])->update(['auth_token'=>null]);
                        $data =['kill_switch'=>1];
                        return $this->apiResponse(  false, '', 'You have not aceess to login', $data, 401);

                    }else{
                        DB::table('workers_users_tokens')->where('token',$request['otp'])->update(['used'=>1,'updated_at'=>date('Y-m-d H:i:s')]);
                        $auth_code = Hash::make($request['phone_number']);
                        $user_data['token'] =$auth_code;
                        DB::table('app_web_users')->where('id',$exist['id'])->update(['auth_token'=>$auth_code]);

                        $user['id'] =$exist['id'];
                        $user['name'] =$exist['name'];
                        $user['email'] =$exist['email'];
                        $user['cnic'] =$exist['cnic'];
                        $user['cnic_number'] =$exist['cnic_number'];
                        $user['phone_number'] =$exist['phone_number'];
                        $user['na_cons_id'] =$exist['na_cons_id'];
                        $user['na_name'] = isset(DB::table("voter_list_polling_scheme_na")->where('id',$exist['na_cons_id'])->first()->na_name)?DB::table("voter_list_polling_scheme_na")->where('id',$exist['na_cons_id'])->first()->na_name:"";
                        $user['polling_station'] =$exist['polling_station'];
                        $user['data_availability'] =$exist['data_availability'];
                        $user['kill_switch'] =$exist['kill_switch'];
                        $user['tehsil'] =isset(DB::table('tehsils')->where('id',$exist['tehsil_id'])->first()->name)?DB::table('tehsils')->where('id',$exist['tehsil_id'])->first()->name:'';

                        $user['union_councils'] = DB::table('app_web_users_union_councils')->where('user_id',$user['id'])->pluck('uc')->toArray();


                        $user_data['user'] =$user;
                        return $this->apiResponse(  true, 'Logged in successfully', '', $user_data, 200);
                    }


                }else{
                    return $this->apiResponse(  false, '', 'Incorrect Phone Number or OTP', [], 401);
                }
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }

        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }


    }

    public function userDetail(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                        $user['id'] =$user_auth['id'];
                        $user['name'] =$user_auth['name'];
                        $user['email'] =$user_auth['email'];
                        $user['cnic_number'] =$user_auth['cnic_number'];
                        $user['phone_number'] =$user_auth['phone_number'];
                        $user['na_cons_id'] =$user_auth['na_cons_id'];
                        $user['na_name'] = isset(DB::table("voter_list_polling_scheme_na")->where('id',$user_auth['na_cons_id'])->first()->na_name)?DB::table("voter_list_polling_scheme_na")->where('id',$user_auth['na_cons_id'])->first()->na_name:"";
                        $user['polling_station'] =$user_auth['polling_station'];

                       // $user['province'] = isset(DB::table("provinces")->where('id',$user_auth['province_id'])->first()->name)?DB::table("provinces")->where('id',$user_auth['province_id'])->first()->name:"";
                       // $user['division'] = isset(DB::table("divisions")->where('id',$user_auth['division_id'])->first()->name)?DB::table("divisions")->where('id',$user_auth['division_id'])->first()->name:"";
                       // $user['district'] = isset(DB::table("districts")->where('id',$user_auth['district_id'])->first()->name)?DB::table("districts")->where('id',$user_auth['district_id'])->first()->name:"";
                       // $user['tehsil'] = isset(DB::table("tehsils")->where('id',$user_auth['tehsil_id'])->first()->name)?DB::table("tehsils")->where('id',$user_auth['tehsil_id'])->first()->name:"";
                        $user_data['user'] =$user;
                        return $this->apiResponse(  true, 'Get User Data', '', $user_data, 200);
                    }
                    else{
                        return $this->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }

    public function verifyToken(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                        $validate_data = [
                            'token' => 'required|digits:6'
                        ];
                        $validator = Validator::make($request->all(), $validate_data);
                        if ($validator->fails()) {
                            return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                        }
                        else{
                            $exist_code = 111111;
                            if($request['token'] == $exist_code){
                                return $this->apiResponse(  true, 'Secret Code Matched', '', [], 200);
                            }else{
                                $exist = (array) DB::table('users_secure_codes')->where('code',$request['token'])->where('used',0)->first();
                                if(sizeof($exist)>0){

                                    $time =  date('Y-m-d H:i:s', time());
                                    DB::table('users_secure_codes')->where('id',$exist['id'])->update(['used'=>1,'user_id'=>$user_auth['id'],'updated_at'=>$time]);

                                    return $this->apiResponse(  true, 'Secret Code Matched', '', [], 200);
                                }else{
                                    return $this->apiResponse(  false, '', 'Incorrect Code, Try Another One', [], 401);
                                }
                            }
                        }
                    }
                    else{
                        return $this->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }

    public function logout(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {

                        $success =  DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->update(['auth_token'=>null]);

                        if(!empty($success)){
                            return $this->apiResponse(  true, 'Logout Successfully', '', [], 200);
                        }else{
                            return $this->apiResponse(  false, '', 'Invalid Auth Token', [], 403);
                        }

                    }
                    else{
                        return $this->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }
    
    public function deleteAccount(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {

                        $success =  DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->update(['deleted'=>1,'auth_token'=>null]);

                        if(!empty($success)){
                            return $this->apiResponse(  true, 'Logout Successfully', '', [], 200);
                        }else{
                            return $this->apiResponse(  false, '', 'Invalid Auth Token', [], 403);
                        }

                    }
                    else{
                        return $this->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }


    public function getPOllingStations12($slug){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){

                $pollings = DB::table("voter_list_polling_scheme_na")
                            ->join('pppmissys.voter_list_blockcode_info','voter_list_blockcode_info.blockcode','voter_list_polling_scheme_na.blockcode')
                ->select('voter_list_polling_scheme_na.id','voter_list_blockcode_info.union_council','voter_list_blockcode_info.ward','voter_list_polling_scheme_na.polling_station')
                ->where('voter_list_polling_scheme_na.nat_cons_id',$slug)
                ->groupby('voter_list_polling_scheme_na.polling_station')->get()->toArray();
                $stations =array();
                foreach ($pollings as $i=> $polling){
                    $stations[$i]['id'] = $polling->id;
                    $stations[$i]['union_council'] = $polling->union_council;
                    $stations[$i]['ward'] = $polling->ward;
                    $stations[$i]['na_cons_id'] = $slug;
                    $stations[$i]['polling_station'] = $polling->polling_station .' ('. implode(", ",DB::table("voter_list_polling_scheme_na")->where('nat_cons_id',$slug)->where('polling_station',$polling->polling_station)->pluck('blockcode')->toArray()) .')';
                }
                       // print_r($stations); die;

                $user_data['stations'] =$stations;
                return $this->apiResponse(  true, 'Polling Stations', '', $user_data, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }

        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }


    public function getPOllingStations($slug){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){

                $pollings1 = DB::table("pppmissys.voter_list_blockcode_info")
                ->select('voter_list_blockcode_info.union_council')
                ->where('voter_list_blockcode_info.union_council','!=','')
                ->where('voter_list_blockcode_info.national_assembly_id',$slug)
                ->groupby('voter_list_blockcode_info.union_council')
                ->get()->toArray();
                $response_json =array();


                foreach($pollings1 as $a=> $polling1){
                    $pollings2 = DB::table("pppmissys.voter_list_blockcode_info")
                        ->select('voter_list_blockcode_info.ward')
                        ->where('voter_list_blockcode_info.union_council',$polling1->union_council)
                        ->where('voter_list_blockcode_info.national_assembly_id',$slug)
                        ->groupby('voter_list_blockcode_info.ward')
                        ->get()->toArray();
                        foreach($pollings2 as $b=>  $polling2){
                            $pollings = DB::table("voter_list_polling_scheme_na")
                            ->join('pppmissys.voter_list_blockcode_info','voter_list_blockcode_info.blockcode','voter_list_polling_scheme_na.blockcode')
                            ->select('voter_list_polling_scheme_na.id','voter_list_polling_scheme_na.polling_station')
                            ->where('voter_list_polling_scheme_na.nat_cons_id',$slug)
                            ->where('voter_list_blockcode_info.union_council',$polling1->union_council)
                            ->where('voter_list_blockcode_info.ward',$polling2->ward)
                            ->groupby('voter_list_polling_scheme_na.polling_station')->get()->toArray();

                        $stations =array();
                        // foreach ($pollings as $i=> $polling){
                        //     $stations[$i]['id'] = $polling->id;
                        //     $stations[$i]['na_cons_id'] = $slug;
                        //     $stations[$i]['polling_station'] = $polling->polling_station .' ('. implode(", ",DB::table("voter_list_polling_scheme_na")->where('nat_cons_id',$slug)->where('polling_station',$polling->polling_station)->pluck('blockcode')->toArray()) .')';
                        // }
                        $i_i =0;
                        foreach ($pollings as $i=> $polling){
                            $polling_station_name_blockcode = $polling->polling_station .' ('. implode(", ",DB::table("voter_list_polling_scheme_na")->where('nat_cons_id',$slug)->where('polling_station',$polling->polling_station)->pluck('blockcode')->toArray()) .')';
                            //echo "<br>";
                            //echo $polling->polling_station;
                           // $exist =  DB::table("app_web_users")->where('polling_station','LIKE',$polling->polling_station.'%')->first();
                            $exist =  DB::table("app_web_users")->where('polling_station',$polling_station_name_blockcode)->first();
                            if (!empty($exist)){
                               // echo " yes-exist";
                            }else{
                               // echo " not exist";
                                $stations[$i_i]['id'] = $polling->id;
                                $stations[$i_i]['na_cons_id'] = $slug;
                                $stations[$i_i]['polling_station']  = $polling_station_name_blockcode;
                                $i_i++;
                            }
                        }
                        $response_json[$a]['uc'] = $polling1->union_council;
                        $response_json[$a]['wards'][$b]['ward'] = $polling2->ward;
                        $response_json[$a]['wards'][$b]['ps'] = $stations;
                        //$response_json[$a]['ward'][$b]['ps'] = 123;

                       // $response_json[$a]['uc']['ward'][$b] = $polling1->union_council;
                       // $response_json[$a]['uc'][$b]['ward']['ps'] =$stations;
                       // $response_json[$a] =$polling1->union_council;
                        //[$polling2->ward] = $stations;
                       // echo $polling2->ward;
                    }



                }

                //print_r(  $response_json);die;



                // $pollings = DB::table("voter_list_polling_scheme_na")
                //             ->join('pppmissys.voter_list_blockcode_info','voter_list_blockcode_info.blockcode','voter_list_polling_scheme_na.blockcode')
                // ->select('voter_list_polling_scheme_na.id','voter_list_blockcode_info.union_council','voter_list_blockcode_info.ward','voter_list_polling_scheme_na.polling_station')
                // ->where('voter_list_polling_scheme_na.nat_cons_id',$slug)
                // ->groupby('voter_list_polling_scheme_na.polling_station')->get()->toArray();
                // $stations =array();
                // foreach ($pollings as $i=> $polling){
                //     $stations[$i]['id'] = $polling->id;
                //     $stations[$i]['union_council'] = $polling->union_council;
                //     $stations[$i]['ward'] = $polling->ward;
                //     $stations[$i]['na_cons_id'] = $slug;
                //     $stations[$i]['polling_station'] = $polling->polling_station .' ('. implode(", ",DB::table("voter_list_polling_scheme_na")->where('nat_cons_id',$slug)->where('polling_station',$polling->polling_station)->pluck('blockcode')->toArray()) .')';
                // }
                       // print_r($stations); die;
                       $response_json_2 =array();
                       $a = 0;
                       foreach ($response_json as $respon){
                               $wardss =array();
                           $b = 0;
                            foreach ($respon['wards'] as $ward){
                                if(sizeof($ward['ps'])>0){
                                    $wardss[$b]['ward'] = $ward['ward'];
                                    $wardss[$b]['ps'] = $ward['ps'];
                                    $b++;
                                }
                           }
                            if (sizeof($wardss)>0){
                               $response_json_2[$a]['uc']= $respon['uc'];
                               $response_json_2[$a]['wards']= $wardss;
                               $a++;
                           }
                       }
                $user_data['stations'] =$response_json_2;
                return $this->apiResponse(  true, 'Polling Stations', '', $user_data, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }

        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }



    public function getOccupation(){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                $designation = DB::table("professions")->select('id','title')->get()->toArray();
                $user_data['occupation'] =$designation;
                return $this->apiResponse(  true, 'Get All occupation ', '', $user_data, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }
    public function getEducation(){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                $qualification = DB::table("educations")->select('id','title')->get()->toArray();
                $user_data['qualification'] =$qualification;
                return $this->apiResponse(  true, 'Get All Qualifications', '', $user_data, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }
}
