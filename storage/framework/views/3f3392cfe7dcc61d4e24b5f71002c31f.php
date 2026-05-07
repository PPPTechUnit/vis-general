
<?php $__env->startSection('title', 'Blockcode Information Listing'); ?>

<?php $__env->startSection('content'); ?>
    <!-- MAIN CONTENT-->
    <style>
        .table-earning thead th {
            background: #626262;
        }
    </style>
    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">Blockcode Information</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">
                <?php if(auth()->user()->group == 'main' || auth()->user()->group == 'group1'  ): ?>
                    <div class="col-lg-12 mb-5" >
                        <a class="btn btn-primary" href="<?php echo e(route('blockcode-information.create')); ?>" style="float: right">Add New Blockcode</a>
                    </div>
                <?php endif; ?>

                <div class="col-lg-12">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">  <?php echo e(session('success')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        </div>
                    <?php endif; ?> <?php if(session('error')): ?>
                        <div class="alert alert-danger">  <?php echo e(session('error')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-12">
                    <div class="table-responsive m-b-30">
                        <table id="votersTable" class="table table-borderless table-striped table-earning">
                            <thead>
                            <tr>
                                <th>S.No#</th>
                                <th>Block Code</th>
                                <th>District</th>
                                <th>Village / City</th>
                                <th>Area</th>
                                <th>Total Voters</th>
                                <th>Circle</th>
                                <th>Book No</th>
                                <th>Pages</th>
                                <th>Missing Page</th>
                                <th>Condition</th>
                                <th>Scanned</th>
                                <th>Deskewed</th>
                                <th>Converted</th>
                                <th>QA Converted_file</th>
                                <th>Uploaded</th>
                                <th>Added By</th>
                                <th>Created At</th>
                                <?php if(auth()->user()->group == 'main' || auth()->user()->group == 'group1'  ): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                                <th>Status</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1; ?>
                            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($i); ?></td>
                                    <td> <a href="<?php echo e(route('blockcode-information.show',$row->id)); ?>" class="btn btn-success"> <?php echo e($row->blockcode); ?></a> </td>
                                    <td><?php echo e($row->district_name); ?></td>
                                    <td><?php echo e($row->village_city); ?></td>
                                    <td><?php echo e($row->eloctoral_area_name); ?></td>
                                    <td><?php echo e($row->total_voters); ?></td>
                                    <td><?php echo e($row->circle_name); ?></td>
                                    <td><?php echo e($row->book_number); ?></td>
                                    <td><?php echo e($row->pages); ?></td>
                                    <td><?php echo e($row->missing_page); ?></td>
                                    <td><?php echo e($row->condition); ?></td>

                                    <!-- Scanned -->
                                    <td>
                                        <?php if(auth()->user()->group == 'main' || auth()->user()->group == 'group2'  ): ?>
                                            <select class="form-control status-select" data-id="<?php echo e($row->id); ?>" data-field="scanned" data-reason="<?php echo e($row->scanned_reason ?? ''); ?>" style="min-width:100px;">
                                                <option value="DONE"    <?php echo e($row->scanned == 'DONE'    ? 'selected' : ''); ?>>DONE</option>
                                                <option value="PENDING" <?php echo e($row->scanned == 'PENDING' ? 'selected' : ''); ?>>PENDING</option>
                                                <option value="ISSUE"   <?php echo e($row->scanned == 'ISSUE'   ? 'selected' : ''); ?>>ISSUE</option>
                                            </select>
                                        <?php else: ?>
                                            <?php echo e($row->scanned); ?>

                                        <?php endif; ?>


                                    </td>

                                    <!-- Deskewed -->
                                    <td>

                                        <?php if(auth()->user()->group == 'main' || auth()->user()->group == 'group2'  ): ?>
                                                <select class="form-control status-select" data-id="<?php echo e($row->id); ?>" data-field="deskewed" data-reason="<?php echo e($row->deskewed_reason ?? ''); ?>" style="min-width:100px;">
                                                    <?php if($row->scanned == 'DONE'): ?>
                                                        <option value="DONE"     <?php echo e($row->deskewed == 'DONE'     ? 'selected' : ''); ?>>DONE</option>
                                                        <option value="ISSUE"   <?php echo e($row->deskewed == 'ISSUE'   ? 'selected' : ''); ?>>ISSUE</option>
                                                        <option value="PENDING" <?php echo e($row->deskewed == 'PENDING' ? 'selected' : ''); ?>>PENDING</option>
                                                    <?php else: ?>
                                                        <option value="PENDING" <?php echo e($row->deskewed == 'PENDING' ? 'selected' : ''); ?>>PENDING</option>
                                                    <?php endif; ?>
                                                </select>
                                            <?php else: ?>
                                            <?php echo e($row->deskewed); ?>

                                            <?php endif; ?>

                                    </td>

                                    <!-- Converted -->
                                    <td>
                                        <?php if(auth()->user()->group == 'main' || auth()->user()->group == 'group2'  ): ?>
                                            <select class="form-control status-select" data-id="<?php echo e($row->id); ?>" data-field="converted" data-reason="<?php echo e($row->converted_reason ?? ''); ?>" style="min-width:100px;">
                                                <?php if($row->deskewed == 'DONE'): ?>
                                                    <option value="DONE"     <?php echo e($row->converted == 'DONE'     ? 'selected' : ''); ?>>DONE</option>
                                                    <option value="ISSUE"   <?php echo e($row->converted == 'ISSUE'   ? 'selected' : ''); ?>>ISSUE</option>
                                                    <option value="PENDING" <?php echo e($row->converted == 'PENDING' ? 'selected' : ''); ?>>PENDING</option>
                                                <?php else: ?>
                                                    <option value="PENDING" <?php echo e($row->converted == 'PENDING' ? 'selected' : ''); ?>>PENDING</option>
                                                <?php endif; ?>
                                            </select>
                                        <?php else: ?>
                                            <?php echo e($row->converted); ?>

                                        <?php endif; ?>

                                    </td>

                                    <!-- QA Converted File -->
                                    <td>
                                        <?php if(auth()->user()->group == 'main' || auth()->user()->group == 'group3'  ): ?>
                                            <select class="form-control status-select" data-id="<?php echo e($row->id); ?>" data-field="qa_converted_file" data-reason="<?php echo e($row->qa_converted_file_reason ?? ''); ?>" style="min-width:130px;">
                                                <?php if($row->converted == 'DONE'): ?>
                                                    <option value="DONE"         <?php echo e($row->qa_converted_file == 'DONE'         ? 'selected' : ''); ?>>DONE</option>
                                                    <option value="DISCREPANCY" <?php echo e($row->qa_converted_file == 'DISCREPANCY' ? 'selected' : ''); ?>>DISCREPANCY</option>
                                                    <option value="PENDING"     <?php echo e($row->qa_converted_file == 'PENDING'     ? 'selected' : ''); ?>>PENDING</option>
                                                <?php else: ?>
                                                    <option value="PENDING"     <?php echo e($row->qa_converted_file == 'PENDING'     ? 'selected' : ''); ?>>PENDING</option>
                                                <?php endif; ?>
                                            </select>
                                        <?php else: ?>
                                            <?php echo e($row->qa_converted_file); ?>

                                        <?php endif; ?>

                                    </td>

                                    <!-- Uploaded -->
                                    <td>
                                        <?php if(auth()->user()->group == 'main' || auth()->user()->group == 'group3'  ): ?>
                                            <select class="form-control status-select" data-id="<?php echo e($row->id); ?>" data-field="uploaded" data-reason="<?php echo e($row->uploaded_reason ?? ''); ?>" style="min-width:100px;">
                                                <?php if($row->qa_converted_file == 'DONE'): ?>
                                                    <option value="DONE"     <?php echo e($row->uploaded == 'DONE'     ? 'selected' : ''); ?>>DONE</option>
                                                    <option value="PENDING" <?php echo e($row->uploaded == 'PENDING' ? 'selected' : ''); ?>>PENDING</option>
                                                <?php else: ?>
                                                    <option value="PENDING" <?php echo e($row->uploaded == 'PENDING' ? 'selected' : ''); ?>>PENDING</option>
                                                <?php endif; ?>
                                            </select>
                                        <?php else: ?>
                                            <?php echo e($row->uploaded); ?>

                                        <?php endif; ?>

                                    </td>

                                    <td><?php echo e($row->added_by); ?></td>
                                    <td><?php echo e($row->created_at); ?></td>
                                    <?php if(auth()->user()->group == 'main' || auth()->user()->group == 'group1'  ): ?>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('blockcode-information.edit', $row->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <button type="button"
                                                        class="btn btn-danger btn-sm delete-btn"
                                                        data-id="<?php echo e($row->id); ?>"
                                                        data-blockcode="<?php echo e($row->blockcode); ?>"
                                                        data-url="<?php echo e(route('blockcode-information.destroy', $row->id)); ?>">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <td>
                                        <?php if($row->scanned == 'DONE' && $row->deskewed == 'DONE'  && $row->converted == 'DONE' ): ?>
                                            <a class="btn btn-primary" href="<?php echo e(route('voterlist-blockcodes.create')); ?>" style="float: right">import</a>
                                        <?php else: ?>
                                            Not Ready for Import
                                        <?php endif; ?>
                                    </td>

                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Load DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .status-select {
        border: none;
        border-radius: 0px;
        padding: 5px 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        outline: none;
        appearance: auto;
    }

    /* Scanned styles */
    .status-select[data-field="scanned"].val-DONE    { background:#d4edda; color:#155724; }
    .status-select[data-field="scanned"].val-PENDING { background:#fff3cd; color:#856404; }
    .status-select[data-field="scanned"].val-ISSUE   { background:#f8d7da; color:#721c24; }

    /* Deskewed styles */
    .status-select[data-field="deskewed"].val-DONE     { background:#d4edda; color:#155724; }
    .status-select[data-field="deskewed"].val-ISSUE   { background:#fff3cd; color:#856404; }
    .status-select[data-field="deskewed"].val-PENDING { background:#e2e3e5; color:#383d41; }

    /* Converted styles */
    .status-select[data-field="converted"].val-DONE     { background:#d4edda; color:#155724; }
    .status-select[data-field="converted"].val-ISSUE   { background:#fff3cd; color:#856404; }
    .status-select[data-field="converted"].val-PENDING { background:#e2e3e5; color:#383d41; }

    /* QA Converted File styles */
    .status-select[data-field="qa_converted_file"].val-DONE         { background:#d4edda; color:#155724; }
    .status-select[data-field="qa_converted_file"].val-DISCREPANCY { background:#fff3cd; color:#856404; }
    .status-select[data-field="qa_converted_file"].val-PENDING     { background:#e2e3e5; color:#383d41; }

    /* Uploaded styles */
    .status-select[data-field="uploaded"].val-DONE     { background:#d4edda; color:#155724; }
    .status-select[data-field="uploaded"].val-PENDING { background:#e2e3e5; color:#383d41; }
</style>

<script>
    var updateStatusUrl = "<?php echo e(route('blockcode-information.update-status')); ?>";
    var csrfToken       = "<?php echo e(csrf_token()); ?>";
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let originalValues = new Map();

        function applyColor(sel) {
            sel.className = sel.className.replace(/\bval-\S+/g, '').trim();
            sel.classList.add('val-' + sel.value);
        }

        async function showReasonPopup(field, currentValue, existingReason = '') {
            const fieldLabels = {
                'scanned': 'Scanned', 'deskewed': 'Deskewed',
                'converted': 'Converted', 'qa_converted_file': 'QA Converted File', 'uploaded': 'Uploaded'
            };

            var type = [];
            if (field == 'scanned') {
                type = ['Page Missing', 'Page Unsequence', 'Other'];
            }
            if (field == 'converted') {
                type = ['Page Entry', 'CNIC length Issue', 'Other'];
            }
            if (field == 'qa_converted_file') {
                type = ['Entry Missing', 'Page Missing', 'Page Unsequence', 'Other'];
            }

            const isDiscrepancy = currentValue === 'DISCREPANCY';
            const isIssue       = currentValue === 'ISSUE';
            if (!isDiscrepancy && !isIssue) return { confirmed: true, reason: null };

            // Build type options HTML
            const typeOptionsHtml = type.length
                ? type.map(t => `<option value="${t}">${t}</option>`).join('')
        : '';

            const result = await Swal.fire({
                    title: `${fieldLabels[field]} - ${currentValue}`,
                    html: `
                <div style="text-align:left;">
                    <p style="margin-bottom:10px;color:#dc3545;font-weight:bold;">
                        ${isDiscrepancy ? '⚠️ Please explain the discrepancy:' : '❌ Please provide details about the issue:'}
                    </p>
                    <select id="type-select" class="swal2-select" style="margin-bottom:10px;width:100%;">
                        <option value="">Select</option>
                        ${typeOptionsHtml}
                    </select>
                    <textarea id="reason-text" class="swal2-textarea"
                              placeholder="Enter detailed reason here..."
                              style="width:100%;min-height:120px;padding:10px;border:1px solid #ced4da;border-radius:4px;width:stretch;">${existingReason}</textarea>
                    <p style="margin-top:10px;font-size:12px;color:#6c757d;">Maximum 500 characters</p>
                </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Save & Continue',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    didOpen: () => {
                    const typeSelect = document.getElementById('type-select');
            const reasonText = document.getElementById('reason-text');

            let lastOptionValue = '';

            typeSelect.addEventListener('change', function () {
                if (!this.value) return;
                let current = reasonText.value;
                if (lastOptionValue && current.includes(lastOptionValue)) {
                    current = current.replace(lastOptionValue, this.value);
                } else {
                    current = current.trim()
                        ? current.trimEnd() + '\n' + this.value
                        : this.value;
                }
                reasonText.value = current;
                lastOptionValue = this.value;
            });
        },
            preConfirm: () => {
                const reason = document.getElementById('reason-text').value.trim();
                if (!reason) { Swal.showValidationMessage('Please enter a reason before saving.'); return false; }
                if (reason.length > 500) { Swal.showValidationMessage('Reason must be less than 500 characters.'); return false; }
                return reason;
            }
        });

            return result.isConfirmed ? { confirmed: true, reason: result.value } : { confirmed: false, reason: null };
        }

        // ─── Reinitialize dropdown options for a single row based on live data ───
        function reinitializeRowSelects(row, data) {
            const fields = ['scanned', 'deskewed', 'converted', 'qa_converted_file', 'uploaded'];

            const optionsMap = {
                        scanned: () => [
                        { v: 'DONE', l: 'DONE' }, { v: 'PENDING', l: 'PENDING' }, { v: 'ISSUE', l: 'ISSUE' }
                    ],
                deskewed: () => data.scanned === 'DONE'
                ? [{ v: 'DONE', l: 'DONE' }, { v: 'ISSUE', l: 'ISSUE' }, { v: 'PENDING', l: 'PENDING' }]
                : [{ v: 'PENDING', l: 'PENDING' }],
                converted: () => data.deskewed === 'DONE'
                ? [{ v: 'DONE', l: 'DONE' }, { v: 'ISSUE', l: 'ISSUE' }, { v: 'PENDING', l: 'PENDING' }]
                : [{ v: 'PENDING', l: 'PENDING' }],
                qa_converted_file: () => data.converted === 'DONE'
                ? [{ v: 'DONE', l: 'DONE' }, { v: 'DISCREPANCY', l: 'DISCREPANCY' }, { v: 'PENDING', l: 'PENDING' }]
                : [{ v: 'PENDING', l: 'PENDING' }],
                uploaded: () => data.qa_converted_file === 'DONE'
                ? [{ v: 'DONE', l: 'DONE' }, { v: 'PENDING', l: 'PENDING' }]
                : [{ v: 'PENDING', l: 'PENDING' }],
        };

            fields.forEach(field => {
                const sel = row.querySelector(`.status-select[data-field="${field}"]`);
            if (!sel) return;

            const currentVal = data[field] || 'PENDING';
            const options    = optionsMap[field]();

            sel.innerHTML = '';
            options.forEach(opt => {
                const o = document.createElement('option');
            o.value = opt.v;
            o.textContent = opt.l;
            o.selected = (opt.v === currentVal) ||
                (!options.find(x => x.v === currentVal) && opt.v === 'PENDING');
            sel.appendChild(o);
        });

            sel.dataset.reason = data[`${field}_reason`] || '';
            applyColor(sel);
            originalValues.set(sel, sel.value);
        });
        }

        // ─── Fetch fresh row data and reinitialize ───────────────────────────────
        async function refreshRow(rowElement, id) {
            try {
                const response = await fetch(`/blockcode-information/${id}/status-data`, {
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error('Failed to fetch row data');
                const data = await response.json();
                reinitializeRowSelects(rowElement, data);
            } catch (err) {
                console.error('Row refresh failed:', err);
            }
        }

        // ─── Init colors on page load ────────────────────────────────────────────
        document.querySelectorAll('.status-select').forEach(function (select) {
            applyColor(select);
            originalValues.set(select, select.value);
        });

        // ─── Handle status changes ───────────────────────────────────────────────
        document.querySelectorAll('.status-select').forEach(function (select) {
            select.addEventListener('change', async function () {
                const id             = this.dataset.id;
                const field          = this.dataset.field;
                const newValue       = this.value;
                const oldValue       = originalValues.get(this);
                const existingReason = this.dataset.reason || '';
                const row            = this.closest('tr');

                let reason = null;
                if (['ISSUE', 'DISCREPANCY'].includes(newValue)) {
                    const result = await showReasonPopup(field, newValue, existingReason);
                    if (!result.confirmed) {
                        this.value = oldValue;
                        applyColor(this);
                        return;
                    }
                    reason = result.reason;
                }

                applyColor(this);
                this.style.opacity = '0.6';

                try {
                    const updateData = { id, field, value: newValue, _token: csrfToken };
                    if (reason) updateData.reason = reason;

                    const response = await fetch(updateStatusUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify(updateData)
                    });
                    const data = await response.json();

                    if (data.success) {
                        this.style.opacity = '1';

                        Swal.fire({
                            icon: 'success', title: 'Updated!',
                            text: `${field.replace(/_/g, ' ').toUpperCase()} updated to ${newValue}`,
                            timer: 1500, showConfirmButton: false, toast: true, position: 'top-end'
                        });

                        await refreshRow(row, id);

                    } else {
                        throw new Error(data.message || 'Failed to update');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.value = oldValue;
                    applyColor(this);
                    this.style.opacity = '1';

                    Swal.fire({
                        icon: 'error', title: 'Update Failed',
                        text: error.message || 'Network error. Please try again.',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && document.getElementById('votersTable')) {
            jQuery(document).ready(function($) {
                $('#votersTable').DataTable({
                    pageLength: 50,
                    lengthMenu: [50, 100, 250, 500],
                    ordering: true,
                    searching: true,
                    scrollX: true,
                    destroy: true,
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries"
                    }
                });
            });
        }

        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', async function(e) {
            e.preventDefault();
            const blockcode  = this.dataset.blockcode;
            const deleteUrl  = this.dataset.url;

            const firstResult = await Swal.fire({
                title: 'Delete Blockcode',
                html: `Are you sure you want to delete <strong>${blockcode}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'No, cancel',
                allowOutsideClick: false
            });

            if (firstResult.isConfirmed) {
                const secondResult = await Swal.fire({
                        title: '⚠️ Permanent Deletion Warning',
                        html: `
                    <div style="text-align: left;">
                        <p style="color: #dc3545; font-weight: bold; margin-bottom: 10px;">
                            ⚠️ THIS ACTION CANNOT BE UNDONE!
                        </p>
                        <p>Are you <strong>ABSOLUTELY SURE</strong> you want to permanently delete <strong style="color: #dc3545;">${blockcode}</strong>?</p>
                        <p style="margin-top: 10px; font-size: 14px; color: #6c757d;">
                            Once deleted, all data associated with this blockcode will be lost forever.
                        </p>
                    </div>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete permanently',
                        cancelButtonText: 'No, keep it',
                        allowOutsideClick: false,
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                        try {
                            const response = await fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                }
                            });
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to delete');
                }
                return await response.json();
            } catch (error) {
                    Swal.showValidationMessage(`Delete failed: ${error.message}`);
                    throw error;
                }
            }
            });

                if (secondResult.isConfirmed) {
                    Swal.fire({
                        title: 'Deleted!',
                        html: `Blockcode <strong>${blockcode}</strong> has been permanently deleted.`,
                        icon: 'success',
                        timer: 2500,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        const row = button.closest('tr');
                    if (row) {
                        row.style.transition = 'all 0.5s ease';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                        if (typeof $('#votersTable').DataTable !== 'undefined') {
                            $('#votersTable').DataTable().draw();
                        }
                    }, 500);
                    } else {
                        window.location.reload();
                    }
                });
                } else if (secondResult.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Cancelled',
                        text: `Blockcode ${blockcode} was not deleted.`,
                        icon: 'info',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            } else if (firstResult.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Deletion process cancelled.',
                    icon: 'info',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.verifier_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/verifier/blockcode_information/listing.blade.php ENDPATH**/ ?>