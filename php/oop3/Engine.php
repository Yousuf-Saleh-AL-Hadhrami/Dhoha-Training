<?php

class Engine 
{
  
 public $name;

 public function setEngineName($name)
 {
    $this->name = $name;
 }

 public function getEngineName()
 {
    return $this->name;
 }
}