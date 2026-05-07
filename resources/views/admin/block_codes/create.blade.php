@extends('layouts.admin')
@section('title', " :: Add Blockcode User")

@section('content')


    <script src="{{ asset('admin/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
    <script src="{{ asset('admin/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/app.js')}}"></script>



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
                    <a href="{{route('admin.dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
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
                                            <option value="{{$user->id}}" @if($user->id == old('user')) selected @endif>{{$user->name}} ({{$user->email}})</option>
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
                                    <label class="col-form-label col-lg-12 pb-0">Select Block Codes</label>
                                        <select name="block_codes[]" id="block_codes" class="form-control select"  data-fouc multiple="multiple">
                                            @foreach($blockcodes as $blockcode)
                                                <option value="{{$blockcode->blockcode}}">{{$blockcode->blockcode}} </option>
                                            @endforeach
                                        </select>
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
        $(document).ready(function() {
//            $('#block_codes').multiselect({
//                includeSelectAllOption: true,
//                enableFiltering: true,
//                enableCaseInsensitiveFiltering: true,
//                buttonWidth: '100%',
//                maxHeight: 300
//            });
        });
    </script>
@endsection
