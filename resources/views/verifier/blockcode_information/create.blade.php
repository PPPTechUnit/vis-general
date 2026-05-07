@extends('layouts.verifier_layout')
@section('title', 'Blockcode Information Create')

@section('content')
    <!-- MAIN CONTENT-->
    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">Blockcode Information</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    {{-- Custom error message --}}
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-lg-12">
                    @if(session('success'))
                        <div class="alert alert-success">  {{session('success')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        </div>
                    @endif @if(session('error'))
                        <div class="alert alert-danger">  {{session('error')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        </div>
                    @endif
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <form id="blockcodeForm" action="{{ route('blockcode-information.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <!-- Block Code -->
                                    <div class="col-md-6 mb-3">
                                        <label for="blockcode" class="form-label">Block Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('blockcode') is-invalid @enderror" id="blockcode" name="blockcode" value="{{ old('blockcode') }}" required>
                                        @error('blockcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Electoral Area -->
                                    <div class="col-md-6 mb-3">
                                        <label for="eloctoral_area_name" class="form-label">Electoral Area Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('eloctoral_area_name') is-invalid @enderror" id="eloctoral_area_name" name="eloctoral_area_name" value="{{ old('eloctoral_area_name') }}" required>
                                        @error('eloctoral_area_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <!-- Village City -->
                                    <div class="col-md-6 mb-3">
                                        <label for="village_city" class="form-label">Village City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('village_city') is-invalid @enderror" id="village_city" name="village_city" value="{{ old('village_city') }}" required>
                                        @error('village_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Circle -->
                                    <div class="col-md-6 mb-3">
                                        <label for="circle_name" class="form-label">Circle <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('circle_name') is-invalid @enderror" id="circle_name" name="circle_name" value="{{ old('circle_name') }}" required>
                                        @error('circle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Committee -->
                                    <div class="col-md-6 mb-3">
                                        <label for="committee" class="form-label">Committee</label>
                                        <input type="text" class="form-control @error('committee') is-invalid @enderror" id="committee" name="committee" value="{{ old('committee') }}" >
                                        @error('committee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- National Assembly -->
                                    <div class="col-md-6 mb-3">
                                        <label for="provincial_constituency_name" class="form-label">Select Constituency <span class="text-danger">*</span></label>
                                        <select class="form-control @error('provincial_constituency_name') is-invalid @enderror" id="provincial_constituency_name" name="provincial_constituency_name" required>
                                            <option value="">Select</option>
                                            @foreach($nas as $na)
                                                <option value="{{ $na->id }}" {{ old('provincial_constituency_name') == $na->id ? 'selected' : '' }}>
                                                    {{ $na->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('provincial_constituency_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Division -->
                                    <div class="col-md-6 mb-3">
                                        <label for="division_name" class="form-label">Division <span class="text-danger">*</span>

                                        </label>
                                        <select class="form-control @error('division_name') is-invalid @enderror" id="division_name" name="division_name" required>
                                            <option value="">Select Division</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}" {{ old('division_name') == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('division_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- District -->
                                    <div class="col-md-6 mb-3">
                                        <label for="district_name" class="form-label">District <span class="text-danger">*</span>
                                            <span> <button type="button" class="btn btn-link btn-sm" onclick="addDistrict()">➕ Add District</button></span>
                                        </label>
                                        <select class="form-control @error('district_name') is-invalid @enderror" id="district_name" name="district_name" required>
                                            <option value="">Select District</option>
                                        </select>
                                        @error('district_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Tehsils / Taluka -->
                                    <div class="col-md-6 mb-3">
                                        <label for="taluka_name" class="form-label">Tehsils <span class="text-danger">*</span>
                                            <span> <button type="button" class="btn btn-link btn-sm" onclick="addTehsil()">➕ Add Tehsil</button></span>
                                        </label>
                                        <select class="form-control @error('taluka_name') is-invalid @enderror"
                                                id="taluka_name" name="taluka_name" required>
                                            <option value="">Select Tehsil</option>
                                        </select>
                                        @error('taluka_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <!-- Book Number -->
                                    <div class="col-md-6 mb-3">
                                        <label for="book_number" class="form-label">Book No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('book_number') is-invalid @enderror" id="book_number" name="book_number" value="{{ old('book_number') }}" >
                                        @error('book_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Pages -->
                                    <div class="col-md-4 mb-3">
                                        <label for="pages" class="form-label">Pages <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('pages') is-invalid @enderror" id="pages" name="pages" value="{{ old('pages') }}" >
                                        @error('pages')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Missing Pages -->
                                    <div class="col-md-4 mb-3">
                                        <label for="missing_page" class="form-label">Missing Pages</label>
                                        <input type="number" class="form-control @error('missing_page') is-invalid @enderror" id="missing_page" name="missing_page" value="{{ old('missing_page') }}">
                                        @error('missing_page')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="condition" class="form-label">Condition <span class="text-danger">*</span>

                                        </label>
                                        <select class="form-control @error('condition') is-invalid @enderror" id="condition" name="condition" >
                                            <option value="">Select</option>
                                            <option value="GOOD" {{ old('condition') == 'GOOD' ? 'selected' : '' }}>GOOD</option>
                                            <option value="AVERAGE" {{ old('condition') == 'AVERAGE' ? 'selected' : '' }}>AVERAGE</option>
                                            <option value="BAD" {{ old('condition') == 'BAD' ? 'selected' : '' }}>BAD</option>

                                        </select>
                                        @error('condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Male Voters -->
                                    <div class="col-md-4 mb-3">
                                        <label for="male_voters" class="form-label">Male Voters <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('male_voters') is-invalid @enderror" id="male_voters" name="male_voters" value="{{ old('male_voters') }}"  >
                                        @error('male_voters')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Female Voters -->
                                    <div class="col-md-4 mb-3">
                                        <label for="female_voters" class="form-label">Female Voters <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('female_voters') is-invalid @enderror" id="female_voters" name="female_voters" value="{{ old('female_voters') }}" >
                                        @error('female_voters')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Total Voters (Auto-calculated) -->
                                    <div class="col-md-4 mb-3">
                                        <label for="total_voters" class="form-label">Total Voters</label>
                                        <input type="number" class="form-control" id="total_voters" name="total_voters" readonly>
                                        <small class="text-muted">Auto-calculated from Male + Female</small>
                                    </div>




                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Save Blockcode</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Make variables globally available
    const districtSel = document.getElementById('district_name');
    const talukaSel = document.getElementById('taluka_name');

    // ── Helper: fetch districts for a given division (GLOBAL FUNCTION) ──
    window.loadDistricts = function(divisionId, selectedDistrictId, callback) {
        const districtSel = document.getElementById('district_name');
        const talukaSel = document.getElementById('taluka_name');

        districtSel.innerHTML = '<option value="">Loading...</option>';
        districtSel.disabled = true;
        talukaSel.innerHTML = '<option value="">Select Tehsil</option>';
        talukaSel.disabled = true;

        fetch(`/ajax/get-districts/${divisionId}`)
            .then(res => {
            if (!res.ok) throw new Error('Network error');
        return res.json();
    })
        .then(data => {
            districtSel.innerHTML = '<option value="">Select District</option>';
        if (data && data.length > 0) {
            data.forEach(d => {
                const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.name;
            if (d.id == selectedDistrictId) opt.selected = true;
            districtSel.appendChild(opt);
        });
            districtSel.disabled = false;
        } else {
            districtSel.innerHTML = '<option value="">No districts found</option>';
        }
        if (typeof callback === 'function') callback();
    })
        .catch(() => {
            districtSel.innerHTML = '<option value="">Error loading districts</option>';
    });
    }

    // ── Helper: fetch tehsils for a given district (GLOBAL FUNCTION) ──
    window.loadTalukas = function(districtId, selectedTalukaId) {
        const talukaSel = document.getElementById('taluka_name');
        talukaSel.innerHTML = '<option value="">Loading...</option>';
        talukaSel.disabled = true;

        fetch(`/ajax/get-tehsils/${districtId}`)
            .then(res => {
            if (!res.ok) throw new Error('Network error');
        return res.json();
    })
        .then(data => {
            talukaSel.innerHTML = '<option value="">Select Tehsil</option>';
        if (data && data.length > 0) {
            data.forEach(t => {
                const opt = document.createElement('option');
            opt.value = t.id;
            opt.textContent = t.name;
            if (selectedTalukaId && t.id == selectedTalukaId) opt.selected = true;
            talukaSel.appendChild(opt);
        });
            talukaSel.disabled = false;
        } else {
            talukaSel.innerHTML = '<option value="">No tehsils found</option>';
        }
    })
        .catch(() => {
            talukaSel.innerHTML = '<option value="">Error loading tehsils</option>';
    });
    }

    document.addEventListener('DOMContentLoaded', function () {

        const oldDivision = "{{ old('division_name') }}";
        const oldDistrict = "{{ old('district_name') }}";
        const oldTaluka   = "{{ old('taluka_name') }}";

        const maleInput    = document.getElementById('male_voters');
        const femaleInput  = document.getElementById('female_voters');
        const totalInput   = document.getElementById('total_voters');
        const divisionSel  = document.getElementById('division_name');

        // ── Auto-calculate total voters ──────────────────────────────
        function calculateTotal() {
            const male   = parseInt(maleInput.value)   || 0;
            const female = parseInt(femaleInput.value)  || 0;
            totalInput.value = male + female;
        }
        maleInput.addEventListener('input', calculateTotal);
        femaleInput.addEventListener('input', calculateTotal);
        calculateTotal();

        // ── Division change event ────────────────────────────────────
        divisionSel.addEventListener('change', function () {
            const divisionId = this.value;
            if (divisionId) {
                window.loadDistricts(divisionId, null, null);
            } else {
                const districtSel = document.getElementById('district_name');
                const talukaSel = document.getElementById('taluka_name');
                districtSel.innerHTML = '<option value="">Select District</option>';
                districtSel.disabled = true;
                talukaSel.innerHTML = '<option value="">Select Tehsil</option>';
                talukaSel.disabled = true;
            }
        });

        // ── District change event ────────────────────────────────────
        const districtSelLocal = document.getElementById('district_name');
        districtSelLocal.addEventListener('change', function () {
            const districtId = this.value;
            if (districtId) {
                window.loadTalukas(districtId, null);
            } else {
                const talukaSel = document.getElementById('taluka_name');
                talukaSel.innerHTML = '<option value="">Select Tehsil</option>';
                talukaSel.disabled = true;
            }
        });

        // ── Restore old values after validation failure ──────────────
        if (oldDivision) {
            window.loadDistricts(oldDivision, oldDistrict, function () {
                if (oldDistrict) {
                    window.loadTalukas(oldDistrict, oldTaluka);
                }
            });
        }

    });


    // ── Function to add new District ───────────────────────────────────
    window.addDistrict = function() {
        const divisionSelect = document.getElementById('division_name');
        const selectedDivisionId = divisionSelect.value;
        const selectedDivisionName = divisionSelect.options[divisionSelect.selectedIndex]?.text;

        // Check if division is selected
        if (!selectedDivisionId || selectedDivisionId === '') {
            Swal.fire({
                icon: 'warning',
                title: 'No Division Selected',
                text: 'Please select a division first before adding a district.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
                title: 'Add New District',
                html: `
                <div style="text-align: left;">
                    <div class="mb-3">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Division:</label>
                        <input type="text" class="swal2-input" value="${selectedDivisionName}" readonly style="background-color: #e9ecef; width: 100%; margin: auto;">
                    </div>
                    <div class="mb-3">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">District Name <span style="color: red;">*</span>:</label>
                        <input type="text" id="new-district-name" class="swal2-input" placeholder="Enter district name" style="width: 100%; margin: auto;">
                    </div>
                    <div class="mb-3">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">District Code (Optional):</label>
                        <input type="text" id="new-district-code" class="swal2-input" placeholder="Enter district code" style="width: 100%; margin: auto;">
                    </div>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Save District',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                preConfirm: () => {
                const districtName = document.getElementById('new-district-name').value.trim();

        if (!districtName) {
            Swal.showValidationMessage('Please enter district name');
            return false;
        }

        if (districtName.length < 2) {
            Swal.showValidationMessage('District name must be at least 2 characters long');
            return false;
        }

        const districtCode = document.getElementById('new-district-code').value.trim();

        return {
            district_name: districtName,
            district_code: districtCode,
            division_id: selectedDivisionId
        };
    },
        allowOutsideClick: false
    }).then(async (result) => {
            if (result.isConfirmed) {
            const data = result.value;

            Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while we save the district',
                    allowOutsideClick: false,
                    didOpen: () => {
                    Swal.showLoading();
        }
        });

            try {
                const response = await fetch('/ajax/add-district', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result_data = await response.json();

                if (result_data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'District Added!',
                        text: `District "${data.district_name}" has been added successfully.`,
                        confirmButtonColor: '#28a745'
                    });

                    // Refresh district dropdown and select the newly added district
                    if (typeof window.loadDistricts === 'function') {
                        window.loadDistricts(selectedDivisionId, result_data.district_id);
                    }

                } else {
                    throw new Error(result_data.message || 'Failed to add district');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Add District',
                    text: error.message || 'An error occurred while adding the district. Please try again.',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    });
    }

    // ── Function to add new Tehsil ───────────────────────────────────
    window.addTehsil = function() {
        const districtSelect = document.getElementById('district_name');
        const selectedDistrictId = districtSelect.value;
        const selectedDistrictName = districtSelect.options[districtSelect.selectedIndex]?.text;

        // Check if district is selected
        if (!selectedDistrictId || selectedDistrictId === '') {
            Swal.fire({
                icon: 'warning',
                title: 'No District Selected',
                text: 'Please select a district first before adding a tehsil.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
                title: 'Add New Tehsil',
                html: `
                <div style="text-align: left;">
                    <div class="mb-3">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">District:</label>
                        <input type="text" class="swal2-input" value="${selectedDistrictName}" readonly style="background-color: #e9ecef; width: 100%; margin: auto;">
                    </div>
                    <div class="mb-3">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tehsil Name <span style="color: red;">*</span>:</label>
                        <input type="text" id="new-tehsil-name" class="swal2-input" placeholder="Enter tehsil name" style="width: 100%; margin: auto;">
                    </div>
                    <div class="mb-3">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tehsil Code (Optional):</label>
                        <input type="text" id="new-tehsil-code" class="swal2-input" placeholder="Enter tehsil code" style="width: 100%; margin: auto;">
                    </div>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Save Tehsil',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                preConfirm: () => {
                const tehsilName = document.getElementById('new-tehsil-name').value.trim();

        if (!tehsilName) {
            Swal.showValidationMessage('Please enter tehsil name');
            return false;
        }

        if (tehsilName.length < 2) {
            Swal.showValidationMessage('Tehsil name must be at least 2 characters long');
            return false;
        }

        const tehsilCode = document.getElementById('new-tehsil-code').value.trim();

        return {
            tehsil_name: tehsilName,
            tehsil_code: tehsilCode,
            district_id: selectedDistrictId
        };
    },
        allowOutsideClick: false
    }).then(async (result) => {
            if (result.isConfirmed) {
            const data = result.value;

            Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while we save the tehsil',
                    allowOutsideClick: false,
                    didOpen: () => {
                    Swal.showLoading();
        }
        });

            try {
                const response = await fetch('/ajax/add-tehsil', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result_data = await response.json();

                if (result_data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Tehsil Added!',
                        text: `Tehsil "${data.tehsil_name}" has been added successfully.`,
                        confirmButtonColor: '#28a745'
                    });

                    // Refresh tehsil dropdown and select the newly added tehsil
                    if (typeof window.loadTalukas === 'function') {
                        window.loadTalukas(selectedDistrictId, result_data.tehsil_id);
                    }

                } else {
                    throw new Error(result_data.message || 'Failed to add tehsil');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Add Tehsil',
                    text: error.message || 'An error occurred while adding the tehsil. Please try again.',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    });
    }

</script>
@endpush