@extends('layouts.verifier_layout')
@section('title', 'Blockcode Detail')

@section('content')

    <style>
        .badge{
            color: #000 !important;
        }
    </style>
    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">Blockcode Information Detail</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <a href="{{ route('blockcode-information.index') }}" class="btn btn-secondary btn-sm">← Back</a>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Basic Info -->
                            <h5 class="mb-3 text-primary">Basic Information</h5>
                            <div class="row mb-4">
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Block Code</label>
                                    <div class="fw-bold">{{ $record->blockcode ?? '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Committee</label>
                                    <div class="fw-bold">{{ $record->committee ?? '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Village / City</label>
                                    <div class="fw-bold">{{ $record->village_city ?? '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Electoral Area</label>
                                    <div class="fw-bold">{{ $record->eloctoral_area_name ?? '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Circle</label>
                                    <div class="fw-bold">{{ $record->circle_name ?? '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Book Number</label>
                                    <div class="fw-bold">{{ $record->book_number ?? '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Pages</label>
                                    <div class="fw-bold">{{ $record->pages ?? '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Missing Pages</label>
                                    <div class="fw-bold">{{ $record->missing_page ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Male Voters</label>
                                    <div class="fw-bold text-primary" style="font-size:20px;">{{ number_format($record->male_voters) }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Female Voters</label>
                                    <div class="fw-bold text-danger" style="font-size:20px;">{{ number_format($record->female_voters) }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Total Voters</label>
                                    <div class="fw-bold text-success" style="font-size:20px;">{{ number_format($record->total_voters) }}</div>
                                </div>
                            </div>

                            <hr>

                            <!-- Location Info -->
                            <h5 class="mb-3 text-primary">Location Information</h5>
                            <div class="row mb-4">

                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Division</label>
                                    <div class="fw-bold">{{ $record->division_name ?: '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">District</label>
                                    <div class="fw-bold">{{ $record->district_name ?: '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Taluka</label>
                                    <div class="fw-bold">{{ $record->taluka_name ?: '-' }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Provincial Assembly</label>
                                    <div class="fw-bold">{{ $record->provincial_assembly_name ?: '-' }}</div>
                                </div>

                            </div>

                            <hr>



                            <!-- Status Info -->
                            <h5 class="mb-3 text-primary">Status Information</h5>
                            <div class="row mb-4">
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Condition</label>
                                    <div>
                                        @php
                                            $conditionClass = $record->condition == 'GOOD' ? 'success' : 'warning';
                                        @endphp
                                        <span class="badge badge-{{ $conditionClass }}">{{ $record->condition ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Scanned</label>
                                    <div>
                                        <span class="badge badge-{{ $record->scanned == 'DONE' ? 'primary' : 'danger' }}">
                                            {{ $record->scanned ?? '-' }}
                                        </span>
                                        @if($record->scanned == 'ISSUE')
                                            <br> {{ $record->scanned_issue_reason }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Deskewed</label>
                                    <div>
                                        <span class="badge badge-{{ $record->deskewed == 'YES' ? 'primary' : 'danger' }}">
                                            {{ $record->deskewed ?? '-' }}
                                        </span>
                                        @if($record->deskewed == 'ISSUE')
                                            <br>
                                            {{ $record->deskewed_issue_reason }}
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Converted</label>
                                    <div>
                                        @php
                                            $convertedClass = $record->converted == 'YES' ? 'primary' : ($record->converted == 'ISSUE' ? 'warning' : 'danger');
                                        @endphp
                                        <span class="badge badge-{{ $convertedClass }}">{{ $record->converted ?? '-' }}</span>

                                        @if($record->converted == 'ISSUE')
                                            <br>
                                            {{ $record->converted_issue_reason }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">QA Converted File</label>
                                    <div>
                                        @php
                                            $qaClass = $record->qa_converted_file == 'YES' ? 'primary' : ($record->qa_converted_file == 'DISCREPANCY' ? 'warning' : 'danger');
                                        @endphp
                                        <span class="badge badge-{{ $qaClass }}">{{ $record->qa_converted_file ?? '-' }}</span>
                                        @if($record->qa_converted_file == 'DISCREPANCY')
                                            <br>
                                            {{ $record->qa_converted_file_issue_reason }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Uploaded</label>
                                    <div>
                                        <span class="badge badge-{{ $record->uploaded == 'YES' ? 'success' : 'danger' }}">
                                            {{ $record->uploaded ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Meta Info -->
                            <h5 class="mb-3 text-primary">Other Information</h5>
                            <div class="row mb-4">
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Added By</label>
                                    <div class="fw-bold">{{ $record->added_by ?? '-' }}</div>
                                </div>


                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Created At</label>
                                    <div class="fw-bold">{{ $record->created_at ?? '-' }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Updated At</label>
                                    <div class="fw-bold">{{ $record->updated_at ?? '-' }}</div>
                                </div>
                            </div>

                            <hr>

                            <!-- History -->
                            <h5 class="mb-3 text-primary">History Log</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>Old Value</th>
                                        <th>New Value</th>
                                        <th>Done By</th>
                                        <th>Date & Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($histories as $i => $history)
                                        @php
                                            $content = json_decode($history['content'], true);
                                            $old     = $content['old'] ?? [];
                                            $new     = $content['new'] ?? [];
                                        @endphp

                                        @if(empty($old))
                                            {{-- First entry: record created --}}
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td><span class=" badge-success">Created</span></td>
                                                <td>-</td>
                                                <td>
                                                    @foreach($new as $key => $val)
                                                        <div><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $val ?: '-' }}</div>
                                                    @endforeach
                                                </td>
                                                <td>{{ $history['added_by'] ?? '-' }}</td>
                                                <td>{{ $history['created_at'] }}</td>
                                            </tr>
                                        @else
                                            @php
                                                $content = json_decode($history['content'], true);
                                                $old     = $content['old'] ?? [];
                                                $new     = $content['new'] ?? [];

                                            //echo "<pre>";
                                           // print_r($old);
                                           /// print_r($new);
                                            //  echo "</pre>";
                                            @endphp
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td><span class=" badge-success">Updated</span></td>
                                                <td>
                                                    @foreach($old as $key => $val)
                                                        <div><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $val ?: '-' }}</div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($new as $key => $val)
                                                        <div><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $val ?: '-' }}</div>
                                                    @endforeach
                                                </td>
                                                <td>{{ $history['added_by'] ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($history['created_at'])->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s') }}</td>
                                            </tr>



                                        @endif
                                            {{-- Status update entries --}}



                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No history found.</td>
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
    </section>
@endsection

@push('scripts')
<script>
</script>
@endpush