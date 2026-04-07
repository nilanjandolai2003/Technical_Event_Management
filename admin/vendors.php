<?php
// admin/vendors.php
require_once '../includes/config.php';
if (!isAdmin()) redirect(SITE_URL . '/admin/login.php');

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    $map = ['approve'=>'active','block'=>'blocked','unblock'=>'active'];
    if (array_key_exists($action, $map)) {
        $conn->query("UPDATE vendors SET status='{$map[$action]}' WHERE id=$id");
        flash('success', ucfirst($action) . 'd vendor.');
    } elseif ($action === 'delete') {
        $conn->query("DELETE FROM vendors WHERE id=$id");
        flash('success','Vendor deleted.');
    }
    redirect(SITE_URL . '/admin/vendors.php');
}

$vendors = $conn->query("SELECT v.*, (SELECT COUNT(*) FROM products WHERE vendor_id=v.id) as prod_count FROM vendors v ORDER BY v.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Vendors – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header">
        <h4><i class="bi bi-shop me-2 text-primary"></i>Manage Vendors</h4>
    </div>
    <?php showFlash(); ?>
    <div class="table-container">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Company</th><th>Products</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
            <tbody>
            <?php while($v = $vendors->fetch_assoc()): ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td><?= htmlspecialchars($v['name']) ?></td>
                <td><?= htmlspecialchars($v['email']) ?></td>
                <td><?= htmlspecialchars($v['company'] ?? '-') ?></td>
                <td><span class="badge bg-info text-dark"><?= $v['prod_count'] ?></span></td>
                <td>
                    <?php
                    $badge = ['active'=>'success','blocked'=>'danger','pending'=>'warning'];
                    echo "<span class='badge bg-{$badge[$v['status']]}'>".ucfirst($v['status'])."</span>";
                    ?>
                </td>
                <td><?= date('d M Y', strtotime($v['created_at'])) ?></td>
                <td class="d-flex gap-1 flex-wrap">
                    <?php if($v['status']==='pending'): ?>
                        <a href="?action=approve&id=<?= $v['id'] ?>" class="btn btn-sm btn-success">Approve</a>
                    <?php elseif($v['status']==='active'): ?>
                        <a href="?action=block&id=<?= $v['id'] ?>" class="btn btn-sm btn-warning" onclick="return confirm('Block vendor?')">Block</a>
                    <?php else: ?>
                        <a href="?action=unblock&id=<?= $v['id'] ?>" class="btn btn-sm btn-success">Unblock</a>
                    <?php endif; ?>
                    <a href="?action=delete&id=<?= $v['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete vendor?')">Delete</a>
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