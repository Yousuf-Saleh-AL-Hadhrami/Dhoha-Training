<?php 
declare(strict_types= 1);

namespace App\Person;

use App\Tripe;
use App\Hobbies;

class Person{

public ?int $id = null; // nullable typed operator 
public ?string $name = null;
//private ?array $hobbies = [];

protected ?string $address = null;

public $hobbies;

public static $counter = 0;                            

public const AGE = 18;

private string $password = "";

public $isLogin = false;

public $tripe;


public function __construct($id = 0 , $name = '' , $address = '' , $hobbies , $tripe ){

$this->id = $id;
$this->name = $name;
$this->address = $address;
$this->hobbies = $hobbies;
$this->tripe  = $tripe;

self::$counter++;

//echo " I Have Instantiated an Object <br>";

}

public static function getCount()
{
   return self::$counter;
}

public static function who()
{
  return self::class;
}

public function setName($n)
{
   $this->name = $n;

   return $this;
}

public function getName()
{
    return $this->name;
}

public function setId(int $id): static
{
    $this->id = $id;

   return $this;

}

public function getId()
{
    return $this->id;
}

public function setAddress($address)
{
    $this->address = $address;

   return $this;

}

public function getAddress()
{
    return $this->address;
}

public function setHobbies($hobbies)
{
    $this->hobbies = $hobbies;

   return $this;

}

public function getHobbies()
{
    return $this->hobbies->getHobbies();
}

public function getTripe()
{ 
   return $this->tripe->getTribeName();
}

public function getAllData()
{
    try{
        if(!$this->isLogin){

        throw new \Exception("Failed to Login!");
    }

        return $this->id . " "
            . $this->name . " "
            . $this->getTripe() . " "
            . $this->address . " "
            . implode("-",$this->getHobbies());
            

    }catch(\Exception $e){

    echo $e->getMessage();

    }
}

public function setPassword($password)
{
    $this->password = password_hash($password , PASSWORD_DEFAULT);

    return $this;
}

public function login($password){

if(password_verify($password, $this->password)){

   $this->isLogin = true;
   return $this;
    
} 

   return $this;
}

public function getHouseName(): string
{
    return "House One";
}


}

  



