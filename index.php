<?php
// index.php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= SITE_NAME ?> – Technical Event Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<style>
.hero {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #4c1d95 100%);
    display: flex; align-items: center;
    position: relative; overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero-title { font-size: clamp(2.5rem, 6vw, 5rem); font-weight: 900; line-height: 1.1; }
.hero-title span { color: #60a5fa; }
.role-card {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 20px;
    padding: 32px 24px;
    text-align: center;
    transition: transform 0.3s, background 0.3s;
    text-decoration: none;
    color: #fff;
    display: block;
}
.role-card:hover {
    background: rgba(255,255,255,0.15);
    transform: translateY(-6px);
    color: #fff;
}
.role-icon {
    width: 72px; height: 72px; border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; margin: 0 auto 16px;
}
.feature-section { padding: 80px 0; }
.feature-icon {
    width: 56px; height: 56px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; margin-bottom: 16px;
}
</style>
</head>
<body style="background:#0f172a">

<!-- Hero Section -->
<div class="hero">
    <div class="container position-relative" style="z-index:2">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white mb-5 mb-lg-0">
                <div class="mb-3">
                    <span class="badge px-3 py-2" style="background:rgba(96,165,250,.2);color:#60a5fa;border:1px solid rgba(96,165,250,.3);border-radius:20px;font-size:.85rem">
                        <i class="bi bi-lightning-charge-fill me-1"></i>Technical Event Management
                    </span>
                </div>
                <h1 class="hero-title mb-4">Manage Events<br>Like a <span>Pro</span></h1>
                <p class="text-white-50 mb-4" style="font-size:1.1rem;max-width:480px">
                    A complete platform for event planners, equipment vendors, and administrators. Browse, order, and manage all your technical event needs in one place.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="user/signup.php" class="btn btn-primary btn-lg px-4"><i class="bi bi-person-plus me-2"></i>Get Started</a>
                    <a href="user/login.php" class="btn btn-outline-light btn-lg px-4">Login</a>
                </div>
            </div>
            <div class="col-lg-6">
                <h6 class="text-white-50 text-uppercase mb-4" style="letter-spacing:1.5px;font-size:.8rem">Access Portal</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <a href="user/login.php" class="role-card d-flex align-items-center gap-4 py-4">
                            <div class="role-icon flex-shrink-0" style="background:rgba(37,99,235,.3)">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold fs-5">User / Event Planner</div>
                                <div style="color:rgba(255,255,255,.6);font-size:.9rem">Browse products, manage cart & track orders</div>
                            </div>
                            <i class="bi bi-arrow-right ms-auto"></i>
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="vendor/login.php" class="role-card d-flex align-items-center gap-4 py-4">
                            <div class="role-icon flex-shrink-0" style="background:rgba(124,58,237,.3)">
                                <i class="bi bi-shop-window text-white"></i>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold fs-5">Vendor / Supplier</div>
                                <div style="color:rgba(255,255,255,.6);font-size:.9rem">List products & manage incoming orders</div>
                            </div>
                            <i class="bi bi-arrow-right ms-auto"></i>
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="admin/login.php" class="role-card d-flex align-items-center gap-4 py-4">
                            <div class="role-icon flex-shrink-0" style="background:rgba(220,38,38,.3)">
                                <i class="bi bi-shield-lock-fill text-white"></i>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold fs-5">Administrator</div>
                                <div style="color:rgba(255,255,255,.6);font-size:.9rem">Full platform control & analytics</div>
                            </div>
                            <i class="bi bi-arrow-right ms-auto"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="feature-section" style="background:#111827">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-white fw-bold">Everything You Need</h2>
            <p class="text-white-50">A complete solution for technical event management</p>
        </div>
        <div class="row g-4">
            <?php
            $features = [
                ['icon'=>'bi-grid-3x3-gap-fill','color'=>'#2563eb','title'=>'Product Catalog','desc'=>'Browse equipment from verified vendors with category filters and search.'],
                ['icon'=>'bi-cart-check-fill','color'=>'#7c3aed','title'=>'Cart & Checkout','desc'=>'Add items to cart, checkout with Cash or UPI, and track your order status.'],
                ['icon'=>'bi-shop-window','color'=>'#16a34a','title'=>'Vendor Portal','desc'=>'Vendors manage inventory, orders, and product listings from one dashboard.'],
                ['icon'=>'bi-shield-check-fill','color'=>'#dc2626','title'=>'Admin Control','desc'=>'Full admin panel to manage users, vendors, approve accounts, and monitor revenue.'],
                ['icon'=>'bi-envelope-heart-fill','color'=>'#d97706','title'=>'Item Requests','desc'=>'Users can request unlisted items from specific vendors or the platform.'],
                ['icon'=>'bi-bar-chart-fill','color'=>'#0891b2','title'=>'Order Tracking','desc'=>'Real-time order status updates from both vendor and user dashboards.'],
            ];
            foreach($features as $f): ?>
            <div class="col-md-4 col-sm-6">
                <div style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:28px;height:100%">
                    <div class="feature-icon" style="background:<?= $f['color'] ?>22">
                        <i class="<?= $f['icon'] ?>" style="color:<?= $f['color'] ?>"></i>
                    </div>
                    <h6 class="text-white fw-bold mb-2"><?= $f['title'] ?></h6>
                    <p class="text-white-50 mb-0" style="font-size:.9rem"><?= $f['desc'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<footer style="background:#0f172a;padding:24px 0;text-align:center">
    <p class="text-white-50 mb-0" style="font-size:.875rem">
        &copy; <?= date('Y') ?> <?= SITE_NAME ?> · Built with PHP & MySQL
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>