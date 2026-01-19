<?php
session_start();
$title = 'تعديل مستخدم';

include "./../includes/header.php";
include "./../includes/navbar.php";
include "./../includes/database.php";

$message = "";

// 1️⃣ Check user ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['flash_message'] = '<div class="alert alert-danger">Invalid user ID.</div>';
    header("location:index.php");
    exit;
}

$id = (int) $_GET['id'];

// 2️⃣ Fetch user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['flash_message'] = '<div class="alert alert-danger">User not found.</div>';
    header("location:index.php");
    exit;
}

$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// 3️⃣ Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $role     = $_POST['role'] ?? '';

    if (!empty($name) && !empty($username)) {

        $sql = "UPDATE users 
                SET name = ?, username = ?, role = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($connection, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssi",
                $name,
                $username,
                $role,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['flash_message'] =
                    '<div class="alert alert-success">User updated successfully.</div>';
                header("location:index.php");
                exit;
            } else {
                $message =
                    '<div class="alert alert-danger">Failed to update user.</div>';
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
    <h1 class="text-center text-primary my-4">Edit User</h1>

    <?= $message ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="<?= htmlspecialchars($user['name']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text"
                                   name="username"
                                   class="form-control"
                                   value="<?= htmlspecialchars($user['username']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="">-- Select Role --</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>
                                    Admin
                                </option>
                                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>
                                    User
                                </option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Update User
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
