<?php $__env->startSection('title', ' :: Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .stat-card h3 {
            font-size: 42px;
            margin: 15px 0;
            font-weight: bold;
        }

        .stat-card p {
            color: #666;
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-card small {
            color: #999;
            font-size: 13px;
        }

        .stat-card.total {
            border-bottom: 4px solid #667eea;
        }

        .stat-card.total h3 {
            color: #667eea;
        }

        .stat-card.male {
            border-bottom: 4px solid #2196F3;
        }

        .stat-card.male h3 {
            color: #2196F3;
        }

        .stat-card.female {
            border-bottom: 4px solid #E91E63;
        }

        .stat-card.female h3 {
            color: #E91E63;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .card-header {
            background: white;
            border-bottom: 2px solid #f0f0f0;
            padding: 15px 20px;
            font-weight: bold;
            font-size: 18px;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-header i {
            margin-right: 8px;
            color: #667eea;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #555;
        }

        .table td {
            vertical-align: middle;
        }

        .badge-custom {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .progress {
            height: 6px;
            border-radius: 3px;
            margin-top: 8px;
        }

        .progress-bar {
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .summary-card h6 {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .summary-card .number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #666;
            margin-top: 30px;
            border-top: 1px solid #e0e0e0;
        }

        @media (max-width: 768px) {
            .stat-card h3 {
                font-size: 28px;
            }
            .stat-card {
                padding: 15px;
            }
        }

        .table-scroll {
            max-height: 400px;
            overflow-y: auto;
        }

        .table-scroll thead {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 2;
        }
        .table-scroll {
            max-height: 400px;
            overflow-y: auto;
        }

        .table-scroll thead {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 2;
        }
    </style>
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">VIS</span> - Dashboard</h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>
        </div>

        
    </div>



    <!-- Content area -->
    <div class="content">


        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Dashboard</h4>
            <small class="text-muted">Voter List Searched History</small>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-6">
                <div class="stat-card female">
                    <p>Total Assigned PPP Users</p>
                    <a href="<?php echo e(url('/backend/ppp-users')); ?>"> <h3><?php echo e(number_format($users->total_assigned_user)); ?></h3></a>

                    <small>Total Assigned PPP Users for Polling Agents</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card total">
                    <p>Total Polling Agents</p>
                   <a href="<?php echo e(url('/backend/app-web-users')); ?>"> <h3><?php echo e(number_format($users->total_users)); ?></h3></a>
                    <small>All Polling Agents users registered by  Assigned PPP Users</small>
                </div>
            </div>

        </div>
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card total">
                    <p>Total Searched Voters</p>

                    <a href="<?php echo e(url('/backend/searched-voters')); ?>">  <h3><?php echo e(number_format($total)); ?></h3></a>
                    <small>All Searched voters</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card male">
                    <p>Male Searched Voters</p>
                    <h3><?php echo e(number_format($male)); ?></h3>
                    <small><?php echo e($total > 0 ? round(($male/$total)*100, 1) : 0); ?>% of total</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card female">
                    <p>Female Searched Voters</p>
                    <h3><?php echo e(number_format($female)); ?></h3>
                    <small><?php echo e($total > 0 ? round(($female/$total)*100, 1) : 0); ?>% of total</small>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Polling Station Groups -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        🗳️ Searched Voters by Polling Agent
                        <span class="badge bg-secondary float-end"><?php echo e($usersGroups->count()); ?> Stations</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <div style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover mb-0">
                                    <thead style="position: sticky; top: 0; background: #fff; z-index: 2;">
                                    <tr>
                                        <th>#</th>
                                        <th>Polling Station</th>
                                        <th>Total Voters</th>
                                        <th>% of Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $usersGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $station): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td><strong><?php echo e($agents[$station->user_id] ?? 'Unknown'); ?></strong></td>
                                            <td><?php echo e(number_format($station->total)); ?></td>
                                            <td>
                                                <?php echo e($total > 0 ? round(($station->total/$total)*100, 2) : 0); ?>%
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar" style="width: <?php echo e($total > 0 ? ($station->total/$total)*100 : 0); ?>%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                No polling station data available
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blockcode Groups -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        📊 Searched Voters by Blockcode
                        <span class="badge bg-secondary float-end"><?php echo e($blockcodeGroups->count()); ?> Blocks</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <div style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover mb-0">
                                    <thead style="position: sticky; top: 0; background: #fff; z-index: 2;">
                                    <tr>
                                        <th>#</th>
                                        <th>Block Code</th>
                                        <th>Total Voters</th>
                                        <th>% of Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $blockcodeGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td><strong><?php echo e($block->blockcode); ?></strong></td>
                                            <td><?php echo e(number_format($block->total)); ?></td>
                                            <td>
                                                <?php echo e($total > 0 ? round(($block->total/$total)*100, 2) : 0); ?>%
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar" style="width: <?php echo e($total > 0 ? ($block->total/$total)*100 : 0); ?>%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                No blockcode data available
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Polling Station Groups -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        🗳️ Searched Voters by Polling Station
                        <span class="badge bg-secondary float-end"><?php echo e($pollingStationGroups->count()); ?> Stations</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <div style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover mb-0">
                                    <thead style="position: sticky; top: 0; background: #fff; z-index: 2;">
                                    <tr>
                                        <th>#</th>
                                        <th>Polling Station</th>
                                        <th>Total Voters</th>
                                        <th>% of Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $pollingStationGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $station): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($index + 1); ?></td>
                                            <td><strong><?php echo e($station->polling_station); ?></strong></td>
                                            <td><?php echo e(number_format($station->total)); ?></td>
                                            <td>
                                                <?php echo e($total > 0 ? round(($station->total/$total)*100, 2) : 0); ?>%
                                                <div class="progress mt-1" style="height: 5px;">
                                                    <div class="progress-bar" style="width: <?php echo e($total > 0 ? ($station->total/$total)*100 : 0); ?>%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                No polling station data available
                                            </td>
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

        <!-- Summary Stats -->
     


    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\vis-system-online\resources\views/backend/dashboard.blade.php ENDPATH**/ ?>