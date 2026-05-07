@extends('layouts.admin')
@section('title', " :: Blockcode Users")

@section('content')
    <script src="{{ asset('public/assets/js/datatables.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatables_basic.js')}}"></script>
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">Blockcode Users</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">Blockcode Users</span>
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
                        <h5 class="card-title">Blockcode Users</h5>
                    </div>
                    <div class="col-lg-6 pull-right">
                        <a href="{{route('blockcodes.create')}}" class="btn btn-primary pull-right" style="float: right;">Create new</a>
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
                                    <th>Name</th>
                                    <th>Province</th>
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>Tehsil</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach($users as $i=>$user)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$user['user']['first_name']. " " .$user['user']['first_name'] }} ({{$user['user']['email']}})</td>
                                        <td>{{isset(DB::connection('mysql2')->table("provinces")->where('id',$user['province_id'])->first()->name)?DB::connection('mysql2')->table("provinces")->where('id',$user['province_id'])->first()->name:""}}</td>
                                        <td>{{isset(DB::connection('mysql2')->table("divisions")->where('id',$user['division_id'])->first()->name)?DB::connection('mysql2')->table("divisions")->where('id',$user['division_id'])->first()->name:""}}</td>
                                        <td>{{isset(DB::connection('mysql2')->table("districts")->where('id',$user['district_id'])->first()->name)?DB::connection('mysql2')->table("districts")->where('id',$user['district_id'])->first()->name:""}}</td>
                                        <td>{{isset(DB::connection('mysql2')->table("tehsils")->where('id',$user['tehsil_id'])->first()->name)?DB::connection('mysql2')->table("tehsils")->where('id',$user['tehsil_id'])->first()->name:""}}</td>
                                        <td>@if($user['completed'] == 1)  Completed  @else incomplete @endif</td>  <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{route('blockcodes.show',$user['user_id'])}}" class="dropdown-item"> View</a>
                                                    <a href="{{route('blockcodes.edit',$user['id'])}}" class="dropdown-item"> Edit</a>
                                                    <a href="{{route('users-blockcodes-delete',$user['user_id'])}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"> Delete</a>
                                                    <a href="{{url('backend/blockcodes-user-payment',$user['user_id'])}}" class="dropdown-item"> View payment</a>
                                                </div>
                                            </div>
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
@endsection
