<?php 

session_start();

//$url = $_SERVER['PHP_REFERER'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){

session_unset();

session_destroy();

header("location: login.php");
exit;

} else {

    echo "You did not come bt post";

    header("location: dashboard.php");
    exit;
    
}