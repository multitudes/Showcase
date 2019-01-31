<?php
// php/myPDO.php
// make the connection to the database. Create a PDO 

class myPDO{
    public static $pdo;
    private $host= "localhost";
    private $user="root";
    private $pass="root";
    private $db="showcase";
    
    public function __construct(){
        try {
            $sdh =  new PDO("mysql:host=$this->host", $this->user, $this->pass);
            $sdh->exec("CREATE DATABASE IF NOT EXISTS `$this->db`;
                        GRANT ALL ON `$this->db`.* TO 'laurent'@'localhost' IDENTIFIED BY '1984!';
                        GRANT ALL ON `$this->db`.* TO 'laurent'@'127.0.0.1' IDENTIFIED BY '1984!';
                        FLUSH PRIVILEGES;
                        USE `$this->db`;")
            or die(print_r($sdh->errorInfo(), true));
        } catch (PDOException $e) {
        die("DB ERROR: ". $e->getMessage());
        }
    
        SELF::$pdo = new PDO('mysql:host=localhost;port=3306;dbname=showcase', $this->user,$this->pass);
        // See the "errors" folder for details...
        SELF::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }
    }
?>