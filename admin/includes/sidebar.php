<?php
// admin/includes/sidebar.php
?>
<nav class="navbar navbar-dark fixed-top" style="background:var(--dark);z-index:1040">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="bi bi-lightning-charge-fill me-2"></i><span>Tech</span>Event</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white-50" style="font-size:.9rem"><i class="bi bi-person-fill me-1"></i><?= $_SESSION['admin_name'] ?></span>
            <a href="<?= SITE_URL ?>/admin/logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>
<div class="sidebar">
    <a href="<?= SITE_URL ?>/admin/dashboard.php" <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'class="active"':'' ?>><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?= SITE_URL ?>/admin/users.php" <?= basename($_SERVER['PHP_SELF'])=='users.php'?'class="active"':'' ?>><i class="bi bi-people-fill"></i> Manage Users</a>
    <a href="<?= SITE_URL ?>/admin/vendors.php" <?= basename($_SERVER['PHP_SELF'])=='vendors.php'?'class="active"':'' ?>><i class="bi bi-shop"></i> Manage Vendors</a>
    <a href="<?= SITE_URL ?>/admin/products.php" <?= basename($_SERVER['PHP_SELF'])=='products.php'?'class="active"':'' ?>><i class="bi bi-box-seam"></i> All Products</a>
    <a href="<?= SITE_URL ?>/admin/orders.php" <?= basename($_SERVER['PHP_SELF'])=='orders.php'?'class="active"':'' ?>><i class="bi bi-bag-check-fill"></i> All Orders</a>
    <a href="<?= SITE_URL ?>/admin/requests.php" <?= basename($_SERVER['PHP_SELF'])=='requests.php'?'class="active"':'' ?>><i class="bi bi-envelope-fill"></i> Item Requests</a>
    <hr style="border-color:#374151;margin:12px 0">
    <a href="<?= SITE_URL ?>/admin/signup.php"><i class="bi bi-person-plus-fill"></i> Add Admin</a>
    <a href="<?= SITE_URL ?>/admin/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>