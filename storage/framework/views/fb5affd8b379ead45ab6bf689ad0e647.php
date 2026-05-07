<?php $__env->startSection('title', " :: User assigned blockcodes"); ?>

<?php $__env->startSection('content'); ?>

    <script src="<?php echo e(asset('public/assets/js/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/assets/js/datatables_basic.js')); ?>"></script>
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4> <span class="font-weight-semibold">User assigned blockcodes</span></h4>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="<?php echo e(route('backend_dashboard')); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
                    <a href="<?php echo e(route('blockcodes.index')); ?>" class="breadcrumb-item">User assigned blockcodes </a>
                    <span class="breadcrumb-item active">User assigned blockcodes listing</span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title"> <a href="<?php echo e(route('blockcodes.index')); ?>" ><i class="icon-reply"></i></a></h5>
            </div>
        </div>
        <div class="card">
            <?php if(session('success')): ?>
                <div class="col-lg-12" style="    margin-top: 15px;">
                    <div class="alert alert-success">  <?php echo e(session('success')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    </div>
                </div>
            <?php endif; ?> <?php if(session('error')): ?>
                <div class="col-lg-12" style="    margin-top: 15px;">
                    <div class="alert alert-danger">  <?php echo e(session('error')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-12">
                        <h5 class="card-title">Blockcodes listing</h5>
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
                                    <th>District</th>
                                    <th>Tehsil</th>
                                    <th>Blockckode</th>
                                    <th>Area Name</th>
                                    <th>Male Voters</th>
                                    <th>Female Voters</th>
                                    <th>Record added</th>
                                    <th>Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                  <?php $__currentLoopData = $blockcodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($i+1); ?></td>

                                        <td><?php echo e(isset($block['blockcode_info']['district_name'])?$block['blockcode_info']['district_name']:""); ?></td>
                                        <td><?php echo e(isset($block['blockcode_info']['taluka_name'])?$block['blockcode_info']['taluka_name']:""); ?></td>
                                        <td><?php echo e($block['blockcode']); ?></td>
                                        <td><?php echo e(isset($block['blockcode_info']['eloctoral_area_name'])?$block['blockcode_info']['eloctoral_area_name']:""); ?></td>
                                        <td><?php echo e(isset($block['blockcode_info']['male_voters'])?$block['blockcode_info']['male_voters']:""); ?></td>
                                        <td><?php echo e(isset($block['blockcode_info']['female_voters'])?$block['blockcode_info']['female_voters']:""); ?></td>
                                        <td><?php echo e(isset($block['count_record'])?$block['count_record']:""); ?></td>

                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="<?php echo e(url('backend/blockcodes/view-records',$block['blockcode'])); ?>" class="dropdown-item"> View</a>
                                                    <a href="<?php echo e(url('backend/blockcodes/edit-records',$block['blockcode'])); ?>" class="dropdown-item"> Edit</a>
                                                    <a href="<?php echo e(url('backend/blockcodes/view-records/delete',$block['blockcode'])); ?>" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"> Delete</a>
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\voterlist-system\resources\views/admin/block_codes/listing_record.blade.php ENDPATH**/ ?>