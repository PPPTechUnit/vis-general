<?php

namespace App\Http\Controllers\Api;
use DateTime;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Laravel\Passport\TokenRepository;
//use Laravel\Passport\RefreshTokenRepository;

class ApiHelperController extends Controller{

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

    public function getProvinces(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){

                $validate_data = [];
//                $validate_data = ['province' => 'required'];
                $validator = Validator::make($request->all(), $validate_data);
                if ($validator->fails()) {
                    return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                }
                $provinces = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22")
                            ->join('provinces','provinces.id','delimitation_areas_2022_with_block_codes_update_till_07_9_22.province_id');
                   if(isset($request['province']) && $request['province'] !=''){
                       $provinces =$provinces->where('provinces.id',$request['province']);
                   }
                $provinces =$provinces ->select('provinces.id','provinces.name')
                    ->groupBy('delimitation_areas_2022_with_block_codes_update_till_07_9_22.province_id')
                    ->orderBy('provinces.name','ASC')
                    ->get()->toArray();

                $login_token=['provinces'=>$provinces];
                return $this->apiResponse(  true, 'Get All Provinces', '', $login_token, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }

        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }


    }

    public function getDivisions(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){

               // $validate_data = ['province' => 'required'];
                $validate_data = [];
                $validator = Validator::make($request->all(), $validate_data);
                if ($validator->fails()) {
                    return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                }
                $divisions = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22")
                            ->join('divisions','divisions.id','delimitation_areas_2022_with_block_codes_update_till_07_9_22.division_id');
                   if(isset($request['province']) && $request['province'] !=''){
                       $divisions =$divisions->where('delimitation_areas_2022_with_block_codes_update_till_07_9_22.province_id',$request['province']);
                   }
                    if(isset($request['division']) && $request['division'] !=''){
                        $divisions =$divisions->where('delimitation_areas_2022_with_block_codes_update_till_07_9_22.division_id',$request['division']);
                   }
                $divisions =$divisions ->select('divisions.id','divisions.province_id','divisions.name')
                    ->groupBy('delimitation_areas_2022_with_block_codes_update_till_07_9_22.division_id')
                    ->orderBy('divisions.name','ASC')

                    ->get()->toArray();

                $login_token=['divisions'=>$divisions];
                return $this->apiResponse(  true, 'Get All Divisions', '', $login_token, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }

        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }


    }

    public function getDistricts(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){

               // $validate_data = ['province' => 'required'];
                $validate_data = [];
                $validator = Validator::make($request->all(), $validate_data);
                if ($validator->fails()) {
                    return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                }
                $districts = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22")
                            ->join('districts','districts.id','delimitation_areas_2022_with_block_codes_update_till_07_9_22.district_id');
                   if(isset($request['province']) && $request['province'] !=''){
                       $districts =$districts->where('delimitation_areas_2022_with_block_codes_update_till_07_9_22.province_id',$request['province']);
                   }
                    if(isset($request['division']) && $request['division'] !=''){
                        $districts =$districts->where('delimitation_areas_2022_with_block_codes_update_till_07_9_22.division_id',$request['division']);
                   }
                    if(isset($request['district']) && $request['district'] !=''){
                        $districts =$districts->where('delimitation_areas_2022_with_block_codes_update_till_07_9_22.district_id',$request['district']);
                   }
                $districts =$districts ->select('districts.id','districts.province_id','districts.division_id','districts.name')
                    ->groupBy('delimitation_areas_2022_with_block_codes_update_till_07_9_22.district_id')
                    ->orderBy('districts.name','ASC')

                    ->get()->toArray();

                $login_token=['districts'=>$districts];
                return $this->apiResponse(  true, 'Get All Districts', '', $login_token, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }

        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }


    }

    public function getTehsils(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){

               // $validate_data = ['province' => 'required'];
                $validate_data = [];
                $validator = Validator::make($request->all(), $validate_data);
                if ($validator->fails()) {
                    return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                }
                $tehsils = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22")
                            ->leftJoin('tehsils','tehsils.id','delimitation_areas_2022_with_block_codes_update_till_07_9_22.tehsil_id');
                  if(isset($request['province']) && $request['province'] !=''){
                      $tehsils =$tehsils->where('delimitation_areas_2022_with_block_codes_update_till_07_9_22.province_id',$request['province']);
                   }
                    if(isset($request['division']) && $request['division'] !=''){
                        $tehsils =$tehsils->where('delimitation_areas_2022_with_block_codes_update_till_07_9_22.division_id',$request['division']);
                   }
                    if(isset($request['district']) && $request['district'] !=''){
                        $tehsils =$tehsils->where('delimitation_areas_2022_with_block_codes_update_till_07_9_22.district_id',$request['district']);
                   }
                    if(isset($request['tehsil']) && $request['tehsil'] !=''){
                        $tehsils =$tehsils->where('delimitation_areas_2022_with_block_codes_update_till_07_9_22.tehsil_id',$request['tehsil']);
                   }
                $tehsils =$tehsils ->select('tehsils.id','tehsils.province_id','tehsils.division_id','tehsils.district_id','tehsils.name')
                    ->groupBy('delimitation_areas_2022_with_block_codes_update_till_07_9_22.tehsil_id')
                    ->orderBy('tehsils.name','ASC')

                    ->get()->toArray();

                $login_token=['tehsils'=>$tehsils];
                return $this->apiResponse(  true, 'Get All Tehsils', '', $login_token, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }

        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }


    }

    public function getUCs(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){

                $validate_data = ['province' => 'required'];
                $validate_data = [];
                $validator = Validator::make($request->all(), $validate_data);
                if ($validator->fails()) {
                    return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                }
                $tehsils = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22");
                  if(isset($request['province']) && $request['province'] !=''){
                      $tehsils =$tehsils->where('province_id',$request['province']);
                   }
                    if(isset($request['division']) && $request['division'] !=''){
                        $tehsils =$tehsils->where('division_id',$request['division']);
                   }
                    if(isset($request['district']) && $request['district'] !=''){
                        $tehsils =$tehsils->where('district_id',$request['district']);
                   }
                    if(isset($request['tehsil']) && $request['tehsil'] !=''){
                        $tehsils =$tehsils->where('tehsil_id',$request['tehsil']);
                   }
                $tehsils =$tehsils ->select('province_id','division_id','district_id','tehsil_id','no_and_name_of_constituency as uc')
                    ->groupBy('no_and_name_of_constituency')
                    ->orderBy('no_and_name_of_constituency','ASC')
                    ->get()->toArray();
                $login_token=['ucs'=>$tehsils];
                return $this->apiResponse(  true, 'Get All UCs', '', $login_token, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }

    public function getSelectionCriteria(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){

                $validate_data = ['province' => 'required'];
                $validate_data = [];
                $validator = Validator::make($request->all(), $validate_data);
                if ($validator->fails()) {
                    return $this->apiResponse(  false, 'unprocessable_entity', $validator->errors()->first(), [], 422);
                }
                $criterias = DB::table("selection_criteria")->where('status',1)->select('id','name')->get()->toArray();
                $login_token=['criterias'=>$criterias];
                return $this->apiResponse(  true, 'Get All Criterias', '', $login_token, 200);
            }else{
                return $this->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }


}
