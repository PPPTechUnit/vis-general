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
        <!-- Default alerts -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    @if(session('success'))
                        <div class="col-lg-12">
                        <div class="alert alert-success">  {{session('success')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        </div>
                        </div>
                    @endif @if(session('error'))
                            <div class="col-lg-12">
                        <div class="alert alert-danger">  {{session('error')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
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
                            <table class="table  datatable-basic">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th> User ID</th>
                                    <th> Name</th>
                                    <th>CNIC Number</th>
                                    <th>Phone Number</th>
                                    <th>Assigned User</th>
                                    <th>User From</th>
                                    <th>Polling Stations</th>
                                    <th>Constituency</th>
                                    <th>Reference</th>
                                    <th>FCM Token</th>
                                    <th>Created Time</th>
                                    <th>Updated Time</th>

                                </tr>
                                </thead>
                                <tbody>
                                  @foreach($users as $i=>$user)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$user['id']}}</td>
                                        <td>
                                            <a href="{{route('app-web-users.show',$user['id'])}}" > {{$user['name']}}</a>

                                            </td>




                                        <td>{{$user['cnic_number']}}</td>
                                        <td>{{$user['phone_number']}}</td>
                                        <td>{{$user['assigned_user']}}</td>
                                        <td>{{$user['user_from']}}</td>
                                        <td>{{$user['polling_station']}}</td>
                                        <td>{{$user['na_cons_id']}}</td>
                                        <td>{{$user['reference']}}</td>
                                        <td>{{$user['fcm_token']}}</td>
                                        <td>
                                            {{\Carbon\Carbon::parse($user['created_at'])->format('Y-m-d H:i:s')}}
                                            </td>
  <td>
                                            {{\Carbon\Carbon::parse($user['updated_at'])->format('Y-m-d H:i:s')}}
                                            </td>


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
        });
    </script>
@endsection
