<?php
function findAllCustomers()
{
    global $db;
    $sql = "SELECT * FROM customers order by id";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findCustomerById($id)
{
    global $db;
    $sql = "SELECT * FROM customers WHERE id = $id LIMIT 1";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findOrderByCustomersId($customer)
{
    global $db;
    $sql = "SELECT customers.* , orders.id , orders.total_product , orders.total_payment
            FROM customers
            INNER JOIN orders ON orders.customer_id = $customer ORDER BY orders.id DESC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findProductsByOrderId($orderId)
{
    global $db;
    $sql = "SELECT products.name_product, products.price, products.gambar , order_product.quantity, (order_product.quantity * products.price) as subtotal
            FROM order_product
            INNER JOIN products ON order_product.product_id = products.id
            WHERE order_product.order_id = $orderId";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function createCategory()
{
    $createUser = db->prepare("INSERT INTO users (nama,jenis_kelamin) values (:nama,:jenis_kelamin)");
    $createUser->execute([
        "nama" => $_POST['nama'],
        "jenis_kelamin" => $_POST['jenis_kelamin']
    ]);
}
