
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <!-- <a class="navbar-brand" href="#">C.P.S</a> -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="dashboard.php">الرئيسية</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="users.php">المستخدمين</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="device_types.php">أنواع الأجهزة</a>
        </li>

             <li class="nav-item">
          <a class="nav-link" href="device_companies.php">الطراز</a>
        </li>

             <li class="nav-item">
          <a class="nav-link" href="device_models.php">الموديل</a>
        </li>

         </li>

             <li class="nav-item">
          <a class="nav-link" href="stores.php">المخازن</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="insert_device.php">تسجيل جهاز</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="expense.php">الصرف</a>
        </li>


        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            الإعدادات
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#"><?= $_SESSION['name'] ?></a></li>
            <li><a class="dropdown-item" href="#"><?= $_SESSION['username'] ?></a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <form action="logout.php" method="post">
                <button type="submit">تسجيل الخروج</button>
              </form>

            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>