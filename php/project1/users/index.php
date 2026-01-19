<?php 
session_start();
$title = 'المستخدمون';

include "./../includes/header.php";
include "./../includes/navbar.php";
include "./../includes/database.php";

?>

<h1 class="text-center text-primary my-3">Manage Users</h1>
<?php 

$query = " SELECT users.* , departments.dname FROM users INNER JOIN departments 
            ON users.department_id = departments.id";
$result = mysqli_query($connection,$query);
$rows = mysqli_num_rows($result);
if($rows == 0) {
  echo "<div class='alert alert-danger'>No Users Found!</div>";
} else {?>

<div class="container">
    <div class="table-responsive">
        <a href="create.php" class="btn btn-outline-info btn-sm mb-2" title="Add New User"><i class="fa-solid fa-plus  me-1"></i>Create New</a>
        <?php 
          if(isset($_SESSION['flash_message'])):
             echo $_SESSION['flash_message'];
             unset($_SESSION['flash_message']);
          endif;
            
        ?>
<table class="table table-bordered text-center table-hover table-striped">
    <thead>
        <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Role</th>
        <th>Department</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody>
    

        
     <?php 
      while($rows = mysqli_fetch_assoc($result)) {
           echo "<tr>";
        echo "<td>$rows[id]</td>";
        echo "<td>$rows[username]</td>";
        echo "<td>$rows[name]</td>";
        echo "<td>$rows[role]</td>";
        echo "<td>$rows[dname]</td>";
        echo "<td>
        
        <a href='edit.php?id=$rows[id]' class='btn btn-outline-primary btn sm'><i class='fa-solid fa-pen-to-square'></i></a>
        <a href='delete.php?id=$rows[id]' class='btn btn-outline-danger btn sm'>Delete</a>
        <a href='show.php?id=$rows[id]' class='btn btn-warning btn sm'>Show</a>

           </td>";
           echo "</tr>";

      }
     ?>

    </tbody>
</table>
    </div>
</div>

<?php
}


?>

<?php 
include "./../includes/footer.php";
?>



