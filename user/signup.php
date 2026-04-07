<?php
// user/signup.php
require_once '../includes/config.php';
if (isUser()) redirect(SITE_URL . '/user/portal.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $phone = sanitize($conn, $_POST['phone']);
    $pass  = hashPassword($_POST['password']);
    if ($conn->query("INSERT INTO users(name,email,phone,password) VALUES('$name','$email','$phone','$pass')")) {
        flash('success', 'Account created! Please login.');
        redirect(SITE_URL . '/user/login.php');
    } else { $error = "Email already registered."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sign Up – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-page">
<div class="form-card w-100" style="max-width:440px">
    <div class="brand-logo"><i class="bi bi-person-plus-fill"></i></div>
    <h5 class="text-center mb-1 fw-bold">Create Account</h5>
    <p class="text-center text-muted mb-4" style="font-size:.9rem">Join TechEvent Manager today</p>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3"><label class="form-label fw-semibold">Full Name</label><input type="text" name="name" class="form-control" placeholder="John Doe" required></div>
        <div class="mb-3"><label class="form-label fw-semibold">Email Address</label><input type="email" name="email" class="form-control" placeholder="you@email.com" required></div>
        <div class="mb-3"><label class="form-label fw-semibold">Phone Number</label><input type="text" name="phone" class="form-control" placeholder="+91 XXXXX XXXXX"></div>
        <div class="mb-4"><label class="form-label fw-semibold">Password</label><input type="password" name="password" class="form-control" placeholder="Min 6 characters" required minlength="6"></div>
        <button class="btn btn-primary w-100 py-2">Create Account</button>
    </form>
    <p class="text-center mt-3" style="font-size:.9rem">Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>