<?php 

// Data Types 

$integer = (float) 10;
$float = 15.5;
$boolean = true;
$name = "Yousuf";
$countries = ["Oman","Qatar","Yemen"];
$status = null;

class Book {

}

$book1 = new Book;  // book 

echo gettype($integer) . PHP_EOL;

echo gettype($countries) . PHP_EOL;

echo gettype($book1) . PHP_EOL;

echo is_integer($book1) . PHP_EOL;

echo is_numeric($float) . PHP_EOL;



if(is_numeric($integer)){

 echo "The Value of integer variable is a numeric value" . PHP_EOL;
} else {

echo "Not a numeric value" . PHP_EOL;
}

// Type Juggling vs Type Cating 

// echo (int) $float;

echo intval($float);

