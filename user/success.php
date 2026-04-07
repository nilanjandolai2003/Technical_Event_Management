<?php
// user/success.php
require_once '../includes/config.php';
if (!isUser()) redirect(SITE_URL . '/user/login.php');
$oid = (int)($_GET['order_id'] ?? 0);
$order = $conn->query("SELECT * FROM orders WHERE id=$oid AND user_id={$_SESSION['user_id']}")->fetch_assoc();
if (!$order) redirect(SITE_URL . '/user/portal.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Order Placed – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container py-5 text-center">
    <div style="max-width:500px;margin:0 auto">
        <div style="width:100px;height:100px;background:linear-gradient(135deg,#16a34a,#059669);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 8px 24px rgba(22,163,74,.3)">
            <i class="bi bi-check-lg text-white" style="font-size:3rem"></i>
        </div>
        <h2 class="fw-bold mb-2">Order Placed!</h2>
        <p class="text-muted mb-1">Your order <strong>#<?= $oid ?></strong> has been placed successfully.</p>
        <p class="text-muted mb-4">Total: <strong class="text-primary">₹<?= number_format($order['total_amount'],2) ?></strong> via <strong><?= strtoupper($order['payment_method']) ?></strong></p>
        <div class="bg-white rounded-3 p-3 shadow-sm mb-4 text-start">
            <p class="mb-1 fw-semibold text-muted" style="font-size:.85rem">DELIVERY TO</p>
            <p class="mb-0"><?= nl2br(htmlspecialchars($order['delivery_address'])) ?></p>
        </div>
        <div class="d-flex gap-3 justify-content-center">
            <a href="orders.php" class="btn btn-primary px-4"><i class="bi bi-bag me-2"></i>Track Order</a>
            <a href="portal.php" class="btn btn-outline-secondary px-4">Continue Shopping</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>