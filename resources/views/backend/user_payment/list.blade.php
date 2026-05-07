@extends('layouts.admin')
@section('title', 'users :: Payment')

@section('content')
    <script src="{{ asset('public/admin/global_assets/plugins/pickers/anytime.min.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/plugins/ui/moment/moment.min.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/plugins/pickers/daterangepicker.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/plugins/pickers/anytime.min.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/plugins/pickers/pickadate/picker.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/plugins/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/plugins/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{ asset('public/admin/global_assets/plugins/pickers/pickadate/legacy.js')}}"></script>

    <script src="{{ asset('public/admin/assets/js/picker_date.js')}}"></script>

    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">User's Payment</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">Users Payment</span>
                </div>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>
    </div>
    <div class="content">
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
                        <form action="{{url('backend/blockcodes-user-payment/submit')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <fieldset class="mb-3">
                                <legend class="text-uppercase font-size-sm font-weight-bold">Add New User</legend>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group ">
                                            <h6 style="margin-bottom: 0px;float: right;margin-right: 10px;">Your remaining amount Rs# {{$amount}} PKR</h6>
                                            <label class="col-form-label col-lg-12 pb-0">Add Amount</label>
                                            <div class="col-lg-12">
                                                <input type="hidden" class="form-control"  name="user_id" value="{{$user_id}}">
                                                <input type="hidden" class="form-control" id="remaining_amount" name="remaining_amount" value="{{$amount}}">
                                                <input type="text" class="form-control" id="amount" name="amount" max="{{$amount}}"  placeholder="Enter Amount" value="{{old('amount')}}">
                                                @if ($errors->has('amount'))<div class="alert alert-danger">  {{ $errors->first('amount') }}
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group ">
                                            <label class="col-form-label col-lg-12 pb-0">Feedback</label>
                                            <div class="col-lg-12">
                                                <textarea class="form-control" name="feedback" value="{{old('feedback')}}"></textarea>
                                                @if ($errors->has('feedback'))<div class="alert alert-danger">  {{ $errors->first('feedback') }}
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">ADD <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
<!--                <form action="{{$_SERVER['REQUEST_URI']}}" method="get" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Date Range:</label>
                                <div class="input-group">
                                    <span class="input-group-prepend"><span class="input-group-text"><i class="icon-calendar22"></i></span></span>
                                    <input type="text" name="date_range" class="form-control daterange-basic" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button style="    margin-top: 25px;" class="btn btn-success" type="submit">SEARCH FILTER</button>
                            </div>
                        </div>
                    </div>
                </form>-->


           <div class="row">
               <div class="col-lg-12">
                   <a class="btn btn-success" href="{{url('backend/blockcodes-user-payment/pdf-print/'.$user_id)}}">Export PDF</a>
               </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                        <h5 class="card-title">Payment History</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table  datatable-basic">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Paid Amount</th>
                                    <th>Remaining Amount</th>
                                    <th>Feedback</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($user_payment as $i=> $payment)
                                        <tr>
                                            <th>{{++$i}}</th>
                                            <th>{{$payment->paid_amount}}</th>
                                            <th>{{$payment->remaining_amount}}</th>
                                            <th>{{$payment->feed_back}}</th>
                                            <th>{{$payment->date}}</th>
                                            <th>{{($payment->status ==1)?"Paid":"Un Paid"}}</th>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
           // $("input").keydown(function(){$("input").css("background-color", "yellow");});
            $("#amount").keyup(function(){
                var remaining_amount = $("#remaining_amount").val();
                var amount = $("#amount").val();
                if(parseInt(amount) >parseInt(remaining_amount)){
                    $("#amount").val(parseInt(remaining_amount));
                }else{}
                //  $("#amount").css("background-color", "pink");
            });
        });
    </script>
@endsection
