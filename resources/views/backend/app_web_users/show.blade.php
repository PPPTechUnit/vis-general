@extends('layouts.admin')
@section('title', 'Stage Hub Elite | Safe DHA listing')

@section('content')

    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Safe DHA</span> - Listing</h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="{{url('backend/safe-dha')}}" class="breadcrumb-item">Safe DHA </a>
                    <span class="breadcrumb-item active"> Safe DHA Detail</span>
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
            <div class="card-header header-elements-inline">
                <h5 class="card-title">DHA Safe Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="nav-item"><a href="#basic-justified-tab1" class="nav-link active" data-toggle="tab">Personal Information</a></li>
                                    <li class="nav-item"><a href="#basic-justified-tab2" class="nav-link" data-toggle="tab">Address</a></li>
                                    <li class="nav-item"><a href="#basic-justified-tab3" class="nav-link" data-toggle="tab">Association</a></li>

                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="basic-justified-tab1">
                                        <div class="table-responsive">
                                            <table class="table">

                                                <tbody>
                                                <tr><th>Name</th> <td>{{$personal_info['name']}}</td></tr>
                                                <tr><th>S/H/W/D</th> <td>{{$personal_info['son_wife_daughter_name']}}</td></tr>
                                                <tr><th>Email Address</th> <td>{{$personal_info['email']}}</td></tr>
                                                <tr><th>Gender</th> <td>@if($personal_info['gender'] ==1) Male @elseif($personal_info['gender'] ==2) Female @else @endif</td></tr>
                                                <tr><th>Contact Number</th> <td>{{$personal_info['contact_no']}}</td></tr>
                                                <tr><th>CNIC</th> <td>{{$personal_info['cnic']}}</td></tr>
                                                <tr><th>Date Of Birth</th> <td>{{$personal_info['date_of_birth']}}</td></tr>
                                                <tr><th>Family Members</th> <td>{{$personal_info['family_members']}}</td></tr>
                                                <tr><th>Depend Family Member</th> <td>{{$personal_info['depend_family_members']}}</td></tr>
                                                <tr><th>Nationality</th> <td>{{$personal_info['nationality']['title']}}</td></tr>
                                                <tr><th>Religion</th> <td>{{$personal_info['religion']['title']}}</td></tr>
                                                <tr><th>Created Date</th> <td>   @php
                                                            $date = explode(" ",$personal_info['created_at']);
                                                            $date_1 = explode("-",$date[0]);
                                                            $dt = DateTime::createFromFormat('!m', $date_1[1]);
                                                            $month =  $dt->format('F');
                                                          $time = explode(":",$date[1]);

                                                        @endphp
                                                        {{$time[0]}}:{{$time[1]}},  {{$month}} {{$date_1[2]}}, {{$date_1[0]}} </td></tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="basic-justified-tab2">
                                        <div class="table-responsive">
                                            <table class="table">

                                                <tbody>
                                                <tr><th>Residence Category</th> <td>{{$address['residence_category']['title']}}</td></tr>
                                                <tr><th>Flat / house / Shop No#</th> <td>{{$address['flat_house_shop']}}</td></tr>
                                                <tr><th>Building Name / Block</th> <td>{{$address['building_block']}}</td></tr>
                                                <tr><th>Street</th> <td>{{$address['street']}}</td></tr>
                                                <tr><th>Phase</th> <td>{{$address['phase']}}</td></tr>
                                                <tr><th>Landmark</th> <td>{{$address['landmark']}}</td></tr>
                                                <tr><th>City</th> <td>{{$address['city']}}</td></tr>
                                                <tr><th>Province</th> <td>{{$address['province']['title']}}</td></tr>
                                                <tr><th>Country</th> <td>{{$address['country']['title']}}</td></tr>

                                                </tbody>
                                            </table>
                                        </div>  </div>

                                    <div class="tab-pane fade" id="basic-justified-tab3">
                                        <div class="table-responsive">
                                            <table class="table">
                                            <thead>
                                            <tr>
                                                <td class="text-center">Association Name</td>
                                            </tr></thead>
                                                <tbody>
                                                @foreach($personal_info['association'] as $association)
                                                 <tr> <td class="text-center">{{$association['name']}}</td></tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
