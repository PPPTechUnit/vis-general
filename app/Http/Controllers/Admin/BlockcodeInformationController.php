<?php

namespace App\Http\Controllers\Admin;

use App\Models\History;
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
class BlockcodeInformationController extends Controller
{


    public function index(){

        $records = DB::table("voter_list_blockcode_info");
        if(auth()->user()->group == 'group1'){
            $records =$records->where('added_id',auth()->user()->id);
        }

        $records =$records->orderBy('id',"DESC")->get()->toArray();
        return view('verifier.blockcode_information.listing', compact('records'));
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

    public function create(){
        $divisions =  DB::connection('mysql2')->table("divisions")->where('province_id',10)->get()->toArray();
        $nas =  DB::connection('mysql2')->table("election_provincial_constituencies")->where('election_year_id',14)->where('province_id',10)->get()->toArray();

        return view('verifier.blockcode_information.create',['divisions'=>$divisions,'nas'=>$nas]);
    }
    public function getDistrict($id){

        $arr = array('id'=>0,'name'=>'Not added');
        $response =  DB::connection('mysql2')->table("districts")->where('division_id',$id)->where('delimitation_year',2021)->select('id', 'name')->orderBy('name')->get();
        if(sizeof($response)>0){

        }else{
            $response[] =$arr;
        }
        return $response;
    }
    public function getTehsil($id){
        $arr = array('id'=>0,'name'=>'Not added');
        $response =   DB::connection('mysql2')->table("tehsils")->where('district_id',$id)->where('delimitation_year',2021)->select('id', 'name')->orderBy('name')->get();
        if(sizeof($response)>0){

        }else{
            $response[] =$arr;
        }
        return $response;
    }

    public function updateStatus(Request $request)
    {
        $allowedFields = ['condition', 'scanned', 'deskewed', 'converted', 'qa_converted_file', 'uploaded'];
        $allowedValues = [
            'condition'         => ['GOOD','AVERAGE','BAD'],
            'scanned'           => ['DONE','PENDING','ISSUE'],
            'deskewed'          => ['DONE','ISSUE','PENDING'],
            'converted'         => ['DONE','ISSUE','PENDING'],
            'qa_converted_file' => ['DONE','DISCREPANCY','PENDING'],
            'uploaded'          => ['DONE','PENDING'],
        ];

        $field = $request->field;
        $value = $request->value;
        $reason = $request->reason;

        $new2['field'] =$field;
        $new2['value'] =$value;
        $new2['reason'] =$reason;
        $id    = $request->id;

        if (!in_array($field, $allowedFields)) {
            return response()->json(['success' => false, 'message' => 'Invalid field.'], 422);
        }

        if (!in_array($value, $allowedValues[$field])) {
            return response()->json(['success' => false, 'message' => 'Invalid value for ' . $field], 422);
        }

        try {
            // Get old record BEFORE update
            // Get old value BEFORE update
            $oldRecord = DB::table('voter_list_blockcode_info')->where('id', $id)->value($field);

            $updates_valus = array(
                $field       => $value,
                'updated_at' => now(),
                );
            if($field == 'scanned' && $value == 'ISSUE'){
                $updates_valus['scanned_issue_reason'] = $reason;
            }
            elseif($field == 'deskewed' && $value == 'ISSUE'){
                $updates_valus['deskewed_issue_reason'] = $reason;
            }
            elseif($field == 'converted' && $value == 'ISSUE'){
                $updates_valus['converted_issue_reason'] = $reason;
            }
            elseif($field == 'qa_converted_file' && $value == 'DISCREPANCY'){
                $updates_valus['qa_converted_file_issue_reason'] = $reason;
            }

            // Perform update
            DB::table('voter_list_blockcode_info')
                ->where('id', $id)
                ->update($updates_valus);

            $data = DB::table('voter_list_blockcode_info')
                ->where('id', $id)
                ->first();

            $blockcode = $data ? $data->blockcode : null;


            // Save history - only the changed field
            $history            = new History();
            $history->type      = 'blockcode-information';
            $history->module_id = $id;
            $content['old']     = [$field => $oldRecord];
            $history->blockcode     = $blockcode;
            $history->status_key     = $field;
            $history->status_value     = $value;
            $content['new']     = $new2;
            $history->content   = json_encode($content);
            $history->added_by   = auth()->user()->name;
            $history->save();

            return response()->json(['success' => true, 'message' => 'Updated successfully.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


// BlockcodeInformationController.php
    public function getStatusData($id)
    {
        $record = (array)DB::table('voter_list_blockcode_info')->where('id',$id)->first();

        return response()->json($record);
    }
    public function store(Request $request)
    {

                //echo "<pre>"; print_r($request->all()); die;
        $validator = Validator::make($request->all(), [
            'blockcode'                  => 'required|unique:voter_list_blockcode_info,blockcode',
            'provincial_constituency_name' => 'required',
            'division_name'              => 'required',
            'district_name'              => 'required',
            'taluka_name'                => 'required',
            'eloctoral_area_name'        => 'required|string',
             'village_city'               => 'required|string',
            'male_voters'                => 'required|integer|min:0',
            'female_voters'              => 'required|integer|min:0',
            'circle_name'                => 'required|string',
            'pages'                      => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $totalVoters = $request->male_voters + $request->female_voters;
            $provincial = DB::connection('mysql2')->table('election_provincial_constituencies')->where('id', $request->provincial_constituency_name)->first();
            $division_name = DB::connection('mysql2')->table('divisions')->where('id', $request->division_name)->first();
            $district_name = DB::connection('mysql2')->table('districts')->where('id', $request->district_name)->first();
            $taluka_name = DB::connection('mysql2')->table('tehsils')->where('id', $request->taluka_name)->first();
           $insert_data =  [
                'blockcode'                  => $request->blockcode,
                'provincial_assembly_id' => $request->provincial_constituency_name,
                'provincial_assembly_name' =>$provincial ? $provincial->name : null,
                'division_id'              => $request->division_name,
                'division_name'              => $division_name ? $division_name->name : null,
                'district_id'              => $request->district_name,
                'district_name'              => $district_name ? $district_name->name : null,
                'taluka_id'                => $request->taluka_name,
                'taluka_name'                => $taluka_name ? $taluka_name->name : null,
                'eloctoral_area_name'        => $request->eloctoral_area_name,
                'committee'                  => $request->committee,
                'village_city'               => $request->village_city,
                'male_voters'                => $request->male_voters,
                'female_voters'              => $request->female_voters,
                'total_voters'               => $totalVoters,
                'circle_name'                => $request->circle_name,
                'book_number'                => $request->book_number,
                'pages'                      => $request->pages,
                'missing_page'               => $request->missing_page,
                'condition'               => $request->condition,
                'added_by'                 => auth()->user()->name,
                'added_id'                 => auth()->user()->id,
                'created_at'                 => now(),
                'updated_at'                 => now(),
            ];

            $inserted = DB::table('voter_list_blockcode_info')->insertGetId($insert_data);
            $oldRecord = DB::table('voter_list_blockcode_info')->select(
                    'blockcode',
                    'provincial_assembly_name',
                    'division_name','district_name',
                    'taluka_name','eloctoral_area_name',
                    'committee','village_city',
                    'male_voters',
                    'female_voters',
                    'total_voters',
                    'circle_name',
                    'book_number',
                    'pages',
                    'condition',
                    'missing_page'
                )->where('blockcode', $request->blockcode)->first();
            $history = new History();
            $history->type = 'blockcode-information';
            $history->module_id =$inserted;
            $content['old'] = array();
            $content['new'] = (array)$oldRecord;
            $history->content =json_encode($content);
            $history->added_by   = auth()->user()->name;
            //$history->save();



            if ($inserted) {
                return redirect()->route('blockcode-information.create')->with('success', 'Blockcode information saved successfully!');
            }

            return redirect()->back()->with('error', 'Failed to save data!')->withInput();

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error saving data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $record = DB::table('voter_list_blockcode_info')->where('id', $id)->first();
        $history = History::where('type', 'blockcode-information')->where('module_id', $id)->orderBy('id','DESC')->get()->toArray();

        if (!$record) {
            return redirect()->route('blockcode-information.index')
                ->with('error', 'Record not found.');
        }

        //echo "<pre>"; print_r($history); die;
        return view('verifier.blockcode_information.show', ['record'=>$record, 'histories'=>$history]);
    }




    public function edit($id){
        $data =array();

        $blockcodes  =  DB::table('voter_list_blockcode_info')->where('id',$id)->first();
        $data['blockcodes']  =  $blockcodes;
        $data['divisions']  =  DB::connection('mysql2')->table("divisions")->where('province_id',10)->get()->toArray();
        $data['nas']  =  DB::connection('mysql2')->table("election_provincial_constituencies")->where('election_year_id',14)->where('province_id',10)->get()->toArray();

        $districts  =  DB::connection('mysql2')->table("districts")->where('delimitation_year',2021)->where('division_id',$blockcodes->division_id)->get()->toArray();

        $arr = array('id'=>0,'name'=>'Not added');
        if(sizeof($districts)>0){

        }else{
            $districts[] = (object)$arr;
        }
        $data['districts']  =  $districts;

        $tehsils  =DB::connection('mysql2')->table("tehsils")->where('delimitation_year',2021)->where('district_id',$blockcodes->district_id)->get()->toArray();

        $arr = array('id'=>0,'name'=>'Not added');
        if(sizeof($tehsils)>0){

        }else{
            $tehsils[] = (object)$arr;
        }
        $data['tehsils']  =  $tehsils;



        return view('verifier.blockcode_information.edit',$data);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'blockcode'                      => 'required|unique:voter_list_blockcode_info,blockcode,' . $id . ',id',
            'provincial_constituency_name'   => 'required',
            'division_name'                  => 'required',
            'district_name'                  => 'required',
            'taluka_name'                    => 'required',
            'eloctoral_area_name'            => 'required|string',
            'village_city'                   => 'required|string',
            'male_voters'                    => 'required|integer|min:0',
            'female_voters'                  => 'required|integer|min:0',
            'circle_name'                    => 'required|string',
            'book_number'                    => 'required|string',
            'pages'                          => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $provincial = DB::connection('mysql2')
            ->table('election_provincial_constituencies')
            ->where('id', $request->provincial_constituency_name)
            ->first();

        $division = DB::connection('mysql2')
            ->table('divisions')
            ->where('id', $request->division_name)
            ->first();

        $district = DB::connection('mysql2')
            ->table('districts')
            ->where('id', $request->district_name)
            ->first();

        $taluka = DB::connection('mysql2')
            ->table('tehsils')
            ->where('id', $request->taluka_name)
            ->first();

        $totalVoters = $request->male_voters + $request->female_voters;

        // Get old record for history
        $oldRecord = DB::table('voter_list_blockcode_info')
            ->select(
                'blockcode',
                'provincial_assembly_name',
                'division_name',
                'district_name',
                'taluka_name',
                'eloctoral_area_name',
                'committee',
                'village_city',
                'male_voters',
                'female_voters',
                'total_voters',
                'circle_name',
                'book_number',
                'pages',
                'condition',
                'missing_page'
            )
            ->where('id', $id)->first();

        $update_data = [
            'blockcode'                  => $request->blockcode,
            'provincial_assembly_id'     => $request->provincial_constituency_name,
            'provincial_assembly_name'   => $provincial ? $provincial->name : null,
            'division_id'                => $request->division_name,
            'division_name'              => $division ? $division->name : null,
            'district_id'                => $request->district_name,
            'district_name'              => $district ? $district->name : null,
            'taluka_id'                  => $request->taluka_name,
            'taluka_name'                => $taluka ? $taluka->name : null,
            'eloctoral_area_name'        => $request->eloctoral_area_name,
            'committee'                  => $request->committee,
            'village_city'               => $request->village_city,
            'male_voters'                => $request->male_voters,
            'female_voters'              => $request->female_voters,
            'total_voters'               => $totalVoters,
            'circle_name'                => $request->circle_name,
            'book_number'                => $request->book_number,
            'pages'                      => $request->pages,
            'missing_page'               => $request->missing_page,
            'condition'                  => $request->condition,
            'updated_at'                 => now(),
        ];
        $update_data_history = [
            'blockcode'                  => $request->blockcode,
            'provincial_assembly_name'   => $provincial ? $provincial->name : null,
            'division_name'              => $division ? $division->name : null,
            'district_name'              => $district ? $district->name : null,
            'taluka_name'                => $taluka ? $taluka->name : null,
            'eloctoral_area_name'        => $request->eloctoral_area_name,
            'committee'                  => $request->committee,
            'village_city'               => $request->village_city,
            'male_voters'                => $request->male_voters,
            'female_voters'              => $request->female_voters,
            'total_voters'               => $totalVoters,
            'circle_name'                => $request->circle_name,
            'book_number'                => $request->book_number,
            'pages'                      => $request->pages,
            'missing_page'               => $request->missing_page,
            'condition'                  => $request->condition,
        ];

        DB::table('voter_list_blockcode_info')->where('id', $id)->update($update_data);

        // Save history
        $history            = new History();
        $history->type      = 'blockcode-information';
        $history->module_id = $id;
        $content['old']     = (array) $oldRecord;
        $content['new']     = $update_data_history;
        $history->content   = json_encode($content);
        $history->added_by   = auth()->user()->name;

        $history->save();

        return redirect()->route('blockcode-information.index')
            ->with('success', 'Blockcode updated successfully!');
    }


    public function destroy($blockcode_information)
    {
        try {
            // Find the record by id
            $record = DB::table('voter_list_blockcode_info')
                ->where('id', $blockcode_information)
                ->first();

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found!'
                ], 404);
            }

            // Delete the record
            $deleted = DB::table('voter_list_blockcode_info')
                ->where('id', $blockcode_information)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record deleted successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete record!'
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    public function storeTehsil(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tehsil_name' => 'required|string|min:2|max:255',
                'district_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $district = DB::connection('mysql2')
                ->table('districts')
                ->where('id', $request->district_id)
                ->first();

            // Create new tehsil using DB query (alternative to Eloquent)
            $tehsilId = DB::connection('mysql2')->table('tehsils')->insertGetId([
                'name' => $request->tehsil_name,
                'delimitation_year' => 2021,
                'code' => $request->tehsil_name,
                'province_id' => $district ? $district->province_id : 0,
                'division_id' => $district ? $district->division_id : 0,
                'district_id' => $request->district_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Get the created tehsil
            $tehsil = DB::connection('mysql2')->table('tehsils')->where('id', $tehsilId)->first();

            return response()->json([
                'success' => true,
                'message' => 'Tehsil added successfully',
                'tehsil_id' => $tehsilId,
                'tehsil' => $tehsil
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    public function storeDistrict(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'district_name' => 'required|string|min:2|max:255',
                'division_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $division = DB::connection('mysql2')
                ->table('divisions')
                ->where('id', $request->division_id)
                ->first();

            // Create new tehsil using DB query (alternative to Eloquent)
            $districtId = DB::connection('mysql2')->table('districts')->insertGetId([
                'name' => $request->district_name,
                'delimitation_year' => 2021,
                'code' => $request->district_name,
                'province_id' => $division ? $division->province_id : 0,
                'division_id' => $request->division_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Get the created tehsil
            $district = DB::connection('mysql2')->table('districts')->where('id', $districtId)->first();


            return response()->json([
                'success' => true,
                'message' => 'District added successfully',
                'district_id' => $districtId,
                'district' => $district
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }



    public function updatePreview(Request $request,  $voter_id)
    {
        DB::table('voter_list_voters_info_temp')->where('id',$voter_id)->update(['age'=>$request['age'],'cnic'=>$request['cnic'],'gharana_no'=>$request['gharana_no'],'silsila_no'=>$request['silsila_no']]);
        return response()->json(['success' => true]);
    }
}
