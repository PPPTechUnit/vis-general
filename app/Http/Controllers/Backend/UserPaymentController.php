<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;
use Validator;


class UserPaymentController extends Controller{

    public function viewUserPayment($user_id){
//
//        echo $_GET['date_range'];
//        print_r (explode(" - ",$_GET['date_range']));
        $users = User::where('id',$user_id)->first()->toArray();
        $earned_amount = 0;
        $price_entry =     isset(DB::table("prices")->where('key','price_entry')->first()->value)?DB::table("prices")->where('key','price_entry')->first()->value:0;
        $number_format   = (DB::connection('mysql2')->table('voter_list_voters_info')->where('added_by',$user_id)->count()*$price_entry);
        $all_record_added=    number_format((float)$number_format, 2, '.', '');
        $user_payment = DB::table('user_payments')->where('user_id',$user_id)->orderBy('id',"DESC")->get()->toArray();

        $all_paid_amount = 0;
//        $remaining_amount = 0;
//        $userpayment = 0;
//        //echo $all_record_added;
        foreach ($user_payment as $payment){
            $all_paid_amount = ($all_paid_amount+$payment->paid_amount);
         //   $remaining_amount = ($all_record_added-$payment->paid_amount);
        }
//
        $userpayment =  ($all_record_added - $all_paid_amount);
////        echo $all_paid_amount;echo "<br/>";  echo $remaining_amount;
        $userpayment=    number_format((float)$userpayment, 2, '.', '');
        return view('backend.user_payment.list',['user_payment'=>$user_payment,'amount'=>$userpayment, 'user_id'=>$user_id]);
    }

    public function submitPayment(Request  $request)
    {
        $validation_data = [
            'amount' => 'required',
            'feedback' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_data);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
        $data =array();

            $data['user_id'] = $request['user_id'];
            $data['paid_amount'] = $request['amount'].".00";
            $data['remaining_amount'] = ($request['remaining_amount'] - $request['amount']).".00";
            $data['feed_back'] = $request['feedback'];
            $data['status'] = 1;
            $data['date'] = date('Y-m-d');
            $data['created_at'] =   date('Y-m-d h:i:s', time());;

            $result = DB::table('user_payments')->insert($data);
            if ($result == 1){
                return redirect()->back()->with('success','Your payment has been added successfully');
            }else{
                return redirect()->back()->with('error','Something went wrong in registration');

            }
        }
    }


    public function printPDF ($user_id){
        echo $user_id;
        $users = User::where('id',$user_id)->first()->toArray();
        $earned_amount = 0;
        $price_entry =     isset(DB::table("prices")->where('key','price_entry')->first()->value)?DB::table("prices")->where('key','price_entry')->first()->value:0;
        $number_format   = (DB::connection('mysql2')->table('voter_list_voters_info')->where('added_by',$user_id)->count()*$price_entry);
        $all_record_added=    number_format((float)$number_format, 2, '.', '');
        $user_payment = DB::table('user_payments')->where('user_id',$user_id)->orderBy('id',"DESC")->get()->toArray();

        $all_paid_amount = 0;
//        $remaining_amount = 0;
//        $userpayment = 0;
//        //echo $all_record_added;
        foreach ($user_payment as $payment){
            $all_paid_amount = ($all_paid_amount+$payment->paid_amount);
            //   $remaining_amount = ($all_record_added-$payment->paid_amount);
        }
//

//
        $userpayment =  ($all_record_added - $all_paid_amount);
////        echo $all_paid_amount;echo "<br/>";  echo $remaining_amount;
        $userpayment=    number_format((float)$userpayment, 2, '.', '');

        //echo "<pre>"; print_r($user_payment); die;
      //  return view('backend.user_payment.list',['user_payment'=>$user_payment,'amount'=>$userpayment, 'user_id'=>$user_id]);

        $pdf = PDF::loadView('backend.user_payment.pdf_report', ['user_payment'=>$user_payment,'amount'=>$userpayment, 'user_id'=>$user_id]);
       return $pdf->download('user_report.pdf');
    }

}
