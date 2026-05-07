@extends('layouts.front')
@section('content')
@section('title', 'DATA CENTER :: Blockcodes')
<style>
    .blockcode-table tbody {
        display: block;
        max-height: 400px;
        overflow-y: auto !important;
    }
    .blockcode-table {
        table-layout: fixed;
        width: 100%;
        word-wrap: break-word;
    }
    .blockcode-table td,
    .blockcode-table th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: bottom;
    }
    tr {
        display: table;
        width: 100%;
        table-layout: auto;
    }
    td {
        text-align: center;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="text-center p-2"><b>VOTERLIST LISTING ({{$block_info['blockcode']}})</b></h3>
            <h3 class="text-center p-2"><b>Total ({{$voters_count}})</b></h3>
        </div>
        <div class="col-lg-12 p-3" style="text-align: right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                Add New
            </button>
        </div>
        <div class="col-lg-12 blockcode-table-padding table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table blockcode-table2 table-bordered">
                <thead>
                <tr>
                    <th style="width: 28%;font-size: 14px; text-align: left">Address</th>
                    <th style="width: 5%;font-size: 14px;">Age</th>
                    <th style="width: 12%;font-size: 14px;">CNIC</th>
                    <th style="width: 12%;font-size: 14px;">Father / Husband's <br>Name</th>
                    <th style="width: 8%;font-size: 14px;">Father /<br> Husband</th>
                    <th style="width: 8%;font-size: 14px;">Gender</th>
                    <th style="width: 12%;font-size: 14px;">Name</th>
                    <th style="width: 5%;font-size: 14px;">Gharana</th>
                    <th style="width: 5%;font-size: 14px;">Silsila</th>
                    <th style="width: 5%;font-size: 14px;">Update</th>

                </tr>
                </thead>
                <tbody id="blockcodes-tbody">
                @foreach($voters as $key => $code)
                    <tr data-id="{{ $code->id }}" data-blockcode="{{ $code->blockcode }}">














                        {{-- ADDRESS --}}
                        <td style="width: 28%; text-align: left;vertical-align: bottom;" class="voter-address">
                            <img src="{{ asset('blockcodes/'.$code->blockcode.'/'.$code->image_cnic.'_1.png') }}" style="width: 450px;"/><br>
                            <textarea class="form-control" name="address" rows="2">{{ $code->address }}</textarea>
                        </td>
                        {{-- AGE --}}
                        <td style="width: 5%;vertical-align: bottom" class="voter-age">
                            <img src="{{ asset('blockcodes/'.$code->blockcode.'/'.$code->image_cnic.'_2.png') }}" style="width: 40px;"/><br>
                            <input type="text"
                                   class="form-control age-input"
                                   name="age"
                                   value="{{ $code->age }}"
                                   oninput="validateAge(this)"
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </td>
                        {{-- CNIC --}}
                        <td style="width: 12%; vertical-align: bottom;" class="voter-cnic">
                            <img src="{{ asset('blockcodes/'.$code->blockcode.'/'.$code->image_cnic.'_3.png') }}" style="width: 140px;"/><br>

                            <input type="text" class="form-control" name="cnic" value="{{ $code->cnic }}">
                        </td>
                        {{-- FATHER/HUSBAND NAME --}}
                        <td style="width: 12%; vertical-align: bottom;" class="voter-father">
                            <img src="{{ asset('blockcodes/'.$code->blockcode.'/'.$code->image_cnic.'_4.png') }}" style="width: 150px;"/><br>
                            <input type="text" class="form-control" name="father_husband_name" value="{{ $code->father_husband_name }}" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')">
                        </td>
                        {{-- FATHER/HUSBAND TYPE --}}
                        <td style="width: 8%; vertical-align: bottom; vertical-align: bottom;" class="voter-father-type">
                            <select class="form-control" name="father_husband">
                                <option value="Father"  {{ $code->father_husband == 'Father'  ? 'selected' : '' }}>Father</option>
                                <option value="Husband" {{ $code->father_husband == 'Husband' ? 'selected' : '' }}>Husband</option>
                            </select>
                        </td>
                        {{-- GENDER --}}
                        <td style="width: 8%; vertical-align: bottom;" class="voter-gender">
                            <select class="form-control" name="gender">
                                <option value="MALE"     {{ $code->gender == 'MALE'     ? 'selected' : '' }}>MALE</option>
                                <option value="FEMALE"   {{ $code->gender == 'FEMALE'   ? 'selected' : '' }}>FEMALE</option>
                                <option value="A-MALE"   {{ $code->gender == 'A-MALE'   ? 'selected' : '' }}>A-MALE</option>
                                <option value="A-FEMALE" {{ $code->gender == 'A-FEMALE' ? 'selected' : '' }}>A-FEMALE</option>
                            </select>
                        </td>
                        {{-- NAME --}}
                        <td style="width: 12%;vertical-align: bottom;" class="voter-name">
                            <img src="{{ asset('blockcodes/'.$code->blockcode.'/'.$code->cnic.'_5.png') }}" style="width: 150px;"/><br>
                            <input type="text" class="form-control" name="name" value="{{ $code->name }}" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')">
                        </td>
                        {{-- GHARANA --}}
                        <td style="width: 5%;vertical-align: bottom;" class="voter-gharana">
                            <img src="{{ asset('blockcodes/'.$code->blockcode.'/'.$code->image_cnic.'_6.png') }}" style="width: 50px;"/><br>
                            <input type="text"
                                   class="form-control numeric-check"
                                   name="gharana_no"
                                   value="{{ $code->gharana_no }}">                        </td>
                        {{-- SILSILA --}}
                        <td style="width: 5%;vertical-align: bottom;" class="voter-silsila">
                            <img src="{{ asset('blockcodes/'.$code->blockcode.'/'.$code->image_cnic.'_7.png') }}" style="width: 50px;"/><br>
                            <input type="text"
                                   class="form-control numeric-check"
                                   name="silsila_no"
                                   value="{{ $code->silsila_no }}">                        </td>
                        {{-- UPDATE BUTTON --}}
                        <td style="width: 5%; vertical-align: bottom;vertical-align: bottom;">
                            <button class="btn btn-success update-btn"
                                    data-id="{{ $code->id }}"
                                    data-blockcode="{{ $code->blockcode }}">
                                Update
                            </button>
                        </td>



                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $voters->links('vendor.pagination.bootstrap-4') !!}
        </div>
    </div>
</div>

<!-- ADD NEW VOTER MODAL -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Voter</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="modal-alert" style="display:none;"></div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="new_silsila_no" placeholder="Enter Silsila No" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="new_gharana_no" placeholder="Enter Gharana No" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="new_name" placeholder="Enter Name" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <select class="form-control" id="new_gender">
                            <option value="">Select Gender</option>
                            <option value="MALE">MALE</option>
                            <option value="FEMALE">FEMALE</option>
                            <option value="A-MALE">A-MALE</option>
                            <option value="A-FEMALE">A-FEMALE</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <select class="form-control" id="new_father_husband">
                            <option value="">Select Father's Name / Husband Name</option>
                            <option value="Father">Father</option>
                            <option value="Husband">Husband</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <input type="hidden" class="form-control" id="new_blockcode" value="{{$block_info['blockcode']}}" >
                        <input type="text" class="form-control" id="new_father_husband_name" placeholder="Enter Father / Husband Name" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="new_cnic" placeholder="Enter CNIC # 11111-1111111-1">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="new_age" placeholder="Enter Age" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <textarea class="form-control" id="new_address" rows="3" placeholder="Enter Address"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="save-new-voter">
                    <span id="save-btn-text">Save</span>
                    <span id="save-btn-loader" style="display:none;">Saving...</span>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsfiles')
    <script>

        function validateAge(input) {
            let value = parseInt(input.value);

            if (!isNaN(value) && (value < 18 || value > 120)) {
                input.style.border = "2px solid red";
                input.style.backgroundColor = "#ffe6e6"; // light red
            } else {
                input.style.border = "";
                input.style.backgroundColor = "";
            }
        }

        // Validate all age inputs on page load
        window.onload = function() {
            document.querySelectorAll('.age-input').forEach(function(input) {
                validateAge(input);
            });
        };

        // Optional: live validation for dynamic inputs (if added later via JS)
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('age-input')) {
                validateAge(e.target);
            }
        });
        function validateNumericField(input) {

            // Remove non-numeric characters
            input.value = input.value.replace(/[^0-9]/g, '');

            // Highlight if starts with zero
            if (input.value.startsWith('0') && input.value.length > 0) {
                input.style.border = "2px solid red";
                input.style.backgroundColor = "#ffe6e6";
            } else {
                input.style.border = "";
                input.style.backgroundColor = "";
            }
        }

        // Run on typing
        document.addEventListener("input", function(e) {
            if (e.target.classList.contains("numeric-check")) {
                validateNumericField(e.target);
            }
        });

        // Run on page load
        window.addEventListener("load", function() {
            document.querySelectorAll(".numeric-check").forEach(function(input) {
                validateNumericField(input);
            });
        });

        function validateCNICField(input) {
            const cnicPattern = /^\d{5}-\d{7}-\d{1}$/;
            const value = input.value.trim();

            if (value.length === 0) {
                input.classList.remove('is-valid', 'is-invalid');
                return;
            }

            if (cnicPattern.test(value)) {
                input.classList.remove('is-invalid');
                //input.classList.add('is-valid');
                if (input.nextElementSibling && input.nextElementSibling.classList.contains('cnic-row-feedback')) {
                    input.nextElementSibling.remove();
                }
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('cnic-row-feedback')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'cnic-row-feedback invalid-feedback';
                    errorDiv.style.display = 'block';
                    errorDiv.textContent = '❌ Invalid CNIC — format: ';
                    input.after(errorDiv);
                }
            }
        }

        // Run validation on all CNIC inputs on page load
        window.addEventListener("load", function() {
            document.querySelectorAll('input[name="cnic"]').forEach(function(input) {
                validateCNICField(input);
            });
        });

        // Optional: Also validate on typing
        document.addEventListener("input", function(e) {
            if (e.target.name === 'cnic') {
                validateCNICField(e.target);
            }
        });






        $(document).ready(function () {

            // ============================================================
            // HELPER: Run the update logic for a given row
            // ============================================================
            function doUpdate(btn, row, onSuccess) {
                let voterId   = btn.data('id');
                let cnicValue = row.find('input[name="cnic"]').val();

                let cnicPattern = /^\d{5}-\d{7}-\d{1}$/;
                if (!cnicPattern.test(cnicValue)) {
                    row.find('.cnic-row-feedback').remove();
                    row.find('input[name="cnic"]')
                        .removeClass('is-valid').addClass('is-invalid')
                        .after('<div class="cnic-row-feedback invalid-feedback" style="display:block;">❌ Invalid CNIC — format: 44101-4614081-1</div>');
                    return;
                }

                let updatedData = {
                    _token:              '{{ csrf_token() }}',
                    name:                row.find('input[name="name"]').val(),
                    cnic:                cnicValue,
                    age:                 row.find('input[name="age"]').val(),
                    address:             row.find('textarea[name="address"]').val(),
                    father_husband_name: row.find('input[name="father_husband_name"]').val(),
                    father_husband:      row.find('select[name="father_husband"]').val(),
                    gender:              row.find('select[name="gender"]').val(),
                    gharana_no:          row.find('input[name="gharana_no"]').val(),
                    silsila_no:          row.find('input[name="silsila_no"]').val(),
                };

                btn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url:  '/ajax/voters/update/' + voterId,
                    type: 'POST',
                    data: updatedData,
                    success: function (response) {
                        btn.prop('disabled', false).text('Update');

                        row.find('input[name="cnic"]').removeClass('is-valid is-invalid');
                        row.find('.cnic-row-feedback').remove();

                        // Flash row green
                        row.css('background-color', '#d4edda');
                        setTimeout(function () { row.css('background-color', ''); }, 1500);

                        // ✅ Move cursor to next row's first input (Name)
                        if (typeof onSuccess === 'function') onSuccess();
                    },
                    error: function (err) {
                        btn.prop('disabled', false).text('Update');
                        alert('Error updating record!');
                        console.log(err);
                    }
                });
            }

            // ============================================================
            // HELPER: Focus the Name input in the next row
            // ============================================================
            function focusNextRow(currentRow) {
                let nextRow = currentRow.next('tr');
                if (nextRow.length) {
                    let nextInput = nextRow.find('input[name="silsila_no"]');
                    if (nextInput.length) {
                        nextInput.focus().select();
                    }
                }
            }

            // ============================================================
            // ✅ Click on Update button
            // ============================================================
            $(document).on('click', '.update-btn', function () {
                let btn = $(this);
                let row = btn.closest('tr');
                doUpdate(btn, row, function () {
                    focusNextRow(row);
                });
            });

            // ============================================================
            // ✅ Enter key inside any input/select/textarea in a row
            //    → triggers save & moves to next row
            // ============================================================
            $(document).on('keydown', '#blockcodes-tbody input, #blockcodes-tbody textarea, #blockcodes-tbody select', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    let row = $(this).closest('tr');
                    let btn = row.find('.update-btn');
                    doUpdate(btn, row, function () {
                        focusNextRow(row);
                    });
                }
            });

            // ============================================================
            // ✅ Space or Enter key on the Update button itself
            //    (button already handles click for mouse; this catches keyboard)
            // ============================================================
            $(document).on('keydown', '.update-btn', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    let btn = $(this);
                    let row = btn.closest('tr');
                    doUpdate(btn, row, function () {
                        focusNextRow(row);
                    });
                }
            });

            // ============================================================
            // ✅ Live CNIC formatting & validation (row inputs)
            // ============================================================
            $(document).on('input', 'input[name="cnic"]', function () {
                let raw = $(this).val().replace(/[^0-9]/g, '');
                let formatted = '';
                if (raw.length <= 5) {
                    formatted = raw;
                } else if (raw.length <= 12) {
                    formatted = raw.substring(0, 5) + '-' + raw.substring(5);
                } else {
                    formatted = raw.substring(0, 5) + '-' + raw.substring(5, 12) + '-' + raw.substring(12, 13);
                }
                $(this).val(formatted);

                let cnicPattern = /^\d{5}-\d{7}-\d{1}$/;
                $(this).siblings('.cnic-row-feedback').remove();
                if (formatted.length === 0) {
                    $(this).removeClass('is-valid is-invalid');
                } else if (!cnicPattern.test(formatted)) {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                    $(this).after('<div class="cnic-row-feedback invalid-feedback" style="display:block;">Invalid — format: 44101-4614081-1</div>');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // ============================================================
            // ✅ Modal: CNIC formatting & validation
            // ============================================================
            $('#new_cnic').on('input', function () {
                let raw = $(this).val().replace(/[^0-9]/g, '');
                let formatted = '';
                if (raw.length <= 5) {
                    formatted = raw;
                } else if (raw.length <= 12) {
                    formatted = raw.substring(0, 5) + '-' + raw.substring(5);
                } else {
                    formatted = raw.substring(0, 5) + '-' + raw.substring(5, 12) + '-' + raw.substring(12, 13);
                }
                $(this).val(formatted);

                let cnicPattern = /^\d{5}-\d{7}-\d{1}$/;
                if (formatted.length === 0) {
                    $(this).removeClass('is-valid is-invalid');
                    $('#cnic-feedback').remove();
                } else if (cnicPattern.test(formatted)) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    $('#cnic-feedback').remove();
                    $(this).after('<div id="cnic-feedback" class="valid-feedback" style="display:block;">✅ Valid CNIC</div>');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                    $('#cnic-feedback').remove();
                    $(this).after('<div id="cnic-feedback" class="invalid-feedback" style="display:block;">❌ Invalid CNIC — format: 44101-4614081-1</div>');
                }
            });

            // ============================================================
            // ✅ SAVE NEW VOTER
            // ============================================================
            $('#save-new-voter').on('click', function () {
                let newData = {
                    _token:              '{{ csrf_token() }}',
                    name:                $('#new_name').val(),
                    cnic:                $('#new_cnic').val(),
                    age:                 $('#new_age').val(),
                    address:             $('#new_address').val(),
                    father_husband_name: $('#new_father_husband_name').val(),
                    father_husband:      $('#new_father_husband').val(),
                    gender:              $('#new_gender').val(),
                    gharana_no:          $('#new_gharana_no').val(),
                    silsila_no:          $('#new_silsila_no').val(),
                    blockcode:          $('#new_blockcode').val(),

                };

                if (!newData.name || !newData.cnic || !newData.gender || !newData.father_husband) {
                    $('#modal-alert').html('<div class="alert alert-danger">Please fill all required fields.</div>').show();
                    return;
                }

                let cnicPattern = /^\d{5}-\d{7}-\d{1}$/;
                if (!cnicPattern.test(newData.cnic)) {
                    $('#modal-alert').html('<div class="alert alert-danger">Invalid CNIC format. Required: 44101-4614081-1</div>').show();
                    return;
                }

                $('#save-btn-text').hide();
                $('#save-btn-loader').show();

                $.ajax({
                    url:  '/ajax/voters/store',
                    type: 'POST',
                    data: newData,
                    success: function (response) {
                        $('#save-btn-text').show();
                        $('#save-btn-loader').hide();
                        $('#modal-alert').html('<div class="alert alert-success">Voter added successfully! Reloading...</div>').show();
                        setTimeout(function () { location.reload(); }, 1000);
                    },
                    error: function (err) {
                        $('#save-btn-text').show();
                        $('#save-btn-loader').hide();
                        if (err.status === 422) {
                            $('#modal-alert').html('<div class="alert alert-danger">' + err.responseJSON.message + '</div>').show();
                        } else {
                            $('#modal-alert').html('<div class="alert alert-danger">Error saving voter. Please try again.</div>').show();
                        }
                        console.log(err);
                    }
                });
            });

            // ============================================================
            // Reset modal on close
            // ============================================================
            $('#myModal').on('hidden.bs.modal', function () {
                $('#modal-alert').hide();
                $('#new_cnic').removeClass('is-valid is-invalid');
                $('#cnic-feedback').remove();
                $('#new_name, #new_cnic, #new_age, #new_address, #new_father_husband_name, #new_gharana_no, #new_silsila_no').val('');
                $('#new_father_husband, #new_gender').val('');
            });

        });
    </script>
@endsection

