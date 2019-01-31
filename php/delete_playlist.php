<?php
// utility functions
require_once "service_util.php";

//start session and include necessary files

// start the session if not already started
session_start();

// load PDO class for DB connection and the others too 
require_once "../class/autoloader.php";

// init DB
new myPDO();

////////////////////////////////////////// CONTROLLER ///////////////////////

// this first part with POST will be ignored when I first land on the page because I have a GET first. When the user reply to the question "do you really want to delete?" then I will get a POST and will action the code below
if ( isset($_POST['delete']) && isset($_POST['id']) ) {
    try{
        //first get the user id from the playlist id
        $sql = "SELECT id_users FROM users_playlist
        WHERE id_playlists = :zip";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(':zip' => $_POST['id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        //then delete the user first
        $id_user=$row['id_users'];
        $sql = "DELETE FROM users 
        WHERE id = :zip";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(':zip' => $id_user));
        
        // then delete the playlist
        $sql = "DELETE FROM playlists 
        WHERE id = :zip";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(':zip' => $_POST['id']));
        $_SESSION['success'] = 'Record deleted';
        header( 'Location: playlists_admin.php' ) ;
        return;
    } catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
    }       
}
// I retrieve the playlist from the GET id which will be put below in the form
if ( isset($_GET['id'])){    
    $stmt = myPDO::$pdo->prepare("SELECT playlist_name,id FROM playlists WHERE id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for playlist id';
        header( 'Location: playlists_admin.php' ) ;
        return;
    } 
}else{
    $_SESSION['error'] = 'missing playlist id in URL';
    header( 'Location: playlists_admin.php' ) ;
    return;
}

//check admin logged in
check_admin_session();

// flash display error or success from the previous page     
flash_message();

// I pass the title of the page to the template as variable
$title = "Delete Playlist";

///// header section finished, I can use include
include("../templ/head.tpl");  


////////////////////////////////////////// VIEW ///////////////////////

?>

<p>Confirm Deleting <?= htmlentities($row['playlist_name']) ?>?</p>

<form method="post">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<p><button type="submit" class = "btn btn-primary" name ='delete' value='delete' >Delete</button></p>
<a href="playlists_admin.php">Cancel</a>
</form>
<?php
include("../templ/footer.tpl"); 
?>