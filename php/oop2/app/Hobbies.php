<?php 

namespace App;

class Hobbies 
{
public array $hobbies;

public function setHobbies(array $hobbies)
{
    $this->hobbies = $hobbies;
}
    public function getHobbies(): array
    {
       return $this->hobbies;

    }
}