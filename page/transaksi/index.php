<?php
session_start();

include __DIR__ . "/../../system/action.php";
useQuery("order.php");
useQuery("product.php");

// include query khusus order
include __DIR__ . "/../../queries/order_query.php";

// Ambil order_id dari URL
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    $orderId = findLastOrderId();
    if (!$orderId) {
        exit("No transaction found.");
    }
}

// Ambil data order & item
$order = findOrderById($orderId);
if (!$order) {
    exit("Order not found!");
}

$items = findOrderItems($orderId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transaksi - Restro POS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <div class="min-h-screen flex flex-col items-center p-6">
    <div class="bg-white shadow-lg rounded-xl w-full max-w-2xl p-6">
      <h1 class="text-2xl font-bold text-orange-500 mb-4">Transaksi Berhasil</h1>

      <div class="mb-4">
        <p><span class="font-semibold">Order ID:</span> <?= htmlspecialchars($order['id']) ?></p>
        <p><span class="font-semibold">Admin:</span> <?= htmlspecialchars($order['admin_name'] ?? '-') ?></p>
        <p><span class="font-semibold">Customer:</span> <?= htmlspecialchars($order['customer_name'] ?? '-') ?></p>
        <p><span class="font-semibold">Tanggal:</span> <?= htmlspecialchars($order['created_at']) ?></p>
      </div>

      <h2 class="text-lg font-semibold mb-2">Detail Produk</h2>
      <table class="w-full border border-gray-300 text-sm mb-4">
        <thead class="bg-gray-200">
          <tr>
            <th class="p-2 border">Produk</th>
            <th class="p-2 border">Qty</th>
            <th class="p-2 border">Harga</th>
            <th class="p-2 border">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td class="p-2 border"><?= htmlspecialchars($item['name_product']) ?></td>
              <td class="p-2 border text-center"><?= (int)$item['quantity'] ?></td>
              <td class="p-2 border">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
              <td class="p-2 border">Rp <?= number_format($item['total_price'], 0, ',', '.') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="text-right space-y-1">
        <p>Subtotal: <span class="font-semibold">Rp <?= number_format($order['total_payment'] / 1.11, 0, ',', '.') ?></span></p>
        <p>Pajak (11%): <span class="font-semibold">Rp <?= number_format($order['total_payment'] - ($order['total_payment'] / 1.11), 0, ',', '.') ?></span></p>
        <p class="text-lg font-bold">Total: Rp <?= number_format($order['total_payment'], 0, ',', '.') ?></p>
      </div>

      <div class="mt-6 flex justify-end space-x-2">
        <a href="index.php?view=dashboard" class="px-4 py-2 bg-gray-300 rounded-lg">Kembali</a>
        <a href="print.php?order_id=<?= $orderId ?>" class="px-4 py-2 bg-green-500 text-white rounded-lg">🖨 Cetak</a>
      </div>
    </div>
  </div>
</body>
</html>
