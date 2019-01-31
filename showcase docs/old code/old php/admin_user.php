<?php
//admin_user.php
// on this site the admin can create retrieve edit and delete users

// not actual as of now

require_once "pdo.php";
// example of error handling
try {
    $stmt = $pdo->prepare("SELECT * FROM users where id = :id");
    $stmt->execute(array(":id" => $_GET['id']));
} catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
}


if ( isset($_POST['new_name']) && isset($_POST['new_email']) 
     && isset($_POST['new_password'])) {
    $sql = "INSERT INTO users (name, email, password) 
              VALUES (:name, :email, :password)";
    echo("<pre>\n".$sql."\n</pre>\n");
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':name' => $_POST['new_name'],
            ':email' => $_POST['new_email'],
            ':password' => $_POST['new_password']));
    }catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
    }
}

// check if there is a POST with del_id
if ( isset($_POST['del_id']) ) {
    $sql = "DELETE FROM users WHERE id = :zip";
    echo("<pre>\n".$sql."\n</pre>\n");
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['del_id']));
}


$stmt = $pdo->query("SELECT id, name, email, password FROM users");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo($row['name']);
    echo("</td><td>");
    echo($row['email']);
    echo("</td><td>");
    echo($row['password']);
    echo("</td><td>");
    echo('<form method="post" action="admin_user.php"><input type="hidden" ');
    echo('name="del_id" value="'.$row['id'].'">'."\n");
    echo('<input type="submit" value="Del" name="delete">');
    echo("\n</form>\n");
    echo("</td></tr>\n");
  
}
?>

<html>
<head><body><table border="1">

// small form to delete a user    
<p>Delete A User</p>
<form method="post">
<p>ID to Delete:
<input type="text" name="del_id"></p>
<p><input type="submit" value="Delete"/></p>
</form>
    
</table>
<p>Add A New User</p>
<form method="post" action="">
<p>Name:
<input type="text" name="new_name" size="40"></p>
<p>Email:
<input type="text" name="new_email"></p>
<p>Password:
<input type="password" name="new_password"></p>
<p><input type="submit" value="Add New"></p>
</form>
</body>
</head>