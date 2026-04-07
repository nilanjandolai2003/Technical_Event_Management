<?php
// vendor/signup.php
require_once '../includes/config.php';
if (isVendor()) redirect(SITE_URL . '/vendor/dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($conn, $_POST['name']);
    $email   = sanitize($conn, $_POST['email']);
    $phone   = sanitize($conn, $_POST['phone']);
    $company = sanitize($conn, $_POST['company']);
    $pass    = hashPassword($_POST['password']);
    if ($conn->query("INSERT INTO vendors(name,email,phone,company,password) VALUES('$name','$email','$phone','$company','$pass')")) {
        flash('success','Registration submitted! Wait for admin approval.');
        redirect(SITE_URL . '/vendor/login.php');
    } else { $error = "Email already registered."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Vendor Registration – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-page">
<div class="form-card w-100" style="max-width:480px">
    <div class="brand-logo"><i class="bi bi-shop-window"></i></div>
    <h5 class="text-center mb-1 fw-bold">Become a Vendor</h5>
    <p class="text-center text-muted mb-4" style="font-size:.9rem">Register to sell on our platform</p>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3"><label class="form-label fw-semibold">Full Name</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control"></div>
        <div class="mb-3"><label class="form-label fw-semibold">Company / Business Name</label><input type="text" name="company" class="form-control"></div>
        <div class="mb-4"><label class="form-label fw-semibold">Password</label><input type="password" name="password" class="form-control" required minlength="6"></div>
        <button class="btn btn-primary w-100 py-2">Submit Registration</button>
    </form>
    <p class="text-center mt-3" style="font-size:.9rem">Already registered? <a href="login.php">Login</a></p>
</div>
</body>
</html>