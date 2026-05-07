@extends('layouts.verifier_layout')
@section('title', 'Voterlist Detail Preview')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .badge{
            color: #000 !important;
        }
        td{
            color: #000 !important;
            font-weight: 600 !important;
        }
    </style>
    <style>
        .highlight-missing {
            background-color: #ffcccc !important; /* light red */
            font-weight: bold;
        }

        @font-face {
            font-family: 'Jameel';
            src: url('/assets/noto-nastaliq-urdu/NotoNastaliqUrdu-VariableFont_wght.ttf') format('truetype');
        }
        .invalidvalue{
            background: red !important;
        }
    </style>
    <section style="padding: 10px 10px; background: #fff">
        <div class="row">
            <div class="col-lg-6 p-t-10">
                <h4 class="p-l-20 ">Blockcode Voterlist Detail</h4>
            </div>
            <div class="col-lg-6">
                {{-- RE-SCAN Button --}}
                <button type="button"
                        class="btn btn-warning float-end ms-2 btn-sm"
                        onclick="confirmRescan('{{ url('/verifier/voterlist-blockcodes-rescan/'.($voters[0]->blockcode ?? '')) }}')">
                    RE-SCAN
                </button>

                {{-- PUSH TO DATABASE Button --}}
                @if($summary['missing_page'] < 1)
                    <button type="button"
                            class="btn btn-success float-end btn-sm"
                            onclick="confirmPush('{{ url('/verifier/voterlist-blockcodes-push-to-database/'.($voters[0]->blockcode ?? '')) }}')">
                        PUSH TO DATABASE
                    </button>
                @endif

            </div>
        </div>

        <hr>
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-3 mb-3">
                    <ul class="list-group">
                        <li class="list-group-item"><b>Blockckode# <span style="float: right">{{ $summary['blockcode'] }}</span></b></li>
                        <li class="list-group-item"><b>Male </b><span style="float: right">{{ $summary['male_voters'] }}</span></li>
                        <li class="list-group-item"><b>Female </b><span style="float: right">{{ $summary['female_voters'] }}</span></li>
                        <li class="list-group-item"><b>Missing Pages </b><span style="float: right">{{ $summary['missing_page'] }}</span></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-3">
                    <ul class="list-group">
                        <li class="list-group-item"><b>Voterlist</b></li>
                        <li class="list-group-item"><b>Male </b><span style="float: right">{{ $summary['maleCount'] }}</span></li>
                        <li class="list-group-item"><b>Female </b><span style="float: right">{{ $summary['femaleCount'] }}</span></li>
                        @if($summary['aMaleCount'] > 0)
                            <li class="list-group-item"><b>A-Male </b><span style="float: right">{{ $summary['aMaleCount'] }}</span></li>
                        @endif
                        @if($summary['aMaleCount'] > 0)
                            <li class="list-group-item"><b>A Female </b><span style="float: right">{{ $summary['aFemaleCount'] }}</span></li>
                        @endif
                        @if($summary['nullCount'] > 0)
                            <li class="list-group-item"><b>Gender Not Selected </b><span style="float: right">{{ $summary['nullCount'] }}</span></li>
                        @endif
                        <li class="list-group-item"><b>Total </b><span style="float: right">{{ $summary['totalCount'] }}</span></li>
                    </ul>
                </div>


                <div class="col-lg-12">
                    <div class="table-responsive m-b-30" style="max-height: 600px; overflow-y: auto;">
                        <table id="votersTable" class="table table-borderless table-striped table-earning">
                            <thead style="position: sticky; top: 0; background: #fff; z-index: 2;">
                            <tr>
                                <th style="direction: rtl; text-align: right;">Address</th>
                                <th>Age</th>
                                <th>CNIC</th>
                                <th style="direction: rtl; text-align: right;">Father's Name</th>
                                <th style="direction: rtl; text-align: right;">Name</th>
                                <th>Gharana</th>
                                <th>Silsila</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $expected_no = null; @endphp
                            @foreach($voters as $row)
                                @php
                                    $is_missing = ($expected_no !== null && $row->silsila_no != $expected_no);
                                    $expected_no = $row->silsila_no + 1;
                                @endphp
                                <tr id="row-{{ $row->id }}">
                                    <td style="direction: rtl; text-align: right; font-family: 'Jameel', serif; @if($is_missing) background-color: red !important; @endif">
                                        {{ $row->address }}
                                    </td>

                                    {{-- Age --}}
                                    <td style="@if($is_missing ) background-color: red !important; @endif"  class="@if( $row->age < 18 || $row->age > 120) invalidvalue @endif">
                                        <span class="view-mode">{{ $row->age }}</span>
                                        <div class="edit-mode" style="display:none;">
                                            <input class="edit-mode-input form-control form-control-sm"
                                                   type="text" name="age" value="{{ $row->age }}"
                                                   style="width:80px; display:inline-block;">
                                            <div class="input-error text-danger" style="font-size:11px; display:none;">Age: 18–120</div>
                                        </div>
                                    </td>

                                    {{-- CNIC --}}
                                    <td style="@if($is_missing ) background-color: red !important; @endif"  class="@if(!preg_match('/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/', $row->cnic)) invalidvalue @endif">
                                        <span class="view-mode">{{ $row->cnic }}</span>
                                        <div class="edit-mode" style="display:none;">
                                            <input class="edit-mode-input form-control form-control-sm"
                                                   type="text" name="cnic" value="{{ $row->cnic }}"
                                                   placeholder="XXXXX-XXXXXXX-X" style="width:160px; display:inline-block;">
                                            <div class="input-error text-danger" style="font-size:11px; display:none;">Format: 43102-7865052-2</div>
                                        </div>
                                    </td>


                                    <td style="direction: rtl; text-align: right; font-family: 'Jameel', serif; @if($is_missing) background-color: red !important; @endif">
                                        {{ $row->father_husband_name }}
                                    </td>
                                    <td style="direction: rtl; text-align: right; font-family: 'Jameel', serif; @if($is_missing) background-color: red !important; @endif">
                                        {{ $row->name }}
                                    </td>

                                    {{-- Gharana --}}
                                    <td style="@if($is_missing) background-color: red !important; @endif">
                                        <span class="view-mode">{{ $row->gharana_no }}</span>
                                        <div class="edit-mode" style="display:none;">
                                            <input class="edit-mode-input form-control form-control-sm"
                                                   type="text" name="gharana_no" value="{{ $row->gharana_no }}"
                                                   style="width:80px; display:inline-block;">
                                            <div class="input-error text-danger" style="font-size:11px; display:none;">Must be a number</div>
                                        </div>
                                    </td>

                                    {{-- Silsila --}}
                                    <td style="@if($is_missing) background-color: red !important; @endif">
                                                <span class="view-mode">
                                                    {{ $row->silsila_no }}
                                                    @if($is_missing) <span>(Gap)</span> @endif
                                                </span>
                                         <div class="edit-mode" style="display:none;">
                                            <input class="edit-mode-input form-control form-control-sm"
                                                   type="text" name="silsila_no" value="{{ $row->silsila_no }}"
                                                   style="width:80px; display:inline-block;">
                                            <div class="input-error text-danger" style="font-size:11px; display:none;">Must be a number</div>
                                        </div>
                                    </td>

                                    {{-- Action --}}
                                    <td style="@if($is_missing) background-color: red !important; @endif">
                                        {{-- View mode: Edit button --}}
                                        <button class="btn btn-sm btn-primary view-mode edit-btn"
                                                data-id="{{ $row->id }}">
                                            Edit
                                        </button>

                                        {{-- Edit mode: Update + Cancel --}}
                                        <div class="edit-mode" style="display:none; white-space:nowrap;">
                                            <button class="btn btn-sm btn-success update-btn"
                                                    data-id="{{ $row->id }}"
                                                    data-url="{{ route('voterspreview.update', $row->id) }}">
                                                Update
                                            </button>
                                            <button class="btn btn-sm btn-secondary cancel-btn ms-1">
                                                Cancel
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ─── Validators ──────────────────────────────────────────────────────────
        const validators = {
                age: (val) => {
                const n = Number(val);
        return val !== '' && Number.isInteger(n) && n >= 18 && n <= 120;
    },
        cnic: (val) => /^[0-9]{5}-[0-9]{7}-[0-9]{1}$/.test(val.trim()),
            gharana_no: (val) => {
            const n = Number(val);
            return val !== '' && Number.isInteger(n) && n >= 1;
        },
        silsila_no: (val) => {
            const n = Number(val);
            return val !== '' && Number.isInteger(n) && n >= 1;
        },
    };

        // ─── Apply keyup validation to a single input ─────────────────────────
        function applyKeyupValidation(input) {
            const name      = input.getAttribute('name');
            const errorDiv  = input.closest('div').querySelector('.input-error');
            const validator = validators[name];
            if (!validator || !errorDiv) return;

            input.addEventListener('keyup', function () {
                const valid = validator(input.value.trim());
                if (valid) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    errorDiv.style.display = 'none';
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    errorDiv.style.display = 'block';
                }
            });

            // Also validate on change (handles paste, spinner clicks)
            input.addEventListener('change', function () {
                input.dispatchEvent(new Event('keyup'));
            });
        }
// ─── CNIC Auto-format (dashes on keyup) ──────────────────────────────────
        document.querySelectorAll('input[name="cnic"]').forEach(function (input) {
            input.addEventListener('input', function () {
                // Strip everything except digits
                let digits = input.value.replace(/\D/g, '');

                // Limit to 13 digits max (5+7+1)
                if (digits.length > 13) digits = digits.slice(0, 13);

                // Insert dashes at positions 5 and 12
                let formatted = '';
                if (digits.length <= 5) {
                    formatted = digits;
                } else if (digits.length <= 12) {
                    formatted = digits.slice(0, 5) + '-' + digits.slice(5);
                } else {
                    formatted = digits.slice(0, 5) + '-' + digits.slice(5, 12) + '-' + digits.slice(12);
                }

                input.value = formatted;

                // Trigger the keyup validator so border color updates live
                input.dispatchEvent(new Event('keyup'));
            });

            // Block non-numeric keys (allow control keys)
            input.addEventListener('keydown', function (e) {
                const allowed = [
                    'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight',
                    'Tab', 'Home', 'End'
                ];
                if (allowed.includes(e.key)) return;
                if (!/^\d$/.test(e.key)) e.preventDefault();
            });
        });
        // ─── Attach keyup to all inputs on load ──────────────────────────────
        document.querySelectorAll('input.edit-mode-input').forEach(applyKeyupValidation);

        // ─── Validate entire row — returns true if all valid ─────────────────
        function validateRow(row) {
            const errors = [];
            row.querySelectorAll('input.edit-mode-input').forEach(input => {
                const name      = input.getAttribute('name');
            const validator = validators[name];
            if (!validator) return;
            const valid = validator(input.value.trim());
            // Trigger visual state
            input.dispatchEvent(new Event('keyup'));
            if (!valid) {
                const messages = {
                    age:        'Age must be a whole number between 18 and 120.',
                    cnic:       'CNIC must be in the format <b>XXXXX-XXXXXXX-X</b> (e.g. 43102-7865052-2).',
                    gharana_no: 'Gharana No must be a positive whole number.',
                    silsila_no: 'Silsila No must be a positive whole number.',
                };
                errors.push('• ' + (messages[name] || name + ' is invalid.'));
            }
        });
            return errors;
        }

        // ─── EDIT ─────────────────────────────────────────────────────────────
        document.querySelectorAll('.edit-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const row = document.getElementById('row-' + btn.dataset.id);
                row.querySelectorAll('.view-mode').forEach(el => el.style.display = 'none');
                row.querySelectorAll('.edit-mode').forEach(el => el.style.display = '');

                // Reset validation state when opening edit mode
                row.querySelectorAll('input.edit-mode-input').forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                const errorDiv = input.closest('div')?.querySelector('.input-error');
                if (errorDiv) errorDiv.style.display = 'none';
            });
            });
        });

        // ─── CANCEL ───────────────────────────────────────────────────────────
        document.querySelectorAll('.cancel-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const row = btn.closest('tr');
                row.querySelectorAll('.view-mode').forEach(el => el.style.display = '');
                row.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'none');
                // Reset validation states
                row.querySelectorAll('input.edit-mode-input').forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                const errorDiv = input.closest('div')?.querySelector('.input-error');
                if (errorDiv) errorDiv.style.display = 'none';
            });
            });
        });

        // ─── UPDATE ───────────────────────────────────────────────────────────
        document.querySelectorAll('.update-btn').forEach(function (btn) {
            btn.addEventListener('click', function ()
            );
        });

    });
</script>
<script>


         function confirmRescan(url) {
             Swal.fire({
                     title: 'RE-SCAN Blockcode?',
                     text: 'Are you sure you want to re-scan this blockcode? This will re-process the data.',
                     icon: 'warning',
                     html: `
                    <p style="margin-bottom: 12px; color: #555;">Are you sure you want to re-scan this blockcode? This will re-process the data.</p>
                    <textarea
                        id="rescan-value"
                        class="swal2-textarea"
                        placeholder="Enter Reason"
                        rows="4"
                        style="width: 100%; resize: vertical;    width: -webkit-fill-available;"
                    ></textarea>
                `,
                     showCancelButton: true,
                     confirmButtonColor: '#f0ad4e',
                     cancelButtonColor: '#6c757d',
                     confirmButtonText: 'Yes, Re-Scan!',
                     cancelButtonText: 'Cancel',
                     preConfirm: () => {
                     const inputValue = document.getElementById('rescan-value').value.trim();
             if (!inputValue) {
                 Swal.showValidationMessage('Please enter a value before proceeding.');
                 return false;
             }
             return inputValue;
         }
         }).then((result) => {
                 if (result.isConfirmed) {
                 const inputValue = result.value;
                 const finalUrl = `${url}?rescan_value=${encodeURIComponent(inputValue)}`;
                 window.location.href = finalUrl;
             }
         });
         }

    function confirmPush(url) {
        Swal.fire({
            title: 'Push to Database?',
            text: 'Are you sure you want to push this data to the database? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Push it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
            window.location.href = url;
        }
    });
    }
</script>
@endpush