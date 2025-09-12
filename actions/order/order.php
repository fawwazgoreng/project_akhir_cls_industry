<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            $id = $_POST['id'];
            $price = (float)($_POST['price'] ?? 0);

            if ($price == 0) {
                exit('Invalid product price.');
            }

            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty'] += 1;
            } else {
                $_SESSION['cart'][$id] = [
                    'id'       => $id,
                    'name'     => $_POST['name'] ?? '',
                    'price'    => $price,
                    'qty'      => 1,
                    'discount' => 0
                ];
            }
            break;
        case 'update':
            $id = $_POST['id'];
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty'] = max(1, (int)($_POST['qty'] ?? 1)); // minimum 1 qty
                $_SESSION['cart'][$id]['discount'] = max(0, (int)($_POST['discount'] ?? 0)); // minimum 0 discount
            }
            break;
        case 'delete':
            $id = $_POST['id'];
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
            break;
    }
}
