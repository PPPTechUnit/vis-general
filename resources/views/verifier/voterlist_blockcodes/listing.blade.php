@extends('layouts.verifier_layout')
@section('title', 'Voterlist Blockcode')

@section('content')

    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">LIST VOTERLIST BLOCKCODE</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 mb-5" >
                    <a class="btn btn-primary" href="{{ route('voterlist-blockcodes.create') }}" style="float: right">Add New Blockcode</a>
                </div>
                <div class="col-lg-12">
                    @if(session('success'))
                        <div class="alert alert-success">  {{session('success')}}
                        </div>
                    @endif @if(session('error'))
                        <div class="alert alert-danger">  {{session('error')}}
                        </div>
                    @endif
                </div>
                <div class="col-lg-12">
                    <div class="table-responsive m-b-30">
                        <table id="votersTable" class="table table-borderless table-striped table-earning">
                            <thead>
                            <tr>
                                <th>S.No#</th>
                                <th>Block Code</th>
                                <th>Imported By</th>
                                <th>Imported Time</th>

                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @if(sizeof($voters)>0)
                                @foreach($voters as $row)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $row->blockcode }} </td>
                                        <td>{{ $row->created_by }}</td>
                                        <td>{{ $row->created_at }}</td>
                                        <td> <a href="{{route('voterlist-blockcodes.show',$row->id)}}" class="btn btn-success"> PREVIEW</a> </td>

                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')

@endpush