<?php
session_start();

include __DIR__ . "/../../system/action.php";
useQuery("product.php");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}
$action = $_POST['action'] ?? null;
if ($action !== "add") {
    die("Invalid action.");
}
$name       = $_POST['name_product'] ?? '';
$price      = $_POST['price'] ?? 0;
$stock      = $_POST['stock'] ?? 0;
$categoryId = $_POST['categories_id'] ?? null;
if (empty($name) || empty($price) || empty($stock) || empty($categoryId)) {
    die("Semua data harus diisi!");
}
$imagePath = null;
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . "/../../uploads/products/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileName = time() . "_" . basename($_FILES['gambar']['name']);
    $target   = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
        $imagePath = "uploads/products/" . $fileName;
    } else {
        die("Gagal upload gambar!");
    }
} else {
    die("Gambar wajib diupload!");
}
try {
    createProduct([
        "name_product" => $name,
        "price"        => $price,
        "stock"        => $stock,
        "categories_id"  => $categoryId,
        "gambar"        => $imagePath,
    ]);
    $_SESSION['success'] = "Produk berhasil ditambahkan!";
    header("Location: /index.php?view=dashboard");
    exit;
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
