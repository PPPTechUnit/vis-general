@extends('layouts.admin')
@section('title', " :: Edit electoral")

@section('content')
<style>
    label{
        margin-bottom: 0px;
        margin-top: 15px;
    }
</style>

    <script src="{{ asset('public/admin/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{ asset('public/admin/assets/js/app.js')}}"></script>

    <script src="https://demo.interface.club/limitless/demo/Template/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
    <script src="https://demo.interface.club/limitless/demo/Template/global_assets/js/plugins/forms/selects/select2.min.js"></script>
    <script src="https://demo.interface.club/limitless/demo/Template/layout_1/LTR/default/full/assets/js/app.js"></script>
    <script src="https://demo.interface.club/limitless/demo/Template/global_assets/js/demo_pages/form_select2.js"></script>


     <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><a href="{{ url()->previous() }}"><i class="icon-arrow-left52 mr-2"></i> </a><span class="font-weight-semibold">Edit Electoral Information</span>  </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="{{route('blockcodes.index')}}" class="breadcrumb-item"> Edit electoral </a>
                    <span class="breadcrumb-item active">Edit </span>
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
                <h5 class="card-title"> <a href="{{ url('backend/blockcodes') }}" ><i class="icon-reply"></i></a></h5>
            </div>
        </div>
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
                <form action="{{url('backend/blockcodes/update-electoral-information')}}" method="post" enctype="multipart/form-data">
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
                    <div class="row" style="margin-top:15px !important;" >

                        <div class="col-lg-12">
                            <button class="next-btn btn btn-success" type="submit" id="next-btn">SUBMIT</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('jsfiles')
     <script>
        $(document).ready(function(){
            $("#province").change(function() {
                var id = $(this).val();
                var key='non';
                var token= $('#token').val();
                $.ajax({
                    url:  "{{url('/ajax/get-provinces')}}",
                    type: 'POST',
                    data: {'province_id' : id , '_token' :  token},
                }).done(function(response){
                    $('#provincial_constituencies').empty();

                    var  html = '';
                   // html += "<option value=''>Select Provincial Constituencies</option>";
                    if(response.length > 0) {
                        for(var i =0 ; i < response.length ; i++){
                            html += "<option value=" + response[i].id + ">" + response[i].name + "</option>";
                        }
                        $("#provincial_constituencies").append(html);
                    }
                });
            });
            //$('.multiselect').multiselect();
        });



     </script>

@endsection
