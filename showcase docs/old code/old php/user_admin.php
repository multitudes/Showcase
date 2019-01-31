<?php
session_start();

//DB connection
require_once "db_init.php";

//check my sessions values
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
<title>Manage Users</title>
<?php include("../templ/head.tpl"); ?>

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
echo "<a href='add_user.php'>Add New User</a>";
?>
   
   
   
   
    <p><a href="admin.php">Back</a></p>
    </div>
</body>
</html>
<!--    http://localhost:8890/ShowcaseProject/php/edit.php?id=4
<iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/533446239&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>
    <br><br>
    <iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/525633687&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>
    <br><br><iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/534269169&color=%23ff5500&inverse=false&auto_play=false&show_user=false"></iframe>
    <br><br>
     <iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/534269169&color=%23ff5500&inverse=true&auto_play=false&show_user=true"></iframe>
    <iframe width="100%" height="20" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/525633687&color=%23ff5500&inverse=true&auto_play=false&show_user=true"></iframe> -->