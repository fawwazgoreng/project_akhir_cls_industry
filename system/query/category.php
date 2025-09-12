<?php

function findAllCategory()
{
     return db->query("select * from categories order by id desc");
}

function createCategory()
{
     $createUser = db->prepare("INSERT INTO users (nama,jenis_kelamin) values (:nama,:jenis_kelamin)");
     $createUser->execute([
          "nama" => $_POST['nama'],
          "jenis_kelamin" => $_POST['jenis_kelamin']
     ]);
}