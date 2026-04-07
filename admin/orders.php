<?php
// admin/orders.php
require_once '../includes/config.php';
if (!isAdmin()) redirect(SITE_URL . '/admin/login.php');

$orders = $conn->query("SELECT o.*,u.name as uname FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>All Orders – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header"><h4><i class="bi bi-bag-check-fill me-2 text-primary"></i>All Orders</h4></div>
    <div class="table-container">
        <table class="table table-hover">
            <thead><tr><th>#Order</th><th>User</th><th>Amount</th><th>Payment</th><th>Address</th><th>Status</th><th>Date</th><th>Detail</th></tr></thead>
            <tbody>
            <?php while($o = $orders->fetch_assoc()): ?>
            <tr>
                <td>#<?= $o['id'] ?></td>
                <td><?= htmlspecialchars($o['uname']) ?></td>
                <td>₹<?= number_format($o['total_amount'],2) ?></td>
                <td><span class="badge bg-secondary"><?= strtoupper($o['payment_method']) ?></span></td>
                <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($o['delivery_address']) ?></td>
                <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
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