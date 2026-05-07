<?php

namespace App\Http\Controllers;
use App\Models\AppWebUser;
use App\Models\UserUnionCouncil;
use App\Models\VoterListVotersInfoHistory;
use App\Models\VoterlistVoterInformation;
use Session;
use \DB;
use Validator;
use App\Models\UserBlockcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class VoterlistDashboardController extends Controller
{

        public function Blockcode(){
            return view('random');
        }


        public function pollingStationsBlockcodes(Request $request){
            if( isset($request['na'])){
                $na = isset($request['na'])?$request['na']:1384;
                $blockcodes_nas = DB::table('voter_list_polling_scheme_na')->where('nat_cons_id',$na)->get()->toArray();
                //echo "<pre>";            print_r($blockcodes_nas);  die;
                return view('voterlist_summary.polling_station_blockcodes',['response'=>$blockcodes_nas]);

            }else{
                return  redirect('/blockcodes?na=1384');
            }
        }

    public function voterlistDashboard (Request $request){
        $result =array();
        if(empty($request['district'])){
            return Redirect('/voter-list/dashboard?district=1384');
          }

        $district = isset($request['district'])?$request['district']:1386;
            //echo  $district; die;

       // $blockcodes_nas = DB::table('voter_list_polling_scheme_na')->where('nat_cons_id',$district)->groupBy('blockcode')->pluck('blockcode')->toArray();

        $records2_data  = DB::table('voter_list_voters_cnic_histories')
        ->select(DB::raw("count(id) as count"))
        //->join('app_web_users','app_web_users.id', 'voter_list_voters_cnic_histories.user_id')
        ->where('nat_cons_id',$district) ->where('publish',1)
        ->groupBy('cnic_number')->get()->toArray();


        $total_male_female = DB::table('voter_list_voters_cnic_histories')->selectRaw("
                        COUNT(CASE WHEN gender='MALE' THEN 1 END) AS male,
        COUNT(CASE WHEN gender='FEMALE' THEN 1 END) AS female
         ") ->where('nat_cons_id',$district) ->where('publish',1)->get()->toArray();

         $total_male_female_Sum = 0;
         foreach ($total_male_female as $voter) {
            $total_male_female_Sum += $voter->male;
            $total_male_female_Sum += $voter->female;
        }

         $result['total_male_female_Sum'] =$total_male_female_Sum;



         $polling_types = DB::table('voter_list_voters_cnic_histories')->selectRaw("
                         COUNT(CASE WHEN polling_type='urban' THEN 1 END) AS urban,
         COUNT(CASE WHEN polling_type='rural' THEN 1 END) AS rural
          ")
         ->where('nat_cons_id',$district) ->where('publish',1)->get()->toArray();


         $polling_types_Sum = 0;
         foreach ($polling_types as $voter) {
            $polling_types_Sum += $voter->urban;
            $polling_types_Sum += $voter->rural;
        }

         $result['polling_types_Sum'] =$polling_types_Sum;




         $result['total_male_female'] =$total_male_female;
         $result['polling_types'] =$polling_types;


         $total_voters_age_wise = DB::select("
         SELECT CASE WHEN age <= 25 THEN '18 - 25'

           WHEN age BETWEEN 26 AND 35 THEN '26 - 35'
           WHEN age BETWEEN 36 AND 45 THEN '36 - 45'
           WHEN age BETWEEN 46 AND 55 THEN '46 - 55'
           WHEN age BETWEEN 56 AND 45 THEN '56 - 65'
           ELSE '65 Plus ' END AS age_range,

              COUNT(id) AS  total
            FROM voter_list_voters_cnic_histories
            where publish = 1 AND nat_cons_id = ".$district."
            GROUP BY
            CASE WHEN age <= 25 THEN '18 - 25'

           WHEN age BETWEEN 26 AND 35 THEN '26 - 35'
           WHEN age BETWEEN 36 AND 45 THEN '36 - 45'
           WHEN age BETWEEN 46 AND 55 THEN '46 - 55'
           WHEN age BETWEEN 56 AND 45 THEN '56 - 65'
           ELSE '65 Plus ' END  order By  age");

           $result['total_voters_age_wise'] =$total_voters_age_wise;

           $total_voters_age_wise_Sum = 0;
           foreach ($total_voters_age_wise as $voter) {
               $total_voters_age_wise_Sum += $voter->total;
           }

           $result['total_voters_age_wise_Sum'] =$total_voters_age_wise_Sum;


                //echo "<pre>";            print_r($result);  die;
            $records2 = 0;
            foreach ($records2_data as $item) {
            $records2 += $item->count;
            }

            $top5Blockcodes= DB::table('voter_list_voters_cnic_histories')
            ->select('blockcode', DB::raw('COUNT(id) as users_count'))
            //->join('voter_list_voters_cnic_histories', 'app_web_users.id', '=',  'voter_list_voters_cnic_histories.user_id')
            //->join('voter_list_voters_info', 'voter_list_voters_info.cnic','=',   'voter_list_voters_cnic_histories.cnic_number')
            ->where('nat_cons_id',$district)->where('publish',1)
            ->groupBy('blockcode')
             ->orderByDesc('users_count')
            ->take(5)
            ->get()->toArray();

           // echo "<pre>";            print_r($top5Blockcodes);  die;
            $top5Users= DB::table('voter_list_voters_cnic_histories')
                ->select('user_id', 'user_name', DB::raw('COUNT(id) as users_count'))
                ->where( 'nat_cons_id',$district)->where('publish',1)
                ->groupBy('user_id')
                //->groupBy('voter_list_voters_cnic_histories.cnic_number')
                ->orderByDesc('users_count')
                ->take(5)
                ->get()->toArray();
        $result['top_5_users'] =$top5Users;


            $top5Polling_stations= DB::table('voter_list_voters_cnic_histories')
                ->select('polling_station', DB::raw('COUNT(id) as users_count'))
                //->join('voter_list_voters_cnic_histories', 'app_web_users.id','=', 'voter_list_voters_cnic_histories.user_id')
                //->join('voter_list_voters_info', 'voter_list_voters_info.cnic', '=',  'voter_list_voters_cnic_histories.cnic_number')
                ->where('nat_cons_id',$district)->where('publish',1)
                ->groupBy('polling_station')
               // ->groupBy('voter_list_voters_cnic_histories.cnic_number')
                ->orderByDesc('users_count')
                ->take(5)
                ->get()->toArray();

        $result['top_5_blockcodes'] =$top5Blockcodes;
        $result['top_5_polling_stations'] =$top5Polling_stations;




        $records  = (array) DB::table('voter_list_voters_dashboard_summary')->where('na_id',$district)->first();

            //echo "<pre>";            print_r($result);die;

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();


        //echo $startOfMonth; die;

        $weekCount = DB::table('app_web_users')->where('na_cons_id',$district)->where('created_at', '>=', $startOfWeek)->count();
        $this_month = DB::table('app_web_users')->where('na_cons_id',$district)->where('created_at', '>=', $startOfMonth)->count();

        $today_users  = DB::table('app_web_users')->where('na_cons_id',$district)->whereDate('created_at', $today)->count();
        $totalusers  = DB::table('app_web_users')->where('na_cons_id',$district)->count();
        // echo "<pre>"; print_r( $today_users); die;

        $result['users_ur']['month'] =$this_month;
        $result['users_ur']['week'] =$weekCount;
        $result['users_ur']['today'] =$today_users;
        $result['users_ur']['total'] =$totalusers;
        $result['total_searched'] =$records2;

        $result['summary'] =$records;


        $records3  = DB::table('app_web_users')->get()->toArray();
        $result['records3'] =$records3;


        return view('voterlist_summary.dashboard',['response'=>$result]);
    }



        public function totalUsers (Request $request){
            $na = isset($request['na'])?$request['na']:1384;
            // $blockcodes_nas = DB::table('voter_list_polling_scheme_na')->where('nat_cons_id',$na)->groupBy('blockcode')->pluck('blockcode')->toArray();

            $top5Users= DB::table('voter_list_voters_cnic_histories')
                ->select('user_id', 'user_name' , DB::raw('COUNT(id) as users_count'))
                //->join('voter_list_voters_cnic_histories', 'app_web_users.id', '=', 'voter_list_voters_cnic_histories.user_id')
                //->join('voter_list_voters_info', 'voter_list_voters_info.cnic','=',   'voter_list_voters_cnic_histories.cnic_number')
                ->where( 'nat_cons_id',$na)
                ->groupBy('user_id')
                //->groupBy('voter_list_voters_cnic_histories.cnic_number')
                ->orderByDesc('users_count')
                ->paginate(50);
            $result['top_5_users'] =$top5Users;
            $append['na'] =   $na;
            return view('voterlist_summary.total_users',['response'=>$result,'append'=>$append]);
        }


        public function totalBlockcode (Request $request){
            $na = isset($request['na'])?$request['na']:1384;
          //  $blockcodes_nas = DB::table('voter_list_polling_scheme_na')->where('nat_cons_id',$na)->groupBy('blockcode')->pluck('blockcode')->toArray();

            $top5Blockcodes= DB::table('voter_list_voters_cnic_histories')
                ->select('user_id', 'user_name','blockcode', DB::raw('COUNT(id) as users_count'))
                //->join('voter_list_voters_cnic_histories', 'app_web_users.id', '=', 'voter_list_voters_cnic_histories.user_id')
               // ->join('voter_list_voters_info', 'voter_list_voters_info.cnic', '=',  'voter_list_voters_cnic_histories.cnic_number')
                ->where( 'nat_cons_id',$na)
                ->groupBy('blockcode')
                ->groupBy('user_id')
               // ->groupBy('voter_list_voters_cnic_histories.cnic_number')
                ->orderByDesc('users_count')
                ->paginate(50);
//              echo "<pre>"; print_r($top5Blockcodes); die;
            $append['na'] =   $na;
            $result['top_5_blockcodes'] =$top5Blockcodes;

            return view('voterlist_summary.total_blockcodes',['response'=>$result,'append'=>$append]);
        }

        public function totalPollingStations (Request $request){
            $na = isset($request['na'])?$request['na']:1384;

            $top5Blockcodes= DB::table('voter_list_voters_cnic_histories')
                ->select('user_id', 'user_name','polling_station', DB::raw('COUNT(id) as users_count'))
                ->where( 'nat_cons_id',$na)
                ->groupBy('polling_station')
                ->groupBy('user_id')
                //->groupBy('app_web_users.id', 'voter_list_voters_cnic_histories.polling_station')
                ->orderByDesc('users_count')
                ->paginate(50);
                $append['na'] =   $na;
            $result['top_5_polling_stations'] =$top5Blockcodes;

            return view('voterlist_summary.total_polling_stations',['response'=>$result,'append'=>$append]);
        }

        public function getRandomVoter(Request $request){
            $randomRecord = VoterlistVoterInformation::where('printed',0);
            if (isset($request['blockcode']) && $request['blockcode'] !=''){
                $randomRecord =$randomRecord->where('blockcode',$request['blockcode']);
            }
            $randomRecord =$randomRecord->inRandomOrder()->first()->toArray();
            return $randomRecord;
        }

        public function updateVoterNULL(){
            $nullVoters = VoterlistVoterInformation::where('silsila_no',null)->get()->toArray();

            foreach ($nullVoters as $voter){

               //$exist =  VoterlistVoterInformation::where('cnic',$voter['cnic'])->first();
                $exist  = (array) DB::table('voter_list_voters_info')->where('cnic_no',$voter['cnic_no'])->first();
                   if(sizeof($exist)){
                       DB::table('voter_list_voters_info')->where('cnic_no',$voter['cnic_no'])->update(['gender'=>NULL]);
                   }
            }
        }



        public function syncToDashboard() {

            return view('sync_dashboard');


            $voters = VoterListVotersInfoHistory::with(['user_app_web'])->where('publish',0)->limit(500)->get()->toArray();
           foreach($voters as $voter){
            if($voter['blockcode'] ==''){
                $name = isset($voter['user_app_web']['name'])?$voter['user_app_web']['name']:"";
                $end_time =  date('Y-m-d H:i:s', time());
                $exist  = (array) DB::table('voter_list_voters_info')->where('cnic',$voter['cnic_number'])->select('blockcode','gender')->first();

                if(sizeof($exist)>0){
                    $polling =  (array)DB::table('voter_list_polling_scheme_na')->where('blockcode',$exist['blockcode'])->where('polling_type',$exist['gender'])->first();
                    $polling_station_name ='';
                    $nat_cons_id =isset($polling['nat_cons_id'])?$polling['nat_cons_id']:0;
                      if(sizeof($polling)>0){
                          $polling_station_name =$polling['polling_station'];
                      }else{
                       // $polling_station_name_arr = isset(DB::table('voter_list_polling_scheme_na')->where('blockcode',$exist['blockcode'])->where('polling_type','COMBINED')->first()->polling_station)?DB::table('voter_list_polling_scheme_na')->where('blockcode',$exist['blockcode'])->where('polling_type','COMBINED')->first()->polling_station:"";
                        $polling_station_name_arr = (array) DB::table('voter_list_polling_scheme_na')->where('blockcode',$exist['blockcode'])->where('polling_type','COMBINED')->first();;
                        $polling_station_name = isset($polling_station_name_arr['polling_station'])?$polling_station_name_arr['polling_station']:'';
                        $nat_cons_id =isset($polling_station_name_arr['nat_cons_id'])?$polling_station_name_arr['nat_cons_id']:0;
                              if(sizeof($polling_station_name_arr)<1){
                                //$polling_station_name = isset(DB::table('voter_list_polling_scheme_na')->where('blockcode',$exist['blockcode'])->first()->polling_station)?DB::table('voter_list_polling_scheme_na')->where('blockcode',$exist['blockcode'])->first()->polling_station:"";
                                $polling_station_name_arr2 = (array) DB::table('voter_list_polling_scheme_na')->where('blockcode',$exist['blockcode'])->first();
                                $polling_station_name = isset($polling_station_name_arr2['polling_station'])?$polling_station_name_arr2['polling_station']:'';
                                $nat_cons_id =isset($polling_station_name_arr2['nat_cons_id'])?$polling_station_name_arr2['nat_cons_id']:0;
                            }
                      }
                      $name = isset($voter['user_app_web']['name'])?$voter['user_app_web']['name']:"";
                    $end_time =  date('Y-m-d H:i:s', time());
                    VoterListVotersInfoHistory::where('id',$voter['id'])->update(['publish'=>1,'user_name'=>$name,'nat_cons_id'=>$nat_cons_id,'blockcode'=>$exist['blockcode'],'polling_station'=> $polling_station_name,'updated_at'=> $end_time]);
                  //  VoterListVotersInfoHistory::where('id',$voter['id'])->update(['publish'=>1]);
                }
            }else{
                $polling =  (array)DB::table('voter_list_polling_scheme_na')->where('blockcode',$voter['blockcode'])->first();
                $nat_cons_id =isset($polling['nat_cons_id'])?$polling['nat_cons_id']:0;

                $name = isset($voter['user_app_web']['name'])?$voter['user_app_web']['name']:"";
                $end_time =  date('Y-m-d H:i:s', time());
                VoterListVotersInfoHistory::where('id',$voter['id'])->update(['publish'=>1,'user_name'=>$name,'nat_cons_id'=>$nat_cons_id,'updated_at'=> $end_time]);
                // VoterListVotersInfoHistory::where('id',$voter['id'])->update(['publish'=>1]);
            }
        }

        }


}
