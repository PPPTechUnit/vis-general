@extends('layouts.admin')
@section('title', 'Notification ::  Add New')

@section('content')

     <!-- /theme JS files -->
     <link  rel="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
     <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

     <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><a href="{{ url()->previous() }}"><i class="icon-arrow-left52 mr-2"></i> </a><span class="font-weight-semibold">Notifications</span> - Add New </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="{{route('backend.notifications.index')}}" class="breadcrumb-item"> Notification</a>
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
                <form action="{{route('backend.notifications.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <fieldset class="mb-3">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Add Notification</legend>
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="form-group ">
                                    <label class="col-form-label col-lg-12 pb-0">Message</label>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="message" value="{{old('message')}}">
                                        @if ($errors->has('message'))<div class="alert alert-danger">  {{ $errors->first('message') }}
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

@endsection
