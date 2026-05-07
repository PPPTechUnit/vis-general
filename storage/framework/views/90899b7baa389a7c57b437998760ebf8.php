
<?php $__env->startSection('title', " :: Blockcode Users"); ?>

<?php $__env->startSection('content'); ?>
    <script src="<?php echo e(asset('public/assets/js/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/datatables_basic.js')); ?>"></script>


    <style>
        td, th{
            padding: 0 !important;
            margin: 0 !important;
        }
    </style>

    <div class="page-header page-header-light"  style="padding: 5px;">


        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">Blockcode Users
                     <p class="text-center" style="color: #000;    display: contents;"> <==> <b>Blockcode Info</b># (<?php echo e($block_info['blockcode']); ?>), Total (<?php echo e($block_info['total_voters']); ?>),
                            <b>Voterlist Info#</b>
                         <?php $total_count_voters = 0; ?>

                         <?php $__currentLoopData = $voters_count; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gender_key => $vot_count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <span style="text-transform: capitalize">
                        <?php echo e(strtolower($gender_key)); ?>: <?php echo e($vot_count); ?>

                    </span><?php if(!$loop->last): ?>, <?php endif; ?>
                             <?php $total_count_voters += $vot_count; ?>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         <strong>Total: <?php echo e($total_count_voters); ?></strong>
                        </p>
                    </span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <div class="content">
        <!-- Default alerts -->
        <div class="card">

            <div class="card-body" style="padding: 5px;">
                <div class="row">

                    <div class="col-lg-12 p-1" style="text-align: right">

                        <button
                                class="btn btn-sm status-btn <?php echo e($block_info['completed'] == 1 ? 'btn-success' : 'btn-warning'); ?>"
                                data-id="<?php echo e($block_info['id']); ?>"
                                data-status="<?php echo e($block_info['tested']); ?>">
                            <?php echo e($block_info['tested'] == 1 ? 'Tested' : 'Pending'); ?>

                        </button>
                    </div>
                    <div class="col-lg-12 blockcode-table-padding table-wrapper-scroll-y my-custom-scrollbar">


                        <div id="accordion">

                            <div class="card">
                                <div class="card-header" style="padding: 7px 15px;">
                                    <a class="card-link" data-toggle="collapse" href="#collapseOne">
                                        PDF VIEW
                                    </a>
                                </div>
                                <div id="collapseOne" class="collapse show">
                                    <div class="card-body"  style="padding: 5px;">
                                        <div>
                                            <iframe src="<?php echo e(asset('blockcode_files/'.$block_info['blockcode'].'.pdf')); ?>#zoom=100" style="height:400px;width:100%;" title="PDF Preview"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header"  style="padding: 7px 15px;">
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
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('jsfiles'); ?>
    <script src="<?php echo e(asset('frontend/lib/jquery/jquery.min.js')); ?>"></script>

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
                        url: '/update-testing-status',
                        method: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            id: id,
                            tested: newStatus
                        },
                        success: function (res) {
                            if (res.success) {
                                btn.data('status', newStatus);
                                btn.text(newStatus === 1 ? 'Tested' : 'Pending');
                                btn.removeClass('btn-success btn-warning')
                                    .addClass(newStatus === 1 ? 'btn-success' : 'btn-warning');

                                Swal.fire("Tested!", "Status updated successfully.", "success");
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
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\voterlist-system\resources\views/admin/block_codes/voter_listing_pdf.blade.php ENDPATH**/ ?>