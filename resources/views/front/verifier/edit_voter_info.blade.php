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


                      <div class="col-lg-12" style="padding-left: 30px;padding-right: 30px;">
{{--                          <embed src="{{'https://techunitbucket.s3-ap-southeast-1.amazonaws.com/production-assets/media/pdf/block_codes/tOIvgSZ3u6ASpkRcO4lWhz0r4v0I10PjPmnUHb6S.pdf'}}" style="width: 100%; height: 350px;"></embed>--}}
                          <embed src="{{$pdf}}" style="width: 100%; height: 350px;"></embed>
{{--                          <embed src="{{$pdf}}" type="application/pdf" style="width: 100%; height: 620px;" />--}}
                      </div>
                      <div class="col-lg-12 electoral-list">
                          <div class="blockcode-block">
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
                              <div class="tab-content">
                                  <div class="col-lg-12"> <h4 class="text-center m-0" style="padding:10px 0px"><b>Update Voter Information </b></h4></div>
                                  <div id="menu1" class="container tab-pane  voter-info-block active ">
                                      <form autocomplete="off" id="basic-form" action="{{url('verifier/user-blockcodes/view-records/update-voter-info')}}" method="post">
                                          <div class="row">
                                              <div class="col-md-12">
                                                  <div id="voter-msg" style="font-weight: 500;"></div>
                                                 </div>

                                          </div>
                                          @csrf
                                          <input type="hidden"  id="vote_id" value="{{$voter['id']}}" name="vote_id">
                                          <input type="hidden"  id="blockcode" value="{{$voter['blockcode']}}" name="blockcode">
                                          <input type="hidden"  id="url" value="{{$url}}" name="url">

                                          <div class="row">
                                              <div class="col-md-1">
                                                  <label for="silsila_no">Silsila <span style="color: red">*</span></label>
                                                   <input type="text" class="form-control  copy_paste step_2" id="silsila_no" value="{{$voter['silsila_no']}}" name="silsila_no" placeholder="No" id="silsila_no" autofocus>
                                              </div>
                                              <div class="col-md-2">
                                                  <label for="house_no">House/Gharana No  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2" name="house_no" value="{{$voter['gharana_no']}}" id="house_no" placeholder="House No" >
                                              </div>
                                              <div class="col-md-9">
                                                  <label for="first_name">Full Name  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2 only-alphabat" value="{{$voter['name']}}" onfocusout="nameONfocusout('name')" name="name" id="name" placeholder="Enter Full Name" >
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-3" style="    padding-top: 13px;">
                                                  <div class="custom-control custom-radio">
                                                      <input type="radio" class="custom-control-input" id="sof_dof" checked value="1" name="father_husband">
                                                      <label class="custom-control-label" for="sof_dof">father's Name</label>
                                                  </div>
                                                  <div class="custom-control custom-radio">
                                                      <input type="radio" class="custom-control-input"  id="wof" value="2" name="father_husband">
                                                      <label class="custom-control-label" for="wof">Husband's Name</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-3">
                                                  <label for="fname">Father's / Husband's Name  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control  copy_paste step_2 only-alphabat"  value="{{$voter['father_husband_name']}}" onfocusout="nameONfocusout('name')" name="fname" id="fname" placeholder="Enter Father's / Husband's Name">
                                              </div>

                                              <div class="col-md-3">
                                                  <label for="cnic">CNIC (XXXXX-XXXXXXX-X) <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control copy_paste step_2"  value="{{$voter['cnic_no']}}" name="cnic" id="cnic" placeholder="Enter CNIC Number" >
                                                  <label  class="cnic-exist" style="color: red;">This CNIC Number already exist .</label>
                                              </div>
                                              <div class="col-md-3">
                                                  <label for="age">Age  <span style="color: red">*</span></label>
                                                  <input type="text" id="age" class="form-control  copy_paste step_2"  value="{{$voter['age']}}" name="age" id="age" placeholder="Age">
                                              </div>

                                          </div>
                                          <div class="row">

                                              <div class="col-md-12">
                                                  <label for="address">Address  <span style="color: red">*</span></label>
                                                  <textarea class="form-control  step_2"   name="address" id="address" placeholder="Enter Address">{{$voter['address']}}</textarea>
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
                                                  <button class="next-btn btn btn-success pull-right" type="submit" style="margin-right: 0px;" id="next-btn">  Update  <img id="next-btn-loader" src="https://icon-library.com/images/loading-icon-transparent-background/loading-icon-transparent-background-12.jpg" style="display: none; height: 20px"></button>
                                                  &nbsp;&nbsp; <button class="next-btn btn btn-success pull-right" type="button" id="back-btn">  <i class="fa fa-arrow-left" aria-hidden="true"></i></button>
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

//            var _val = document.getElementById(id).value;
//            console.log(_val);
//             var voters_names = JSON.parse( localStorage.getItem('voters' ) );
//            var name = _val.charAt(0).toUpperCase() + _val.slice(1);
//            jQuery.each(voters_names, function(index, item) {
//             var isExist =    item.includes(name);
//             var isExist2 =    item.includes(_val);
//              if(isExist == true || isExist2 == true){
//                  document.getElementById(id).value = item[0];
//             }else {
//                 console.log("Not Exist");
//             }
//            });

        }


        $(document).ready(function() {


            $("#basic-form").validate({
                rules: {
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
                    name : {
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

                    name: {
                        minlength: " Name should be at least 3 characters"
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
                                $("#voter-msg").append("<p class='m-0' style='color: #63b190'>Voter Information updated Successfully</p>");
                              // $('#basic-form')[0].reset();
//                               document.referrer

                               setTimeout(function() {
                                   window.history.back();
                               }, 1000);
                               //$('#you-earned').text(response.count_record);
                               //$('#last_silsila').text('This is your last added CNIC  No# '+response.last_cnic_no);
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

            $("#back-btn").click(function(){
                var url = $("#url").val();
                window.location.href=url;

            });
        });
    </script>
@endsection
