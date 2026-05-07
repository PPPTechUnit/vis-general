@extends('layouts.admin')
@section('title', 'users :: users Listing')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<style>
    #datatable-basic_filter{
        float: right;
    }
    #datatable-basic_length{
        float: left;
    }
</style>
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
                    <div class="col-lg-6">
                        <h5 class="card-title">users listing</h5>
                    </div>

                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="datatable-basic">
                                <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Total Updated</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($dailyUpdates as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row->update_date }}</td>
                                        <td>{{ $users[$row->updated_by]->name ?? 'Unknown' }}</td>
                                        <td>{{ $row->start_time }}</td>
                                        <td>{{ $row->end_time }}</td>
                                        <td>{{ $row->total_updated }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('jsfiles')
    <script>
        $(document).ready(function() {
            $('#datatable-basic').DataTable();
        });
    </script>
@endsection
