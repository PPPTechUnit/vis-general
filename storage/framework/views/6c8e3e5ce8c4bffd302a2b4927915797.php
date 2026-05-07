<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <script src="<?php echo e(asset('public/assets/js/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/datatables_basic.js')); ?>"></script>

    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">Dashboard</span> </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <span class="breadcrumb-item active">Dashboard</span>
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
                    <div class="col-lg-12">
                        <h5 class="card-title">Dashboard</h5>
                    </div>

                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>