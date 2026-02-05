<?php 

namespace App\BankAccount;

interface BankAccount
{
 public function setSalary($bankName , $accountNumber);

 public function getSalary();


}
