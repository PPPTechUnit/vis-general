@extends('layouts.admin')
@section('title', 'Polling Stations Matches :: Listing')

@section('content')

    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">Polling Stations Matches</span> - Listing</h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">Polling Stations Matches</span>
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
                    <div class="col-lg-6">
                        <h5 class="card-title">Polling Stations Matches</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Constituencies</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach($users as $i=>$user)

                                      @php      $constituencies =   \App\UserConstituency::join('pppmissys.election_provincial_constituencies','election_provincial_constituencies.id','user_constituencies.constituency_id')->where('user_constituencies.user_id',$user['id'])->pluck('election_provincial_constituencies.name')->toArray();@endphp
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$user['name']}}</td>
                                        <td>@foreach($constituencies  as $cons) <p>{{{$cons}}}</p>@endforeach</td>
                                        <td>  <a class="btn btn-success" href="{{route('polling-stations-matches.show',$user['id'])}}" class="dropdown-item"> View</a></td>
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
@endsection
