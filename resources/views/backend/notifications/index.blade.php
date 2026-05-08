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
                    <a href="{{route('backend_dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
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
                    <div class="col-lg-6 pull-right">
                        <a href="{{route('backend.notifications.create')}}" class="btn btn-primary pull-right" style="float: right;">Create new</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <div class="content">

                                <div class="card">
                                    @if($notifications->isEmpty())
                                        <div class="empty">
                                            <div class="empty-icon">🔕</div>
                                            <p>No notifications yet.<br>Create your first one above.</p>
                                        </div>
                                    @else
                                        <table class="table  datatable-basic">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Message</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($notifications as $notification)
                                                <tr>
                                                    <td><span class="badge">{{ $notification->id }}</span></td>
                                                    <td>
                                                    <span class="truncate" title="{{ $notification->message }}">{{ $notification->message }}
                                                    </span>
                                                    </td>
                                                    <td class="meta">{{ $notification->created_at->format('d M Y, H:i') }}</td>
                                                    <td>
                                                        <div class="td-actions" style="    display: inline-flex">
                                                            <a class="dropdown-item"
                                                               href="{{ route('notification-verify', $notification['id']) }}"
                                                               onclick="return confirm('Are you sure to send notification?')">
                                                                Verify
                                                            </a>
                                                            <a href="{{ route('backend.notifications.edit', $notification) }}" class="btn btn-ghost btn-sm">✏ Edit</a>

                                                            <form action="{{ route('backend.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">✕ Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        @if($notifications->hasPages())
                                            <div class="pagination-wrap">
                                                <div class="pagination">
                                                    @foreach($notifications->links()->elements[0] ?? [] as $page => $url)
                                                        <a href="{{ $url }}"
                                                           class="page-link {{ $notifications->currentPage() === $page ? 'active' : '' }}">
                                                            {{ $page }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
