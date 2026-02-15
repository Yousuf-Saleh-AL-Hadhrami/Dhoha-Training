<?php 
session_start();

date_default_timezone_set('Asia/Muscat');

if(!isset($_COOKIE['products'])){
  setCookie("products", "Computer Dell", time() + 30 * 24 * 60 * 60 , "/", "", true , true);
}

include_once "./../includes/header.php";
include_once "./../includes/config.php";


$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);

    if(!$user)
    {
        $error = 'اسم المستخدم غير صحيح!';
    } 
    else 
    {
        if(password_verify($password, $user['password']))
        {
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['staff_name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_login'] = true;

            header("location: ./dashboard.php");
            exit;
        } 
        else 
        {
            $error = 'كلمة المرور غير صحيحة!';
        }
    } 
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>تسجيل الدخول</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<style>
  body {
    background-color: #CFE9FF; /* Light background */
    font-family: 'Poppins', sans-serif;
    direction: rtl;
    margin: 0;
    padding: 0;
  }

  .login-container {
    background-color: #fff;
    border-radius: 15px;
    overflow: hidden;
    max-width: 900px;
    margin: 5% auto;
    display: flex;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    min-height: 500px;
  }

  /* --- Left side with image --- */
  .login-left {
    flex: 1;
    background: url('assets/images/BB.jpg') no-repeat center center;
    background-size: cover;
    border-radius: 15px 0 0 15px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #f7f7f7ff;
    padding: 40px;
  }
  .login-left h2 {
    font-size: 2.2rem;
    font-weight: bold;
    margin-bottom: 15px;
    
  }
  .login-left p {
    font-size: 1.1rem;
    
  }

  /* --- Right side form --- */
  .login-right {
    flex: 1.2;
    padding: 50px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background-color: #ffffff;
    position: relative;
  }

  .icon-circle {
    width: 80px;
    height: 80px;
    background: #3498db; /* button main color */
    color: #fff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 40px;
    margin: 0 auto 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  }

  h4 {
    color: #004a80; /* dark blue heading */
    font-weight: bold;
  }

  .input-group-text {
    background-color: transparent;
    border: none;
    border-bottom: 1px solid #b3d4fc;
    color: #6c757d;
  }

  .form-control {
    border: none;
    border-bottom: 1px solid #b3d4fc;
    border-radius: 0;
    box-shadow: none;
    color: #004a80;
  }

  .form-control:focus {
    border-color: #3498db;
    box-shadow: none;
  }

  .btn-login {
    background: linear-gradient(135deg, #3498db, #5dade2);
    border: none;
    width: 100%;
    padding: 12px;
    color: #fff;
    font-weight: bold;
    border-radius: 25px;
    transition: 0.3s;
    cursor: pointer;
  }

  .btn-login:hover {
    background: linear-gradient(135deg, #5dade2, #3498db);
  }

  a {
    color: #3498db;
    text-decoration: none;
  }
  a:hover {
    color: #1d6fa5;
  }

  .alert-danger {
    text-align: center;
    color: #d9534f;
  }

</style>
</head>
<body>
  <div class="login-container">
    <!-- Left with image -->
    <div class="login-left">
      <h2>مرحباً بعودتك!</h2>
      <p>سجّل الدخول للوصول إلى حسابك</p>
    </div>

    <!-- Right form -->
    <div class="login-right">
      <div class="icon-circle">
        <i class="bi bi-person-fill"></i>
      </div>
      <h4 class="text-center mb-4">تسجيل الدخول</h4>

      <?php if(!empty($error)): ?>
        <p class="alert alert-danger"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
          <input type="text" class="form-control" name="username" id="username" placeholder="اسم المستخدم" required />
        </div>

        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
          <input type="password" name="password" class="form-control" id="password" placeholder="كلمة المرور" required />
        </div>

        <div class="text-end mb-3">
          <a href="#">هل نسيت كلمة المرور؟</a>
        </div>

        <button type="submit" class="btn-login">تسجيل الدخول</button>
      </form>
    </div>
  </div>
</body>
</html>
