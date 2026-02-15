<?php
declare(strict_types=1);
session_start();

include_once './authorize.php';
include_once './../includes/header.php';
include_once './../includes/navbar.php';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function fetchData(mysqli $connection, string $query): array {
    $result = mysqli_query($connection, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// بيانات الجداول
$tables = [
    'users' => [
        'title' => 'المستخدمين',
        'icon' => 'bi-people-fill',
        'columns' => ["رقم المستخدم", "الاسم"],
        'rows' => fetchData($connection, "SELECT id, name FROM users"),
        'color' => 'palegreen'
    ],
    'device_stock' => [
        'title' => 'الأجهزة',
        'icon' => 'bi-box-seam',
        'columns' => ["الرقم", "الحالة", "تاريخ الإضافة", "نوع الجهاز", "اسم الطراز", "اسم الموديل"],
        'rows' => fetchData($connection, "
            SELECT ds.id, ds.device_status, ds.created_at, dt.type_name, dc.device_company_name, dm.device_model
            FROM device_stock ds
            JOIN device_types dt ON ds.device_type_id = dt.id
            JOIN device_companies dc ON ds.device_company_id = dc.id
            JOIN device_models dm ON ds.device_model_id = dm.id
            ORDER BY dc.id ASC
        "),
        'color' => 'honeydew'
    ],
    'expenses' => [
        'title' => 'صرف الأجهزة',
        'icon' => 'bi-arrow-left-right',
        'columns' => ["الرقم", "الرقم التسلسلي", "اسم المستخدم", "الحالة", "تاريخ الإضافة", "تاريخ الصرف", "نوع الجهاز", "اسم الطراز", "اسم الموديل"],
        'rows' => fetchData($connection, "
            SELECT 
                di.id, 
                di.serial_number, 
                u.staff_name, 
                ds.device_status, 
                di.created_at,
                di.expensed_at, 
                dt.type_name, 
                dc.device_company_name, 
                dm.device_model
            FROM expenses di
            JOIN users u ON di.user_id = u.id
            JOIN device_stock ds ON di.device_stock_id = ds.id
            JOIN device_types dt ON di.device_type_id = dt.id
            JOIN device_companies dc ON di.device_company_id = dc.id
            JOIN device_models dm ON di.device_model_id = dm.id
            ORDER BY di.id ASC
        "),
        'color' => 'lightgoldenrodyellow'
    ],
    'device_types' => [
        'title' => 'أنواع الأجهزة',
        'icon' => 'bi-hdd-network',
        'columns' => ["رقم النوع", "اسم نوع الجهاز"],
        'rows' => fetchData($connection, "SELECT id, type_name FROM device_types ORDER BY id ASC"),
        'color' => 'lightblue'
    ],
    'device_companies' => [
        'title' => 'طرازات الأجهزة',
        'icon' => 'bi-cpu-fill',
        'columns' => ["الرقم", "اسم الطراز", "نوع الجهاز"],
        'rows' => fetchData($connection, "
            SELECT dc.id, dc.device_company_name, dt.type_name
            FROM device_companies dc
            JOIN device_types dt ON dc.device_type_id = dt.id
            ORDER BY dc.id ASC
        "),
        'color' => 'lavender'
    ],
    'device_models' => [
        'title' => 'موديلات الأجهزة',
        'icon' => 'bi-laptop-fill',
        'columns' => ["الرقم", "نوع الجهاز", "اسم الطراز", "اسم الموديل"],
        'rows' => fetchData($connection, "
            SELECT dm.id, dm.device_model, dt.type_name, dc.device_company_name
            FROM device_models dm
            JOIN device_types dt ON dm.device_type_id = dt.id
            JOIN device_companies dc ON dm.device_company_id = dc.id
            ORDER BY dc.id ASC
        "),
        'color' => 'mistyrose'
    ]
];

// بيانات الشارت فقط للكاردات التي لها شارت
$chartsData = [
    'users' => fetchData($connection, "
        SELECT u.staff_name, COUNT(e.id) AS devices_used
        FROM users u
        LEFT JOIN expenses e ON e.user_id = u.id
        GROUP BY u.id
    "),
    'device_stock' => fetchData($connection, "
        SELECT ds.device_status, COUNT(ds.id) AS total
        FROM device_stock ds
        GROUP BY ds.device_status
    "),
    'expenses' => fetchData($connection, "
        SELECT dt.type_name, COUNT(e.id) AS total
        FROM expenses e
        JOIN device_types dt ON e.device_type_id = dt.id
        GROUP BY dt.id
    ")
];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard Tables + Charts</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.bg-palegreen { background-color: #dff7df !important; }
.bg-lightblue { background-color: #d9f0ff !important; }
.bg-lavender { background-color: #e6e6fa !important; }
.bg-mistyrose { background-color: #ffe4e1 !important; }
.bg-honeydew { background-color: #f0fff0 !important; }
.bg-lightgoldenrodyellow { background-color: #fafad2 !important; }

.cursor-pointer { cursor: pointer; }
.icon-small { font-size: 2rem; color: #004a80; transition: 0.3s; }
.icon-small:hover { transform: scale(1.1); color: #007bff; }
.small-card { width: 100%; height: 160px; border-radius: 12px; padding: 15px; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center; }
.small-card:hover { transform: translateY(-5px); box-shadow: 0 6px 14px rgba(0,0,0,0.1); }
h5.card-title { font-size: 1rem; margin-top: 10px; font-weight: bold; }

@media (min-width: 768px) { .card-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; justify-items: stretch; } }
@media (max-width: 767px) { .card-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; } }

.chart-card { width: 100%; max-width: 700px; margin: 20px auto; }
</style>
</head>
<body>

<div class="container my-4">
  <div class="mb-4 text-center">
    <input type="text" class="form-control w-50 mx-auto" placeholder="ابحث هنا..." id="global-search">
  </div>

  <div class="card-grid mb-4">
    <?php foreach($tables as $key => $table): ?>
      <div class="card small-card text-dark bg-<?= $table['color'] ?> cursor-pointer" onclick="showTable('<?= $key ?>')">
        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
          <i class="bi <?= $table['icon'] ?> icon-small"></i>
          <h5 class="card-title mt-2"><?= $table['title'] ?></h5>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div id="table-container"></div>
</div>

<script>
const tables = <?= json_encode($tables, JSON_UNESCAPED_UNICODE) ?>;
const chartsData = <?= json_encode($chartsData, JSON_UNESCAPED_UNICODE) ?>;

function showTable(key){
    const container = document.getElementById('table-container');
    container.innerHTML = ''; // إزالة أي محتوى سابق

    const table = tables[key];
    if(!table) return;

    // إنشاء الجدول
    let html = `<div class="card mb-4">
        <div class="card-header bg-${table.color} text-dark fw-bold"><i class="bi ${table.icon}"></i> ${table.title}</div>
        <div class="card-body table-responsive">
        <table class="table table-bordered table-hover text-center">
        <thead class="table-light"><tr>`;
    table.columns.forEach(col => html += `<th>${col}</th>`);
    html += `</tr></thead><tbody>`;
    table.rows.forEach(row=>{
        html += '<tr>';
        Object.values(row).forEach(cell=> html += `<td>${cell}</td>`);
        html += '</tr>';
    });
    html += `</tbody></table></div></div>`;
    container.innerHTML = html;

    // إضافة الشارت فقط إذا موجود
    if(chartsData[key]){
        container.innerHTML += `<div class="chart-card bg-white p-3 rounded"><canvas id="chart-${key}" height="200"></canvas></div>`;
        const ctx = document.getElementById(`chart-${key}`).getContext('2d');

        let chartConfig;
        if(key==='users'){
            chartConfig = {
                type: 'bar',
                data: {
                    labels: chartsData[key].map(d=>d.staff_name),
                    datasets:[{
                        label:'عدد الأجهزة المصروفة',
                        data: chartsData[key].map(d=>d.devices_used),
                        backgroundColor:'#007bff'
                    }]
                },
                options:{ responsive:true, plugins:{legend:{display:false}}}
            };
        } else if(key==='device_stock'){
            chartConfig = {
                type: 'pie',
                data: {
                    labels: chartsData[key].map(d=>d.device_status),
                    datasets:[{
                        label:'الحالة',
                        data: chartsData[key].map(d=>d.total),
                        backgroundColor:['#28a745','#ffc107','#dc3545']
                    }]
                },
                options:{ responsive:true }
            };
        } else if(key==='expenses'){
            chartConfig = {
                type:'doughnut',
                data:{
                    labels: chartsData[key].map(d=>d.type_name),
                    datasets:[{
                        data: chartsData[key].map(d=>d.total),
                        backgroundColor:['#007bff','#28a745','#ffc107','#dc3545','#6f42c1','#20c997']
                    }]
                },
                options:{ responsive:true }
            };
        }
        new Chart(ctx, chartConfig);
    }
}

// البحث في الجدول الظاهر
document.getElementById('global-search').addEventListener('keyup', function(){
    const filter = this.value.toLowerCase();
    const visibleTable = document.querySelector('.table-container table');
    if(!visibleTable) return;
    visibleTable.querySelectorAll('tbody tr').forEach(row=>{
        row.style.display = Array.from(row.cells).some(td=>td.innerText.toLowerCase().includes(filter))?'':'none';
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
