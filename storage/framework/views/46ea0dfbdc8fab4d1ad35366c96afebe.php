
<?php $__env->startSection('title', 'Blockcode Detail'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .badge{
            color: #000 !important;
        }
    </style>
    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">Blockcode Information Detail</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <a href="<?php echo e(route('blockcode-information.index')); ?>" class="btn btn-secondary btn-sm">← Back</a>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Basic Info -->
                            <h5 class="mb-3 text-primary">Basic Information</h5>
                            <div class="row mb-4">
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Block Code</label>
                                    <div class="fw-bold"><?php echo e($record->blockcode ?? '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Committee</label>
                                    <div class="fw-bold"><?php echo e($record->committee ?? '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Village / City</label>
                                    <div class="fw-bold"><?php echo e($record->village_city ?? '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Electoral Area</label>
                                    <div class="fw-bold"><?php echo e($record->eloctoral_area_name ?? '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Circle</label>
                                    <div class="fw-bold"><?php echo e($record->circle_name ?? '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Book Number</label>
                                    <div class="fw-bold"><?php echo e($record->book_number ?? '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Pages</label>
                                    <div class="fw-bold"><?php echo e($record->pages ?? '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Missing Pages</label>
                                    <div class="fw-bold"><?php echo e($record->missing_page ?? '-'); ?></div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Male Voters</label>
                                    <div class="fw-bold text-primary" style="font-size:20px;"><?php echo e(number_format($record->male_voters)); ?></div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Female Voters</label>
                                    <div class="fw-bold text-danger" style="font-size:20px;"><?php echo e(number_format($record->female_voters)); ?></div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Total Voters</label>
                                    <div class="fw-bold text-success" style="font-size:20px;"><?php echo e(number_format($record->total_voters)); ?></div>
                                </div>
                            </div>

                            <hr>

                            <!-- Location Info -->
                            <h5 class="mb-3 text-primary">Location Information</h5>
                            <div class="row mb-4">

                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Division</label>
                                    <div class="fw-bold"><?php echo e($record->division_name ?: '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">District</label>
                                    <div class="fw-bold"><?php echo e($record->district_name ?: '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Taluka</label>
                                    <div class="fw-bold"><?php echo e($record->taluka_name ?: '-'); ?></div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="text-muted small">Provincial Assembly</label>
                                    <div class="fw-bold"><?php echo e($record->provincial_assembly_name ?: '-'); ?></div>
                                </div>

                            </div>

                            <hr>



                            <!-- Status Info -->
                            <h5 class="mb-3 text-primary">Status Information</h5>
                            <div class="row mb-4">
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Condition</label>
                                    <div>
                                        <?php
                                            $conditionClass = $record->condition == 'GOOD' ? 'success' : 'warning';
                                        ?>
                                        <span class="badge badge-<?php echo e($conditionClass); ?>"><?php echo e($record->condition ?? '-'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Scanned</label>
                                    <div>
                                        <span class="badge badge-<?php echo e($record->scanned == 'DONE' ? 'primary' : 'danger'); ?>">
                                            <?php echo e($record->scanned ?? '-'); ?>

                                        </span>
                                        <?php if($record->scanned == 'ISSUE'): ?>
                                            <br> <?php echo e($record->scanned_issue_reason); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Deskewed</label>
                                    <div>
                                        <span class="badge badge-<?php echo e($record->deskewed == 'YES' ? 'primary' : 'danger'); ?>">
                                            <?php echo e($record->deskewed ?? '-'); ?>

                                        </span>
                                        <?php if($record->deskewed == 'ISSUE'): ?>
                                            <br>
                                            <?php echo e($record->deskewed_issue_reason); ?>

                                        <?php endif; ?>

                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Converted</label>
                                    <div>
                                        <?php
                                            $convertedClass = $record->converted == 'YES' ? 'primary' : ($record->converted == 'ISSUE' ? 'warning' : 'danger');
                                        ?>
                                        <span class="badge badge-<?php echo e($convertedClass); ?>"><?php echo e($record->converted ?? '-'); ?></span>

                                        <?php if($record->converted == 'ISSUE'): ?>
                                            <br>
                                            <?php echo e($record->converted_issue_reason); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">QA Converted File</label>
                                    <div>
                                        <?php
                                            $qaClass = $record->qa_converted_file == 'YES' ? 'primary' : ($record->qa_converted_file == 'DISCREPANCY' ? 'warning' : 'danger');
                                        ?>
                                        <span class="badge badge-<?php echo e($qaClass); ?>"><?php echo e($record->qa_converted_file ?? '-'); ?></span>
                                        <?php if($record->qa_converted_file == 'DISCREPANCY'): ?>
                                            <br>
                                            <?php echo e($record->qa_converted_file_issue_reason); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="text-muted small">Uploaded</label>
                                    <div>
                                        <span class="badge badge-<?php echo e($record->uploaded == 'YES' ? 'success' : 'danger'); ?>">
                                            <?php echo e($record->uploaded ?? '-'); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Meta Info -->
                            <h5 class="mb-3 text-primary">Other Information</h5>
                            <div class="row mb-4">
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Added By</label>
                                    <div class="fw-bold"><?php echo e($record->added_by ?? '-'); ?></div>
                                </div>


                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Created At</label>
                                    <div class="fw-bold"><?php echo e($record->created_at ?? '-'); ?></div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="text-muted small">Updated At</label>
                                    <div class="fw-bold"><?php echo e($record->updated_at ?? '-'); ?></div>
                                </div>
                            </div>

                            <hr>

                            <!-- History -->
                            <h5 class="mb-3 text-primary">History Log</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>Old Value</th>
                                        <th>New Value</th>
                                        <th>Done By</th>
                                        <th>Date & Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $content = json_decode($history['content'], true);
                                            $old     = $content['old'] ?? [];
                                            $new     = $content['new'] ?? [];
                                        ?>

                                        <?php if(empty($old)): ?>
                                            
                                            <tr>
                                                <td><?php echo e($i + 1); ?></td>
                                                <td><span class=" badge-success">Created</span></td>
                                                <td>-</td>
                                                <td>
                                                    <?php $__currentLoopData = $new; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div><strong><?php echo e(ucwords(str_replace('_', ' ', $key))); ?>:</strong> <?php echo e($val ?: '-'); ?></div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                                <td><?php echo e($history['added_by'] ?? '-'); ?></td>
                                                <td><?php echo e($history['created_at']); ?></td>
                                            </tr>
                                        <?php else: ?>
                                            <?php
                                                $content = json_decode($history['content'], true);
                                                $old     = $content['old'] ?? [];
                                                $new     = $content['new'] ?? [];

                                            //echo "<pre>";
                                           // print_r($old);
                                           /// print_r($new);
                                            //  echo "</pre>";
                                            ?>
                                            <tr>
                                                <td><?php echo e($i + 1); ?></td>
                                                <td><span class=" badge-success">Updated</span></td>
                                                <td>
                                                    <?php $__currentLoopData = $old; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div><strong><?php echo e(ucwords(str_replace('_', ' ', $key))); ?>:</strong> <?php echo e($val ?: '-'); ?></div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                                <td>
                                                    <?php $__currentLoopData = $new; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div><strong><?php echo e(ucwords(str_replace('_', ' ', $key))); ?>:</strong> <?php echo e($val ?: '-'); ?></div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                                <td><?php echo e($history['added_by'] ?? '-'); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($history['created_at'])->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s')); ?></td>
                                            </tr>



                                        <?php endif; ?>
                                            



                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No history found.</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.verifier_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/verifier/blockcode_information/show.blade.php ENDPATH**/ ?>