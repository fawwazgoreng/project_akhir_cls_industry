<?php
session_start();
include __DIR__ . "/../../system/action.php";
useQuery('customer.php');
useQuery('order.php');

$id = $_GET['id'] ?? null;
if (!$id) {
  die("Order ID tidak ditemukan.");
}

$order = findOrderById($id); 
$products = findProductsByOrderId($id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>POS - Detail Order</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 capitalize">
  <div class="flex relative flex-col flex-nowrap h-screen">
    <div class="bg-white fixed z-20 p-4 font-bold h-14 text-xl w-full text-orange-500">Restro POS</div>
    <div class="flex flex-row">
      <aside class="w-full fixed z-10 h-screen md:w-40 bg-white shadow-md flex flex-col">
        <nav class="flex-1 space-y-1">
          <a href="index.php?view=dashboard" class="flex mt-14 items-center flex-col px-4 py-2 hover:bg-orange-100">🏠 <p>Home</p></a>
          <a href="index.php?view=customers" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">👥 <p>Customer</p></a>
          <a href="index.php?view=tables" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">📑 <p>Tables</p></a>
          <a href="index.php?view=chasier" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">🪙 <p>Cashier</p></a>
          <a href="index.php?view=orders" class="flex items-center flex-col px-4 py-2 bg-orange-100 text-orange-600">📦 <p>Orders</p></a>
          <a href="index.php?view=settings" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">⚙️ <p>Settings</p></a>
        </nav>
        <a href="logout.php" class="flex items-center flex-col px-4 py-2 text-center rounded-lg">❌ <p>Log out</p></a>
      </aside>
      <main class="flex-1 p-4 ml-40 overflow-y-auto mt-14">
        <h1 class="text-2xl font-bold mt-2">Detail Order #<?= htmlspecialchars($order['id']) ?></h1>
        <p class="text-gray-600 mt-1">Tanggal: <?= htmlspecialchars($order['created_at']) ?></p>
        <p class="text-gray-600">Total Pembayaran: Rp <?= number_format($order['total_payment'], 0, ',', '.') ?></p>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
          <?php foreach ($products as $index => $p): ?>
            <div class="bg-white shadow-md rounded-xl p-4 hover:shadow-lg transition flex flex-col items-center">
              <img src="<?= htmlspecialchars($p['gambar']) ?>" 
                   class="w-28 h-28 object-cover rounded-lg mb-3" 
                   alt="<?= htmlspecialchars($p['name_product']) ?>">
              <h2 class="text-lg font-semibold text-gray-800 text-center"><?= htmlspecialchars($p["name_product"]) ?></h2>
              <p class="text-sm text-gray-500">Harga: Rp <?= number_format($p["price"], 0, ',', '.') ?></p>
              <p class="text-sm text-gray-500">quantity: <?= $p["quantity"] ?></p>
              <p class="text-sm text-gray-700 font-bold mt-2">Subtotal: Rp <?= number_format($p["subtotal"], 0, ',', '.') ?></p>
            </div>
          <?php endforeach; ?>
        </div>

        <a href="index.php?view=customers&id=<?= $order['customer_id'] ?>" 
           class="inline-block mt-6 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg shadow-sm transition">
          ← Kembali ke Customer
        </a>
      </main>
    </div>
  </div>
</body>
</html>
