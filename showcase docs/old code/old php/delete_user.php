<?php
session_start();

//DB connection
require_once "db_init.php";
////////////////////////////////////////// CONTROLLER ///////////////////////

// this first part with POST will be ignored when I first land on the page because I have a GET
if ( isset($_POST['delete']) && isset($_POST['id']) ) {
    $sql = "DELETE FROM users WHERE id = :zip";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: ../index.php' ) ;
    return;
}

// Guardian: Make sure that id is present
if ( ! isset($_GET['id']) ) {
  $_SESSION['error'] = "Missing user id";
  header('Location: ../index.php');
  return;
}


$stmt = myPDO::$pdo->prepare("SELECT username,id FROM users WHERE id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user id';
    header( 'Location: ../index.php' ) ;
    return;
}
var_dump ($row); 

////////////////////////////////////////// VIEW ///////////////////////

?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<?php include("../templ/head.tpl"); ?>

<p>Confirm: Deleting <?= htmlentities($row['username']) ?></p>

<form method="post">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="user_admin.php">Cancel</a>
</form>
