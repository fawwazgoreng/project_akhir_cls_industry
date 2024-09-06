<?php
require "system/query/category.php";
$users = findAllCategory();
?>
<h1>List User</h1>
<a href="<?= url('/master/category/create') ?>">
     Tambah Data
</a>
<table>
     <thead>
          <tr>
               <th>Nama</th>
               <th>Jenis Kelamin</th>
               <th>Aksi</th>
          </tr>
     </thead>
     <tbody>
          <?php foreach ($users as $user): ?>
               <tr>
                    <td>
                         <?= $user['nama'] ?>
                    </td>
                    <td>
                         <?= $user['jenis_kelamin'] ?>
                    </td>
                    <td>
                         <a href="update.php?id=<?= $user['id'] ?>">
                              Edit
                         </a>
                         
                         <a href="delete.php?id=<?= $user['id'] ?>">
                              Delete
                         </a>
                    </td>
               </tr>
          <?php endforeach ?>
     </tbody>
</table>