@extends('layouts.admin')
@section('title', " :: Add Blockcode User")

@section('content')


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
                <h4><a href="{{ url()->previous() }}"><i class="icon-arrow-left52 mr-2"></i> </a><span class="font-weight-semibold">Blockcode Users</span>  </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="{{route('blockcodes.index')}}" class="breadcrumb-item"> Blockcode Users </a>
                    <span class="breadcrumb-item active">Add </span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title"> <a href="{{ url()->previous() }}" ><i class="icon-reply"></i></a></h5>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{route('blockcodes.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="mb-3">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Add New Blockcode User</legend>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select User</label>
                                    <select name="user" id="user" class="form-control">
                                        <option value="">Select user</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}" @if($user->id == old('user')) selected @endif>{{$user->first_name}} {{$user->last_name}} ({{$user->email}})</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('user'))<div class="alert alert-danger">  {{ $errors->first('user') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Province</label>
                                        <select  name="province" id="province" class="form-control">
                                            <option value="">Select Province</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->id}}" @if($province->id == old('province')) selected @endif>{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('province'))
                                            <div class="alert alert-danger">  {{ $errors->first('province') }}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                        @endif

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Division</label>
                                             <select name="division" id="division" class="form-control">
                                                <option value="">Select Division</option>
                                            </select>
                                            @if ($errors->has('division'))
                                                <div class="alert alert-danger">  {{ $errors->first('division') }}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            @endif

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select District</label>
                                            <select name="district" id="district" class="form-control">
                                                <option value="">Select District</option>
                                            </select>
                                            @if ($errors->has('district'))
                                                <div class="alert alert-danger">  {{ $errors->first('district') }}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            @endif

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Tehsil</label>
                                            <select name="tehsil" id="tehsil" class="form-control">
                                                <option value="">Select Tehsil</option>
                                            </select>
                                            @if ($errors->has('tehsil'))
                                                <div class="alert alert-danger">  {{ $errors->first('tehsil') }}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            @endif

                                </div>
                            </div>
                         <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Block Codes</label>
                                            <select name="block_codes[]" id="block_codes" class="form-control select"  data-fouc multiple="multiple"></select>
                                            @if ($errors->has('block_codes'))
                                                <div class="alert alert-danger">  {{'The block codes field is required.'}}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            @endif

                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
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
                    url:  "{{url('/ajax/get-divisions')}}",
                    type: 'POST',
                    data: {'province_id' : id , '_token' :  token},
                }).done(function(response){
                    $('#division').empty();
                    var  html = '';
                    html += "<option value=''>Select Division</option>";
                    if(response.length > 0) {
                        for(var i =0 ; i < response.length ; i++){
                            html += "<option value=" + response[i].id + ">" + response[i].name + "</option>";
                        }
                        $("#division").append(html);
                    }
                });
            });
            $("#division").change(function() {
                var id = $(this).val();
                var key='non';
                var token= $('#token').val();
                $.ajax({
                    url:  "{{url('/ajax/get-districts')}}",
                    type: 'POST',
                    data: {'division_id' : id , '_token' :  token},
                }).done(function(response){
                    $('#district').empty();
                    var  html = '';
                    html += "<option value=''>Select District</option>";
                    if(response.length > 0) {
                        for(var i =0 ; i < response.length ; i++){
                            html += "<option value=" + response[i].id + ">" + response[i].name + "</option>";
                        }
                        $("#district").append(html);
                    }
                });
            });
            $("#district").change(function() {
                var id = $(this).val();
                var key='non';
                var token= $('#token').val();
                $.ajax({
                    url:  "{{url('/ajax/get-tehsils')}}",
                    type: 'POST',
                    data: {'district_id' : id , '_token' :  token},
                }).done(function(response){
                    $('#tehsil').empty();
                    var  html = '';
                    html += "<option value=''>Select Tehsil</option>";
                    if(response.length > 0) {
                        for(var i =0 ; i < response.length ; i++){
                            html += "<option value=" + response[i].id + ">" + response[i].name + "</option>";
                        }
                        $("#tehsil").append(html);
                    }
                });
            });
        });
         $(document).ready(function(){
            $("#tehsil").change(function() {
                var id = $(this).val();
                var key='non';
                var token= $('#token').val();
                $.ajax({
                    url:  "{{url('/ajax/get-block-codes')}}",
                    type: 'POST',
                    data: {'tehsil_id' : id , '_token' :  token},
                }).done(function(response){
                    $('#block_codes').empty();

                    var  html = '';
                   // html += "<option value=''>Select Provincial Constituencies</option>";
                    if(response.length > 0) {
                        for(var i =0 ; i < response.length ; i++){
                            html += "<option value=" + response[i].name + ">" + response[i].name + "</option>";
                        }
                        $("#block_codes").append(html);
                    }
                });
            });
        });



     </script>

@endsection
