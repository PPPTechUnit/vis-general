@extends('layouts.admin')
@section('title', 'APP & WEB Users :: users Listing')

@section('content')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">APP & WEB Users</span> - Listing</h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">users listing</span>
                </div>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    @if(session('success'))
                        <div class="col-lg-12">
                            <div class="alert alert-success">{{session('success')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="col-lg-12">
                            <div class="alert alert-danger">{{session('error')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-6">
                        <h5 class="card-title">users listing</h5>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table datatable-basic">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>CNIC Number</th>
                                    <th>Phone Number</th>
                                    <th>Assigned User</th>
                                    <th>User From</th>
                                    <th>Polling Stations</th>
                                    <th>Constituency</th>
                                    <th>Reference</th>
                                    <th>Location</th>
                                    <th>Kill Switch</th>
                                    <th>FCM Token</th>
                                    <th>Created Time</th>
                                    <th>Updated Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $i => $user)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$user['id']}}</td>
                                        <td>
                                            <a href="{{route('app-web-users.show', $user['id'])}}">{{$user['name']}}</a>
                                        </td>
                                        <td>{{$user['cnic_number']}}</td>
                                        <td>{{$user['phone_number']}}</td>
                                        <td>{{$user['assigned_user']}}</td>
                                        <td>{{$user['user_from']}}</td>
                                        <td>{{$user['polling_station']}}</td>
                                        <td>{{$user['na_cons_id']}}</td>
                                        <td>{{$user['reference']}}</td>
                                        <td>
                                            <a href="{{url('backend/app-web-users-location/'.$user['id'])}}">View Location</a>
                                        </td>
                                        <td>
                                            {{-- Kill Switch Toggle Button --}}
                                            <button
                                                    class="btn btn-sm kill-switch-btn {{ $user['kill_switch'] == 1 ? 'btn-success' : 'btn-danger' }}"
                                                    data-id="{{ $user['id'] }}"
                                                    data-status="{{ $user['kill_switch'] }}"
                                                    data-url="{{ url('backend/app-web-users-kill-switch/' . $user['id']) }}"
                                                    title="{{ $user['kill_switch'] == 1 ? 'Click to turn OFF' : 'Click to turn ON' }}">
                                                <i class="icon-{{ $user['kill_switch'] == 1 ? 'checkmark3' : 'cross2' }} mr-1"></i>
                                                {{ $user['kill_switch'] == 1 ? 'ON' : 'OFF' }}
                                            </button>

                                            {{-- Hidden form for POST request --}}
                                            <form
                                                    id="kill-switch-form-{{ $user['id'] }}"
                                                    action="{{ url('backend/app-web-users-kill-switch/' . $user['id']) }}"
                                                    method="POST"
                                                    style="display: none;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="kill_switch" value="{{ $user['kill_switch'] == 1 ? 0 : 1 }}">
                                            </form>
                                        </td>
                                        <td>{{$user['fcm_token']}}</td>

                                        <td>{{\Carbon\Carbon::parse($user['created_at'])->format('Y-m-d H:i:s')}}</td>
                                        <td>{{\Carbon\Carbon::parse($user['updated_at'])->format('Y-m-d H:i:s')}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.datatable-basic').DataTable({
                "pageLength": 10,
                "ordering": true,
                "searching": true
            });

            // Kill Switch toggle with confirmation
            $(document).on('click', '.kill-switch-btn', function () {
                const btn       = $(this);
                const userId    = btn.data('id');
                const status    = btn.data('status');
                const newStatus = status == 1 ? 'OFF' : 'ON';
                const action    = status == 1 ? 'turn OFF' : 'turn ON';

                const confirmed = confirm(
                    `Are you sure you want to ${action} the Kill Switch for User ID: ${userId}?\n\n` +
                    `Current status: ${status == 1 ? 'ON' : 'OFF'} → New status: ${newStatus}`
                );

                if (confirmed) {
                    $(`#kill-switch-form-${userId}`).submit();
                }
            });
        });
    </script>
@endsection