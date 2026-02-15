<?php
session_start();
include_once './authorize.php';
include_once './../includes/header.php';
include_once './../includes/navbar.php';
include_once './../includes/config.php';

$page = $_GET['page'] ?? 'Index';
echo "<div class='container'>";

if ($page === 'Index') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo "<h2 class='text-center my-3'>طرازات الأجهزة</h2>";

    $query = mysqli_query($connection, "
        SELECT dc.id, dc.device_company_name, dt.type_name
        FROM device_companies dc
        JOIN device_types dt ON dc.device_type_id = dt.id
        ORDER BY dc.id 
    ");

    if (isset($_SESSION['flash_message'])) {
        echo "<div class='alert alert-success text-center'>{$_SESSION['flash_message']}</div>";
        unset($_SESSION['flash_message']);
    }

    echo '<a href="?page=create" class="btn btn-outline-info btn-sm mb-2">إضافة طراز جهاز</a>';

    if (mysqli_num_rows($query) === 0) {
        echo "<div class='alert alert-warning'>لا توجد طرازات أجهزة</div>";
    } else {
        echo '
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>الرقم</th>
                        <th>اسم الطراز</th>
                        <th>نوع الجهاز</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>';
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>" . htmlspecialchars($row['device_company_name']) . "</td>
                <td>" . htmlspecialchars($row['type_name']) . "</td>
                <td>
                    <a href='?page=Edit&id={$row['id']}' class='btn btn-outline-primary btn-sm'>تعديل</a>
                    <form action='?page=Delete' method='POST' class='d-inline' onsubmit=\"return confirm('هل أنت متأكد من الحذف؟');\">
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>
                        <button class='btn btn-outline-danger btn-sm'>حذف</button>
                    </form>
                </td>
            </tr>";
        }
        echo '</tbody></table></div>';
    }

} elseif ($page === 'create') {
    $device_company_name = $_POST['device_company_name'] ?? '';
    $device_type_id = $_POST['device_type_id'] ?? '';
    $errors = [];

    $types = mysqli_query($connection, "SELECT id, type_name FROM device_types");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$device_company_name) $errors['device_company_name'] = 'اسم الطراز مطلوب';
        if (!$device_type_id) $errors['device_type_id'] = 'نوع الجهاز مطلوب';

        if (empty($errors)) {
            $stmt = mysqli_prepare($connection, "INSERT INTO device_companies (device_company_name, device_type_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "si", $device_company_name, $device_type_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['flash_message'] = "تمت إضافة الطراز بنجاح";
            header("Location: device_companies.php");
            exit;
        }
    }

    echo "<h2 class='text-center my-3'>إضافة طراز جهاز</h2>";
    ?>
    <form method="POST" class="w-50 mx-auto">
        <div class="mb-3">
            <label>اسم الطراز</label>
            <input type="text" name="device_company_name" class="form-control" value="<?= htmlspecialchars($device_company_name) ?>">
            <?php if (isset($errors['device_company_name'])): ?>
                <div class="text-danger"><?= $errors['device_company_name'] ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label>نوع الجهاز</label>
            <select name="device_type_id" class="form-control">
                <option value="">-- اختر نوع الجهاز --</option>
                <?php while ($type = mysqli_fetch_assoc($types)): ?>
                    <option value="<?= $type['id'] ?>" <?= ($device_type_id == $type['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['type_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if (isset($errors['device_type_id'])): ?>
                <div class="text-danger"><?= $errors['device_type_id'] ?></div>
            <?php endif; ?>
        </div>
        
        <button class="btn btn-success">حفظ</button>
        <a href="device_companies.php" class="btn btn-secondary">رجوع</a>
    </form>
    <?php

} elseif ($page === 'Edit' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $res = mysqli_query($connection, "SELECT * FROM device_companies WHERE id = $id");
    $device = mysqli_fetch_assoc($res);
    if (!$device) {
        echo "<div class='alert alert-danger'>الطراز غير موجود</div>";
    } else {
        $device_company_name = $_POST['device_company_name'] ?? $device['device_company_name'];
        $device_type_id = $_POST['device_type_id'] ?? $device['device_type_id'];
        $errors = [];

        $types = mysqli_query($connection, "SELECT id, type_name FROM device_types");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$device_company_name) $errors['device_company_name'] = 'اسم الطراز مطلوب';
            if (!$device_type_id) $errors['device_type_id'] = 'نوع الجهاز مطلوب';

            if (empty($errors)) {
                $stmt = mysqli_prepare($connection, "UPDATE device_companies SET device_company_name = ?, device_type_id = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "sii", $device_company_name, $device_type_id, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['flash_message'] = "تم تعديل الطراز بنجاح";
                header("Location: device_companies.php");
                exit;
            }
        }

        echo "<h2 class='text-center my-3'>تعديل الطراز</h2>";
        ?>
        <form method="POST" class="w-50 mx-auto">
            <div class="mb-3">
                <label>اسم الطراز</label>
                <input type="text" name="device_company_name" class="form-control" value="<?= htmlspecialchars($device_company_name) ?>">
                <?php if (isset($errors['device_company_name'])): ?>
                    <div class="text-danger"><?= $errors['device_company_name'] ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label>نوع الجهاز</label>
                <select name="device_type_id" class="form-control">
                    <option value="">-- اختر نوع الجهاز --</option>
                    <?php while ($type = mysqli_fetch_assoc($types)): ?>
                        <option value="<?= $type['id'] ?>" <?= ($device_type_id == $type['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['type_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <?php if (isset($errors['device_type_id'])): ?>
                    <div class="text-danger"><?= $errors['device_type_id'] ?></div>
                <?php endif; ?>
            </div>
            <button class="btn btn-primary">تحديث</button>
            <a href="device_companies.php" class="btn btn-secondary">رجوع</a>
        </form>
        <?php
    }

} elseif ($page === 'Delete' && isset($_POST['id'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("طلب غير صالح.");
    }

    $id = (int) $_POST['id'];
    mysqli_query($connection, "DELETE FROM device_companies WHERE id = $id");
    $_SESSION['flash_message'] = "تم حذف الطراز بنجاح";
    header("Location: device_companies.php");
    exit;
}

echo "</div>";
include_once './../includes/footer.php';
?>
