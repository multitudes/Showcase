<?php

session_start();
//DB connection
require_once "db_init.php";
require_once "service_util.php";

check_admin_session();

if ( isset($_POST['id']) ) {

    echo "vardump :";
   var_dump($_POST);
try {
        
    if (isset($_POST['admin']))
        {
        $admin = $_POST['admin'];    
        $sql= "UPDATE is_admin 
               SET  admin = :admin WHERE id_users = :id_users";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
            ':id_users' => $_POST['id'],
            ':admin' => $admin));
        }else{
        echo "there is no POST ADMIN!! "."<br>";
        $_POST['admin'] = 'no';    
        }

    $_SESSION['success'] = 'Record Added';
    //header( 'Location: user_admin.php' ) ;
    //return;
} catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
    }   
}


// Guardian: Make sure that id is present    
if ( !isset($_GET['id'])){
  $_SESSION['error'] = "Missing id";
  //header( 'Location: user_admin.php' ) ;
  return;
}        
// from the id I get all users details to prepopulate the form        
$stmt = myPDO::$pdo->prepare("SELECT u.id, username, admin, first_name, last_name,               email, comments, playlist_name 
            FROM users u 
            LEFT JOIN is_admin i ON u.id = i.id_users
            LEFT JOIN users_playlist up ON u.id = up.id_users
            LEFT JOIN playlists p ON p.id = up.id_playlists
            LEFT JOIN user_details d ON u.id = d.id_users
            WHERE u.id = :xyz");

$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user id';
    //header( 'Location: admin.php' ) ;
    return;
}


// Flash pattern to display alert messages 
flash_pattern();

// prepare the data to pre fill the form below
$admin = htmlentities($row['admin']);

// on the form this will be used on the chechbox
if ($admin=='yes'){
    $checked='checked';
}else{
    $checked='';
}

echo " is admin : ".$admin.' '.$checked;
// sanitize my databank output

?>
<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>
<?php include("../templ/head.tpl"); 
    ?>


<!-- view the form prepopulated with the values for the user to edit and submit or cancel and back to the manage users section-->

<h3>Edit User</h3>
<form method="post" id="add_user_form"> <div class="form-group">

<p><label for="admin" >Is an Admin: </label>
    <input type="checkbox" name="admin" value="yes" <?= $checked ?> ></p></div>

<p><input type="hidden" name="id" value="<?= $row['id'] ?>">
<button type="submit" class = "btn btn-primary" >Submit Changes</button><p>
</form>

