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

// copy the post received into sessions

// check if my playlist session is still active
if (!isset($_SESSION['playlist_name'])){ 
    $_SESSION['error']="missing playlist name in session";
    header("Location: add_playlist.php");
    return;
  }

// when I got the magic link I close the session and save the link 
  if ( isset($_POST['close'])) {
      try{
                $sql= "INSERT INTO user_details (id_users,comments) 
                    VALUES(:id_users, :comments)";
                $stmt = myPDO::$pdo->prepare($sql);
                $stmt->execute(array(
                ':comments' => $_POST['magic'],
                ':id_users' => $_SESSION['id_users']));  

              $_SESSION['success'] = 'Magic Link saved in user comments';
              header("Location: playlists_admin.php");
        return;
            }catch (Exception $ex ) { 
                echo("Internal error, please contact support");
                error_log("error4.php, SQL error=".$ex->getMessage());
                return;
                } 
                
      
  }

// this in case of cancel. Cancel goes back to playlist admin menu 
  if ( isset($_POST['cancel'])) {
        $_SESSION['success'] = 'back to editing playlist!';
        header("Location: add_playlist_continue.php");
        return;
  }
   
// Check for POST Magic Link
  if ( isset($_POST['Magic_link'])) {
        if ( !isset($_SESSION['magic'])) {
           $_SESSION['magic']='requested'; 
        }
  }
// debug
//echo $_SESSION['magic'];
//echo $_SESSION['id_users'];
         
// check if the user is logged in as admin
check_admin_session();
// flash display error or success from the previous page     
flash_message();

//debug
//echo " first track in session playlist: ".$_SESSION['playlist'][0]."<p> vardump:"."<br>";
//var_dump($_SESSION['playlist']);

// I pass the title of the page to the template as variable
$title = "Preview Playlist";

///// header section finished, I can use include
include("../templ/head.tpl");  
?>

        <p>
      <form method="post" action="preview_playlist.php" id="preview_playlist_form">
<!-- Create a magic link to send to a customer -->
      
      <p><button type="submit" class = "btn btn-primary" name ='Magic_link' >Create a magic Link</button></p>
<!-- cancel and go back to previous screen -->      
      <div id="close">
      <input type="submit" id="close" name="cancel" value="Cancel"/> </div>
      </form>

<!--     here comes the magic link when requested -->
    <div id='magic_link'></div><br>
     <!-- preview the playlist -->      
      <h2 id='customized_page_header'></h2>
    
      <p id='customized_text'></p>   
      <!--  here I put the tracks.. -->  
      
      tracks:
      <div id="playlist_content">
      </div>      



<script type="text/javascript">
    
// prepopulating the page with JQuery    
function preview_playlist() {
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
      // update the track playlist on the site
      $('#playlist_content').empty();
      for (var i = 0; i < data.length; i++) {
        found = true;
        row = data[i];  
        if(row.track_name) { 
        window.console && console.log('Row: '+i+' '+row.track_name);
        $('#playlist_content').append(
            '<p> '+row.track_name+' Soundcloud id:  '+row.soundcloud_id );
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
 
function show_magic_link() {
  window.console && console.log('Requesting JSON'); 
  $.getJSON('create_magic.php', function(data){
      var row = data;
      window.console && console.log('JSON Received'); 
      window.console && console.log(data);
      $('#magic_link').empty();   
      magic="The magic link is:  localhost:8890/php/authenticate.php\?id="+data.id+'&token='+data.hash;
      $('#magic_link').append(magic);
      $('#close').html(
          '<p><input type="hidden" name="magic" value="'+magic+'">'+
          '<input type="submit" id="close" name="close" value="Close"/>'); 
        });
      }
   
// Make sure JSON requests are not cached
$(document).ready(function() {
  $.ajaxSetup({ cache: false });     
  
  // This will update my divs with the current values of title text and tracks  
  preview_playlist();
  show_magic_link();
});    
</script>

<!-- close the head.tpl -->
<?php
include("../templ/footer.tpl"); 
?>