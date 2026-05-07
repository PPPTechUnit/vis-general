@extends('layouts.verifier_layout')
@section('title', 'Import Voterlist')

@section('content')

    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">IMPORT VOTERLIST BLOCKCODE</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12">
                    <form action="{{url('verifier/voterlist-blockcodes-import')}}" method="POST" enctype="multipart/form-data">
                        <!-- CSRF (Laravel) -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="mb-3">
                            <label for="csvFile" class="form-label">Upload Blockcode File</label>
                            <input
                                    class="form-control"
                                    type="file"
                                    id="csvFile"
                                    name="csv_file"
                                    accept=".csv"
                                    required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.getElementById('csvFile').addEventListener('change', function () {
        const file = this.files[0];
        if (file && !file.name.endsWith('.csv')) {
            alert('Only CSV files are allowed!');
            this.value = '';
        }
    });
</script>
@endpush