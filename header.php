<?php

require("connection_niceadmin.php");

session_start();
$login_user1 = '';
$login_user2 = '';
$login_user = '';
if (isset($_SESSION['login_user1'])) {
    $login_user1 = $_SESSION['login_user1'];
}
if (isset($_SESSION['login_user2'])) {
    $login_user2 = $_SESSION['login_user2'];
}
$login_user = $login_user1 . $login_user2;

if (isset($_POST['logout'])) {

    session_destroy();
    header("location:index.php");
    exit();
}

$kg_get_category = "SELECT * FROM category";
$kg_get_category_run = mysqli_query($kg_niceadmin_con, $kg_get_category);

$categories = array();
while ($row_categories = mysqli_fetch_assoc($kg_get_category_run)) {
    $categories[] = $row_categories;
}

$total_quantity_in_cart = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product) {
        $total_quantity_in_cart += $product['quantity'];
    }
}


?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Nice Website</title>
    <meta name="description" content="description">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/css/plugins.css">
    <!-- Bootstap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

    <script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="assets/js/vendor/jquery.cookie.js"></script>
    <script src="assets/js/vendor/wow.min.js"></script>
    <!-- Including Javascript -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/lazysizes.js"></script>
    <script src="assets/js/main.js"></script>
    <style>
        .logout {
            color: white;
            background-color: black;
            border: 0.5px solid white;

            padding: 5px 10px;

            cursor: pointer;

            outline: none;

            transition: background-color 0.3s, color 0.3s, border-color 0.3s;

        }
    </style>
</head>

<body>

    <div class="search">
        <div class="search__form">
            <form class="search-bar__form" action="#">
                <button class="go-btn search__button" type="submit"><i class="icon anm anm-search-l"></i></button>
                <input class="search__input" type="search" name="q" value="" placeholder="Search entire store..." aria-label="Search" autocomplete="off">
            </form>
            <button type="button" class="search-trigger close-btn"><i class="anm anm-times-l"></i></button>
        </div>
    </div>
    <!-- top header-->
    <div class="top-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 col-sm-8 col-md-5 col-lg-4">



                </div>
                <div class="col-sm-4 col-md-4 col-lg-4 d-none d-lg-none d-md-block d-lg-block">
                    <div class="text-center">
                        <p class="top-header_middle-text">We Are Selling Products</p>
                    </div>

                </div>

                <div class="col-2 col-sm-4 col-md-3 col-lg-4 text-right">
                    <span class="user-menu d-block d-lg-none"><i class="anm anm-user-al" aria-hidden="true"></i></span>
                    <ul class="customer-links list-inline">
                        <?php if ($login_user) : ?>
                            <li>
                                <p style="margin-right: 50px; display: inline;"><?php echo $login_user; ?></p>
                                <form method="post" style="display: inline;">
                                    <button class="logout" name="logout">Logout</button>
                                </form>
                            </li>
                        <?php else : ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Create Account</a></li>
                        <?php endif; ?>

                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- header -->
    <div class="header-wrap animated d-flex" bis_skin_checked="1">
        <div class="container-fluid" bis_skin_checked="1">
            <div class="row align-items-center" bis_skin_checked="1">
                <!--Desktop Logo-->
                <div class="logo col-md-2 col-lg-2 d-none d-lg-block" bis_skin_checked="1">
                    <a href="index.php">
                        <img src="assets/images/logo.svg" alt="Belle Multipurpose Html Template" title="Belle Multipurpose Html Template">
                    </a>
                </div>
                <!--End Desktop Logo-->
                <div class="col-2 col-sm-3 col-md-3 col-lg-8" bis_skin_checked="1">
                    <div class="d-block d-lg-none" bis_skin_checked="1">
                        <button type="button" class="btn--link site-header__menu js-mobile-nav-toggle mobile-nav--open">
                            <i class="icon anm anm-times-l"></i>
                            <i class="anm anm-bars-r"></i>
                        </button>
                    </div>
                    <!--Desktop Menu-->
                    <nav class="grid__item" id="AccessibleNav"><!-- for mobile -->
                        <ul id="siteNav" class="site-nav medium center hidearrow">

                            <li class="lvl1 parent megamenu">
                                <a href="index.php">Home<i class="anm anm-angle-down-l"></i></a>
                            </li>

                            <?php foreach ($categories as $category) : ?>
                                <li class="lvl1 parent megamenu">
                                    <a href=""><?php echo $category['category_name']; ?> <i class="anm anm-angle-down-l"></i></a>
                                    <div class="megamenu style1">
                                        <ul class="grid mmWrapper">
                                            <li class="grid__item large-up--one-whole">
                                                <ul class="grid subLinks">
                                                    <?php
                                                    // Query subcategories for the current category
                                                    $categoryId = $category['c_id'];
                                                    $kg_get_sub_category = "SELECT * FROM sub_category WHERE c_id = $categoryId";
                                                    $kg_get_sub_category_run = mysqli_query($kg_niceadmin_con, $kg_get_sub_category);

                                                    while ($row_sub_categories = mysqli_fetch_assoc($kg_get_sub_category_run)) {
                                                        echo '<li class="grid__item lvl-1 col-md-3 col-lg-3"><a href="product.php?s_c_id=' . $row_sub_categories['s_c_id'] . '" class="site-nav lvl-1">' . $row_sub_categories['sub_category_name'] . '</a></li>';
                                                    }
                                                    ?>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            <?php endforeach; ?>

                    </nav>
                    <!--End Desktop Menu-->
                </div>



                <div class="site-cart">
                    <a href="cart.php" class="site-header__cart" title="Cart">


                        <i class="icon anm anm-bag-l"></i>
                        <span id="CartCount" class="site-header__cart-count" data-cart-render="item_count"><?php echo $total_quantity_in_cart; ?></span>
                    </a>
                    <!--Minicart Popup-->
                    <div id="header-cart" class="block block-cart">
                        <div class="total">
                            <a href="cart.php" class="btn btn-secondary btn--small"> <i class="icon anm anm-bag-l"></i>View Cart</a>
                        </div>
                    </div>
                    <!--End Minicart Popup-->
                </div>
            </div>

        </div>
    </div>



</body>

</html>