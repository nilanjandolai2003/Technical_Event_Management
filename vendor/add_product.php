<?php
// vendor/add_product.php
require_once '../includes/config.php';
if (!isVendor()) redirect(SITE_URL . '/vendor/login.php');
$vid = $_SESSION['vendor_id'];

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = sanitize($conn, $_POST['name']);
    $desc     = sanitize($conn, $_POST['description']);
    $price    = (float)$_POST['price'];
    $stock    = (int)$_POST['stock'];
    $category = sanitize($conn, $_POST['category']);
    $image    = '';

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../assets/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext  = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file = uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $file)) {
            $image = $file;
        }
    }

    if ($conn->query("INSERT INTO products(vendor_id,name,description,price,stock,category,image) VALUES($vid,'$name','$desc',$price,$stock,'$category','$image')")) {
        flash('success', 'Product added successfully!');
        redirect(SITE_URL . '/vendor/products.php');
    } else {
        $error = 'Failed to add product. Try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add Product – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header">
        <h4><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Add New Product</h4>
        <a href="products.php" class="btn btn-outline-secondary btn-sm">← Back</a>
    </div>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <div class="bg-white rounded-3 shadow-sm p-4" style="max-width:700px">
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Product Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price (₹) *</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Stock Quantity *</label>
                    <input type="number" name="stock" class="form-control" min="0" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select">
                        <option value="">Select Category</option>
                        <option>Electronics</option>
                        <option>Audio / Visual</option>
                        <option>Lighting</option>
                        <option>Furniture</option>
                        <option>Catering</option>
                        <option>Decoration</option>
                        <option>Staging</option>
                        <option>Security</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Describe the product/service..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Product Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-12 mt-2">
                    <button class="btn btn-primary px-5">Add Product</button>
                    <a href="products.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>