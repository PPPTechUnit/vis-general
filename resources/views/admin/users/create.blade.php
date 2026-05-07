@extends('layouts.admin')
@section('title', 'users :: users Listing')

@section('content')
    <script src="{{ asset('public/assets/js/datatables.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatables_basic.js')}}"></script>

    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">Users</span> - Listing</h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('admin.dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">users listing</span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <div class="content">
        <!-- Default alerts -->
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

                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <fieldset class="mb-3">
                                <legend class="text-uppercase font-size-sm font-weight-bold">Add New User</legend>
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="form-group ">
                                            <label class="col-form-label col-lg-12 pb-0">Name</label>
                                            <div class="col-lg-12">
                                                <input type="text" class="form-control" name="name" value="{{old('name')}}">
                                                @if ($errors->has('name'))<div class="alert alert-danger">  {{ $errors->first('name') }}
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
                                                    <option value="admin" @if(old('role') == 'admin') selected @endif>Admin</option>
                                                    <option value="user" @if(old('role') == 'user') selected @endif>User</option>
                                                </select>
                                                @if ($errors->has('role'))<div class="alert alert-danger">  {{ $errors->first('role') }}
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
                                </div>
                            </fieldset>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
