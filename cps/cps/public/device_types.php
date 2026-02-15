<?php
session_start();

$title = 'أنواع الأجهزة';

include_once './authorize.php';
include_once './../includes/header.php';
include_once './../includes/navbar.php';
include_once './../includes/config.php';

$page = $_GET['page'] ?? 'Index';

echo "<div class='container'>";

if ($page === 'Index') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    echo "<h2 class='text-center my-3'>أنواع الأجهزة</h2>";

    $query = mysqli_query($connection, "SELECT id, type_name FROM device_types ORDER BY id ASC");
    $count = mysqli_num_rows($query);

    if (isset($_SESSION['flash_message'])) {
        echo "<div class='alert alert-success text-center'>{$_SESSION['flash_message']}</div>";
        unset($_SESSION['flash_message']);
    }

    if ($count == 0) {
        echo '<a href="device_types.php?page=create" class="btn btn-outline-info btn-sm mb-2">إضافة نوع جهاز</a>';
        echo "<div class='alert alert-danger my-2'>لا توجد أنواع أجهزة</div>";
    } else { ?>
        <a href="device_types.php?page=create" class='btn btn-outline-info btn-sm mb-2'>إضافة نوع جهاز</a>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>رقم النوع</th>
                        <th>إسم نوع الجهاز</th>
                        <th>العملية</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($device_type = mysqli_fetch_object($query)): ?>
                        <tr>
                            <td><?= $device_type->id ?></td>
                            <td><?= htmlspecialchars($device_type->type_name) ?></td>
                            <td>
                                <a href="device_types.php?page=Edit&id=<?= $device_type->id ?>" class="btn btn-outline-primary btn-sm">تعديل</a>
                                <form action="?page=Delete" method="post" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف نوع الجهاز؟');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($device_type->id) ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php }
} elseif ($page === 'create') {
    $type_name = $_POST['type_name'] ?? '';
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($type_name)) $errors['type_name'] = "اسم نوع الجهاز مطلوب";

        if (empty($errors)) {
            $stmt = mysqli_prepare($connection, "INSERT INTO device_types (type_name) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $type_name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['flash_message'] = "تمت إضافة نوع الجهاز بنجاح";
            header("Location: device_types.php");
            exit;
        }
    }

    echo "<h2 class='text-center my-3'>إضافة نوع جهاز</h2>"; ?>
    <form method="POST" class="w-50 mx-auto">
        <div class="mb-3">
            <label>اسم نوع الجهاز</label>
            <input type="text" name="type_name" class="form-control" value="<?= htmlspecialchars($type_name) ?>">
            <?php if (isset($errors['type_name'])): ?>
                <div class="text-danger"><?= $errors['type_name'] ?></div>
            <?php endif; ?>
        </div>
        <button class="btn btn-success">حفظ</button>
        <a href="device_types.php" class="btn btn-secondary">رجوع</a>
    </form>
<?php
} elseif ($page === 'Edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $type = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM device_types WHERE id = $id"));

    if (!$type) {
        echo "<div class='alert alert-danger text-center'>نوع الجهاز غير موجود</div>";
    } else {
        $type_name = $_POST['type_name'] ?? $type['type_name'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($type_name)) $errors['type_name'] = "اسم النوع مطلوب";

            if (empty($errors)) {
                $stmt = mysqli_prepare($connection, "UPDATE device_types SET type_name = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "si", $type_name, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $_SESSION['flash_message'] = "تم تعديل نوع الجهاز بنجاح";
                header("Location: device_types.php");
                exit;
            }
        }

        echo "<h2 class='text-center my-3'>تعديل نوع جهاز</h2>"; ?>
        <form method="POST" class="w-50 mx-auto">
            <div class="mb-3">
                <label>اسم النوع</label>
                <input type="text" name="type_name" class="form-control" value="<?= htmlspecialchars($type_name) ?>">
                <?php if (isset($errors['type_name'])): ?>
                    <div class="text-danger"><?= $errors['type_name'] ?></div>
                <?php endif; ?>
            </div>
            <button class="btn btn-primary">تحديث</button>
            <a href="device_types.php" class="btn btn-secondary">رجوع</a>
        </form>
<?php
    }
} elseif ($page === 'Delete' && isset($_POST['id'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<div class='alert alert-danger text-center'>طلب غير صالح (CSRF)</div>");
    }

    $id = intval($_POST['id']);
    $type = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM device_types WHERE id = $id"));

    if (!$type) {
        echo "<div class='alert alert-danger text-center'>نوع الجهاز غير موجود</div>";
    } else {
        mysqli_query($connection, "DELETE FROM device_types WHERE id = $id");
        $_SESSION['flash_message'] = "تم حذف نوع الجهاز بنجاح";
        header("Location: device_types.php");
        exit;
    }
}

echo "</div>";

include_once './../includes/footer.php';
?>
