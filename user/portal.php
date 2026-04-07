<?php
// user/portal.php
require_once '../includes/config.php';
if (!isUser()) redirect(SITE_URL . '/user/login.php');

// Handle add to cart
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    $uid = $_SESSION['user_id'];
    $existing = $conn->query("SELECT * FROM cart WHERE user_id=$uid AND product_id=$pid")->fetch_assoc();
    if ($existing) {
        $conn->query("UPDATE cart SET quantity=quantity+1 WHERE user_id=$uid AND product_id=$pid");
    } else {
        $conn->query("INSERT INTO cart(user_id,product_id,quantity) VALUES($uid,$pid,1)");
    }
    flash('success', 'Added to cart!');
    redirect(SITE_URL . '/user/portal.php' . (isset($_GET['category']) ? '?category='.urlencode($_GET['category']) : ''));
}

// Filters
$search   = sanitize($conn, $_GET['search'] ?? '');
$category = sanitize($conn, $_GET['category'] ?? '');
$where    = "WHERE p.status='active' AND v.status='active'";
if ($search)   $where .= " AND p.name LIKE '%$search%'";
if ($category) $where .= " AND p.category='$category'";

$products   = $conn->query("SELECT p.*,v.name as vname FROM products p JOIN vendors v ON p.vendor_id=v.id $where ORDER BY p.created_at DESC");
$categories = $conn->query("SELECT DISTINCT category FROM products WHERE status='active' AND category!='' ORDER BY category");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Browse Products – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container py-4">
    <?php showFlash(); ?>

    <!-- Search & Filter Bar -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
            </div>
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php while($c=$categories->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($c['category']) ?>" <?= $category===$c['category']?'selected':'' ?>><?= htmlspecialchars($c['category']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-primary flex-fill">Filter</button>
            <a href="portal.php" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
        </div>
    </form>

    <!-- Products Grid -->
    <div class="row g-3">
    <?php
    $count = 0;
    while($p = $products->fetch_assoc()):
        $count++;
    ?>
    <div class="col-md-4 col-lg-3 col-6">
        <div class="product-card">
            <?php if($p['image'] && file_exists('../assets/uploads/'.$p['image'])): ?>
                <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <?php else: ?>
                <div class="no-img"><i class="bi bi-box-seam"></i></div>
            <?php endif; ?>
            <div class="card-body">
                <div class="vendor-name"><i class="bi bi-shop me-1"></i><?= htmlspecialchars($p['vname']) ?></div>
                <div class="fw-semibold mb-1" style="font-size:.95rem"><?= htmlspecialchars($p['name']) ?></div>
                <?php if($p['category']): ?>
                <span class="badge bg-light text-dark mb-2" style="font-size:.75rem"><?= htmlspecialchars($p['category']) ?></span>
                <?php endif; ?>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="price">₹<?= number_format($p['price'],2) ?></span>
                    <span class="badge <?= $p['stock']>0?'bg-success':'bg-danger' ?>"><?= $p['stock']>0?$p['stock'].' left':'Out' ?></span>
                </div>
                <?php if($p['stock'] > 0): ?>
                <form method="POST" class="mt-2">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <button class="btn btn-primary btn-sm w-100"><i class="bi bi-cart-plus me-1"></i>Add to Cart</button>
                </form>
                <?php else: ?>
                <button class="btn btn-secondary btn-sm w-100 mt-2" disabled>Out of Stock</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
    <?php if($count === 0): ?>
    <div class="col-12 text-center py-5">
        <i class="bi bi-inbox display-3 text-muted"></i>
        <p class="text-muted mt-3">No products found. <a href="portal.php">Clear filters</a></p>
    </div>
    <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>