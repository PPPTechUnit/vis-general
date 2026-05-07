
<?php $__env->startSection('title', 'Voterlist'); ?>

<?php $__env->startSection('content'); ?>

    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">LIST VOTERLIST BLOCKCODE</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">

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
                                <th>Blockcode Male</th>
                                <th>Blockcode Female</th>
                                <th>Blockcode Total</th>
                                <th>Voter Male</th>
                                <th>Voter Female</th>
                                <th>Total</th>
                                <th>created_by</th>
                                <th>created_at</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1; ?>
                            <?php $__currentLoopData = $voters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($i); ?></td>
                                    <td><?php echo e($row->blockcode); ?> </td>
                                    <td><?php echo e($blockcodes[ $row->blockcode]->male_voters ?? 0); ?></td>
                                    <td><?php echo e($blockcodes[ $row->blockcode]->female_voters ?? 0); ?></td>
                                    <td><?php echo e($blockcodes[ $row->blockcode]->total_voters ?? 0); ?></td>
                                    <td><?php echo e($row->male_count); ?></td>
                                    <td><?php echo e($row->female_count); ?></td>
                                    <td><?php echo e($row->total_voters); ?></td>
                                    <td><?php echo e($row->created_by); ?></td>
                                    <td><?php echo e($row->created_at); ?></td>

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

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.verifier_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/verifier/voterlist_blockcodes/voter_list_listing.blade.php ENDPATH**/ ?>