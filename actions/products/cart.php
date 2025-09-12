<?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            if($_POST['price'] = 0) {
                exit;
            }
            $_SESSION['cart'][] = [
                'id'       => $_POST['id'],
                'name'     => $_POST['name'],
                'price'    => (float)$_POST['price'],
                'qty'      => 1,
                'discount' => 0
            ];
            break;
        case 'update':
            $id = $_POST['id'];
            $_SESSION['cart'][$id]['qty']      = (int)$_POST['qty'];
            $_SESSION['cart'][$id]['discount'] = (int)$_POST['discount'];
            break;
        case 'delete':
            $id = $_POST['id'];
            unset($_SESSION['cart'][$id]);
            break;
    }
}