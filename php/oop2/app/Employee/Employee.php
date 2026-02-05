<?php
declare(strict_types=1);

namespace App\Employee;

use App\Car\Car;
use App\Hobbies;
use App\City\City;
use App\Person\Person;
use App\BankAccount\BankAccount;


class Employee extends Person implements BankAccount,City
{
    use Car;

    protected ?int $baseSalary = null;
    protected ?int $bonus = null;
    protected ?int $totalSalary = null;
    protected ?string $job = null;

    public $bankName;

    public $accountNumber;

    public const AGE = 22;

    public function __construct(
        int $id = 0,
        string $name = '',
        string $address = '',
        Hobbies $hobbies,
        $tripe,
        ?int $baseSalary = null,
        ?int $bonus = null,
        ?string $job = null
    ) {
        parent::__construct($id, $name, $address, $hobbies , $tripe);

        $this->baseSalary = $baseSalary;
        $this->bonus = $bonus;
        $this->job = $job;
    }

    public function setJob(string $job): static
    {
        $this->job = $job;
        return $this;
    }

    public function setBaseSalary(int $salary): static
    {
        $this->baseSalary = $salary;
        return $this;
    }

    public function setBonus(int $bonus): static
    {
        $this->bonus = $bonus;
        return $this;
    }

    public function addBonus(int $bonus): static
    {
        $this->bonus = ($this->bonus ?? 0) + $bonus;
        return $this;
    }

    public function deductBonus(int $bonus): static
    {
        $this->bonus = ($this->bonus ?? 0) - $bonus;
        return $this;
    }

    public function setTotalSalary(): static
    {
        $this->totalSalary = ($this->baseSalary ?? 0) + ($this->bonus ?? 0);
        return $this;
    }

    public function setSalary($bankName, $accountNumber)
    {
        $this->bankName = $bankName;
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getSalary()
    {
        $total = $this->baseSalary + $this->bonus;

        return  "{$this->job} {$this->baseSalary} {$this->bonus} {$total}";
    }

    public function getAllData()
    {
    try{
      if(!$this->isLogin){
      throw new \Exception("Failed to Login!");
  }
        return parent::getAllData() . " " . $this->getSalary();
           
    }catch(\Exception $e){

    echo $e->getMessage();

    }
    }
}
