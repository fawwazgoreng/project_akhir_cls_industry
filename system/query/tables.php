<?php
function findAllTables($category)
{
    global $db;

    $sql = "
        SELECT
            orders.id AS order_id,
            orders.customer_id,
            orders.total_payment,
            orders.created_at,
            products.id AS product_id,
            order_product.quantity,
            products.name_product,
            products.stock,
            products.price,
            categories.categori_name
        FROM orders
        INNER JOIN order_product ON orders.id = order_product.order_id
        INNER JOIN products ON order_product.product_id = products.id
        INNER JOIN categories ON products.categories_id = categories.id
    ";

    if ($category !== 'all') {
        $sql .= " WHERE categories.categori_name = :category";
        $stmt = $db->prepare($sql);
        $stmt->execute([':category' => $category]);
    } else {
        $stmt = $db->query($sql);
    }

    return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}
