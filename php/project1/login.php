<?php 
session_start();

include "./includes/header.php";
include "./includes/database.php";


$usernameError = '';
$passwordError = '';

if($_SERVER["REQUEST_METHOD"] === "POST")
{
  $username = $_POST["username"];
  $password = $_POST["password"];

  if(empty($username)){

      $usernameError = "Username is Required";
  }

  if(empty($password)){
    $passwordError = "Password is Required";
}
  
if(empty($usernameError) && empty($passwordError)){

  $query = mysqli_query($connection," SELECT * FROM users WHERE username = '$username'");
  $user = mysqli_fetch_array($query);

  if($user){

       if(password_verify($password, $user["password"])){

       $_SESSION["username"] = $user["username"];
       $_SESSION["name"] = $user["name"];
       $_SESSION["role"] = $user["role"];
       $_SESSION["login"] = true;

       header("location: index.php");
       exit;

  }

}
}

}


?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Login</h3>

                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="username" 
                                name="username"
                                placeholder="Enter your username"
                            >
                            <?=  '<p class="text-danger">' . $usernameError . '</p>'  ?? '' ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password" 
                                name="password"
                                placeholder="Enter your password"
                            >
                                <?=  '<p class="text-danger">' . $passwordError . '</p>'  ?? '' ?>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                               Login
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>






<?php 
include "./includes/footer.php";
?>


