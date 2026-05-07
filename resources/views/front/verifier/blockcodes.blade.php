@extends('layouts.front')
@section('content')
@section('title', 'DATA CENTER :: Blockcodes')

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

<script src="{{ asset('public/assets/js/datatables.min.js')}}"></script>
<script src="{{ asset('public/assets/js/datatables_basic.js')}}"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<div class="main-bg">
            <div class="blockcode-listing-container">
                <div class="blockcode-listing-inner2" style="height: auto;background: #dbdbda2b">
                  <div class="row">
                      <div class="col-lg-12"> <h3 class="text-center m-0 ">
                              <b>BLOCKCODES LISTING</b>
                          </h3></div>
                      <div class="col-lg-12 text-center">
                          @if($status = \Session::get('success')) <div class="success alert-success ">{{$status}} </div> @endif
                          @if($status = \Session::get('error')) <div class="alert success alert-success"> {{$status}} </div> @endif
                      </div>
                      <div class="col-lg-12 table-responsive blockcode-table-padding table-wrapper-scroll-y my-custom-scrollbar" >
                          <table class="table blockcode-table datatable-basic">
                              <thead>
                              <tr>
                                  <th>District</th>
                                  <th>Tehsil</th>
                                  <th>Blockcode</th>
                                  <th>Area Name</th>
                                  <th>Male Voter</th>
                                  <th>Female Voter</th>
                                  <th>Record added</th>
                                  <th>Status</th>
                                  <th>Details</th>
                              </tr>
                              </thead>
                              <tbody>
                              @foreach($blockcodes as $i=>$block)
                                  <tr>
                                      <td>{{isset($block['blockcode_info']['district_name'])?$block['blockcode_info']['district_name']:""}}</td>
                                      <td>{{isset($block['blockcode_info']['taluka_name'])?$block['blockcode_info']['taluka_name']:""}}</td>
                                      <td>{{$block['blockcode']}}</td>
                                      <td>{{isset($block['blockcode_info']['eloctoral_area_name'])?$block['blockcode_info']['eloctoral_area_name']:""}}</td>
                                      <td>{{isset($block['blockcode_info']['male_voters'])?$block['blockcode_info']['male_voters']:""}}</td>
                                      <td>{{isset($block['blockcode_info']['female_voters'])?$block['blockcode_info']['female_voters']:""}}</td>
                                      <td>{{$block['count_record']}}</td>
                                      <td>@if($block['blockcode_info']['completed'] == 1) completed @else Incompleted @endif</td>

                                      <td>
                                          <div class="dropdown">
                                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Action</button>
                                              <div class="dropdown-menu">
                                                  <a href="{{url('verifier/user-blockcodes/view-records',$block['blockcode'])}}" class="btn btn-success btn-block"> View</a>
                                                  <a href="{{url('verifier/user-blockcodes/edit-records',$block['blockcode'])}}" class="btn btn-success btn-block" onclick="return confirm('Are you sure you want to edit this record?');" > Edit</a>
{{--                                                  <a href="{{url('verifier/user-blockcodes/change-status',$block['blockcode'])}}" class="btn btn-success btn-block"> Change Status</a>--}}
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

@endsection
@section('jsfiles')
    <script>

    </script>
@endsection
