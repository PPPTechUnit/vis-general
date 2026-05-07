<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DB;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function showUserDashboard(){
        $users = User::where('role', 'user')->latest()->get()->keyBy('id');

        $dailyUpdates = DB::table('voter_list_voters_info')
            ->select(
                DB::raw('DATE(updated_at) as update_date'),
                'updated_by',
                DB::raw('COUNT(*) as total_updated'),
                DB::raw('MIN(TIME(updated_at)) as start_time'),
                DB::raw('MAX(TIME(updated_at)) as end_time')
            )
            ->where('updated_by', '!=', 0)
            ->whereNotNull('updated_at')
            ->groupBy(DB::raw('DATE(updated_at)'), 'updated_by')
            ->orderBy('update_date', 'desc')
            ->orderBy('total_updated', 'desc')
            ->get();

        return view('admin.users.user_dashbaord', compact('dailyUpdates', 'users'));



    }
}
