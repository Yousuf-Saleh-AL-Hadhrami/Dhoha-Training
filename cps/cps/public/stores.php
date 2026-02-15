<?php
session_start();

$title = 'المخازن';

include_once './authorize.php';
include_once './../includes/header.php';
include_once './../includes/navbar.php';
include_once './../includes/config.php';

$page = $_GET['page'] ?? 'Index';

echo "<div class='container'>";

if ($page === 'Index') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    echo "<h2 class='text-center my-3'>المخازن</h2>";

    if (isset($_SESSION['flash_message'])) {
        echo "<div class='alert alert-success text-center'>{$_SESSION['flash_message']}</div>";
        unset($_SESSION['flash_message']);
    }
    ?>


    <div class="mb-3 w-50 mx-auto">
        <input type="text" id="search" class="form-control" placeholder="ابحث عن المخزن">
    </div>

    <!-- زر إضافة مخزن -->
    <a href="stores.php?page=create" class='btn btn-outline-info btn-sm mb-2'>إضافة مخزن</a>

    <!-- جدول المخازن -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-secondary">
                <tr>
                    <th>رقم المخزن</th>
                    <th>إسم المخزن</th>
                    <th>العملية</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                $query = mysqli_query($connection, "SELECT * FROM stores ORDER BY id ASC");
                if (mysqli_num_rows($query) > 0) {
                    while ($store = mysqli_fetch_assoc($query)) {
                        echo '<tr>
                                <td>' . $store['id'] . '</td>
                                <td>' . htmlspecialchars($store['store_name']) . '</td>
                                <td>
                                    <a href="stores.php?page=Edit&id=' . $store['id'] . '" class="btn btn-outline-primary btn-sm">تعديل</a>
                                    <form action="?page=Delete" method="post" class="d-inline" onsubmit="return confirm(\'هل أنت متأكد من حذف المخزن؟\');">
                                        <input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">
                                        <input type="hidden" name="id" value="' . $store['id'] . '">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                              </tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">لا توجد مخازن</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery للـ AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function(){
        $("#search").on("keyup", function(){
            var search = $(this).val();
            $.ajax({
                url: "ajax/stores_search.php",
                method: "GET",
                data: { search: search },
                success: function(data){
                    $("#table-body").html(data);
                }
            });
        });
    });
    </script>

    <?php
} elseif ($page === 'create') {
    $store_name = $_POST['store_name'] ?? '';
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($store_name)) $errors['store_name'] = "اسم المخزن مطلوب";

        if (empty($errors)) {
            $stmt = mysqli_prepare($connection, "INSERT INTO stores (store_name) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $store_name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['flash_message'] = "تمت إضافة المخزن بنجاح";
            header("Location: stores.php");
            exit;
        }
    }

    echo "<h2 class='text-center my-3'>إضافة مخزن</h2>";
    echo '<form method="POST" class="w-50 mx-auto">
            <div class="mb-3">
                <label>اسم المخزن</label>
                <input type="text" name="store_name" class="form-control" value="' . htmlspecialchars($store_name) . '">';
    if (isset($errors['store_name'])) {
        echo '<div class="text-danger">' . $errors['store_name'] . '</div>';
    }
    echo '</div>
            <button class="btn btn-success">حفظ</button>
            <a href="stores.php" class="btn btn-secondary">رجوع</a>
          </form>';

} elseif ($page === 'Edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $store = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM stores WHERE id = $id"));

    if (!$store) {
        echo "<div class='alert alert-danger text-center'>المخزن غير موجود</div>";
    } else {
        $store_name = $_POST['store_name'] ?? $store['store_name'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($store_name)) $errors['store_name'] = "اسم المخزن مطلوب";

            if (empty($errors)) {
                $stmt = mysqli_prepare($connection, "UPDATE stores SET store_name = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "si", $store_name, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $_SESSION['flash_message'] = "تم تعديل المخزن بنجاح";
                header("Location: stores.php");
                exit;
            }
        }

        echo "<h2 class='text-center my-3'>تعديل المخزن</h2>";
        echo '<form method="POST" class="w-50 mx-auto">
                <div class="mb-3">
                    <label>اسم المخزن</label>
                    <input type="text" name="store_name" class="form-control" value="' . htmlspecialchars($store_name) . '">';
        if (isset($errors['store_name'])) {
            echo '<div class="text-danger">' . $errors['store_name'] . '</div>';
        }
        echo '</div>
                <button class="btn btn-primary">تحديث</button>
                <a href="stores.php" class="btn btn-secondary">رجوع</a>
              </form>';
    }

} elseif ($page === 'Delete' && isset($_POST['id'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<div class='alert alert-danger text-center'>طلب غير صالح (CSRF)</div>");
    }

    $id = intval($_POST['id']);
    $store = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM stores WHERE id = $id"));

    if (!$store) {
        echo "<div class='alert alert-danger text-center'>المخزن غير موجود</div>";
    } else {
        mysqli_query($connection, "DELETE FROM stores WHERE id = $id");
        $_SESSION['flash_message'] = "تم حذف المخزن بنجاح";
        header("Location: stores.php");
        exit;
    }
}

echo "</div>";
include_once './../includes/footer.php';
?>
