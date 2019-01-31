<?php


// this is just an example of code
$privateKey = '1984!';
$userName = 'laurent';
$url = 'http://localhost:8890/ShowcaseProject';
//$timeLimit = new DateTime('Tomorow');
$salt = openssl_random_pseudo_bytes(int8):string;
   echo "salt: ".$salt;
function createToken($privateKey, $salt){
        return hash('sha256', $privateKey.$salt);
}

function createUrl($privateKey,$salt, $url, $userId){ 
    $token = createToken($privateKey,$salt);

    $autoLoginUrl = http_build_query(array(
        'id' => $userId,
        'token' => $token
    ));
    return $url.'?'.$autoLoginUrl;
}

function checkUrl($privateKey){  
    $hash = createToken($privateKey, $salt);
    return ($_GET['token'] == $hash);
}  



?>
  
