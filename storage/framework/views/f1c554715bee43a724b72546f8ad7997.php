<?php $__env->startSection('title', 'APP & WEB Users :: users Listing'); ?>

<?php $__env->startSection('content'); ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">APP & WEB Users</span> - Listing</h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="<?php echo e(route('backend_dashboard')); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="<?php echo e(url('backend/app-web-users')); ?>" class="breadcrumb-item">Users </a>

                    <span class="breadcrumb-item active">users listing</span>
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
                        <h5 class="card-title">users listing</h5>
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
                                    <th>#</th>
                                    <th> blockcode</th>
                                    <th> silsila_no</th>
                                    <th> gharana_no</th>
                                    <th>name</th>
                                    <th>father_name</th>
                                    <th>cnic_number</th>
                                    <th>age</th>
                                    <th>polling_station</th>
                                    <th>searched_at</th>
                                </tr>
                                </thead>
                                <tbody>
                                  <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($i+1); ?></td>
                                        <td><?php echo e($user->blockcode); ?></td>
                                        <td><?php echo e($user->silsila_no); ?></td>
                                        <td><?php echo e($user->gharana_no); ?></td>
                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->father_name); ?></td>
                                        <td><?php echo e($user->cnic_number); ?></td>
                                        <td><?php echo e($user->age); ?></td>
                                        <td><?php echo e($user->polling_station); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($user->searched_at)->format('Y-m-d H:i:s')); ?></td>

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
    <script>
        $(document).ready(function () {
            $('.datatable-basic').DataTable({
                "pageLength": 10,
                "ordering": true,
                "searching": true
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-online\resources\views/backend/app_web_users/voters_listing.blade.php ENDPATH**/ ?>