<?php $__env->startSection('title', " :: Blockcode Users"); ?>

<?php $__env->startSection('content'); ?>
    <script src="<?php echo e(asset('public/assets/js/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/datatables_basic.js')); ?>"></script>
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">Blockcode Users</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">Blockcode Users</span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <div class="content">
        <!-- Default alerts -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <?php if(session('success')): ?>
                        <div class="col-lg-12">
                            <div class="alert alert-success">  <?php echo e(session('success')); ?>

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            </div>
                        </div>
                    <?php endif; ?> <?php if(session('error')): ?>
                        <div class="col-lg-12">
                            <div class="alert alert-danger">  <?php echo e(session('error')); ?>

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-6">
                        <h5 class="card-title">Blockcode Users</h5>
                    </div>
                    
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table  datatable-basic">
                                <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>BLOCK CODE</th>
                                    <th>Eloctoral Area Name</th>
                                    <th>Circle Name</th>
                                    <th>Village / City</th>
                                    <th>Total Voters</th>
                                    <th>Voter Count</th>
                                    <th>Tester</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody id="blockcodes-tbody">
                                <?php $__currentLoopData = $blockcodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr  class="code-clicktr"  id="blocktr-<?php echo e($code->blockcode); ?>" blockcode="<?php echo e($code->blockcode); ?>" >
                                        <td><?php echo e($key+1); ?></td>
                                        <td>
                                            <a href="<?php echo e(url('admin/blockcodes/voters/'.$code->blockcode)); ?>" class=""> <?php echo e($code->blockcode); ?></a>

                                        </td>
                                        <td><?php echo e($code->eloctoral_area_name); ?></td>
                                        <td><?php echo e($code->circle_name); ?></td>
                                        <td><?php echo e($code->village_city); ?></td>
                                        <td><?php echo e($code->total_voters); ?></td>
                                        <td><?php echo e($code->count_voters); ?> / <?php echo e($code->count_updated); ?>  </td>
                                        <td>
                                            <button
                                                    class="btn btn-sm status-btn <?php echo e($code->tested == 1 ? 'btn-primary' : 'btn-danger'); ?>"
                                                    data-id="<?php echo e($code->id); ?>"
                                                    data-status="<?php echo e($code->tested); ?>">
                                                <?php echo e($code->tested == 1 ? 'Tested' : 'Pending'); ?>

                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm  <?php echo e($code->completed == 1 ? 'btn-success' : 'btn-warning'); ?>">
                                                <?php echo e($code->completed == 1 ? 'Completed' : 'Pending'); ?>

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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\voterlist-system\resources\views/admin/block_codes/show.blade.php ENDPATH**/ ?>