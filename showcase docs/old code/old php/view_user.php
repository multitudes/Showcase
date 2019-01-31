<?php

session_start();
//DB connection
require_once "db_init.php";

    

if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);}
if ( isset($_SESSION['username']) && isset($_SESSION['id'])){
    echo '<p style="color:red">'."&nbsp".'username: '.$_SESSION['username']."</p>\n";
    echo '<p style="color:red">'."&nbsp".'ID: '.$_SESSION['id']."</p>\n"; 
    echo '<p style="color:red">'."&nbsp".'Is Admin : '.$_SESSION['admin']."</p>\n"; 
    if ($_SESSION['admin'] !== 'yes'){
        header('Location: index.php');
        return;
    }
}else{
    header('Location: login.php');
    return;
}




////////////////////////////////////////// VIEW ///////////////////////

// Guardian: Make sure that id is present
$_GET['id'];
    
if ( !isset($_GET['id'])){
  $_SESSION['error'] = "Missing id";
  header( 'Location: user_admin.php' ) ;
  return;
}



$stmt = myPDO::$pdo->prepare("SELECT username, admin, first_name, last_name, email, comments, playlist_name 
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
    header( 'Location: admin.php' ) ;
    return;
}

///// header section finished, I can use include
include("../templ/head.tpl");  
// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}


$username = htmlentities($row['username']);
//echo "username : ".$username;
$admin = htmlentities($row['admin']);
//echo " is admin : ".$admin;
if ($admin=='yes'){
    $checked='checked';
}else{
    $checked='';
}

$email = htmlentities($row['email']);
$first_name = htmlentities($row['first_name']);
$last_name = htmlentities($row['last_name']);
$comments = htmlentities($row['comments']);

$playlist_name = htmlentities($row['playlist_name']);
if ($playlist_name==NULL){
    $playlist_name='none';
}
$id = $_GET['id'];
    echo '<table class="table table-striped table-bordered"><thead>';
    echo '<tr>';
    echo '<th scope="col">User </th>';
    echo '<th scope="col">Admin </th>';
    echo '<th scope="col">First Name </th>';    
    echo '<th scope="col">Last Name </th>';
    echo '<th scope="col">Email</th>';
    echo '<th scope="col">Playlist</th>';
    echo '</tr></thead><tbody>';

    echo "<tr><td>";
    echo $username;
    echo("</td><td>");
    echo $admin;
    echo("</td><td>");
    echo $first_name;
    echo("</td><td>");
    echo $last_name;
    echo("</td><td>");
    echo $email;
    echo("</td><td>");
    echo $playlist_name;
    echo("</td>");
echo "</tbody></table>";

echo '<p><table class="table table-striped table-bordered"><thead>';
    echo '<tr>';
    echo '<th scope="col">Comments </th>';
    
    echo '</tr></thead><tbody>';
    echo "<tr><td>";
    echo $comments;
    echo("</td></tr>\n");

    echo "</tbody></table>";
echo "<a href='user_admin.php'>Back</a>";

/*
echo '<div class="panel panel-default">';
echo '<div class="panel-heading">User: '.$username.'</div>';
echo '<div class="panel-body" style="color:red">Admin: '.$admin.'</div>';
echo '<div class="panel-body">First Name:  '.$first_name.'</div>';
echo '<div class="panel-body">Last Name:  '.$last_name.'</div>';
echo '<div class="panel-body">'.$email.'</div>';
echo '<div class="panel-body">'.$playlist_name.'</div>';
echo '<div class="panel-body">'.$comments.'</div>';
*/

?>
<!DOCTYPE html>
<html>
<head>
<title>View User</title>
<?php include("../templ/head.tpl"); ?>

</div>
</form>

