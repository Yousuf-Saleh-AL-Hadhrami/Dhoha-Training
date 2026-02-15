<?php 

if(!isset($_SESSION['is_login'])){

     header("location: login_test.php");
     exit;
}