@extends('layouts.admin')
@section('title', 'Polling Stations Matches | listing')

@section('content')

    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Polling Stations Matches</span> - Listing</h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="{{url('backend/polling-stations-matches')}}" class="breadcrumb-item">Polling Stations Matches </a>
                    <span class="breadcrumb-item active"> Polling Stations Matches Detail</span>
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
                <h5 class="card-title"> <a href="{{ url()->previous() }}" ><i class="icon-reply"></i></a></h5>
            </div>
        </div>
        <!-- Default alerts -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">DHA Safe Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                @foreach($response as $respon)
                                    <h4> Constituency Name {{$respon['name']}}</h4>
                                    <h4> Matched Polling Stations </h4>
                                    <div class="table-responsive" style="overflow-y: scroll;height: 500px;">

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                     <th>S#</th>
                                                     <th> Polling Station Name</th>
                                                     <th>Matched By</th>
                                                     <th>Verified By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php $a=1; @endphp
                                                @foreach($respon['matched_polling'] as $iii=> $polling)
                                                    <tr>
                                                        <td>{{$a}}</td>
                                                        <td>{{$polling->name}}</td>
                                                        <td>{{$polling->matched_by}}</td>
                                                        <td>{{$polling->verified_by}}</td>
                                                    </tr>
                                                    @php $a++; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                <br>
                                <br>
                                    <h4> Others Polling Stations </h4>
                                 <div class="table-responsive" style="overflow-y: scroll;height: 500px;">
                                        <table class="table scrolling ">
                                            <thead>
                                                <tr>
                                                     <th>S#</th>
                                                     <th>Polling Station Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php $ab=1; @endphp
                                            @if(sizeof($respon['not_matched_polling'])>0)
                                                @foreach($respon['not_matched_polling'] as $kk=> $polling)
                                                    <tr>
                                                        <td>{{$ab}}</td>
                                                        <td>{{$polling->name}}</td>
                                                    </tr>
                                                    @php $ab++; @endphp
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td>NOT FOUND</td>

                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
