<?php
// includes/config.php
define('DB_HOST', 'sql113.infinityfree.com');
define('DB_USER', 'if0_41568620');
define('DB_PASS', 'H67oIB99yv47B');
define('DB_NAME', 'if0_41568620_tech_event_db');
define('SITE_URL', 'http://if0_41568620.infinityfreeapp.com');
define('SITE_NAME', 'TechEvent Manager');

session_start();

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Helper functions
function redirect($url) {
    header("Location: $url");
    exit();
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function isUser() {
    return isset($_SESSION['user_id']);
}

function isVendor() {
    return isset($_SESSION['vendor_id']);
}

function sanitize($conn, $data) {
    return $conn->real_escape_string(htmlspecialchars(strip_tags(trim($data))));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function showFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $cls = $f['type'] === 'success' ? 'alert-success' : ($f['type'] === 'error' ? 'alert-danger' : 'alert-info');
        echo "<div class='alert $cls alert-dismissible fade show' role='alert'>{$f['message']}
              <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

function getCartCount($conn, $user_id) {
    $r = $conn->query("SELECT SUM(quantity) as cnt FROM cart WHERE user_id=$user_id");
    $row = $r->fetch_assoc();
    return $row['cnt'] ?? 0;
}