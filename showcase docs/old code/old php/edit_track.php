<?php
require_once "db_init.php";
session_start();

////////////////////////////////////////// CONTROLLER ///////////////////////


// tracks are 
//    533446239
//    5256 3368 7
//    534269169


if ( isset($_POST['track_name']) && isset($_POST['soundcloud_id'])
     && isset($_POST['format']) && isset($_POST['id'])) {

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
        if ( strlen($_POST['id']) < 1 ) {
        $_SESSION['error'] = 'Missing id ';
        header("Location: ../index.php");
        return;
    }

    try {       
    
    //echo($_POST['id']);
    $sql = "UPDATE tracks SET track_name = :track_name, 
            soundcloud_id = :soundcloud_id
            WHERE id = :id";
    $stmt = myPDO::$pdo->prepare($sql);
    //print_r( $stmt);
    $stmt->execute(array(
        ':track_name' => $_POST['track_name'],
        ':soundcloud_id' => $_POST['soundcloud_id'],
        ':id' => $_POST['id'])); 
    //echo ' '.$_POST['format'];
     
    $sql = "UPDATE track_format SET format = :format
            WHERE id_tracks = :id_tracks";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(
            ':id_tracks' => $_POST['id'],
            ':format' => $_POST['format'])); 
    
    $tags = explode(",", $_POST['tags_string']);
    // loop through the tags array and update
    //print_r($tags);
    //$l = $_POST['tags_string'];
    foreach($tags as $l){
        //echo $_POST['id'].' '.$l.' ';
        $sql = "INSERT IGNORE INTO tags (id_tracks, tags)
                VALUES (:id_tracks, :tags)";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
               ':id_tracks' => $_POST['id'],
               ':tags' => trim($l))); 
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


// Guardian: Make sure that id is present
//echo $_GET['id'];
// if I did not get the id in the browser I update the session and return to the index page    
if ( !isset($_GET['id'])){
  $_SESSION['error'] = "Missing id";
  header( 'Location: ../index.php' ) ;
  return;
}


$stmt = myPDO::$pdo->prepare("SELECT id, track_name, soundcloud_id, format 
                           FROM tracks t JOIN track_format f 
                           ON t.id = f.id_tracks
                           WHERE t.id = :id");
//print_r( $stmt);
$stmt->execute(array(":id"=> $_GET['id']));

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for track id';
    header( 'Location: ../index.php' ) ;
    return;
}
//assign the variables for the form fields
$id = $row['id'];
//echo $id;
$n = htmlentities($row['track_name']);
//echo $n;
$s = htmlentities($row['soundcloud_id']);
$f = htmlentities($row['format']);

// get the tags as array
//echo $_GET['id'];
$stmt = myPDO::$pdo->prepare("SELECT tags 
                             FROM tracks t JOIN tags ta 
                             ON t.id = ta.id_tracks
                             WHERE ta.id_tracks = :id");
//print_r( $stmt);
$stmt->execute(array(":id"=> $_GET['id']));
$tags = [];
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $tags[]= htmlentities($row['tags']);
}

//convert the array tags into string for the form fields
$tags_string =  implode(", ", $tags);
//echo $tags_string;

////////////////////////////////////////// VIEW ///////////////////////
?>

<!-- view-->
<h3>Edit Track</h3>
<form method="post">
<p>Track Name:
<input type="text" name="track_name" value = "<?= $n ?>"></p>
<p>SoundCloud ID:
<input type="text" name="soundcloud_id" value = "<?= $s ?>"></p>
<p>Format (mp3, WAW):
<input type="text" name="format" value = "<?= $f ?>"></p>
    <p>Tags : <?= $tags_string?>
<p>Add a Tag <input type="text" name="tags_string" value = ""></p>
<input type="hidden" name="id" value="<?= $id ?>">
<p><input type="submit" value="Update"/>
<a href="../index.php">Cancel</a></p>
</form>

