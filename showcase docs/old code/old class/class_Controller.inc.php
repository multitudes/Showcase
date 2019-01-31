<?php
//class/class_Controller.inc.php

class Controller{
  private $req;    
  public function __construct(){
       $this->req = $_REQUEST;//Superglobale Querystring ?id=2323
  
      //Verteiler
       
    switch(true){
      case isset($this->req['id']):
            echo "\nplaylist".$this->req['id']."\n";
            //$this->generateProduct();
            break;
     //case default login
            default: header("Location: php/login.php");
               
    }
    
  }
    
  private function retrievePlaylist(){
     
  } 
  
}

?>