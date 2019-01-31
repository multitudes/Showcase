<?php
//class/class_Controller.inc.php

class Controller{
  private $req;    
  public function __construct(){
       $this->req = $_REQUEST;//Superglobale Querystring ?id=2323
  //Verteiler
       
    switch(true){
      case isset($this->req['id']):
            $this->generateProduct();
            break;
     //case 
      default: $this->generateAllProducts();
            //VIEW::setLayout('start');     
    }
    VIEW::toDisplay();//letzte Anweisung Layout anzeigen
  }
    
  private function generateAllProducts(){
     $data = MODEL::getAllProducts();//SQL Anfrage formulieren
     VIEW::setLayout($data,'start');
  } 
    
  private function generateProduct(){ 
     $data = MODEL::getOneProduct($this->req['id']);
     $data['system']  = MODEL::getAllSystem($this->req['id']);
     VIEW::setLayout($data,"product");
  }
    
    
    
}

?>