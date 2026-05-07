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
    tbody{
        overflow-x: auto !important; ;
    }
    ul.pagination li a {
        color: #63b190;
        border-color: #63b190;
    }
    .page-item.active .page-link {
        z-index: 1;
        color: #fff;
        background-color: #63b190;
        border-color: #63b190;
    }
</style>

<script src="{{ asset('public/assets/js/datatables.min.js')}}"></script>
<script src="{{ asset('public/assets/js/datatables_basic.js')}}"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<div class="main-bg">
            <div class="blockcode-listing-container">
                <div class="blockcode-listing-inner2" style="background: #dbdbda2b">
                  <div class="row" style="margin: 70px 0px;">
                      <div class="col-lg-12" style="padding-left: 30px;padding-right: 30px;">
                          {{--                          <embed src="{{'https://techunitbucket.s3-ap-southeast-1.amazonaws.com/production-assets/media/pdf/block_codes/tOIvgSZ3u6ASpkRcO4lWhz0r4v0I10PjPmnUHb6S.pdf'}}" style="width: 100%; height: 350px;"></embed>--}}
                          <embed src="{{$pdf}}" style="width: 100%; height: 350px;"></embed>
{{--                          <embed src="http://localhost/data-center/public/assets/410040702.pdf" style="width: 100%; height: 350px;"></embed>--}}
                      </div>
                      <div class="col-lg-12"> <h3 class="text-center m-0 "><b>BLOCKCODES LISTING # {{$blockcode_info['blockcode']}} </b></h3>
                          @if (\Session::has('success'))
                              @if(Session::get('success') == 1)
                                  <div class="col-lg-12 electoral-msg"> <h5 style="color: #447c66;margin-bottom: 10px !important;" class="text-center m-0">Voter Information deleted successfully </h5></div>
                              @elseif(Session::get('success') == 2)
                                  <div class="col-lg-12 electoral-msg"> <h5 style="color: #447c66;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information updated successfully </h5></div>
                              @elseif(Session::get('success') == 0)
                                  <div class="col-lg-12 electoral-msg"> <h5 style="color: #ff835d;margin-bottom: 10px !important;" class="text-center m-0">Error in deleting Voter Information  </h5></div>
                              @else
                              @endif
                          @endif
                      </div>
                      <div class="col-lg-12"> <h5 class="text-center " id="voter-msg"></h5></div>
                      <div class="col-lg-12">
                          <form>
                              <div class="row" style="margin: 10px 15px;    background: #fff;    padding-top: 15px;">
                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <input type="text" class="form-control" id="name" placeholder="Search Name" name="name" @if(isset($_GET['name']) && $_GET['name'] != "") value="{{$_GET['name']}}" @else value="" @endif>
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                      <input type="text" class="form-control" id="cnic_no" placeholder="Search CNIC Number" name="cnic_no" @if(isset($_GET['cnic_no']) && $_GET['cnic_no'] != "") value="{{$_GET['cnic_no']}}" @else value="" @endif>
                                  </div>
                                  <div class="col-md-3">
                                      <button type="submit" class="next-btn btn btn-success">Search</button>
                                      <button type="button" onclick="clearFilter()" class="next-btn btn btn-success">Clear Filter</button>
                                  </div>
                              </div>
                          </form>
                      </div>
                      <div class="col-lg-12 table-responsive blockcode-table-padding table-wrapper-scroll-y my-custom-scrollbar" >
                          <table class="table   datatable-basic2 blockcode-table">
                              <thead>
                              <tr>
                                  <th>S.No</th>
                                  <th>Action</th>
                                  <th>Verifying By</th>
                                  <th>Added By</th>
                                  <th>Address</th>
                                  <th>Age</th>
                                  <th>CNIC No</th>
                                  <th>Father's / Husband's Name</th>
                                  <th>Name</th>
                                  <th>Gharana No</th>
                                  <th>Silsila No</th>



                              </tr>
                              </thead>
                              <tbody>
                              @foreach($voters_info as $i=> $voter)
{{--                                  @if($voter->verified_by == 0 || $voter->verified_by == Auth::user()->id)--}}
                                      <tr id="{{'voter-tr-'.$voter->id}}">
                                          <td>{{++$i}}</td>
                                          <td>
                                              <div class="dropdown">
                                                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Action</button>
                                                  <div class="dropdown-menu">
                                                      <a href="{{url('verifier/user-blockcodes/view-records/edit/'.$voter->id)}}"  onclick="return confirm('Are you sure you want to edit this record?');" class="btn btn-success btn-block"> Edit</a>
                                                      <a href="javascript:void(0)"  onclick="deletingRecord({{$voter->id}})"  class="btn btn-success btn-block"> Delete</a>
                                                  </div>
                                              </div>
                                          </td>
                                          <td>  @if($voter->verified_by != 0) <a class="btn btn-primary btn-block" style="color: #FFF">{{isset(\App\User::where('id',$voter->verified_by)->first()->first_name)?\App\User::where('id',$voter->verified_by)->first()->first_name:""}}</a>
                                              @else <button class="btn btn-success btn-block" type="button" id="voter-{{$voter->id}}"  onclick="verifyRecord({{$voter->id}})"  >Verifying</button> @endif
                                          </td>
                                          <td>{{isset(DB::table("users")->where('id',$voter->added_by)->first()->first_name)?DB::table("users")->where('id',$voter->added_by)->first()->first_name:""}}</td>

                                          <td>{{$voter->address}}</td>
                                          <td>{{$voter->age}}</td>
                                          <td>{{$voter->cnic_no}}</td>
                                          <td> @if($voter->father_husband == 1) {{"Father's Name"}} @elseif($voter->father_husband) {{"Husband's Name"}} @else @endif {{$voter->father_husband_name}}</td>
                                          <td>{{$voter->name}}</td>
                                          <td>{{$voter->gharana_no}}</td>
                                          <td>{{$voter->silsila_no}}</td>



                                  </tr>
                                  {{--@endif--}}
                              @endforeach
                              </tbody>
                          </table>
                         {{$voters_info->appends($url)->links()}}
                      </div>
                  </div>
                </div>
            </div>
</div>

@endsection


@section('jsfiles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

    <sript src="{{asset('/public/frontend/js/jquery.maskedinput.js')}}">
        <script>

        jQuery(function($){
            $("#cnic_no").mask("99999-9999999-9");

        });
        
        function clearFilter() {
            var url      = window.location.href;     // Returns full URL (https://example.com/path/example.html)
          var path =  url.split("?");
           if(path.length ==2){
               window.location.href = path[0];
           }

        }
        function  verifyRecord(id){
            var isconfirm =   confirm('Are you sure you want to Voter Information verifying?');
            if (isconfirm){
           if (id !=""){
               $.ajax({
                   url: "{{url('/verifier/user-blockcodes/view-record-verifying')}}",
                   type: 'POST',
                   data:{'id':id,"_token": "{{ csrf_token() }}"},
                   success: function(response) {
                       $("#voter-msg").empty();
                      if(response.status ===1){
                          console.log(id);
                          $("#voter-"+id+"").html(response.name);
                          $("#voter-"+id+"").removeClass('btn-success');
                          $("#voter-"+id+"").addClass('btn-primary');
                          $("#voter-msg").append("<span class='m-0' style='color: #63b190;font-weight: 500;'>Voter Information verified Successfully</span>");
                      }else {
                      $("#voter-msg").append("<span class='m-0' style='color: red;font-weight: 500;'>Error in voter information verifying</span>");
                      }
                   }
               });
           }
            }
        }


        function deletingRecord(id){
            var isconfirm =   confirm('Are you delete this Voter Information record?');
            if (isconfirm){

                console.log('id');

                if (id !=""){
                    $.ajax({
                        url: "{{url('/verifier/user-blockcodes/view-records/delete')}}/"+id,

                        success: function(response) {
                            // console.log(response); return false;
                            $("#voter-msg").empty();
                            if(response.status ===1){
                                console.log(id);
                                $("#voter-tr-"+id+"").remove();
                                $("#voter-msg").append("<span class='m-0' style='color: #63b190;font-weight: 500;'>Voter Information deleted Successfully</span>");
                            }else {
                                $("#voter-msg").append("<span class='m-0' style='color: red;font-weight: 500;'>Error in voter information deleting</span>");
                            }
                        }
                    });
                }
            }
        }
        $(document).ready(function() {
//
//            //$('#cnic').on('input', function (e) {
//            $('input[type=search]').on('input', function (e) {
//                var pattern= /^[0-9+]{5}-[0-9+]{7}$/;
//              var nic =   $('input[type=search]').val();
//              console.log(nic.length);
//              if(nic.length == 5){
//                  $('#voter-msg').empty();
//                  //  $("input[name=cnic]").attr("maxlength", "15");
//                  var key = e.which || this.value.substr(-1).charCodeAt(0);
//                  var nic = $('input[type=search]').val();
//                  if (key >= 48 && key <= 57) {
//                      if (nic.length == 5) {
//                          $('input[type=search]').val(nic + "-");
//                      } else if (nic.length == 13) {
//                          $('input[type=search]').val(nic + "-");
//
//                      }
//                      else if (nic.length == 15) {
//                      }
//                      else if (nic.length > 15) {
//                          e.preventDefault();
//                          return false;
//                      }
//                  } else {
//                      editedText = nic.slice(0, -1);
//                      console.log(editedText);
//                      $('input[type=search]').val(editedText);
//                      e.preventDefault();
//                      return false;
//                  }
//              }
//              else if((pattern.test(nic)) && nic.length == 13){
//                  $('input[type=search]').val(nic + "-");
//                }
//                console.log("testing");
//
//                return false;
//
//            });
//            $('input[type=search]').on('input', function (e) {
//                var nic =   $('input[type=search]').val();
//
//                var lastChar = nic.substr(nic.length - 1);
//                if(nic.length == 14 && lastChar == '-'){
//                  //  var lastChar = nic.substr(nic.length - 1);
//                  //  if(lastChar == '-'){
//                   //   var replaced_nic =   nic.substring(0,nic.length - 1);
//                      //  $('input[type=search]').val(replaced_nic);
//                  //  }
//                }
//
//
//            });
        });
    </script>
@endsection
