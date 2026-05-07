<?php

namespace App\Http\Controllers;
use App\Models\UserUnionCouncil;
use App\Models\VoterlistBlockcodeInformation;
use App\Models\VoterlistVoterInformation;
use Session;
use \DB;
use Validator;
use App\Models\UserBlockcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelperController extends Controller
{

    public function naTopParties(){


        $response =array();
        $nas = DB::connection('mysql2')->table('election_national_constituencies')->select('id','name')->where('election_year_id',23)->get()->toArray();
        $i =0;
        foreach ($nas as $na){
            $parties = DB::connection('mysql2')->table('election_national_constituency_candidates')
            ->join('politicial_parties','politicial_parties.id','election_national_constituency_candidates.party_id')
            ->select('politicial_parties.id','politicial_parties.name','politicial_parties.short_name')
            ->where('election_national_constituency_candidates.e_nat_cons_id',$na->id)
            ->groupBy('election_national_constituency_candidates.party_id')
            ->orderBy('election_national_constituency_candidates.candidate_rank','ASC')
            ->limit(5)
            ->get()->toArray();

            foreach ($parties as $party){

                $response[$i]['na_id'] =$na->id;
                $response[$i]['na_name'] =$na->name;
                $response[$i]['party_id'] =$party->id;
                $response[$i]['party_name'] =$party->name;
                $response[$i]['party_short_name'] =$party->short_name;
                $i++;
            }
        }
        foreach ($response as $respons){
            DB::table('na_top_parties')->insert($respons);
        }
            echo "DONE";
         //return view('na_parties',['response'=>$response]);
          //echo "<pre>";
        // print_r( $response);

    }


    public function naPollingUpdates(Request $request){

        if (isset($request['file']) && $request['file'] !=''){

          // echo "<pre>"; print_r(   $request['file'] ); die;
           $file = $request->file('file');

           // File Details
           $filename = $file->getClientOriginalName();
           $extension = $file->getClientOriginalExtension();
           $tempPath = $file->getRealPath();
           $fileSize = $file->getSize();
           $mimeType = $file->getMimeType();

           // Valid File Extensions
           $valid_extension = array("csv");

           // 2MB in Bytes
           $maxFileSize = 209715200;

                   // Upload file
                   $file->move(public_path("assets/csv_upload/"),$filename);

                   // Import CSV to Database
                   $filepath = public_path("assets/csv_upload/".$filename);

                   // Reading file
                   $file = fopen($filepath,"r");

                   $importData_arr = array();
                   $header = fgetcsv($file);
                   while ($row = fgetcsv($file)) {
                       $importData_arr[] = array_combine($header, $row);
                   }

                   foreach($importData_arr as $import){

                     $polling =  (array)DB::table('voter_list_polling_scheme_na')->where('blockcode',$import['blockcode'])->where('polling_type',$import['polling_type'])->where('polling_station',$import['polling_station'])->first();
                     //echo "<pre>";print_r($polling);

                     if(sizeof($polling)>0){
                        DB::table('voter_list_polling_scheme_na')->where('id',$polling['id'])->update(['latitude'=>$import['latitude'],'longitude'=>$import['longitude'],'google_image'=>$import['google_image']]);
                        echo "<pre>";print_r($polling);
                     }

                    }

                die;

                   fclose($file);


            //  $randomRecord =$randomRecord->where('blockcode',$request['blockcode']);
        }
        return view('na_polling');



//        $file = public_path('voter_list_polling_scheme_na.csv');

  //      echo "<pre>"; print_r(   $file );


    }

    public function Blockcode(){
        return view('random');
    }
    public function getRandomBlockcode(){
        $randomRecord = VoterlistBlockcodeInformation::inRandomOrder()->first()->toArray();
        return $randomRecord;
    }

    public function getRandomVoter(Request $request){
        $randomRecord = VoterlistVoterInformation::where('printed',0);
        if (isset($request['blockcode']) && $request['blockcode'] !=''){
            $randomRecord =$randomRecord->where('blockcode',$request['blockcode']);
        }
        $randomRecord =$randomRecord->inRandomOrder()->first()->toArray();
        return $randomRecord;
    }

    public function updateVoterDuplicate(){

        $duplicateEmails = VoterlistVoterInformation::select('*', DB::raw('COUNT(*) as count'))
            ->groupBy('cnic_no')
            ->havingRaw('COUNT(*) > 1')
            ->get()->toArray();

        echo sizeof($duplicateEmails);
    }

    public function updatePS(){
        $voters = VoterlistVoterInformation::skip(750000)->take(50000)->get()->toArray();

        foreach( $voters as  $user){


            $polling =  (array)DB::table('voter_list_polling_scheme_na')->where('blockcode',$user['blockcode'])->where('polling_type',$user['gender'])->first();
            $polling_station_name ='';
              if(sizeof($polling)>0){
                  $polling_station_name =$polling['polling_station'];
              }else{
                    $polling_station_name = isset(DB::table('voter_list_polling_scheme_na')->where('blockcode',$user['blockcode'])->where('polling_type','COMBINED')->first()->polling_station)?DB::table('voter_list_polling_scheme_na')->where('blockcode',$user['blockcode'])->where('polling_type','COMBINED')->first()->polling_station:"";
                    if($polling_station_name == ''){

                      $polling_station_name = isset(DB::table('voter_list_polling_scheme_na')->where('blockcode',$user['blockcode'])->first()->polling_station)?DB::table('voter_list_polling_scheme_na')->where('blockcode',$user['blockcode'])->first()->polling_station:"";
                      }
                  }

          //  echo $polling_station_name."<br>";

            VoterlistVoterInformation::where('id',$user['id'])->update(['polling_station'=>$polling_station_name]);

        }

      echo "DONE - 800000";
        die;


    }


}
