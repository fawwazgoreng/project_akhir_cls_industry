<?php
session_start();

include __DIR__ . "/../../system/action.php";
useQuery('order.php'); // load query helper

$action = $_POST['action'] ?? null;

if ($action === 'add') {
    // if (empty($_SESSION['cart'])) {
        // redirect("/index.php?view=dashboard&msg=Cart+is+empty");
    // }

    foreach ($_SESSION['cart'] as $id => &$item) {
        $discount = min(max(0, $item['discount'] ?? 0), 100);
        $priceAfterDiscount = $item['price'] - ($item['price'] * $discount / 100);
        $item['total_price'] = $priceAfterDiscount * $item['qty'];
    }
    unset($item);
    try {
        $orderId = createOrders();
        redirect("index.php?view=transaksi&order_id={$orderId}&msg=Order+success");
        unset($_SESSION['cart']);
    } catch (Exception $e) {
        redirect("/index.php?view=dashboard&msg=" . urlencode("Error: " . $e->getMessage()));
    }
}

// redirect("index.php?view=dashboard");
exit;
