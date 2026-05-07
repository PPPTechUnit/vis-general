<?php

namespace App\Http\Controllers\Backend;

use App\Role;
use App\User;
use App\UserConstituency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Hash;
use Validator;
class ConstituencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = UserConstituency::with(['user'])->groupBy('user_id')->get()->toArray();
        return view('backend.constituencies.listing',['users'=>$users]);
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
    public function create()
    {
        $users =  DB::table("users")->where('role_id',2)->get()->toArray();
        $provinces =  DB::connection('mysql2')->table("provinces")->get()->toArray();
    return view('backend.constituencies.create',['provinces'=>$provinces,'users'=>$users]);
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
            'user' => 'required',
            'province' => 'required',

        ];
        $validator = Validator::make($request->all(), $data);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

         //   print_r($request->all()); die;
          // $user_constituency = new UserConstituency();
           // $user_constituency->province_id = $request['province_id'];
        //    $user_constituency->user_id = $request['user_id'];
        //    $constituency =  $user_constituency->save();
            if(isset($request['provincial_constituencies']) && sizeof($request['provincial_constituencies'])>0){
                foreach ($request['provincial_constituencies'] as $constituency ){
                    $user_constituency = new UserConstituency();
                    $user_constituency->province_id = $request['province'];
                    $user_constituency->user_id = $request['user'];
                    $user_constituency->constituency_id = $constituency;
                    $constituency =  $user_constituency->save();
                }
            }
            if ($constituency != false) {
                return redirect()->route('constituencies.index')->with('success', 'User Created Successfully.');
            } else {
                return redirect()->back()->with('error', '!Oops something went wrong please try again.');
            }

        }
    }
    public function getProvinces(Request $request){
        $constituencies =array();
        if (isset($request['province_id'])){
            $issues =  DB::connection('mysql2')->table('election_provincial_constituencies')
                ->select('id','name')
                ->where('province_id',$request['province_id'])->where('election_type_id',1)->where('election_year_id',3)->get()->toArray();


            $exist_ids= UserConstituency::pluck('constituency_id')->toArray();
            $i=0;
            foreach ($issues as $issue){
                if (in_array($issue->id, $exist_ids)) {
                }
                else {
                    $constituencies[$i] =  $issue;
                    $i++;
                }
            }
        }
        return $constituencies;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $UserConstituency = UserConstituency::where('user_id',$id)->groupBy('user_id')->first()->toArray();

        $data =array();
        $data['constituency'] = $UserConstituency;
        $data['user'] = User::where('id',$id)->get()->toArray();

       $data['province'] =   DB::connection('mysql2')->table("provinces")->get()->toArray();
         $data['userConstituency'] = UserConstituency::where('user_id',$id)->pluck('constituency_id')->toArray();
        $data['province_cons'] =  DB::connection('mysql2')->table('election_provincial_constituencies')->select('id','name')->where('province_id',$UserConstituency['province_id'])->where('election_type_id',1)->where('election_year_id',3)->get()->toArray();

 return view('backend.constituencies.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //echo "<pre>"; print_r($request->all()); echo $id; die;
        $data =  [ 'user' => 'required',];
        $validator = Validator::make($request->all(), $data);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else {
            $result = false;
            if(isset($request['provincial_constituencies']) && sizeof($request['provincial_constituencies'])>0){
                UserConstituency::where('user_id',$id)->delete();
                foreach ($request['provincial_constituencies'] as $constituency ){
                    $user_constituency = new UserConstituency();
                    $user_constituency->province_id = $request['province'];
                    $user_constituency->user_id = $request['user'];
                    $user_constituency->constituency_id = $constituency;
                    $result =   $user_constituency->save();
                }
            }

             if ($result != false) {
                return redirect()->route('constituencies.index')->with('success', 'User constituencies updated Successfully.');
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
            $response = UserConstituency::where('user_id',$id)->delete();
            if($response != false)
            {
                return redirect()->back()->with('success','Constituencies Deleted Successfully.');
            }
            else
            {
                return redirect()->back()->with('error','Oops something went wrong please try again.');
            }
        }else{
            return redirect()->back()->with('error','Oops something went wrong please try again.');
        }
    }
}
