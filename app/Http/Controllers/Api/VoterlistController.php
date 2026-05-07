<?php

namespace App\Http\Controllers\Api;

use App\Models\AppWebUserUnionCouncil;
use App\Models\UsersInformation;
use App\Models\UserUnionCouncil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;
use Carbon\Carbon;

use App\User;
class VoterlistController extends Controller{

    public function __construct(){
        $this->apiResponse = new AuthController();
    }

    public function getAllCountCNIC_old(Request $request){
        $validate_data = array();
        $validate_data['user_id'] = 'required';

        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            return $this->apiResponse->apiResponse(  false, 'Unprocessable',  $validator->errors()->first(), [], 422);
        }
        else{

            //print_r($request->all());
            $message = '';
            if(isset($request['type']) && $request['type'] == 'today'){
                $start_time =  date('Y-m-d').' 00:00:05';
                $end_time =  date('Y-m-d H:i:s', time());
                $data_query =    DB::table("voter_list_voters_cnic_histories")->where('user_id',$request['user_id'])->whereBetween('created_at',[$start_time,$end_time])->get()->count();
                $message = 'Get All today record searched';
            }elseif(isset($request['type']) && $request['type'] == 'week'){
                $data_query =    DB::table("voter_list_voters_cnic_histories")->where('user_id',$request['user_id'])->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->groupBy('cnic_number')->get()->count();
                $message = 'Get all searched Week record ';

            } elseif(isset($request['type']) && $request['type'] == 'month'){
                $data_query =    DB::table("voter_list_voters_cnic_histories")->where('user_id',$request['user_id'])->whereMonth('created_at', Carbon::now()->month)->groupBy('cnic_number')->get()->count();
                $message = 'Get all searched Week month ';
            }else{

                $data_query =    DB::table("voter_list_voters_cnic_histories")->where('user_id',$request['user_id'])->groupBy('cnic_number')->get()->count();
                $message = 'Get all searched';
            }

            $all_count =array('total'=>$data_query);
            return $this->apiResponse->apiResponse(  true, $message, '', $all_count, 200);
        }

    }

    public function addCnicHistory_old(Request $request){

            $validate_data = array();
            $validate_data['cnic_number'] = 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/';
            $validate_data['user_id'] = 'required';

            $validator = Validator::make($request->all(), $validate_data);
            if ($validator->fails()) {
                return $this->apiResponse->apiResponse(  false, 'Unprocessable',  $validator->errors()->first(), [], 422);
            }
            else{
                $result =0;



                $start_time =  date('Y-m-d').' 00:00:05';
                $end_time =  date('Y-m-d H:i:s', time());

               // echo $end_time; die;
                $cnics = (explode(",",$request['cnic_number']));

                    foreach ($cnics as $nic){
                        $exist = (array) DB::table("voter_list_voters_cnic_histories")->where('user_id',$request['user_id'])->where('cnic_number',$nic)->whereBetween('created_at',[$start_time,$end_time])->first();
                        if (sizeof($exist)>0){
                            return $this->apiResponse->apiResponse(  false, 'Added already', '', [], 200);
                        }else{
                            $name =isset(DB::table("voter_list_voters_info")->where('cnic_no',$nic)->first()->name)?DB::table("voter_list_voters_info")->where('cnic_no',$nic)->first()->name:"";
                            $cnic_no =isset(DB::table("voter_list_voters_info")->where('cnic_no',$nic)->first()->cnic_no)?DB::table("voter_list_voters_info")->where('cnic_no',$nic)->first()->cnic_no:"";

                        if($cnic_no != ""){
                            DB::table("voter_list_voters_info")->where('cnic_no',$nic)->update(['view'=>1]);
                            DB::table("voter_list_voters_cnic_histories")->insert(['user_id'=>$request['user_id'],'name'=>$name,'cnic_number'=>$cnic_no,'created_at'=>$end_time]);
                            $data_query =    DB::table("voter_list_voters_cnic_histories")->where('user_id',$request['user_id'])->groupBy('cnic_number')->get()->count();
                            $all_count =array('total'=>$data_query);
                            return $this->apiResponse->apiResponse(  true, 'Successfully Added', '', $all_count, 200);


                        }else{
                            return $this->apiResponse->apiResponse(  false, 'CNIC not Fond', '', [], 200);
                        }


                        }
                    }

            }

    }

    public function addMultipleCnicHistory_old(Request $request){
        $validate_data = array();
        //$validate_data['cnic_number'] = 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/';
        $validate_data['cnic_number'] = 'required';
        $validate_data['user_id'] = 'required';

        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            return $this->apiResponse->apiResponse(  false, 'Unprocessable',  $validator->errors()->first(), [], 422);
        }
        else{
            $result =0;
            $nics = explode(',', $request['cnic_number']);
            $start_time =  date('Y-m-d').' 00:00:05';
            $end_time =  date('Y-m-d H:i:s', time());
                foreach ($nics as $nic){
                    $cnic_pattern = '/[0-9]{5}[-][0-9]{7}[-][0-9]{1}/';
                    if(preg_match_all($cnic_pattern, $nic)){
                    $cnics = (explode(",",$nic));
                    foreach ($cnics as $nic){
                        $exist = (array) DB::table("voter_list_voters_cnic_histories")->where('user_id',$request['user_id'])->where('cnic_number',$nic)->whereBetween('created_at',[$start_time,$end_time])->first();
                        if (sizeof($exist)>0){}
                        else{
                            $name =isset(DB::table("voter_list_voters_info")->where('cnic_no',$nic)->first()->name)?DB::table("voter_list_voters_info")->where('cnic_no',$nic)->first()->name:"";
                            $cnic_no =isset(DB::table("voter_list_voters_info")->where('cnic_no',$nic)->first()->cnic_no)?DB::table("voter_list_voters_info")->where('cnic_no',$nic)->first()->cnic_no:"";
                            if($cnic_no != ""){
                                DB::table("voter_list_voters_info")->where('cnic_no',$nic)->update(['view'=>1]);
                                DB::table("voter_list_voters_cnic_histories")->insert(['user_id'=>$request['user_id'],'name'=>$name,'cnic_number'=>$cnic_no,'created_at'=>$end_time]);
                            }
                        }
                    }
                    }
                }

            $data_query =    DB::table("voter_list_voters_cnic_histories")->where('user_id',$request['user_id'])->groupBy('cnic_number')->get()->count();
            $all_count =array('total'=>$data_query);
            return $this->apiResponse->apiResponse(  true, 'Successfully Added', '', $all_count, 200);
        }
    }


    public function countBlockcodeServery($blockcode){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {

                        $response_result =array();
                        $arr = (array) DB::connection('mysql2')->table("voter_list_voters_info")
                            ->leftJoin('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                            ->selectRaw("COUNT(CASE WHEN voter_list_voters_info_servery_users.survey='1' THEN 1 END) AS completed,count(*) as total")
                            ->where('voter_list_voters_info.blockcode', $blockcode)->first();
                        $total = isset($arr['total'])?$arr['total']:0;
                        $completed = isset($arr['completed'])?$arr['completed']:0;
                        $in_completed = ($total - $completed);
                        $result['total'] = $total;
                        $result['completed'] = $completed;
                        $result['incompleted'] = $in_completed;

                        $arr =   (array) DB::connection('mysql2')->table("voter_list_voters_info")
                            ->leftJoin('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                            ->select('voter_list_voters_info.cnic','voter_list_voters_info_servery_users.survey')
                            ->where('voter_list_voters_info.blockcode', $blockcode)->get()->toArray();

                        $response_result['info'] =$result;
                        $response_result['voters'] =$arr;

                         return $this->apiResponse->apiResponse(  true, 'Blockcode Servery Count', '', $response_result, 200);
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }




    public function userPSBlockcodeSurvey(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                        $current_time =  date('Y-m-d H:i:s', time());
                        $response_msg = 'Gharana Servery ';
                        $response =array();
                        $ps =  (explode("(",$user_auth['polling_station']));
                        $blockcode =  end($ps);

                        $blockcodes =  str_replace(")","", $blockcode);
                          $all_bc =  (explode(",",$blockcodes));
                          $user_blockcodes =array();
                          foreach($all_bc as $b_c){

                                 $uc_cnic =  DB::connection('mysql2')->table('voter_list_voters_info')
                                         ->join('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                                         ->where('voter_list_voters_info.blockcode',trim($b_c))
                                         ->select('voter_list_voters_info_servery_users.skip_reason')
                                         ->groupBy('voter_list_voters_info_servery_users.skip_reason')
                                         ->get()->toArray();
                                     $survey_type =array();
                                     foreach($uc_cnic  as $ii => $uc_survey){
                                         $uc_cnic12 =  DB::connection('mysql2')->table('voter_list_voters_info')
                                             ->join('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                                             ->where('voter_list_voters_info.blockcode',trim($b_c))
                                             ->where('voter_list_voters_info_servery_users.skip_reason','=',$uc_survey->skip_reason)
                                            ->select('voter_list_voters_info_servery_users.*', DB::raw('CASE WHEN voter_list_voters_info_servery_users.skip_reason = "" THEN "completed" ELSE voter_list_voters_info_servery_users.skip_reason END AS skip_reason'))
                                           ->get()->toArray();
                                           //  ->get()->count();
                                              $survey_type[$ii]['type'] = ($uc_survey->skip_reason == "")?"Completed":$uc_survey->skip_reason;
                                             $survey_type[$ii]['voters'] = $uc_cnic12;

                                         }
                                         $user_blockcodes[trim($b_c)]['total_voters'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',trim($b_c))->get()->count();
                                         $user_blockcodes[trim($b_c)]['total_gharana'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',trim($b_c))->groupBy('gharana_no')->get()->count();
                                         $user_blockcodes[trim($b_c)]['total_survey'] = DB::connection('mysql2')->table('voter_list_voters_info')
                                         ->join('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                                         ->where('voter_list_voters_info.blockcode',trim($b_c))

                                         ->get()->count();;
                                        $user_blockcodes[trim($b_c)]['voters_survey'] =$survey_type;
                         }

                        $response['survey'] =$user_blockcodes;

                         return $this->apiResponse->apiResponse(true, 'Worker Blockcode Survery', '', $response, 200);
                    }

                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }





    public function userBlockcodeSurvey(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                        $current_time =  date('Y-m-d H:i:s', time());
                        $response_msg = 'Gharana Servery ';
                        $response =array();
                        $ps =  (explode("(",$user_auth['polling_station']));
                        $blockcode =  end($ps);

                        $blockcodes =  str_replace(")","", $blockcode);
                          $all_bc =  (explode(",",$blockcodes));
                          $user_blockcodes =array();
                          foreach($all_bc as $b_c){

                                //  $uc_cnic =  DB::connection('mysql2')->table('voter_list_voters_info')
                                //          ->join('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                                //          ->where('voter_list_voters_info.blockcode',trim($b_c))
                                //          ->select('voter_list_voters_info_servery_users.skip_reason')
                                //          ->groupBy('voter_list_voters_info_servery_users.skip_reason')
                                //          ->get()->toArray();
                                     $survey_type =array();
                                    //  foreach($uc_cnic  as $ii => $uc_survey){
                                    //      $uc_cnic12 =  DB::connection('mysql2')->table('voter_list_voters_info')
                                    //          ->join('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                                    //          ->where('voter_list_voters_info.blockcode',trim($b_c))
                                    //          ->where('voter_list_voters_info_servery_users.skip_reason','=',$uc_survey->skip_reason)
                                    //         ->select('voter_list_voters_info_servery_users.*', DB::raw('CASE WHEN voter_list_voters_info_servery_users.skip_reason = "" THEN "completed" ELSE voter_list_voters_info_servery_users.skip_reason END AS skip_reason'))
                                    //        ->get()->toArray();
                                    //          //->get()->count();
                                    //           $survey_type[$ii]['type'] = ($uc_survey->skip_reason == "")?"Completed":$uc_survey->skip_reason;
                                    //          $survey_type[$ii]['voters'] = $uc_cnic12;

                                    //      }
                                         $user_blockcodes[trim($b_c)]['total_voters'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',trim($b_c))->get()->count();
                                         $user_blockcodes[trim($b_c)]['total_gharana'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',trim($b_c))->groupBy('gharana_no')->get()->count();
                                         $user_blockcodes[trim($b_c)]['total_survey'] = DB::connection('mysql2')->table('voter_list_voters_info')
                                         ->join('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                                         ->where('voter_list_voters_info.blockcode',trim($b_c))

                                         ->get()->count();;
                                        // $user_blockcodes[trim($b_c)]['voters_survey'] =$survey_type;
                         }

                        $response['survey'] =$user_blockcodes;

                         return $this->apiResponse->apiResponse(true, 'Worker Blockcode Survery', '', $response, 200);
                    }

                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }





    public function userBlockcodeSurveyDetails($blockcode_single){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {


                        $user_blockcodes =array();
                        $user_blockcodes['total_voters'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',$blockcode_single)->get()->count();
                        $user_blockcodes['total_gharana'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',$blockcode_single)->groupBy('gharana_no')->get()->count();
                        $user_blockcodes['total_survey'] = DB::connection('mysql2')->table('voter_list_voters_info')
                        ->join('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                        ->where('voter_list_voters_info.blockcode',$blockcode_single)
                        ->get()->count();;
                        $uc_cnic =  DB::connection('mysql2')->table('voter_list_voters_info')
                        ->join('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                        ->where('voter_list_voters_info.blockcode',$blockcode_single)
                        ->select('voter_list_voters_info_servery_users.skip_reason')
                        ->groupBy('voter_list_voters_info_servery_users.skip_reason')
                        ->get()->toArray();
                        $survey_type =array();
                        foreach($uc_cnic  as $ii => $uc_survey){
                            $uc_cnic12 =  DB::connection('mysql2')->table('voter_list_voters_info')
                                ->join('voter_list_voters_info_servery_users','voter_list_voters_info_servery_users.cnic','voter_list_voters_info.cnic')
                                ->where('voter_list_voters_info.blockcode',$blockcode_single)
                                ->where('voter_list_voters_info_servery_users.skip_reason','=',$uc_survey->skip_reason)
                            ->select('voter_list_voters_info_servery_users.*', DB::raw('CASE WHEN voter_list_voters_info_servery_users.skip_reason = "" THEN "completed" ELSE voter_list_voters_info_servery_users.skip_reason END AS skip_reason'))
                                ->get()->toArray();
                                //->get()->count();
                                $survey_type[$ii]['type'] = ($uc_survey->skip_reason == "")?"Completed":$uc_survey->skip_reason;
                                $survey_type[$ii]['voters'] = $uc_cnic12;

                            }
                            $user_blockcodes['voters_survey'] =$survey_type;


                        $response['survey'] =$user_blockcodes;

                         return $this->apiResponse->apiResponse(true, 'Worker Blockcode Survery', '', $response, 200);
                    }

                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }





    public function serveyGharana(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                        $current_time =  date('Y-m-d H:i:s', time());
                        $response_msg = 'Gharana Servery ';

                        if(isset($request['users']) && sizeof($request['users'])>0){

                            foreach ($request['users'] as $user){
                                if(isset($user['cnic']) && $user['cnic'] !=""){
                                        $data_update =array();
                                        $skip=isset($user['skip'])?$user['skip']:"";
                                        $data_update['skip'] = $skip;
                                        $data_update['created_at']=$current_time;

                                        $data_update['survey']=1;
                                        $data_update['party']=isset($user['party'])?$user['party']:'';
                                        $data_update['cnic']=isset($user['cnic'])?$user['cnic']:"";
                                        $data_update['swing_factor']=isset($user['swing_factor'])?$user['swing_factor']:"";
                                        $data_update['major_issues']=isset($user['major_issues'])?$user['major_issues']:"";
                                        $data_update['phone_number']=isset($user['phone_number'])?$user['phone_number']:"";

                                        $cast=isset($user['cast'])?$user['cast']:"";
                                        $language=isset($user['language'])?$user['language']:"";

                                        $data_update['cast']= $cast;
                                        $data_update['language']= $language;

                                        if($language != ""){
                                            $existLanguage = (array) DB::connection('mysql2')->table("languages")->where('title',$language)->first();
                                            if(sizeof($existLanguage)>0){ }else{
                                                DB::connection('mysql2')->table("languages")->insert(['title'=>$language]);
                                            }
                                        }

                                        if($cast != ""){
                                            $existCast = (array) DB::connection('mysql2')->table("casts")->where('title',$cast)->first();
                                            if(sizeof( $existCast)>0){ }else{
                                                DB::connection('mysql2')->table("casts")->insert(['title'=>$cast]);
                                            }
                                        }


                                        $data_update['education']=isset($user['education'])?$user['education']:"";
                                        $data_update['occupation']=isset($user['occupation'])?$user['occupation']:"";
                                        $data_update['skip_reason']=isset($user['skip_reason'])?$user['skip_reason']:"";
                                        $data_update['date_of_birth']=isset($user['date_of_birth'])?$user['date_of_birth']:"";
                                        $data_update['latitude']=isset($user['latitude'])?$user['latitude']:"";
                                        $data_update['longitude']=isset($user['longitude'])?$user['longitude']:"";
                                        if(isset($user['skip_reason']) && $user['skip_reason'] != ''){
                                            $data_update['skip'] = 1;
                                            $data_update['education']="";
                                            $data_update['occupation']='';
                                            //$data_update['skip_reason'] = '';
                                            $data_update['date_of_birth'] = "";
                                            $data_update['party']='';
                                            $data_update['swing_factor']="";
                                            $data_update['major_issues']="";
                                            $data_update['phone_number']="";
                                            $data_update['cast']= '';
                                            $data_update['language']= '';
                                        }else{
                                            $data_update['skip'] = 0;
                                        }
                                        $data_update['user_id']=isset($user_auth['id'])?$user_auth['id']:0;
                                        $data_update['national_assembly_id']=isset($user_auth['na_cons_id'])?$user_auth['na_cons_id']:0;
                                        $user_id=isset($user_auth['id'])?$user_auth['id']:0;
                                        $existUsr = (array) DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->where('cnic',$user['cnic'])->first();
                                        if(sizeof($existUsr)>0){
                                            $existUsr2 = (array) DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->where('cnic',$user['cnic'])->where('user_id',$user_id)->first();
                                            if(sizeof($existUsr2)>0){
                                                $data_update['updated_at']=$current_time;
                                                DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->where('cnic',$user['cnic'])->update($data_update);
                                            }else{
                                                $data_update['updated_at']=$current_time;
                                                DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->where('cnic',$user['cnic'])->update($data_update);
                                                //print_r($existUsr2);
                                               // $name = isset(DB::table("app_web_users")->where('id',$existUsr['user_id'])->first()->name)?DB::table("app_web_users")->where('id',$existUsr['user_id'])->first()->name:"";
                                               // $this_cnic = $user['cnic'];
                                               // $response_msg.= " this cnic# $this_cnic added by $name ";
                                            }
                                        }else{
                                            DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->insert($data_update);
                                        }



                                    if(isset($user['children'])){
                                            foreach ($user['children'] as $child){
                                                $child_arr =array();
                                                $child_arr['cnic']=$user['cnic'];
                                                $child_arr['name']=isset($child['name'])?$child['name']:'';
                                                $child_arr['b_form_number']=isset($child['b_form_number'])?$child['b_form_number']:'';
                                                $child_arr['date_of_birth']=isset($child['date_of_birth'])?$child['date_of_birth']:'';
                                                $child_arr['education']=isset($child['education'])?$child['education']:'';
                                                $child_arr['user_id']=  $user_id;
                                                $child_arr['created_at']=  $current_time;
                                                $b_form_number=isset($child['b_form_number'])?$child['b_form_number']:'';
                                                $existUsrChild = (array) DB::connection('mysql2')->table("voter_list_voters_info_childrens")->where('b_form_number',$b_form_number)->where('cnic',$user['cnic'])->first();
                                                if(sizeof( $existUsrChild)>0){
                                                    $existUsrChild2 = (array) DB::connection('mysql2')->table("voter_list_voters_info_childrens")->where('user_id',$user_id)->where('b_form_number',$b_form_number)->where('cnic',$user['cnic'])->first();
                                                    if(sizeof($existUsrChild2)>0){
                                                        $child_arr['updated_at']=$current_time;
                                                        DB::connection('mysql2')->table("voter_list_voters_info_childrens")->where('b_form_number',$b_form_number)->update($child_arr);

                                                    }
                                                }else{
                                                    DB::connection('mysql2')->table("voter_list_voters_info_childrens")->insert($child_arr);
                                                }




                                            }
                                        }

                                }
                            }

                        $data_query =array();
                        $all_count =array();
                         return $this->apiResponse->apiResponse(  true, $response_msg, '', $all_count, 200);
                    }else{
                        $data_query =array();
                        $all_count =array();
                         return $this->apiResponse->apiResponse(  false, 'CNIC Not found', '', $all_count, 400);
                    }
                       $data_query =array();
                        $all_count =array();
                         return $this->apiResponse->apiResponse(  true, $response_msg, '', $all_count, 200);
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }




    public function serveyGharanaWeb(Request $request){
        $current_time =  date('Y-m-d H:i:s', time());
        $response_msg = 'Gharana Servery ';

        if(isset($request['users']) && sizeof($request['users'])>0){

            foreach ($request['users'] as $user){
                if(isset($user['cnic']) && $user['cnic'] !=""){
                        $data_update =array();
                        $skip=isset($user['skip'])?$user['skip']:"";
                        $data_update['skip'] = $skip;
                        $data_update['created_at']=$current_time;

                        $data_update['survey']=1;
                        $data_update['party']=isset($user['party'])?$user['party']:'';
                        $data_update['cnic']=isset($user['cnic'])?$user['cnic']:"";
                        $data_update['swing_factor']=isset($user['swing_factor'])?$user['swing_factor']:"";
                        $data_update['major_issues']=isset($user['major_issues'])?$user['major_issues']:"";
                        $data_update['phone_number']=isset($user['phone_number'])?$user['phone_number']:"";

                        $cast=isset($user['cast'])?$user['cast']:"";
                        $language=isset($user['language'])?$user['language']:"";

                        $data_update['cast']= $cast;
                        $data_update['language']= $language;

                        if($language != ""){
                            $existLanguage = (array) DB::connection('mysql2')->table("languages")->where('title',$language)->first();
                            if(sizeof($existLanguage)>0){ }else{
                                DB::connection('mysql2')->table("languages")->insert(['title'=>$language]);
                            }
                        }

                        if($cast != ""){
                            $existCast = (array) DB::connection('mysql2')->table("casts")->where('title',$cast)->first();
                            if(sizeof( $existCast)>0){ }else{
                                DB::connection('mysql2')->table("casts")->insert(['title'=>$cast]);
                            }
                        }


                        $data_update['education']=isset($user['education'])?$user['education']:"";
                        $data_update['occupation']=isset($user['occupation'])?$user['occupation']:"";
                        $data_update['skip_reason']=isset($user['skip_reason'])?$user['skip_reason']:"";
                        $data_update['date_of_birth']=isset($user['date_of_birth'])?$user['date_of_birth']:"";
                        $data_update['latitude']=isset($user['latitude'])?$user['latitude']:"";
                        $data_update['longitude']=isset($user['longitude'])?$user['longitude']:"";
                        if(isset($user['skip_reason']) && $user['skip_reason'] != ''){
                            $data_update['skip'] = 1;
                            $data_update['education']="";
                            $data_update['occupation']='';
                            //$data_update['skip_reason'] = '';
                            $data_update['date_of_birth'] = "";
                            $data_update['party']='';
                            $data_update['swing_factor']="";
                            $data_update['major_issues']="";
                            $data_update['phone_number']="";
                            $data_update['cast']= '';
                            $data_update['language']= '';
                        }else{
                            $data_update['skip'] = 0;
                        }
                        $data_update['user_id']=isset($user['user_id'])?$user['user_id']:0;
                        $data_update['national_assembly_id']=1430;
                        $user_id=isset($user['user_id'])?$user['user_id']:0;
                        $existUsr = (array) DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->where('cnic',$user['cnic'])->first();
                        if(sizeof($existUsr)>0){
                            $existUsr2 = (array) DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->where('cnic',$user['cnic'])->where('user_id',$user_id)->first();
                            if(sizeof($existUsr2)>0){
                                $data_update['updated_at']=$current_time;
                                DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->where('cnic',$user['cnic'])->update($data_update);
                            }else{
                                $data_update['updated_at']=$current_time;
                                DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->where('cnic',$user['cnic'])->update($data_update);
                                //print_r($existUsr2);
                               // $name = isset(DB::table("app_web_users")->where('id',$existUsr['user_id'])->first()->name)?DB::table("app_web_users")->where('id',$existUsr['user_id'])->first()->name:"";
                               // $this_cnic = $user['cnic'];
                               // $response_msg.= " this cnic# $this_cnic added by $name ";
                            }
                        }else{
                            DB::connection('mysql2')->table("voter_list_voters_info_servery_users")->insert($data_update);
                        }



                    if(isset($user['children'])){
                            foreach ($user['children'] as $child){
                                $child_arr =array();
                                $child_arr['cnic']=$user['cnic'];
                                $child_arr['name']=isset($child['name'])?$child['name']:'';
                                $child_arr['b_form_number']=isset($child['b_form_number'])?$child['b_form_number']:'';
                                $child_arr['date_of_birth']=isset($child['date_of_birth'])?$child['date_of_birth']:'';
                                $child_arr['education']=isset($child['education'])?$child['education']:'';
                                $child_arr['user_id']=  $user_id;
                                $child_arr['created_at']=  $current_time;
                                $b_form_number=isset($child['b_form_number'])?$child['b_form_number']:'';
                                $existUsrChild = (array) DB::connection('mysql2')->table("voter_list_voters_info_childrens")->where('b_form_number',$b_form_number)->where('cnic',$user['cnic'])->first();
                                if(sizeof( $existUsrChild)>0){
                                    $existUsrChild2 = (array) DB::connection('mysql2')->table("voter_list_voters_info_childrens")->where('user_id',$user_id)->where('b_form_number',$b_form_number)->where('cnic',$user['cnic'])->first();
                                    if(sizeof($existUsrChild2)>0){
                                        $child_arr['updated_at']=$current_time;
                                        DB::connection('mysql2')->table("voter_list_voters_info_childrens")->where('b_form_number',$b_form_number)->update($child_arr);

                                    }
                                }else{
                                    DB::connection('mysql2')->table("voter_list_voters_info_childrens")->insert($child_arr);
                                }




                            }
                        }

                }
            }

        $data_query =array();
        $all_count =array();
         return $this->apiResponse->apiResponse(  true, $response_msg, '', $all_count, 200);
    }else{
        $data_query =array();
        $all_count =array();
         return $this->apiResponse->apiResponse(  false, 'CNIC Not found', '', $all_count, 400);
    }
       $data_query =array();
        $all_count =array();
         return $this->apiResponse->apiResponse(  true, $response_msg, '', $all_count, 200);


    
    }



    public function getAllCountCNIC(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                      //  echo "<pr>"; print_r($request->all()); die;

                        $result =0;
                        $nics = explode(',', $request['cnic_number']);
                        $start_time =  date('Y-m-d').' 00:00:05';
                        $current_time =  date('Y-m-d H:i:s', time());

                        $start_time =  date('Y-m-d').' 00:00:05';
                        $end_time =  date('Y-m-d 23:59:59');
                        $date =  date('Y-m-d');
                        foreach ($nics as $nic){
                            $nic = trim($nic);
                            if($nic != ""){
                                    $name ='';
                                    $cnic_no ='';
                                        // DB::table("voter_list_voters_cnic_histories")->insert(['name'=>$name,'cnic_number'=>$nic,'date'=>$date,'blockcode'=>$blockcode,'polling_station'=>$polling_station_name,'user_id'=>$user_auth['id'],'created_at'=>$current_time]);
                                         DB::table("voter_list_voters_cnic_histories")->insert(['name'=>$name,'cnic_number'=>$nic,'date'=>$date,'user_id'=>$user_auth['id'],'created_at'=>$current_time]);
                            }
                        }
                        //$data_query =    DB::table("voter_list_voters_cnic_histories")->where('user_id',$user_auth['id'])->groupBy('cnic_number')->pluck('cnic_number')->toArray();
                        $data_query =array();
                        $all_count =array('total'=>$data_query);
                         return $this->apiResponse->apiResponse(  true, 'Counter Updated', '', $all_count, 200);
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }




    public function getAllCountCNIC_PS_Blockcode(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                       //echo "<pr>"; print_r($request->all()); die;

                        $result =0;
                        $nics = explode(',', $request['cnic_number']);
                        $start_time =  date('Y-m-d').' 00:00:05';
                        $current_time =  date('Y-m-d H:i:s', time());

                        $start_time =  date('Y-m-d').' 00:00:05';
                        $end_time =  date('Y-m-d 23:59:59');
                        $date =  date('Y-m-d');
                        foreach ($nics as $i=> $nic){
                            $blockcodes = explode(',', $request['blockcodes']);
                            $polling_stations = explode('=', $request['polling_stations']);

                            $blockcode = isset($blockcodes[$i])?$blockcodes[$i]:0;
                            $polling_station_name = isset($polling_stations[$i])?$polling_stations[$i]:0;
                            $nic = trim($nic);
                            if($nic != ""){
                                $name ='';
                                 DB::table("voter_list_voters_cnic_histories")->insert(['name'=>$name,'cnic_number'=>$nic,'date'=>$date,'blockcode'=>$blockcode,'polling_station'=>$polling_station_name,'user_id'=>$user_auth['id'],'created_at'=>$current_time]);
                                  //   DB::table("voter_list_voters_cnic_histories")->insert(['name'=>$name,'cnic_number'=>$nic,'date'=>$date,'user_id'=>$user_auth['id'],'created_at'=>$current_time]);
                            }
                        }
                        $data_query =    DB::table("voter_list_voters_cnic_histories")->where('user_id',$user_auth['id'])->groupBy('cnic_number')->get()->count();
                        $all_count =array('total'=>$data_query);
                         return $this->apiResponse->apiResponse(  true, 'Counter Updated', '', $all_count, 200);
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }



    public function userCnicCount (Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                      //  echo "<pr>"; print_r($request->all()); die;
                        $data_query =    DB::table("voter_list_voters_cnic_histories")->where('user_id',$user_auth['id'])
                            //->groupBy('cnic_number')
                            ->orderBy('id') ->pluck('cnic_number')->toArray();
                        $all_count =array('total'=>$data_query);
                         return $this->apiResponse->apiResponse(  true, 'Counter Updated', '', $all_count, 200);
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }


    public function getLocation(Request $request){
        $validate_data = array();
        $validate_data['user_id'] = 'required';
        $validate_data['longitude'] = 'required';
        $validate_data['latitude'] = 'required';

        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            return $this->apiResponse->apiResponse(  false, 'Unprocessable',  $validator->errors()->first(), [], 422);
        }else{
            $current_time =  date('Y-m-d H:i:s', time());
            $record['user_id'] =$request['user_id'];
            $record['longitude'] =$request['longitude'];
            $record['latitude'] =$request['latitude'];
            $record['created_at'] =  date('Y-m-d H:i:s', time());
            $record['location'] =isset($request['location'])?$request['location']:"";
            DB::table("user_locations")->insert($record);
            $resopnse =array('Location'=>'Added Successfully');
            return $this->apiResponse->apiResponse(  true, 'Added Location', '', $resopnse, 200);
        }
    }


    public function getLocation123(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                        $validate_data = array();
                         $validate_data['user_id'] = 'required';
                         $validate_data['longitude'] = 'required';
                         $validate_data['latitude'] = 'required';

                        $validator = Validator::make($request->all(), $validate_data);
                        if ($validator->fails()) {
                            return $this->apiResponse->apiResponse(  false, 'Unprocessable',  $validator->errors()->first(), [], 422);
                        }else{
                            $current_time =  date('Y-m-d H:i:s', time());
                            $record['user_id'] =$request['user_id'];
                            $record['longitude'] =$request['longitude'];
                            $record['latitude'] =$request['latitude'];
                            $record['created_at'] =  date('Y-m-d H:i:s', time());
                            $record['location'] =isset($request['location'])?$request['location']:"";
                            DB::table("user_locations")->insert($record);
                            $resopnse =array('Location'=>'Added Successfully');
                            return $this->apiResponse->apiResponse(  true, 'Added Location', '', $resopnse, 200);
                        }
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }



    public function updateProfile(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {
                        $validate_data = array();
                       //  $validate_data['user'] = 'required';
                         $validate_data['polling_station'] = 'required';

                        $validator = Validator::make($request->all(), $validate_data);
                        if ($validator->fails()) {
                            return $this->apiResponse->apiResponse(  false, 'Unprocessable',  $validator->errors()->first(), [], 422);
                        }else{
                            $current_time =  date('Y-m-d H:i:s', time());
                             DB::table('app_web_users')->where('id',$user_auth['id'])->update(['polling_station'=>  $request['polling_station']]);
                            $resopnse =array('Location'=>'Profile Update Successfully');
                            return $this->apiResponse->apiResponse(  true, 'Profile Update Successfully12', '', $resopnse, 200);
                        }
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }

    public function getVoterInformationByCNIC(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {

                        $validate_data = array();
                        $validate_data['cnic_number'] = 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/';
                        $validator = Validator::make($request->all(), $validate_data);
                        if ($validator->fails()) {
                            return $this->apiResponse->apiResponse(  false, 'Unprocessable',  $validator->errors()->first(), [], 422);
                        }
                        else{
                            $nic = (array) DB::table('voter_list_voters_info')
                                //->join('delimitation_areas_2022_with_block_codes_update_till_07_9_22','delimitation_areas_2022_with_block_codes_update_till_07_9_22.census_block_code','voter_list_voters_info.blockcode')
                                ->where('cnic',$request['cnic_number'])->first();

                            if (sizeof($nic)>0){
                                $record =array();
                                $record['silsila_no'] =$nic['silsila_no'];
                                $record['gharana_no'] =$nic['gharana_no'];
                                $record['name'] =$nic['name'];
                                if($nic['father_husband'] ==1){
                                    $record['father_husband'] = "Father's Name";
                                }  if($nic['father_husband'] ==2){
                                    $record['father_husband'] = "Husband's Name";
                                }
                                $record['father_husband_name'] =$nic['father_husband_name'];
                                $record['cnic'] =$nic['cnic'];
                                $record['cnic_number'] =$nic['cnic_no'];
                                $record['age'] =$nic['age'];
                                $record['address'] =$nic['address'];
                                $record['blockcode'] =$nic['blockcode'];
                                $blockcode = (array) DB::table('delimitation_areas_2022_with_block_codes_update_till_07_9_22')

                                    ->where('census_block_code',$nic['blockcode'])->first();

                               // $record['polling_station'] =$blockcode['polling_station'];
                                $record['polling_station'] =isset($blockcode['polling_station'])?$blockcode['polling_station']:"";
                                $record['union_council'] =isset($blockcode['no_and_name_of_constituency'])?$blockcode['no_and_name_of_constituency']:"";
                                $record['polling_station'] =isset($blockcode['polling_station_name'])?$blockcode['polling_station_name']:"";
                                $record['ward_number'] =isset($blockcode['ward'])?$blockcode['ward']:"";
                                $record['tehsil'] =isset(DB::table('tehsils')->where('id',$blockcode['tehsil_id'])->first()->name)?DB::table('tehsils')->where('id',$blockcode['tehsil_id'])->first()->name:'';
                                $record['district'] =isset(DB::table('districts')->where('id',$blockcode['district_id'])->first()->name)?DB::table('districts')->where('id',$blockcode['district_id'])->first()->name:'';
                                $record['division'] =isset(DB::table('divisions')->where('id',$blockcode['division_id'])->first()->name)?DB::table('divisions')->where('id',$blockcode['division_id'])->first()->name:'';
                                $record['province'] =isset(DB::table('provinces')->where('id',$blockcode['province_id'])->first()->name)?DB::table('provinces')->where('id',$blockcode['province_id'])->first()->name:'';
                                $record['families'] =DB::table('voter_list_voters_info')
                                                    ->where('blockcode',$nic['blockcode'])->where('gharana_no',$nic['gharana_no'])
                                                    //->where('cnic', '!=', $nic['cnic'])
                                                   // ->select('silsila_no','gharana_no','name','cnic as cnic_number')

                                                    ->get()->toArray();

                                $resopnse =array('voter_information'=>$record);
                                return $this->apiResponse->apiResponse(  true, 'Get Voter Information Record', '', $resopnse, 200);

                            }else{
                                return $this->apiResponse->apiResponse(  false, '', 'No Record Found ', [], 401);
                            }

                        }

                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }

    public function syncUserData(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {




                        if($user_auth['data_availability'] == 'Particular'){
                            $user = $user_auth;
                            $response = array();
                            $user_district_tehsil = (array) DB::table('app_web_users_union_councils')->where('user_id',$user_auth['id'])->first();

                            $user_district = isset($user_district_tehsil['district_id'])?$user_district_tehsil['district_id']:0;
                            $user_tehsil = isset($user_district_tehsil['tehsil_id'])?$user_district_tehsil['tehsil_id']:0;
                            $ucs = AppWebUserUnionCouncil::where('user_id',$user_auth['id'])->pluck('uc')->toArray();

                            $ucs_data = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22 as delimitation_blockcode")
                                ->join('voter_list_blockcode_info','voter_list_blockcode_info.blockcode','delimitation_blockcode.census_block_code')
                                ->where('delimitation_blockcode.district_id',$user_district)
                                ->where('delimitation_blockcode.tehsil_id',$user_tehsil)
                                ->whereIn('delimitation_blockcode.no_and_name_of_constituency',$ucs)
                                ->select('voter_list_blockcode_info.*','delimitation_blockcode.polling_station_name')
                                ->groupBy('voter_list_blockcode_info.blockcode')
                                ->get()->toArray();



                            foreach ($ucs_data as $i=> $blockcode){
                                $response[$i]['blockcode']= $blockcode->blockcode;
                                $response[$i]['national_assembly_id']= $blockcode->national_assembly_id;
                                $response[$i]['national_assembly_name']= $blockcode->national_assembly_name;
                                $response[$i]['provincial_assembly_id']= $blockcode->provincial_assembly_id;
                                $response[$i]['provincial_assembly_name']= $blockcode->provincial_assembly_name;
                                $response[$i]['district_id']= $blockcode->district_id;
                                $response[$i]['district_name']= $blockcode->district_name;
                                $response[$i]['tehsil_id']= $blockcode->taluka_id;
                                $response[$i]['tehsil_name']= $blockcode->taluka_name;
                                $response[$i]['village_city']= $blockcode->village_city;
                                $response[$i]['union_council']= $blockcode->union_council;
                                $response[$i]['eloctoral_area_name']= $blockcode->eloctoral_area_name;
                                $response[$i]['male_voters']= $blockcode->male_voters;
                                $response[$i]['female_voters']= $blockcode->female_voters;
                                $response[$i]['total_voters']= $blockcode->total_voters;
                                $response[$i]['circle_name']= $blockcode->circle_name;
                                $response[$i]['polling_station_name']= $blockcode->polling_station_name;
                                $response[$i]['voter_information']=  DB::table("voter_list_voters_info")
                                    ->where('blockcode',$blockcode->blockcode)
                                    ->select('id','silsila_no','gharana_no','name',DB::raw('(CASE
                                                WHEN father_husband = 2 THEN "Husbands Name"
                                                WHEN father_husband = 1 THEN "Fathers Name"
                                                ELSE "Fathers Name"
                                            END) AS father_or_husband'),
                                  'father_husband_name','cnic','age','address')
                                    ->get()
                                    ->toArray();

                                        if($i >10){
                                           // break;
                                        }
                            }

                            $all_response['records'] = $response;
                            //  print_r($all_response); die;

                            if(sizeof($response)>0){
                                return $this->apiResponse->apiResponse(  true, 'Successfully Synced', '', $all_response, 200);

                            }else{
                                return $this->apiResponse->apiResponse(  false, 'Synced Not Data', '', $all_response, 401);

                            }


                        }else{
                            return $this->apiResponse->apiResponse(  false, '', 'You have not axis to Syncing data', [], 401);

                        }
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }


    public function syncUserDataUcDelimitation(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {




                        if($user_auth['data_availability'] == 'Particular'){
                            $user = $user_auth;
                            $response = array();
                            $user_district_tehsil = (array) DB::table('app_web_users_union_councils')->where('user_id',$user_auth['id'])->first();

                            $user_district = isset($user_district_tehsil['district_id'])?$user_district_tehsil['district_id']:0;
                            $user_tehsil = isset($user_district_tehsil['tehsil_id'])?$user_district_tehsil['tehsil_id']:0;
                            $ucs = AppWebUserUnionCouncil::where('user_id',$user_auth['id'])->pluck('uc')->toArray();

                            $ucs_data = DB::connection('mysql2')
                                ->table("delimitation_areas_2022_with_block_codes_update_till_07_9_22 as delimitation_blockcode")
                                ->where('district_id',$user_district)
                                ->where('tehsil_id',$user_tehsil)
                                ->whereIn('no_and_name_of_constituency',$ucs)
                                ->groupBy('census_block_code')
                                ->get()->toArray();


                            $all_response['ucs_data'] = $ucs_data;
                            //  print_r($all_response); die;

                            if(sizeof($ucs_data)>0){
                                return $this->apiResponse->apiResponse(  true, 'Successfully Synced Delimitation Data', '', $all_response, 200);

                            }else{
                                return $this->apiResponse->apiResponse(  false, 'Synced Not Data', '', $all_response, 401);

                            }


                        }else{
                            return $this->apiResponse->apiResponse(  false, '', 'You have not axis to Syncing data', [], 401);

                        }
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }


    public function syncUserDataBlockcodeInformation(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {




                        if($user_auth['data_availability'] == 'Particular'){
                            $user = $user_auth;
                            $response = array();
                            $user_district_tehsil = (array) DB::table('app_web_users_union_councils')->where('user_id',$user_auth['id'])->first();

                            $user_district = isset($user_district_tehsil['district_id'])?$user_district_tehsil['district_id']:0;
                            $user_tehsil = isset($user_district_tehsil['tehsil_id'])?$user_district_tehsil['tehsil_id']:0;
                            $ucs = AppWebUserUnionCouncil::where('user_id',$user_auth['id'])->pluck('uc')->toArray();

                            $ucs_blockcodes = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22")
                                ->where('district_id',$user_district)
                                ->where('tehsil_id',$user_tehsil)
                                ->whereIn('no_and_name_of_constituency',$ucs)
                                ->groupBy('census_block_code')
                                ->pluck('census_block_code')->toArray();
                            $ucs_data = DB::table("voter_list_blockcode_info")
                               ->whereIn('blockcode',$ucs_blockcodes)
                                ->get()->toArray();

                            $all_response['blockcodes'] = $ucs_data;

                            if(sizeof($ucs_data)>0){
                                return $this->apiResponse->apiResponse(  true, 'Successfully Synced Blockcode Information', '', $all_response, 200);

                            }else{
                                return $this->apiResponse->apiResponse(  false, 'Synced Not Data', '', $all_response, 401);

                            }
                        }else{
                            return $this->apiResponse->apiResponse(  false, '', 'You have not axis to Syncing data', [], 401);

                        }
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }


    public function syncUserDataVoterInformation(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {




                        if($user_auth['data_availability'] == 'Particular'){
                            $user = $user_auth;
                            $response = array();
                            $user_district_tehsil = (array) DB::table('app_web_users_union_councils')->where('user_id',$user_auth['id'])->first();

                            $user_district = isset($user_district_tehsil['district_id'])?$user_district_tehsil['district_id']:0;
                            $user_tehsil = isset($user_district_tehsil['tehsil_id'])?$user_district_tehsil['tehsil_id']:0;
                            $ucs = AppWebUserUnionCouncil::where('user_id',$user_auth['id'])->pluck('uc')->toArray();

                            $ucs_blockcodes = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22")
                                ->where('district_id',$user_district)
                                ->where('tehsil_id',$user_tehsil)
                                ->whereIn('no_and_name_of_constituency',$ucs)
                                ->groupBy('census_block_code')
                                ->pluck('census_block_code')->toArray();

                            $response =  DB::table("voter_list_voters_info")
                                ->whereIn('blockcode',$ucs_blockcodes)
                                ->select('blockcode','silsila_no','gharana_no','name','father_husband', 'father_husband_name','cnic','age','address','confirm_voter')
                                /*->select('id','silsila_no','gharana_no','name',DB::raw('(CASE
                                                WHEN father_husband = 2 THEN "Husbands Name"
                                                WHEN father_husband = 1 THEN "Fathers Name"
                                                ELSE "Fathers Name"
                                            END) AS father_or_husband'),
                                    'father_husband_name','cnic','age','address')*/
                                ->get()
                                ->toArray();

                            $all_response['voters'] = $response;

                            if(sizeof($response)>0){
                                return $this->apiResponse->apiResponse(  true, 'Successfully Synced Voter Information', '', $all_response, 200);

                            }else{
                                return $this->apiResponse->apiResponse(  false, 'Synced Not Data', '', $all_response, 401);

                            }

                        }else{
                            return $this->apiResponse->apiResponse(  false, '', 'You have not axis to Syncing data', [], 401);

                        }
                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }

    public function syncUserDataVoterList(Request $request){
        $headers = apache_request_headers();
        if(sizeof($headers)>0 && isset($headers['Apitoken']) ){
            $api =  (array) DB::table('api_token')->where('token',$headers['Apitoken'])->first();
            if(is_array($api) && sizeof($api)>0){
                if (isset($headers['Authorization']) && $headers['Authorization'] !=""){
                    $user_auth =  (array) DB::table('app_web_users')->where('auth_token',$headers['Authorization'])->first();
                    if(is_array($user_auth) && sizeof($user_auth)>0) {

                            $response =  DB::table("voter_list_voters_info")
                                ->select('blockcode','silsila_no','gharana_no','cnic','age','gender','national_assembly_id','national_assembly_name','national_assembly_candidate','provincial_assembly_id','provincial_assembly_name','provincial_assembly_candidate','polling_station')
                                ->limit(50000)
                                ->get()
                                ->toArray();

                            $all_response['voters'] = $response;

                            if(sizeof($response)>0){
                                return $this->apiResponse->apiResponse(  true, 'Successfully Synced Voter Information', '', $all_response, 200);

                            }else{
                                return $this->apiResponse->apiResponse(  false, 'Synced Not Data', '', $all_response, 401);

                            }


                    }
                    else{
                        return $this->apiResponse->apiResponse(  false, '', 'Invalid Auth Token ', [], 403);
                    }
                }else{
                    return $this->apiResponse->apiResponse(  false, '', 'Auth Token Not exist', [], 401);
                }
            }else{
                return $this->apiResponse->apiResponse(  false, '', 'Invalid API token', [], 401);
            }
        }else{
            return $this->apiResponse->apiResponse(  false, '', 'Please enter Api Token in header section', [], 401);
        }
    }

}
