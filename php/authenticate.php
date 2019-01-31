<?php  //do not put any HTML above this line
///////////////// user or admin login with magic link ///////////////////////////

//putting a timeout of one day for the session, after that he will need to authenticate again
ini_set('session.gc_maxlifetime', 60 * 60 * 24);

// include utility functions
require_once "service_util.php";

// start the session 
session_start();

require_once "db_init.php";

// load PDO class for DB connection and the others too 
require_once "../class/autoloader.php";
    
// init DB
new myPDO();

// initialise DB if needed
//require_once "db_init.php";

//The only truly secure way of preventing these session IDs from being discovered is to implement Transport Layer Security (TLS, the more secure successor to the Secure Sockets Layer, or SSL) and run HTTPS instead of HTTP web pages

//https://sebastian-morawietz.com


// check for magic link passworless login
// I get the id and token from the link

// for debugging only this is the token the user gets per email
// localhost:8890/php/authenticate.php?id=1825&token=$2y$10$tXuxZbm9zekmDcFTzJ1JOekojTIFg07SwNtkWa4FBVEQL.3/79Va2
if ( isset($_POST['close'])) {
    // log the time and redirect
        $sql= "UPDATE log SET logged_out = NOW()
              WHERE id = :id";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
            ':id' => $_SESSION['id_log']));  
        header("Location: https://sebastian-morawietz.com");
        return;
        }



// Guardian: Make sure that user_id and token are present
if ( isset($_GET['id'])&&isset($_GET['token'])){
    echo"ok ";
    // log the previous user out if not happened yet
    unset($_SESSION['username']);
    unset($_SESSION['id']);
    unset($_SESSION['admin']);
    
    try{
        // get the hash from the database 
        $stmt = myPDO::$pdo->prepare("SELECT hash 
                                FROM hash h
                               WHERE id_users = :xyz");
        $stmt->execute(array(":xyz" => $_GET['id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "hash stored for user id ".$_GET['id']."<br>";  
        var_dump($row['hash']);

        echo  "<br>"."token in URL GET id ".$_GET['id']."<br>"; 
        var_dump($_GET['token']);
    }catch (Exception $ex ) { 
            echo("Internal error, please contact support");
            error_log("error4.php, SQL error=".$ex->getMessage());
            return;
            } 
    if ($row !== false){ 
        $token = $_GET['token'];
        $hash = $row['hash'];    
        // checking that the hash stored corresponds to the token
        // in production this would be a single use only sign in. 
        // then the user would need another magic link 
        if($token == $hash) {
            
            // if the token is correct then the session will be updated with the ID 
            $_SESSION['id']=$_GET['id'];
            $_SESSION['admin']='no';
            $_SESSION['success']="logged in!!!!!!!!";

            // now I will log the time of log in
            $sql= "INSERT INTO log (id_users, logged_in)
                   VALUES(:id_users, NOW())";
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute(array(
                ':id_users' => $_SESSION['id']));
            $_SESSION['id_log'] = myPDO::$pdo->lastInsertId();
            header("Location: authenticate.php"); 
            return;   
        }else{
         $_SESSION['error'] = "Invalid sign in: please request a new token";
         header("Location: start.php"); 
         return;
            }
    }else{
        $_SESSION['error'] = "Invalid sign in: not found";
             header("Location: start.php"); 
             return;
        }
}


flash_message();

// redirect if admin
if ($_SESSION['admin'] == 'yes'){
            $_SESSION['error']='admin login required';
            header('Location: login.php');
            return;
}

// for debugging display if user is admin, he should not be
// so I print this to console too..
if ( isset($_SESSION['id'])){
    //echo '<p style="color:red">username: '.$_SESSION['username']."</p>\n";
    echo '<p style="color:red">ID: '.$_SESSION['id']."</p>\n"; 
    echo '<p style="color:red">Is Admin : '.$_SESSION['admin']."</p>\n"; 
    }

// I pass the title of the page to the template as variable
$title = "Authenticate";

///// header section finished, I can use include for the template
include("../templ/head.tpl");  

?>

 <!-- preview the playlist -->      
      <h1 id='customized_page_header'></h1>
    
      <h5 id='customized_text'></h5>   
      <!--  here I put the tracks.. it is empty at the beginning and JQuery will populate it -->  
      <div id="playlist_content" style="padding: 20px; ">
      </div><br> 
      <form method="post" action = "authenticate.php">
      <p><button type="submit" class = "btn btn-primary" name ='close' value='close' >Close</button></p>           
      </form>            
<!-- 
<iframe width="80%" height="20"  frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/525633687&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>

<iframe width="80%" height="20" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/342002845&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=false"></iframe> -->



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
            player='<p><iframe width="80%" height="20" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/';
            player_url_end = '&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe></p>';
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

<!-- this is a tryout for a UX with background image
<style>
.content:before {
  content: "";
  position: fixed;
  top: -15%;
  left: -20%;
  right: 0;
  bottom: 0;
  z-index: -1;
  
  display: block;
  background-image: url("sebastian.jpg");
  background-size:cover;
  width: 120%;
  height: 120%;
  
  -webkit-filter: blur(5px);
  -moz-filter: blur(5px);
  -o-filter: blur(5px);
  -ms-filter: blur(5px);
  filter: blur(3px);
}

.content {
  overflow: auto;
  position: relative;
  margin: 15px;
    color: lightgray;
  /* background: rgba(255, 255, 255, 0.3);
  padding: 5px;
  box-shadow: 0 0 5px gray;  */
}
</style>
<div class="content "><p>strong overlay</p>
    </div> -->
