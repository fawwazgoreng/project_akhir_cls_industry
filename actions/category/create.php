<?php

if($_SERVER['REQUEST_METHOD']=='POST'){
     include '../../system/action.php';
     useQuery('category.php');

     createCategory();
     redirect('/master/category');
}

