@extends('layouts.admin')
@section('title', 'Users :: User : Edit')

@section('content')

     <!-- Theme JS files -->
    <script src="{{ asset('public/admin/global_assets/js/plugins/editors/summernote/summernote.min.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>

    <script src="{{ asset('public/admin/assets/js/app.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/js/demo_pages/editor_summernote.js')}}"></script>
    <!-- /theme JS files -->

     <!-- Theme JS files -->
     <script src="{{ asset('public/admin/global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
     <script src="{{ asset('public/admin/global_assets/js/plugins/pickers/daterangepicker.js')}}"></script>
     <script src="{{ asset('public/admin/global_assets/js/plugins/pickers/anytime.min.js')}}"></script>
     <script src="{{ asset('public/admin/global_assets/js/plugins/pickers/pickadate/picker.js')}}"></script>
     <script src="{{ asset('public/admin/global_assets/js/plugins/pickers/pickadate/picker.date.js')}}"></script>
     <script src="{{ asset('public/admin/global_assets/js/plugins/pickers/pickadate/picker.time.js')}}"></script>
     <script src="{{ asset('public/admin/global_assets/js/plugins/pickers/pickadate/legacy.js')}}"></script>
     <script src="{{ asset('public/admin/global_assets/js/plugins/notifications/jgrowl.min.js')}}"></script>

     <script src="{{ asset('public/admin/global_assets/js/demo_pages/picker_date.js')}}"></script>
     <!-- /theme JS files -->

     <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">User</span> - Edit </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="{{route('users.index')}}" class="breadcrumb-item"> Users</a>
                    <span class="breadcrumb-item active">Edit</span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <!-- /page header -->


    <!-- Content area -->
    <div class="content">

        <!-- Default alerts -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('users.update', $user['id'])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="mb-3">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Edit User</legend>
                        @method('PATCH')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12">Enter Name</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="name" value="{{$user['name']}}">
                                        @if ($errors->has('name'))<div class="alert alert-danger">  {{ $errors->first('name') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">

                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12">Select Role</label>
                                    <div class="col-lg-12">
                                        <select  name="role" class="form-control" disabled>
                                            @foreach($roles as $role)
                                                <option value="{{$role['id']}}" @if($role['id'] == $user['role_id']) selected @endif>{{$role['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12">Enter Email</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="email" value="{{$user['email']}}" disabled>
                                        @if ($errors->has('email'))<div class="alert alert-danger">  {{ $errors->first('email') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12">Enter Password</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="password">
                                        @if ($errors->has('password'))<div class="alert alert-danger">  {{ $errors->first('password') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                    </fieldset>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Update <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        $('#summernote').summernote('code')
    </script>
@endsection
