<?php

include __DIR__ . '/config.php';


function url($url)
{
     return BASE_URL . $url;
}

function render($view)
{
     $basepage = __DIR__ . "/../page";  // pastikan sesuai struktur folder
     $render = @include "$basepage/$view.php";
     if (!$render) {
          @include "$basepage/$view/index.php";
     }
}


function connection()
{
    try {
        $serverAddress = 'localhost';
        $databaseName  = 'akhirsmt1';
        $username      = 'root';
        $password      = '';
        $db = new PDO(
            "mysql:host={$serverAddress};dbname={$databaseName};charset=utf8",
            $username,
            $password
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $GLOBALS['db'] = $db;
        define('db' , $db);
    } catch (PDOException $e) {
        die("Gagal koneksi => " . $e->getMessage());
    }
}

connection();

function middleware()
{
     session_start();
     $view = $_GET['view'] ?? 'login';
     $isLogin = isset($_SESSION['login']) && $_SESSION['login'] === true;
     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
          $email = $_POST['email'];
          $password = $_POST['password'];
          try {
               $sql = "SELECT * FROM admins WHERE email = ?";
               $stmt = db->prepare($sql);
               $stmt->execute([$email]);
               $admin = $stmt->fetch(PDO::FETCH_ASSOC);
               // if ($admin && password_verify($password, $admin['password'])) {
               if ($admin) {
                    $_SESSION['login'] = true;
                    $_SESSION['email'] = $admin['email'];
                    $_SESSION['admin_id'] = $admin['id'];
                    header("Location: index.php?view=dashboard");
               }
          } catch (Exception $e) {
               die("Error login: " . $e->getMessage());
          }
     }
     if ($isLogin && $view === 'login') {
          header("Location: index.php?view=dashboard");
          exit;
     }
     if (!$isLogin && $view !== 'login') {
          header("Location: login.php");
          exit;
     }
     return $view;
}




function action($url)
{
     return url("/actions$url.php");
}

function redirect($path)
{
     $url = url("$path");
     header("Location: $url");
}
