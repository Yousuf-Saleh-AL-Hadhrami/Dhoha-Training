<?php
session_start();
include_once __DIR__ . '/../../includes/config.php';

mysqli_set_charset($connection, "utf8");

$serial = $_GET['serial_number'] ?? '';
$serial = mysqli_real_escape_string($connection, $serial);

 
if (!empty($serial)) {
    $query = mysqli_query($connection, "SELECT * FROM expenses WHERE serial_number LIKE '%$serial%' ORDER BY id ASC");
} else {
    $query = mysqli_query($connection, "SELECT * FROM expenses ORDER BY id ASC");
}


if (mysqli_num_rows($query) > 0) {
    while ($expense = mysqli_fetch_assoc($query)) {
        echo '<tr>
                <td>' . $expense['id'] . '</td>
                <td>' . htmlspecialchars($expense['serial_number']) . '</td>
                <td>' . htmlspecialchars($expense['device_name']) . '</td>
                <td>' . htmlspecialchars($expense['device_type']) . '</td>
                <td>' . htmlspecialchars($expense['amount']) . '</td>
                <td>
                    <a href="../expenses.php?page=Edit&id=' . $expense['id'] . '" class="btn btn-outline-primary btn-sm">تعديل</a>
                    <form action="../expenses.php?page=Delete" method="post" class="d-inline" onsubmit="return confirm(\'هل أنت متأكد من حذف المصروف؟\');">
                        <input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">
                        <input type="hidden" name="id" value="' . $expense['id'] . '">
                        <button type="submit" class="btn btn-outline-danger btn-sm">حذف</button>
                    </form>
                </td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="6">لا توجد مصاريف تطابق البحث</td></tr>';
}
