@extends('layouts.front')
@section('content')
@section('title', 'DATA CENTER :: VOTER DATA REGISTRATION')

<Style>
    textarea#address {
        border-radius: 0px;
        border: 1px solid #63b190;
    }
    textarea#address:focus {
        border: 1px solid #63b190;
    }
    input[type=checkbox]
    {
        /* Double-sized Checkboxes */
        -ms-transform: scale(2); /* IE */
        -moz-transform: scale(2); /* FF */
        -webkit-transform: scale(2); /* Safari and Chrome */
        -o-transform: scale(2); /* Opera */
        transform: scale(2);
        padding: 10px;
    }

    /* Might want to wrap a span around your checkbox text */
    .checkboxtext
    {
        /* Checkbox text */
        font-size: 110%;
        display: inline;
    }
    .blockcode-block label {
        margin: 0px;
        font-weight: 600;
        font-size: 14px;
    }
    .error {
        color: red !important;
        font-weight: 400 !important;
    }
    .form-control {
        padding: 5px;
    }
</Style>
<div class="main-bg">
<?php
   ?>

            <div class="blockcode-listing-container">
                <div class="blockcode-listing-inner" style="height: 750px;;">
                  <div class="row">
                      <div class="col-lg-12"> <h4 class="text-center m-0" style="padding:10px 0px"><b>VOTER DATA REGISTRATION</b></h4></div>


                      <div class="col-lg-12" style="padding-left: 30px;padding-right: 30px;">
{{--                          <embed src="{{'https://techunitbucket.s3-ap-southeast-1.amazonaws.com/production-assets/media/pdf/block_codes/tOIvgSZ3u6ASpkRcO4lWhz0r4v0I10PjPmnUHb6S.pdf'}}" style="width: 100%; height: 350px;"></embed>--}}
                          <embed src="{{$pdf}}" style="width: 100%; height: 350px;"></embed>
{{--                          <embed src="{{$pdf}}" type="application/pdf" style="width: 100%; height: 620px;" />--}}
                      </div>
                      <div class="col-lg-12 electoral-list">
                          <div class="blockcode-block">
                              <ul class="nav nav-tabs" role="tablist">
                                  <li class="nav-item">
                                      <a class="nav-link active " id="tab-01" data-toggle="tab" href="#home">ELECTORAL INFORMATION</a>
                                  </li>
                                  <li class="nav-item">
                                      <a class="nav-link "  id="tab-02" data-toggle="tab" href="#menu1">VOTER INFORMATION</a>
                                  </li>
                                  <li class="nav-item">
                                      <a  class="nav-link" href="{{url('/user/send-email')}}"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Email </a>
                                  </li>
                              </ul>
                              <a href=""  style="position: absolute;   top: 11px;  color: #18d26e;right: 36px;font-weight: 500;pointer-events: none;cursor: default;;"> You  have Earned Rs. <span id="you-earned">{{($data['count_record'])}}</span> PKR</a>
                              <!-- Tab panes -->
                              <div class="tab-content">
                                  <div class="row">
                                      <div class="col-lg-12">
                                          @if (\Session::has('success'))
                                              @if(Session::get('success') == 1)
                                                  <div class="col-lg-12 electoral-msg"> <h5 style="color: #447c66;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information Added successfully </h5></div>
                                              @elseif(Session::get('success') == 2)
                                                  <div class="col-lg-12 electoral-msg"> <h5 style="color: #447c66;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information updated successfully </h5></div>
                                              @elseif(Session::get('success') == 0)
                                                  <div class="col-lg-12 electoral-msg"> <h5 style="color: #ff835d;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information already added successfully </h5></div>
                                              @else
                                              @endif
                                          @endif
                                      </div>
                                  </div>
                                  <div id="home" class="container tab-pane  active ">
                                      <form autocomplete="off" action="{{url('/submit-electoral-information')}}" id="electoral-form" method="post">
                                          @csrf
                                          <input type="hidden" class="form-control step_2" name="electoral" value="{{$data['electoral']}}">
                                          <div class="row">
                                              <div class="col-md-3"><label>Area Name <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2" name="area_name" placeholder="Enter Area Name"  @if($data['electoral']  != 0) value="{{$data['eloctoral_area_name']}}" @else value="{{old('area_name')}}" @endif >
                                                  @if ($errors->has('area_name'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('area_name') }}</div>@endif
                                              </div>
                                              <div class="col-md-3"><label>National Assembly * </label>
                                                  <select class="form-control step_2" name="national_assembly">
                                                      @foreach($data['na'] as $na)
                                                          <option value="{{$na->id}}">{{$na->name}} </option>
                                                      @endforeach
                                                  </select>
                                                  @if ($errors->has('national_assembly'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('national_assembly') }}</div>@endif

                                              </div>
                                              <div class="col-md-3"><label>Provincial Assembly  <span style="color: red">*</span></label>
                                                  <select class="form-control step_2" name="provincial_assembly">
                                                      @foreach($data['pa'] as $pa)
                                                          <option value="{{$pa->id}}">{{$pa->name}} </option>
                                                      @endforeach
                                                  </select>
                                                  @if ($errors->has('provincial_assembly'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('provincial_assembly') }}</div>@endif

                                              </div>
                                              <div class="col-md-3"><label>Block Code  <span style="color: red">*</span></label><input type="text" class="form-control step_2" name="blockcode123" placeholder="Enter Blockcode" readonly value="{{$data['code']}}" ><input type="hidden" class="form-control step_2" name="blockcode" placeholder="Enter Blockcode" value="{{$data['code']}}" ></div>

                                          </div>
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <label>District  <span style="color: red">*</span> </label>
                                                  <select class="form-control step_2" name="district">
                                                      @foreach($data['districts'] as $district)
                                                          <option value="{{$district->id}}">{{$district->name}} </option>
                                                      @endforeach
                                                  </select>
                                                  @if ($errors->has('district'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('district') }}</div>@endif

                                              </div>
                                              <div class="col-md-3"><label>Taluka / Tehsil  <span style="color: red">*</span></label>
                                                  <select class="form-control step_2" name="tehsil">
                                                      @foreach($data['tehsils'] as $tehsil)
                                                          <option value="{{$tehsil->id}}">{{$tehsil->name}} </option>
                                                      @endforeach
                                                  </select>
                                                  @if ($errors->has('tehsil'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('tehsil') }}</div>@endif

                                              </div>
                                              <div class="col-md-3"><label>City/Village <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2" name="city" placeholder="Enter village city"    @if($data['electoral']  != 0) value="{{$data['village_city']}}" @else value="{{old('city')}}" @endif >
                                                  @if ($errors->has('city'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('city') }}</div>@endif
                                              </div>
                                              <div class="col-md-3"><label>Patwar Halka  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2" name="patwar_halka" placeholder="Enter Patwar Halka"   @if($data['electoral']  != 0) value="{{$data['circle_name']}}" @else value="{{old('patwar_halka')}}" @endif>
                                                  @if ($errors->has('patwar_halka'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('patwar_halka') }}</div>@endif
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-3"><label>Union Council </label>
                                                  <input type="text" class="form-control copy_paste step_2" name="union_council" placeholder="Enter Union Council"  @if($data['electoral']  != 0) value="{{$data['union_council']}}" @else value="{{old('union_council')}}" @endif>
                                                  @if ($errors->has('union_council'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('union_council') }}</div>@endif
                                              </div>
                                              <div class="col-md-3"><label>Male Voters  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2" name="male_voters" id="male_voters" placeholder="Enter Male Voters"  @if($data['electoral']  != 0) value="{{$data['male_voters']}}" @else value="{{old('male_voters')}}" @endif>
                                                  @if ($errors->has('male_voters'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('male_voters') }}</div>@endif
                                              </div>
                                              <div class="col-md-3"><label>Female Voters  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2" name="female_voters" id="female_voters" placeholder="Enter Female Voters"   @if($data['electoral']  != 0) value="{{$data['female_voters']}}" @else value="{{old('female_voters')}}" @endif>
                                                  @if ($errors->has('female_voters'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('female_voters') }}</div>@endif
                                              </div>
                                              <div class="col-md-3"><label>Book Number  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2" name="book_number" placeholder="Enter Book Number"   @if($data['electoral']  != 0) value="{{$data['book_number']}}" @else value="{{old('book_number')}}" @endif>
                                                  @if ($errors->has('book_number'))<div class="alert alert-danger" style="background-color: #f8d7da00; border-color: #f8d7da00;">  {{ $errors->first('book_number') }}</div>@endif
                                              </div>
                                          </div>

                                          <div class="row">

                                              <div class="col-lg-12">
                                                  <button style="margin-right:0px;" class="next-btn btn btn-success pull-right" type="submit" id="next-btn">SUBMIT <img id="pageloader" src="https://icon-library.com/images/loading-icon-transparent-background/loading-icon-transparent-background-12.jpg" style="display: none; height: 20px"></button>
                                                  </div>
{{--                                                  <div class="col-lg-1"><ul class="nav skip-tab" style=""><li class=""><a class="" href="javascript:void(0)" onclick="SkipTab()">skip</a></li></ul></div>--}}
                                          </div>
                                      </form>
                                  </div>
                                  <div id="menu1" class="container tab-pane fade voter-info-block  ">
                                      <form autocomplete="off" id="basic-form" action="{{url('add-voter-information')}}" method="post">
                                          <div class="row">
                                              <div class="col-md-12">
                                                  <div id="voter-msg" style="font-weight: 500;"></div>
                                                 </div>
                                          </div>
                                          @csrf
                                          <div class="row"> <div class="col-md-12" style="font-weight: 500;">  <p id="last_silsila" style="margin-bottom: 10px; font-weight: bold">This is your last added CNIC  No# {{$last_cnic_no}}</p></div></div>

                                          <div class="row">
                                              <div class="col-md-1">
                                                  <label for="silsila_no">Silsila <span style="color: red">*</span></label>
                                                  <input type="hidden" class="form-control step_2" id="electoral_id" name="electoral_id" value="@if($data['electoral'] == 0)@else{{$data['electoral']}}@endif" >
                                                  <input type="hidden" class="form-control copy_paste step_2" name="blockcode" value="{{$data['code']}}" >
                                                  <input type="text" class="form-control  copy_paste step_2" id="silsila_no" name="silsila_no" placeholder="No" id="silsila_no" autofocus>
                                              </div>
                                              <div class="col-md-2">
                                                  <label for="house_no">House/Gharana No  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2" name="house_no" id="house_no" placeholder="House No" >
                                              </div>
                                              <div class="col-md-3">
                                                  <label for="first_name">First Name  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2 only-alphabat" onfocusout="nameONfocusout('first_name')" name="first_name" id="first_name" placeholder="Enter First Name" >
                                              </div>
                                              <div class="col-md-3">
                                                  <label for="middle_name">Middle Name  </label>
                                                  <input type="text" class="form-control copy_paste step_2 only-alphabat"  onfocusout="nameONfocusout('middle_name')"  name="middle_name" id="middle_name" placeholder="Enter First Name" >
                                              </div>
                                              <div class="col-md-3">
                                                  <label for="last_name">Last Name </label>
                                                  <input type="text" class="form-control copy_paste step_2 only-alphabat" onfocusout="nameONfocusout('last_name')"  name="last_name" id="last_name" placeholder="Enter First Name" >
                                              </div>

                                          </div>
                                          <div class="row">
                                              <div class="col-md-3" style="    padding-top: 13px;">
                                                  <div class="custom-control custom-radio">
                                                      <input type="radio" class="custom-control-input" id="sof_dof" checked value="1" name="father_husband">
                                                      <label class="custom-control-label" for="sof_dof">father's Name</label>
                                                  </div>
                                                  <div class="custom-control custom-radio">
                                                      <input type="radio" class="custom-control-input" id="wof" value="2" name="father_husband">
                                                      <label class="custom-control-label" for="wof">Husband's Name</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <label for="fname">Father's / Husband's Name  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control  copy_paste step_2 only-alphabat" name="fname" id="fname" placeholder="Enter Father's / Husband's Name">
                                              </div>

                                              <div class="col-md-3">
                                                  <label for="cnic">CNIC (XXXXX-XXXXXXX-X) <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2" name="cnic" id="cnic" placeholder="Enter CNIC Number" >
                                                  <label  class="cnic-exist" style="color: red;">This CNIC Number already exist .</label>
                                              </div>
                                              <div class="col-md-3">
                                                  <label for="age">Age  <span style="color: red">*</span></label>
                                                  <input type="text" id="age" class="form-control  copy_paste step_2" name="age" id="age" placeholder="Age">
                                              </div>

                                          </div>
                                          <div class="row">

                                              <div class="col-md-12">
                                                  <label for="address">Address  <span style="color: red">*</span></label>
                                                  <textarea class="form-control  step_2" name="address" id="address" placeholder="Enter Address"></textarea>
                                              </div>
                                          </div>

                                          <div class="row" style="margin-bottom: 0px;">
                                               <div class="col-md-12" style="padding-left: 22px;">
                                                  <div class="form-check" >
                                                      <label class="form-check-label" style="font-size: 16px;"><input type="checkbox" value="1" name="invalid_address" class="form-check-input" value=""><span class="checkboxtext">If address not valid</span></label>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="row" style="margin-top: 0px">


                                              <div class="col-lg-12">
                                                  <button class="next-btn btn btn-success pull-right" type="submit" style="margin-right: 0px;" id="next-btn" @if($data['electoral'] == 0) disabled @else  @endif>@if($data['electoral'] == 0) First add electoral information @else Add  @endif <img id="next-btn-loader" src="https://icon-library.com/images/loading-icon-transparent-background/loading-icon-transparent-background-12.jpg" style="display: none; height: 20px"></button>
                                              </div>
                                          </div>

                                      </form>
                                  </div>
                              </div>
                          </div>
                          </div>

                  </div>
                </div>
            </div>
</div>



@endsection
@section('jsfiles')



    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
    <script>


        function nameONfocusout(id){
            console.log(id);

            var _val = document.getElementById(id).value;
            console.log(_val);
             var voters_names = JSON.parse( localStorage.getItem('voters' ) );
            var name = _val.charAt(0).toUpperCase() + _val.slice(1);
            jQuery.each(voters_names, function(index, item) {
             var isExist =    item.includes(name);
             var isExist2 =    item.includes(_val);
              if(isExist == true || isExist2 == true){
                  document.getElementById(id).value = item[0];
             }else {
                 console.log("Not Exist");
             }
            });

        }

        $(document).ready(function(){
            $("#electoral-form").on("submit", function(){
                $("#pageloader").fadeIn();
            });//submit
        });//document ready
        {{--$("#next-btn").on('click', function(){--}}
        {{--    window.location = "{{url('/user/blockcode/213423423')}}";--}}
        {{--});--}}

       function SkipTab(){
           $("#tab-01").removeClass('active show');
           $("#tab-02").addClass('active show');

           $("#home").removeClass('active show');
           $("#menu1").addClass('active show');
       }
        $(document).ready(function() {


            $("#basic-form").validate({
                rules: {
                    electoral_id : {
                        required: true,
                    },
                    silsila_no : {
                        required: true,
                        number: true,
                        minlength: 1,
                        maxlength: 4
                    },
                    house_no : {
                        required: true,
                        minlength: 1,
                        maxlength: 5
                    },
                    cnic : {
                        required: true,
                        pattern: /^[0-9+]{5}-[0-9+]{7}-[0-9]{1}$/,
                    },
                    first_name : {
                        required: true,
                        minlength: 3
                    },
                    age: {
                        required: true,
                        number: true,
                        min: 18,
                        max: 150,
                    },
                    address: {
                        required: true,
                        minlength: 3
                    },
                    fname: {
                        required: true,
                        minlength: 3
                    },
                },
                messages : {
                    // silsila_no: {
                    //     minlength: "Name should be at least 3 characters"
                    // },
                    electoral_id: {
                        required: "Please first add Electoral information then fill voter information"
                    },
                    first_name: {
                        minlength: "First Name should be at least 3 characters"
                    },
                    fname: {
                        minlength: "Father's Name should be at least 3 characters"
                    },
                    address: {
                        minlength: "Address should be at least three characters"
                    },
                    age: {
                        required: "Please enter your age",
                        number: "Please enter your age as a numerical value",
                        min: "You must be at least 18 years old"
                    },
                    cnic: {
                        pattern: 'The CNIC Number string format is invalid'
                    },
                },
                submitHandler: function(form) {
                    $("#next-btn-loader").show();
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            console.log(response);
                            $(".cnic-exist").hide()
                            $(".electoral-msg").empty();
                            $("#voter-msg").empty();
                            $("#next-btn-loader").hide();
                           if(response.code ==1){
                               $("#silsila_no").focus();
                                $("#voter-msg").append("<p class='m-0' style='color: #63b190'>Voter Information added Successfully</p>");
                               $('#basic-form')[0].reset();
                               $('#you-earned').text(response.count_record);
                               $('#last_silsila').text('This is your last added CNIC  No# '+response.last_cnic_no);
                           }
                           else if(response.code ==2){$(".cnic-exist").show()}
                           else {$("#voter-msg").append("<p class='m-0' style='color: red'>Error in voter information adding</p>");}
                        }
                    });
                }
            });
        });

        $(document).ready(function() {

        $('#male_voters').keypress(function(e) {
            var cell_number = $('#male_voters').val();
            var valid_value = false;
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $('#male_voters').attr('valid_val',false);
                return false;
            }else{
                if (cell_number.length == 4) {
                    $('#male_voters').attr('valid_val',false);
                }else if (cell_number.length > 4 && cell_number.length < 11) {
                    $('#male_voters').attr('valid_val',false);}
                else if (cell_number.length >= 11) {
                }else{

                }
            }
        });
        $('#female_voters').keypress(function(e) {
            var cell_number = $('#female_voters').val();
            var valid_value = false;
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $('#female_voters').attr('valid_val',false);
                return false;
            }else{
                if (cell_number.length == 4) {
                    $('#female_voters').attr('valid_val',false);
                }else if (cell_number.length > 4 && cell_number.length < 11) {
                    $('#female_voters').attr('valid_val',false);}
                else if (cell_number.length >= 11) {
                }else{

                }
            }
        });
        $('#silsila_no').keypress(function(e) {
            $('#voter-msg').empty();
            var cell_number = $('#silsila_no').val();
            var valid_value = false;
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $('#silsila_no').attr('valid_val',false);
                return false;
            }else{
                if (cell_number.length >= 4) {
                    $('#silsila_no').attr('valid_val',false);
                    return false;
                }else{

                }
            }
        });
        $('#house_no').keypress(function(e) {
            $('#voter-msg').empty();
            var house_no = $('#house_no').val();
                if (house_no.length >= 5) {
                    $('#house_no').attr('valid_val',false);
                    return false;
                }else{
                }
        });
        $('#age').keypress(function(e) {
            $('#voter-msg').empty();
            var cell_number = $('#age').val();
            var valid_value = false;
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $('#age').attr('valid_val',false);
                return false;
            }else{
                if (cell_number.length == 4) {
                    $('#age').attr('valid_val',false);
                }else if (cell_number.length > 4 && cell_number.length < 11) {
                    $('#age').attr('valid_val',false);}
                else if (cell_number.length >= 11) {
                }else{

                }
            }
        });


            $('#cnic').on('input', function(e){
                $('#voter-msg').empty();
                $("input[name=cnic]").attr("maxlength", "15");
                var key = e.which || this.value.substr(-1).charCodeAt(0);
                var nic = $('#cnic').val();
                if (key >= 48 && key <= 57) {
                    if (nic.length == 5) {
                        $('#cnic').val(nic + "-");
                    } else if (nic.length == 13) {
                        $('#cnic').val(nic + "-");

                    }
                    else if (nic.length == 15) {
                    }
                    else if (nic.length > 15) {
                        e.preventDefault();
                        return false;
                    }
                }else{
                    editedText = nic.slice(0, -1);
                    console.log(editedText);
                    $('#cnic').val(editedText);
                    e.preventDefault();
                    return false;
                }
            });
            $(".form-control").focus(function(){
                $('#voter-msg').empty();
            });
            $(".form-control").focusin(function(){
                $('#voter-msg').empty();
            });
            $('.only-alphabat').keypress(function (e) {
                $('#voter-msg').empty();
                var regex = new RegExp("^[a-zA-Z ]+$");
                var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                $(this).next().hide();
                if (regex.test(str)) {
                    $(this).attr('valid_val',true);
                    return true;
                } else {
                    e.preventDefault();
                 return false;
                }
            });
            $('.copy_paste').on("cut copy paste",function(e) {
                e.preventDefault();
            });

            //            Voter Information added
            // $("#first_name").keyup(function(){
            //     var first_name = $('#first_name').val();
            //
            //
            //     console.log(first_name);
            //
            //
            // });
            // $('#target').on('blur', function() {
            //     alert('Handler for .blur() called.');
            // });
        });
    </script>
@endsection
