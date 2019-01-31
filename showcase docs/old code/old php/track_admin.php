<?php
session_start();

// this will load the classes in the class folder 
//include_once "class/autoloader.inc.php";
// check if need login or token login
// user or admin login
//connect to the database

//DB connection
require_once "db_init.php";
include("../templ/head.tpl");
?>
<h1>Users administration</h1>
    <p>
        <b>Note:</b> Here you can as admin manage the users, retrieve accounts edit and delete them.
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
    
// retrieve tracks   
echo "<H3>List of Tracks</H3>";    
echo('<table border="1">'."\n");
$stmt = myPDO::$pdo->query("SELECT id, track_name, soundcloud_id, format  
                           FROM tracks t JOIN track_format f 
                           ON t.id = f.id_tracks");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo(htmlentities($row['id']));
    echo("</td><td>");
    echo(htmlentities($row['track_name']));
    echo("</td><td>");
    echo(htmlentities($row['soundcloud_id']));
    echo("</td><td>");
    echo(htmlentities($row['format']));
    echo("</td><td>");
    echo('<a href="php/edit_track.php?id='.$row['id'].'">Edit</a> / ');
    echo('<a href="php/delete_track.php?id='.$row['id'].'">Delete</a>');
    echo("</td></tr>\n");
}    
    echo "</table>";
echo "<a href='add_track.php'>Add New Track</a>";
    
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    //echo $host.$uri;
?>
    <p><a href="../index.php">Back</a></p>
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