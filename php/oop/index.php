<?php

require './Person.php';
require './Student.php';


$yousuf = new Person();
$dhoha = new Person();

$Muna = new Student();


$yousuf->setName('Yousuf AL Hadhrami')
    ->setId(100)
    ->setAddress('Izki')
    ->setHobbies(['Programming', 'Football'])
    ->showData();


$dhoha->setName('Dhoha')
    ->setId(200)
    ->setHobbies(['Programming', 'Drawing', 'Reading'])
    ->setAddress('Nizwa')
    ->showData();


      $data = [
  "name" => "Mohammed AL Riyami",
  "username" => "m12345",
  "password" => password_hash("123456", PASSWORD_DEFAULT),
  "role" => "admin",
  "department_id" => 2,
   "id" => 3
  ];


    // Student Object
echo $Muna->setName('Muna')
    ->setId(300)
    ->setaddress('Bahla')
    ->setHobbies(['C++', 'PHP'])
    ->setStdId(204060)
    ->setMajor("Software Engineering")
    ->setGpa("3.9")
    ->setCrentials(["username" => "admin","password" => "admin"])
    ->login(["username" => "admin","password" => "wssaddasasd"])
    ->update($data , 3);



echo "<pre>";
echo $yousuf->showData();
echo "<br>";
echo $dhoha->showData();
echo "<br>";
// echo $Muna->showData();


/// INSERT INTO Students Table  




