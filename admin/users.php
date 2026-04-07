<?php
// admin/users.php
require_once '../includes/config.php';
if (!isAdmin()) redirect(SITE_URL . '/admin/login.php');

// Block/unblock/delete
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    if ($action === 'block') {
        $conn->query("UPDATE users SET status='blocked' WHERE id=$id");
        flash('success','User blocked.');
    } elseif ($action === 'unblock') {
        $conn->query("UPDATE users SET status='active' WHERE id=$id");
        flash('success','User unblocked.');
    } elseif ($action === 'delete') {
        $conn->query("DELETE FROM users WHERE id=$id");
        flash('success','User deleted.');
    }
    redirect(SITE_URL . '/admin/users.php');
}

$users = $conn->query("SELECT u.*, (SELECT COUNT(*) FROM orders WHERE user_id=u.id) as order_count FROM users u ORDER BY u.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Users – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header">
        <h4><i class="bi bi-people-fill me-2 text-primary"></i>Manage Users</h4>
    </div>
    <?php showFlash(); ?>
    <div class="table-container">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
            <tbody>
            <?php while($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                <td><span class="badge bg-primary"><?= $u['order_count'] ?></span></td>
                <td>
                    <?php if($u['status']==='active'): ?>
                        <span class="badge bg-success">Active</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Blocked</span>
                    <?php endif; ?>
                </td>
                <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <?php if($u['status']==='active'): ?>
                        <a href="?action=block&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning" onclick="return confirm('Block this user?')">Block</a>
                    <?php else: ?>
                        <a href="?action=unblock&id=<?= $u['id'] ?>" class="btn btn-sm btn-success">Unblock</a>
                    <?php endif; ?>
                    <a href="?action=delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete permanently?')">Delete</a>
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