<?php
require_once "db_init.php";
session_start();

////////////////////////////////////////// CONTROLLER ///////////////////////


// tracks are 
//    533446239
//    5256 3368 7
//    534269169

if ( isset($_POST['delete']) && isset($_POST['id']) ) {
    $sql = "DELETE FROM tracks WHERE id = :zip";
    $stmt = INIT::$pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: ../index.php' ) ;
    return;
}




if ( isset($_POST['track_name']) && isset($_POST['soundcloud_id'])
     && isset($_POST['format'])) {

//if ( isset($_POST['id']) && isset($_POST['email']){

    // Data validation soundcloud
    if((strlen($_POST['soundcloud_id']) < 9 )){
        $_SESSION['error'] = 'invalid soundcloud_id (min 9 digit)';
        header("Location: add_track.php");
        return;
        }
    if ( strlen($_POST['track_name']) < 1 ) {
        $_SESSION['error'] = 'Missing track_name';
        header("Location: add_track.php");
        return;
    }
    if ( strlen($_POST['format']) < 1 ) {
        $_SESSION['error'] = 'Missing format type';
        header("Location: add_track.php");
        return;
    }

try {   
    
    
    $sql = "INSERT INTO tracks (track_name, soundcloud_id)
              VALUES (:track_name, :soundcloud_id)";
    $stmt = Init::$pdo->prepare($sql);
    $stmt->execute(array(
        ':track_name' => $_POST['track_name'],
        ':soundcloud_id' => $_POST['soundcloud_id'])); 
    
    $sql = "INSERT INTO track_format (id_track, format)
            VALUES (:id_track, :format)";
    $stmt = Init::$pdo->prepare($sql);
    $stmt->execute(array(
            ':id_track' => Init::$pdo->lastInsertId(),
            ':format' => $_POST['format'])); 
    $_SESSION['success'] = 'Record Added';
    header( 'Location: ../index.php' ) ;
    return;
} catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
}   
}

// Guardian: Make sure that id is present
if ( ! isset($_GET['id']) ) {
  $_SESSION['error'] = "Missing user id";
  header('Location: ../index.php');
  return;
}
// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
    ////////////////////////////////////////// VIEW ///////////////////////
?>

<!-- view-->
<h3>Add A New Track</h3>
<form method="post">

<p>Track Name:
<input type="text" name="track_name" ></p>

<p>SoundCloud ID:
<input type="text" name="soundcloud_id"></p>
<p>Format (mp3, WAW):
<input type="text" name="format"></p>
   
<p><input type="submit" value="Add New"/>
<a href="../index.php">Cancel</a></p>
</form>
