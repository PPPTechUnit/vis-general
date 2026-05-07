<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\UserBlockcode;
use App\Models\UserBlockcodeHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
class VerifierController extends Controller
{


    public function index(){
        $users = UserBlockcode::with(['user'])->groupBy('user_id')->orderBy('id',"DESC")->get()->toArray();
        return view('admin.block_codes.listing',['users'=>$users]);
    }
    public function uploadImage($image, $path){
        //$path = '/assets/events/images';
        // $cv_name =  $image->getClientOriginalName();
        //  $cv_name_without_ext =  explode(".", $cv_name);
        $fileName = time().rand(111111,999999).'.'.$image->getClientOriginalExtension();
        // $fileMoving =  $cv_name_without_ext[0]."_".$fileName;
        $image->move(public_path($path),$fileName );
        return $fileName;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $users =  DB::table("users")->where('role','user')->where('verified',1)->get()->toArray();
        $provinces =  DB::connection('mysql2')->table("provinces")->get()->toArray();
        $blockcodes =  DB::table('voter_list_blockcode_info')->where('assigned',0)->get()->toArray();
        return view('admin.block_codes.create',['blockcodes'=>$blockcodes,'users'=>$users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data =  [
            'user' => 'required|unique:user_blockcodes,user_id',
            'province' => 'required',
            'division' => 'required',
            'district' => 'required',
            'tehsil' => 'required',
            'block_codes' => 'required|array|min:1',
        ];
        //  $data['images'][0] = 'required';
        $validator = Validator::make($request->all(), $data);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            //echo "<pre>";            print_r($request->all()); die;
            $code = new UserBlockcode();
            $code->province_id = $request['province'];
            $code->division_id = $request['division'];
            $code->district_id = $request['district'];
            $code->tehsil_id = $request['tehsil'];
            $code->user_id = $request['user'];
            $code->block_codes =implode(",",$request['block_codes']);
            $code_result =  $code->save();
            $code1 = new UserBlockcodeHistory();
            $code1->province_id = $request['province'];
            $code1->division_id = $request['division'];
            $code1->district_id = $request['district'];
            $code1->tehsil_id = $request['tehsil'];
            $code1->user_id = $request['user'];
            $code1->block_codes =implode(",",$request['block_codes']);
            $code1->save();
            if ($code_result != false) {
                return redirect()->route('blockcodes.index')->with('success', 'Block codes added Successfully.');
            } else {
                return redirect()->back()->with('error', '!Oops something went wrong please try again.');
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id){

        $user_tehsil= UserBlockcode::where('user_id',$user_id)->pluck('tehsil_id')->first();

        $user_blockcodes = UserBlockcode::where('user_id',$user_id)->pluck('block_codes')->first();
        $codes = (explode(',',$user_blockcodes));

        $blockes = DB::table('voter_list_blockcode_info')->whereIn('blockcode',$codes)->get()->toArray();

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

        return view('admin.block_codes.show',['blockcodes'=>$blockcodes_list ,'tehsil_id'=>$user_tehsil]);

//        $user_block = UserBlockcode::where('user_id',$id)->first()->toArray();
//        $user_id = Auth::user()->id;
//        //$blockes = DB::connection('mysql2')->table('block_codes')->join('voter_list_blockcode_info', 'voter_list_blockcode_info.blockcode', '=', 'block_codes.name')->orWhere('block_codes.tehsil_id',$user_block['tehsil_id'])
//
//        //  $blockes = DB::connection('mysql2')->table('voter_list_blockcode_info')->where('taluka_id',$user_block['tehsil_id'])->get()->toArray();
//
//
//        $blockes = DB::connection('mysql2')->table('voter_list_voters_info')->where('added_by',$user_block['user_id'])->groupBy('blockcode')->orderBy('id',"DESC")->get()->toArray();
//
//        $block_voters =array();
//        foreach ($blockes as $i=> $block) {
//            $block_voters[$i] = (array) $block;
//
//            $block_voters[$i]['blockcode_info'] = (array)DB::connection('mysql2')->table('voter_list_blockcode_info')->where('blockcode',$block->blockcode)->first();
//            $block_voters[$i]['count_record'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',$block->blockcode)->count(); ;
//        }
//        // echo "<pre>"; print_r($block_voters); die;
//        return view('admin.block_codes.listing_record',['blockcodes'=>$block_voters]);

    }

    public function voterListing ($code){
        $user_id = Auth::user()->id;
        $pdf_blockcode = public_path('/blockcode_files/'.$code);
        $result =   (array)DB::table('voter_list_blockcode_info')->where('blockcode',$code)->first();
        $voters =  DB::table('voter_list_voters_info')->where('blockcode',$code)->orderBy('id','ASC')->get();

        //echo "<pre>"; print_r($voters); die;
        //$voters_count =  DB::table('voter_list_voters_info')->where('blockcode',$code)->count();
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
        return view('admin.block_codes.voter_listing_pdf',['voters'=>$voters,'pdf'=>$pdf_blockcode,'block_info'=>$result,'voters_count'=>$voters_count,'count_today'=>$count_today]);

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $data =array();
        $User = UserBlockcode::where('id',$id)->first()->toArray();
        $user_block_codes =  (explode(",",$User['block_codes']));
        $blockcodes =   UserBlockcode::whereNotIn('id',[$id])->pluck('block_codes')->toArray();
        $data['editdata']   =$User;
        $data['user_block_codes']   =$user_block_codes;
        $exist_array =array();
        $i=0;
        foreach ($blockcodes as $block){
            $block_codes =   (explode(",",$block));
            if (is_array($block_codes) && sizeof($block_codes)>0){
                foreach ($block_codes as $code){
                    if(!empty($code)){
                        $exist_array[$i] =    $code;
                        $i++;
                    }
                }
            }
        }
        $data['users']   =  DB::table("users")->where('role_id',2)->where('status',1)->where('verified',1)->get()->toArray();
        $data['exist_array']   =$exist_array;
        $data['all_blockcodes']  =  DB::connection('mysql2')->table('block_codes')->where('tehsil_id',$User['tehsil_id'])->whereNotIn('name',$exist_array)->select('id','name')->get()->toArray();
        $data['provinces']  =  DB::connection('mysql2')->table("provinces")->get()->toArray();
        $data['divisions']  =  DB::connection('mysql2')->table("divisions")->where('province_id',$User['province_id'])->get()->toArray();
        $data['districts']  =  DB::connection('mysql2')->table("districts")->where('division_id',$User['division_id'])->get()->toArray();
        $data['tehsils']  =  DB::connection('mysql2')->table("tehsils")->where('district_id',$User['district_id'])->get()->toArray();

        return view('admin.block_codes.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

        //    echo "<pre>"; print_r($request->all()); die;

        //   $data =  [ 'user' => 'required',];
        $data =  [
            'user' => 'required',
            'province' => 'required',
            'division' => 'required',
            'district' => 'required',
            'tehsil' => 'required',
            'block_codes' => 'required|array|min:1',
        ];
        $validator = Validator::make($request->all(), $data);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else {
            $user_block_codes = explode(',',$request['user_block_codes']);
            $new_blockcodes = array();
            foreach ($request['block_codes'] as $code){

                if (in_array($code, $user_block_codes)) {
                    //   echo "Match found";
                } else {
                    array_push($new_blockcodes,$code);
                }
            }

            $result = false;


            // $code = new UserBlockcode($id);
            $code = UserBlockcode::find($id);
            //    $code->province_id = $request['province'];
            //    $code->division_id = $request['division'];
            //    $code->district_id = $request['district'];
            //   $code->tehsil_id = $request['tehsil'];
            //    $code->user_id = $request['user'];
            $code->block_codes =implode(",",$request['block_codes']);
            $result =  $code->save();


            $code1 = new UserBlockcodeHistory();
            $code1->province_id = $request['province'];
            $code1->division_id = $request['division'];
            $code1->district_id = $request['district'];
            $code1->tehsil_id = $request['tehsil'];
            $code1->user_id = $request['user'];
            $code1->block_codes =implode(",",$new_blockcodes);
            $code1->save();

            if ($result != false) {
                return redirect()->route('blockcodes.index')->with('success', 'User blockcodes updated Successfully.');
            } else {
                return redirect()->back()->with('error', '!Oops something went wrong please try again.');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id){
            $response = UserBlockcode::where('user_id',$id)->delete();
            if($response != false)
            {
                return redirect()->back()->with('success','User"s Blockcode Deleted Successfully.');
            }
            else
            {
                return redirect()->back()->with('error','Oops something went wrong please try again.');
            }
        }else{
            return redirect()->back()->with('error','Oops something went wrong please try again.');
        }
    }

    //get Divisions
    public function getDivisios(Request $request){
        $result =array();
        if (isset($request['province_id'])){
            $result =  DB::connection('mysql2')->table('divisions')->join('block_codes','block_codes.division_id','divisions.id')->select('divisions.id','divisions.name')->where('block_codes.province_id',$request['province_id'])->groupBy('divisions.id')->get()->toArray();
        }
        return $result;
    }
    //get Divisions
    public function getDistricts(Request $request){
        $result =array();
        if (isset($request['division_id'])){
            $result =  DB::connection('mysql2')->table('districts')->join('block_codes','block_codes.district_id','districts.id')->select('districts.id','districts.name')->where('block_codes.division_id',$request['division_id'])->groupBy('districts.id')->get()->toArray();
        }
        return $result;
    }
    //get Divisions
    public function getTehsils(Request $request){
        $result =array();
        if (isset($request['district_id'])){
            $result =  DB::connection('mysql2')->table('tehsils')->join('block_codes','block_codes.tehsil_id','tehsils.id')->select('tehsils.id','tehsils.name')->where('block_codes.district_id',$request['district_id'])->groupBy('tehsils.id')->get()->toArray();
        }
        return $result;
    }


    public function getBlockcodes(Request $request){
        $result =array();
        if (isset($request['tehsil_id'])){
            $blockcodes =   UserBlockcode::pluck('block_codes')->toArray();
            $exist_array =array();
            $i=0;
            foreach ($blockcodes as $block){
                $block_codes =   (explode(",",$block));
                if (is_array($block_codes) && sizeof($block_codes)>0){
                    foreach ($block_codes as $code){
                        if(!empty($code)){
                            $exist_array[$i] =    $code;
                            $i++;
                        }
                    }
                }
            }
            $result =  DB::connection('mysql2')->table('block_codes')->where('tehsil_id',$request['tehsil_id'])->whereNotIn('name',$exist_array)->select('id','name')->get()->toArray();
        }
        return $result;
    }



    public function viewBlockCodeRecord($blockcode){
        $block = (array) DB::connection('mysql2')->table('voter_list_blockcode_info')->where('blockcode',$blockcode)->first();
        $block_voters =array();
        if (sizeof($block)>0){
            $block_voters['blockcode_info'] = $block;
            $block_voters['voters_info'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',$blockcode)->orderBy('id',"DESC")->get()->toArray(); ;
        }else{
            return redirect()->back()->with('error','Oops something went wrong please try again.');
        }
        return view('admin.block_codes.voters_record',$block_voters);
    }

    public function editBlockCodeRecord($blockcode){

        $block = (array) DB::connection('mysql2')->table('voter_list_blockcode_info')->where('blockcode',$blockcode)->first();
        $year = DB::connection('mysql2')->table('election_years')->where('gilgat', 0)->orderBy('name', 'DESC')->first()->id;

        $province_id = DB::connection('mysql2')->table('districts')->where('id',$block['district_id'])->first()->province_id;
        $division_id = DB::connection('mysql2')->table('districts')->where('id',$block['district_id'])->first()->division_id;

        //  echo "<pre>";  print_r($block); die;
        $block_voters =array();
        $block_voters['na']  = DB::connection('mysql2')->table('election_national_constituencies')->where('election_year_id', $year)->select('id', 'name')->get()->toArray();

        $block_voters['provinces'] = DB::connection('mysql2')->table('provinces')->select('id', 'name')->get()->toArray();
        $block_voters['districts'] = DB::connection('mysql2')->table('districts')->where('province_id',$province_id )->select('id', 'name')->get()->toArray();

        $block_voters['pa'] = DB::connection('mysql2')->table('election_provincial_constituencies')->where('election_year_id', $year)->select('id', 'name')->get()->toArray();
        if (sizeof($block)>0){
            $block_voters['pa'] = DB::connection('mysql2')->table('election_provincial_constituencies')->where('election_year_id', $year)->where('province_id',$province_id )->select('id', 'name')->get()->toArray();
            $block_voters['data'] = $block;
        }else{
            return redirect()->back()->with('error','Oops something went wrong please try again.');
        }

        //echo "<pre>";       print_r($block_voters);
        return view('admin.block_codes.edit_electoral',$block_voters);
    }


    public function deleteBlockCodeRecord($blockcode){
        if($blockcode){
            $response =  DB::connection('mysql2')->table('voter_list_blockcode_info')->where('blockcode',$blockcode)->delete();
            $response = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',$blockcode)->delete();
            if($response != false)
            {
                return redirect()->back()->with('success','Blockcode Deleted Successfully.');
            }
            else
            {
                return redirect()->back()->with('error','Oops something went wrong please try again.');
            }
        }else{
            return redirect()->back()->with('error','Oops something went wrong please try again.');
        }
    }
    public function deleteVoterRecord($id){
        if($id){
            $response = DB::connection('mysql2')->table('voter_list_voters_info')->where('id',$id)->delete();
            if($response != false)
            {
                return redirect()->back()->with('success','Voter information Deleted Successfully.');
            }
            else
            {
                return redirect()->back()->with('error','Oops something went wrong please try again.');
            }
        }else{
            return redirect()->back()->with('error','Oops something went wrong please try again.');
        }
    }

    public function updateElectoralInformation(Request $request){

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
                return redirect()->back()->with('success','Electoral Information updated successfully');
            }else{
                return redirect()->back()->with('success','Electoral Information not updated successfully');

            }

        }
    }
}
