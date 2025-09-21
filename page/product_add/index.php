<?php
session_start();

include __DIR__ . "/../../system/action.php";
useQuery("product.php");
useQuery("category.php");

$categories = findAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk - Restro POS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200">
  <div class="min-h-screen flex items-center justify-center p-6">
    <div class="bg-white shadow-xl rounded-2xl w-full max-w-lg p-8 border border-gray-200">
      <h1 class="text-3xl font-bold text-orange-600 mb-6 text-center">Tambah Produk</h1>
      <form action="<?= action('/products/product_save') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
        <input type="hidden" name="action" value="add">
        <div>
          <label class="block text-sm font-medium mb-1 text-gray-700">Nama Produk</label>
          <input type="text" name="name_product" required
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-gray-700">Harga</label>
          <input type="number" name="price" min="0" required
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-gray-700">Stok</label>
          <input type="number" name="stock" min="0" required
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-gray-700">Kategori</label>
          <select name="categories_id" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400">
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['categori_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="gambar" class="block text-sm font-medium mb-2 text-gray-700">Upload Gambar</label>
          <input type="file" name="gambar" id="gambar" accept="image/*" required
                 class="hidden">
          <label for="gambar" class="cursor-pointer flex items-center justify-center px-4 py-3 bg-orange-500 text-white font-medium rounded-lg shadow hover:bg-orange-600 transition">
            Pilih Gambar
          </label>
          <div class="mt-4 flex justify-center">
            <img id="previewImage" src="" alt="Preview" class="hidden min-w-40 h-40 object-cover rounded-xl border border-gray-300 shadow">
          </div>
        </div>
        <div class="flex justify-between pt-4">
          <a href="index.php?view=dashboard" class="px-5 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg shadow hover:bg-gray-400 transition">Batal</a>
          <button type="submit" class="px-5 py-2 bg-green-500 text-white font-medium rounded-lg shadow hover:bg-green-600 transition">💾 Simpan</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    const gambar = document.getElementById("gambar");
    const previewImage = document.getElementById("previewImage");
    gambar.addEventListener("change", function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.src = e.target.result;
          previewImage.classList.remove("hidden");
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>
