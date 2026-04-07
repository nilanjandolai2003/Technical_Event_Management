<?php
// user/orders.php
require_once '../includes/config.php';
if (!isUser()) redirect(SITE_URL . '/user/login.php');
$uid = $_SESSION['user_id'];

$orders = $conn->query("SELECT o.*, (SELECT COUNT(*) FROM order_items WHERE order_id=o.id) as item_count FROM orders o WHERE o.user_id=$uid ORDER BY o.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Orders – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-bag-check me-2 text-primary"></i>My Orders</h4>
    <?php if($orders->num_rows === 0): ?>
    <div class="text-center py-5">
        <i class="bi bi-bag-x display-3 text-muted"></i>
        <p class="text-muted mt-3 mb-4">You haven't placed any orders yet.</p>
        <a href="portal.php" class="btn btn-primary">Start Shopping</a>
    </div>
    <?php else: ?>
    <?php while($o = $orders->fetch_assoc()): ?>
    <div class="bg-white rounded-3 shadow-sm p-4 mb-3">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <div class="fw-bold">Order #<?= $o['id'] ?></div>
                <div class="text-muted" style="font-size:.85rem"><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?> · <?= $o['item_count'] ?> item(s)</div>
            </div>
            <div class="text-end">
                <div class="fw-bold text-primary fs-5">₹<?= number_format($o['total_amount'],2) ?></div>
                <span class="badge badge-<?= $o['status'] ?> px-3 py-1"><?= ucfirst($o['status']) ?></span>
            </div>
        </div>
        <hr class="my-3">
        <?php
        $orderItems = $conn->query("SELECT oi.*,p.name as pname,v.name as vname FROM order_items oi JOIN products p ON oi.product_id=p.id JOIN vendors v ON oi.vendor_id=v.id WHERE oi.order_id={$o['id']}");
        while($item = $orderItems->fetch_assoc()):
        ?>
        <div class="d-flex justify-content-between align-items-center py-1">
            <div>
                <span class="fw-semibold"><?= htmlspecialchars($item['pname']) ?></span>
                <small class="text-muted ms-2">by <?= htmlspecialchars($item['vname']) ?></small>
            </div>
            <div class="text-end">
                <span class="text-muted">×<?= $item['quantity'] ?></span>
                <span class="ms-3 fw-semibold">₹<?= number_format($item['price']*$item['quantity'],2) ?></span>
                <span class="badge badge-<?= $item['status'] ?> ms-2"><?= ucfirst($item['status']) ?></span>
            </div>
        </div>
        <?php endwhile; ?>
        <div class="mt-3 d-flex gap-2 flex-wrap">
            <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars(substr($o['delivery_address'],0,50)).'...' ?></span>
            <span class="badge bg-light text-dark border"><i class="bi bi-credit-card me-1"></i><?= strtoupper($o['payment_method']) ?></span>
        </div>
    </div>
    <?php endwhile; ?>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>