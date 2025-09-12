<?php
function findAllOrders()
{
    global $db;
    $sql = "SELECT*
            FROM orders";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createOrders()
{
    global $db;
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        exit("Cart is empty.");
    }
    $cart = $_SESSION['cart'];
    // Get values
    $adminId = $_SESSION['admin_id'] ?? null;
    $customerId = $_SESSION['admin_id'] ?? null;
    $totalProduct = (int)($_POST['total_product'] ?? 0);
    $totalPayment = (float)($_POST['total_payment'] ?? 0);
    try {
        $db->beginTransaction();
        $stmt = $db->prepare("INSERT INTO orders (id, admin_id, customer_id, total_product, total_payment, created_at)
                              VALUES (null ,:admin_id, :customer_id, :total_product, :total_payment, NOW())");
        $stmt->execute([
            ":admin_id" => $adminId,
            ":customer_id" => $customerId,
            ":total_product" => $totalProduct,
            ":total_payment" => $totalPayment,
        ]);

        $orderId = $db->lastInsertId();
        $stmtItem = $db->prepare("INSERT INTO order_product
            (order_id, product_id, quantity, total_price)
            VALUES (:order_id, :product_id, :quantity , :total_price)");
        $stmtUpdateStock = $db->prepare("UPDATE products SET stock = stock - :qty WHERE id = :product_id");
        foreach ($cart as $item) {
            var_dump($item); // Check the keys and values here

            $productId = $item['id'] ?? $item['product_id'] ?? null;
            if ($productId === null) {
                exit('Product ID not found in cart item!');
            }

            $qty = $item['qty'] ?? 0;
            $totalPrice = $item['total_price'] ?? 0;

            $stmtItem->execute([
                ':order_id' => $orderId,
                ':product_id' => $productId,
                ':quantity' => $qty,
                ':total_price' => $totalPrice,
            ]);

            $stmtUpdateStock->execute([
                ':qty' => $qty,
                ':product_id' => $productId,
            ]);
        }

        $db->commit();
        unset($_SESSION['cart']);
    } catch (PDOException $e) {
        $db->rollBack();
        exit("Failed to create order: " . $e->getMessage());
    }
}
