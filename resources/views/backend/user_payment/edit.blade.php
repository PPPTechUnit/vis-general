@extends('layouts.admin')
@section('title', 'Users :: User : Edit')

@section('content')

 {{--    <!-- Theme JS files -->
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
--}}
 <link  rel="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
                                    <label class="col-form-label col-lg-12 pb-0">Profile Picture</label>
                                    <div class="col-lg-12">
                                        <img src="{{config('globalvariables.s3_bucket_url').$user['profile_picture']}}" width="100">
                                        <input type="file" class="form-control" name="profile_picture" value="{{old('profile_picture')}}">
                                        @if ($errors->has('profile_picture'))<div class="alert alert-danger">  {{ $errors->first('profile_picture') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12">First Name</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="first_name" value="{{$user['first_name']}}">
                                        @if ($errors->has('first_name'))<div class="alert alert-danger">  {{ $errors->first('first_name') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12">Last Name</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="last_name" value="{{$user['last_name']}}">
                                        @if ($errors->has('last_name'))<div class="alert alert-danger">  {{ $errors->first('last_name') }}
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
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Gender</label>
                                    <div class="col-lg-12">
                                        <select name="gender" class="form-control">
                                           <option value="1" @if($user['gender'] == 1) selected @endif>Male</option>
                                           <option value="2" @if($user['gender'] == 2) selected @endif>Female</option>
                                        </select>
                                        @if ($errors->has('gender'))<div class="alert alert-danger">  {{ $errors->first('gender') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12 pb-0">Phone Number</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="phone_number"  value="{{$user['phone_number']}}">
                                        @if ($errors->has('phone_number'))<div class="alert alert-danger">  {{ $errors->first('phone_number') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12 pb-0">CNIC Number</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="cnic_number"  value="{{str_replace("-", "", $user['cnic_number'])}}">
                                        @if ($errors->has('cnic_number'))<div class="alert alert-danger">  {{ $errors->first('cnic_number') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12">Enter Email</label>
                                    <div class="col-lg-12">
                                        <input type="email" class="form-control" name="email" value="{{$user['email']}}" disabled>
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
                                        <input type="password" class="form-control" name="password" value="{{old('password')}}">
                                        @if ($errors->has('password'))<div class="alert alert-danger">  {{ $errors->first('password') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        <div class="col-lg-6">
                            <div class="form-group ">
                                <label class="col-form-label col-lg-12 pb-0">Enter Confirm Password</label>
                                <div class="col-lg-12">
                                    <input type="password" class="form-control" name="password_confirmation" value="{{old('password_confirmation')}}">
                                    @if ($errors->has('password_confirmation'))<div class="alert alert-danger">  {{ $errors->first('password_confirmation') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group ">
                                <label class="col-form-label col-lg-12 pb-0">CNIC Front Side</label>
                                <div class="col-lg-12">
                                    <img src="{{config('globalvariables.s3_bucket_url').$user['cnic_front_side']}}" width="100">
                                    <input type="file" class="form-control" name="cnic_front_side" >
                                    @if ($errors->has('cnic_front_side'))<div class="alert alert-danger">  {{ $errors->first('cnic_front_side') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group ">
                                <label class="col-form-label col-lg-12 pb-0">CNIC Back Side</label>
                                <div class="col-lg-12">
                                    <img src="{{config('globalvariables.s3_bucket_url').$user['cnic_back_side']}}" width="100">
                                    <input type="file" class="form-control" name="cnic_back_side" >
                                    @if ($errors->has('cnic_back_side'))<div class="alert alert-danger">  {{ $errors->first('cnic_back_side') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group ">
                                <label class="col-form-label col-lg-12 pb-0">Date Of Birth</label>
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" id="datepicker" name="date_of_birth" value="{{$user['new_db']}}">
                                    @if ($errors->has('date_of_birth'))<div class="alert alert-danger">  {{ $errors->first('date_of_birth') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-form-label col-lg-12 pb-0">Select Status</label>
                                <div class="col-lg-12">
                                    <select name="status" class="form-control">
                                        <option value="1"@if($user['status'] == 1) selected @endif>Active</option>
                                        <option value="0" @if($user['status'] == 0) selected @endif>In Active</option>
                                    </select>
                                    @if ($errors->has('status'))<div class="alert alert-danger">  {{ $errors->first('status') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-form-label col-lg-12 pb-0">Select Verifying</label>
                                <div class="col-lg-12">
                                    <select name="verified" class="form-control">
                                        <option value="1" @if($user['verified'] == 1) selected @endif>Verified</option>
                                        <option value="0"  @if($user['verified'] == 0) selected @endif>Un Verified</option>
                                    </select>
                                    @if ($errors->has('verified'))<div class="alert alert-danger">  {{ $errors->first('verified') }}
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

        $( function() {
            $( "#datepicker" ).datepicker({
                dateFormat: 'dd-mm-yy',
                endDate: '-18y'
            });
        } );
    </script>
    </script>
@endsection
