<?php
//class/class_Model.inc.php
// Diese Klasse dient auschließlich für SQl Anfragen
class Model{

 public static function getAllProducts(){
   //Anfrage formulieren
   $sql = "SELECT T1.id, T1.name, T1.text, T1.price , T2.link
           FROM tb_products T1, tb_image T2
           WHERE T1.id = T2.id_products
           ";
   //mySQL injection verhindern
   $sth = SERVICE::setPrepare($sql);//vorbereiten
   return SERVICE::getAll($sth);//führt Verbindung zum MySQL Server aus 
 }
   // Alle Produktinformationen zu einem Produkt 
  public static function getOneProduct($id){
  $sql = "SELECT T1.id, T1.name, T1.text, T1.price , T2.link
           FROM tb_products T1, tb_image T2
           WHERE T1.id = T2.id_products
           AND T1.id = ? ";
          $sth = SERVICE::setPrepare($sql);
          $sth->bindValue(1,$id,PDO::PARAM_INT);//type int
          return SERVICE::getOne($sth);
  } 
   // Alle Betriebsysteme zu einem Produkt 
  public static function getAllSystem($id){
   $sql = "SELECT T2.system  
   FROM tb_z_prod_syst T1, tb_system T2
   WHERE T1.id_syst = T2.id
   AND T1.id_prod = ? 
   ";
   $sth = SERVICE::setPrepare($sql);
   $sth->bindValue(1,$id,PDO::PARAM_INT);  
   return SERVICE::getAllasIA($sth);//als indiziertes Array
  }

}

?>