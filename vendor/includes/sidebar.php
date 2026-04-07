<?php // vendor/includes/sidebar.php ?>
<nav class="navbar navbar-dark fixed-top" style="background:var(--dark);z-index:1040">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="bi bi-shop-window me-2"></i>Vendor Panel</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white-50" style="font-size:.9rem"><?= htmlspecialchars($_SESSION['vendor_name']) ?></span>
            <a href="<?= SITE_URL ?>/vendor/logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>
<div class="sidebar">
    <a href="<?= SITE_URL ?>/vendor/dashboard.php" <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'class="active"':'' ?>><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?= SITE_URL ?>/vendor/products.php" <?= basename($_SERVER['PHP_SELF'])=='products.php'?'class="active"':'' ?>><i class="bi bi-box-seam"></i> My Products</a>
    <a href="<?= SITE_URL ?>/vendor/add_product.php" <?= basename($_SERVER['PHP_SELF'])=='add_product.php'?'class="active"':'' ?>><i class="bi bi-plus-circle-fill"></i> Add Product</a>
    <a href="<?= SITE_URL ?>/vendor/orders.php" <?= basename($_SERVER['PHP_SELF'])=='orders.php'?'class="active"':'' ?>><i class="bi bi-bag-check-fill"></i> My Orders</a>
    <a href="<?= SITE_URL ?>/vendor/requests.php" <?= basename($_SERVER['PHP_SELF'])=='requests.php'?'class="active"':'' ?>><i class="bi bi-envelope-fill"></i> Item Requests</a>
    <hr style="border-color:#374151;margin:12px 0">
    <a href="<?= SITE_URL ?>/vendor/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>