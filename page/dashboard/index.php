<?php
session_start();
include __DIR__ . "/../../system/action.php";
include __DIR__ . "/../../actions/products/cart.php";
useQuery('product.php');

$menus = findAllProducts();
$cart  = $_SESSION['cart'] ?? [];

$category = $_GET['category'] ?? 'all';
if (isset($_GET['category']) && $_GET['category'] !== '') {
  $category = $_GET['category'];
  $menus = array_filter($menus, function ($m) use ($category) {
    return strtolower($m['categories_id']) === strtolower($category);
  });
}

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

<body class="bg-gray-100">
  <div class="flex relative flex-col flex-nowrap h-screen">
    <!-- Header -->
    <div class="bg-white p-4 absolute font-bold h-14 text-xl w-full text-orange-500">Restro POS</div>

    <div class="flex flex-row">
      <!-- Sidebar -->
      <aside class="w-full h-screen md:w-40 bg-white shadow-md flex flex-col">
        <nav class="flex-1 space-y-1">
          <a href="#" class="flex mt-14 items-center flex-col px-4 py-2 bg-orange-100 text-orange-600">🏠 <p>Home</p></a>
          <a href="#" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">👥 <p>Customer</p></a>
          <a href="#" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">📑 <p>Tables</p></a>
          <a href="#" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">🪙 <p>Cashier</p></a>
          <a href="#" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">📦 <p>Orders</p></a>
          <a href="#" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">⚙️ <p>Settings</p></a>
        </nav>
        <a href="logout.php" class="flex itmes-center flex-col px-4 py-2 text-center rounded-lg">❌ <p>Log out</p></a>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-4 overflow-y-auto mt-14">
<div class="flex space-x-2 mb-4">
  <a href="index.php?view=dashboard"
     class="px-4 py-2 rounded-lg <?= $category === 'all' ? 'bg-orange-500 text-white' : 'bg-gray-200' ?>">
     Semua
  </a>
  <a href="index.php?view=dashboard&category=sarapan"
     class="px-4 py-2 rounded-lg <?= $category === 'sarapan' ? 'bg-orange-500 text-white' : 'bg-gray-200' ?>">
     Sarapan
  </a>
  <a href="index.php?view=dashboard&category=siang"
     class="px-4 py-2 rounded-lg <?= $category === 'siang' ? 'bg-orange-500 text-white' : 'bg-gray-200' ?>">
     Makan siang
  </a>
  <a href="index.php?view=dashboard&category=sore"
     class="px-4 py-2 rounded-lg <?= $category === 'sore' ? 'bg-orange-500 text-white' : 'bg-gray-200' ?>">
     Makan sore
  </a>
  <a href="index.php?view=dashboard&category=malam"
     class="px-4 py-2 rounded-lg <?= $category === 'malam' ? 'bg-orange-500 text-white' : 'bg-gray-200' ?>">
     Makan malam
  </a>
  <a href="index.php?view=dashboard&category=cemilan"
     class="px-4 py-2 rounded-lg <?= $category === 'cemilan' ? 'bg-orange-500 text-white' : 'bg-gray-200' ?>">
     Cemilan
  </a>
  <a href="index.php?view=dashboard&category=dessert"
     class="px-4 py-2 rounded-lg <?= $category === 'dessert' ? 'bg-orange-500 text-white' : 'bg-gray-200' ?>">
     Dessert
  </a>
</div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <?php foreach ($menus as $m) { ?>
            <div class="bg-white shadow rounded-xl p-3 flex flex-col items-center">
              <img src="<?= $m['image'] ?>" class="w-28 h-28 object-cover rounded-lg mb-2" alt="<?= htmlspecialchars($m['name']) ?>">
              <div class="font-semibold text-center text-sm"><?= htmlspecialchars($m['name']) ?></div>
              <div class="text-gray-600">Rp. <?= number_format($m['price'], 0, ',', '.') ?></div>
              <form method="post" class="mt-2">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="name" value="<?= htmlspecialchars($m['name']) ?>">
                <input type="hidden" name="price" value="<?= $m['price'] ?>">
                <button type="submit" class="px-3 py-1 bg-orange-500 text-white text-sm rounded-lg">Add</button>
              </form>
            </div>
          <?php } ?>
        </div>
      </main>

      <!-- Cart Sidebar -->
      <aside class="w-full md:w-96 bg-white shadow-lg p-4 flex flex-col">
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
                <p class="text-right text-sm text-gray-600">= Rp.<?= number_format($finalPrice, 0, ',', '.') ?></p>
              </form>
            </div>
          <?php } ?>
        </div>
        <!-- Summary -->
        <div class="mt-4 border-t pt-4 space-y-1">
          <div class="flex justify-between text-sm">
            <span>Subtotal</span>
            <span>Rp.<?= number_format($subtotal, 0, ',', '.') ?></span>
          </div>
          <div class="flex justify-between text-sm">
            <span>Pajak (11%)</span>
            <span>Rp.<?= number_format($tax, 0, ',', '.') ?></span>
          </div>
          <div class="flex justify-between font-bold">
            <span>Payable Amount</span>
            <span>Rp.<?= number_format($total, 0, ',', '.') ?></span>
          </div>
          <div class="flex space-x-2 mt-3">
            <button class="flex-1 py-2 bg-gray-300 rounded-lg">Hold Order</button>
            <button class="flex-1 py-2 bg-green-500 text-white rounded-lg">Proceed</button>
          </div>
        </div>
      </aside>
    </div>
  </div>
  <script>
    document.querySelectorAll(".category-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const category = btn.dataset.category;
        document.querySelectorAll(".menu-item").forEach(item => {
          if (category === "all" || item.dataset.category === category) {
            item.style.display = "flex";
          } else {
            item.style.display = "none";
          }
        });
      });
    });
  </script>
</body>

</html>