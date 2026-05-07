@extends('layouts.verifier_layout')
@section('title', 'Rescan Blockcode')

@section('content')

    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">RE_SCANED BLOCKCODE</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12">
                    <div class="table-responsive m-b-30">
                        <table id="votersTable" class="table table-borderless table-striped table-earning">
                            <thead>
                            <tr>
                                <th>S.No#</th>
                                <th>Block Code</th>
                                <th>Scanned Done By</th>

                                <th>Imported  By</th>
                                <th>Rescanned Time</th>

                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($voters as $row)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $row->blockcode }} </td>
                                    <td>{{ $row->rescened_done_by }}</td>
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