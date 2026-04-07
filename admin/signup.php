<?php
// admin/signup.php
require_once '../includes/config.php';
if (!isAdmin()) redirect(SITE_URL . '/admin/login.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = sanitize($conn, $_POST['name']);
    $email = sanitize($conn, $_POST['email']);
    $pass  = hashPassword($_POST['password']);
    if ($conn->query("INSERT INTO admins(name,email,password) VALUES('$name','$email','$pass')")) {
        flash('success','New admin added.');
        redirect(SITE_URL . '/admin/dashboard.php');
    } else { $error = "Email already exists."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add Admin – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header"><h4><i class="bi bi-person-plus-fill me-2 text-primary"></i>Add Admin</h4></div>
    <div class="form-card">
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-4"><label class="form-label fw-semibold">Password</label><input type="password" name="password" class="form-control" required minlength="6"></div>
            <button class="btn btn-primary w-100">Create Admin</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>