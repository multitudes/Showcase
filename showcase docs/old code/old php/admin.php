<?php //do not put any HTML above this line
session_start();

//DB connection
require_once "db_init.php";


if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);}
if ( isset($_SESSION['username']) && isset($_SESSION['id'])){
    echo '<p style="color:red">'."&nbsp".'username: '.$_SESSION['username']."</p>\n";
    echo '<p style="color:red">'."&nbsp".'ID: '.$_SESSION['id']."</p>\n"; 
    echo '<p style="color:red">'."&nbsp".'Is Admin : '.$_SESSION['admin']."</p>\n"; 
    if ($_SESSION['admin'] !== 'yes'){
        header('Location: index.php');
        return;
    }
}else{
    header('Location: login.php');
    return;
}


?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<?php include("../templ/head.tpl"); ?>

<h1>Create Playlists</h1>
    <p>
        <b>Note:</b> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui dicta minus molestiae vel beatae natus eveniet ratione temporibus aperiam harum alias officiis assumenda officia quibusdam deleniti eos cupiditate dolore doloribus! Lorem ipsum dolor.
    </p>


<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
    
// manage add and delete users
echo "";

?>


<a href='user_admin.php'>Manage Users  </a><br>
<a href="track_admin.php">Manage Tracks</a>
    <br><br>
    <p><a href="logout.php">Log Out</a></p>
    </div>
</body>


<!--    http://localhost:8890/ShowcaseProject/php/edit.php?id=4
<iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/533446239&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>
    <br><br>
    <iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/525633687&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>
    <br><br><iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/534269169&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>
    <br><br>
     <iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/534269169&color=%23ff5500&inverse=true&auto_play=false&show_user=true"></iframe>
    <iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/525633687&color=%23ff5500&inverse=true&auto_play=false&show_user=true"></iframe> -->