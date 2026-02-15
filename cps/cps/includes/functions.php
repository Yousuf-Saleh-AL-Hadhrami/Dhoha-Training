<?php 

/* 
     function()
     {
       Body to write code 
     }

     - Built-in functions 
     - user defined functions 
*/

include_once '../includes/config.php';

function old_input($inputName , $default = ''){

   if(isset($_POST[$inputName]))
   {
     return $_POST[$inputName];
   }

   return $default;

}

function count_devices(mysqli $connection): int {
    $query = mysqli_query($connection, "SELECT COUNT(*) AS total FROM device_stock");

    if ($query && $result = mysqli_fetch_assoc($query)) {
        return (int)$result['total'];
    }

    
    return 0;
}
function count_devices_expense(mysqli $connection): int {
    $query = mysqli_query($connection, "SELECT COUNT(*) AS total FROM expenses");

    if ($query && $result = mysqli_fetch_assoc($query)) {
        return (int)$result['total'];
    }

    
    return 0;
}