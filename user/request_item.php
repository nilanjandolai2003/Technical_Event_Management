<?php
// user/request_item.php
require_once '../includes/config.php';
if (!isUser()) redirect(SITE_URL . '/user/login.php');
$uid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name  = sanitize($conn, $_POST['item_name']);
    $desc       = sanitize($conn, $_POST['description']);
    $vendor_id  = !empty($_POST['vendor_id']) ? (int)$_POST['vendor_id'] : 'NULL';
    $conn->query("INSERT INTO item_requests(user_id,vendor_id,item_name,description) VALUES($uid,$vendor_id,'$item_name','$desc')");
    flash('success','Item request submitted successfully!');
    redirect(SITE_URL . '/user/request_item.php');
}

$vendors   = $conn->query("SELECT id,name,company FROM vendors WHERE status='active' ORDER BY name");
$myRequests = $conn->query("SELECT r.*,v.name as vname FROM item_requests r LEFT JOIN vendors v ON r.vendor_id=v.id WHERE r.user_id=$uid ORDER BY r.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Request an Item – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container py-4">
    <?php showFlash(); ?>
    <div class="row g-4">
        <div class="col-md-5">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <h5 class="fw-bold mb-1"><i class="bi bi-envelope-plus me-2 text-primary"></i>Request an Item</h5>
                <p class="text-muted mb-4" style="font-size:.9rem">Can't find what you need? Request it from a vendor.</p>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Item Name *</label>
                        <input type="text" name="item_name" class="form-control" placeholder="e.g. Stage Lighting Kit" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Preferred Vendor <span class="text-muted">(optional)</span></label>
                        <select name="vendor_id" class="form-select">
                            <option value="">Any Vendor</option>
                            <?php while($v=$vendors->fetch_assoc()): ?>
                            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?><?= $v['company']?' – '.htmlspecialchars($v['company']):'' ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description / Requirements</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Describe what you need in detail..."></textarea>
                    </div>
                    <button class="btn btn-primary w-100"><i class="bi bi-send me-2"></i>Submit Request</button>
                </form>
            </div>
        </div>
        <div class="col-md-7">
            <h6 class="fw-bold mb-3">My Requests</h6>
            <?php if($myRequests->num_rows === 0): ?>
            <div class="text-center py-5 bg-white rounded-3 shadow-sm">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-3">No requests yet.</p>
            </div>
            <?php else: ?>
            <?php while($r = $myRequests->fetch_assoc()): ?>
            <div class="bg-white rounded-3 shadow-sm p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-semibold"><?= htmlspecialchars($r['item_name']) ?></div>
                        <small class="text-muted"><?= $r['vname'] ? 'Vendor: '.htmlspecialchars($r['vname']) : 'Any Vendor' ?></small>
                        <?php if($r['description']): ?>
                        <p class="text-muted mt-1 mb-0" style="font-size:.85rem"><?= htmlspecialchars(substr($r['description'],0,120)) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="text-end ms-3">
                        <span class="badge badge-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span>
                        <div class="text-muted mt-1" style="font-size:.8rem"><?= date('d M Y', strtotime($r['created_at'])) ?></div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>