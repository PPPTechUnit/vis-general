
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
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Blocode</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                  <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($i+1); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('blockcodes.show',$user['user_id'])); ?>" class=""> <?php echo e($user['user']['name']); ?> (<?php echo e($user['user']['email']); ?>)</a>

                                            </td>
                                        
                                        
                                        
                                        
                                        <td><?php echo e($user['block_codes']); ?></td>  <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="<?php echo e(route('blockcodes.show',$user['user_id'])); ?>" class="dropdown-item"> View</a>
                                                    

                                                </div>
                                            </div>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/admin/block_codes/listing.blade.php ENDPATH**/ ?>