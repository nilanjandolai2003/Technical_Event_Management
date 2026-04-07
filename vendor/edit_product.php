<?php
// vendor/edit_product.php
require_once '../includes/config.php';
if (!isVendor()) redirect(SITE_URL . '/vendor/login.php');
$vid = $_SESSION['vendor_id'];

$pid = (int)($_GET['id'] ?? 0);
$product = $conn->query("SELECT * FROM products WHERE id=$pid AND vendor_id=$vid")->fetch_assoc();
if (!$product) { flash('error','Product not found.'); redirect(SITE_URL.'/vendor/products.php'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = sanitize($conn, $_POST['name']);
    $desc     = sanitize($conn, $_POST['description']);
    $price    = (float)$_POST['price'];
    $stock    = (int)$_POST['stock'];
    $category = sanitize($conn, $_POST['category']);
    $image    = $product['image'];

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../assets/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext  = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file = uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $file)) {
            $image = $file;
        }
    }

    if ($conn->query("UPDATE products SET name='$name',description='$desc',price=$price,stock=$stock,category='$category',image='$image' WHERE id=$pid AND vendor_id=$vid")) {
        flash('success','Product updated!');
        redirect(SITE_URL . '/vendor/products.php');
    } else { $error = 'Update failed.'; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Product – <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <div class="page-header">
        <h4><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Product</h4>
        <a href="products.php" class="btn btn-outline-secondary btn-sm">← Back</a>
    </div>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <div class="bg-white rounded-3 shadow-sm p-4" style="max-width:700px">
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Product Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price (₹) *</label>
                    <input type="number" name="price" class="form-control" step="0.01" value="<?= $product['price'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Stock Quantity *</label>
                    <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select">
                        <?php foreach(['Electronics','Audio / Visual','Lighting','Furniture','Catering','Decoration','Staging','Security','Other'] as $cat): ?>
                        <option <?= $product['category']===$cat?'selected':'' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']??'') ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Product Image</label>
                    <?php if($product['image']): ?>
                    <div class="mb-2"><img src="<?= SITE_URL ?>/assets/uploads/<?= $product['image'] ?>" style="height:80px;border-radius:8px"></div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-12 mt-2">
                    <button class="btn btn-primary px-5">Update Product</button>
                    <a href="products.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>