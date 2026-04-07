<?php
// vendor/products.php
require_once '../includes/config.php';
if (!isVendor()) redirect(SITE_URL . '/vendor/login.php');
$vid = $_SESSION['vendor_id'];

if (isset($_GET['action']) && isset($_GET['id'])) {
    $pid = (int)$_GET['id'];
    if ($_GET['action'] === 'toggle') {
        $conn->query("UPDATE products SET status=IF(status='active','inactive','active') WHERE id=$pid AND vendor_id=$vid");
        flash('success', 'Product status updated.');
    } elseif ($_GET['action'] === 'delete') {
        $conn->query("DELETE FROM products WHERE id=$pid AND vendor_id=$vid");
        flash('success', 'Product deleted.');
    }
    redirect(SITE_URL . '/vendor/products.php');
}

$products = $conn->query("SELECT * FROM products WHERE vendor_id=$vid ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Products – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header">
        <h4><i class="bi bi-box-seam me-2 text-primary"></i>My Products</h4>
        <a href="add_product.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
    </div>
    <?php showFlash(); ?>
    <div class="table-container">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Product Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php while($p = $products->fetch_assoc()): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td>
                    <div class="fw-semibold"><?= htmlspecialchars($p['name']) ?></div>
                    <small class="text-muted"><?= htmlspecialchars(substr($p['description']??'',0,60)) ?></small>
                </td>
                <td><?= htmlspecialchars($p['category'] ?? '-') ?></td>
                <td class="fw-bold text-primary">₹<?= number_format($p['price'],2) ?></td>
                <td>
                    <span class="badge <?= $p['stock']<=5?'bg-danger':'bg-success' ?>"><?= $p['stock'] ?></span>
                </td>
                <td><span class="badge bg-<?= $p['status']==='active'?'success':'secondary' ?>"><?= ucfirst($p['status']) ?></span></td>
                <td class="d-flex gap-1">
                    <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    <a href="?action=toggle&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-toggle-on"></i></a>
                    <a href="?action=delete&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')"><i class="bi bi-trash"></i></a>
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