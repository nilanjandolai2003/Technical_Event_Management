<?php
// vendor/orders.php
require_once '../includes/config.php';
if (!isVendor()) redirect(SITE_URL . '/vendor/login.php');
$vid = $_SESSION['vendor_id'];

// Update item status
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['item_id'], $_POST['status'])) {
    $iid    = (int)$_POST['item_id'];
    $status = sanitize($conn, $_POST['status']);
    $conn->query("UPDATE order_items SET status='$status' WHERE id=$iid AND vendor_id=$vid");
    flash('success', 'Status updated.');
    redirect(SITE_URL . '/vendor/orders.php');
}

$orders = $conn->query("SELECT oi.*,p.name as pname,u.name as uname,u.email as uemail,o.created_at,o.delivery_address,o.payment_method
    FROM order_items oi
    JOIN products p ON oi.product_id=p.id
    JOIN orders o ON oi.order_id=o.id
    JOIN users u ON o.user_id=u.id
    WHERE oi.vendor_id=$vid ORDER BY o.created_at DESC");
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
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header"><h4><i class="bi bi-bag-check-fill me-2 text-primary"></i>My Orders</h4></div>
    <?php showFlash(); ?>
    <div class="table-container">
        <table class="table table-hover">
            <thead><tr><th>Order#</th><th>Product</th><th>Customer</th><th>Qty</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th><th>Update</th></tr></thead>
            <tbody>
            <?php while($o = $orders->fetch_assoc()): ?>
            <tr>
                <td>#<?= $o['order_id'] ?></td>
                <td><?= htmlspecialchars($o['pname']) ?></td>
                <td>
                    <div><?= htmlspecialchars($o['uname']) ?></div>
                    <small class="text-muted"><?= htmlspecialchars($o['uemail']) ?></small>
                </td>
                <td><?= $o['quantity'] ?></td>
                <td>₹<?= number_format($o['price']*$o['quantity'],2) ?></td>
                <td><span class="badge bg-secondary"><?= strtoupper($o['payment_method']) ?></span></td>
                <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                <td><?= date('d M y', strtotime($o['created_at'])) ?></td>
                <td>
                    <form method="POST" class="d-flex gap-1">
                        <input type="hidden" name="item_id" value="<?= $o['id'] ?>">
                        <select name="status" class="form-select form-select-sm" style="min-width:120px">
                            <?php foreach(['pending','confirmed','shipped','delivered','cancelled'] as $s): ?>
                            <option value="<?= $s ?>" <?= $o['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-sm btn-primary">Save</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>