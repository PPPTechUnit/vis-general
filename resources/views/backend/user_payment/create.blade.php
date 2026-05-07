@extends('layouts.admin')
@section('title', 'Users :: User : Add New')

@section('content')

     <!-- /theme JS files -->
     <link  rel="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
     <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

     <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><a href="{{ url()->previous() }}"><i class="icon-arrow-left52 mr-2"></i> </a><span class="font-weight-semibold">Users</span> - Add New </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="{{route('users.index')}}" class="breadcrumb-item"> Users</a>
                    <span class="breadcrumb-item active">Add New</span>
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
                <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="mb-3">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Add New User</legend>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12 pb-0">Profile Picture</label>
                                    <div class="col-lg-12">
                                        <input type="file" class="form-control" name="profile_picture" value="{{old('profile_picture')}}">
                                        @if ($errors->has('profile_picture'))<div class="alert alert-danger">  {{ $errors->first('profile_picture') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12 pb-0">First Name</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="first_name" value="{{old('first_name')}}">
                                        @if ($errors->has('first_name'))<div class="alert alert-danger">  {{ $errors->first('first_name') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12 pb-0">Last Name</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="last_name" value="{{old('last_name')}}">
                                        @if ($errors->has('last_name'))<div class="alert alert-danger">  {{ $errors->first('last_name') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Role</label>
                                    <div class="col-lg-12">
                                        <select name="role" class="form-control">
                                            @foreach($roles as $role)
                                                <option value="{{$role['id']}}" @if($role['id'] == old('role')) selected @endif>{{$role['name']}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('role'))<div class="alert alert-danger">  {{ $errors->first('role') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Gender</label>
                                    <div class="col-lg-12">
                                        <select name="gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option value="1" @if(old('gender') == 1) selected @endif>Male</option>
                                            <option value="2" @if(old('gender') == 2) selected @endif>Female</option>
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
                                        <input type="text" class="form-control" name="phone_number" value="{{old('phone_number')}}">
                                        @if ($errors->has('phone_number'))<div class="alert alert-danger">  {{ $errors->first('phone_number') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div> <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12 pb-0">CNIC Number</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="cnic_number" value="{{old('cnic_number')}}">
                                        @if ($errors->has('cnic_number'))<div class="alert alert-danger">  {{ $errors->first('cnic_number') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12 pb-0">Enter Email</label>
                                    <div class="col-lg-12">
                                        <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                        @if ($errors->has('email'))<div class="alert alert-danger">  {{ $errors->first('email') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12 pb-0">Enter Password</label>
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
                                        <input type="file" class="form-control" name="cnic_front_side" value="{{old('cnic_front_side')}}">
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
                                        <input type="file" class="form-control" name="cnic_back_side" value="{{old('cnic_back_side')}}">
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
                                        <input type="text" class="form-control" id="datepicker" name="date_of_birth" value="{{old('date_of_birth')}}">
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
                                            <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                            <option value="2" @if(old('status') == 2) selected @endif>In Active</option>
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
                                            <option value="1" @if(old('verified') == 1) selected @endif>Verified</option>
                                            <option value="2" @if(old('verified') == 2) selected @endif>Un Verified</option>
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
                        <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
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
@endsection
