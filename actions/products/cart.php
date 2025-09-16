<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'];

    switch ($_POST['action']) {
        case 'add':
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty'] += 1;
            } else {
                $_SESSION['cart'][$id] = [
                    'id'       => $id,
                    'name'     => $_POST['name'],
                    'price'    => (float)$_POST['price'],
                    'qty'      => 1,
                    'discount' => 0
                ];
            }
            break;
        case 'update':
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty']      = (int)$_POST['qty'];
                $_SESSION['cart'][$id]['discount'] = (int)$_POST['discount'];
            }
            break;
        case 'delete':
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
            break;
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
