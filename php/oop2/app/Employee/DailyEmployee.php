<?php 

namespace App\Employee;

use App\Person\Person;

class DailyEmployee extends Employee
{
    public $days;

    public $rate;  

    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    public function setTotalSalary(): static
    {
        $this->days * $this->rate;

        return $this;
    }


    public function getAllData(): string                          
{
     return Person::getAllData() ." ". $this->days . " ". $this->rate . "Salary" . $this->days * $this->rate;
        
}
    
    
    
}