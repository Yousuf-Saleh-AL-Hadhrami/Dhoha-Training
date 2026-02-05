<?php 

// echo "Index.php <strong>Hello</strong>";
// 
// $array = ["Yousuf","Khould","Salim"];
// 
// class Person{
//   public $names = ["Ali","Nasser","Saleh"];
// }
// 
// $p1 = new Person;
// 
// 
// $stringArray =  json_encode($array);
// 
// echo "<br>";
// 
// echo json_encode($p1->names);
// 
// echo $stringArray;

// require "./Person.php";
// require "./BankAccount.php";
// require "./Car.php";
// require "./City.php";
// require "./Employee.php";
// require "./DailyEmployee.php";

require './vendor/autoload.php';

use App\Person\Person;
use App\Employee\Employee;
use Carbon\Carbon;
use App\Tripe;
use App\Hobbies;


$tripe1 = new Tripe('الحضرمي');
$tripe2 = new Tripe('التوبي');

$hobbies1 = new Hobbies;
$hobbies1->setHobbies(["Writing","Reading"]);


$hobbies2 = new Hobbies;
$hobbies2->setHobbies(["Tennis","Footabll"]);


$p1 = new Person(1000,"Yousuf","Izki", $hobbies1, $tripe1); 
$e1 = new Employee(9000,"Nasser","Izki", $hobbies2, $tripe2, 300,100,"Programmer");


echo $p1->setPassword("admin")
       ->login("admin")
       ->getAllData();
echo "<br>";


echo $e1->setPassword("admin")
       ->login("admin")
       ->getAllData();
echo "<br>";


//var_dump($p1->getHobbies());
//echo $e1->getAllData();
// echo Person::AGE;
// echo "<br>";
// echo Employee::AGE;

// echo $e1->setPassword("123456")
// ->login("123456")
// ->one("One")
// ->getAllData();

//Carbon::setLocale('ar');


// $now = Carbon::now();
// 
// echo $now->monthName;
// 
// $date = new DateTime("now", new DateTimeZone("Asia/Muscat"));
// echo $date->sub(new DateInterval(("P3Y1M6D")))
       //    ->format("Y-m-d H:i:s");
// 

