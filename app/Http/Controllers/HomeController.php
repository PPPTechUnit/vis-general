<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function countStats(){
        $allrecods =array();
        return view('backend.dashboard_count',['allrecods'=>$allrecods]);
    }
    public function index(){
        $allrecods =array();
        /*foreach(range(intval('07:00:00'),intval('16:00:00')) as $time) {
            //  echo date("H:00", mktime($time+1)).'<br>';
        }

        // die;
        $users = User::where('role_id',2)->get()->toArray();
        $current_time =  date("Y-m-d H:i:s", strtotime("now")) . "<br>";
        $ago_10_mintes =  date("Y-m-d H:i:s", strtotime("-30 minutes"));
        $one_hour_ago =  date("Y-m-d H:i:s", strtotime('-1 hour'));
        //$ago_10_mintes =  '2022-07-03 15:56:13';
        // echo $current_time;
        $to =  $current_time;
        $from =  $one_hour_ago;
        foreach ($users as $i1=> $user) {
            $user_data['name']=  $user['first_name'] .' '. $user['last_name'];
            $records = DB::table('voter_list_blockcode_info')
                ->join('voter_list_voters_info', 'voter_list_voters_info.blockcode', 'voter_list_blockcode_info.blockcode')
                ->where('voter_list_voters_info.added_by', $user['id']);
            $records = $records->where('voter_list_voters_info.created_at', '>=', $from);
            $records = $records->where('voter_list_voters_info.created_at', '<=', $to);

            $records = $records->select(DB::raw('COUNT(voter_list_voters_info.name)as total'))->get()->toArray();
            $total =0;
            foreach ($records as $record){
                $total+=$record->total;
            }
            $user_data['total']=  $total;
            $data[$i1] =$user_data;
        }
        $todayrecords =   $this->recordTodayDashboard();
        $allrecods['one_hour'] = $data;
        $allrecods['today_record'] = $todayrecords;*/

       /* $stats = DB::table('voter_list_voters_cnic_histories')
            ->selectRaw("
                COUNT(DISTINCT blockcode)        AS total_blockcodes,
                COUNT(DISTINCT polling_station)  AS total_polling_stations,
                SUM(gender = 'MALE')                AS total_male,
                SUM(gender = 'FEMALE')                AS total_female,
                COUNT(*)                         AS total_voters
            ")
            ->where('user_from','GB')
            ->first();
        $allrecods['stats'] =$stats;

        return view('backend.dashboard',['allrecods'=>$allrecods]);
        return view('backend.dashboard',['allrecods'=>$allrecods]);*/

        // 1. Total, Male and Female Counts
        $total = DB::table('voter_list_voters_cnic_histories')->where('user_from','GB')->count();

        $male = DB::table('voter_list_voters_cnic_histories')
            ->where('gender', 'Male')            ->where('user_from','GB')

            ->count();

        $female = DB::table('voter_list_voters_cnic_histories')
            ->where('gender', 'Female')            ->where('user_from','GB')

            ->count();

        // 2. Total count group by blockcode
        $blockcodeGroups = DB::table('voter_list_voters_cnic_histories')
            ->select('blockcode', DB::raw('count(*) as total'))
            ->whereNotNull('blockcode')
            ->where('user_from','GB')

            ->where('blockcode', '!=', '')
            ->groupBy('blockcode')
            ->orderBy('total', 'desc')
            ->get();

        // 3. Total count group by polling_station
        $pollingStationGroups = DB::table('voter_list_voters_cnic_histories')
            ->select('polling_station', DB::raw('count(*) as total'))
            ->whereNotNull('polling_station')
            ->where('user_from','GB')
            ->where('polling_station', '!=', '')
            ->groupBy('polling_station')
            ->orderBy('total', 'desc')
            ->get();

        // 3. Total count group by Agent
        $usersGroups = DB::table('voter_list_voters_cnic_histories')
            ->select('user_id', DB::raw('count(*) as total'))
            ->whereNotNull('user_id')
            ->where('user_from','GB')
            ->where('user_id', '!=', '')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->get();

        $users = DB::table('app_web_users')
                        ->selectRaw("
                    COUNT(DISTINCT assigned_user) AS total_assigned_user,
                    COUNT(DISTINCT id) AS total_users,
                    COUNT(*) AS total_voters
                ")
            ->where('user_from','GB')
            ->first();

        $agents = DB::table('app_web_users')->pluck('name', 'id');
        return view('backend.dashboard', compact(
            'agents',
            'users',
            'total',
            'male',
            'female',
            'usersGroups',
            'blockcodeGroups',
            'pollingStationGroups'
        ));
    }

    public function recordUpdate10mDashboard(){
        $todayrecords =   $this->recordTodayDashboard();

        $users = User::where('role_id',2)->get()->toArray();
        $current_time =  date("Y-m-d H:i:s", strtotime("now")) . "<br>";
        $one_hour_ago =  date("Y-m-d H:i:s", strtotime('-1 hour'));

        $to =  $current_time;
        $from =  $one_hour_ago;
        foreach ($users as $i1=> $user) {
            $user_data['name']=  $user['first_name'] .' '. $user['last_name'];
            $records = DB::table('voter_list_blockcode_info')
                ->join('voter_list_voters_info', 'voter_list_voters_info.blockcode', 'voter_list_blockcode_info.blockcode')
                ->where('voter_list_voters_info.added_by', $user['id']);
            $records = $records->where('voter_list_voters_info.created_at', '>=', $from);
            $records = $records->where('voter_list_voters_info.created_at', '<=', $to);

            $records = $records->select(DB::raw('COUNT(voter_list_voters_info.name)as total'))->get()->toArray();
            $total =0;
            foreach ($records as $record){
                $total+=$record->total;
            }
            $user_data['total']=  $total;
            $data[$i1] =$user_data;
        }

        $all_records =array();
        $i = 0;
        foreach ($data as $dataa){
            if ($dataa['total'] >0){
                $all_records[$i] = $dataa;
                $i++;
            }
        }

        $response['one_hour'] = $all_records;
        $response['today_records'] = $todayrecords;
        return $response;

    }
    public function recordTodayDashboard(){
        $users = User::where('role_id',2)->get()->toArray();
        $current_time =  date("Y-m-d H:i:s", strtotime("now")) . "<br>";
        //  $ago_10_mintes =  date("Y-m-d H:i:s", strtotime("-30 minutes"));
        $from =  date("Y-m-d").' 00:00:01';
        // $ago_10_mintes =  '2022-07-03 15:56:13';
        $to =  $current_time;
        foreach ($users as $i1=> $user) {
            $user_data['name']=  $user['first_name'] .' '. $user['last_name'];
            $records = DB::table('voter_list_blockcode_info')
                ->join('voter_list_voters_info', 'voter_list_voters_info.blockcode', 'voter_list_blockcode_info.blockcode')
                ->where('voter_list_voters_info.added_by', $user['id']);
            $records = $records->where('voter_list_voters_info.created_at', '>=', $from);
            $records = $records->where('voter_list_voters_info.created_at', '<=', $to);

            $records = $records->select(DB::raw('COUNT(voter_list_voters_info.name)as total'))->get()->toArray();
            $total =0;
            foreach ($records as $record){
                $total+=$record->total;
            }
            $user_data['total']=  $total;
            $data[$i1] =$user_data;
        }

        $all_records =array();
        $i = 0;
        foreach ($data as $dataa){
            if ($dataa['total'] >0){
                $all_records[$i] = $dataa;
                $i++;
            }
        }
        return $all_records;

    }

}
