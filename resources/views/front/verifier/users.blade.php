@extends('layouts.front')
@section('content')
@section('title', 'DATA CENTER :: Blockcodes')
<script src="{{ asset('frontend/js/datatables.min.js')}}"></script>
<script src="{{ asset('frontend/js/datatables_basic.js')}}"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<style>
    .blockcode-listing-inner {
        height: auto !important;
    }
    div#DataTables_Table_0_info {
        position: fixed;
        left: 28px;
        bottom: 0;
        width: 50%;
        background: #63b190;
        z-index: 12312312;
    }
    div#DataTables_Table_0_paginate {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        float: right;

        background: #63b190;
    }
    .blockcode-table td, .blockcode-table th
    {
        text-align: center;
        vertical-align: middle;
    }
</style>


<div class="main-bg">
            <div class="blockcode-listing-container">
                <div class="blockcode-listing-inner2" style="height: auto;background: #dbdbda2b;">
                  <div class="row" style="">
                      <div class="col-lg-12"> <h3 class="text-center m-0 "><b>USERS LISTING</b></h3></div>
                      <div class="col-col-lg-12 table-responsive blockcode-table-padding table-wrapper-scroll-y my-custom-scrollbar" style="overflow-x: inherit;" >
                            <table class="table blockcode-table text-center  datatable-basic" id ="datatable-basic">
                                <thead>
                                    <tr>
                                        <th>No#</th>
                                        <th>Name</th>
                                        <th>Province</th>
                                        <th>Division</th>
                                        <th>District</th>
                                        <th>Tehsil</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $i=> $user)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$user['user']['first_name'] . " ". $user['user']['last_name']}} ({{$user['user']['email']}})</td>
                                        <td>{{isset(DB::connection('mysql2')->table("provinces")->where('id',$user['province_id'])->first()->name)?DB::connection('mysql2')->table("provinces")->where('id',$user['province_id'])->first()->name:""}}</td>
                                        <td>{{isset(DB::connection('mysql2')->table("divisions")->where('id',$user['division_id'])->first()->name)?DB::connection('mysql2')->table("divisions")->where('id',$user['division_id'])->first()->name:""}}</td>
                                        <td>{{isset(DB::connection('mysql2')->table("districts")->where('id',$user['district_id'])->first()->name)?DB::connection('mysql2')->table("districts")->where('id',$user['district_id'])->first()->name:""}}</td>
                                        <td>{{isset(DB::connection('mysql2')->table("tehsils")->where('id',$user['tehsil_id'])->first()->name)?DB::connection('mysql2')->table("tehsils")->where('id',$user['tehsil_id'])->first()->name:""}}</td>
                                        <td> <a class="btn btn-success" href="{{url('/verifier/user-blockcodes/'.$user['user_id'])}}">View Blockcodes</a> </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                          </div>
                  </div>
                </div>
            </div>
</div>

@endsection
@section('jsfiles')
    <script>
       // $(document).ready(function() {
       //     $('#datatable-basic').DataTable();
       // });
    </script>
@endsection
