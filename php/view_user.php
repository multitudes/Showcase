<?php

// include utility functions
require_once "service_util.php";

//start session and include necessary files

// start the session if not already started
session_start();

// load PDO class for DB connection and the others too 
require_once "../class/autoloader.php";

// init DB
new myPDO();


////////////////////////////////////////// VIEW ///////////////////////


// Make sure that the user id is on the get request to view delete or edit users
check_get_userid();

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
// I pass the title of the page to the template as variable
$title = "View User";

///// header section finished, I can use include
include("../templ/head.tpl");  

//check admin logged in
check_admin_session();

// flash display error or success from the previous page     
flash_message();


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
    echo "<br><H3>User Details</H3><br>";
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

$title = "View User";
include("../templ/head.tpl"); ?>

</div>
</form>
<?php
include("../templ/footer.tpl"); 

?>

