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

// flash display error or success from the previous page     
flash_message();

// I pass the title of the page to the template as variable
$title = "User Admin";
    
//the include has to be put after the header()
include("../templ/head.tpl");
?>
<h1>Users administration</h1>
    <p>
        <b>Note:</b> Here you can as admin manage the users, retrieve accounts edit and delete them.
    </p>


<?php

    
// retrieve Users   
echo "<H3>List of Users</H3>";

  // mySQL query for the user details including hash and user ID
    $sql = "SELECT id, username, admin 
            FROM users u 
            INNER JOIN is_admin i ON u.id = i.id_users
            ORDER BY id";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute();
    //$row = $stmt->fetch(PDO::FETCH_ASSOC);
    
echo '<table class="table table-striped table-bordered"><thead>';
    echo '<tr>';
    echo '<th scope="col">id </th>';
    echo '<th scope="col">username </th>';
    echo '<th scope="col">is admin?</th>';
    echo '<th scope="col">action </th>';
    echo '</tr></thead>';
    echo '<tbody>';
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo(htmlentities($row['id']));
    echo("</td><td>");
    echo(htmlentities($row['username']));
    echo("</td><td>");
    echo(htmlentities($row['admin']));
    echo("</td><td>");
    echo('<a href="edit_user.php?id='.$row['id'].'">Edit</a> / ');
    echo('<a href="delete_user.php?id='.$row['id'].'">Delete</a> / ');
    echo('<a href="view_user.php?id='.$row['id'].'">View</a>');
    echo("</td></tr>\n");
}
    echo "</tbody></table>";

?>
     <form action="add_user.php">
      <p><button type="submit" class = "btn btn-primary" name ='submit' value='add' >Add New User</button></p>
      </form>
   
    <p><a href="playlists_admin.php">Back</a></p>
<!-- close the head.tpl -->
<?php
include("../templ/footer.tpl"); 
?>