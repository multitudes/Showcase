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


////////////////////////////////////////// CONTROLLER ///////////////////////

// this first part with POST will be ignored when I first land on the page because I have a GET
if ( isset($_POST['delete']) && isset($_POST['id']) ) {
    $sql = "DELETE FROM users 
    WHERE id = :zip";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: user_admin.php' ) ;
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
//check admin logged in
check_admin_session();
// flash display error or success from the previous page     
flash_message();

// I pass the title of the page to the template as variable
$title = "Delete User";

///// header section finished, I can use include
include("../templ/head.tpl");  


////////////////////////////////////////// VIEW ///////////////////////

?>
<br>
<p>Confirm: Deleting <?= htmlentities($row['username']) ?></p>

<form method="post">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<p><button type="submit" class = "btn btn-primary" name ='delete' value='delete' >Delete</button></p>
<a href="user_admin.php">Cancel</a>
</form>

<!-- close the head.tpl -->
</div>
</body>
<?php
include("../templ/footer.tpl"); 
?>