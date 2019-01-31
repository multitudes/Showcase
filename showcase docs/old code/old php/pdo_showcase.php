<?php
// php/pdo.php
// make the connection to the database. Create a PDO 

$host= "localhost";
$user="laurent";
$pass="1984!";
$db_name="showcase";

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=showcase',
   $user,$pass);
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




