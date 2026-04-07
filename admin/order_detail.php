<?php
// admin/order_detail.php
require_once '../includes/config.php';
if (!isAdmin()) redirect(SITE_URL . '/admin/login.php');

$id = (int)($_GET['id'] ?? 0);
$order = $conn->query("SELECT o.*,u.name as uname,u.email as uemail FROM orders o JOIN users u ON o.user_id=u.id WHERE o.id=$id")->fetch_assoc();
if (!$order) { flash('error','Order not found.'); redirect(SITE_URL.'/admin/orders.php'); }

$items = $conn->query("SELECT oi.*,p.name as pname,v.name as vname FROM order_items oi JOIN products p ON oi.product_id=p.id JOIN vendors v ON oi.vendor_id=v.id WHERE oi.order_id=$id");

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['status'])) {
    $status = sanitize($conn, $_POST['status']);
    $conn->query("UPDATE orders SET status='$status' WHERE id=$id");
    flash('success','Order status updated.');
    redirect(SITE_URL."/admin/order_detail.php?id=$id");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Order #<?= $id ?> – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header">
        <h4><i class="bi bi-receipt me-2 text-primary"></i>Order #<?= $id ?></h4>
        <a href="orders.php" class="btn btn-sm btn-outline-secondary">← Back</a>
    </div>
    <?php showFlash(); ?>
    <div class="row g-3">
        <div class="col-md-8">
            <div class="table-container p-3">
                <h6 class="fw-bold mb-3">Order Items</h6>
                <table class="table">
                    <thead><tr><th>Product</th><th>Vendor</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    <?php while($i = $items->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($i['pname']) ?></td>
                        <td><?= htmlspecialchars($i['vname']) ?></td>
                        <td><?= $i['quantity'] ?></td>
                        <td>₹<?= number_format($i['price'],2) ?></td>
                        <td>₹<?= number_format($i['price']*$i['quantity'],2) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                    <tfoot><tr><td colspan="4" class="text-end fw-bold">Total</td><td class="fw-bold">₹<?= number_format($order['total_amount'],2) ?></td></tr></tfoot>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-3 p-3 shadow-sm mb-3">
                <h6 class="fw-bold mb-3">Customer Info</h6>
                <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($order['uname']) ?></p>
                <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($order['uemail']) ?></p>
                <p class="mb-1"><strong>Payment:</strong> <?= strtoupper($order['payment_method']) ?></p>
                <p class="mb-0"><strong>Address:</strong><br><?= nl2br(htmlspecialchars($order['delivery_address'])) ?></p>
            </div>
            <div class="bg-white rounded-3 p-3 shadow-sm">
                <h6 class="fw-bold mb-3">Update Status</h6>
                <form method="POST">
                    <select name="status" class="form-select mb-2">
                        <?php foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s): ?>
                        <option value="<?= $s ?>" <?= $order['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>