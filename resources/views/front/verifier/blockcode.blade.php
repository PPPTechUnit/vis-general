@extends('layouts.front')
@section('content')
@section('title', 'DATA CENTER :: VOTER DATA REGISTRATION')
<div class="main-bg">

            <div class="blockcode-listing-container">
                <div class="blockcode-listing-inner">
                  <div class="row">
                      <div class="col-lg-12"> <h3 class="text-center m-0"><b>VOTER DATA REGISTRATION</b></h3></div>
                      @if (\Session::has('success'))
                          @if(Session::get('success') == 1)
                              <div class="col-lg-12"> <h5 style="color: #447c66;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information Added successfully </h5></div>
                          @elseif(Session::get('success') == 2)
                              <div class="col-lg-12"> <h5 style="color: #447c66;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information updated successfully </h5></div>
                          @elseif(Session::get('success') == 0)
                              <div class="col-lg-12"> <h5 style="color: #ff835d;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information already added successfully </h5></div>
                          @else
                          @endif
                      @endif

                      <div class="col-lg-12 electoral-list">
                          <div class="blockcode-block">
                              <ul class="nav nav-tabs" role="tablist">
                                  <li class="nav-item">
                                      <a class="nav-link active " id="tab-01" data-toggle="tab" href="#home">ELECTORAL INFORMATION</a>
                                  </li>
                                  <li class="nav-item">
                                      <a class="nav-link "  id="tab-02" data-toggle="tab" href="#menu1">VOTER INFORMATION</a>
                                  </li>
                              </ul>
                              <!-- Tab panes -->
                              <div class="tab-content">
                                  <div id="home" class="container tab-pane  active ">
                                      <form action="{{url('/submit-electoral-information')}}" method="post">
                                          @csrf
                                          <input type="hidden" class="form-control step_2" name="electoral" value="{{$data['electoral']}}">
                                        <div class="row">
                                            <div class="col-md-6"><label>Area Name <span style="color: red">*</span></label>
                                                <input type="text" class="form-control step_2" name="area_name" placeholder="Enter Area Name" value="{{$data['eloctoral_area_name']}}">
                                                @if ($errors->has('area_name'))<div class="alert alert-danger">  {{ $errors->first('area_name') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                            <div class="col-md-3"><label>National Assembly * </label>
                                                <select class="form-control step_2" name="national_assembly">
                                                     @foreach($data['na'] as $na)
                                                        <option value="{{$na->id}}">{{$na->name}} </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('national_assembly'))<div class="alert alert-danger">  {{ $errors->first('national_assembly') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif

                                            </div>
                                            <div class="col-md-3"><label>Provincial Assembly  <span style="color: red">*</span></label>
                                                <select class="form-control step_2" name="provincial_assembly">
                                                     @foreach($data['pa'] as $pa)
                                                        <option value="{{$pa->id}}">{{$pa->name}} </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('provincial_assembly'))<div class="alert alert-danger">  {{ $errors->first('provincial_assembly') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif

                                            </div>
                                        </div>
                                          <div class="row">
                                              <div class="col-md-4"><label>Block Code  <span style="color: red">*</span></label><input type="text" class="form-control step_2" name="blockcode" placeholder="Enter Blockcode" value="{{$data['code']}}" ></div>
                                              <div class="col-md-4">
                                                  <label>District  <span style="color: red">*</span> </label>
                                                  <select class="form-control step_2" name="district">
                                                      @foreach($data['districts'] as $district)
                                                          <option value="{{$district->id}}">{{$district->name}} </option>
                                                      @endforeach
                                                  </select>
                                                  @if ($errors->has('district'))<div class="alert alert-danger">  {{ $errors->first('district') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif

                                              </div>
                                                <div class="col-md-4"><label>Taluka / Tehsil  <span style="color: red">*</span></label>
                                                    <select class="form-control step_2" name="tehsil">
                                                         @foreach($data['tehsils'] as $tehsil)
                                                            <option value="{{$tehsil->id}}">{{$tehsil->name}} </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('tehsil'))<div class="alert alert-danger">  {{ $errors->first('tehsil') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif

                                                </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4"><label>City/Village <span style="color: red">*</span></label>
                                                <input type="text" class="form-control step_2" name="city" placeholder="Enter village city" value="{{$data['village_city']}}">
                                                @if ($errors->has('city'))<div class="alert alert-danger">  {{ $errors->first('city') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                            <div class="col-md-4"><label>Patwar Halka  <span style="color: red">*</span></label>
                                                <input type="text" class="form-control step_2" name="patwar_halka" placeholder="Enter Patwar Halka" value="{{$data['circle_name']}}">
                                                @if ($errors->has('patwar_halka'))<div class="alert alert-danger">  {{ $errors->first('patwar_halka') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                            <div class="col-md-4"><label>Union Council </label>
                                                <input type="text" class="form-control step_2" name="union_council" placeholder="Enter Union Council" value="{{$data['union_council']}}">
                                                @if ($errors->has('union_council'))<div class="alert alert-danger">  {{ $errors->first('union_council') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4"><label>Male Voters  <span style="color: red">*</span></label>
                                                <input type="text" class="form-control step_2" name="male_voters" placeholder="Enter Male Voters" value="{{$data['male_voters']}}">
                                                @if ($errors->has('male_voters'))<div class="alert alert-danger">  {{ $errors->first('male_voters') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                            <div class="col-md-4"><label>Female Voters  <span style="color: red">*</span></label>
                                                <input type="text" class="form-control step_2" name="female_voters" placeholder="Enter Female Voters" value="{{$data['female_voters']}}">
                                                @if ($errors->has('female_voters'))<div class="alert alert-danger">  {{ $errors->first('female_voters') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                            <div class="col-md-4"><label>Book Number  <span style="color: red">*</span></label>
                                                <input type="text" class="form-control step_2" name="book_number" placeholder="Enter Book Number" value="{{$data['book_number']}}">
                                                @if ($errors->has('book_number'))<div class="alert alert-danger">  {{ $errors->first('book_number') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                          </div>
                                          <div class="row">

                                              <div class="col-lg-11">
                                                  <ul class="nav skip-tab">
                                                      <li class="">
                                                          <a class="" href="javascript:void(0)" onclick="SkipTab()">skip</a>
                                                      </li>
                                                  </ul></div>
                                                  <div class="col-lg-1">
                                                  <button style="margin-right:0px;" class="next-btn btn btn-success pull-right" type="submit" id="next-btn">SUBMIT</button>
                                              </div>
                                          </div>
                                      </form>
                                  </div>
                                  <div id="menu1" class="container tab-pane fade voter-info-block  "><br>
                                      <form  id="basic-form" action="{{url('add-voter-information')}}" method="post">
                                          <div class="row">
                                              <div class="col-md-12">
                                                  <div id="voter-msg" style="font-weight: 500;"></div>
                                                 </div>
                                          </div>
                                          @csrf
                                          <div class="row">
                                              <div class="col-md-2">
                                                  <label for="silsila_no">Silsila No  <span style="color: red">*</span></label>
                                                  <input type="hidden" class="form-control step_2" name="blockcode" value="{{$data['code']}}" >
                                                  <input type="text" class="form-control step_2" name="silsila_no" placeholder="Enter Silsila No" id="silsila_no" >
                                              </div>
                                              <div class="col-md-2">
                                                  <label for="house_no">House/Gharana No  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control step_2" name="house_no" id="house_no" placeholder="Enter House No" >
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-4">
                                                  <label for="name">Name  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control step_2" name="name" id="name" placeholder="Enter Name" >
                                              </div>
                                              <div class="col-md-4">
                                                  <label for="fname">Father's Name  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control step_2" name="fname" id="fname" placeholder="Enter Father's Name">
                                              </div>
                                              <div class="col-md-4">
                                                  <label for="cnic">CNIC Number (XXXXX-XXXXXXX-X) <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control step_2" name="cnic" id="cnic" placeholder="Enter CNIC Number" >
                                                      <label  class="cnic-exist" style="color: red;">This CNIC Number already exist .</label>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-3">
                                                  <label for="age">Age  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control step_2" name="age" id="age" placeholder="Enter Age">
                                              </div>
                                              <div class="col-md-9">
                                                  <label for="address">Address  <span style="color: red">*</span></label>
                                                  <input type="text" class="form-control step_2" name="address" id="address" placeholder="Enter Address">
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-lg-12">
                                                  <button class="next-btn btn btn-success pull-right" type="submit" id="next-btn">ADD</button>
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

        $("#next-btn").on('click', function(){
            window.location = "{{url('/user/blockcode/213423423')}}";
        });

       function SkipTab(){
           $("#tab-01").removeClass('active show');
           $("#tab-02").addClass('active show');

           $("#home").removeClass('active show');
           $("#menu1").addClass('active show');
       }
        $(document).ready(function() {


            $("#basic-form").validate({
                rules: {
                    silsila_no : {
                        required: true,
                        number: true,
                        minlength: 3
                    },
                    house_no : {
                        required: true,
                        number: true,
                        minlength: 3
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
                        min: 18
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
                    name: {
                        minlength: "Name should be at least 3 characters"
                    },
                    fname: {
                        minlength: "Father's Name should be at least 3 characters"
                    },
                    address: {
                        minlength: "Address should be at least 3 characters"
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
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            $(".cnic-exist").hide()
                            $("#voter-msg").empty();
                           if(response ==1){
                                $("#voter-msg").append("<p class='m-0' style='color: #63b190'>Voter Information added Successfully</p>");
                               $('#basic-form')[0].reset();
                           }
                           else if(response ==2){$(".cnic-exist").show()}
                           else {$("#voter-msg").append("<p class='m-0' style='color: red'>Error in voter information adding</p>");}
                        }
                    });
                }
            });
        });
    </script>
@endsection
