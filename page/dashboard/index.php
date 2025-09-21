<?php
session_start();

// Includes
include __DIR__ . "/../../system/action.php";
include __DIR__ . "/../../actions/products/cart.php";

useQuery('product.php');
useQuery('category.php');

define('TAX_RATE', 0.11);

// Ambil kategori
$categoryParam = $_GET['category'] ?? 'all';
$categories = findAllCategories();

// Produk by kategori
if ($categoryParam === 'all' || !$categoryParam) {
  $menus = findAllProducts();
} else {
  $menus = findProductsByCategoryName($categoryParam);
}

// Cart
$cart = $_SESSION['cart'] ?? [];
$totalProduct = 0;
$subtotal = 0;

foreach ($cart as $item) {
  $priceAfterDiscount = $item['price'] - ($item['price'] * $item['discount'] / 100);
  $subtotal += $priceAfterDiscount * $item['qty'];
  $totalProduct += $item['qty'];
}
$tax = $subtotal * TAX_RATE;
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
  <div class="flex flex-col h-screen relative">

    <!-- Header -->
    <header class="bg-white fixed z-10 h-14 w-full p-4 font-bold text-xl text-orange-500">
      Restro POS
    </header>
    <div class="flex flex-row">
      <!-- Sidebar -->
      <aside class="w-full md:w-40 fixed h-screen bg-white shadow-md flex flex-col">
        <nav class="flex-1 space-y-1 mt-14">
          <a href="index.php?view=dashboard" class="flex items-center flex-col px-4 py-2 bg-orange-100 text-orange-600">🏠 <p>Home</p></a>
          <a href="index.php?view=customers" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">👥 <p>Customer</p></a>
          <a href="index.php?view=tables" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">📑 <p>Tables</p></a>
          <a href="index.php?view=chasier" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">🪙 <p>Cashier</p></a>
          <a href="index.php?view=orders" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">📦 <p>Orders</p></a>
          <a href="index.php?view=settings" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">⚙️ <p>Settings</p></a>
        </nav>
        <a href="logout.php" class="nav-link text-center">❌ <p>Log out</p></a>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-4 mt-14 ml-40 mr-96 overflow-y-auto">
        <a href="index.php?view=product_add" class="inline-block py-2 w-32 px-4 bg-blue-500 my-4 rounded-lg text-center text-white font-bold">
          Tambah
        </a>

        <!-- Category Filter -->
        <div class="flex space-x-2 mb-4">
          <a href="index.php?view=dashboard&category=all"
            class="px-4 py-2 rounded-lg <?= $categoryParam === 'all' ? 'bg-orange-500 text-white' : 'bg-gray-200' ?>">
            Semua
          </a>
          <?php foreach ($categories as $cat): 
            $isActive = $categoryParam == $cat['categori_name'] ? 'bg-orange-500 text-white' : 'bg-gray-200';
          ?>
            <a href="index.php?view=dashboard&category=<?= $cat['categori_name'] ?>" 
               class="px-4 py-2 rounded-lg <?= $isActive ?>">
              <?= htmlspecialchars($cat['categori_name']) ?>
            </a>
          <?php endforeach; ?>
        </div>

        <!-- Products -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <?php foreach ($menus as $m): ?>
            <?php if (!empty($m['stock']) && $m['stock'] > 0): ?>
              <div class="bg-white shadow rounded-xl p-3 flex flex-col items-center">
                <img src="<?= htmlspecialchars($m['gambar']) ?>" 
                     class="w-28 h-28 object-cover rounded-lg mb-2" 
                     alt="<?= htmlspecialchars($m['name_product']) ?>">
                <div class="font-semibold text-center text-sm"><?= htmlspecialchars($m['name_product']) ?></div>
                <div class="text-gray-600">Rp. <?= number_format($m['price'], 0, ',', '.') ?></div>
                <form method="post" action="index.php?view=dashboard" class="mt-2">
                  <input type="hidden" name="action" value="add">
                  <input type="hidden" name="id" value="<?= $m['id'] ?>">
                  <input type="hidden" name="name" value="<?= htmlspecialchars($m['name_product']) ?>">
                  <input type="hidden" name="price" value="<?= $m['price'] ?>">
                  <input type="hidden" name="discount" value="<?= $m['discount'] ?? 0 ?>">
                  <button type="submit" class="px-3 py-1 bg-orange-500 text-white text-sm rounded-lg">Add</button>
                </form>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </main>

      <aside class="w-full fixed right-0 md:w-96 h-screen bg-white shadow-lg p-4 flex flex-col">
        <h2 class="font-bold mt-14 text-lg mb-4">Order List</h2>
        <div class="flex-1 space-y-3 overflow-y-auto">
          <?php foreach ($cart as $id => $item): 
            $priceAfterDiscount = $item['price'] - ($item['price'] * $item['discount'] / 100);
            $lineTotal = $priceAfterDiscount * $item['qty'];
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
                <p class="text-right text-sm text-gray-600">= Rp. <?= number_format($lineTotal, 0, ',', '.') ?></p>
              </form>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Cart Summary -->
        <div class="mt-4 border-t pt-4 space-y-1">
          <div class="flex justify-between text-sm">
            <span>Subtotal</span>
            <span>Rp. <?= number_format($subtotal, 0, ',', '.') ?></span>
          </div>
          <div class="flex justify-between text-sm">
            <span>Pajak (11%)</span>
            <span>Rp. <?= number_format($tax, 0, ',', '.') ?></span>
          </div>
          <div class="flex justify-between font-bold">
            <span>Total</span>
            <span>Rp. <?= number_format($total, 0, ',', '.') ?></span>
          </div>
          <div class="flex space-x-2 mt-3">
            <button class="flex-1 py-2 bg-gray-300 rounded-lg">Hold Order</button>
            <form action="<?= action('/order/order') ?>" method="POST" class="flex-1 w-full">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="total_product" value="<?= $totalProduct ?>">
              <input type="hidden" name="total_payment" value="<?= $total ?>">
              <button type="submit" class="w-full py-2 bg-green-500 text-white rounded-lg">Proceed</button>
            </form>
          </div>
        </div>
      </aside>
    </div>
  </div>
</body>
</html>
