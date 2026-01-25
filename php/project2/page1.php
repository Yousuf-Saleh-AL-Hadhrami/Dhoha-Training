<!DOCTYPE html>
<html>
<head>
    <title>PHP Project: Computer Basics</title>
</head>
<body>

<h1>Welcome to My Computer PHP Project</h1>

<?php

$firstMessage = "Welcome to the Computer World!";
$computerName = "Dell";
$cpuSpeed = 3.2;
$ramGB = 16;
$isLaptop = true; 
$software = array("Windows", "Office", "Chrome"); 
$emptyVar = null; 

// =
echo "<h2>Computer Information using echo</h2>";
echo $firstMessage . "<br>";
echo "Computer Brand: $computerName<br>";
echo "CPU Speed: $cpuSpeed GHz<br>";
echo "RAM: $ramGB GB<br>";
echo "Is it a laptop? " . ($isLaptop ? "Yes" : "No") . "<br>";

// echo 
echo "Installed software: ", $software[0], ", ", $software[1], " and ", $software[2], ".<br>";

// 
print "<h2>Using print</h2>";
print "Hello Computer World!<br>";
print "Brand: " . $computerName . "<br>";
print "RAM: " . $ramGB . "<br>";

//
echo "<h2>PHP Data Types Example</h2>";

//
$stringVar = "Hello Computer!";
echo "String example: $stringVar<br>";
var_dump($stringVar);
echo "<br>";

//
$intVar = $ramGB;
echo "Integer example (RAM): $intVar<br>";
var_dump($intVar);
echo "<br>";

// 
$floatVar = $cpuSpeed;
echo "Float example (CPU speed): $floatVar<br>";
var_dump($floatVar);
echo "<br>";

// 
$boolVar = $isLaptop;
echo "Boolean example (Is Laptop?): ";
var_dump($boolVar);
echo "<br>";

// 
$arrayVar = $software;
echo "Array example (Installed software): ";
var_dump($arrayVar);
echo "<br>";

// 
$nullVar = $emptyVar;
echo "Null example: ";
var_dump($nullVar);
echo "<br>";

?>

</body>
</html>















