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

// handle the session. 

// Need to load the tracks value for autocomplete (moved to jso_autocomplete)
/*if ( !isset ($_SESSION['autocomplete_tracks'])) $_SESSION['autocomplete_tracks'] = array(); */

// copy the post received into sessions

// check if my playlist session is still active
if (!isset($_SESSION['playlist_name'])){ 
    $_SESSION['error']="missing playlist name in session";
    header("Location: add_playlist.php");
    return;
  }

//clear up
if ( isset($_SESSION['magic'])){
 unset($_SESSION['magic']);
}

// redirect fore the preview mode
  if ( isset($_POST['preview']) ) {
        $_SESSION['success'] = 'preview mode!';
        header("Location: preview_playlist.php");
        return;
  }
// this in case of reset. Reset applies only to the tracks!
  if ( isset($_POST['reset']) ) {
    // I need to delete the entries in the tracks_in_playlist table too..
    try{  
        $sql = "DELETE FROM tracks_in_playlist 
        WHERE id_playlist = :zip";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(':zip' => $_SESSION['playlist_id']));  
        // then unset the session containing the tracks
        unset($_SESSION['playlist']); 
        // write to the session success reset done!
        $_SESSION['success'] = 'reset done!';
        header("Location: add_playlist_continue.php");
        return;
    }catch (Exception $ex ) { 
        echo("Internal error, please contact support");
        error_log("error4.php, SQL error=".$ex->getMessage());
        return;
    }  
  }
// this in case of cancel. Cancel deletes the current playlist entries and go back to playlist admin menu 
  if ( isset($_POST['cancel'])) {
    // delete the playlist from the system
    try{  
        $sql = "DELETE FROM playlists 
        WHERE playlist_name = :zip";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(':zip' => $_SESSION['playlist_name'])); 
        // then unset the session containing the tracks!!
        unset($_SESSION['playlist']);
        unset($_SESSION['playlist_name']);
        unset($_SESSION['playlist_id']);
        $_SESSION['success'] = 'cancelled!';
        header("Location: playlists_admin.php");
        return;
    }catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
    }
  }

  if ( isset($_POST['track']) ) {
      //$track = $_POST['track'];
      // To DO! need to convert from name to soundcloud id
      // create a session playlist if it doesnt yet exists
      if ( !isset ($_SESSION['playlist']) ) {
        $_SESSION['playlist'] = Array();
      }
      // check if the track does not exists in the array already
      if (! in_array($_POST['track'], $_SESSION['playlist'])){
        // then add it to the session  
        $_SESSION['playlist'][] = $_POST['track'];
        header("Location: add_playlist_continue.php");
        return;
      }
       
  }
   
// check if the user is logged in as admin
check_admin_session();

// flash display error or success from the previous page     
flash_message();

//debug
//echo " first track in session playlist: ".$_SESSION['playlist'][0]."<p> vardump:"."<br>";
//var_dump($_SESSION['playlist']);
// I pass the title of the page to the template as variable
$title = "Add Tracks to Playlist";

///// header section finished, I can use include
include("../templ/head.tpl");  
?>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
      
      

<!-- view -->      
      <h2 id='customized_page_header'></h2>
      <p id='customized_text'></p>      

        <p>
      <form method="post" action="add_playlist_continue.php" id="add_playlist_form">
<!--       Add the tracks! -->
       
      
      <input type="text" id="track" autofocus name="track" size="60" />
      <button type="submit" class = "btn btn-primary"  name ='submit' >Add Track</button>
<!-- Reset all Values -->
      <input type="submit" name="reset" value="Reset"/> 
      <input type="submit" name="cancel" value="Cancel"/> 
      <input type="submit" name="preview" value="Preview"/>       
     
      </form>
<!--  here I put the tracks.. -->  
     <!-- <p>tracks...</p>  -->   
      <div id="playlist_content">
         <!--  <img src="spinner.gif" alt="Loading..."/> -->
      </div>    

      <!-- <iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/441531300&color=%23ff5500&inverse=false&auto_play=false&show_user=true"></iframe> -->

    
<!-- close the head.tpl -->
<?php
include("../templ/footer.tpl"); 
?>

<script type="text/javascript">
    
function update_playlist() {
  window.console && console.log('Requesting JSON'); 
  $.getJSON('session_playlist.php', function(data){
      var row = data[0].customized_page_header;
      window.console && console.log('JSON Received'); 
      window.console && console.log(data[0].customized_page_header);
      $('#customized_page_header').empty();      
      $('#customized_page_header').append(row);
      // update the text
      var row = data[0].customized_text;
      window.console && console.log('JSON Received'); 
      window.console && console.log(data[0].customized_text);
      $('#customized_text').empty();      
      $('#customized_text').append(row);
      found = false;
      $('#playlist_content').empty();
      for (var i = 0; i < data.length; i++) {
        found = true;
        row = data[i];  
        if(row.track_name) { 
        window.console && console.log('Row: '+i+' '+row.track_name);
        $('#playlist_content').append('<p> '+row.track_name+' Soundcloud id:  '+row.soundcloud_id );
        }else{
            $('#playlist_content').append('No tracks selected' );
        }
      }
     if ( ! found ) {
        $("#playlist_content").append("<tr><td>No entries found</td></tr>\n");
        }       
  });
}
 
// Make sure JSON requests are not cached
$(document).ready(function() {
  $.ajaxSetup({ cache: false });     
  
  // This will update my divs with the current values of title text and tracks  
  update_playlist();

 
  
 // autocomplete for tracks
$( function() {
    window.console && console.log('Requesting JSON'); 
    $.getJSON('json_autocomplete.php', function(data){   
    window.console && console.log('JSON Received'); 
    window.console && console.log(data);    
    var songs = [];
    songs=data;    
    $( "#track" ).autocomplete({
      source: songs
    });
  } );
});    
})    
</script>
</body>
<!-- // autocomplete for tracks
 $( function() {
    var availableTracks = [
      "Eye Opener (for Choir & Piano)",
      "Nocturne No.2"
    ];
    $( "#track" ).autocomplete({
      source: availableTracks
    });   
  }); -->

<!-- <form method="post" id="add_user_form">
<p><input type="checkbox" name="admin" value="yes"></p>

<textarea rows="4" cols="50" name="comments" form="add_user_form" placeholder="Free Text for Comments.."></textarea>
<p><input type="submit" value="Preview"/>
<a href="playlists_admin.php">Cancel</a></p>
</form> -->
 <!--  
    <iframe width="80%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/525633687&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>
    <iframe width="80%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/534269169&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>        
   -->