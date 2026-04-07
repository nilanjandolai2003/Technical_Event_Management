<?php
// admin/products.php
require_once '../includes/config.php';
if (!isAdmin()) redirect(SITE_URL . '/admin/login.php');

if (isset($_GET['action']) && $_GET['action']==='toggle' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE products SET status=IF(status='active','inactive','active') WHERE id=$id");
    flash('success','Product status updated.');
    redirect(SITE_URL . '/admin/products.php');
}

$products = $conn->query("SELECT p.*,v.name as vname FROM products p JOIN vendors v ON p.vendor_id=v.id ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>All Products – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header"><h4><i class="bi bi-box-seam me-2 text-primary"></i>All Products</h4></div>
    <?php showFlash(); ?>
    <div class="table-container">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Product</th><th>Vendor</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php while($p = $products->fetch_assoc()): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['vname']) ?></td>
                <td><?= htmlspecialchars($p['category'] ?? '-') ?></td>
                <td>₹<?= number_format($p['price'],2) ?></td>
                <td><?= $p['stock'] ?></td>
                <td><span class="badge bg-<?= $p['status']==='active'?'success':'secondary' ?>"><?= ucfirst($p['status']) ?></span></td>
                <td><a href="?action=toggle&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning">Toggle</a></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>