
<?php $__env->startSection('title', " :: Add Blockcode User"); ?>

<?php $__env->startSection('content'); ?>


    <script src="<?php echo e(asset('admin/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/global_assets/js/plugins/forms/styling/uniform.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/app.js')); ?>"></script>



     <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><a href="<?php echo e(url()->previous()); ?>"><i class="icon-arrow-left52 mr-2"></i> </a><span class="font-weight-semibold">Blockcode Users</span>  </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="<?php echo e(route('blockcodes.index')); ?>" class="breadcrumb-item"> Blockcode Users </a>
                    <span class="breadcrumb-item active">Add </span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title"> <a href="<?php echo e(url()->previous()); ?>" ><i class="icon-reply"></i></a></h5>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('blockcodes.store')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <fieldset class="mb-3">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Add New Blockcode User</legend>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select User</label>
                                    <select name="user" id="user" class="form-control">
                                        <option value="">Select user</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" <?php if($user->id == old('user')): ?> selected <?php endif; ?>><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if($errors->has('user')): ?><div class="alert alert-danger">  <?php echo e($errors->first('user')); ?>

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                         <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="col-form-label col-lg-12 pb-0">Select Block Codes</label>
                                        <select name="block_codes[]" id="block_codes" class="form-control select"  data-fouc multiple="multiple">
                                            <?php $__currentLoopData = $blockcodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blockcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($blockcode->blockcode); ?>"><?php echo e($blockcode->blockcode); ?> </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                            <?php if($errors->has('block_codes')): ?>
                                                <div class="alert alert-danger">  <?php echo e('The block codes field is required.'); ?>

                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                </div>
                                        <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('jsfiles'); ?>
    <script>
        $(document).ready(function() {
//            $('#block_codes').multiselect({
//                includeSelectAllOption: true,
//                enableFiltering: true,
//                enableCaseInsensitiveFiltering: true,
//                buttonWidth: '100%',
//                maxHeight: 300
//            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\voterlist-system\resources\views/admin/block_codes/create.blade.php ENDPATH**/ ?>