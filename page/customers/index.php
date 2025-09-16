<?php
session_start();
include __DIR__ . "/../../system/action.php";
include __DIR__ . "/../../actions/products/cart.php";
useQuery('product.php');
useQuery('category.php');

$category = $_GET['category'] ?? 'all';

$categories = findAllCategories();
$menus = findProductsByCategoryName($category);
$cart  = $_SESSION['cart'] ?? [];

$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += ($item['price'] - ($item['price'] * $item['discount'] / 100)) * $item['qty'];
}
$tax   = $subtotal * 0.11;
$total = $subtotal + $tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>POS - Transaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 capitalize">
  <div class="flex relative flex-col flex-nowrap h-screen">
    <div class="bg-white p-4 absolute font-bold h-14 text-xl w-full text-orange-500">Restro POS</div>
    <div class="flex flex-row">
      <aside class="w-full h-screen md:w-40 bg-white shadow-md flex flex-col">
        <nav class="flex-1 space-y-1">
          <a href="index.php?view=dashboard" class="flex mt-14 items-center flex-col px-4 py-2 hover:bg-orange-100">🏠 <p>Home</p></a>
          <a href="index.php?view=customers" class="flex items-center flex-col px-4 py-2 bg-orange-100 text-orange-600">👥 <p>Customer</p></a>
          <a href="index.php?view=tables" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">📑 <p>Tables</p></a>
          <a href="index.php?view=chasier" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">🪙 <p>Cashier</p></a>
          <a href="index.php?view=orders" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">📦 <p>Orders</p></a>
          <a href="index.php?view=settings" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">⚙️ <p>Settings</p></a>
        </nav>
        <a href="logout.php" class="flex items-center flex-col px-4 py-2 text-center rounded-lg">❌ <p>Log out</p></a>
      </aside>
      <main class="flex-1 p-4 overflow-y-auto mt-14">
      </main>
    </div>
  </div>
</body>
</html>