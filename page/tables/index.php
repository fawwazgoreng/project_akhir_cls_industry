<?php
session_start();

// Includes
include __DIR__ . "/../../system/action.php";
useQuery('tables.php');
useQuery('category.php');

$categoryParam = $_GET['category'] ?? 'all';
$categories = findAllCategories();
    $menus = findAllTables($categoryParam);

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
        <header class="bg-white fixed z-10 h-14 w-full p-4 font-bold text-xl text-orange-500">
            Restro POS
        </header>
        <div class="flex flex-row">
            <aside class="w-full md:w-40 fixed h-screen bg-white shadow-md flex flex-col">
                <nav class="flex-1 space-y-1 mt-14">
                    <a href="index.php?view=dashboard" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">🏠 <p>Home</p></a>
                    <a href="index.php?view=customers" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">👥 <p>Customer</p></a>
                    <a href="index.php?view=tables" class="flex items-center flex-col px-4 py-2 bg-orange-100 text-orange-600">📑 <p>Tables</p></a>
                    <a href="index.php?view=chasier" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">🪙 <p>Cashier</p></a>
                    <a href="index.php?view=orders" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">📦 <p>Orders</p></a>
                    <a href="index.php?view=settings" class="flex items-center flex-col px-4 py-2 hover:bg-orange-100">⚙️ <p>Settings</p></a>
                </nav>
                <a href="logout.php" class="nav-link text-center">❌ <p>Log out</p></a>
            </aside>
            <main class="flex-1 p-6 mt-14 ml-40 overflow-y-auto bg-gray-100">
                <div class="max-w-6xl mx-auto">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Penjualan berdasarkan categori <?= $categoryParam ?></h1>
                    <div class="flex space-x-2 mb-4">
                        <a href="index.php?view=tables&category=all"
                            class="px-4 py-2 rounded-lg <?= $categoryParam === 'all' ? 'bg-orange-500 text-white' : 'bg-gray-200' ?>">
                            Semua
                        </a>
                        <?php foreach ($categories as $cat):
                            $isActive = $categoryParam == $cat['categori_name'] ? 'bg-orange-500 text-white' : 'bg-gray-200';
                        ?>
                            <a href="index.php?view=tables&category=<?= $cat['categori_name'] ?>"
                                class="px-4 py-2 rounded-lg <?= $isActive ?>">
                                <?= htmlspecialchars($cat['categori_name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white rounded-2xl shadow p-6">
                            <p class="text-gray-500">Total Orders</p>
                            <p class="text-4xl font-bold text-orange-500"><?= count($menus) ?></p>
                        </div>
                        <div class="bg-white rounded-2xl shadow p-6">
                            <p class="text-gray-500">Total Products yang terjual</p>
                            <?php
                            // menghitung total quantity
                            $totalItems = 0;
                            foreach ($menus as $m) {
                                $totalItems += $m['quantity'] ?? 0;
                            }
                            ?>
                            <p class="text-4xl font-bold text-green-500"><?= number_format($totalItems) ?></p>
                        </div>
                        <div class="bg-white rounded-2xl shadow p-6">
                            <p class="text-gray-500">Total Pendapatan</p>
                            <?php
                            $totalRevenue = 0;
                            foreach ($menus as $m) {
                                $totalRevenue += $m['total_payment'];
                            }
                            ?>
                            <p class="text-4xl font-bold text-blue-500">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow p-6">
                        <p class="text-gray-700 font-semibold mb-4">Detail Penjualan Harian</p>
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="p-3">Tanggal</th>
                                    <th class="p-3">Jumlah Orders</th>
                                    <th class="p-3">Jumlah Products</th>
                                    <th class="p-3">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $daily = [];
                                foreach ($menus as $m) {
                                    $date = $m['created_at'];
                                    if (!isset($daily[$date])) {
                                        $daily[$date] = ['orders' => 0, 'items' => 0, 'pendapatan' => 0];
                                    }
                                    $daily[$date]['orders'] += 1;
                                    $daily[$date]['items'] += $m['quantity'] ?? 0;
                                    $daily[$date]['pendapatan'] += $m['total_payment'];
                                }
                                // agar dimulai dari data terbaru / terbesar
                                krsort($daily);
                                foreach ($daily as $date => $d) :
                                ?>
                                    <tr class="border-b">
                                        <td class="p-3"><?= htmlspecialchars($date) ?></td>
                                        <td class="p-3"><?= $d['orders'] ?></td>
                                        <td class="p-3"><?= $d['items'] ?></td>
                                        <td class="p-3">Rp <?= number_format($d['pendapatan'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>