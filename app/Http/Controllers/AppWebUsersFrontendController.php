<?php

namespace App\Http\Controllers;

use App\Models\User;
use \DB;
use Validator;
use Illuminate\Support\Facades\Hash;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use App\Models\UserBlockcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
class AppWebUsersFrontendController extends Controller
{



    public function sendLocationLocal(Request $request){

        $response =array('status'=>1, 'message'=>'sent');
        $record['user_id'] =isset($request['user_id'])?$request['user_id']:0;
         $record['latitude'] =isset($request['latitude'])?$request['latitude']:0;
         $record['longitude'] =isset($request['longitude'])?$request['longitude']:0;
         $record['created_at'] =isset($request['created_at'])?$request['created_at']:null;
         DB::table("user_locations")->insert($record);

        return $response;
    }

    public function sendLocation(Request $request){

            //   //  print_r($request->all()); die;
            //     $response =array('status'=>0, 'message'=>'Empty');

            //     $record['user_id'] =isset($request['user_id'])?$request['user_id']:0;
            //     $record['latitude'] =isset($request['latitude'])?$request['latitude']:0;
            //     $record['longitude'] =isset($request['longitude'])?$request['longitude']:0;
            //     $record['created_at'] =$end_time =  date('Y-m-d H:i:s', time());
            //     DB::table("user_locations")->insert($record);
            //     $response =array('status'=>1, 'message'=>'Synced Voter');

            //     return $response;
            $response =array('status'=>0, 'message'=>'Empty');
            $existLocation =  (array) DB::table("user_locations")->where('user_id',$request['user_id'])->orderBy('id',"DESC")->first();
            $current_time =  date('Y-m-d H:i:s', time());

            if(sizeof(  $existLocation )>0){

            $old_time = $existLocation['created_at'];

            $newDateTime = new DateTime($current_time );
            $oldDateTime  = new DateTime($old_time);

            $timeDifference = $oldDateTime->diff($newDateTime);

            $difference =  $timeDifference->format('%i');
            if($difference >15){

                $record['user_id'] =isset($request['user_id'])?$request['user_id']:0;
                $record['latitude'] =isset($request['latitude'])?$request['latitude']:0;
                $record['longitude'] =isset($request['longitude'])?$request['longitude']:0;
                $record['created_at'] =  date('Y-m-d H:i:s', time());
                DB::table("user_locations")->insert($record);
                $response =array('status'=>1, 'message'=>'user history');
            }


            //  print_r( $existLocation); die;

            }else{

                $response =array('status'=>0, 'message'=>'Empty');
                $record['user_id'] =isset($request['user_id'])?$request['user_id']:0;
                $record['latitude'] =isset($request['latitude'])?$request['latitude']:0;
                $record['longitude'] =isset($request['longitude'])?$request['longitude']:0;
                $record['created_at'] =  date('Y-m-d H:i:s', time());
                DB::table("user_locations")->insert($record);
                $response =array('status'=>1, 'message'=>'user history');
            }


            return $response;
    }
    public function registerSubmitAPI(Request $request){
        $validate_data = [
            'phone_number' => 'required|digits:11|unique:app_web_users,phone_number',
            'name' => 'required',
           // 'polling_station' => 'required'
        ];
        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            $validator_error = array();
            foreach ($validator->errors()->toArray() as $i => $error) {
                $validator_error[$i] = $error[0];
            }
            return response()->json(['status' => 0, 'message' => $validator_error, 'data' =>  (object) array()]);
        } else {

            $auth_code = Hash::make($request['cnic_number']);
            $user = array();
            $user['name'] = $request['name'];
            $user['email'] = isset($request['email']) ? $request['email'] : "";
          //  $user['na_cons_id'] = isset($request['na_cons_id']) ? $request['na_cons_id'] :0;
            // $user['cnic_number'] = isset($request['cnic_number']) ? $request['cnic_number'] : "";
           // $user['polling_station'] = isset($request['polling_station']) ? $request['polling_station'] : "";
            $user['phone_number'] = $request['phone_number'];
            $user['auth_token'] = $auth_code;
            $user['created_at'] = date('Y-m-d H:i:s', time());
           // $user['polling_id'] = $request['polling_station'];

           // $ps =  (explode("-",$request['polling_station']));

          //  $polling_station_name = DB::table("voter_list_polling_scheme_na")->where('id',$ps[0])->where('nat_cons_id',$ps[1])->first()->polling_station;

            //$polling_station = $polling_station_name . ' (' . implode(", ", DB::table("voter_list_polling_scheme_na")->where('nat_cons_id', $ps[1])->where('polling_station', $polling_station_name)->pluck('blockcode')->toArray()) . ')';
            $polling_station ='';

            $user['polling_station'] = $polling_station;
           $user['na_cons_id'] = 1430;

            $id = DB::table('app_web_users')->insertGetId($user);
            $user['id'] = $id;
            //Session::put('auth_token', $auth_code);
            //Session::put('auth_id', $id);
            if (!empty($id)) {
                $success['success'] = 'Register Successfully';
                $validator_error = $success;
                $data['user'] = $user;
                return response()->json(['status' => 1, 'message' => $validator_error, 'data' => $data, array()]);
            } {
                $validator_error['error'] = "Some thing went wrong in During Register";
                return response()->json(['status' => 0, 'message' => $validator_error, 'data' =>  (object) array()]);
            }


        }
    }




    public function loginSubmitAPI(Request $request){
        $validate_data = [
            'phone_number' => 'required|digits:11|exists:app_web_users,phone_number'
        ];
        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            $validator_error = array();
            foreach ($validator->errors()->toArray() as $i => $error) {
                $validator_error[$i] = $error[0];
            }
            return response()->json(['status' => 0, 'message' => $validator_error, 'data' =>  (object) array()]);
        } else {

            $exist = (array) DB::table('app_web_users')->where('phone_number', $request['phone_number'])->first();
            if (sizeof($exist) > 0) {
                if ($exist['kill_switch']  == 1) {
                      DB::table('app_web_users')->where('phone_number', $request['phone_number'])->update(['auth_token' => null]);
                   // $data = ['kill_switch' => 1];
                    $validator_error['error'] ='You have not access to login';
                    return response()->json(['status' => 0, 'message' => $validator_error, 'data' =>  (object) array()]);
                }else{
                    $auth_code = Hash::make($request['phone_number']);
                    $user_data['token'] = $auth_code;
                    DB::table('app_web_users')->where('id', $exist['id'])->update(['auth_token' => $auth_code]);
                    $user['id'] = $exist['id'];
                    $user['name'] = $exist['name'];
                    $user['cnic'] = $exist['cnic'];
                    $user['phone_number'] = $exist['phone_number'];
                    $user['auth_token'] = $auth_code;
                    $user['na_cons_id'] = $exist['na_cons_id'];
                    $user['polling_id'] = $exist['polling_id'];
                   // $user['na_name'] = isset(DB::table("voter_list_polling_scheme_na")->where('id', $exist['na_cons_id'])->first()->na_name) ? DB::table("voter_list_polling_scheme_na")->where('id', $exist['na_cons_id'])->first()->na_name : "";
                    $user['polling_station'] = $exist['polling_station'];

                    $success['success'] = 'Login Successfully';
                    $validator_error = $success;
                    $data['user'] = $user;
                    return response()->json(['status' => 1, 'message' => $validator_error, 'data' => $data, array()]);
                }
            }else{
                $validator_error['error'] ='Invalid Credentials';
                return response()->json(['status' => 0, 'message' => $validator_error, 'data' =>  (object) array()]);
            }
        }
    }



    public function updateProfileSubmitAPI(Request $request){
        $validate_data = [
            'polling_station' => 'required',
            'auth_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            $validator_error = array();
            foreach ($validator->errors()->toArray() as $i => $error) {
                $validator_error[$i] = $error[0];
            }
            return response()->json(['status' => 0, 'message' => $validator_error, 'data' =>  (object) array()]);
        } else {

            $exist = (array) DB::table('app_web_users')->where('id', $request['auth_id'])->first();
            if (sizeof($exist) > 0) {
                $user['polling_id'] = $request['polling_station'];

                $ps =  (explode("-",$request['polling_station']));

                $polling_station_name = DB::table("voter_list_polling_scheme_na")->where('id',$ps[0])->where('nat_cons_id',$ps[1])->first()->polling_station;

                $polling_station = $polling_station_name . ' (' . implode(", ", DB::table("voter_list_polling_scheme_na")->where('nat_cons_id', $ps[1])->where('polling_station', $polling_station_name)->pluck('blockcode')->toArray()) . ')';

               // $user['polling_station'] = $polling_station;
               // $user['na_cons_id'] = $ps[1];


                DB::table('app_web_users')->where('id', $exist['id'])->update(['polling_station' => $polling_station,'na_cons_id' =>  $ps[1],'polling_id' =>  $request['polling_station']]);


                $user['id'] = $exist['id'];
                $user['name'] = $exist['name'];
                $user['cnic'] = $exist['cnic'];
                $user['phone_number'] = $exist['phone_number'];
                $user['na_cons_id'] = $exist['na_cons_id'];
                $user['polling_id'] = $request['polling_station'];
                $user['polling_station'] =  $polling_station;

                $success['success'] = 'Profile Updated Successfully';
                $validator_error = $success;
                $data['user'] = $user;
                return response()->json(['status' => 1, 'message' => $validator_error, 'data' => $data, array()]);

            }else{
                $validator_error['error'] ='Invalid Credentials';
                return response()->json(['status' => 0, 'message' => $validator_error, 'data' =>  (object) array()]);
            }
        }
    }







    public function loginForm()
    {
        return view('app_web_users.login');
    }



    public function registerForm()
    {
        $data = array();
        //   $data['districts'] = DB::table("delimitation_areas_2022_with_block_codes_update_till_07_9_22")

        $slug = 1384;
        $pollings = DB::table("voter_list_polling_scheme_na")->select('id', 'polling_station','nat_cons_id')->whereIn('nat_cons_id',[1384,1386])->groupby('polling_station')->get()->toArray();
        $stations = array();
        foreach ($pollings as $i => $polling) {
            $stations[$i]['id'] =$polling->id .'-'.$polling->nat_cons_id;
            $stations[$i]['na_cons_id'] = $polling->nat_cons_id;

            $stations[$i]['polling_station'] = $polling->polling_station . ' (' . implode(", ", DB::table("voter_list_polling_scheme_na")->where('nat_cons_id', $polling->nat_cons_id)->where('polling_station', $polling->polling_station)->pluck('blockcode')->toArray()) . ')';
        }
        $user_data['stations'] = $stations;

            //  echo "<pre>"; print_r($user_data); die;
        return view('app_web_users.register', $user_data);
    }

    public function registerSubmit(Request $request){

        $validate_data = [
            'phone_number' => 'required|size:11|unique:app_web_users,phone_number',
            'name' => 'required',
            'polling_station' => 'required',
        ];
        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $user = array();
            $user['name'] = $request['name'];
            $user['email'] = isset($request['email']) ? $request['email'] : "";
            $user['cnic_number'] = isset($request['cnic_number']) ? $request['cnic_number'] : "";
            $user['cnic'] = isset($request['cnic']) ? $request['cnic'] : "";


            $user['phone_number'] = $request['phone_number'];
            $user['data_availability'] = $request['data_availability'];
            $user['created_at'] = date('Y-m-d H:i:s', time());

            $ps =  (explode("-",$request['polling_station']));

            $polling_station_name = DB::table("voter_list_polling_scheme_na")->where('id',$ps[0])->where('nat_cons_id',$ps[1])->first()->polling_station;

            $polling_station = $polling_station_name . ' (' . implode(", ", DB::table("voter_list_polling_scheme_na")->where('nat_cons_id', $ps[1])->where('polling_station', $polling_station_name)->pluck('blockcode')->toArray()) . ')';

            $user['polling_station'] = $polling_station;
            $user['na_cons_id'] = $ps[1];

            $id = DB::table('app_web_users')->insert($user);

            if (!empty($id)) {
                return redirect('/web/login')->with('success', 'Register User');
            }
        }
    }

    public function registerSubmitPhone(Request $request)
    {
        $user = array();
        $user['name'] = $request['name'];
        // $user['email'] = isset($request['email']) ? $request['email'] : "";
        // $user['cnic_number'] = isset($request['cnic_number']) ? $request['cnic_number'] : "";
        // $user['cnic'] = isset($request['cnic']) ? $request['cnic'] : "";
        $user['polling_station'] = isset($request['polling_station']) ? $request['polling_station'] : "";

        $user['phone_number'] = $request['phone_number'];
        // $user['data_availability'] = $request['data_availability'];
        $user['created_at'] = date('Y-m-d H:i:s', time());
        $id = DB::table('app_web_users')->insert($user);
        if (!empty($id)) {
            // return redirect('/web/login')->with('success', 'Register User');
            return response()->json(['status' => 1, 'message' => 'User Registered successfully','http_status' => 200]);
        }
    }



    public function loginSubmit(Request $request)
    {
       // $input = $request->only(['cnic_number', 'phone_number']);

        $validate_data = [
            'phone_number' => 'required|digits:11|exists:app_web_users,phone_number',
        ];
        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $exist = (array) DB::table('app_web_users')->where('phone_number', $request['phone_number'])->first();
        if (sizeof($exist) > 0) {

            if ($exist['kill_switch']  == 1) {
                $success =  DB::table('app_web_users')->where('phone_number', $request['phone_number'])->update(['auth_token' => null]);
                return redirect()->back()->with('error', 'You have not aceess to login');
            } else {

                $ps =  (explode("(",$exist['polling_station']));
                $blockcode =  end($ps);

                $blockcodes =  str_replace(")","", $blockcode);

                $auth_code = Hash::make($request['phone_number']);
                $user_data['token'] = $auth_code;
                Session::put('auth_token', $auth_code);
                Session::put('auth_id', $exist['id']);
                Session::put('blockcodes',$blockcodes) ;
                DB::table('app_web_users')->where('id', $exist['id'])->update(['auth_token' => $auth_code]);


                $user['id'] = $exist['id'];
                $user['name'] = $exist['name'];
                $user['email'] = isset($exist['email']) ? $exist['email'] : "";
                $user['cnic_number'] = isset($exist['cnic_number']) ? $exist['cnic_number'] : "";
                $user['cnic'] = isset($exist['cnic']) ? $exist['cnic'] : "";
                $user['polling_station'] = isset($exist['polling_station']) ? $exist['polling_station'] : "";

                $user['phone_number'] = $exist['phone_number'];
                $user['data_availability'] = $exist['data_availability'];
                $user['auth_id'] = $exist['id'];


                //print_r($user) ; die;
                //  $user['tehsil'] =isset(DB::table('tehsils')->where('id',$exist['tehsil_id'])->first()->name)?DB::table('tehsils')->where('id',$exist['tehsil_id'])->first()->name:'';

                //$user['union_councils'] = DB::table('app_web_users_union_councils')->where('user_id',$user['id'])->pluck('uc')->toArray();


                $user_data['user'] = $user;
                return redirect('/web/dashboard')->with('success', 'Logged in successfully');
            }
        } else {
            return redirect()->back()->with('error', 'Incorrect CNIC Number or Phone Number');
        }
    }

    public function loginSubmitPhone(Request $request){


        $exist = (array) DB::table('app_web_users')->where('phone_number', $request['phone_number'])->first();
        if (sizeof($exist) > 0) {

            if ($exist['kill_switch']  == 1) {
                $success =  DB::table('app_web_users')->where('phone_number', $request['phone_number'])->update(['auth_token' => null]);
                $data = ['kill_switch' => 1];
                return response()->json(['status' => 0, 'message' => 'You have not aceess to login', 'data' =>  (object) array()]);
            } else {
                $auth_code = Hash::make($request['phone_number']);
                $user_data['token'] = $auth_code;
                DB::table('app_web_users')->where('id', $exist['id'])->update(['auth_token' => $auth_code]);

                Session::put('auth_token', $auth_code);
                Session::put('auth_id', $exist['id']);

                $user['id'] = $exist['id'];
                $user['name'] = $exist['name'];
                $user['email'] = $exist['email'];
                $user['cnic'] = $exist['cnic'];
                $user['cnic_number'] = $exist['cnic_number'];
                $user['phone_number'] = $exist['phone_number'];
                $user['na_cons_id'] = $exist['na_cons_id'];
                $user['na_name'] = isset(DB::table("voter_list_polling_scheme_na")->where('id', $exist['na_cons_id'])->first()->na_name) ? DB::table("voter_list_polling_scheme_na")->where('id', $exist['na_cons_id'])->first()->na_name : "";
                $user['polling_station'] = $exist['polling_station'];
                $user['data_availability'] = $exist['data_availability'];
                $user['kill_switch'] = $exist['kill_switch'];
                $user['tehsil'] = isset(DB::table('tehsils')->where('id', $exist['tehsil_id'])->first()->name) ? DB::table('tehsils')->where('id', $exist['tehsil_id'])->first()->name : '';

                $user['union_councils'] = DB::table('app_web_users_union_councils')->where('user_id', $user['id'])->pluck('uc')->toArray();


                $user_data['user'] = $user;
                return response()->json(['status' => 1, 'message' => 'Logged in successfully', 'data' =>  (object) $user_data, 'http_status' => 200]);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Invalid Credientials', 'data' =>  (object) [], 'http_status' => 401]);

        }
    }

    public function loginSubmitAPIBK(Request $request)
    {
        $auth_id =   Session::get('auth_id');
        $validate_data = [
            'phone_number' => 'required|digits:11|exists:app_web_users,phone_number',
        ];
        $validator = Validator::make($request->all(), $validate_data);
        if ($validator->fails()) {
            $validator_error = array();
            foreach ($validator->errors()->toArray() as $i => $error) {
                $validator_error[$i] = $error[0];
            }
            return response()->json(['status' => 0, 'message' => $validator_error, 'data' =>  (object) array()]);
        }else{

        }

        $data = ["phone_number" => $request['phone_number']];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://optimusdataaxis.ap-southeast-1.elasticbeanstalk.com/web/app-web-user/loginPhone',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => (array) $data,
            CURLOPT_HTTPHEADER => array(
                'Cookie: XSRF-TOKEN=eyJpdiI6IjlhODFFa1Zlcnk2NDlES3Y4UVhuMkE9PSIsInZhbHVlIjoiQklWNEwvRFdVSmF2Y1E2U1pTeElrWVcyRUFGVXlvb3FiQmVodEZNM0l5QW1vMHFoZjdxbHdwRWdTVnFSUjhyUjJ5bnAwaGk4MktnRlllVE5NQVNTMmJqUjUvUVRGcFBzQm9meG9vdTBkdTdKYkRvT1RUT0lnK0VnMGRLdi9QaWwiLCJtYWMiOiJhYzE2YzQ0MzBmNmMwOTQwYjNkZjM1ZGE5YjUyYjcwOWZlZDdiODIwNGMyYWIxM2EzOThkNjY0YTE2Y2I1M2E1IiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6IjNjUXBKcEF3aGRTVDRheEZtNFh4VkE9PSIsInZhbHVlIjoiV2RwQWRMc0ZTZFA2YklIWVRCNy9jbW50UHZtankvbVM0akcrWkRQczE1bWM4cUtFQ0RtNFZTa0lhekM3aTNKZ05LOHdmbFpyeU9kTFUvRWliUC9FSDlTdThyd2wzb1hIMjZVcWIxNUFrR3U5dTBuY3YyWHhCc240ZHVoOTdKdEgiLCJtYWMiOiI1YjY1YjQxZjRkNmFlOGEyN2NkMmMwZDdkMTZmN2E4ZjU4NDk0ZjRkNDllYzQ3YTFiNTgwZGIyODBjNTE3MDhjIiwidGFnIjoiIn0%3D'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        // return $response;
        $data = json_decode($response, true);
        if($data['status'] == 1)
        {
            return redirect('/web/dashboard')->with('success', 'Logged in successfully');
        }
        else{
            return redirect()->back()->with('error','Incorrect Phone Number');
        }
    }

    public function accessTokenProcess()
    {
        if (isset($_POST['access_token'])) {
            $access_token = $_POST['access_token'];
            // Use the access token as needed (e.g., store it in a database, perform actions, etc.)
            echo "Received access token: " . $access_token;
        } else {
            echo "No access token received";
        }
    }

    public function dashboard()
    {
        $auth_id =   Session::get('auth_id');

      //  echo $auth_id;
        //  $history = DB::table("voter_list_voters_cnic_histories")->where('user_id', $auth_id)->where('sync', 0)->get()->toArray();
        //  if(sizeof( $history) > 0){
        //      foreach ($history as $hist) {
        //       //  echo "<pre>"; print_r($hist);
        //          $exist_date = (array) DB::table("voter_list_voters_cnic_histories")->where('cnic_number', $hist->cnic_number)->where('date', $hist->date)->where('user_id', $hist->user_id)->first();
        //        //  echo "<pre>"; print_r($exist_date);
        //                if (sizeof($exist_date) > 0) {
        //                  //  echo "tetet4646456e";
        //          } else {
        //               //     echo "tetete";
        //              //DB::table("voter_list_voters_cnic_histories")->insert(['name' => $hist->name, 'cnic_number' => $hist->cnic_number, 'date' => $hist->date, 'user_id' => $hist->user_id, 'created_at' => $hist->created_at]);
        //                    $name =isset($hist->name)?$hist->name:"";
        //                    $cnic_no =isset($hist->cnic_number)?$hist->cnic_number:"";
        //                    $date =  date('Y-m-d', time());
        //                    $end_time =  date('Y-m-d H:i:s', time());
        //                    $auth_id =   Session::get('auth_id');
        //                    $voter = (array)DB::table("voter_list_voters_info")->where('cnic',$cnic_no)->first();
        //                    $blockcode = isset($voter['blockcode'])?$voter['blockcode']:0;
        //                    $gender = isset($voter['gender'])?$voter['gender']:0;
        //                    $polling_station_name = '';
        //                    $polling_stations =  (array)DB::table("voter_list_polling_scheme_na")->where('blockcode',$blockcode)->where('polling_type',$gender)->first();
        //                    if(sizeof($exist_date) > 0){
        //                    $polling_station_name = $polling_stations['polling_station'];
        //                    }else{
        //                     $polling_station_combile =  (array)DB::table("voter_list_polling_scheme_na")->where('blockcode',$blockcode)->where('polling_type','COMBINED')->first();
        //                       $polling_station_name = isset($polling_station_combile['polling_station'])?$polling_station_combile['polling_station']:'';
        //                     }
        //                    DB::table("voter_list_voters_cnic_histories")->insert(['name'=>$name,'cnic_number'=>$cnic_no,'date'=>$date,'blockcode'=>$blockcode,'polling_station'=>$polling_station_name,'user_id'=>$auth_id,'created_at'=>$end_time]);
        //                    DB::table("voter_list_voters_cnic_histories")->where('id', $hist->id)->update(['sync' => 1]);
        //          }
        //      }
        //  }
     //   die;
//
//         if (Session::has('auth_token')) {
//            $auth_token =   Session::get('auth_token');
//            $auth_id =   Session::get('auth_id');
//            return view('app_web_users.dashboard', ['auth_id' => $auth_id]);
//        } else {
//            return redirect('web/login')->with('error', 'Sorry Something went Try Again ');
//        }



        $auth_token =   Session::get('auth_token');
        $auth_id =   Session::get('auth_id');
        //
        if ($auth_id != "") {
            // $history = DB::table("voter_list_voters_cnic_histories")->where('user_id', $auth_id)->where('sync', 0)->get()->toArray();
            // if(sizeof( $history) > 0){
            //     foreach ($history as $hist) {
            //         $exist_date = (array) DB::table("voter_list_voters_cnic_histories")->where('cnic_number', $hist->cnic_number)->where('date', $hist->date)->where('user_id', $hist->user_id)->first();
            //         if (sizeof($exist_date) > 0) {
            //         } else {
            //             DB::table("voter_list_voters_cnic_histories")->insert(['name' => $hist->name, 'cnic_number' => $hist->cnic_number, 'date' => $hist->date, 'user_id' => $hist->user_id, 'created_at' => $hist->created_at]);
            //             DB::table("voter_list_voters_cnic_histories")->where('id', $hist->id)->update(['sync' => 1]);
            //         }
            //     }
            // }
            return view('app_web_users.dashboard', ['auth_id' => $auth_id]);
        } else {
            return redirect('web/login')->with('error', 'Sorry Something went Login Again ');
        }
    }

    public function dashboardVoterlist(){
        $auth_id =   Session::get('auth_id');
        $history = DB::table("voter_list_voters_cnic_histories")->where('user_id', $auth_id)->where('sync', 0)->get()->toArray();
        if(sizeof( $history) > 0){
            foreach ($history as $hist) {
                $exist_date = (array) DB::table("voter_list_voters_cnic_histories")->where('cnic_number', $hist->cnic_number)->where('date', $hist->date)->where('user_id', $hist->user_id)->first();
                if (sizeof($exist_date) > 0) {
                } else {
                    DB::table("voter_list_voters_cnic_histories")->insert(['name' => $hist->name, 'cnic_number' => $hist->cnic_number, 'date' => $hist->date, 'user_id' => $hist->user_id, 'created_at' => $hist->created_at]);
                    DB::table("voter_list_voters_cnic_histories")->where('id', $hist->id)->update(['sync' => 1]);
                }
            }
        }
        return view('app_web_users.dashboard', ['auth_id' => $auth_id]);
        echo  $auth_id ;
    }

    public function searchPDF()
    {


        if (Session::has('auth_token')) {
            $auth_token =   Session::get('auth_token');
            $auth_id =   Session::get('auth_id');
            return view('app_web_users.search_pdf', ['auth_id' => $auth_id]);
        } else {
            return redirect('web/login')->with('error', 'Sorry Something went Try Again ');
        }
    }



    public function searchCnicNumber(Request $request)
    {

        $response = array('status' => 0, 'message' => 'Not Found', 'data' => array());
        $data =  ['cnic_no' => 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/'];
        $validator = Validator::make($request->all(), $data);
        if ($validator->fails()) {
            $response = array('status' => 0, 'message' => 'Please Valid CNIC Number', 'data' => array());
        } else {
            $nic = (array) DB::table('voter_list_voters_info')->where('cnic', $request['cnic_no'])->first();
            if (sizeof($nic) > 0) {
                $response = array('status' => 1, 'message' => 'Voter Found', 'data' => $nic);
            } else {
                $response = array('status' => 0, 'message' => 'Voter Not Found Please Search Again', 'data' => array());
            }
        }

        return $response;
    }

    public function searchBloccode(Request $request)
    {

        $response = array('status' => 0, 'message' => 'Not Found', 'data' => array());
        $data =  ['blockcode' => 'required|integer|digits_between:8,11'];
        $validator = Validator::make($request->all(), $data);
        if ($validator->fails()) {
            $response = array('status' => 0, 'message' => 'Please Valid CNIC Number', 'data' => array());
        } else {
            $nic = (array) DB::table('voter_list_blockcode_info')->where('blockcode', $request['blockcode'])->first();
            if (sizeof($nic) > 0) {
                $response = array('status' => 1, 'message' => 'Blockcode Available', 'data' => $nic);
            } else {
                $response = array('status' => 0, 'message' => 'Blockcode Not Found', 'data' => array());
            }
        }

        return $response;
    }


    public function voterDataView(Request $request)
    {



        if (Session::has('auth_token')) {
            //            $auth_token =   Session::get('auth_token');
            //            $auth_id =   Session::get('auth_id');
            //            return view('app_web_users.search_pdf',['auth_id'=>$auth_id]);

            $data =  ['cnic_number' => 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/'];
            $validator = Validator::make($request->all(), $data);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $nic = (array) DB::table('voter_list_voters_info')->where('cnic', $request['cnic_number'])->first();
                if (sizeof($nic) > 0) {
                    $name = isset($nic['name']) ? $nic['name'] : "";
                    $cnic_no = isset($nic['cnic']) ? $nic['cnic'] : "";
                    $date =  date('Y-m-d', time());
                    $end_time =  date('Y-m-d H:i:s', time());
                    $auth_id =   Session::get('auth_id');
//                    $exist_date = (array) DB::table("voter_list_voters_cnic_histories")->where('cnic_number', $cnic_no)->where('date', $date)->where('user_id', $auth_id)->first();
//                    if (sizeof($exist_date) > 0) {
//                    } else {
//                        DB::table("voter_list_voters_cnic_histories")->insert(['name' => $name, 'cnic_number' => $cnic_no, 'date' => $date, 'user_id' => $auth_id, 'created_at' => $end_time]);
//                    }

                    return view('app_web_users.voter_view', ['user' => $nic]);
                } else {
                    return redirect()->back()->with('status_error', 'Voter Not Found');
                }
            }
        } else {
            return redirect('web/login')->with('error', 'Sorry Something went Try Again ');
        }


        // print_r($request->all()); die;

    }
    public function viewBlockcodeData(Request $request)
    {
        if (Session::has('auth_token')) {
            $auth_token =   Session::get('auth_token');
            $auth_id =   Session::get('auth_id');
            $data =  ['blockcode' => 'required|integer|digits_between:8,11'];
            $validator = Validator::make($request->all(), $data);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $nic = (array) DB::table('voter_list_blockcode_info')->where('blockcode', $request['blockcode'])->first();
                if (sizeof($nic) > 0) {
                    $name = isset($nic['name']) ? $nic['name'] : "";
                    $cnic_no = isset($nic['cnic']) ? $nic['cnic'] : "";
                    $date =  date('Y-m-d', time());
                    $end_time =  date('Y-m-d H:i:s', time());
                    $auth_id =   Session::get('auth_id');
                    /*$exist_date = (array) DB::table("voter_list_voters_cnic_histories")->where('cnic_number',$cnic_no)->where('date',$date)->where('user_id',$auth_id)->first();
                    if(sizeof($exist_date) > 0){
                    }else{
                        DB::table("voter_list_voters_cnic_histories")->insert(['name'=>$name,'cnic_number'=>$cnic_no,'date'=>$date,'user_id'=>$auth_id,'created_at'=>$end_time]);
                    }*/
                    return view('app_web_users.blockcode_view', ['blockcode' => $nic]);
                } else {
                    return redirect()->back()->with('status', 'User Not Found');
                }
            }
        } else {
            return redirect('web/login')->with('error', 'Sorry Something went Try Again ');
        }
    }

    public function voterDataViewPrint($cnic)
    {


        if (Session::has('auth_token')) {
            $auth_token =   Session::get('auth_token');
            $auth_id =   Session::get('auth_id');
            $data['user'] = (array) DB::table('voter_list_voters_info')->where('cnic', $cnic)->first();
            return view('app_web_users.user_data_pdf', $data);
            $pdf = PDF::loadView('user_data_pdf', $data);
            $pdf_file = $cnic . '.pdf';
            return $pdf->download($pdf_file);
        } else {
            return redirect('web/login')->with('error', 'Sorry Something went Try Again ');
        }
    }
    public function voterDataViewPrintALL($blockcode, $gharana)
    {


        if (Session::has('auth_token')) {
            $data['users'] = DB::table('voter_list_voters_info')->where('blockcode', $blockcode)->where('gharana_no', $gharana)->get()->toArray();
            return view('app_web_users.user_data_pdf_all', $data);
            $pdf = PDF::loadView('user_data_pdf', $data);
            $pdf_file = $cnic . '.pdf';
            return $pdf->download($pdf_file);
        } else {
            return redirect('web/login')->with('error', 'Sorry Something went Try Again ');
        }
    }
    public function printBlockcodeAllReceipt($blockcode)
    {


        if (Session::has('auth_token')) {
            $auth_token =   Session::get('auth_token');
            $auth_id =   Session::get('auth_id');
            $data['users'] = DB::table('voter_list_voters_info')->where('blockcode', $blockcode)->get()->toArray();
            return view('app_web_users.blockcode_all_receipt', $data);
            $pdf = PDF::loadView('user_data_pdf', $data);
            $pdf_file = $cnic . '.pdf';
            return $pdf->download($pdf_file);
        } else {
            return redirect('web/login')->with('error', 'Sorry Something went Try Again ');
        }
    }
    public function dataSyncVoterlist(Request $request){
        //      print_r($request->all()); die;
        $response =array('status'=>0, 'message'=>'Empty');
        if (isset($request['cnic_number'])) {
                //print_r($request->all()); die;
           // $exist_date = (array) DB::table("voter_list_voters_cnic_histories")->where('cnic_number', $request['cnic_number'])->where('date', $request['date'])->where('user_id', $request['user_id'])->first();
          //  if (sizeof($exist_date) > 0) {
           // } else {
              //  DB::table("voter_list_voters_cnic_histories")->insert(['name' => $request['name'], 'cnic_number' => $request['cnic_number'], 'date' => $request['date'], 'user_id' => $request['user_id'], 'created_at' => $request['created_at']]);
                $name =isset($request['name'])?$request['name']:"";
                $cnic_no =isset($request['cnic_number'])?$request['cnic_number']:"";
                $blockcode =isset($request['blockcode'])?$request['blockcode']:"";
                $polling_station =isset($request['polling_station'])?$request['polling_station']:"";
                $date =  date('Y-m-d', time());
                $end_time =  date('Y-m-d H:i:s', time());
                $auth_id =   Session::get('auth_id');
               // $voter = (array)DB::table("voter_list_voters_info")->where('cnic',$cnic_no)->first();
                //$blockcode = isset($voter['blockcode'])?$voter['blockcode']:0;
                // $gender = isset($voter['gender'])?$voter['gender']:0;
                // $polling_station_name = '';
                // $polling_stations =  (array)DB::table("voter_list_polling_scheme_na")->where('blockcode',$blockcode)->where('polling_type',$gender)->first();
                // if(sizeof($polling_stations) > 0){
                //     $polling_station_name = $polling_stations['polling_station'];
                // }else{
                //     $polling_station_combile =  (array)DB::table("voter_list_polling_scheme_na")->where('blockcode',$blockcode)->where('polling_type','COMBINED')->first();
                //     $polling_station_name = isset($polling_station_combile['polling_station'])?$polling_station_combile['polling_station']:'';
                // }
                DB::table("voter_list_voters_cnic_histories")->insert(['name'=>$name,'cnic_number'=>$cnic_no,'date'=>$request['date'],'blockcode'=>$blockcode,'polling_station'=>$polling_station,'user_id'=>$request['user_id'],'created_at'=> $request['created_at']]);


                $response =array('status'=>1, 'message'=>'Synced Voter');
          //  }
        }
        return $response;
    }


    public function dataSyncVoterlist50Voters(Request $request){
        $cnic =explode("=",$request['cnic']);
        $user_id=explode("=",$request['user_id']);
        $dates =explode("=",$request['date']);
        $blockcodes =explode("=",$request['blockcode']);
        $ps =explode("=",$request['ps']);
        $created_ats =explode("=",$request['created_at']);
        foreach($user_id as $i=>$user){
            $name = '';
            $cnic_no =isset($cnic[$i])?$cnic[$i]:"";
            $date =isset($dates[$i])?$dates[$i]:"";
            $blockcode =isset($blockcodes[$i])?$blockcodes[$i]:"";
            $polling_station =isset($ps[$i])?$ps[$i]:"";
            $created_at =isset($created_ats[$i])?$created_ats[$i]:"";

            $end_time =  date('Y-m-d H:i:s', time());
            DB::table("voter_list_voters_cnic_histories")->insert(['name'=>$name,'cnic_number'=>$cnic_no,'date'=>$date,'blockcode'=>$blockcode,'polling_station'=>$polling_station,'user_id'=>$request['user_id'],'created_at'=> $created_at,'updated_at'=> $end_time]);
        }
        $response =array('status'=>1, 'message'=>'Synced Voter');

        return $response;
    }

    public function userLogout()
    {



        //        $auth_id =   Session::get('auth_id');
        //
        //        echo $auth_id;
        //        Session::flush(); // removes all session data
        //        Auth::logout();; // logs out the user
        //        Session::flush();
        //        Session::forget('auth_id');
        //        Session::forget('auth_token');
        //        echo $auth_id;
        //
        //        echo Session::get('auth_id');
        //
        //        die;

        if (Session::has('auth_token') && Session::get('auth_id') != "") {
            $auth_token =   Session::get('auth_token');
            $auth_id =   Session::get('auth_id');
            $success =  DB::table('app_web_users')->where('auth_token', $auth_token)->update(['auth_token' => null]);
            return redirect('web/login')->with('success', 'Logout Successfully ');
            session()->forget('auth_token');
            session()->forget('auth_id');


            //            $auth_token =   Session::get('auth_token');
            //            $auth_id =   Session::get('auth_id');
            //
            //            echo $auth_token;
            //            echo "<br>";
            //            echo $auth_id;
            //            echo "<br>";

            // $success =  DB::table('app_web_users')->where('auth_token',$auth_token)->update(['auth_token'=>null]);
            //   return redirect('web/login')->with('success','Logout Successfully ');
            Session::flush(); // removes all session data
            Auth::logout();; // logs out the user
            Session::flush();
            Session::forget('auth_id');
            Session::forget('auth_token');

            //            echo "logiut 123"; die;
        } else {

            return redirect('web/login')->with('error', 'Please Login Again ');
        }
    }
}
