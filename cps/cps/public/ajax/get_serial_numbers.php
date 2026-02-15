<?php
include_once '../includes/config.php';

$model_id = $_GET['model_id'] ?? 0;

$result = mysqli_query($connection, "
    SELECT id, device_status 
    FROM device_stock
    WHERE device_model_id = $model_id 
        AND (expensed_status IS NULL OR expensed_status != 'مصروف')
");

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
