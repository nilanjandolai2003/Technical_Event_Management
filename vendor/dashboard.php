<?php
// vendor/dashboard.php
require_once '../includes/config.php';
if (!isVendor()) redirect(SITE_URL . '/vendor/login.php');

$vid = $_SESSION['vendor_id'];
$totalProducts = $conn->query("SELECT COUNT(*) c FROM products WHERE vendor_id=$vid")->fetch_assoc()['c'];
$totalOrders   = $conn->query("SELECT COUNT(*) c FROM order_items WHERE vendor_id=$vid")->fetch_assoc()['c'];
$revenue       = $conn->query("SELECT IFNULL(SUM(oi.price*oi.quantity),0) r FROM order_items oi JOIN orders o ON oi.order_id=o.id WHERE oi.vendor_id=$vid AND o.status!='cancelled'")->fetch_assoc()['r'];
$pendingOrders = $conn->query("SELECT COUNT(*) c FROM order_items WHERE vendor_id=$vid AND status='pending'")->fetch_assoc()['c'];

$recentOrders = $conn->query("SELECT oi.*,p.name as pname,u.name as uname,o.created_at FROM order_items oi JOIN products p ON oi.product_id=p.id JOIN orders o ON oi.order_id=o.id JOIN users u ON o.user_id=u.id WHERE oi.vendor_id=$vid ORDER BY o.created_at DESC LIMIT 8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Vendor Dashboard – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header">
        <h4><i class="bi bi-speedometer2 me-2 text-primary"></i>Vendor Dashboard</h4>
        <span class="text-muted"><?= date('D, d M Y') ?></span>
    </div>
    <?php showFlash(); ?>
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6"><div class="stat-card bg-blue"><p>My Products</p><h3><?= $totalProducts ?></h3></div></div>
        <div class="col-md-3 col-6"><div class="stat-card bg-orange"><p>Total Orders</p><h3><?= $totalOrders ?></h3></div></div>
        <div class="col-md-3 col-6"><div class="stat-card bg-purple"><p>Pending Orders</p><h3><?= $pendingOrders ?></h3></div></div>
        <div class="col-md-3 col-6"><div class="stat-card bg-green"><p>Revenue</p><h3>₹<?= number_format($revenue) ?></h3></div></div>
    </div>
    <div class="table-container">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Recent Orders</h6>
            <a href="orders.php" class="btn btn-sm btn-primary">View All</a>
        </div>
        <table class="table table-hover">
            <thead><tr><th>Order#</th><th>Product</th><th>Customer</th><th>Qty</th><th>Amount</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
            <?php while($o = $recentOrders->fetch_assoc()): ?>
            <tr>
                <td>#<?= $o['order_id'] ?></td>
                <td><?= htmlspecialchars($o['pname']) ?></td>
                <td><?= htmlspecialchars($o['uname']) ?></td>
                <td><?= $o['quantity'] ?></td>
                <td>₹<?= number_format($o['price']*$o['quantity'],2) ?></td>
                <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                <td><?= date('d M y', strtotime($o['created_at'])) ?></td>
                <td><a href="orders.php?id=<?= $o['order_id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>