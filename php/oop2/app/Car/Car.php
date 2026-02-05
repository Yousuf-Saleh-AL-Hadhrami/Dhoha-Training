<?php
namespace App\Car;
trait Car{

public $one;
public $two;
public $three;

  public function One($one)
  {
    $this->one = $one;
    return $this;
  }

  public function Two($two)
{
  $this->two = $two;
}

public function Three($three)
{
  $this->three = $three;
}

public function getAll()
{
    return [$this->one, $this->two, $this->three];
}
}