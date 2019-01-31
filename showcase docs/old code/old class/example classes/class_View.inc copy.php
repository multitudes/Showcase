<?php
// class/class_View.inc.php

//statische Klasse
class View{
  private static $out;    

  public static function setLayout($data, $tpl = 'start'){
   
    ob_start();// Puffer auf dem Server
      include("templ/head.tpl");
      include("templ/".$tpl.".tpl");//nach Bedarf
      include("templ/footer.tpl");
    ?>
     
    <!--c Jan Hill -->
    
    <?php  
     SELF::$out =  ob_get_contents();//auslesen Puffer
     ob_end_clean(); //Puffer reinigen  
  }

  public static function toDisplay(){
       echo SELF::$out;
  }     

}
?>