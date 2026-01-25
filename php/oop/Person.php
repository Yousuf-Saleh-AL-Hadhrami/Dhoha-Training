<?php 

class Person {
    public $id;
    public $name;
    public $address;
    public $hobbies = [];

    public function __construct() {

    // $this->id = $id;
    // $this->name = $name;
    // $this->address = $address;
    // $this->hobbies[] = $h;

    echo "سيتم 'طباعة' هذه الجملة عند أخذ نسخة من الكلاس <br>";

    }

    public function setName($n)
{
    $this->name = $n;

    return $this;
}

public function setId($n)
{
    $this->id = $n;

    return $this;
}

public function setAddress($address)
{
    $this->address = $address;

    return $this;
}


public function setHobbies($hobbies)
{
    $this->hobbies = $hobbies;

    return $this;
}


public function showData()
{
    return $this->id ." ". $this->name ." ". $this->address . " " . implode("-", $this->hobbies);
}

}
 