<?php
// vendor/requests.php
require_once '../includes/config.php';
if (!isVendor()) redirect(SITE_URL . '/vendor/login.php');
$vid = $_SESSION['vendor_id'];

$requests = $conn->query("SELECT r.*,u.name as uname FROM item_requests r JOIN users u ON r.user_id=u.id WHERE r.vendor_id=$vid OR r.vendor_id IS NULL ORDER BY r.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Item Requests – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header"><h4><i class="bi bi-envelope-fill me-2 text-primary"></i>Item Requests from Users</h4></div>
    <div class="table-container">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>User</th><th>Item Requested</th><th>Description</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            <?php while($r = $requests->fetch_assoc()): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['uname']) ?></td>
                <td><strong><?= htmlspecialchars($r['item_name']) ?></strong></td>
                <td><?= htmlspecialchars(substr($r['description']??'',0,80)) ?></td>
                <td><span class="badge badge-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
                <td><?= date('d M Y', strtotime($r['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>