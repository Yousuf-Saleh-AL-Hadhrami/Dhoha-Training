<?php
include_once './../includes/config.php';

$company_id = (int) $_GET['company_id'];

$query = mysqli_query($connection, "
    SELECT DISTINCT dm.id, dm.device_model 
    FROM device_stock ds
    JOIN device_models dm ON ds.device_model_id = dm.id
    WHERE ds.device_company_id = $company_id
");

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

echo json_encode($data);
