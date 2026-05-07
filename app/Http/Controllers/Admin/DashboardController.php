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
class DashboardController extends Controller
{

    public function index()
    {
        $stats = $this->getBlockcodeStats();
        return view('verifier.dashboard', compact('stats'));
    }

    public function stats()
    {
        return response()->json($this->getBlockcodeStats());
    }

    private function getBlockcodeStats(): array
    {
        $table = 'voter_list_blockcode_info';

        $total = DB::table($table)->count();

        // Condition
        $cond = DB::table($table)
            ->selectRaw('`condition`, COUNT(*) as cnt')
            ->groupBy('condition')
            ->pluck('cnt', 'condition')
            ->toArray();

        // Scanned
        $scanned = DB::table($table)
            ->selectRaw('scanned, COUNT(*) as cnt')
            ->groupBy('scanned')
            ->pluck('cnt', 'scanned')
            ->toArray();

        // Deskewed
        $deskewed = DB::table($table)
            ->selectRaw('deskewed, COUNT(*) as cnt')
            ->groupBy('deskewed')
            ->pluck('cnt', 'deskewed')
            ->toArray();

        // Converted
        $converted = DB::table($table)
            ->selectRaw('converted, COUNT(*) as cnt')
            ->groupBy('converted')
            ->pluck('cnt', 'converted')
            ->toArray();

        // QA Converted
        $qa = DB::table($table)
            ->selectRaw('qa_converted_file, COUNT(*) as cnt')
            ->groupBy('qa_converted_file')
            ->pluck('cnt', 'qa_converted_file')
            ->toArray();

        // Uploaded
        $uploaded = DB::table($table)
            ->selectRaw('uploaded, COUNT(*) as cnt')
            ->groupBy('uploaded')
            ->pluck('cnt', 'uploaded')
            ->toArray();

        // Voter totals
        $voters = DB::table($table)
            ->selectRaw('SUM(male_voters) as male, SUM(female_voters) as female, SUM(total_voters) as total, SUM(count_issues) as issues')
            ->first();

        return [
            'total'     => $total,
            'cond'      => [
                'GOOD'    => $cond['GOOD']    ?? 0,
                'AVERAGE' => $cond['AVERAGE'] ?? 0,
                'BAD'     => $cond['BAD']     ?? 0,
                'null'    => $cond['']        ?? ($cond[null] ?? 0),
            ],
            'scanned'   => [
                'DONE'    => $scanned['DONE']    ?? 0,
                'PENDING' => $scanned['PENDING'] ?? 0,
                'ISSUE'   => $scanned['ISSUE']   ?? 0,
            ],
            'deskewed'  => [
                'DONE'    => $deskewed['DONE']    ?? 0,
                'PENDING' => $deskewed['PENDING'] ?? 0,
                'ISSUE'   => $deskewed['ISSUE']   ?? 0,
            ],
            'converted' => [
                'DONE'    => $converted['DONE']    ?? 0,
                'PENDING' => $converted['PENDING'] ?? 0,
                'ISSUE'   => $converted['ISSUE']   ?? 0,
            ],
            'qa'        => [
                'DONE'         => $qa['DONE']         ?? 0,
                'PENDING'      => $qa['PENDING']      ?? 0,
                'DISCREPANCY'  => $qa['DISCREPANCY']  ?? 0,
            ],
            'uploaded'  => [
                'DONE'    => $uploaded['DONE']    ?? 0,
                'PENDING' => $uploaded['PENDING'] ?? 0,
            ],
            'voters'    => [
                'male'   => (int) $voters->male,
                'female' => (int) $voters->female,
                'total'  => (int) $voters->total,
                'issues' => (int) $voters->issues,
            ],
        ];
    }

}
