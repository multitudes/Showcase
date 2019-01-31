<?php


$password = '123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
var_dump($hashed_password);
if(password_verify($password, $hashed_password)) {
   echo 'it works !! ';
    //echo hash('sha512',1+4);}
}
echo '<hr>';
echo "<h2>magic link test</h2>";
$length = 8;
$token = bin2hex(random_bytes($length));
echo strlen($token);
//$token_bin = random_bytes($length);
//echo"token_bin : '.$token_bin'";
echo'token : '.$token.' hash: ';
$hashed_token = password_hash($token, PASSWORD_DEFAULT);
var_dump($hashed_token);
//$hashed_token='$2y$10$f93XQ4ZDhpOpJT.P69IFaO50q3EnG4hfkAy6ZIXN7Lltr8g7RRw';
if(password_verify($token, $hashed_token)) {
   echo 'yes works !! ';
    //echo hash('sha512',1+4);}
}

?><html>
    
<h3>second check</h3>    
    
</html>
<?php
$token='f88c412df393955aa4a351fcfe8cfa55326a02aa86c833351b03c12465e0';
$hashed_token='$2y$10$iK.dlXlDUD48IlI/8Jf.puqUN7PpxlKNuprekc3tqDw1xjuAKDZtK';
if(password_verify($token, $hashed_token)) {
   echo 'yes works !! ';
    //echo hash('sha512',1+4);}
}

