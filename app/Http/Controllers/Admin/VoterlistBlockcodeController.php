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
class VoterlistBlockcodeController extends Controller
{


    public function index(){
        $voters = DB::table('voter_list_voters_info_temp')->groupBy('blockcode')->get()->toArray();

        return view('verifier.voterlist_blockcodes.listing',['voters'=>$voters]);
    }
    public function create(){
        return view('verifier.voterlist_blockcodes.create');
    }

    public function importVoterlist(Request $request)
    {
        // ✅ Validate file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        set_time_limit(0);
        ini_set('memory_limit', '10240M');

        $file = $request->file('csv_file');

        // ✅ Open file
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return back()->with('error', 'Unable to open file.');
        }

        // ✅ Read header
        $headerRow = fgetcsv($handle);

        if (empty($headerRow)) {
            fclose($handle);
            return back()->with('error', 'CSV header missing.');
        }
        $originalName = $file->getClientOriginalName(); // e.g. 437070612.csv
        $blockcode = pathinfo($originalName, PATHINFO_FILENAME); // 437070612

       // echo $blockcode;
       // echo "<pre>"; print_r($request->all()); die;


        // ✅ Clean headers
        $headers = array_map(fn($h) => strtolower(trim((string) $h)), $headerRow);

            $headers[0] = ltrim($headers[0], "\xef\xbb\xbf"); // remove BOM

            $headerCount = count($headers);

            $insertData = [];
            $skipped = 0;
            $now = now();



            while (($row = fgetcsv($handle)) !== false) {

                // ✅ Fix address column with commas
                if (count($row) > $headerCount) {
                    $extra = array_splice($row, $headerCount - 1);
                    $row[] = implode(',', $extra);
                }

                $row = array_pad($row, $headerCount, null);
                $data = array_combine($headers, $row);

                // ✅ Skip empty rows
                if (empty(array_filter($data, fn($v) => $v !== null && $v !== ''))) {
                    $skipped++;
                    continue;
                }

                $cnic = $data['cnic'] ?? null;

                if (!$cnic) {
                    $skipped++;
                    continue;
                }

                // ✅ Father/Husband logic
                $father_husband = 'Father';
                $father_husband_name = $data["father's name"] ?? null;

                if (!empty($father_husband_name)) {
                    $firstWord = trim(explode(" ", $father_husband_name)[0]);

                    if (in_array($firstWord, ["زوجه", "زال", "زوجہ", "ز وجه"])) {
                        $father_husband = 'Husband';
                    }
                }

                // ✅ Gender from CNIC
                $lastDigit = (int) substr($cnic, -1);
                $gender = ($lastDigit % 2 === 0) ? "FEMALE" : "MALE";

                $insertData[] = [
                    'blockcode'           => $blockcode,
                    'silsila_no'          => $data['house no.'] ?? null,
                    'gharana_no'          => $data['s. no.'] ?? null,
                    'father_husband_name' => $father_husband_name,
                    'father_husband'      => $father_husband,
                    'address'             => $data['address'] ?? null,
                    'name'                => $data['name'] ?? null,
                    'gender'              => $gender,
                    'image_cnic'          => $cnic,
                    'cnic'                => $cnic,
                    'age'                 => $data['age'] ?? null,
                    'created_by'          => auth()->check() ? auth()->user()->name :'',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];

                // ✅ Insert in chunks (avoid memory issue)
                if (count($insertData) >= 1000) {
                    DB::table('voter_list_voters_info_temp')->insert($insertData);
                    $insertData = [];
                }
            }

            // ✅ Insert remaining data
            if (!empty($insertData)) {
                DB::table('voter_list_voters_info_temp')->insert($insertData);
            }

            fclose($handle);
             DB::table('voter_list_blockckode_rescan')->where('blockcode',$blockcode)->delete();
              return redirect()->route('voterlist-blockcodes.index')->with('success', 'CSV Imported Successfully! Skipped: ' . $skipped);
        }



    public function show($id){
        $blockcode= DB::table('voter_list_voters_info_temp')->where('id', $id)->first()->blockcode;
        $voters= DB::table('voter_list_voters_info_temp')->where('blockcode', $blockcode)->get()->toArray();
        $genderCounts = DB::table('voter_list_voters_info_temp')
            ->select('gender', DB::raw('COUNT(*) as total'))
            ->where('blockcode', $blockcode)
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $summary = [];
        $maleCount    = $genderCounts['MALE']     ?? 0;
        $femaleCount  = $genderCounts['FEMALE']   ?? 0;
        $aFemaleCount = $genderCounts['A-FEMALE'] ?? 0;
        $aMaleCount   = $genderCounts['A-MALE']   ?? 0;
        $nullCount    = $genderCounts['']         ?? $genderCounts[null] ?? 0;
        $totalCount   = $maleCount + $femaleCount + $aFemaleCount + $aMaleCount + $nullCount;

        $summary['maleCount']    = $maleCount;
        $summary['femaleCount']  = $femaleCount;
        $summary['aFemaleCount'] = $aFemaleCount;
        $summary['aMaleCount']   = $aMaleCount;
        $summary['nullCount']    = $nullCount;
        $summary['totalCount']   = $totalCount;

        $blockcode = DB::table('voter_list_blockcode_info')
            ->where('blockcode', $id)
            ->select('male_voters','female_voters','total_voters','missing_page')
            ->first();
        $summary['male_voters']  = $blockcode->male_voters ?? 0;
        $summary['female_voters'] = $blockcode->female_voters ?? 0;
        $summary['total_voters']  = $blockcode->total_voters ?? 0;
        $summary['missing_page']  = $blockcode->missing_page ?? 0;
        $summary['blockcode']  = $id;

       // print_r($summary); die;


        return view('verifier.voterlist_blockcodes.show', ['voters'=>$voters, 'summary'=>$summary]);
    }

    public function rescanVoterlistBlockcode($blockcode, Request $request){

        $rescan_value = $request->get('rescan_value'); // tertetert

        $data = DB::table('voter_list_voters_info_temp')
            ->where('blockcode', $blockcode)
            ->first();

        $created_by = $data ? $data->created_by : null;

        DB::table('voter_list_voters_info_temp')->where('blockcode', $blockcode)->delete();


        $history            = new History();
        $history->type      = 'blockcode-information';
        $history->module_id =  DB::table('voter_list_blockcode_info')->where('blockcode', $blockcode)->first()->id;;
        $content['old']     = [];
        $history->blockcode     = $blockcode;
        $history->status_key     = '';
        $history->status_value     = '';
        $new =['uploaded'=>'PENDING','qa_converted_file'=>'DISCREPANCY','reason'=>$rescan_value];
        $content['new']     = $new;
        $history->content   = json_encode($content);
        $history->added_by   = auth()->user()->name;
        $history->save();
        DB::table('voter_list_blockcode_info')->where('blockcode', $blockcode)->update(['uploaded'=>'PENDING','qa_converted_file'=>'DISCREPANCY']);



        $now = now();

        $insertData = [
            'blockcode'    => $blockcode,
            'created_by'   => $created_by, // keep original
            'rescanned_by' => auth()->check() ? auth()->user()->name : '',
            'created_at'   => $now,
            'updated_at'   => $now,
        ];

        DB::table('voter_list_blockckode_rescan')->insert($insertData);

        return redirect()->route('voterlist-blockcodes.index')->with('success', 'Re-Scan this blockcode: ' . $blockcode);
    }

    public function pushToMainTable($blockcode)
    {
        try {
            $now = now();

            $voters = DB::table('voter_list_voters_info_temp')
                ->where('blockcode', $blockcode)
                ->get();

            if ($voters->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for this blockcode.');
            }

            $data = $voters->map(function ($row) use ($now) {
                return [
                    'blockcode'           => $row->blockcode,
                    'silsila_no'          => $row->silsila_no,
                    'gharana_no'          => $row->gharana_no,
                    'name'                => $row->name,
                    'gender'              => $row->gender,
                    'father_husband'      => $row->father_husband,
                    'father_husband_name' => $row->father_husband_name,
                    'image_cnic'          => $row->image_cnic,
                    'cnic'                => $row->cnic,
                    'age'                 => $row->age,
                    'address'             => $row->address,
                    'religion'            => $row->religion,
                    'created_by'          => auth()->check() ? auth()->user()->name : '',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];
            })->toArray();

            // Insert in chunks to avoid memory issues on large data
            foreach (array_chunk($data, 500) as $chunk) {
                DB::table('voter_list_voters_info')->insert($chunk);
            }
            // Delete from temp table after successful push
            DB::table('voter_list_voters_info_temp')->where('blockcode', $blockcode)->delete();
            DB::table('voter_list_blockcode_info')->where('blockcode', $blockcode)->update(['uploaded'=>'DONE','qa_converted_file'=>'DONE']);

            $history            = new History();
            $history->type      = 'blockcode-information';
            $history->module_id =  DB::table('voter_list_blockcode_info')->where('blockcode', $blockcode)->first()->id;;
            $content['old']     = [];
            $history->blockcode     = $blockcode;
            $history->status_key     = '';
            $history->status_value     = '';
            $new =['uploaded'=>'DONE','qa_converted_file'=>'DONE','status'=>'Push to Database'];
            $content['new']     = $new;
            $history->content   = json_encode($content);
            $history->added_by   = auth()->user()->name;
            $history->save();


            return redirect()->route('voterlist-blockcodes.index')->with('success', 'Data pushed to database successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function rescanBlockcode(){
        $voters= DB::table('voter_list_blockckode_rescan')->orderBy('id','desc')->get()->toArray();

        $voters_list =array();
        foreach ($voters as $i=> $voter){
            $voters_list[$i]= $voter;
            $voters_list[$i]->rescened_done_by = DB::table('histories')
                ->where('blockcode', $voter->blockcode)
                ->where('type', 'blockcode-information')
                ->where('status_key', 'scanned')
                ->where('status_value', 'DONE')
                ->value('added_by');
        }

        //echo "<pre>"; print_r($voters_list); die;
        return view('verifier.voterlist_blockcodes.rescan_blockcode', ['voters'=>$voters_list]);
    }

    public function voterlist(){

        $voters = DB::table('voter_list_voters_info')
            ->select(
                'blockcode',
                'created_by',
                'created_at',

                DB::raw("COUNT(*) as total_voters"),

                DB::raw("SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as male_count"),

                DB::raw("SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as female_count")
            )            ->groupBy('blockcode')->get()->toArray();

        $blockcodes = DB::table('voter_list_blockcode_info')->get()->keyBy('blockcode');

        return view('verifier.voterlist_blockcodes.voter_list_listing',['voters'=>$voters,'blockcodes'=>$blockcodes]);
    }




}
