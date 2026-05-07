@extends('layouts.admin')
@section('title', 'Voters Information')

@section('content')
    <script src="{{ asset('public/assets/js/datatables.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatables_basic.js')}}"></script>
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Voters Information</span> - Listing</h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="{{ route('blockcodes.index')}}" class="breadcrumb-item">Blockcode users </a>
                    <span class="breadcrumb-item active"> Detail</span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <!-- /page header -->


    <!-- Content area -->
    <div class="content">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title"> <a href="{{ route('blockcodes.index') }}" ><i class="icon-reply"></i></a></h5>
            </div>
        </div>
        <div class="card">
            @if(session('success'))
                <div class="col-lg-12" style="    margin-top: 15px;">
                    <div class="alert alert-success">  {{session('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    </div>
                </div>
            @endif @if(session('error'))
                <div class="col-lg-12" style="    margin-top: 15px;">
                    <div class="alert alert-danger">  {{session('error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    </div>
                </div>
            @endif
        </div>
        <!-- Default alerts -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Blockcode Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr><th>National Assembly</th> <td>{{$blockcode_info['national_assembly_name']}}</td></tr>
                                <tr><th>Provincial Assembly</th> <td>{{$blockcode_info['provincial_assembly_name']}}</td></tr>
                                <tr><th>District</th> <td>{{$blockcode_info['district_name']}}</td></tr>
                                <tr><th>Taluka</th> <td>{{$blockcode_info['taluka_name']}}</td></tr>
                                <tr><th>Village  / City</th> <td>{{$blockcode_info['village_city']}}</td></tr>
                                <tr><th>Union Council</th> <td>{{$blockcode_info['union_council']}}</td></tr>
                                <tr><th>Area Name</th> <td>{{$blockcode_info['eloctoral_area_name']}}</td></tr>
                                <tr><th>Blockcode</th> <td>{{$blockcode_info['blockcode']}}</td></tr>
                                <tr><th>Male Voters</th> <td>{{$blockcode_info['male_voters']}}</td></tr>
                                <tr><th>Female Voters</th> <td>{{$blockcode_info['female_voters']}}</td></tr>
                                <tr><th>Total Voters</th> <td>{{$blockcode_info['total_voters']}}</td></tr>
                                <tr><th>Circle Name</th> <td>{{$blockcode_info['circle_name']}}</td></tr>
                                <tr><th>Book Number</th> <td>{{$blockcode_info['book_number']}}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default alerts -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Voters Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table  datatable-basic">
                                <thead>
                                <tr>
                                    <th>S#</th>
                                    <th>Name</th>
                                    <th>Father's Name</th>
                                    <th>CNIC No</th>
                                    <th>Age</th>
                                    <th>Silsila No</th>
                                    <th>Gharana No</th>
                                    <th>Address</th>
                                    <th>Invalid Address</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($voters_info as $i=> $voter)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$voter->name}}</td>
                                        <td>{{$voter->father_husband_name}}</td>
                                        <td>{{$voter->cnic_no}}</td>
                                        <td>{{$voter->age}}</td>
                                        <td>{{$voter->silsila_no}}</td>
                                        <td>{{$voter->gharana_no}}</td>
                                        <td>{{$voter->address}}</td>
                                        <td>{{($voter->invalid_address == 1)?'Invalid':'valid'}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{url('backend/blockcodes/view-records/delete-record',$voter->id)}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"> Delete</a>
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
