<?php
include_once './../includes/config.php';

header('Content-Type: application/json; charset=UTF-8');

// التحقق من وجود نوع الجهاز في الطلب
if (!isset($_GET['type_id'])) {
    echo json_encode(['error' => 'type_id_missing']);
    exit;
}

$type_id = intval($_GET['type_id']);

// جلب الشركات حسب نوع الجهاز
$companies = mysqli_query($connection, "
    SELECT DISTINCT dc.id, dc.device_company_name
    FROM device_stock ds
    JOIN device_companies dc ON ds.device_company_id = dc.id
    WHERE ds.device_type_id = $type_id
");

// جلب الموديلات حسب نوع الجهاز
$models = mysqli_query($connection, "
    SELECT DISTINCT dm.id, dm.device_model
    FROM device_stock ds
    JOIN device_models dm ON ds.device_model_id = dm.id
    WHERE ds.device_type_id = $type_id
");

$response = [
    'companies' => [],
    'models' => []
];

while ($row = mysqli_fetch_assoc($companies)) {
    $response['companies'][] = $row;
}
while ($row = mysqli_fetch_assoc($models)) {
    $response['models'][] = $row;
}

echo json_encode($response);
exit;
