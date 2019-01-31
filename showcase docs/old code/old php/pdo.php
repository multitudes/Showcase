<?php
// php/pdo.php
// make the connection to the database. Create a PDO 

class DB_Connection{
    private $host= "localhost";
    private $user="root";
    private $pass="root";
    private $db="showcase";
    
    public function __construct(){
    
        try {
            $dbh =  new PDO("mysql:host=$this->host", $this->user, $this->pass);
            $dbh->exec("CREATE DATABASE IF NOT EXISTS `$this->db`;
                        GRANT ALL ON `$this->db`.* TO 'laurent'@'localhost' IDENTIFIED BY '1984!';
                        GRANT ALL ON `$this->db`.* TO 'laurent'@'127.0.0.1' IDENTIFIED BY '1984!';
                        FLUSH PRIVILEGES;
                        USE `$this->db`;")
        or die(print_r($dbh->errorInfo(), true));
        } catch (PDOException $e) {
        die("DB ERROR: ". $e->getMessage());
        }
    }
    
    public function connect(){
        $pdo = new PDO('mysql:host=localhost;port=3306;dbname=showcase', $user,$pass);
        // See the "errors" folder for details...
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
        }
}

new DB_Connection();
#var_dump($pdo);var_dump($dbh);


/*

if ( isset($_POST['name']) && isset($_POST['email']) 
     && isset($_POST['password'])) {
    $sql = "INSERT INTO users (name, email, password) 
              VALUES (:name, :email, :password)";
    echo("<pre>\n".$sql."\n</pre>\n");
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':password' => $_POST['password']));
        header("location:db_init.php");
}
if ( isset($_POST['id']) ) {
    $sql = "DELETE FROM users WHERE id = :zip";
    echo "<pre>\n$sql\n</pre>\n";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['id']));
}
$stmt = $pdo->query("SELECT id, name, email, password FROM users");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head></head><body><table border="1">
<?php
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo($row['id']);
    echo("</td><td>");
    echo($row['name']);
    echo("</td><td>");
    echo($row['email']);
    echo("</td><td>");
    echo($row['password']);
    echo("</td></tr>\n");
}
?>
    <p>Delete A User</p>
<form method="post">
<p>ID to Delete:
<input type="text" name="id"></p>
<p><input type="submit" value="Delete"/></p>
</form>
    
</table>
<p>Add A New User</p>
<form method="post" action="db_init.php">
<p>Name:
<input type="text" name="name" size="40"></p>
<p>Email:
<input type="text" name="email"></p>
<p>Password:
<input type="password" name="password"></p>
<p><input type="submit" value="Add New"/></p>
</form>
</body>

<?php


echo "<pre>\n";
$stmt = $pdo->query("SELECT * FROM users");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
echo "</pre>\n";


try{
 $pdo = new PDO("mysql:host=".$host, $user, $pass);
}
catch(PDOExeption $e){
 exit("Fehler: ".$e->getMessage());
}

/*$sql[] = "INSERT INTO tb_system(id,system)
          VALUES (5,'iOS')";


$sql[] = "CREATE TABLE IF NOT EXISTS tb_admin(
          id INT(11) AUTO_INCREMENT PRIMARY KEY,
          id_account INT(11) NOT NULL
          )";

$sql[] = "CREATE TABLE IF NOT EXISTS tb_system(
         id INT(11) AUTO_INCREMENT PRIMARY KEY,
         system VARCHAR(100) NOT NULL UNIQUE
         )";

$sql[] = "CREATE TABLE IF NOT EXISTS tb_z_prod_syst(
         id_prod INT(11) NOT NULL,
         id_syst INT(11) NOT NULL,
         FOREIGN KEY(id_prod) REFERENCES tb_products(id),
         FOREIGN KEY(id_syst) REFERENCES tb_system(id)
          )";

$sql[] = "ALTER TABLE tb_z_prod_syst ADD UNIQUE(id_prod,id_syst)";

$sql[] = "INSERT INTO tb_z_prod_syst(id_prod,id_syst)
          VALUES (1,1)";
          */


/*$sql[] = "INSERT INTO tb_products(name,text,price)
         VALUES ('antivir','Virensoftware kostenloses Komplettpaket,vielfach preisgekrönt',00.00)
         ";

$sql[] = "INSERT INTO tb_image(id_products,link)
          VALUES (1,'antivir_1.png')";

$sql[] = "INSERT INTO tb_system(id,system)
          VALUES (1,'Windows')";

$sql[] = "INSERT INTO tb_z_prod_syst(id_prod,id_syst)
          VALUES (1,1)";*/
/*
foreach($sql as $query){
    $myPDO->exec($query);
    var_dump($myPDO);
}
*/
?>