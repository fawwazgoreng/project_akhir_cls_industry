<?php
function findAllOrders()
{
    global $db;
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findOrderById($orderId)
{
    global $db;
    $stmt = $db->prepare("
        SELECT orders.*, customers.username AS customer_name
        FROM orders
        LEFT JOIN admins ON orders.admin_id = admins.id
        LEFT JOIN customers ON orders.customer_id = customers.id
        WHERE orders.id = :id
    ");
    $stmt->execute([":id" => $orderId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findOrderItems($orderId)
{
    global $db;
    $stmt = $db->prepare("
        SELECT order_product.*, products.name_product, products.price
        FROM order_product
        JOIN products ON order_product.product_id = products.id
        WHERE order_product.order_id = :id
    ");
    $stmt->execute([":id" => $orderId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findLastOrderId()
{
    $orders = findAllOrders();
    if (empty($orders)) {
        return null;
    }
    $lastOrder = end($orders);
    return $lastOrder['id'];
}


function createOrders()
{
    global $db;

    if (empty($_SESSION['cart'])) {
        throw new Exception("Cart is empty.");
    }
    $cart = $_SESSION['cart'];
    $adminId     = $_SESSION['admin_id'] ?? null;
    $customerId  = $_SESSION['customer_id'] ?? $adminId;
    $totalProduct = (int)($_POST['total_product'] ?? 0);
    $totalPayment = (float)($_POST['total_payment'] ?? 0);

    try {
        $db->beginTransaction();

        $stmt = $db->prepare("
            INSERT INTO orders (id,admin_id, customer_id, total_product, total_payment, created_at)
            VALUES (null,:admin_id, :customer_id, :total_product, :total_payment, NOW())
        ");
        $stmt->execute([
            ":admin_id"      => $adminId,
            ":customer_id"   => $customerId,
            ":total_product" => $totalProduct,
            ":total_payment" => $totalPayment,
        ]);

        $orderId = $db->lastInsertId();
        $stmtItem = $db->prepare("
            INSERT INTO order_product (order_id, product_id, quantity, total_price)
            VALUES (:order_id, :product_id, :quantity, :total_price)
        ");
        $stmtUpdateStock = $db->prepare("
            UPDATE products SET stock = stock - :qty WHERE id = :product_id
        ");
        foreach ($cart as $item) {
            $productId = $item['id'] ?? $item['product_id'] ?? null;
            if (!$productId) {
                throw new Exception("Product ID not found in cart item.");
            }
            $qty        = $item['qty'] ?? 0;
            $totalPrice = $item['total_price'] ?? 0;
            $stmtItem->execute([
                ':order_id'   => $orderId,
                ':product_id' => $productId,
                ':quantity'   => $qty,
                ':total_price'=> $totalPrice,
            ]);
            $stmtUpdateStock->execute([
                ':qty'        => $qty,
                ':product_id' => $productId,
            ]);
        }
        $db->commit();
        unset($_SESSION['cart']);
        return $orderId;
    } catch (Exception $e) {
        $db->rollBack();
        throw new Exception("Failed to create order: " . $e->getMessage());
    }
}
