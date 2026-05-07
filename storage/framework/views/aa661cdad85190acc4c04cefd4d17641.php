
<?php $__env->startSection('content'); ?>
<?php $__env->startSection('title', 'DATA CENTER :: Blockcodes'); ?>
<style>
    .blockcode-table tbody {
        display: block;
        max-height: 400px;
        overflow-y: auto !important;
    }
    .blockcode-table2 tbody {
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
    .table td, .table th{
        padding: 0px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <p class="text-center"><b>Blockcode Info</b># (<?php echo e($block_info['blockcode']); ?>), Total (<?php echo e($block_info['total_voters']); ?>),
                <b>Voterlist Info#</b>
                <?php $total_count_voters = 0; ?>

                <?php $__currentLoopData = $voters_count; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gender_key => $vot_count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span style="text-transform: capitalize">
                        <?php echo e(strtolower($gender_key)); ?>: <?php echo e($vot_count); ?>

                    </span><?php if(!$loop->last): ?>, <?php endif; ?>
                    <?php $total_count_voters += $vot_count; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <strong>Total: <?php echo e($total_count_voters); ?></strong>
                (Today# <span id="today_updated"><?php echo e($count_today); ?></span> )</p>
        </div>
        <div class="col-lg-12" style="text-align: right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                Add New
            </button>
            <button
                    class="btn btn-sm status-btn <?php echo e($block_info['completed'] == 1 ? 'btn-success' : 'btn-warning'); ?>"
                    data-id="<?php echo e($block_info['id']); ?>"
                    data-status="<?php echo e($block_info['completed']); ?>">
                <?php echo e($block_info['completed'] == 1 ? 'Completed' : 'Pending'); ?>

            </button>
        </div>
        <div class="col-lg-12 blockcode-table-padding table-wrapper-scroll-y my-custom-scrollbar">


            <div id="accordion">

                <div class="card">
                    <div class="card-header">
                        <a class="card-link" data-toggle="collapse" href="#collapseOne">
                            PDF VIEW
                        </a>
                    </div>
                    <div id="collapseOne" class="collapse show">
                        <div class="card-body">
                            <div>
                                <iframe src="<?php echo e(asset('blockcode_files/'.$block_info['blockcode'].'.pdf')); ?>#zoom=200" style="height:400px;width:100%;" title="PDF Preview"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
                            Voterlist
                        </a>
                    </div>
                    <div id="collapseTwo" class="collapse">
                        <div class="card-body">
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
                                <?php $__currentLoopData = $voters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($code->id); ?>" data-blockcode="<?php echo e($code->blockcode); ?>">

                                        
                                        <td style="width: 28%; text-align: left;vertical-align: bottom;" class="voter-address">
                                            <textarea class="form-control" name="address" rows="2"  style="text-transform: uppercase;"><?php echo e($code->address); ?></textarea>
                                        </td>
                                        
                                        <td style="width: 5%;vertical-align: bottom" class="voter-age">
                                            <input type="text"
                                                   class="form-control age-input"
                                                   name="age"
                                                   value="<?php echo e($code->age); ?>"
                                                   oninput="validateAge(this)"
                                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                        </td>
                                        
                                        <td style="width: 12%; vertical-align: bottom;" class="voter-cnic">

                                            <input type="text" class="form-control" name="cnic" value="<?php echo e($code->cnic); ?>">
                                        </td>
                                        
                                        <td style="width: 12%; vertical-align: bottom;" class="voter-father">
                                            <input type="text" class="form-control" name="father_husband_name" value="<?php echo e($code->father_husband_name); ?>" style="text-transform: uppercase;">
                                        </td>
                                        
                                        <td style="width: 8%; vertical-align: bottom; vertical-align: bottom;" class="voter-father-type">
                                            <select class="form-control" name="father_husband">
                                                <option value="Father"  <?php echo e($code->father_husband == 'Father'  ? 'selected' : ''); ?>>Father</option>
                                                <option value="Husband" <?php echo e($code->father_husband == 'Husband' ? 'selected' : ''); ?>>Husband</option>
                                            </select>
                                        </td>
                                        
                                        <td style="width: 8%; vertical-align: bottom;" class="voter-gender">
                                            <select class="form-control" name="gender">
                                                <option value="MALE"     <?php echo e($code->gender == 'MALE'     ? 'selected' : ''); ?>>MALE</option>
                                                <option value="FEMALE"   <?php echo e($code->gender == 'FEMALE'   ? 'selected' : ''); ?>>FEMALE</option>
                                                <option value="A-MALE"   <?php echo e($code->gender == 'A-MALE'   ? 'selected' : ''); ?>>A-MALE</option>
                                                <option value="A-FEMALE" <?php echo e($code->gender == 'A-FEMALE' ? 'selected' : ''); ?>>A-FEMALE</option>
                                            </select>
                                        </td>
                                        
                                        <td style="width: 12%;vertical-align: bottom;" class="voter-name">
                                            <input type="text" class="form-control" name="name" value="<?php echo e($code->name); ?>" style="text-transform: uppercase;">
                                        </td>
                                        <td style="width: 5%; vertical-align: bottom;" class="voter-gharana">
                                            <input type="text"
                                                   class="form-control numeric-check"
                                                   name="gharana_no"
                                                   value="<?php echo e($code->gharana_no); ?>">
                                        </td>

                                        <td style="width: 5%; vertical-align: bottom;" class="voter-silsila">
                                            <input type="text"
                                                   class="form-control numeric-check"
                                                   name="silsila_no"
                                                   value="<?php echo e($code->silsila_no); ?>">
                                        </td>
                                        
                                        <td style="width: 5%; vertical-align: bottom;vertical-align: bottom;">
                                            <button id="voter-id-<?php echo e($code->id); ?>" class="btn  update-btn <?php if($code->updated_by ==0): ?> btn-danger <?php else: ?> btn-success <?php endif; ?> "
                                                    data-id="<?php echo e($code->id); ?>"
                                                    data-blockcode="<?php echo e($code->blockcode); ?>">
                                                Update
                                            </button>
                                        </td>



                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

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
                        <input type="text" class="form-control" id="new_name" placeholder="Enter Name" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')"  style="text-transform: uppercase;">
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
                        <input type="hidden" class="form-control" id="new_blockcode" value="<?php echo e($block_info['blockcode']); ?>" >
                        <input type="text" class="form-control" id="new_father_husband_name" placeholder="Enter Father / Husband Name" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')"  style="text-transform: uppercase;">
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
                        <textarea class="form-control" id="new_address" rows="3" placeholder="Enter Address"  style="text-transform: uppercase;"></textarea>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('myModal');
        let isDragging = false;
        let startX, startY, initialLeft, initialTop;

        // Only allow dragging from the header
        const dragHandle = document.querySelector('#myModal .modal-header');

        dragHandle.style.cursor = 'move';

        dragHandle.addEventListener('mousedown', function(e) {
            // Don't drag if clicking on close button or input elements inside header
            if (e.target.classList.contains('close') || e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') {
                return;
            }

            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;

            const modalDialog = modal.querySelector('.modal-dialog');
            const rect = modalDialog.getBoundingClientRect();
            initialLeft = rect.left;
            initialTop = rect.top;

            modalDialog.style.position = 'fixed';
            modalDialog.style.margin = '0';
            modalDialog.style.left = initialLeft + 'px';
            modalDialog.style.top = initialTop + 'px';
            modalDialog.style.cursor = 'move';

            e.preventDefault();
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;

            const dx = e.clientX - startX;
            const dy = e.clientY - startY;

            const modalDialog = modal.querySelector('.modal-dialog');
            let newLeft = initialLeft + dx;
            let newTop = initialTop + dy;

            // Boundary constraints
            const maxLeft = window.innerWidth - modalDialog.offsetWidth - 20;
            const maxTop = window.innerHeight - modalDialog.offsetHeight - 20;

            newLeft = Math.max(10, Math.min(newLeft, maxLeft));
            newTop = Math.max(10, Math.min(newTop, maxTop));

            modalDialog.style.left = newLeft + 'px';
            modalDialog.style.top = newTop + 'px';
        });

        document.addEventListener('mouseup', function() {
            isDragging = false;
            const modalDialog = modal.querySelector('.modal-dialog');
            if (modalDialog) {
                modalDialog.style.cursor = '';
            }
        });

        // Reset positioning when modal is hidden
        $('#myModal').on('hidden.bs.modal', function() {
            const modalDialog = this.querySelector('.modal-dialog');
            modalDialog.style.position = '';
            modalDialog.style.left = '';
            modalDialog.style.top = '';
            modalDialog.style.margin = '';
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('jsfiles'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        $(document).ready(function () {  // ← wrap it in document.ready

            $(document).on('click', '.status-btn', function () {
                const btn       = $(this);
                const id        = btn.data('id');
                const status    = parseInt(btn.data('status')); // ← force integer
                const newStatus = status === 1 ? 0 : 1;

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to change the status?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, update it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                    $.ajax({
                        url: '/update-completed-status',
                        method: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            id: id,
                            completed: newStatus
                        },
                        success: function (res) {
                            if (res.success) {
                                btn.data('status', newStatus);
                                btn.text(newStatus === 1 ? 'Completed' : 'Pending');
                                btn.removeClass('btn-success btn-warning')
                                    .addClass(newStatus === 1 ? 'btn-success' : 'btn-warning');

                                Swal.fire("Updated!", "Status updated successfully.", "success");
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr);
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });
                }
            });
            });

        });

        function validateAge(input) {
            let value = parseInt(input.value);

            if (!isNaN(value) && (value < 18 || value > 120)) {
                input.style.border = "2px solid red";
                input.style.backgroundColor = "#ffe6e6";
            } else {
                input.style.border = "";
                input.style.backgroundColor = "";
            }
        }

        window.onload = function() {
            validateAge(document.getElementById('ageInput'));
        };
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
                        .after('<div class="cnic-row-feedback invalid-feedback" style="display:block;">❌ Invalid CNIC — format: </div>');
                    return;
                }

                let updatedData = {
                    _token:              '<?php echo e(csrf_token()); ?>',
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

                        $('#voter-id-'+voterId).removeClass('btn-danger');
                        $('#voter-id-'+voterId).addClass('btn-success');




                        row.find('input[name="cnic"]').removeClass('is-valid is-invalid');
                        row.find('.cnic-row-feedback').remove();
                        $("#today_updated").text(response.count_today);
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
                    let nextInput = nextRow.find('textarea[name="address"]');
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
            // ✅ Live CNIC formatting & validation (row inputs)c
            // ============================================================
            /*$(document).on('input', 'input[name="cnic"]', function () {
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
             $(this).after('<div class="cnic-row-feedback invalid-feedback" style="display:block;">Invalid — format: </div>');
             } else {
             $(this).removeClass('is-invalid');
             }
             });*/
            $(document).on('input', 'input[name="cnic"]', function () {

                let input = this;
                let cursorPosition = input.selectionStart; // save cursor position

                let raw = input.value.replace(/[^0-9]/g, '');
                let formatted = '';

                if (raw.length <= 5) {
                    formatted = raw;
                } else if (raw.length <= 12) {
                    formatted = raw.substring(0, 5) + '-' + raw.substring(5);
                } else {
                    formatted = raw.substring(0, 5) + '-' + raw.substring(5, 12) + '-' + raw.substring(12, 13);
                }

                input.value = formatted;

                // 🔹 Restore cursor position correctly
                let newCursorPosition = cursorPosition;

                // adjust position if dash added
                if (cursorPosition === 6 || cursorPosition === 14) {
                    newCursorPosition++;
                }

                input.setSelectionRange(newCursorPosition, newCursorPosition);

                // Validation
                let cnicPattern = /^\d{5}-\d{7}-\d{1}$/;
                $(input).siblings('.cnic-row-feedback').remove();

                if (formatted.length === 0) {
                    $(input).removeClass('is-valid is-invalid');
                } else if (!cnicPattern.test(formatted)) {
                    $(input).removeClass('is-valid').addClass('is-invalid');
                    $(input).after('<div class="cnic-row-feedback invalid-feedback" style="display:block;">Invalid — format: 12345-1234567-1</div>');
                } else {
                    $(input).removeClass('is-invalid').addClass('is-valid');
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
                    // $(this).removeClass('is-invalid').addClass('is-valid');
                    $('#cnic-feedback').remove();
                    $(this).after('<div id="cnic-feedback" class="valid-feedback" style="display:block;">✅ Valid CNIC</div>');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                    $('#cnic-feedback').remove();
                    $(this).after('<div id="cnic-feedback" class="invalid-feedback" style="display:block;">❌ Invalid CNIC — format: </div>');
                }
            });

            // ============================================================
            // ✅ SAVE NEW VOTER
            // ============================================================
            $('#save-new-voter').on('click', function () {
                let newData = {
                    _token:              '<?php echo e(csrf_token()); ?>',
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
                    $('#modal-alert').html('<div class="alert alert-danger">Invalid CNIC format. Required: </div>').show();
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
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.front', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/front/voter_list/voter_listing_pdf.blade.php ENDPATH**/ ?>