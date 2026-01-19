<?php
session_start();
$title = 'إضافة مستخدم جديد';

include "./../includes/header.php";
include "./../includes/navbar.php";
include "./../includes/database.php";

$message = "";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $role     = $_POST['role'] ?? '';

    if (!empty($name) && !empty($username)) {

        // Hash password
        $hashedPassword = password_hash("123456", PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, username, password, role)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $name,
                $username,
                $hashedPassword,
                $role
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['flash_message'] = '<div class="alert alert-success">User created successfully.</div>';
                header("location:index.php");
                exit;
            } else {
                 $_SESSION['flash_message']  = '<div class="alert alert-danger">Failed to create user.</div>';
                 header("location:index.php");
                 exit;
            }

            mysqli_stmt_close($stmt);
        } else {
            $message = '<div class="alert alert-danger">Database error.</div>';
        }

    } else {
        $message = '<div class="alert alert-warning">Please fill all fields.</div>';
    }
}
?>

<div class="container">
    <h1 class="text-center text-primary my-4">Create User</h1>

    <?= $message ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">

                    <form method="POST" action="">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="">-- Select Role --</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i> Create User
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "./../includes/footer.php";
?>
