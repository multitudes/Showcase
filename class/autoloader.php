<?php
// class/autoloader.php
// standard PHP Library

spl_autoload_register(function($class_name)
{
 // class/class_Controller.inc.php     
 include "class_".$class_name.".php";
});

?>