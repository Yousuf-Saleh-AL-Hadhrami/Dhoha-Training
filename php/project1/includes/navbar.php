
<?php 
define("ROUTE","http://localhost/Dhoha/php/project1/"); // Root Directory
?>
 <nav class="navbar navbar-expand-lg bg-body-tertiary">
     <div class="container">
         <a class="navbar-brand" href="#">Ecommerce</a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
             data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="fa
             aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
             <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                 <li class="nav-item">
                     <a class="nav-link active" aria-current="page" href="<?= ROUTE ?>">Home</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="<?= ROUTE ?>users/index.php">Users</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="project1/categories/index.php">Categories</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="/products/products.php">Products</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="orders.php">Orders</a>
                 </li>
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                         aria-expanded="false">
                         Settings
                     </a>
                     <ul class="dropdown-menu">
                         <li><a class="dropdown-item" href="#"><?=  $_SESSION['username'] ?></a></li>
                         <li><a class="dropdown-item" href="#"><?=  $_SESSION['name'] ?></a></li>
                         <li>
                             <hr class="dropdown-divider">
                         </li>
                         <li><a class="dropdown-item" href="./../logout.php">Logout</a></li>
                     </ul>
                 </li>
             </ul>
             <form class="d-flex" role="search">
                 <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                 <button class="btn btn-outline-success" type="submit">Search</button>
             </form>
         </div>
     </div>
 </nav>