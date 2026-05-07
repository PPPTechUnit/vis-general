
<?php $__env->startSection('title', 'Rescan Blockcode'); ?>

<?php $__env->startSection('content'); ?>

    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">RE_SCANED BLOCKCODE</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12">
                    <div class="table-responsive m-b-30">
                        <table id="votersTable" class="table table-borderless table-striped table-earning">
                            <thead>
                            <tr>
                                <th>S.No#</th>
                                <th>Block Code</th>
                                <th>Scanned Done By</th>

                                <th>Imported  By</th>
                                <th>Rescanned Time</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1; ?>
                            <?php $__currentLoopData = $voters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($i); ?></td>
                                    <td><?php echo e($row->blockcode); ?> </td>
                                    <td><?php echo e($row->rescened_done_by); ?></td>
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
<?php echo $__env->make('layouts.verifier_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/verifier/voterlist_blockcodes/rescan_blockcode.blade.php ENDPATH**/ ?>