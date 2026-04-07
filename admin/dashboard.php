<?php
// admin/dashboard.php
require_once '../includes/config.php';
if (!isAdmin()) redirect(SITE_URL . '/admin/login.php');

$totalUsers    = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$totalVendors  = $conn->query("SELECT COUNT(*) c FROM vendors")->fetch_assoc()['c'];
$totalProducts = $conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()['c'];
$totalOrders   = $conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()['c'];
$revenue       = $conn->query("SELECT IFNULL(SUM(total_amount),0) r FROM orders WHERE status!='cancelled'")->fetch_assoc()['r'];
$pendingVendors= $conn->query("SELECT COUNT(*) c FROM vendors WHERE status='pending'")->fetch_assoc()['c'];

$recentOrders  = $conn->query("SELECT o.*,u.name as uname FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header">
        <h4><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</h4>
        <span class="text-muted" style="font-size:.9rem"><?= date('D, d M Y') ?></span>
    </div>
    <?php showFlash(); ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card bg-blue"><p>Total Users</p><h3><?= $totalUsers ?></h3></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card bg-green"><p>Vendors</p><h3><?= $totalVendors ?></h3><?php if($pendingVendors): ?><small><?= $pendingVendors ?> pending</small><?php endif; ?></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card bg-orange"><p>Products</p><h3><?= $totalProducts ?></h3></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card bg-purple"><p>Revenue</p><h3>₹<?= number_format($revenue) ?></h3><small><?= $totalOrders ?> orders</small></div>
        </div>
    </div>

    <div class="table-container">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Recent Orders</h6>
            <a href="orders.php" class="btn btn-sm btn-primary">View All</a>
        </div>
        <table class="table table-hover">
            <thead><tr><th>#</th><th>User</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
            <?php while($o = $recentOrders->fetch_assoc()): ?>
            <tr>
                <td>#<?= $o['id'] ?></td>
                <td><?= htmlspecialchars($o['uname']) ?></td>
                <td>₹<?= number_format($o['total_amount'],2) ?></td>
                <td><span class="badge bg-secondary"><?= strtoupper($o['payment_method']) ?></span></td>
                <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                <td><?= date('d M y', strtotime($o['created_at'])) ?></td>
                <td><a href="order_detail.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>