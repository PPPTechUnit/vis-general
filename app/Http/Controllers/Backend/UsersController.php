<?php

namespace App\Http\Controllers\Backend;

use App\Models\Role;
use App\Models\User;
use App\Models\UserConstituency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;
use Mail;
use Session;

class UsersController extends Controller
{
    public function __construct() {
       // $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with(['role'])->orderBy('id',"DESC")->get()->toArray();

        return view('backend.users.listing',['users'=>$users]);
    }

    private function uploadImage($request, $image,$path){
        return Storage::disk('s3')->put($path, $image, 'public');
    }

  //   public function uploadImage($image, $path){
         //$path = '/assets/events/images';
        // $cv_name =  $image->getClientOriginalName();
       //  $cv_name_without_ext =  explode(".", $cv_name);
    ////     $fileName = time().rand(111111,999999).'.'.$image->getClientOriginalExtension();
         // $fileMoving =  $cv_name_without_ext[0]."_".$fileName;
    //     $image->move(public_path($path),$fileName );
      //   return $fileName;
    // }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id','!=',1)->get()->toArray();
        return view('backend.users.create',['roles'=>$roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'profile_picture.required' => 'Please upload Profile Picture',
            'first_name.required' => 'Please Enter First Name',
            'last_name.required' => 'Please Enter Last Name',
            'gender.required' => 'Please Select Gender',
            'date_of_birth.required' => 'Please Select Date Of Birth',
            'email.required' => 'Please Enter Email Address',
            'password.required' => 'Please Enter Password',
            'cnic_number.required' => 'Please Enter CNIC Number',
            'phone_number.required' => 'Please Enter Phone Number like# 03XXXXXXXXX',
            'cnic_front_side.required' => 'Please Upload CNIC front Side',
            'cnic_back_side.required' => 'Please Upload CNIC Back Side',
            'cnic.required' => 'Please Enter Valid  CNIC Number',
            'email.unique' => 'Email has  already been  taken',
            'cnic_number.required' => 'Please Enter CNIC Number ',
            'cnic_number.regex' => 'Please Enter CNIC Number like# XXXXX-XXXXXXX-X ',
        ];
        $validation_data =  [
            'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:25',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:25',
             'cnic_number' => 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/|unique:users,cnic_number',
            'phone_number' => 'required|size:11',
          //  'cnic_number' => 'required|size:13|unique:users,cnic_number',
         //   'cnic_number' => 'required|/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/|unique:users,cnic_number',
            'email' => 'required|email|unique:users',
            // 'profile_picture' => 'required|mimes:jpeg,png,jpg',
            'profile_picture' => 'required',
            'cnic_front_side' => 'required',
            'cnic_back_side' => 'required',
            'gender' => 'required',
            'password' => 'required|confirmed',
            'date_of_birth' => 'required',
        ];
        $validator = Validator::make($request->all(), $validation_data,$messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

            $number = $request['cnic_number'];
            $cnic_number = preg_replace("/^(\d{5})(\d{7})(\d{1})$/", "$1-$2-$3", $number);

            $user  = new User();
            $user->role_id = $request['role'];
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->cnic_number = $cnic_number;
            $user->phone_number = $request['phone_number'];
            $user->remember_token = str_random(25);
            $user->email = $request['email'];
            $user->gender = $request['gender'];
            $user->password =  Hash::make($request['password']);
            $new_dob =  explode("-",$request['date_of_birth']);
            $user->date_of_birth =$new_dob[2].'-'.$new_dob[1].'-'.$new_dob[0];
            $user->status = $request['status'];
            $user->verified = $request['verified'];

            if($request->hasFile('profile_picture')){
                $image = $request->file('profile_picture');
                $cv_name =  $image->getClientOriginalName();
                $cv_name_without_ext =  explode(".", $cv_name);
                $fileName = $cnic_number.'-'.rand(100,999).'.'.$image->getClientOriginalExtension();
                $user->profile_picture = $this->uploadImage($request,$request->file('profile_picture'), config('globalvariables.s3_images_path') . '/data-center/profile');
                // $image->move(public_path('/assets/images/profile-images/'),$fileName );
                // $user->profile_picture = $fileName;
            }
            $cnic_number_front = $cnic_number.'-front';
            if($request->hasFile('cnic_front_side')){
                $image = $request->file('cnic_front_side');
                $cv_name =  $image->getClientOriginalName();
                $cv_name_without_ext =  explode(".", $cv_name);
                $fileName = $cnic_number_front.'-'.rand(100,999).'.'.$image->getClientOriginalExtension();
                $user->cnic_front_side = $this->uploadImage($request,$request->file('cnic_front_side'), config('globalvariables.s3_images_path') . '/data-center/cnic_front');
                // $image->move(public_path('/assets/images/cnic-images/'),$fileName );
                // $user->cnic_front_side = $fileName;
            }
            $cnic_number_back = $cnic_number.'-back';
            if($request->hasFile('cnic_back_side')){
                $image = $request->file('cnic_back_side');
                $cv_name =  $image->getClientOriginalName();
                $cv_name_without_ext =  explode(".", $cv_name);
                $fileName = $cnic_number_back.'-'.rand(100,999).'.'.$image->getClientOriginalExtension();
                $user->cnic_back_side = $this->uploadImage($request,$request->file('cnic_back_side'), config('globalvariables.s3_images_path') . '/data-center/cnic_back');
                // $image->move(public_path('/assets/images/cnic-images/'),$fileName );
                // $user->cnic_back_side = $fileName;
            }
            $result =    $user->save();
            if ($result == 1){
                return redirect()->route('users.index')->with('success', 'User Created Successfully.');
            }else{
                return redirect()->back()->with('error','Something went wrong in registration');

            }
        }
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
        $data =array();
        $data['roles'] = Role::get();
        $data['user']  = User::where('id',$id)->first()->toArray();

         $new_dob =  explode("-",$data['user']['date_of_birth']);
        $data['user']['new_db'] =   $new_dob[2].'-'.$new_dob[1].'-'.$new_dob[0];
        return view('backend.users.edit',$data);
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
        $validation_data =  [
            'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:25',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:25',
            // 'cnic_number' => 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/|unique:users,cnic_number',
            'phone_number' => 'required|size:11',
            'cnic_number' => 'required|size:13|unique:users,cnic_number',
            'date_of_birth' => 'required',
            ];
        if(isset($request['password']) && $request['password'] !="") {
             $validation_data =  [
                'first_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:25',
                'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:25',
                // 'cnic_number' => 'required|regex:/^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/|unique:users,cnic_number',
                'phone_number' => 'required|size:11',
                'cnic_number' => 'required|size:13|unique:users,cnic_number',
                 'password' => 'required|confirmed',
                'date_of_birth' => 'required',
            ];
        }


        $validator = Validator::make($request->all(), $validation_data);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else {
         //  echo "<pre>"; print_r($validation_data); die;
            $user = User::find($id);
            $number = $request['cnic_number'];
            $cnic_number = preg_replace("/^(\d{5})(\d{7})(\d{1})$/", "$1-$2-$3", $number);

            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->cnic_number = $cnic_number;
            $user->phone_number = $request['phone_number'];

            $user->gender = $request['gender'];
            $user->status = $request['status'];
            $user->verified = $request['verified'];

            if($request->hasFile('profile_picture')){
                $image = $request->file('profile_picture');
                $cv_name =  $image->getClientOriginalName();
                $cv_name_without_ext =  explode(".", $cv_name);
                $fileName = $cnic_number.'-'.rand(100,999).'.'.$image->getClientOriginalExtension();
                $user->profile_picture = $this->uploadImage($request,$request->file('profile_picture'), config('globalvariables.s3_images_path') . '/data-center/profile');
                // $image->move(public_path('/assets/images/profile-images/'),$fileName );
                // $user->profile_picture = $fileName;
            }
            $cnic_number_front = $cnic_number.'-front';
            if($request->hasFile('cnic_front_side')){
                $image = $request->file('cnic_front_side');
                $cv_name =  $image->getClientOriginalName();
                $cv_name_without_ext =  explode(".", $cv_name);
                $fileName = $cnic_number_front.'-'.rand(100,999).'.'.$image->getClientOriginalExtension();
                $user->cnic_front_side = $this->uploadImage($request,$request->file('cnic_front_side'), config('globalvariables.s3_images_path') . '/data-center/cnic_front');
                // $image->move(public_path('/assets/images/cnic-images/'),$fileName );
                // $user->cnic_front_side = $fileName;
            }
            $cnic_number_back = $cnic_number.'-back';
            if($request->hasFile('cnic_back_side')){
                $image = $request->file('cnic_back_side');
                $cv_name =  $image->getClientOriginalName();
                $cv_name_without_ext =  explode(".", $cv_name);
                $fileName = $cnic_number_back.'-'.rand(100,999).'.'.$image->getClientOriginalExtension();
                $user->cnic_back_side = $this->uploadImage($request,$request->file('cnic_back_side'), config('globalvariables.s3_images_path') . '/data-center/cnic_back');
                // $image->move(public_path('/assets/images/cnic-images/'),$fileName );
                // $user->cnic_back_side = $fileName;
            }
            $new_dob =  explode("-",$request['date_of_birth']);
            $user->date_of_birth =$new_dob[2].'-'.$new_dob[1].'-'.$new_dob[0];
            $result =    $user->save();
            if(isset($request['password']) && $request['password'] !="") {
                 $user->password =  Hash::make($request['password']);
            }
			$user->status =  1;
           $user->verified =  1;
            $user->updated_at =  date('Y-m-d h:i:s', time());
            $result = $user->save();

             if ($result != false) {
                return redirect()->route('users.index')->with('success', 'User updated Successfully.');
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
    public function destroy($id){
        if($id){
            if ($id == 1){
                return redirect()->back()->with('error','You can not delete this user, This is Super Administrator');
            }else{
                $response = User::where('id' , $id)->delete();
                if($response != false) {
                     UserConstituency::where('user_id' , $id)->delete();
                    return redirect()->back()->with('success','User Deleted Successfully.');
                } else {
                    return redirect()->back()->with('error','Oops something went wrong please try again.');
                }
            }
        }else{
            return redirect()->back()->with('error','Oops something went wrong please try again.');
        }
    }
    public function verify($id){
      $ids =   explode("-",$id);
      if($id){
            if ($ids[0] == 1){
                return redirect()->back()->with('error','This user is Super Administrator.');
            }else{
                 if (str_replace(' ', '', $ids[1])  == 1){
                     $data = User::where('id',$ids[0])->first()->toArray();
                     Session::put('email',$data['email']);
                    if(!empty(Session::get('email'))){
                        Mail::send('email_templates.register_verified', $data, function ($message) use ($data){
                            $message->to(Session::get('email'));
                            $message->subject('User verified successfully');
                        });

                    }
                    $response = User::where('id' , $ids[0])->update(['verified'=>1, 'status'=>1]);
                    if($response != false) {
                        return redirect()->back()->with('success','User verified Successfully.');
                    } else {
                        return redirect()->back()->with('error','Oops something went wrong please try again.');
                    }
                }

                elseif (str_replace(' ', '', $ids[1]) == 0){
                    $response = User::where('id' , $ids[0])->update(['verified'=>0,'status'=>0]);
                    if($response != false) {
                        return redirect()->back()->with('success','User un verified Successfully.');
                    } else {
                        return redirect()->back()->with('error','Oops something went wrong please try again.');
                    }
                }else{
                    return redirect()->back()->with('error','Oops something went wrong please try again.');
                }

            }
        }else{
            return redirect()->back()->with('error','Oops something went wrong please try again.');
        }
    }
}
