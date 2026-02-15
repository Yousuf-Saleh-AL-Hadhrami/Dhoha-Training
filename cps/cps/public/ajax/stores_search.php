<?php
session_start();
include_once __DIR__ . '/../../includes/config.php';

mysqli_set_charset($connection, "utf8");

$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($connection, $search);

if (!empty($search)) {
    $query = mysqli_query($connection, "SELECT * FROM stores WHERE store_name LIKE '%$search%' ORDER BY id ASC");
} else {
    $query = mysqli_query($connection, "SELECT * FROM stores ORDER BY id ASC");
}

if (mysqli_num_rows($query) > 0) {
    while ($store = mysqli_fetch_assoc($query)) {
        echo '<tr>
                <td>' . $store['id'] . '</td>
                <td>' . htmlspecialchars($store['store_name']) . '</td>
                <td>
                    <a href="../stores.php?page=Edit&id=' . $store['id'] . '" class="btn btn-outline-primary btn-sm">تعديل</a>
                    <form action="../stores.php?page=Delete" method="post" class="d-inline" onsubmit="return confirm(\'هل أنت متأكد من حذف المخزن؟\');">
                        <input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">
                        <input type="hidden" name="id" value="' . $store['id'] . '">
                        <button type="submit" class="btn btn-outline-danger btn-sm">حذف</button>
                    </form>
                </td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="3">لا توجد مخازن تطابق البحث</td></tr>';
}
