<?php
// class//class_Service.inc.php
// Dient zur Konnectivität zum Server
class Service{
  private static $myPDO;//Handler /die Verbindung /Instanz

  private static function connectDB($user='laurent',$pass='1984!'){
      $host= "localhost";
      $db_name="showcase";

   try{
     SELF::$myPDO = new PDO("mysql:host=".$host.";port = 3306; dbname=".$db_name.";charset=utf8",$user,$pass);
   }
   catch(PDOExeption $e){
     exit("Fehler: ".$e->getMessage());
   }  
  }//end Methode connect
   
    
    
  // SQL Injection vermeiden durch prepare
  public static function setPrepare($sql){
      SELF::connectDB($user,$pass);
      return SELF::$myPDO->prepare($sql);//zu Model
  }    
  // mehrere Produkte    
  public static function getAll($sth){
      $sth->execute();//ausführen der vorbereiteten Anfrage
      //SELF::connectDB(); //Datenbankverbindung aufbauen
      //$res = SELF::$myPDO->query($sql); //Anfrage ausführen
      return $sth->fetchAll(PDO::FETCH_ASSOC); //konvertiert das Ergebniss zu einem asso Array
  }    
// eine Zeile (ein Produkt)
  public static function getOne($sth){
      $sth->execute();    
      return $sth->fetch(PDO::FETCH_ASSOC);//ersten Treffer
  }
  public static function getAllasIA($sth){
      $sth->execute(); 
      return $sth->fetchAll(PDO::FETCH_COLUMN,0);//nummeriertes Array
  }  
    
}


?>