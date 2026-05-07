<?php

namespace App\Http\Controllers;
use Session;
use \DB;
use Validator;
use App\UserBlockcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
class VerifierController extends Controller
{


    public function import_blockcode(){

       return view('front.import_blockcode');
    }
    public function import_voterlist(){

       return view('front.import_voterlist');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files'   => 'required|array|min:1',
            'files.*' => 'file|mimes:xlsx,xls|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $totalInserted = 0;
        $totalSkipped  = 0;
        $fileResults   = [];

        DB::beginTransaction();


        try {
            foreach ($request->file('files') as $file) {
                $fileName    = $file->getClientOriginalName();
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheet       = $spreadsheet->getActiveSheet();
                $rows        = $sheet->toArray(null, true, true, false);

                if (empty($rows)) {
                    $fileResults[] = ['file' => $fileName, 'inserted' => 0, 'skipped' => 0, 'note' => 'Empty file'];
                    continue;
                }

                // Extract header row and normalize: lowercase + trim
                $headers = array_map(fn($h) => strtolower(trim((string) $h)), array_shift($rows));

                $insertData = [];
                $skipped    = 0;
                $now        = now()->toDateTimeString();

                foreach ($rows as $row) {
                    // Pad row to match header count (trailing empty cells)
                    $row  = array_pad($row, count($headers), null);
                    $data = array_combine($headers, $row);

                    // Skip empty rows
                    if (empty(array_filter($data, fn($v) => $v !== null && $v !== ''))) {
                        $skipped++;
                        continue;
                    }

                    $blockcode = isset($data['blockcode']) ? (int) $data['blockcode'] : 0;
                    if ($blockcode === 0) {
                        $skipped++;
                        continue;
                    }

                    $maleVoters   = isset($data['male'])   ? (int) $data['male']   : 0;
                    $femaleVoters = isset($data['female']) ? (int) $data['female'] : 0;

                    $district = isset($data['district'])            ? trim((string) $data['district'])            : null;
                    $tehsil = isset($data['tehsil'])? trim((string) $data['tehsil']): null;
                    $tehsil_id = DB::connection('mysql2')
                        ->table('tehsils')
                        ->where('delimitation_year', 2021)
                        ->where('name', $tehsil)
                        ->value('id');
                    $district_id = DB::connection('mysql2')
                        ->table('districts')
                        ->where('delimitation_year', 2021)
                        ->where('name', $district)
                        ->value('id');


                    $insertData[] = [
                        'blockcode'          => $blockcode,
                        'eloctoral_area_name' => isset($data['eloctoral_area_name']) ? trim((string) $data['eloctoral_area_name']) : null,
                        'village_city'        => isset($data['village_city'])        ? trim((string) $data['village_city'])        : null,
                        'circle_name'         => isset($data['circle_name'])         ? trim((string) $data['circle_name'])         : null,
                        'taluka_id'         => $tehsil_id,
                        'district_id'         => $district_id,
                        'taluka_name'         => $tehsil,
                        'district_name'       => $district,
                        'male_voters'         => $maleVoters,
                        'female_voters'       => $femaleVoters,
                        'total_voters'        => $maleVoters + $femaleVoters,
                        'book_number'         => isset($data['book_number'])         ? trim((string) $data['book_number'])         : null,
                        'pages'               => isset($data['pages'])               ? (string) $data['pages']                     : '0',
                        'added_by'            => auth()->id() ?? 0,
                        'created_at'          => $now,

                    ];
                }

                $inserted = 0;
                foreach (array_chunk($insertData, 200) as $chunk) {
                    DB::table('voter_list_blockcode_info')->insert($chunk);
                    $inserted += count($chunk);
                }

                $totalInserted += $inserted;
                $totalSkipped  += $skipped;
                $fileResults[]  = ['file' => $fileName, 'inserted' => $inserted, 'skipped' => $skipped];
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'All files imported successfully.',
                'inserted' => $totalInserted,
                'skipped'  => $totalSkipped,
                'files'    => $fileResults,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'files'   => $fileResults,
            ], 500);
        }
    }


    public function importvoters(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files'   => 'required|array|min:1',
            'files.*' => 'file|mimes:csv,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $totalInserted = 0;
        $totalSkipped  = 0;
        $fileResults   = [];

        DB::beginTransaction();

        try {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();



                $handle = fopen($file->getPathname(), 'r');

                if ($handle === false) {
                    $fileResults[] = ['file' => $fileName, 'inserted' => 0, 'skipped' => 0, 'note' => 'Could not open file'];
                    continue;
                }

                // Read and normalize header row
                $headerRow = fgetcsv($handle);

                if (empty($headerRow)) {
                    fclose($handle);
                    $fileResults[] = ['file' => $fileName, 'inserted' => 0, 'skipped' => 0, 'note' => 'Empty file'];
                    continue;
                }

                $headers = array_map(fn($h) => strtolower(trim((string) $h)), $headerRow);

            $insertData = [];
            $skipped    = 0;
            $now        = now()->toDateTimeString();

            while (($row = fgetcsv($handle)) !== false) {
                // Pad row to match header count
                $row  = array_pad($row, count($headers), null);
                $data = array_combine($headers, $row);
             //   print_r($data);

                // Skip empty rows
                if (empty(array_filter($data, fn($v) => $v !== null && $v !== ''))) {
                    $skipped++;
                    continue;
                }
                $file_arr =  (explode(".",$fileName));
                $blockcode = isset($file_arr[0])?$file_arr[0]:"";

                $age       = isset($data['age'])     ? ($data['age'])            : null;
                $cnic      = isset($data['cnic'])     ? ( $data['cnic'])        : null;
                $gharana   = isset($data['gharana'])  ?($data['gharana'])        : null;
                $silsila   = isset($data['silsila'])  ?($data['silsila'])        : null;
                $formatted_cnic ='';
               if (strlen($cnic) === 13) {
                   $formatted_cnic = preg_replace("/(\d{5})(\d{7})(\d{1})/", "$1-$2-$3", $cnic);
               } else {
                   $formatted_cnic = substr($cnic, 0, 5) . '-' . substr($cnic, 5, 7) . '-' . substr($cnic, 12, 1);
               }

                $insertData[] = [
                    'blockcode'          => $blockcode,
                    'silsila_no'         => $silsila,
                    'gharana_no'         => $gharana,

                    'image_cnic'         => $formatted_cnic,
                    'cnic'               => $formatted_cnic,
                    'age'                => $age,
                    'created_by'         => auth()->id() ?? 0,
                    'created_at'         => $now,
                ];
            }

            fclose($handle);
        // Save file to blockodes folder
            $blockcodesFolder = public_path('blockodes');
            if (!file_exists($blockcodesFolder)) {
                mkdir($blockcodesFolder, 0755, true);
            }
            $file->move($blockcodesFolder, $fileName);
            $inserted = 0;
            foreach (array_chunk($insertData, 500) as $chunk) {
                DB::table('voter_list_voters_info')->insert($chunk);
                $inserted += count($chunk);
            }

            $totalInserted += $inserted;
            $totalSkipped  += $skipped;
            $fileResults[]  = ['file' => $fileName, 'inserted' => $inserted, 'skipped' => $skipped];
        }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'All files imported successfully.',
                'inserted' => $totalInserted,
                'skipped'  => $totalSkipped,
                'files'    => $fileResults,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

//            return response()->json([
//                'success' => false,
//                'message' => 'Import failed: ' . $e->getMessage(),
//                'files'   => $fileResults,
//            ], 500);
        }
    }


    public function importvotersUrdu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files'   => 'required|array|min:1',
            'files.*' => 'file|mimes:csv,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $totalInserted = 0;
        $totalSkipped  = 0;
        $fileResults   = [];

        DB::beginTransaction();

        try {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();

                $handle = fopen($file->getPathname(), 'r');

                if ($handle === false) {
                    $fileResults[] = ['file' => $fileName, 'inserted' => 0, 'skipped' => 0, 'note' => 'Could not open file'];
                    continue;
                }

                // Read and normalize header row
                $headerRow = fgetcsv($handle);

                if (empty($headerRow)) {
                    fclose($handle);
                    $fileResults[] = ['file' => $fileName, 'inserted' => 0, 'skipped' => 0, 'note' => 'Empty file'];
                    continue;
                }

                $headers = array_map(fn($h) => strtolower(trim((string) $h)), $headerRow);

            // Strip BOM from first header (handles UTF-8 BOM: EF BB BF)
            $headers[0] = ltrim($headers[0], "\xef\xbb\xbf");

            $insertData = [];
            $skipped    = 0;
            $now        = now()->toDateTimeString();

            while (($row = fgetcsv($handle)) !== false) {
                // Pad row to match header count
                $row  = array_pad($row, count($headers), null);
                $data = array_combine($headers, $row);

                // Skip empty rows
                if (empty(array_filter($data, fn($v) => $v !== null && $v !== ''))) {
                    $skipped++;
                    continue;
                }

                $file_arr  = explode(".", $fileName);
                $blockcode = isset($file_arr[0]) ? $file_arr[0] : "";

                $age         = $data['age']           ?? null;
                $cnic        = $data['cnic']           ?? null;
                $name        = $data['name']           ?? null;
                $gharana     = $data['gharana no']     ?? null;
                $silsila     = $data['silsila no']     ?? null;
                $Address     = $data['address']        ?? null;
                $Father_Name = $data["father's name"]  ?? null;

                $lastDigit = (int) substr($cnic, -1);

                if ($lastDigit % 2 === 0) {
                    $gender = "FEMALE";
                } else {
                    $gender = "MALE";
                }

                $insertData[] = [
                    'blockcode'           => $blockcode,
                    'silsila_no'          => $silsila,
                    'gharana_no'          => $gharana,
                    'father_husband_name' => $Father_Name,
                    'address'             => $Address,
                    'name'                => $name,
                    'gender'              => $gender,
                    'image_cnic'          => $cnic,
                    'cnic'                => $cnic,
                    'age'                 => $age,
                    'created_by'          => auth()->id() ?? 0,
                    'created_at'          => $now,
                ];
            }

            fclose($handle);

            // Save file to blockodes folder
            $blockcodesFolder = public_path('blockodes');
            if (!file_exists($blockcodesFolder)) {
                mkdir($blockcodesFolder, 0755, true);
            }
            $file->move($blockcodesFolder, $fileName);

            $inserted = 0;
            foreach (array_chunk($insertData, 500) as $chunk) {
                DB::table('voter_list_voters_info')->insert($chunk);
                $inserted += count($chunk);
            }

            $totalInserted += $inserted;
            $totalSkipped  += $skipped;
            $fileResults[]  = ['file' => $fileName, 'inserted' => $inserted, 'skipped' => $skipped];
        }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'All files imported successfully.',
                'inserted' => $totalInserted,
                'skipped'  => $totalSkipped,
                'files'    => $fileResults,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'files'   => $fileResults,
            ], 500);
        }
    }

    public function index(){
        $users = UserBlockcode::with(['user'])->groupBy('user_id')->get()->toArray();
      //  echo "<pre>";print_r($users); die;
       return view('front.verifier.users',['users'=>$users]);
    }


    public function userBlockckodes($user_id){
//        echo "trtert"; die;

     /*   $user_block = UserBlockcode::where('user_id',$user_id)->first()->toArray();
        $blockes = DB::connection('mysql2')->table('block_codes')
            ->join('voter_list_blockcode_info', 'voter_list_blockcode_info.blockcode', '=', 'block_codes.name')
            ->orWhere('block_codes.tehsil_id',$user_block['tehsil_id'])->select('voter_list_blockcode_info.*')->get()->toArray();
        $block_voters =array();
        foreach ($blockes as $i=> $block) {
            $block_voters[$i] = (array) $block;
            $block_voters[$i]['count_record'] =DB::connection('mysql2')->table('voter_list_voters_info')->where('verified_by',Auth::user()->id)->where('blockcode',$block->blockcode)->count(); ;
        }*/
        $user_block = UserBlockcode::where('user_id',$user_id)->first()->toArray();
        $user_id = Auth::user()->id;
        $blockes = DB::connection('mysql2')->table('voter_list_voters_info')->where('added_by',$user_block['user_id'])->groupBy('blockcode')->get()->toArray();

        $block_voters =array();
        foreach ($blockes as $i=> $block) {
            $block_voters[$i] = (array) $block;

            $block_voters[$i]['blockcode_info'] = (array)DB::connection('mysql2')->table('voter_list_blockcode_info')->where('blockcode',$block->blockcode)->first();
            $block_voters[$i]['count_record'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',$block->blockcode)->count(); ;
        }

        // echo "<pre>";print_r($block_voters); die;
        return view('front.verifier.blockcodes',['blockcodes'=>$block_voters]);
    }

    public function viewBlockCodeRecord($blockcode){

        $back_url = \Request::fullUrl();
        Session::put('back_url',$back_url);
//     die;
        $pdf_blockcode = '';
        $blockcode_pdf = (array) DB::connection('mysql2')->table('block_codes')->where('name', $blockcode)->pluck('pdf')->first();
        if (sizeof($blockcode_pdf)>0){
            $website_url =  DB::connection('mysql2')->table('website_urls')->where('id',1)->pluck('url')->first();
            $pdf_blockcode = config('globalvariables.s3_bucket_url').$blockcode_pdf[0];
        }
        $block = (array) DB::connection('mysql2')->table('voter_list_blockcode_info')->where('blockcode',$blockcode)->first();
        $block_voters =array();
        $urls = array();
        if (sizeof($block)>0){
            $update_page_session = "";
            if(isset($_GET['page']) && $_GET['page'] >0){
              //  Session::put('update_page_session',$_GET['page']);
            }
            if(Session::has('update_page_session')){
             //   Session::get('update_page_session');
            }

            $block_voters['blockcode_info'] = $block;
            $block_voters['pdf'] = $pdf_blockcode;
           // $block_voters['voters_info'] = DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',$block['blockcode'])->orWhere('verified_by',Auth::user()->id)->get()->toArray(); ;
            $voter_data= DB::connection('mysql2')->table('voter_list_voters_info')->where('blockcode',$block['blockcode']);
            if(isset($_GET['name']) && $_GET['name'] != ""){
                 $voter_data = $voter_data->where('name', 'like', '%'. $_GET['name']. '%');
                $urls['name'] =$_GET['name'];
            }
            if(isset($_GET['cnic_no']) && $_GET['cnic_no'] != ""){
                 $voter_data = $voter_data->where('cnic_no', 'like', '%'. $_GET['cnic_no']. '%');
                $urls['cnic_no'] =$_GET['cnic_no'];
            }

            $voter_data = $voter_data->orderBy('id','ASC')->paginate(10);
            $block_voters['voters_info'] = $voter_data;
            $block_voters['url'] = $urls;
        }else{
            return redirect()->back()->with('error','Oops something went wrong please try again.');
        }
     //echo "<pre>"; print_r($block_voters); die;
        return view('front.verifier.voters_record',$block_voters);
    }

    public function verifyingRecord(Request $request){
        $response =array();
       if(isset($request['id']) && $request['id'] !=""){
         $result = DB::connection('mysql2')->table('voter_list_voters_info')->where('id',$request['id'])->update(['verified_by'=>Auth::user()->id,'verified_at'=>date('Y-m-d H:i:s')]);
        if($result == 1){
            $response['name'] =Auth::user()->first_name;
            $response['status'] = 1;
            $response['msg'] = 'Verified successfully';
        }else{
            $response['name'] ='';
            $response['status'] =0;
            $response['msg'] = 'Not Verified ';
        }
       }else{
           $response['name'] ='';
           $response['status'] =0;
           $response['msg'] = 'Something went wrong in voter verifying ';
       }
            return $response;
    }

    public function changeStatus($blockckode){
        $result = DB::connection('mysql2')->table('voter_list_blockcode_info')->where('blockcode',$blockckode)->update(['completed'=>1]);
        $result2 = DB::connection('mysql2')->table('block_codes')->where('name',$blockckode)->update(['voter_list_completed'=>1]);
        if($result == 1 || $result2 ==1){
            return redirect()->back()->with('success', 'block code status updated Successfully.');
        }else{
            return redirect()->back()->with('error', '!Oops something went wrong.');
        }
    }

    public function editRecord($blockcode){

        $block = (array) DB::connection('mysql2')->table('voter_list_blockcode_info')->where('blockcode',$blockcode)->first();
        $year = DB::connection('mysql2')->table('election_years')->where('gilgat', 0)->orderBy('name', 'DESC')->first()->id;

        $province_id = DB::connection('mysql2')->table('districts')->where('id',$block['district_id'])->first()->province_id;
        $division_id = DB::connection('mysql2')->table('districts')->where('id',$block['district_id'])->first()->division_id;

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
         return view('front.verifier.edit_blockcode',$block_voters);
    }

    public function  updateRecord(Request  $request){

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
                $result  = 2;
            }
            return redirect()->back()->with('success',$result);
            }
    }

    public function deleteVoterInoformation($id){

        $result = DB::connection('mysql2')->table('voter_list_voters_info')->where('id',$id)->delete();
        if($result == 1){
            return redirect()->back()->with('success',1);
        }else{
            return redirect()->back()->with('error',0);
        }
    }
    public function deleteVoterInformation($id){

        $response =array();
        if(isset($id) && $id !=""){
            $result = DB::connection('mysql2')->table('voter_list_voters_info')->where('id',$id)->delete();
            // $result =1;
            if($result == 1){
                $response['name'] =Auth::user()->first_name;
                $response['status'] = 1;
                $response['msg'] = 'Deleted successfully';
            }else{
                $response['name'] ='';
                $response['status'] =0;
                $response['msg'] = 'Not Verified ';
            }
        }else{
            $response['name'] ='';
            $response['status'] =0;
            $response['msg'] = 'Something went wrong in voter deleting ';
        }
        return $response;
    }

        public function editVoterInformation ($id){

            $vote_info =   (array) DB::connection('mysql2')->table('voter_list_voters_info')->where('id',$id)->first();

            $pdf_blockcode = '';
            $blockcode_pdf = (array) DB::connection('mysql2')->table('block_codes')->where('name', $vote_info['blockcode'])->pluck('pdf')->first();


            $url = url('/verifier/user-blockcodes/view-records/'.$vote_info['blockcode']);

            if (sizeof($blockcode_pdf)>0){
                $website_url =  DB::connection('mysql2')->table('website_urls')->where('id',1)->pluck('url')->first();
                $pdf_blockcode = config('globalvariables.s3_bucket_url').$blockcode_pdf[0];
            }

            return view('front.verifier.edit_voter_info',['voter'=>$vote_info,'pdf'=>$pdf_blockcode,'url'=>$url]);
        }

        public function voterInfoUpdateVerifier(Request $request){
       //     print_r($request->all());
           // $data['blockcode'] = $request['blockcode'];
            $data['silsila_no'] = $request['silsila_no'];



            $data['gharana_no'] = $request['house_no'];
            $data['name'] = $request['name'];
            $data['father_husband'] = $request['father_husband'];
            $data['father_husband_name'] = $request['fname'];
            $data['cnic_no'] = $request['cnic'];
            $data['age'] = $request['age'];
            if(isset($request['invalid_address']) && $request['invalid_address'] ==1){
                $data['invalid_address'] = 1;
            }
            $data['address'] = $request['address'];
            $data['updated_at']  =  date('Y-m-d h:i:s', time());;
            $result =   DB::connection('mysql2')->table('voter_list_voters_info')->where('id',$request['vote_id'])->update($data);
            $url = url('/verifier/user-blockcodes/view-records/'.$request['blockcode']);

            $response =array();
            if($result == 1){
                $response['code'] = 1;
                $response['url'] = $url;
            }else{
                $response['code'] = 0;
                $response['url'] = $url;

            }
            return $response;
        }
}
