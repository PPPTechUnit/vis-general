<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Voterl <?php echo $__env->yieldContent('title'); ?></title>

    <!-- Fontfaces CSS-->
    <link href="<?php echo e(asset('verifier_assets/css/font-face.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('verifier_assets/vendor/fontawesome-7.1.0/css/all.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('verifier_assets/vendor/mdi-font/css/material-design-iconic-font.min.css')); ?>" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="<?php echo e(asset('verifier_assets/vendor/bootstrap-5.3.8.min.css')); ?>" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?php echo e(asset('verifier_assets/css/aos.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('verifier_assets/vendor/css-hamburgers/hamburgers.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('verifier_assets/css/swiper-bundle-12.0.3.min.css')); ?>" rel="stylesheet" media="all">
    <link href="<?php echo e(asset('verifier_assets/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css')); ?>" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="<?php echo e(asset('verifier_assets/css/theme.css')); ?>" rel="stylesheet" media="all">
    <script src="<?php echo e(asset('verifier_assets/vendor/bootstrap-5.3.8.bundle.min.js')); ?>"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Jquery JS-->
    <script src="<?php echo e(asset('verifier_assets/js/vanilla-utils.js')); ?>"></script>

    <!-- Bootstrap JS-->
    <!-- Vendor JS       -->
    <script src="<?php echo e(asset('verifier_assets/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js')); ?>"></script>
    <script src="<?php echo e(asset('verifier_assets/vendor/chartjs/chart.umd.js-4.5.1.min.js')); ?>"></script>

</head>

<style>
    .menu-sidebar {
        transition: width 0.3s ease, min-width 0.3s ease, padding 0.3s ease;
        overflow: hidden;
        min-width: 250px; /* adjust to match your theme's sidebar width */
    }

    .sidebar-hidden {
        width: 0 !important;
        min-width: 0 !important;
        padding: 0 !important;
    }

</style>

<body>
<div class="page-wrapper">
    <!-- HEADER MOBILE-->
    <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
            <div class="container-fluid">
                <div class="header-mobile-inner">
                    <a class="logo" href="<?php echo e(url('/verifier/dashboard')); ?>">
                        <img src="<?php echo e(asset('verifier_assets/images/icon/logo.png')); ?>" alt="CoolAdmin" />
                    </a>
                    <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                    </button>
                </div>
            </div>
        </div>
        <nav class="navbar-mobile">
            <div class="container-fluid">
                <ul class="navbar-mobile__list list-unstyled">
                    <li>
                        <a href="<?php echo e(url('/verifier/dashboard')); ?>">
                            <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('blockcode-information.index')); ?>">
                            <i class="fas fa-chart-bar"></i>Blockcode Information</a>
                    </li>
                    <?php if(auth()->user()->group == 'main' || auth()->user()->group != 'group2'  ): ?>
                        <li>
                            <a href="<?php echo e(route('voterlist-blockcodes.index')); ?>">
                                <i class="fas fa-chart-bar"></i>Import Voterlist</a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/verifier/rescan-blockcode')); ?>">
                                <i class="fas fa-chart-bar"></i>Re-Scaned Blockcode</a>
                        </li>

                        <li>
                            <a href="<?php echo e(url('/verifier/voterlist')); ?>">
                                <i class="fas fa-chart-bar"></i>Voterlist</a>
                        </li>
                    <?php endif; ?>


                </ul>
            </div>
        </nav>
    </header>
    <!-- END HEADER MOBILE-->

    <!-- MENU SIDEBAR-->
    <aside class="menu-sidebar" id="menuSidebar123">
        <div class="logo">
            <a href="#">
                <img src="<?php echo e(asset('verifier_assets/images/icon/logo.png')); ?>" alt="Cool Admin" />
            </a>
        </div>
        <div class="menu-sidebar__content js-scrollbar1">
            <nav class="navbar-sidebar">
                <ul class="list-unstyled navbar__list">

                    <li>
                        <a href="<?php echo e(url('/verifier/dashboard')); ?>">
                            <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('blockcode-information.index')); ?>">
                            <i class="fas fa-table"></i>Blockcode Information</a>
                    </li>
                    <?php if(auth()->user()->group == 'main' || auth()->user()->group != 'group2'  ): ?>
                        <li>
                            <a href="<?php echo e(route('voterlist-blockcodes.index')); ?>">
                                <i class="fas fa-chart-bar"></i>Import Voterlist</a>
                        </li>
                        <li>
                            <a href="<?php echo e(url('/verifier/rescan-blockcode')); ?>">
                                <i class="fas fa-chart-bar"></i>Re-Scaned Blockcode</a>
                        </li>

                        <li>
                            <a href="<?php echo e(url('/verifier/voterlist')); ?>">
                                <i class="fas fa-chart-bar"></i>Voterlist</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </nav>
        </div>
    </aside>

    <!-- PAGE CONTAINER-->
    <div class="page-container">
        <!-- HEADER DESKTOP-->
        <header class="header-desktop">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="header-wrap">

                        <button id="barsbars" > <i class="fa fa-bars"></i></button>

                        <form class="form-header" action="" method="POST" style="visibility: hidden;">
                            <input class="au-input au-input--xl" type="text" name="search" placeholder="Search for datas &amp; reports..." />
                            <button class="au-btn--submit" type="submit">
                                <i class="zmdi zmdi-search"></i>
                            </button>
                        </form>
                        <div class="header-button">

                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">



                                    <div class="content">
                                        <div class="btn-group">

                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown"><?php echo e(auth()->user()->name); ?></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#"><?php echo e('Profile'); ?></a></li>
                                                    <li><a class="dropdown-item"  href="<?php echo e(route('logout')); ?>"
                                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Logout</a></li>
                                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                                        <?php echo csrf_field(); ?>
                                                    </form>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- HEADER DESKTOP-->

        <!-- MAIN CONTENT-->
        <div class="main-content">
            <div class="section__content ">
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>



                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright">
                                <p>Copyright © <?php echo e(date('Y')); ?> . All rights reserved</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MAIN CONTENT-->
        <!-- END PAGE CONTAINER-->
    </div>

</div>

<!-- Main JS-->


<script src="<?php echo e(asset('verifier_assets/js/swiper-bundle-12.0.3.min.js')); ?>"></script>
<script src="<?php echo e(asset('verifier_assets/js/aos.js')); ?>"></script>
<script src="<?php echo e(asset('verifier_assets/js/modern-plugins.js')); ?>"></script>
<!-- At the bottom of your layout, before </body> -->
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
<script>
    $(document).ready(function () {
        $("#barsbars").click(function () {
            $("#menuSidebar123").toggleClass("sidebar-hidden");
        });
    });
</script>
</html>
<!-- end document-->
<?php /**PATH C:\xampp\htdocs\vis-system-gb\resources\views/layouts/verifier_layout.blade.php ENDPATH**/ ?>