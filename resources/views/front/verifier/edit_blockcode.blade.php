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
                              <div class="col-lg-12 electoral-msg"> <h5 style="color: #447c66;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information Added successfully </h5></div>
                          @elseif(Session::get('success') == 2)
                              <div class="col-lg-12 electoral-msg"> <h5 style="color: #447c66;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information updated successfully </h5></div>
                          @elseif(Session::get('success') == 0)
                              <div class="col-lg-12 electoral-msg"> <h5 style="color: #ff835d;margin-bottom: 10px !important;" class="text-center m-0">Electoral Information already added successfully </h5></div>
                          @else
                          @endif
                      @endif

                      <div class="col-lg-12 electoral-list">
                          <div class="blockcode-block">
                              <ul class="nav nav-tabs" role="tablist" style="display: block !important;text-align: center;">
                                  <li class="nav-item"><a class="nav-link active " id="tab-01" data-toggle="tab" href="#home">UPDATE ELECTORAL INFORMATION</a></li>
                              </ul>
                              <!-- Tab panes -->
                              <div class="tab-content">
                                  <div id="home" class="container tab-pane  active ">
                                      <form action="{{url('/verifier/user-blockcodes/update-electoral-information')}}" method="post">
                                          @csrf
                                          <input type="hidden" class="form-control step_2" name="electoral" value="{{$data['id']}}">
                                        <div class="row">
                                            <div class="col-md-6"><label>Area Name <span style="color: red">*</span></label>
                                                <input type="text" class="form-control step_2" name="area_name" placeholder="Enter Area Name" value="{{$data['eloctoral_area_name']}}">
                                                @if ($errors->has('area_name'))<div class="alert alert-danger">  {{ $errors->first('area_name') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                            <div class="col-md-3"><label>National Assembly * </label>
                                                <select class="form-control step_2" name="national_assembly">
                                                     @foreach($na as $naa)
                                                        <option value="{{$naa->id}}" @if($naa->id == $data['national_assembly_id']) selected="selected" @endif>{{$naa->name}} </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('national_assembly'))<div class="alert alert-danger">  {{ $errors->first('national_assembly') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif

                                            </div>
                                            <div class="col-md-3"><label>Provincial Assembly  <span style="color: red">*</span></label>
                                                <select class="form-control step_2" name="provincial_assembly">
                                                    @foreach($pa as $naa)
                                                        <option value="{{$naa->id}}" @if($naa->id == $data['provincial_assembly_id']) selected="selected" @endif >{{$naa->name}} </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('provincial_assembly'))<div class="alert alert-danger">  {{ $errors->first('provincial_assembly') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif

                                            </div>
                                        </div>
                                          <div class="row">
                                              <div class="col-md-4"><label>Block Code  <span style="color: red">*</span></label><input type="text" class="form-control step_2" readonly name="blockcode" placeholder="Enter Blockcode" value="{{$data['blockcode']}}" ></div>
                                              <div class="col-md-4">
                                                  <label>District  <span style="color: red">*</span> </label>
                                                  <select class="form-control step_2" name="district">
                                                      @foreach($districts as $naa)
                                                          <option value="{{$naa->id}}" @if($naa->id == $data['district_id']) selected="selected" @endif  >{{$naa->name}} </option>
                                                      @endforeach
                                                  </select>
                                                  @if ($errors->has('district'))<div class="alert alert-danger">  {{ $errors->first('district') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif

                                              </div>
                                                <div class="col-md-4"><label>Taluka / Tehsil  <span style="color: red">*</span></label>
                                                    <select class="form-control step_2" name="tehsil">
                                                            <option value="{{$data['taluka_id']}}" >{{$data['taluka_name']}} </option>
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
                                                <input type="text" class="form-control step_2" name="male_voters" id="male_voters" placeholder="Enter Male Voters" value="{{$data['male_voters']}}">
                                                @if ($errors->has('male_voters'))<div class="alert alert-danger">  {{ $errors->first('male_voters') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                            <div class="col-md-4"><label>Female Voters  <span style="color: red">*</span></label>
                                                <input type="text" class="form-control step_2" name="female_voters" id="female_voters" placeholder="Enter Female Voters" value="{{$data['female_voters']}}">
                                                @if ($errors->has('female_voters'))<div class="alert alert-danger">  {{ $errors->first('female_voters') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                            <div class="col-md-4"><label>Book Number  <span style="color: red">*</span></label>
                                                <input type="text" class="form-control step_2" name="book_number" placeholder="Enter Book Number" value="{{$data['book_number']}}">
                                                @if ($errors->has('book_number'))<div class="alert alert-danger">  {{ $errors->first('book_number') }}<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button></div>@endif
                                            </div>
                                          </div>
                                          <div class="row">

                                                  <div class="col-lg-12">
                                                  <button style="margin-right:0px;" class="next-btn btn btn-success pull-right" type="submit" id="next-btn">SUBMIT</button>
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
        });
    </script>
@endsection
