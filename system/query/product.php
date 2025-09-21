<?php
function findAllProducts()
{
    global $db;
    $sql = "SELECT products.*, categories.categori_name 
            FROM products
            INNER JOIN categories ON categories.id = products.categories_id";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findProductsById($id)
{
    global $db;
    $sql = "SELECT * FROM products WHERE id = $id";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function createProduct($data)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO products 
        (name_product, price, stock, categories_id, gambar)
        VALUES (:name_product, :price, :stock, :categories_id, :gambar)");
    return $stmt->execute([
        ":name_product"   => $data['name_product'],
        ":price"          => $data['price'],
        ":stock"          => $data['stock'],
        ":categories_id"  => $data['categories_id'],
        ":gambar"         => $data['gambar'],
    ]);
}
