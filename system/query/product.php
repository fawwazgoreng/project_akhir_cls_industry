<?php
function findAllProducts()
{
    global $db;
    $sql = "SELECT p.*, categories.categori_name 
            FROM products p
            INNER JOIN categories c ON categories.id = products.categories_id";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createProducts()
{
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
