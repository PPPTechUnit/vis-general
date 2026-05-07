@extends('layouts.admin')
@section('title', 'Polling Stations Matches :: Listing')

@section('content')
    <link  rel="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('/public/admin/assets/js/export_csv_plugin.js')}}"></script>
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
                        <h5 class="card-title">Polling Stations Matches   </h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('matched-pa-na.index')}}" >
                    <div class="row">
                        <div class="col-lg-6">
                            <label>User </label>
                            <div class="input-group">
                                <select class="form-control" name="user">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{{$user['id']}}}" @if(isset($_GET['user']) && $user['id'] == $_GET['user']) selected @endif> {{$user['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <label>From </label>
                            <div class="input-group">
                                <input type="text" name="from" class="form-control daterange-single" id="datepicker" value="{{isset($_GET['from'])?$_GET['from']: date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label>To </label>
                            <div class="input-group">
                                <input type="text" name="to" class="form-control daterange-single" id="datepicker2" value="{{isset($_GET['to'])?$_GET['to']: date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mt-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success"> search</button>
                                <a href="{{route('matched-pa-na.index')}}" class="btn btn-success">Clear</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12"><h3>Summary</h3></div>
                    <div class="col-lg-12">
                        <a id="export_csv" style="    float: right;    margin: 20px;" href="#" class="btn btn-danger">Export csv</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped registration">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>assigned</th>
                                    <th>matched</th>
                                    <th>not_matched</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $abc =0;
                                    $assigned =0;
                                    $matched =0;
                                    $not_matched =0;
                                    $csv_data =array();
                                @endphp
                                @foreach($results as $i=>$user)
                                    @php
                                        $assigned = ($assigned +   array_sum(array_column($user['constituency'],'assigned')));

                                        $matched = ($matched +   array_sum(array_column($user['constituency'],'matched')));
                                        $not_matched = ($not_matched +   array_sum(array_column($user['constituency'],'not_matched')));

                                    @endphp
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$user['name']}}</td>
                                        <td>{{array_sum(array_column($user['constituency'],'assigned'))}}</td>
                                        <td>{{array_sum(array_column($user['constituency'],'matched'))}}</td>
                                        <td>{{array_sum(array_column($user['constituency'],'not_matched'))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <th> S# </th>
                                        <th> Total </th>
                                        <th>{{$assigned}}</th>
                                        <th>{{$matched}}</th>
                                        <th>{{$not_matched}}</th>

                                    </tr>
                                </tfooter>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12"><h3>Details</h3></div>
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Summary</th>

                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $abc =0;
                                    $assigned =0;
                                    $matched =0;
                                    $not_matched =0;
                                    $csv_data =array();
                                @endphp
                                @foreach($results as $i=>$user)
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>{{$user['name']}}</td>
                                        <td>
                                            <ol>

                                                @foreach($user['constituency'] as $cons)
                                                    @php
                                                        $assigned = ($assigned + $cons['assigned']);
                                                        $matched =($matched + $cons['matched']);
                                                        $not_matched = ($not_matched + $cons['not_matched']);
                                                    @endphp
                                                    <li style="margin: 10px 0px;">{{$cons['constituency']}}  <span style="margin: 5px 10px;;background: dodgerblue; color: #fff; padding: 5px">Assigned: {{$cons['assigned']}}</span>  -- <span style="margin: 5px 10px;;background: green; color: #fff; padding: 5px">Matched: {{$cons['matched']}}</span> -- <span style="margin: 5px 10px;;background: red; color: #fff; padding: 5px">Not matched: {{$cons['not_matched']}}</span></li>
{{--                                                        @php--}}
{{--                                                            $csv_data[$abc]['id'] = $abc+1;--}}
{{--                                                            $csv_data[$abc]['name'] = $user['user']['name'];--}}
{{--                                                            $csv_data[$abc]['constituency'] = $cons['constituency'];--}}
{{--                                                            $csv_data[$abc]['assigned'] = DB::connection('mysql2')->table("polling_station_provincial_constituency")->where('provincial_cons_id',$cons['id'])->count();--}}
{{--                                                            $csv_data[$abc]['matched'] = $cons['matched'];--}}
{{--                                                            $csv_data[$abc]['not_matched'] = DB::connection('mysql2')->table("polling_station_provincial_constituency")->where('provincial_cons_id',$cons['id'])->where('not_match',1)->count();--}}
{{--                                                             $csv_data[$abc]['from'] = $_GET['from'];--}}
{{--                                                             $csv_data[$abc]['to'] = $_GET['to'];--}}
{{--                                                           $abc++;--}}
{{--                                                        @endphp--}}
                                                @endforeach
                                            </ol>
                                        </td>
{{--                                        <td>--}}
{{--                                            <ol>--}}
{{--                                                @foreach($user['constituencies'] as $cons)--}}

{{--                                                    <li style="margin: 10px 0px;">{{$cons['constituency']}} --  <span style="background: dodgerblue; color: #fff; padding: 5px">{{DB::connection('mysql2')->table("polling_station_provincial_constituency")->where('provincial_cons_id',$cons['id'])->count()}}</span>  -- <span style="background: green; color: #fff; padding: 5px">{{$cons['matched']}}</span> -- <span style="background: red; color: #fff; padding: 5px">{{DB::connection('mysql2')->table("polling_station_provincial_constituency")->where('provincial_cons_id',$cons['id'])->where('not_match',1)->count()}}</span></li>--}}

{{--                                                @endforeach--}}
{{--                                            </ol>--}}
{{--                                        </td>--}}

                                    </tr>
                                @endforeach
                                </tbody>
                                <tfooter>
                                <tr>
                                    <th colspan="2" style="text-align: center"> Total# </th>

                                    <th>
                                            <p style="margin: 10px 0px;"> <span style="margin: 5px 10px;;background: dodgerblue; color: #fff; padding: 5px">Assigned: {{$assigned}}</span>  -- <span style="margin: 5px 10px;;background: green; color: #fff; padding: 5px">Matched: {{$matched}}</span> -- <span style="margin: 5px 10px;;background: red; color: #fff; padding: 5px">Not matched: {{$not_matched}}</span></p>
                                    </th>

                                </tr>
                                </tfooter>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $( function() {
            $( "#datepicker,#datepicker2" ).datepicker({
                dateFormat: 'yy-mm-dd'
            });
        } );
    </script>
    <script>
        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>
    <script>
        $('#export_csv').click(function(){
            $.exportCSV(
                [
                    this, //Do not change
                    '.registration', //Selected table, Required, Type: string
                    5, //All columns number,  Required, Type: number
                    ['No', 'Name', 'assigned', 'matched', 'not_matched'],  //Column names, Required, Type: array
                    'export-pa-na', //The download prefix name, Required, Default: 'download', Type: string
                    '-', // A date separator in a file, Default: '-', Type: string
                    '_', // A time separator in a file, Default: '_', Type: string
                    10 // The length of random digits, Default: 6, Type: number
                ]
            );
        })
    </script>
@endsection
