
<?php $__env->startSection('title', 'Voterlist Blockcode'); ?>

<?php $__env->startSection('content'); ?>

    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">LIST VOTERLIST BLOCKCODE</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 mb-5" >
                    <a class="btn btn-primary" href="<?php echo e(route('voterlist-blockcodes.create')); ?>" style="float: right">Add New Blockcode</a>
                </div>
                <div class="col-lg-12">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">  <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?> <?php if(session('error')): ?>
                        <div class="alert alert-danger">  <?php echo e(session('error')); ?>

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
                                <th>Imported By</th>
                                <th>Imported Time</th>

                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1; ?>
                            <?php if(sizeof($voters)>0): ?>
                                <?php $__currentLoopData = $voters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($i); ?></td>
                                        <td><?php echo e($row->blockcode); ?> </td>
                                        <td><?php echo e($row->created_by); ?></td>
                                        <td><?php echo e($row->created_at); ?></td>
                                        <td> <a href="<?php echo e(route('voterlist-blockcodes.show',$row->id)); ?>" class="btn btn-success"> PREVIEW</a> </td>

                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.verifier_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/verifier/voterlist_blockcodes/listing.blade.php ENDPATH**/ ?>