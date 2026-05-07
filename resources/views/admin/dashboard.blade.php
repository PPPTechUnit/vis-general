@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <script src="{{ asset('public/assets/js/datatables.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatables_basic.js')}}"></script>

    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">Dashboard</span> </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('admin.dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">Dashboard</span>
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
                    <div class="col-lg-12">
                        <h5 class="card-title">Dashboard</h5>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
