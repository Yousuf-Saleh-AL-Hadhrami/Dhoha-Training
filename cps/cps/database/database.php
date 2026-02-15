<?php 

require "./../includes/config.php";

$hased_password =  password_hash('123456', PASSWORD_DEFAULT);


$query = "CREATE TABLE IF NOT EXISTS users(
          id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
          staff_name VARCHAR(200),
          username VARCHAR(100),
          password VARCHAR(64)
); ";


$insert = "INSERT INTO users (staff_name , username , password)
                   VALUES 
                ('Yousuf AL Hadhrami','admin', '$hased_password'),
                ('AL Shima Alsulimi','shima', '$hased_password'),
                ('Fatema AL Rawahi','fatma', '$hased_password');

";



if(mysqli_query($connection, $query))
{
    echo "Table Users Created";
}

if(mysqli_query($connection, $insert))
{
    echo "Data Inserted";
}