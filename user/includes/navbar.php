<?php // user/includes/navbar.php
$cartCount = isUser() ? getCartCount($conn, $_SESSION['user_id']) : 0;
?>
<nav class="navbar navbar-expand-lg user-topbar sticky-top">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="<?= SITE_URL ?>/user/portal.php">
            <i class="bi bi-lightning-charge-fill me-1" style="color:#60a5fa"></i><?= SITE_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])==='portal.php'?'active text-white':'' ?>" href="<?= SITE_URL ?>/user/portal.php"><i class="bi bi-grid me-1"></i>Products</a></li>
                <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])==='orders.php'?'active text-white':'' ?>" href="<?= SITE_URL ?>/user/orders.php"><i class="bi bi-bag me-1"></i>My Orders</a></li>
                <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF'])==='request_item.php'?'active text-white':'' ?>" href="<?= SITE_URL ?>/user/request_item.php"><i class="bi bi-envelope me-1"></i>Request Item</a></li>
            </ul>
            <ul class="navbar-nav gap-2 align-items-center">
                <li class="nav-item">
                    <a class="nav-link position-relative" href="<?= SITE_URL ?>/user/cart.php">
                        <i class="bi bi-cart3 fs-5 text-white"></i>
                        <?php if($cartCount > 0): ?>
                        <span class="cart-badge position-absolute" style="top:-4px;right:-8px"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= SITE_URL ?>/user/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>