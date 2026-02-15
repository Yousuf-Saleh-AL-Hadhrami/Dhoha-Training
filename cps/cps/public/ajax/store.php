<?php
session_start();
include_once '../includes/config.php';

$store_id = $_GET['store_id'] ?? '';

if ($store_id === '') {
    echo "<div class='alert alert-warning'>الرجاء اختيار مخزن</div>";
    exit;
}

$stmt = mysqli_prepare($connection, "
    SELECT ds.id, ds.expensed_status, ds.device_status, ds.created_at, 
           dt.type_name, dc.device_company_name, dm.device_model, s.store_name
    FROM device_stock ds
    JOIN device_types dt ON ds.device_type_id = dt.id
    JOIN device_companies dc ON ds.device_company_id = dc.id
    JOIN device_models dm ON ds.device_model_id = dm.id
    JOIN stores s ON ds.store_id = s.id
    WHERE s.id = ?
    ORDER BY ds.id ASC
");
mysqli_stmt_bind_param($stmt, "i", $store_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$totalDevices = mysqli_num_rows($result);
echo "<div class='alert alert-info text-center'>عدد الأجهزة في هذا المخزن: {$totalDevices}</div>";

if ($totalDevices === 0) {
    echo "<div class='alert alert-warning'>لا توجد أجهزة في هذا المخزن</div>";
    exit;
}

echo '
<div class="table-responsive">
<table class="table table-bordered text-center">
    <thead class="table-secondary">
        <tr>
            <th>الرقم</th>
            <th>حالة الصرف</th>
            <th>الحالة</th>
            <th>تاريخ الإضافة</th>
            <th>نوع الجهاز</th>
            <th>اسم الطراز</th>
            <th>اسم الموديل</th>
            <th>المخزن</th>
        </tr>
    </thead>
    <tbody>';
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>" . htmlspecialchars($row['expensed_status']) . "</td>
        <td>" . htmlspecialchars($row['device_status']) . "</td>
        <td>" . htmlspecialchars($row['created_at']) . "</td>
        <td>" . htmlspecialchars($row['type_name']) . "</td>
        <td>" . htmlspecialchars($row['device_company_name']) . "</td>
        <td>" . htmlspecialchars($row['device_model']) . "</td>
        <td>" . htmlspecialchars($row['store_name']) . "</td>
    </tr>";
}
echo '</tbody></table></div>';
