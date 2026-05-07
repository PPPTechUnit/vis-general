
<?php $__env->startSection('title', 'Import Voterlist'); ?>

<?php $__env->startSection('content'); ?>

    <section style="padding: 40px 10px; background: #fff">
        <h4 class="p-l-20">IMPORT VOTERLIST BLOCKCODE</h4>
        <hr>
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12">
                    <form action="<?php echo e(url('verifier/voterlist-blockcodes-import')); ?>" method="POST" enctype="multipart/form-data">
                        <!-- CSRF (Laravel) -->
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                        <div class="mb-3">
                            <label for="csvFile" class="form-label">Upload Blockcode File</label>
                            <input
                                    class="form-control"
                                    type="file"
                                    id="csvFile"
                                    name="csv_file"
                                    accept=".csv"
                                    required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('csvFile').addEventListener('change', function () {
        const file = this.files[0];
        if (file && !file.name.endsWith('.csv')) {
            alert('Only CSV files are allowed!');
            this.value = '';
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.verifier_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/verifier/voterlist_blockcodes/create.blade.php ENDPATH**/ ?>