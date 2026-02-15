<?php
namespace YousufAlhadhrami\Helpers\Helper;

use YousufAlhadhrami\Helpers\Helper;

require './../vendor/autoload.php';

session_start();

$title = 'المستخدمين';

include_once './authorize.php';
include_once './../includes/header.php';
include_once './../includes/navbar.php';
include_once './../includes/config.php';

$page = $_GET['page'] ?? 'Index';

echo "<div class='container'>";

if ($page === 'Index') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    echo "<h2 class='text-center my-3'>المستخدمين</h2>";

    $query = mysqli_query($connection, "SELECT id, staff_name FROM users");

    if (isset($_SESSION['flash_message'])) {
        echo "<div class='alert alert-success text-center'>{$_SESSION['flash_message']}</div>";
        unset($_SESSION['flash_message']);
    }
    ?>

    <a href="users.php?page=create" class='btn btn-outline-info btn-sm mb-2'>إضافة مستخدم</a>
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-secondary">
                <tr>
                    <th>رقم المستخدم</th>
                    <th>الإسم</th>
                    <th>العملية</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($user = mysqli_fetch_object($query)): ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= htmlspecialchars($user->staff_name) ?></td>
                    <td>
                        <a href="users.php?page=Edit&id=<?= $user->id ?>" class="btn btn-outline-primary btn-sm">تعديل</a>
                        <form action="?page=Delete" method="post" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف المستخدم؟');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($user->id) ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm">حذف</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php
} elseif ($page === 'create') {
    $staff_name = $_POST['staff_name'] ?? '';
    $username = $_POST['username'] ?? '';
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($staff_name)) $errors['staff_name'] = "الاسم مطلوب";
        if (empty($username)) $errors['username'] = "اسم المستخدم مطلوب";

        if (empty($errors)) {
            $password = password_hash('1234566', PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($connection, "INSERT INTO users (staff_name, username, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $staff_name, $username, $password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['flash_message'] = "تمت إضافة المستخدم بنجاح";
            header("Location: users.php");
            exit;
        }
    }

    echo "<h2 class='text-center my-3'>إضافة مستخدم</h2>";
    ?>
    <form method="POST" class="w-50 mx-auto">
        <div class="mb-3">
            <label>الإسم</label>
            <input type="text" name="staff_name" class="form-control" value="<?= htmlspecialchars($staff_name) ?>">
            <?php if (isset($errors['staff_name'])): ?>
                <div class="text-danger"><?= $errors['staff_name'] ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label>اسم المستخدم</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>">
            <?php if (isset($errors['username'])): ?>
                <div class="text-danger"><?= $errors['username'] ?></div>
            <?php endif; ?>
        </div>
        <button class="btn btn-success px-4">حفظ</button>
        <a href="users.php" class="btn btn-secondary px-4">رجوع</a>
    </form>
    <?php
} elseif ($page === 'Edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM users WHERE id = $id"));

    if (!$user) {
        echo "<div class='alert alert-danger text-center'>المستخدم غير موجود</div>";
    } else {
        $staff_name = $_POST['staff_name'] ?? $user['staff_name'];
        $username = $_POST['username'] ?? $user['username'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($staff_name)) $errors['staff_name'] = "الاسم مطلوب";
            if (empty($username)) $errors['username'] = "اسم المستخدم مطلوب";

            if (empty($errors)) {
                $stmt = mysqli_prepare($connection, "UPDATE users SET staff_name = ?, username = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "ssi", $staff_name, $username, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $_SESSION['flash_message'] = "تم تعديل المستخدم بنجاح";
                header("Location: users.php");
                exit;
            }
        }

        echo "<h2 class='text-center my-3'>تعديل مستخدم</h2>";
        ?>
        <form method="POST" class="w-50 mx-auto">
            <div class="mb-3">
                <label>الإسم</label>
                <input type="text" name="staff_name" class="form-control" value="<?= htmlspecialchars($staff_name) ?>">
                <?php if (isset($errors['staff_name'])): ?>
                    <div class="text-danger"><?= $errors['staff_name'] ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label>اسم المستخدم</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>">
                <?php if (isset($errors['username'])): ?>
                    <div class="text-danger"><?= $errors['username'] ?></div>
                <?php endif; ?>
            </div>
            <button class="btn btn-primary px-4">تحديث</button>
            <a href="users.php" class="btn btn-secondary px-4">رجوع</a>
        </form>
        <?php
    }
} elseif ($page === 'Delete' && isset($_POST['id'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<div class='alert alert-danger text-center'>طلب غير صالح (CSRF)</div>");
    }

    $id = intval($_POST['id']);
    $user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM users WHERE id = $id"));

    if (!$user) {
        echo "<div class='alert alert-danger text-center'>المستخدم غير موجود</div>";
    } else {
        mysqli_query($connection, "DELETE FROM users WHERE id = $id");
        $_SESSION['flash_message'] = "تم حذف المستخدم بنجاح";
        header("Location: users.php");
        exit;
    }
}

echo "</div>";

include_once './../includes/footer.php';
?>
