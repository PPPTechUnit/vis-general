<?php

namespace App\Http\Controllers;
use Session;
use \DB;
use Validator;
use Hash;
use App\Models\WorkersUsersToken;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyWorkersController extends Controller{

    public function surveyLogin(){
        return view('survey_workers.login');
    }

    public function dashboard(){

        $auth_token =   Session::get('auth_token');
        $exist = (array) DB::table('survey_workers_users')->where('auth_token',$auth_token)->first();

        if(sizeof( $exist)>0){

        }else{
            return redirect()->back()->with('logout', 'Worker Logout Successfully.');
        }
        return view('survey_workers.dashboard');
    }

    public function getNewOTP(){

        $otp=WorkersUsersToken::where('used',0)->inRandomOrder()->first();
        $response =array();
        if(!empty($otp)){
            $response['code'] = $otp['token'];
            $response['status'] = 'true';
        }else{
            $response['code'] = '';
            $response['status'] = 'false';

        }
        return $response;
    }


  public function surveyLoginSubmit(Request $request){
    $data['phone_number'] = 'required|digits:11|exists:survey_workers_users,phone_number';

    $validator = Validator::make($request->all(), $data);
    if ($validator->fails()) {

        return redirect()->back()->withErrors($validator)->withInput();
    }else{

        $exist = (array) DB::table('survey_workers_users')->where('phone_number',$request['phone_number'])->first();
        if(sizeof($exist)>0){
           // print_r($request->all());
            $auth_code = Hash::make($request['phone_number']);
            Session::put('auth_token', $auth_code);
            DB::table('survey_workers_users')->where('id',$exist['id'])->update(['auth_token'=>$auth_code]);

            return redirect('survey-workers/dashboard')->with('success', 'Login Successfully');

        }else{
            return redirect()->back()->with('error', 'Something went wrong, Please try again');
        }

    }
  }



}
