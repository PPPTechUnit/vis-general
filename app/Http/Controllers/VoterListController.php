<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Models\User;
use \DB;
use Validator;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use App\Models\UserBlockcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoterListController extends Controller
{
    public function syncVoterNames(){
        $filepath = url('/public/voterlist-name.csv');
        $adata =array();
          // Reading file
        $file = fopen($filepath,"r");
        $importData_arr = array();
        $header = fgetcsv($file);
        while ($row = fgetcsv($file)) {
            $importData_arr[] = array_combine($header, $row);
        }
        fclose($file);


        foreach ($importData_arr as $key=> $arr){
            $voter_info =array();
            $i  =0;
                foreach ($arr as $key2=> $arrr){

                    if ($key2 == 'Correction'){
                        $voter_info[0] = $arrr;
                    }else{
                        if (!empty($arrr) || $arrr !=""){
                            $i++;
                            $voter_info[$i] = $arrr;

                        }
                    }
                    $adata[$key] = $voter_info;
                }
        }
        //print_r($adata);

        	echo '<script language="javascript">';
         echo "var a = JSON.stringify(".json_encode($adata).");"; //Indirect conversion, first convert the data format
         echo "console.log('json data', a);";
         echo 'localStorage.setItem("voters",JSON.stringify('.json_encode($adata).'));';
          echo '</script>';

    }
    public function index(){

        $user_id = Auth::user()->id;
        $user_tehsil= UserBlockcode::where('user_id',$user_id)->pluck('tehsil_id')->first();

        $user_blockcodes = UserBlockcode::where('user_id',$user_id)->pluck('block_codes')->first();
        $codes = (explode(',',$user_blockcodes));

        $blockes = DB::table('voter_list_blockcode_info')->whereIn('blockcode',$codes)->where('completed',0)->get()->toArray();
        $blockcodes_list =array();

        $counts = DB::table('voter_list_voters_info')
            ->select(
                'blockcode',
                DB::raw('COUNT(*) as count_voters'),
                DB::raw('SUM(CASE WHEN updated_by != 0 THEN 1 ELSE 0 END) as count_updated')
            )
            ->whereIn('blockcode',$codes)
            ->groupBy('blockcode')
            ->get()
            ->keyBy('blockcode');

        $blockcodes_list = [];

        foreach ($blockes as $i => $block) {

            $blockcodes_list[$i] = $block;

            $blockcodes_list[$i]->count_voters = $counts[$block->blockcode]->count_voters ?? 0;

            $blockcodes_list[$i]->count_updated = $counts[$block->blockcode]->count_updated ?? 0;
        }

        return view('front.voter_list.index',['blockcodes'=>$blockcodes_list ,'tehsil_id'=>$user_tehsil]);
    }



    public function voterListing ($code){
        $user_id = Auth::user()->id;


        $pdf_blockcode = public_path('/blockcode_files/'.$code);
        $result =   (array)DB::table('voter_list_blockcode_info')->where('blockcode',$code)->first();
        $voters =  DB::table('voter_list_voters_info')->where('blockcode',$code)->orderBy('id','ASC')->get();

        $voters_count = DB::table('voter_list_voters_info')
            ->select('gender', DB::raw('COUNT(*) as total'))
            ->where('blockcode', $code)
            ->groupBy('gender')
            ->pluck('total', 'gender')->toArray();


        // echo "<pre>"; print_r($voters_count); die;
        $user_id = Auth::user()->id;
        $count_today = DB::table('voter_list_voters_info')
            ->whereDate('updated_at', Carbon::today())
            ->where('updated_by',$user_id)
            ->count();
        return view('front.voter_list.voter_listing_pdf',['voters'=>$voters,'pdf'=>$pdf_blockcode,'block_info'=>$result,'voters_count'=>$voters_count,'count_today'=>$count_today]);

    }

    public function storeVoter(Request $request)
    {
        // ✅ CNIC unique check
        $exists = DB::table('voter_list_voters_info')->where('cnic', $request->cnic)->exists();
        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'CNIC already exists in database.'
            ], 422);
        }
        $now        = now()->toDateTimeString();

        $voter = DB::table('voter_list_voters_info')->insert([
            'name'                => $request->name,
            'image_cnic'                => $request->cnic,
            'cnic'                => $request->cnic,
            'age'                 => $request->age,
            'address'             => $request->address,
            'father_husband_name' => $request->father_husband_name,
            'father_husband'      => $request->father_husband,
            'gender'              => $request->gender,
            'gharana_no'          => $request->gharana_no,
            'silsila_no'          => $request->silsila_no,
            'blockcode'           => $request->blockcode ?? null,
            'updated_by'         => auth()->id() ?? 0,
            'updated_at'         => $now,

        ]);


        return response()->json($voter);
    }



    public function submitEectoralInformation(Request  $request){

        $data =  [
            'area_name' => 'required',
            'electoral' => 'required',
            'blockcode' => 'required',
            'national_assembly' => 'required',
            'provincial_assembly' => 'required',
            'city' => 'required',
            'district' => 'required',
            'tehsil' => 'required',
            'patwar_halka' => 'required',
           // 'union_council' => 'required',
            'male_voters' => 'required|integer',
            'female_voters' => 'required|integer',
            'book_number' => 'required',
         ];
        $validator = Validator::make($request->all(), $data);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else {
            $result = 0;
            if (isset($request['electoral']) && $request['electoral'] !=0){
                $data =array();
                $data['blockcode'] = $request['blockcode'];
                $data['eloctoral_area_name'] = $request['area_name'];
                $data['national_assembly_id'] = $request['national_assembly'];
                $data['national_assembly_name'] = DB::connection('mysql2')->table('election_national_constituencies')->where('id',$request['national_assembly'])->first()->name;
                $data['provincial_assembly_id'] = $request['provincial_assembly'];
                $data['provincial_assembly_name'] = DB::connection('mysql2')->table('election_provincial_constituencies')->where('id',$request['provincial_assembly'])->first()->name;;
                $data['district_id'] = $request['district'];
                $data['district_name'] = DB::connection('mysql2')->table('districts')->where('id',$request['district'])->first()->name;
                $data['taluka_id'] = $request['tehsil'];
                $data['taluka_name'] = DB::connection('mysql2')->table('tehsils')->where('id',$request['tehsil'])->first()->name;
                $data['village_city'] = $request['city'];
                $data['circle_name'] = $request['patwar_halka'];
                $data['union_council'] = $request['union_council'];
                $data['male_voters'] = $request['male_voters'];
                $data['female_voters'] = $request['female_voters'];
                $data['total_voters'] = ($request['male_voters'] + $request['female_voters']);
                $data['book_number'] = $request['book_number'];
                $result =   DB::connection('mysql2')->table('voter_list_blockcode_info')->where('id',$request['electoral'])->update($data);
                if ($result == 1){
                    $result  = 2;
                }
             }else{
                $data =array();
                $data['blockcode'] = $request['blockcode'];
                $data['eloctoral_area_name'] = $request['area_name'];
                $data['national_assembly_id'] = $request['national_assembly'];
                $data['national_assembly_name'] = DB::connection('mysql2')->table('election_national_constituencies')->where('id',$request['national_assembly'])->first()->name;
                $data['provincial_assembly_id'] = $request['provincial_assembly'];
                $data['provincial_assembly_name'] = DB::connection('mysql2')->table('election_provincial_constituencies')->where('id',$request['provincial_assembly'])->first()->name;;
                $data['district_id'] = $request['district'];
                $data['district_name'] = DB::connection('mysql2')->table('districts')->where('id',$request['district'])->first()->name;
                $data['taluka_id'] = $request['tehsil'];
                $data['taluka_name'] = DB::connection('mysql2')->table('tehsils')->where('id',$request['tehsil'])->first()->name;
                $data['village_city'] = $request['city'];
                $data['circle_name'] = $request['patwar_halka'];
                $data['union_council'] = $request['union_council'];
                $data['male_voters'] = $request['male_voters'];
                $data['female_voters'] = $request['female_voters'];
                $data['total_voters'] = ($request['male_voters'] + $request['female_voters']);
                $data['book_number'] = $request['book_number'];
                $data['added_by'] = Auth::user()->id;
                $result =   DB::connection('mysql2')->table('voter_list_blockcode_info')->insert($data);

            }

           // echo $result; die;
            return redirect()->back()->with('success',$result);
        }

    }

    public function addVoterInformation(Request  $request){
        $price_entry =     isset(DB::table("prices")->where('key','price_entry')->first()->value)?DB::table("prices")->where('key','price_entry')->first()->value:0;

        $data =array();
       $nic = (array) DB::connection('mysql2')->table('voter_list_voters_info')->where('cnic_no',$request['cnic'])->first();
       if (sizeof($nic)>0){
           $response['code'] = 2;
           $response['count_record'] = '';
           return $response;
       }else{
           $data['blockcode'] = $request['blockcode'];
           $data['silsila_no'] = $request['silsila_no'];

           $data['gharana_no'] = $request['house_no'];
           $data['name'] = $request['first_name']. ' '.$request['middle_name']. ' '.$request['last_name'];
           $data['father_husband'] = $request['father_husband'];
           $data['father_husband_name'] = $request['fname'];
           $data['cnic_no'] = $request['cnic'];
           $data['age'] = $request['age'];
           if(isset($request['invalid_address']) && $request['invalid_address'] ==1){
               $data['invalid_address'] = 1;
           }
           $data['address'] = $request['address'];
           $data['added_by'] = Auth::user()->id;
           $result =   DB::connection('mysql2')->table('voter_list_voters_info')->insert($data);

           $lastentry=   (array) DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',$request['blockcode'])->where('added_by',Auth::id())->orderBy('id',"DESC")->first();
           $last_cnic_no = "";
           if(sizeof($lastentry)>0){
               $last_cnic_no = $lastentry['cnic_no'];
           }
        $response =array();
          if($result == 1){
              $response['code'] = 1;
              $response['last_cnic_no'] = $last_cnic_no;
              $number_format   = (DB::connection('mysql2')->table('voter_list_voters_info')->where('added_by',Auth::id())->count()*$price_entry);
              $response['count_record']  =    number_format((float)$number_format, 2, '.', '');
          }else{
              $response['code'] = 0;
              $response['last_cnic_no'] = $last_cnic_no;
              $response['count_record'] = (DB::connection('mysql2')->table('voter_list_voters_info')->where('added_by',Auth::id())->count() * $price_entry) ;
          }
          return $response;
       }

    }

    public function searchBlockcodes(Request $request)
{
    // Validate incoming request
    $request->validate([
        'block_code' => 'nullable|string',
        'tehsil'     => 'required|integer',
    ]);

    // Get the user's allowed block codes
    $raw = DB::table('user_blockcodes')
        ->where('user_id', Auth::id())
        ->pluck('block_codes')
        ->first();

    // If no block codes assigned to user, return early
    if (!$raw) {
        return response()->json([
            'status'     => false,
            'blockcodes' => [],
        ]);
    }

    $allowedBlockCodes = array_map('trim', explode(',', $raw));

    // Search within allowed block codes
    $blockcodes = DB::table('voter_list_blockcode_info')
        ->whereIn('blockcode', $allowedBlockCodes)
        ->where('blockcode', 'LIKE', '%' . $request->block_code . '%')
        //->where('tehsil_id', $request->tehsil)
        ->where('completed', 0)
        ->get()
        ->toArray();

    return response()->json([
        'blockcodes' => $blockcodes,
    ]);
}

    public function sendEmail (){

        return view('front.voter_list.send_email');
    }

    public function sendFeedback(Request $request){
        $form_data =array();
        $user =Auth::user()->toArray();
        $response =array();
        $form_data['user_id'] =$user['id'];
        $form_data['subject'] =$request['subject'];
        $form_data['description'] =$request['description'];
        $form_data['created_at']  =  date('Y-m-d h:i:s', time());;
        $form_data['updated_at']  =  date('Y-m-d h:i:s', time());;
       DB::table('feedbacks')->insert($form_data);

        Session::put('email',$user['email']);
        Session::put('subject',$request['subject']);
        $data = User::where('email',$user['email'])->first()->toArray();
        $data['subject'] = $request['subject'];
        $data['description'] = $request['description'];

        Mail::send('email_templates.feedback', $data, function ($message) use ($data){
            $message->to('itsme.rklakhani@gmail.com');
             $message->subject(Session::get('subject'));
        });
//        $response['status'] =1;
//        $response['message'] ="Your Mail has been sent successfully";
//
//        if( count(Mail::failures()) > 0 ) {
//            $response['status'] =0;
//            $response['message'] ="Error! Your Mail not sent";
//            return redirect()->back()->with('error','Error! Your Mail not sent.');
//        }
//        else {
//            $response['status'] =1;
//            $response['message'] ="Your Mail has been sent successfully";
//            return redirect()->back()->with('success','Your Mail has been sent successfully.');
//        }

        if( count(Mail::failures()) > 0 ) {
            $response['status'] =0;
            $response['message'] ="Error! Your Mail not sent";
        }
        else {
            $response['status'] =1;
            $response['message'] ="Your Mail has been sent successfully";
        }
        return  $response;
    }


    public function updateVoter(Request $request, $id)
    {
        $now        = now()->toDateTimeString();

        DB::table('voter_list_voters_info')->where('id',$id)->update([
            'name'               => $request->name,
            'cnic'               => $request->cnic,
            'age'                => $request->age,
            'address'            => $request->address,
            'father_husband_name'=> $request->father_husband_name,
            'father_husband'     => $request->father_husband,
            'gender'             => $request->gender,
            'gharana_no'         => $request->gharana_no,
            'silsila_no'         => $request->silsila_no,
            'updated_by'         => auth()->id() ?? 0,
            'updated_at'         => $now,

        ]);

        $user_id = Auth::user()->id;
        $count_today = DB::table('voter_list_voters_info')
            ->whereDate('updated_at', Carbon::today())
            ->where('updated_by',$user_id)
            ->count();
        return response()->json(['success' => true,'count_today' => $count_today, 'message' => 'Updated!']);
    }
    public function updateCompletedStatus(Request $request)
    {
        $now        = now()->toDateTimeString();
        $request->validate([
            'id'        => 'required',
            'completed' => 'required|in:0,1',
        ]);

        DB::table('voter_list_blockcode_info')->where('id', $request->id)->update(['completed' => $request->completed, 'completed_by' => Auth::user()->id, 'completed_at' => $now]);

        return response()->json([
            'success'   => true,
            'completed' => $request->completed,
            'message'   => 'Status updated successfully.',
        ]);
    }
    public function updateTestedStatus(Request $request)
    {
        $now        = now()->toDateTimeString();
        $request->validate([
            'id'        => 'required',
            'tested' => 'required|in:0,1',
        ]);

        DB::table('voter_list_blockcode_info')->where('id', $request->id)->update(['tested' => $request->tested]);

        return response()->json([
            'success'   => true,
            'tested' => $request->tested,
            'message'   => 'Status updated successfully.',
        ]);
    }

}
