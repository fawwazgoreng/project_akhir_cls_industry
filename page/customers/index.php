<?php
session_start();
include __DIR__ . "/../../system/action.php";
useQuery('customer.php');

$allCus = findAllCustomers();
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
      <main class="flex-1 p-4 overflow-y-auto mt-14 max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mt-2">Customers</h1>
        <a href="index.php?view=product_add" class="inline-block py-2 w-32 px-4 bg-blue-500 my-4 rounded-lg text-center text-white font-bold">
          Tambah
        </a>
        <table class="w-full border-collapse rounded-lg overflow-hidden shadow-md">
          <thead class="bg-gray-100">
            <tr>
              <th class="py-3 px-4 text-left font-semibold text-gray-700">No</th>
              <th class="py-3 px-4 text-left font-semibold text-gray-700">Id Customers</th>
              <th class="py-3 px-4 text-left font-semibold text-gray-700">Nama Customers</th>
              <th class="py-3 px-4 text-left font-semibold text-gray-700">Jenis Kelamin</th>
              <th class="py-3 px-4 text-center font-semibold text-gray-700">Detail Transaksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            <?php foreach ($allCus as $index => $custumer): ?>
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="py-3 px-4"><?= $index + 1 ?></td>
                <td class="py-3 px-4"><?= $custumer["id"] ?></td>
                <td class="py-3 px-4"><?= $custumer["username"] ?></td>
                <td class="py-3 px-4"><?= $custumer["jenis_kelamin"] ?></td>
                <td class="py-3 px-4 text-center">
                  <a href="index.php?view=customer&id=<?= $custumer['id'] ?>"
                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition">
                    Cek Transaksi
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </main>
    </div>
  </div>
</body>

</html>