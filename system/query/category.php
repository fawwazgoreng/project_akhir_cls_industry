<?php
function findAllCategories()
{
     global $db;
     $sql = "SELECT * FROM categories order by id";
     $stmt = $db->query($sql);
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findProductsByCategoryName($category)
{
     global $db;
     $sql = "SELECT products.* , categories.categori_name
            FROM products
            INNER JOIN categories ON categories.id = products.categories_id";
     if ($category !== 'all') {
          $sql .= " WHERE LOWER(categories.categori_name) = LOWER(:category)";
          $stmt = $db->prepare($sql);
          $stmt->execute([':category' => $category]);
     } else {
          $stmt = $db->query($sql);
     }
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function createCategory()
{
     $createUser = db->prepare("INSERT INTO users (nama,jenis_kelamin) values (:nama,:jenis_kelamin)");
     $createUser->execute([
          "nama" => $_POST['nama'],
          "jenis_kelamin" => $_POST['jenis_kelamin']
     ]);
}
