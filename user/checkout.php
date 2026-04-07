<?php
// user/checkout.php
require_once '../includes/config.php';
if (!isUser()) redirect(SITE_URL . '/user/login.php');
$uid = $_SESSION['user_id'];

// Get cart
$items = $conn->query("SELECT c.*,p.name,p.price,p.stock,p.vendor_id FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=$uid");
$cartRows = []; $total = 0;
while($row = $items->fetch_assoc()) { $cartRows[] = $row; $total += $row['price'] * $row['quantity']; }
if (empty($cartRows)) { flash('error','Your cart is empty.'); redirect(SITE_URL.'/user/cart.php'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = sanitize($conn, $_POST['address']);
    $payment = sanitize($conn, $_POST['payment_method']);
    if (empty($address)) { $error = "Delivery address is required."; }
    else {
        // Create order
        $conn->query("INSERT INTO orders(user_id,total_amount,payment_method,delivery_address) VALUES($uid,$total,'$payment','$address')");
        $oid = $conn->insert_id;
        // Create order items & reduce stock
        foreach($cartRows as $item) {
            $pid = $item['product_id'];
            $qty = $item['quantity'];
            $price = $item['price'];
            $vid = $item['vendor_id'];
            $conn->query("INSERT INTO order_items(order_id,product_id,vendor_id,quantity,price) VALUES($oid,$pid,$vid,$qty,$price)");
            $conn->query("UPDATE products SET stock=stock-$qty WHERE id=$pid AND stock>=$qty");
        }
        // Clear cart
        $conn->query("DELETE FROM cart WHERE user_id=$uid");
        redirect(SITE_URL . '/user/success.php?order_id=' . $oid);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Checkout – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-credit-card me-2 text-primary"></i>Checkout</h4>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <div class="row g-4">
        <div class="col-md-7">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <h6 class="fw-bold mb-3">Delivery Details</h6>
                <form method="POST" id="checkoutForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Delivery Address *</label>
                        <textarea name="address" class="form-control" rows="4" placeholder="Enter full delivery address..." required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Payment Method</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="payment_method" id="cash" value="cash" checked>
                                <label class="btn btn-outline-secondary w-100 py-3" for="cash">
                                    <i class="bi bi-cash d-block fs-4 mb-1"></i>Cash on Delivery
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="payment_method" id="upi" value="upi">
                                <label class="btn btn-outline-secondary w-100 py-3" for="upi">
                                    <i class="bi bi-phone d-block fs-4 mb-1"></i>UPI Payment
                                </label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 py-3 fs-5">
                        <i class="bi bi-check-circle me-2"></i>Place Order – ₹<?= number_format($total,2) ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="col-md-5">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <h6 class="fw-bold mb-3">Order Summary (<?= count($cartRows) ?> items)</h6>
                <?php foreach($cartRows as $item): ?>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <div style="font-size:.9rem;font-weight:600"><?= htmlspecialchars($item['name']) ?></div>
                        <small class="text-muted">Qty: <?= $item['quantity'] ?> × ₹<?= number_format($item['price'],2) ?></small>
                    </div>
                    <span class="fw-semibold">₹<?= number_format($item['price']*$item['quantity'],2) ?></span>
                </div>
                <?php endforeach; ?>
                <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
                    <span>Total</span><span class="text-primary">₹<?= number_format($total,2) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>