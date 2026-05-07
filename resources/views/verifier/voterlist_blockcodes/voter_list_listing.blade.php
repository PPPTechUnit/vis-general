@extends('layouts.verifier_layout')
@section('title', 'Voterlist')

@section('content')

    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">LIST VOTERLIST BLOCKCODE</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">

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
                                <th>Blockcode Male</th>
                                <th>Blockcode Female</th>
                                <th>Blockcode Total</th>
                                <th>Voter Male</th>
                                <th>Voter Female</th>
                                <th>Total</th>
                                <th>created_by</th>
                                <th>created_at</th>


                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($voters as $row)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $row->blockcode }} </td>
                                    <td>{{ $blockcodes[ $row->blockcode]->male_voters ?? 0; }}</td>
                                    <td>{{ $blockcodes[ $row->blockcode]->female_voters ?? 0; }}</td>
                                    <td>{{ $blockcodes[ $row->blockcode]->total_voters ?? 0; }}</td>
                                    <td>{{ $row->male_count }}</td>
                                    <td>{{ $row->female_count }}</td>
                                    <td>{{ $row->total_voters }}</td>
                                    <td>{{ $row->created_by }}</td>
                                    <td>{{ $row->created_at }}</td>

                                </tr>
                                @php $i++; @endphp
                            @endforeach
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