<?php 
declare(strict_types= 1);

namespace App;

class Tripe{
    public ?string $tripe;

    public function __construct($tname){
        $this->tripe = $tname;
    }

    public function getTribeName()
    {
        return $this->tripe;
    }
}