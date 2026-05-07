<?php

namespace App\Http\Controllers;
use App\Models\AppWebUser;
use App\Models\UserLocation;
use App\Models\UserUnionCouncil;
use App\Models\VoterlistBlockcodeInformation;
use App\Models\VoterlistVoterInformation;
use DateTime;
use \DB;
use Validator;
use App\Models\UserBlockcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;

class ConstituenciesResultController extends Controller
{
    public function naConstituencyDetail($id){

        $arr = (array) DB::connection('mysql2')->table('election_national_constituencies')->where('id',$id)->first();

        $arr['candidates'] =  DB::connection('mysql2')->table('election_national_constituency_candidates')
            ->join('election_candidates','election_candidates.id','election_national_constituency_candidates.e_cand_id')
            ->join('politicial_parties','politicial_parties.id','election_national_constituency_candidates.party_id')
            ->select('election_national_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
            ->where('election_national_constituency_candidates.e_nat_cons_id',$id)

            ->orderBy('election_national_constituency_candidates.candidate_rank','ASC')
            ->get()->toArray();

        return view('result.na_result_details',['result'=>$arr]);
        echo "<pre>"; print_r($arr);

    }
    public function paConstituencyDetail($id){

        $arr = (array) DB::connection('mysql2')->table('election_provincial_constituencies')->where('id',$id)->first();

        $arr['candidates'] =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
            ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
            ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
            ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
            ->where('election_provincial_constituency_candidates.e_prov_cons_id',$id)

            ->orderBy('election_provincial_constituency_candidates.candidate_rank','ASC')
            ->get()->toArray();



        return view('result.pa_result_details',['result'=>$arr]);
        echo "<pre>"; print_r($arr);

    }


    public function naConstituencies(Request $request){
       // echo "<pre>";
        $result =array();
        $arr =  DB::connection('mysql2')->table('election_national_constituencies');
                if(isset($request['constituency']) && $request['constituency'] !=""){
                    $name = $request['constituency'];
                    $arr = $arr->where('name', 'LIKE', "%$name%");
                }
            $arr =$arr->where('election_year_id',23)->select('id','name')->get()->toArray();
        foreach ($arr as $i=> $aaa){
           $candidate2 =  DB::connection('mysql2')->table('election_national_constituency_candidates')
                ->join('election_candidates','election_candidates.id','election_national_constituency_candidates.e_cand_id')
                ->join('politicial_parties','politicial_parties.id','election_national_constituency_candidates.party_id')
                ->select('election_national_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                ->where('election_national_constituency_candidates.e_nat_cons_id',$aaa->id)
                ->where('election_national_constituency_candidates.party_id',116)
                ->where('election_national_constituency_candidates.candidate_rank',1)
                ->groupBy('election_national_constituency_candidates.e_cand_id')
              ->get()->toArray();
           if(sizeof($candidate2)>0){
               $candidate3 =  DB::connection('mysql2')->table('election_national_constituency_candidates')
                   ->join('election_candidates','election_candidates.id','election_national_constituency_candidates.e_cand_id')
                   ->join('politicial_parties','politicial_parties.id','election_national_constituency_candidates.party_id')
                   ->select('election_national_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                   ->where('election_national_constituency_candidates.e_nat_cons_id',$aaa->id)
                   //->where('election_national_constituency_candidates.party_id',116)
                   ->where('election_national_constituency_candidates.candidate_rank',2)
                   ->groupBy('election_national_constituency_candidates.e_cand_id')
                   ->get()->toArray();
               foreach ($candidate3 as $candi){
                   $candidate2[] =$candi;
               }
               if(isset($request['type']) && ($request['type'] =="won")){
                   $result[$i]['id'] = $aaa->id;
                   $result[$i]['name'] = $aaa->name;
                   $result[$i]['candidates'] = $candidate2;
               }elseif(isset($request['type']) && ($request['type'] =="lost")) {

               }else{
                   $result[$i]['id'] = $aaa->id;
                   $result[$i]['name'] = $aaa->name;
                   $result[$i]['candidates'] = $candidate2;
               }

           }else{
               $candidate2 =  DB::connection('mysql2')->table('election_national_constituency_candidates')
                   ->join('election_candidates','election_candidates.id','election_national_constituency_candidates.e_cand_id')
                   ->join('politicial_parties','politicial_parties.id','election_national_constituency_candidates.party_id')
                   ->select('election_national_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                   ->where('election_national_constituency_candidates.e_nat_cons_id',$aaa->id)
                   ->where('election_national_constituency_candidates.candidate_rank',1)
                   ->groupBy('election_national_constituency_candidates.e_cand_id')
                   ->get()->toArray();

               $candidate3 =  DB::connection('mysql2')->table('election_national_constituency_candidates')
                   ->join('election_candidates','election_candidates.id','election_national_constituency_candidates.e_cand_id')
                   ->join('politicial_parties','politicial_parties.id','election_national_constituency_candidates.party_id')
                   ->select('election_national_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                   ->where('election_national_constituency_candidates.e_nat_cons_id',$aaa->id)
                   ->where('election_national_constituency_candidates.party_id',116)
                   ->groupBy('election_national_constituency_candidates.e_cand_id')
                   ->get()->toArray();
               foreach ($candidate3 as $candi){
                   $candidate2[] =$candi;
               }
               if(isset($request['type'])  && ($request['type'] == "lost")){
                   $result[$i]['id'] = $aaa->id;
                   $result[$i]['name'] = $aaa->name;
                   $result[$i]['candidates'] = $candidate2;
               }else  if(isset($request['type'])  && ($request['type'] == "won")){
               }else{
                   $result[$i]['id'] = $aaa->id;
                   $result[$i]['name'] = $aaa->name;
                   $result[$i]['candidates'] = $candidate2;
               }


           }




        }

            return view('result.na_result',['result'=>$result]);
    }



    public function paConstituencies(Request $request){
        if(empty($request['type'])){
            return Redirect('/pa-constituencies-result?constituency=&area=sindh&type=all');
        }
        $response['sindh'] =array();
        $response['punjab'] =array();
        $response['kpk'] =array();
        $response['balochistan'] =array();
        if(  $request['area'] =="sindh"){
            $result =array();
            $arr =  DB::connection('mysql2')->table('election_provincial_constituencies');
            if(isset($request['constituency']) && $request['constituency'] !=""  &&  $request['area'] =="sindh"){
                $name = $request['constituency'];
                $arr = $arr->where('name', 'LIKE', "%$name%");
            }
            $arr =$arr->where('election_year_id',23)
                ->where('province_id',4)
                ->select('id','name')->get()->toArray();

            foreach ($arr as $i=> $aaa){
                $candidate2 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                    ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                    ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                    ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                    ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                    ->where('election_provincial_constituency_candidates.party_id',116)
                    ->where('election_provincial_constituency_candidates.candidate_rank',1)
                    ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                    ->get()->toArray();
                if(sizeof($candidate2)>0){
                    $candidate3 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        //->where('election_national_constituency_candidates.party_id',116)
                        ->where('election_provincial_constituency_candidates.candidate_rank',2)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();
                    foreach ($candidate3 as $candi){
                        $candidate2[] =$candi;
                    }
                    if(isset($request['type']) && ($request['type'] =="won" &&  $request['area'] =="sindh")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }elseif(isset($request['type']) && ($request['type'] =="all" &&  $request['area'] =="sindh")) {
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else{

                    }

                }else{
                    $candidate2 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        ->where('election_provincial_constituency_candidates.candidate_rank',1)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();

                    $candidate3 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        ->where('election_provincial_constituency_candidates.party_id',116)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();
                    foreach ($candidate3 as $candi){
                        $candidate2[] =$candi;
                    }
                    if(isset($request['type'])  && ($request['type'] == "lost" &&  $request['area'] =="sindh")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else  if(isset($request['type'])  && ($request['type'] == "all" &&  $request['area'] =="sindh")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else{

                    }
                }
            }
            $response['sindh'] =$result;
        }
        if($request['area'] =="punjab"){

            $result =array();
            $arr =  DB::connection('mysql2')->table('election_provincial_constituencies');
            if(isset($request['constituency']) && $request['constituency'] !=""  &&  $request['area'] =="punjab"){
                $name = $request['constituency'];
                $arr = $arr->where('name', 'LIKE', "%$name%");
            }
            $arr =$arr->where('election_year_id',23)
                ->where('province_id',5)
                ->select('id','name')->get()->toArray();

            foreach ($arr as $i=> $aaa){
                $candidate2 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                    ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                    ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                    ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                    ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                    ->where('election_provincial_constituency_candidates.party_id',116)
                    ->where('election_provincial_constituency_candidates.candidate_rank',1)
                    ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                    ->get()->toArray();
                if(sizeof($candidate2)>0){
                    $candidate3 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        //->where('election_national_constituency_candidates.party_id',116)
                        ->where('election_provincial_constituency_candidates.candidate_rank',2)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();
                    foreach ($candidate3 as $candi){
                        $candidate2[] =$candi;
                    }
                    if(isset($request['type']) && ($request['type'] =="won" &&  $request['area'] =="punjab")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }elseif(isset($request['type']) && ($request['type'] =="all" &&  $request['area'] =="punjab")) {
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else{

                    }

                }else{
                    $candidate2 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        ->where('election_provincial_constituency_candidates.candidate_rank',1)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();

                    $candidate3 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        ->where('election_provincial_constituency_candidates.party_id',116)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();
                    foreach ($candidate3 as $candi){
                        $candidate2[] =$candi;
                    }
                    if(isset($request['type'])  && ($request['type'] == "lost" &&  $request['area'] =="punjab")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else  if(isset($request['type'])  && ($request['type'] == "all" &&  $request['area'] =="punjab")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else{

                    }
                }
            }
            $response['punjab'] =$result;
        }
        if($request['area'] =="balochistan"){
            $result =array();
            $arr =  DB::connection('mysql2')->table('election_provincial_constituencies');
            if(isset($request['constituency']) && $request['constituency'] !=""  &&  $request['area'] =="balochistan"){
                $name = $request['constituency'];
                $arr = $arr->where('name', 'LIKE', "%$name%");
            }
            $arr =$arr->where('election_year_id',23)
                ->where('province_id',6)
                ->select('id','name')->get()->toArray();

            foreach ($arr as $i=> $aaa){
                $candidate2 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                    ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                    ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                    ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                    ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                    ->where('election_provincial_constituency_candidates.party_id',116)
                    ->where('election_provincial_constituency_candidates.candidate_rank',1)
                    ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                    ->get()->toArray();
                if(sizeof($candidate2)>0){
                    $candidate3 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        //->where('election_national_constituency_candidates.party_id',116)
                        ->where('election_provincial_constituency_candidates.candidate_rank',2)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();
                    foreach ($candidate3 as $candi){
                        $candidate2[] =$candi;
                    }
                    if(isset($request['type']) && ($request['type'] =="won" &&  $request['area'] =="balochistan")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }elseif(isset($request['type']) && ($request['type'] =="all" &&  $request['area'] =="balochistan")) {
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else{

                    }

                }else{
                    $candidate2 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        ->where('election_provincial_constituency_candidates.candidate_rank',1)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();

                    $candidate3 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        ->where('election_provincial_constituency_candidates.party_id',116)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();
                    foreach ($candidate3 as $candi){
                        $candidate2[] =$candi;
                    }
                    if(isset($request['type'])  && ($request['type'] == "lost" &&  $request['area'] =="balochistan")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else  if(isset($request['type'])  && ($request['type'] == "all" &&  $request['area'] =="balochistan")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else{

                    }
                }
            }
            $response['balochistan'] =$result;

        }
        if($request['area'] =="kpk"){
            $result =array();
            $arr =  DB::connection('mysql2')->table('election_provincial_constituencies');
            if(isset($request['constituency']) && $request['constituency'] !=""  &&  $request['area'] =="kpk"){
                $name = $request['constituency'];
                $arr = $arr->where('name', 'LIKE', "%$name%");
            }
            $arr =$arr->where('election_year_id',23)
                ->where('province_id',7)
                ->select('id','name')->get()->toArray();

            foreach ($arr as $i=> $aaa){
                $candidate2 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                    ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                    ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                    ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                    ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                    ->where('election_provincial_constituency_candidates.party_id',116)
                    ->where('election_provincial_constituency_candidates.candidate_rank',1)
                    ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                    ->get()->toArray();
                if(sizeof($candidate2)>0){
                    $candidate3 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        //->where('election_national_constituency_candidates.party_id',116)
                        ->where('election_provincial_constituency_candidates.candidate_rank',2)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();
                    foreach ($candidate3 as $candi){
                        $candidate2[] =$candi;
                    }
                    if(isset($request['type']) && ($request['type'] =="won" &&  $request['area'] =="kpk")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }elseif(isset($request['type']) && ($request['type'] =="all" &&  $request['area'] =="kpk")) {
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else{

                    }

                }else{
                    $candidate2 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        ->where('election_provincial_constituency_candidates.candidate_rank',1)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();

                    $candidate3 =  DB::connection('mysql2')->table('election_provincial_constituency_candidates')
                        ->join('election_candidates','election_candidates.id','election_provincial_constituency_candidates.e_cand_id')
                        ->join('politicial_parties','politicial_parties.id','election_provincial_constituency_candidates.party_id')
                        ->select('election_provincial_constituency_candidates.*','election_candidates.name as candidate_name','politicial_parties.short_name as party_name')
                        ->where('election_provincial_constituency_candidates.e_prov_cons_id',$aaa->id)
                        ->where('election_provincial_constituency_candidates.party_id',116)
                        ->groupBy('election_provincial_constituency_candidates.e_cand_id')
                        ->get()->toArray();
                    foreach ($candidate3 as $candi){
                        $candidate2[] =$candi;
                    }
                    if(isset($request['type'])  && ($request['type'] == "lost" &&  $request['area'] =="kpk")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else  if(isset($request['type'])  && ($request['type'] == "all" &&  $request['area'] =="kpk")){
                        $result[$i]['id'] = $aaa->id;
                        $result[$i]['name'] = $aaa->name;
                        $result[$i]['candidates'] = $candidate2;
                    }else{

                    }
                }
            }
            $response['kpk'] =$result;

        }









            return view('result.pa_result',['result'=>$response]);



    }



}
