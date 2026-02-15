<?php
include_once './../includes/config.php';

$type_id = (int) $_GET['type_id'];

$query = mysqli_query($connection, "
    SELECT DISTINCT dc.id, dc.device_company_name 
    FROM device_stock ds
    JOIN device_companies dc ON ds.device_company_id = dc.id
    WHERE ds.device_type_id = $type_id
");

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

echo json_encode($data);
