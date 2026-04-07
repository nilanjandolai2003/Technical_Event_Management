<?php
// user/cart.php
require_once '../includes/config.php';
if (!isUser()) redirect(SITE_URL . '/user/login.php');
$uid = $_SESSION['user_id'];

// Update quantity
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (isset($_POST['update'])) {
        foreach ($_POST['qty'] as $cid => $qty) {
            $cid = (int)$cid; $qty = (int)$qty;
            if ($qty <= 0) {
                $conn->query("DELETE FROM cart WHERE id=$cid AND user_id=$uid");
            } else {
                $conn->query("UPDATE cart SET quantity=$qty WHERE id=$cid AND user_id=$uid");
            }
        }
        flash('success','Cart updated.');
    }
    redirect(SITE_URL . '/user/cart.php');
}

// Remove item
if (isset($_GET['remove'])) {
    $cid = (int)$_GET['remove'];
    $conn->query("DELETE FROM cart WHERE id=$cid AND user_id=$uid");
    flash('success','Item removed.');
    redirect(SITE_URL . '/user/cart.php');
}

$items = $conn->query("SELECT c.*,p.name,p.price,p.image,p.stock FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=$uid");
$cartRows = []; $total = 0;
while($row = $items->fetch_assoc()) { $cartRows[] = $row; $total += $row['price'] * $row['quantity']; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Cart – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-cart3 me-2 text-primary"></i>My Cart</h4>
    <?php showFlash(); ?>

    <?php if(empty($cartRows)): ?>
    <div class="text-center py-5">
        <i class="bi bi-cart-x display-3 text-muted"></i>
        <p class="text-muted mt-3 mb-4">Your cart is empty.</p>
        <a href="portal.php" class="btn btn-primary">Browse Products</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <div class="col-md-8">
            <form method="POST">
            <?php foreach($cartRows as $item): ?>
            <div class="cart-item d-flex align-items-center gap-3">
                <?php if($item['image'] && file_exists('../assets/uploads/'.$item['image'])): ?>
                    <img src="<?= SITE_URL ?>/assets/uploads/<?= htmlspecialchars($item['image']) ?>" style="width:70px;height:70px;object-fit:cover;border-radius:10px">
                <?php else: ?>
                    <div style="width:70px;height:70px;background:#dbeafe;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:var(--primary)"><i class="bi bi-box-seam"></i></div>
                <?php endif; ?>
                <div class="flex-fill">
                    <div class="fw-semibold"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="text-primary fw-bold">₹<?= number_format($item['price'],2) ?></div>
                </div>
                <div style="width:100px">
                    <input type="number" name="qty[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="0" max="<?= $item['stock'] ?>" class="form-control form-control-sm text-center">
                </div>
                <div class="fw-bold" style="min-width:80px;text-align:right">₹<?= number_format($item['price']*$item['quantity'],2) ?></div>
                <a href="?remove=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove item?')"><i class="bi bi-trash"></i></a>
            </div>
            <?php endforeach; ?>
            <button name="update" class="btn btn-outline-primary mt-3"><i class="bi bi-arrow-clockwise me-1"></i>Update Cart</button>
            </form>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <h6 class="fw-bold mb-3">Order Summary</h6>
                <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span>₹<?= number_format($total,2) ?></span></div>
                <div class="d-flex justify-content-between mb-2 text-success"><span>Delivery</span><span>Free</span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5 mb-4"><span>Total</span><span class="text-primary">₹<?= number_format($total,2) ?></span></div>
                <a href="checkout.php" class="btn btn-primary w-100 py-2"><i class="bi bi-credit-card me-2"></i>Proceed to Checkout</a>
                <a href="portal.php" class="btn btn-outline-secondary w-100 mt-2">Continue Shopping</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>