<?php

namespace App\Http\Controllers;
use DB;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Session;
use Mail;
use Hash;
class UserController extends Controller
{

    public function forgotFunctionForm(){
        return view('/password/email');
    }

    public function logoutUser (Request $request){
        Auth::logout();
        return redirect('/login');
    }
    public function forgotPassword(Request $request)
    {

        //print_r($request->all());// die;
      //  echo $request['email'];
        $validateData = array();
        $validateData['email'] = 'required|email';

        $validator = Validator::make($request->all(), $validateData);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $email_exists = (array) DB::table('users')->where('email',$request['email'])->first();
            //print_r($email_exists); die;
          if(sizeof($email_exists)>0){
              Session::put('email', $request['email']);
               $data_data['remember_token'] = str_random(25);
               $data_data['status'] = 0;
               $data_data['online'] = 0;
               $user = User::where('email',$request['email'])->update($data_data);
                    $data = User::where($data_data)->first()->toArray();
             //echo "<pre>"; print_r($data); die;
              Mail::send('password.password_reset_template', $data, function ($message) use ($data) {
                    $message->to(Session::get('email'));
                    $message->subject('Password Reset');
                });
                   return redirect()->back()->with('status','Please check email for rest password');
           }else{
               return redirect()->back()->with('exist-email','This email not exists');
           }
        }
    }

    public function resetPassrdForm($token){
      $user = User::where('remember_token',$token)->first();
      if (!empty($user)){
          $email['data'] = User::where('remember_token',$token)->first()->toArray();
          return view('/password/reset',$email);
      }else{
          $email['data'] = 1;
          return view('/password/reset',$email);
      }
    //  print_r($user); die;

    }

    public function resetPassword(Request $request){
        $validateData = array();
        $validateData['password'] = 'required|confirmed';
        $validateData['password_confirmation'] = 'required';
        $where = ['remember_token'=>$request['token'],'email'=>$request['email']];
       // echo "<pre>"; print_r($request->all()); die;
            $tokenExists = User::where($where)->first()->toArray();

        $validator = Validator::make($request->all(), $validateData);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else if(sizeof($tokenExists)<1){
            return redirect()->back()->with('status','Your token has been expired');
        }else{
            $user = User::where($tokenExists)->update(['password'=>Hash::make($request['password'])]);
            $user = User::where('remember_token',$request['token'])->first();
            if(!is_null($user)){
                $user->status= 1;
                $user->verified= 1;
                $user->remember_token= null;
                $user->save();
             //   return redirect('/')->with('status','Your sign up activation has completed');
            }
            return redirect('login')->with('status2','You new password generated');

        }
    }
}
