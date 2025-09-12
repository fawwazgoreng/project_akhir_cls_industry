<?php
session_start();

// handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $_SESSION['cart'][] = [
      'name' => $_POST['name'],
      'price' => (float)$_POST['price'],
      'qty'   => 1,
      'discount' => 0
    ];
  }
  if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['id'];
    $_SESSION['cart'][$id]['qty'] = (int)$_POST['qty'];
    $_SESSION['cart'][$id]['discount'] = (int)$_POST['discount'];
  }
  if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    unset($_SESSION['cart'][$id]);
  }
}

$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart as $item) {
  $subtotal += ($item['price'] - ($item['price'] * $item['discount'] / 100)) * $item['qty'];
}
$tax = $subtotal * 0.1;
$total = $subtotal + $tax;

$menus = [
  ["name" => "Schezwan Egg Noodles", "price" => 25, "img" => "https://source.unsplash.com/200x200/?noodles"],
  ["name" => "Spicy Shrimp Soup", "price" => 50, "img" => "https://source.unsplash.com/200x200/?shrimp"],
  ["name" => "Thai Style Fried Noodles", "price" => 50, "img" => "https://source.unsplash.com/200x200/?thai-food"],
  ["name" => "Fried Basil", "price" => 75, "img" => "https://source.unsplash.com/200x200/?basil"],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>POS - Transaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

  <div class="flex relative flex-col flex-nowrap h-screen">
    <div class="bg-white p-4 absolute font-bold h-14 text-xl w-full text-orange-500">Restro POS</div>
    <div class="flex flex-row">
    <aside class="w-full h-screen md:w-40 bg-white shadow-md flex flex-col">
      <nav class="flex-1 space-y-1">
        <a href="#" class="flex mt-14 items-center flex-col px-4 py-2 bg-orange-100 text-orange-600">🏠 <p>Home</p></a>
        <a href="#" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">👥 <p>Customer</p></a>
        <a href="#" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">🪙 <p>Cashier</p></a>
        <a href="#" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">📦 <p>Orders</p></a>
        <a href="#" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">⚙️ <p>Settings</p></a>
      </nav>
      <a href="logout.php" class="flex itmes-center flex-col px-4 py-2 text-center rounded-lg">❌ <p>Log out</p></a>
    </aside>
    <main class="flex-1 p-4 overflow-y-auto mt-14">
      <div class="flex space-x-2 mb-4">
        <button class="px-4 py-2 bg-orange-500 text-white rounded-lg">Lunch</button>
        <button class="px-4 py-2 bg-gray-200 rounded-lg">Breakfast</button>
        <button class="px-4 py-2 bg-gray-200 rounded-lg">Supper</button>
        <button class="px-4 py-2 bg-gray-200 rounded-lg">Desserts</button>
        <button class="px-4 py-2 bg-gray-200 rounded-lg">Beverages</button>
      </div>
      <!-- Menu Grid -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php foreach ($menus as $m) { ?>
          <div class="bg-white shadow rounded-xl p-3 flex flex-col items-center">
            <img src="<?= $m['img'] ?>" class="w-28 h-28 object-cover rounded-lg mb-2">
            <div class="font-semibold text-center text-sm"><?= $m['name'] ?></div>
            <div class="text-gray-600">$<?= number_format($m['price'], 2) ?></div>
            <form method="post" class="mt-2">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="name" value="<?= $m['name'] ?>">
              <input type="hidden" name="price" value="<?= $m['price'] ?>">
              <button type="submit" class="px-3 py-1 bg-orange-500 text-white text-sm rounded-lg">Add</button>
            </form>
          </div>
        <?php } ?>
      </div>
    </main>
    <aside class=" w-full md:w-96 bg-white shadow-lg p-4 flex flex-col">
      <h2 class="font-bold text-lg mb-4 mt-12">Order List</h2>
      <div class="flex-1 space-y-3 overflow-y-auto">
        <?php foreach ($cart as $id => $item) {
          $finalPrice = ($item['price'] - ($item['price'] * $item['discount'] / 100)) * $item['qty'];
        ?>
          <div class="border rounded-lg p-2">
            <form method="post" class="space-y-1">
              <div class="flex justify-between items-center">
                <p class="font-semibold"><?= htmlspecialchars($item['name']) ?></p>
                <button type="submit" name="action" value="delete" class="text-red-500 text-sm">✕</button>
              </div>
              <div class="flex space-x-2 text-sm">
                <input type="hidden" name="id" value="<?= $id ?>">
                <label>Qty:</label>
                <input type="number" name="qty" value="<?= $item['qty'] ?>" class="w-14 border rounded px-1">
                <label>Disc%:</label>
                <input type="number" name="discount" value="<?= $item['discount'] ?>" class="w-14 border rounded px-1">
                <button type="submit" name="action" value="update" class="px-2 bg-green-500 text-white rounded">✔</button>
              </div>
              <p class="text-right text-sm text-gray-600">= $<?= number_format($finalPrice, 2) ?></p>
            </form>
          </div>
        <?php } ?>
      </div>

      <div class="mt-4 border-t pt-4 space-y-1">
        <div class="flex justify-between text-sm">
          <span>Subtotal</span>
          <span>$<?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="flex justify-between text-sm">
          <span>Pajak (11%)</span>
          <span>$<?= number_format($tax, 2) ?></span>
        </div>
        <div class="flex justify-between font-bold">
          <span>Payable Amount</span>
          <span>$<?= number_format($total, 2) ?></span>
        </div>
        <div class="flex space-x-2 mt-3">
          <button class="flex-1 py-2 bg-gray-300 rounded-lg">Hold Order</button>
          <button class="flex-1 py-2 bg-green-500 text-white rounded-lg">Proceed</button>
        </div>
      </div>
    </aside>
    </div>
  </div>

</body>

</html>