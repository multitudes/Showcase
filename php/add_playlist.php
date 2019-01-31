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
 
// clear previous sessions just in case
unset($_SESSION['playlist_name']);
unset($_SESSION['playlist_id']);
unset($_SESSION['id_users']);

// this first part with POST will be ignored when I first land on the page
// handle the session. copy the post received into sessions

// if user wants to cancel
 if ( isset($_POST['cancel'])) {
        $_SESSION['success'] = 'cancelled!';
        header("Location: playlists_admin.php");
        return;
 }

// playlist name is the only mandatory detail
if ( isset($_POST['playlist_name'] )) {
    // Data validation on POST. 
    if ( strlen($_POST['playlist_name']) < 1) {
        $_SESSION['error'] = "Missing playlist name";
        header("Location: add_playlist.php");
        return;
    }else{
        //check if a playlist with that name already exists 
        // if it exists return a flash message that the playlist exists
        $stmt = myPDO::$pdo->prepare("SELECT u.id 
            FROM playlists u
            WHERE u.playlist_name = :xyz");
        $stmt->execute(array(":xyz" => $_POST['playlist_name']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // If I found data then the row exists
            if ( $row !== false ) {
                $_SESSION['error'] = 'playlist name already exists';
                header("Location: add_playlist.php") ;
                return;
            }else{
            try{
                $customized_page_header = $_POST['customized_page_header'];
                $customized_text = $_POST['customized_text'];
                $sql= "INSERT INTO playlists (playlist_name, customized_page_header , customized_text) VALUES(:playlist_name, :customized_page_header , :customized_text)";
                $stmt = myPDO::$pdo->prepare($sql);
                $stmt->execute(array(
                ':playlist_name' => $_POST['playlist_name'],
                ':customized_page_header' => $customized_page_header,
                ':customized_text' => $customized_text));  
                $_SESSION['playlist_id']=myPDO::$pdo->lastInsertId();
                //success data inserted    
                $_SESSION['success'] = 'Record Added';
                // I copy the playlist name to the session
                $_SESSION['playlist_name'] = $_POST['playlist_name'];
                header( 'Location: add_playlist_continue.php' ) ;
                return;  
            }catch (Exception $ex ) { 
                echo("Internal error, please contact support");
                error_log("error4.php, SQL error=".$ex->getMessage());
                return;
                } 
              
            }
        }         
}


// flash display error or success from the previous page     
flash_message();

// check if the user is logged in as admin
check_admin_session();

// I pass the title of the page to the template as variable
$title = "Add Playlist";

///// header section finished, I can use include
include("../templ/head.tpl");  
?>
<?php 

?>     
<h1>Add A New Playlist </h1> <br>
     <h4>Add Name and text. </h4>
      <form method="post" action="add_playlist.php" id="add_playlist_form">
      <p>
      <input type="text" autofocus name="playlist_name" size="60" placeholder ="Enter Playlist name - Mandatory">
      <!-- <input type="submit" value="Add Name" ><br>  -->
      </p>
      <p>
      <input type="text" name="customized_page_header" size="60" placeholder ="Enter Playlist Greeting to User (Header) ">
      <!-- <input type="submit" value="Add Header" ><br>  -->
      </p>
      <p>
      <textarea rows="3" cols="60" id='customized_text' name="customized_text" form="add_playlist_form" value = "" placeholder ="Enter text or description (will be displayed to user)"></textarea>  <br>
      <p><button type="submit" class = "btn btn-primary" name ='submit' value='add' >Add New and Continue</button></p>
      <input type="submit" name="cancel" value="Cancel"/>  
      
      
<!-- close the head.tpl -->
<?php
include("../templ/footer.tpl"); 
?>