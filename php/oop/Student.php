<?php 

class Student extends Person{

   public $stdId;                        
   public $major;
   private $gpa;

   private $credentials = [];

   public $login = false;

   public function setStdId($stdId) 
   {
    $this->stdId = $stdId;
    return $this;
   }

   public function setMajor($major)

   {
    $this->major = $major;
    return $this;
   }

   public function setGpa($gpa)

   {
     $this->gpa = $gpa;

      return $this;
   }

   public function showStudentData(){

      return $this->stdId . " " . $this->major ." ". $this->showGpg() . " ";
   }

   public function showGpg(){

   if( $this->login ){

      return $this->gpa . " ";
   }

       return "Please Login to Show GPA";
   }

   public function insert($data = [])
{
    $pdo = new PDO(
        "mysql:host=localhost;dbname=dhoha;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $sql = "INSERT INTO users (name, username, password, role, department_id)
            VALUES (:name, :username, :password, :role, :department_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    if ($stmt->rowCount() > 0) {
        echo "Data Inserted";
    }
}


 public function update($data = [])
{
    $pdo = new PDO(
        "mysql:host=localhost;dbname=dhoha;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $sql = "UPDATE users 
            SET name = :name,
                username = :username,
                password = :password,
                role = :role,
                department_id = :department_id
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);

    if ($stmt->rowCount() > 0) {
        echo "Data Updated";
    }
}

  
  

public  function delete($id){
  $pdo = new PDO(
  "mysql:host=localhost;dbname=dhoha;charset=utf8mb4",
  "root",
  "",
  [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]
);

    $pdo->exec("DELETE FROM users WHERE id = $id");


}



   public function showData()
   {
      return parent::showData() . $this->showStudentData();
   }

   public function setCrentials($credentials = [])
   {
      $this->credentials = $credentials;

      return $this;
   }

   public function login($credentialss = [])
   {
       if( $this->credentials == $credentialss){

          $this->login = true;

        } 

        return $this;


   }

}