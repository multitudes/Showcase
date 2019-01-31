<?php  //do not put any HTML above this line
///////////////// user or admin login with magic link ///////////////////////////

// include utility functions
require_once "service_util.php";

// start the session 
session_start();

// load PDO class for DB connection and the others too 
require_once "../class/autoloader.php";
    
// init DB
new myPDO();

//The only truly secure way of preventing these session IDs from being discovered is to implement Transport Layer Security (TLS, the more secure successor to the Secure Sockets Layer, or SSL) and run HTTPS instead of HTTP web pages

// Guardian: Make sure that id
// I retrieve the playlist from the GET playlist id 
if ( isset($_GET['id'])){  
    unset($_SESSION['id']);
    //first get the user id from the playlist id
    $sql = "SELECT id_users FROM users_playlist
    WHERE id_playlists = :zip";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_GET['id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC); 
    $_SESSION['id']= $row['id_users'];
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for playlist id';
        header( 'Location: playlists_admin.php' ) ;
        return;
    } 
    // next check if there is a magic link in comments
    $stmt = myPDO::$pdo->prepare("SELECT comments FROM user_details WHERE id_users = :xyz");
    $stmt->execute(array(":xyz" => $_SESSION['id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['user_comment'] = '';
    }else{
        $_SESSION['user_comment'] = $row['comments'];
    } 
    
}else{
    $_SESSION['error'] = 'missing playlist id in URL';
    header( 'Location: playlists_admin.php' ) ;
    return;
}

/* if ( isset($_GET['id'])){
    //first get the user id from the playlist id
    
     I have this already in Json    $sql = "SELECT up.id_users, ud.comments , p.playlist_name, p.customized_page_header, p.customized_text, t.track_name, t.soundcloud_id
        FROM users_playlist up
        LEFT JOIN user_details ud
        ON up.id_users = ud.id_users
        LEFT JOIN playlists p
        ON p.id = up.id_playlists
        LEFT JOIN tracks_in_playlist tp
        ON p.id = tp.id_playlist
        LEFT JOIN tracks t
        ON tp.id_tracks = t.id
        WHERE id_playlists = :zip"; 
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(':zip' => $_GET['id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 
         
    if ($row == false){ 
        $_SESSION['error']="no data found";
        header("Location: playlists_admin.php"); 
        return;   
    }
}
*/

flash_message();

// check if the user is logged in as admin
check_admin_session();

// I pass the title of the page to the template as variable
$title = "Edit Playlist";

///// header section finished, I can use include for the template
include("../templ/head.tpl");  

?>
<h1> Still work in progress!!</h1>
 <!-- preview the playlist -->      
      <br><p><?=$_SESSION['user_comment']?></p> <br>
      <p><a href="playlists_admin.php">Return</a></p>
      <h1 id='customized_page_header'></h1>
    
      <p id='customized_text'><p>  
      
      <!--  here I put the tracks.. it is empty at the beginning and JQuery will populate it -->  
      <div id="playlist_content">
      </div>      

<script type="text/javascript">
    
function preview_playlist() {
  window.console && console.log('Requesting JSON'); 
  $.getJSON('user_session_playlist.php', function(data){
      var row = data[0].customized_page_header;
      window.console && console.log('JSON Received user id is: '+data[0].user_id); 
      //window.console && console.log(data[0].user_id);
      //window.console && console.log(data[0].customized_page_header);
      $('#customized_page_header').empty();      
      $('#customized_page_header').append(row);
      // update the text
      var row = data[0].customized_text;
      //window.console && console.log('JSON Received'); 
      //window.console && console.log(data[0].customized_text);
      $('#customized_text').empty();      
      $('#customized_text').append(row);
      found = false;
      // update the playlist in player
      $('#playlist_content').empty();
      for (var i = 0; i < data.length; i++) {
        found = true;
        row = data[i];  
        if(row.track_name) { 
            window.console && console.log('song: '+i+' name: '+row.track_name+' track id: '+row.id+' soundcloud id: '+row.soundcloud_id+' playlist id: '+row.playlist_id);
            //$('#playlist_content').append(
            //'<p> '+row.track_name+' Soundcloud id:  '+row.soundcloud_id );
            
            //here I am enbedding the player and the soundcloud id
            player='<iframe width="60%" height="20" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/';
            player_url_end = '&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>';
            $('#playlist_content').append(
            player+row.soundcloud_id+player_url_end );
            
        //'<iframe width="80%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'+row.soundcloud_id+'&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>');
        
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
  preview_playlist();
});    
</script>
</body>

<?php
include("../templ/footer.tpl"); 

?>

