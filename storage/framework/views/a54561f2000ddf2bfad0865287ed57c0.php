<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>



    <div class="container-fluid py-4">

        
        <div class="row">
            <div class="col-12">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">Blockcode</h6>
                        <div class="row">
                            <div class="col-3 col-md-3">
                                <div class="card border-0 bg-success-subtle">
                                    <div class="card-body">
                                        <div class="small fw-semibold mb-1">Total blockcodes</div>
                                        <div class="fs-4 fw-semibold stat-total"><?php echo e(number_format($stats['total'])); ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php $__currentLoopData = [
                                ['label'=>'Condition Good',    'key'=>'GOOD',    'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Condition Average', 'key'=>'AVERAGE', 'bg'=>'warning-subtle',   'text'=>'warning'],
                                ['label'=>'Condition Bad',     'key'=>'BAD',     'bg'=>'danger-subtle',    'text'=>'danger'],
                               // ['label'=>'Not set', 'key'=>'null',    'bg'=>'secondary-subtle', 'text'=>'secondary'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-md-3">
                                    <div class="card border-0 bg-<?php echo e($item['bg']); ?>">
                                        <div class="card-body">
                                            <div class="small text-<?php echo e($item['text']); ?> fw-semibold mb-1"><?php echo e($item['label']); ?></div>
                                            <div class="fs-4 fw-semibold stat-cond-<?php echo e($item['key']); ?>"><?php echo e(number_format($stats['cond'][$item['key']])); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(auth()->user()->group == 'main'  ): ?>


        
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">Scanned</h6>
                        <div class="row g-3 mb-3">
                            <?php $__currentLoopData = [
                                ['label'=>'Done',    'key'=>'DONE',    'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Pending', 'key'=>'PENDING', 'bg'=>'secondary-subtle', 'text'=>'secondary'],
                                ['label'=>'Issue',   'key'=>'ISSUE',   'bg'=>'danger-subtle',    'text'=>'danger'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-md-4">
                                    <div class="card border-0 bg-<?php echo e($item['bg']); ?>">
                                        <div class="card-body">
                                            <div class="small text-<?php echo e($item['text']); ?> fw-semibold mb-1"><?php echo e($item['label']); ?></div>
                                            <div class="fs-4 fw-semibold stat-scanned-<?php echo e($item['key']); ?>"><?php echo e(number_format($stats['scanned'][$item['key']])); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">Deskewed</h6>
                        <div class="row g-3 mb-3">
                            <?php $__currentLoopData = [
                                ['label'=>'Done',    'key'=>'DONE',    'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Pending', 'key'=>'PENDING', 'bg'=>'secondary-subtle', 'text'=>'secondary'],
                                ['label'=>'Issue',   'key'=>'ISSUE',   'bg'=>'danger-subtle',    'text'=>'danger'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-md-4">
                                    <div class="card border-0 bg-<?php echo e($item['bg']); ?>">
                                        <div class="card-body">
                                            <div class="small text-<?php echo e($item['text']); ?> fw-semibold mb-1"><?php echo e($item['label']); ?></div>
                                            <div class="fs-4 fw-semibold stat-deskewed-<?php echo e($item['key']); ?>"><?php echo e(number_format($stats['deskewed'][$item['key']])); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>


        
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">Converted</h6>
                        <div class="row g-3 mb-3">
                            <?php $__currentLoopData = [
                                ['label'=>'Done',    'key'=>'DONE',    'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Pending', 'key'=>'PENDING', 'bg'=>'secondary-subtle', 'text'=>'secondary'],
                                ['label'=>'Issue',   'key'=>'ISSUE',   'bg'=>'danger-subtle',    'text'=>'danger'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-md-4">
                                    <div class="card border-0 bg-<?php echo e($item['bg']); ?>">
                                        <div class="card-body">
                                            <div class="small text-<?php echo e($item['text']); ?> fw-semibold mb-1"><?php echo e($item['label']); ?></div>
                                            <div class="fs-4 fw-semibold stat-converted-<?php echo e($item['key']); ?>"><?php echo e(number_format($stats['converted'][$item['key']])); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small fw-semibold mb-2 mt-4">QA Converted</h6>
                        <div class="row g-3 mb-3">
                            <?php $__currentLoopData = [
                                ['label'=>'Done',        'key'=>'DONE',        'bg'=>'success-subtle',   'text'=>'success'],
                                ['label'=>'Pending',     'key'=>'PENDING',     'bg'=>'secondary-subtle', 'text'=>'secondary'],
                                ['label'=>'Discrepancy', 'key'=>'DISCREPANCY', 'bg'=>'warning-subtle',   'text'=>'warning'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-md-4">
                                    <div class="card border-0 bg-<?php echo e($item['bg']); ?>">
                                        <div class="card-body">
                                            <div class="small text-<?php echo e($item['text']); ?> fw-semibold mb-1"><?php echo e($item['label']); ?></div>
                                            <div class="fs-4 fw-semibold stat-qa-<?php echo e($item['key']); ?>"><?php echo e(number_format($stats['qa'][$item['key']])); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>



        
       

        
        <div class="text-end text-muted small mt-3 pe-2">
            Last updated: <span id="last-updated">just now</span>
        </div>

            <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    (function () {
        const INTERVAL_MS = 60_000; // 1 minute

        // Map CSS selector → function to extract value from the stats JSON
        const statMap = {
                    '.stat-total': d => d.total,

            '.stat-cond-GOOD':    d => d.cond.GOOD,
            '.stat-cond-AVERAGE': d => d.cond.AVERAGE,
            '.stat-cond-BAD':     d => d.cond.BAD,
            '.stat-cond-null':    d => d.cond['null'],

            '.stat-scanned-DONE':    d => d.scanned.DONE,
            '.stat-scanned-PENDING': d => d.scanned.PENDING,
            '.stat-scanned-ISSUE':   d => d.scanned.ISSUE,

            '.stat-deskewed-DONE':    d => d.deskewed.DONE,
            '.stat-deskewed-PENDING': d => d.deskewed.PENDING,
            '.stat-deskewed-ISSUE':   d => d.deskewed.ISSUE,

            '.stat-converted-DONE':    d => d.converted.DONE,
            '.stat-converted-PENDING': d => d.converted.PENDING,
            '.stat-converted-ISSUE':   d => d.converted.ISSUE,

            '.stat-qa-DONE':        d => d.qa.DONE,
            '.stat-qa-PENDING':     d => d.qa.PENDING,
            '.stat-qa-DISCREPANCY': d => d.qa.DISCREPANCY,

            '.stat-voters-male':   d => d.voters.male,
            '.stat-voters-female': d => d.voters.female,
            '.stat-voters-total':  d => d.voters.total,
            '.stat-voters-issues': d => d.voters.issues,
    };

        function fmt(n) {
            return Number(n).toLocaleString();
        }

        function applyStats(data) {
            Object.entries(statMap).forEach(([selector, getter]) => {
                const el = document.querySelector(selector);
            if (!el) return;

            const newVal = fmt(getter(data));
            if (el.textContent !== newVal) {
                // Brief flash to signal an update
                el.classList.add('text-primary');
                el.textContent = newVal;
                setTimeout(() => el.classList.remove('text-primary'), 1000);
            }
        });

            // Update "last updated" timestamp
            const ts = document.getElementById('last-updated');
            if (ts) {
                const now = new Date();
                ts.textContent = now.toLocaleTimeString();
            }
        }

        function fetchStats() {
            fetch('<?php echo e(route("dashboard.stats")); ?>', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
                .then(r => {
                if (!r.ok) throw new Error('Network response was not ok');
            return r.json();
        })
        .then(applyStats)
                .catch(err => console.warn('Stats refresh failed:', err));
        }

        // Start polling
        setInterval(fetchStats, INTERVAL_MS);
    })();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.verifier_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/verifier/dashboard.blade.php ENDPATH**/ ?>