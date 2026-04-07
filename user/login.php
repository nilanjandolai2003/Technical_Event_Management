<?php
// user/login.php
require_once '../includes/config.php';
if (isUser()) redirect(SITE_URL . '/user/portal.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];
    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($res->num_rows === 1) {
        $u = $res->fetch_assoc();
        if ($u['status'] === 'blocked') {
            $error = "Your account has been blocked. Contact admin.";
        } elseif (verifyPassword($password, $u['password'])) {
            $_SESSION['user_id']   = $u['id'];
            $_SESSION['user_name'] = $u['name'];
            redirect(SITE_URL . '/user/portal.php');
        } else { $error = "Invalid password."; }
    } else { $error = "No account found with this email."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>User Login – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-page">
<div class="form-card w-100" style="max-width:420px">
    <div class="brand-logo"><i class="bi bi-person-circle"></i></div>
    <h5 class="text-center mb-1 fw-bold">Welcome Back!</h5>
    <p class="text-center text-muted mb-4" style="font-size:.9rem">Sign in to browse & order</p>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="you@email.com" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button class="btn btn-primary w-100 py-2">Login</button>
    </form>
    <p class="text-center mt-3" style="font-size:.9rem">New user? <a href="signup.php">Create Account</a></p>
    <div class="text-center mt-2"><a href="<?= SITE_URL ?>/index.php" class="text-muted" style="font-size:.85rem">← Back to Home</a></div>
</div>
</body>
</html>