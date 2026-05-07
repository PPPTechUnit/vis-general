@extends('layouts.admin')
@section('title', 'users :: users Listing')

@section('content')
    <script src="{{ asset('public/assets/js/datatables.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatables_basic.js')}}"></script>

    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">Users</span> - Listing</h4>
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
                    <div class="col-lg-6 pull-right">
                        <a href="{{route('users.create')}}" class="btn btn-primary pull-right" style="float: right;">Create new</a>
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
                                    <th>Profile</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Designation</th>
                                    <th>Verify</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach($users as $i=>$user)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td> <img src="{{ config('globalvariables.s3_bucket_url').$user['profile_picture']}}" width="100"></td>
                                        <td>{{$user['first_name']}}</td>
                                        <td>{{$user['last_name']}}</td>
                                        <td>{{$user['email']}}</td>

                                        <td>{{isset($user['role']['name'])?$user['role']['name']:""}}</td>
                                        <td>@if($user['verified'] == 1)  verified  @else un-verified @endif</td>
                                       <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{route('users.edit',$user['id'])}}" class="dropdown-item"> Edit</a>
                                                    <a href="{{url('backend/user/delete',$user['id'])}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"> Delete</a>
                                                    <a href="{{url('backend/user/verify',$user['id'])}}-@if($user['verified'] == 1)0 @else 1 @endif" @if($user['verified'] == 1) onclick="return confirm('Are you sure you want to un verify this ?');"  @else onclick="return confirm('Are you sure you want to verify this ?');"  @endif class="dropdown-item"> @if($user['verified'] == 1) un verify  @else verify @endif</a>
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
