<?php
function findAllProducts() {
    global $db;
    $stmt = $db->query("SELECT * FROM products");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createProducts() {
    global $db;
    $stmt = $db->prepare("INSERT INTO products
        (id, name_product, categories_id, price, stock, gambar) 
        VALUES (NULL, :name_product, :categories_id, :price, :stock, :gambar)");
    $stmt->execute([
        "name_product"   => $_POST['name_product'],
        "categories_id"  => $_POST['categories_id'],
        "price"          => $_POST['price'],
        "stock"          => $_POST['stock'],
        "gambar"         => $_POST['gambar'],
    ]);
}
