<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DATA CENTER <?php echo $__env->yieldContent('title'); ?></title>
    <link href="<?php echo e(asset('public/frontend/img/favicon.png')); ?>" rel="icon">
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('admin/global_assets/css/icons/icomoon/styles.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('admin/assets/css/bootstrap_limitless.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('admin/assets/css/layout.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('admin/assets/css/components.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('admin/assets/css/colors.min.css')); ?>" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="<?php echo e(asset('admin/global_assets/main/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/global_assets/main/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/global_assets/plugins/loaders/blockui.min.js')); ?>"></script>
    <!-- /core JS files -->

    <script src="<?php echo e(asset('admin/assets/js/app.js')); ?>"></script>


</head>

<body>

<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark">
    <div class="navbar-brand">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="d-inline-block">
            <img src="<?php echo e(asset('admin/assets/images/login-img.png')); ?>" alt="">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>
        <span class="badge ml-md-3 mr-md-auto" style="color: #324148; background: #324148;">_</span>

        <ul class="navbar-nav">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <img src="<?php echo e(asset('admin/assets/images/logo.png')); ?>" class="rounded-circle mr-2" height="34" alt="">
                    <span><?php echo e(Auth::user()->name); ?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="icon-switch2"></i> Logout</a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->
<?php echo $__env->yieldContent('csssection'); ?>


<!-- Page content -->
<div class="page-content">

    <!-- Main sidebar -->
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

        <!-- Sidebar mobile toggler -->
        <div class="sidebar-mobile-toggler text-center">
            <a href="#" class="sidebar-mobile-main-toggle">
                <i class="icon-arrow-left8"></i>
            </a>
            Navigation
            <a href="#" class="sidebar-mobile-expand">
                <i class="icon-screen-full"></i>
                <i class="icon-screen-normal"></i>
            </a>
        </div>
        <!-- /sidebar mobile toggler -->


        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- User menu -->
            <div class="sidebar-user">
                <div class="card-body">
                    <div class="media">
                        <div class="mr-3">
                            <a href="<?php echo e(route('admin.dashboard')); ?>"><img src="<?php echo e(asset('admin/assets/images/logo.png')); ?>" width="38" height="38" class="rounded-circle" alt=""></a>
                        </div>

                        <div class="media-body">
                            <div class="media-title font-weight-semibold"><?php echo e(Auth::user()->name); ?></div>
                            <div class="font-size-xs opacity-50">
                                <i class="icon-pin font-size-sm"></i> &nbsp;Karachi
                            </div>
                        </div>

                        <div class="ml-3 align-self-center">
                            <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /user menu -->


            <div class="card card-sidebar-mobile">
                <ul class="nav nav-sidebar" data-nav-type="accordion">

                    <!-- Main -->
                    <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main Menu</div> <i class="icon-menu" title="Main"></i></li>

                    <li class="nav-item"><a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link"><i class="icon-home4"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('users.index')); ?>" class="nav-link"><i class="icon-users"></i><span>Users</span></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('blockcodes.index')); ?>" class="nav-link"><i class="icon-users"></i><span>blockcodes</span></a></li>
                    <li class="nav-item"><a href="<?php echo e(url('admin/users-dashboard')); ?>" class="nav-link"><i class="icon-users"></i><span>Users Blockcode</span></a></li>
                </ul>
            </div>

        </div>
        <!-- /sidebar content -->

    </div>
    <!-- /main sidebar -->


    <!-- Main content -->
    <div class="content-wrapper">

        <?php echo $__env->yieldContent('content'); ?>
        <div class="navbar navbar-expand-lg navbar-light">
            <div class="text-center d-lg-none w-100">
                <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
                    <i class="icon-unfold mr-2"></i>
                    Footer
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-footer">
                <span class="navbar-text">
                    &copy; <?php echo e(date('Y')); ?>. <a href="<?php echo e(route('admin.dashboard')); ?>">PPP - Data Center</a>
                </span>
            </div>
        </div>
    </div>
</div>
</body>
<?php echo $__env->yieldContent('jsfiles'); ?>
</html>
<?php /**PATH C:\xampp\htdocs\voterlist-system\resources\views/layouts/admin.blade.php ENDPATH**/ ?>