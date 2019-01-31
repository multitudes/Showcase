<?php
class Login{
    static $myPDO = NULL;
    private $host= "localhost";
    private $user="root";
    private $pass="";
    private $db_name="db_login";
    
    public function __construct(){
      if(is_null($this->myPDO))$this->createDB();   
    }
    
    
    private function createDB(){
     try{
       $this->myPDO = new PDO("mysql:host=".$this->host, $this->user, $this->pass);
      }
     catch(PDOExeption $e){
       exit("Fehler: ".$e->getMessage());
     }
      // Datenbank erstellen
     $this->myPDO->exec("CREATE DATABASE IF NOT EXISTS ".$this->db_name);
      //Datenbank bereitstellen
     $this->myPDO->exec("USE ".$this->db_name);
     $this->myPDO->exec("SET NAMES utf8;SET CHARACTER SET UTF8"); //Windows/Unix
     $this->createTB();    
    }  
    
    private function createTB(){
        $sql = "CREATE TABLE IF NOT EXISTS tb_pass(
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(100) NOT NULL UNIQUE,
                pass VARCHAR(128) NOT NULL 
               )";
        $this->myPDO->exec($sql);
         $sql = "CREATE TABLE IF NOT EXISTS tb_salt(
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                fk_id_pass INT(11),
                salt VARCHAR(50) NOT NULL,
                FOREIGN KEY(fk_id_pass) REFERENCES tb_pass(id)
         )";
    }  
}


new Login();

?>