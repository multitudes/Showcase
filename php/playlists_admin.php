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

//and check admin logged in
check_admin_session();

// I pass the title of the page to the template as variable
$title = "Playlists Admin";

// flash display error or success from the previous page     
flash_message();

//the include has to be put after the header()
include("../templ/head.tpl");
?>
<h1>Your Playlists:</h1>
    <p>
        <b>Note:</b> Here you can create new playlists, save them with a personalized text and create a magic link to send to your customers.
    </p>


<?php
// retrieve playlists

  // mySQL query for the playlists details 
    $sql = "SELECT id, playlist_name, customized_page_header
            FROM playlists
            ORDER BY id";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute();
/*
// mySQL query for the tracks details 
    $sql = "SELECT p.id, playlist_name, customized_page_header, track_name,                   soundcloud_id
            FROM playlists p 
            left JOIN tracks_in_playlist tip ON p.id = tip.id_playlist            left JOIN tracks t ON tip.id_tracks = t.id
            ORDER BY p.id";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute();
    
*/
echo '<table class="table table-striped table-bordered"><thead>';
    echo '<tr>';
    echo '<th scope="col">id </th>';
    echo '<th scope="col">playlist name </th>';
    echo '<th scope="col">actions </th>';
    //echo '<th scope="col">is admin?</th>';
    //echo '<th scope="col">action </th>';
    //echo '</tr></thead>';
    //echo '<tbody>';
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo(htmlentities($row['id']));
    echo("</td><td>");
    echo(htmlentities($row['playlist_name']));
    echo("</td><td>");
    //echo(htmlentities($row['admin']));
    //echo("</td><td>");
    echo('<a href="edit_playlist.php?id='.$row['id'].'">Edit</a> / ');
    echo('<a href="delete_playlist.php?id='.$row['id'].'">Delete</a> / ');
    echo('<a href="view_playlist.php?id='.$row['id'].'">View</a>');
    echo("</td></tr>\n");
}
    echo "</tbody></table>";
?>
  <form action="add_playlist.php">
   <p><button type="submit" class = "btn btn-primary" name ='Add_New_Playlist' >Add New Playlist</button></p>
   <p><a href="user_admin.php">User Admin</a></p>
    <p><a href="logout.php">Log Out</a></p>



<?php
include("../templ/footer.tpl");
?>