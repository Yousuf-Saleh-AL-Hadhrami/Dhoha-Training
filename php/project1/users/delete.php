<?php
session_start();
include "./../includes/database.php";


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['flash_message'] =
        '<div class="alert alert-danger">Invalid user ID.</div>';
    header("location:index.php");
    exit;
}

$id = (int) $_GET['id'];

$sql = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_message'] =
        '<div class="alert alert-success">User deleted successfully.</div>';
} else {
    $_SESSION['flash_message'] =
        '<div class="alert alert-danger">Failed to delete user.</div>';
}

mysqli_stmt_close($stmt);
header("location:index.php");
exit;
