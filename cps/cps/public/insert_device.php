<?php
session_start();
include_once './authorize.php';
include_once './../includes/header.php';
include_once './../includes/navbar.php';
include_once './../includes/config.php';
include_once './../includes/functions.php';

$page = $_GET['page'] ?? 'Index';

echo "<div class='container'>";

if ($page === 'Index') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo "<h2 class='text-center my-3'>الأجهزة</h2>";

    // قراءة خيار البحث من GET
    $selected_store = $_GET['store_id'] ?? '';

    // إذا تم اختيار مخزن، إضافة شرط WHERE
    $where_clause = '';
    if (!empty($selected_store)) {
        $where_clause = "WHERE ds.store_id = " . (int)$selected_store;
    }

    $query = mysqli_query($connection, "
        SELECT ds.id, ds.expensed_status, ds.device_status, ds.created_at, 
               dt.type_name, dc.device_company_name, dm.device_model, s.store_name
        FROM device_stock ds
        JOIN device_types dt ON ds.device_type_id = dt.id
        JOIN device_companies dc ON ds.device_company_id = dc.id
        JOIN device_models dm ON ds.device_model_id = dm.id
        JOIN stores s ON ds.store_id = s.id
        $where_clause
        ORDER BY ds.id ASC
    ");

    if (isset($_SESSION['flash_message'])) {
        echo "<div class='alert alert-success text-center'>{$_SESSION['flash_message']}</div>";
        unset($_SESSION['flash_message']);
    }
    ?>

    <div class="mb-3 w-25">
        <form method="GET">
            <input type="hidden" name="page" value="Index">
            <label>ابحث حسب المخزن</label>
            <select name="store_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- اختر المخزن --</option>
                <?php
                $stores_result = mysqli_query($connection, "SELECT id, store_name FROM stores");
                while ($store = mysqli_fetch_assoc($stores_result)):
                    $selected = ($store['id'] == $selected_store) ? 'selected' : '';
                ?>
                    <option value="<?= $store['id'] ?>" <?= $selected ?>><?= htmlspecialchars($store['store_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>

    <?php
    echo '<a href="?page=create" class="btn btn-outline-info btn-sm mb-2">إضافة جهاز</a>';
    $totalDevices = mysqli_num_rows($query);
    echo "<div class='alert alert-info text-center'>عدد الأجهزة: {$totalDevices}</div>";

    if (mysqli_num_rows($query) === 0) {
        echo "<div class='alert alert-warning'>لا توجد أجهزة</div>";
    } else {
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
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>';
        while ($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>" . htmlspecialchars($row['expensed_status']) . "</td>
                <td>" . htmlspecialchars($row['device_status']) . "</td>
                <td>" . htmlspecialchars($row['created_at']) . "</td>
                <td>" . htmlspecialchars($row['type_name']) . "</td>
                <td>" . htmlspecialchars($row['device_company_name']) . "</td>
                <td>" . htmlspecialchars($row['device_model']) . "</td>
                <td>" . htmlspecialchars($row['store_name']) . "</td>
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

    $types = mysqli_query($connection, "SELECT id, type_name FROM device_types");
    $companies = mysqli_query($connection, "SELECT id, device_company_name FROM device_companies");
    $models = mysqli_query($connection, "SELECT id, device_model FROM device_models");
    $stores = mysqli_query($connection, "SELECT id, store_name FROM stores");

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $device_status = $_POST['device_status'] ?? '';
        $device_type_id = $_POST['device_type_id'] ?? '';
        $device_company_id = $_POST['device_company_id'] ?? '';
        $device_model_id = $_POST['device_model_id'] ?? '';
        $store_id = $_POST['store_id'] ?? '';

        if (!$device_status) $errors['device_status'] = 'حالة الجهاز مطلوبة';
        if (!$device_type_id) $errors['device_type_id'] = 'نوع الجهاز مطلوب';
        if (!$device_company_id) $errors['device_company_id'] = 'اسم الطراز مطلوب';
        if (!$device_model_id) $errors['device_model_id'] = 'اسم الموديل مطلوب';
        if (!$store_id) $errors['store_id'] = 'المخزن مطلوب';

        if (empty($errors)) {
            $stmt = mysqli_prepare($connection, "
                INSERT INTO device_stock (device_status, device_type_id, device_company_id, device_model_id, store_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            mysqli_stmt_bind_param($stmt, "siiii", $device_status, $device_type_id, $device_company_id, $device_model_id, $store_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['flash_message'] = "تمت إضافة الجهاز بنجاح";
            header("Location: insert_device.php");
            exit;
        }
    }

    echo "<h2 class='text-center my-3'>إضافة جهاز</h2>";
    ?>
    <form action="" method="POST" class="w-50 mx-auto">

        <div class="mb-3">
            <label>حالة الجهاز</label>
            <select name="device_status" class="form-select">
                <option value="">-- اختر حالة الجهاز --</option>
                <option value="جديد" <?= (old_input('device_status') == 'جديد') ? 'selected' : '' ?>>جديد</option>
                <option value="مستعمل" <?= (old_input('device_status') == 'مستعمل') ? 'selected' : '' ?>>مستعمل</option>
            </select>
            <?php if (isset($errors['device_status'])): ?>
                <div class="text-danger"><?= $errors['device_status'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>نوع الجهاز</label>
            <select name="device_type_id" class="form-control">
                <option value="">-- اختر نوع الجهاز --</option>
                <?php while ($type = mysqli_fetch_assoc($types)): ?>
                    <option value="<?= $type['id'] ?>" <?= (old_input('device_type_id') == $type['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['type_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if (isset($errors['device_type_id'])): ?>
                <div class="text-danger"><?= $errors['device_type_id'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>اسم الطراز</label>
            <select name="device_company_id" class="form-control">
                <option value="">-- اختر اسم الطراز --</option>
                <?php while ($company = mysqli_fetch_assoc($companies)): ?>
                    <option value="<?= $company['id'] ?>" <?= (old_input('device_company_id') == $company['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($company['device_company_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if (isset($errors['device_company_id'])): ?>
                <div class="text-danger"><?= $errors['device_company_id'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>اسم الموديل</label>
            <select name="device_model_id" class="form-control">
                <option value="">-- اختر اسم الموديل --</option>
                <?php while ($model = mysqli_fetch_assoc($models)): ?>
                    <option value="<?= $model['id'] ?>" <?= (old_input('device_model_id') == $model['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($model['device_model']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if (isset($errors['device_model_id'])): ?>
                <div class="text-danger"><?= $errors['device_model_id'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>المخزن</label>
            <select name="store_id" class="form-control">
                <option value="">-- اختر المخزن --</option>
                <?php while ($store = mysqli_fetch_assoc($stores)): ?>
                    <option value="<?= $store['id'] ?>" <?= (old_input('store_id') == $store['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($store['store_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <?php if (isset($errors['store_id'])): ?>
                <div class="text-danger"><?= $errors['store_id'] ?></div>
            <?php endif; ?>
        </div>

        <button class="btn btn-success px-4">حفظ</button>
        <a href="insert_device.php" class="btn btn-secondary px-4">رجوع</a>
    </form>
    <?php

} elseif ($page === 'Edit') {

    $id = (int) ($_GET['id'] ?? 0);
    $stmt = mysqli_prepare($connection, "
        SELECT * FROM device_stock WHERE id = ?
    ");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $device = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$device) {
        echo "<div class='alert alert-danger'>الجهاز غير موجود</div>";
    } else {

        $device_status = $device['device_status'];
        $device_type_id = $device['device_type_id'];
        $device_company_id = $device['device_company_id'];
        $device_model_id = $device['device_model_id'];
        $store_id = $device['store_id'];

        $types = mysqli_query($connection, "SELECT id, type_name FROM device_types");
        $companies = mysqli_query($connection, "SELECT id, device_company_name FROM device_companies");
        $models = mysqli_query($connection, "SELECT id, device_model FROM device_models");
        $stores = mysqli_query($connection, "SELECT id, store_name FROM stores");

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $device_status = $_POST['device_status'] ?? '';
            $device_type_id = $_POST['device_type_id'] ?? '';
            $device_company_id = $_POST['device_company_id'] ?? '';
            $device_model_id = $_POST['device_model_id'] ?? '';
            $store_id = $_POST['store_id'] ?? '';

            if (!$device_status) $errors['device_status'] = 'حالة الجهاز مطلوبة';
            if (!$device_type_id) $errors['device_type_id'] = 'نوع الجهاز مطلوب';
            if (!$device_company_id) $errors['device_company_id'] = 'اسم الطراز مطلوب';
            if (!$device_model_id) $errors['device_model_id'] = 'اسم الموديل مطلوب';
            if (!$store_id) $errors['store_id'] = 'المخزن مطلوب';

            if (empty($errors)) {
                $stmt = mysqli_prepare($connection, "
                    UPDATE device_stock 
                    SET device_status=?, device_type_id=?, device_company_id=?, device_model_id=?, store_id=?
                    WHERE id=?
                ");
                mysqli_stmt_bind_param($stmt, "siiiii", $device_status, $device_type_id, $device_company_id, $device_model_id, $store_id, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $_SESSION['flash_message'] = "تم تحديث الجهاز بنجاح";
                header("Location: insert_device.php");
                exit;
            }
        }

        echo "<h2 class='text-center my-3'>تعديل الجهاز</h2>";
        ?>
        <form action="" method="POST" class="w-50 mx-auto">

            <div class="mb-3">
                <label>حالة الجهاز</label>
                <select name="device_status" class="form-select">
                    <option value="">-- اختر حالة الجهاز --</option>
                    <option value="جديد" <?= ($device_status == 'جديد') ? 'selected' : '' ?>>جديد</option>
                    <option value="مستعمل" <?= ($device_status == 'مستعمل') ? 'selected' : '' ?>>مستعمل</option>
                </select>
                <?php if (isset($errors['device_status'])): ?>
                    <div class="text-danger"><?= $errors['device_status'] ?></div>
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

            <div class="mb-3">
                <label>اسم الطراز</label>
                <select name="device_company_id" class="form-control">
                    <option value="">-- اختر اسم الطراز --</option>
                    <?php while ($company = mysqli_fetch_assoc($companies)): ?>
                        <option value="<?= $company['id'] ?>" <?= ($device_company_id == $company['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($company['device_company_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <?php if (isset($errors['device_company_id'])): ?>
                    <div class="text-danger"><?= $errors['device_company_id'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label>اسم الموديل</label>
                <select name="device_model_id" class="form-control">
                    <option value="">-- اختر اسم الموديل --</option>
                    <?php while ($model = mysqli_fetch_assoc($models)): ?>
                        <option value="<?= $model['id'] ?>" <?= ($device_model_id == $model['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($model['device_model']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <?php if (isset($errors['device_model_id'])): ?>
                    <div class="text-danger"><?= $errors['device_model_id'] ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label>المخزن</label>
                <select name="store_id" class="form-control">
                    <option value="">-- اختر المخزن --</option>
                    <?php while ($store = mysqli_fetch_assoc($stores)): ?>
                        <option value="<?= $store['id'] ?>" <?= ($store_id == $store['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($store['store_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <?php if (isset($errors['store_id'])): ?>
                    <div class="text-danger"><?= $errors['store_id'] ?></div>
                <?php endif; ?>
            </div>

            <button class="btn btn-success px-4">تحديث</button>
            <a href="insert_device.php" class="btn btn-secondary px-4">رجوع</a>
        </form>
        <?php
    }

} elseif ($page === 'Delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token mismatch.");
    }

    $id = (int) $_POST['id'];
    mysqli_query($connection, "DELETE FROM device_stock WHERE id = $id");
    $_SESSION['flash_message'] = "تم حذف الجهاز بنجاح";
    header("Location: insert_device.php");
    exit;
}

echo "</div>"; // container
include_once './../includes/footer.php';
?>
