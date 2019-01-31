<?php

require_once "db_init.php";
session_start();

////////////////////////////////////////// CONTROLLER ///////////////////////


// tracks are 
//    533446239
//    5256 3368 7
//    534269169


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
    
    // insert what I receive via POST from the form below
    // first insert the trackname and soundcloud id in table tracks
    $sql = "INSERT INTO tracks (track_name, soundcloud_id)
              VALUES (:track_name, :soundcloud_id)";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(
        ':track_name' => $_POST['track_name'],
        ':soundcloud_id' => $_POST['soundcloud_id'])); 
    
    // then insert the format in table tracks_format
    $id_tracks = myPDO::$pdo->lastInsertId();
    $sql = "INSERT INTO track_format (id_tracks, format)
            VALUES (:id_tracks, :format)";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(
            ':id_tracks' => $id_tracks,
            ':format' => $_POST['format'])); 
    
    // for the tags I need to convert my POSt into an array first
    $tags = explode(",", $_POST['tags']);
    // how many elements in my array PHP
    print_r($tags);
    $i = count($tags);
    
    echo $i;
    foreach($tags as $l){
        //echo $l;
    $sql = "INSERT INTO tags (id_tracks, tags)
            VALUES (:id_tracks, :tags)";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(
            ':id_tracks' => $id_tracks,
            ':tags' => $l)); 
    
    }
    
    $_SESSION['success'] = 'Record Added';
    header( 'Location: ../index.php' ) ;
    return;
} catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
}   
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
<p>Tags (separated by comma ex: emotional, dark.. etc):
<input type="text" name="tags"></p>   
<p><input type="submit" value="Add New"/>
<a href="../index.php">Cancel</a></p>
</form>
