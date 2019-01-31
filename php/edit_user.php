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

// check if I got some POST data. I need to receive one ID and one username at least
if ( isset($_POST['username']) && isset($_POST['id']) ) {
    
    // Data validation on POST. 
    if ( strlen($_POST['username']) < 1) {
        $_SESSION['error'] = "Missing username";
        header("Location: edit_user.php?id=".$_POST['id']);  //if something is missing I send a GET again with user ID
        return;
    }
    
    //check if password has been entered
    if ( ! empty($_POST['password'])|| ! empty($_POST['password_repeat'])) {
   
        // if it has been entered then data validation passwords
        if((strlen($_POST['password']) < 1 )||strlen($_POST['password_repeat']) < 1 ){
            $_SESSION['error'] = 'password missing';
            header("Location: edit_user.php?id=".$_POST['id']);  //if something is missing I send a GET again with user ID
            return;
        }
        
        // check if passwords  match
        if ($_POST['password']===$_POST['password_repeat'] ) {
               $password = $_POST['password'];
          }else{
             $_SESSION['error'] = 'Passwords do not match';
                $password = '';
                header("Location: edit_user.php?id=".$_POST['id']);  //if something is missing I send a GET again with user ID
                return;
        }

    }else{
        $password = ''; //no password to change
    }


try {
    // update new data from POST in the tables. start with the user details
    
    // update username
    $username = $_POST['username'];
    $sql= "UPDATE users 
           SET  username = :username
           WHERE id = :id_users";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(
            ':id_users' => $_POST['id'],
            ':username' => $_POST['username'])); 
    
    
    $first_name='';
    $last_name='';
    $email='';
    $comments='';
    if ( isset($_POST['first_name'])){
        $first_name=$_POST['first_name'];
    }
    if ( isset($_POST['last_name'])){
        $last_name=$_POST['last_name'];
    } 
    if ( isset($_POST['email'])){
        $email=$_POST['email'];
    }
    if ( isset($_POST['comments'])){
        $comments=$_POST['comments'];
    }
        
    $sql= "UPDATE user_details 
           SET  first_name = :first_name , 
                last_name = :last_name, email = :email, comments = :comments
                WHERE id_users = :id_users";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
            ':id_users' => $_POST['id'],
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':comments' => $comments));    
    
    // if passwords have been posted I update the generated password hash in the hash table with the 
    // user ID and update the random generated 8 byte salt string
    if (strlen($password)>0){
        $hash = password_hash($password,PASSWORD_DEFAULT);
        
        // update the hash table 
        $sql= "UPDATE hash 
           SET  hash = :hash WHERE id_users = :id_users";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
            ':id_users' => $_POST['id'],
            ':hash' => $hash));
    
        // update the salt table with the last generated id 
        // the salt is actually fake because the function password_hash() creates a hash with salt already included. it is a honey pot in case somebody gets the tables from the database will think there is a hash.. 
        $length = 8;
        $salt = bin2hex(random_bytes($length));  //the value is 16 chars because 8 bytes in hex 16 chars
        $sql= "UPDATE salt 
            SET  salt = :salt WHERE id_users = :id_users";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
            ':id_users' => $_POST['id'],
            ':salt' => $salt));
    }
    
    if (!isset($_POST['admin']))
        {
        $admin = "no";
        }else{
        $admin = "yes";
        }  
    $sql= "UPDATE is_admin 
         SET  admin = :admin WHERE id_users = :id_users";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(
        ':id_users' => $_POST['id'],
        ':admin' => $admin));
    
    $_SESSION['success'] = 'Updated!';
    header( 'Location: user_admin.php' ) ;
    return;
} catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
    }   
}


// Make sure that the user id is on the get request to view delete or edit users
check_get_userid();
    
// from the id I get all users details to prepopulate the form        
$stmt = myPDO::$pdo->prepare("SELECT u.id, username, admin, first_name, last_name,               email, comments, playlist_name 
            FROM users u 
            LEFT JOIN is_admin i ON u.id = i.id_users
            LEFT JOIN users_playlist up ON u.id = up.id_users
            LEFT JOIN playlists p ON p.id = up.id_playlists
            LEFT JOIN user_details d ON u.id = d.id_users
            WHERE u.id = :xyz");

$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user id';
    header( 'Location: admin.php' ) ;
    return;
}

//check admin logged in
check_admin_session();

// flash display error or success from the previous page     
flash_message();

// prepare the data to pre fill the form below
$username = htmlentities($row['username']);
//echo "username : ".$username;
$admin = htmlentities($row['admin']);

// on the form this will be used on the chechbox
if ($admin=='yes'){
    $checked='checked';
}else{
    $checked='';
}
// this was to debug the checkbox!
//echo " is admin : ".$admin.' '.$checked;
// sanitize my databank output
$email = htmlentities($row['email']);
$first_name = htmlentities($row['first_name']);
$last_name = htmlentities($row['last_name']);
$comments = htmlentities($row['comments']);
$playlist_name = htmlentities($row['playlist_name']);

// I pass the title of the page to the template as variable
$title = "Edit User";
    
//the include has to be put after the header()
include("../templ/head.tpl");
?>

<!-- view the form prepopulated with the values for the user to edit and submit or cancel and back to the manage users section-->

<h3>Edit User</h3>
<form method="post" id="add_user_form"> <div class="form-group">

<p><label for="admin" >Is an Admin: </label>
    <input type="checkbox" name="admin" value="yes" <?= $checked ?> ></p></div>

<div class="form-group"><p><label >UserName: </label>
<input type="text" name="username" value="<?= $username ?>"></p>
</div>
<div class="form-group">
<p>Change Password:
<input type="password" name="password">
</div>  
<div class="form-group">Repeat Password:
<input type="password" name="password_repeat">
 </div>   
<p>These are optional:
<p>First Name:
<input type="text" name="first_name" value="<?= $first_name ?>"></p>
    <p>Last Name:
<input type="text" name="last_name" value="<?= $last_name ?>"></p>
<p>Email:
<input type="email" class="form-control" name="email" size = "50" value="<?= $email ?>"></p>
<p>Playlist:
<input type="text" name="playlists" value="<?= $playlist_name ?>"></p>

<label for="comment">Comments : <br></label><p>
<textarea rows="4" cols="50" name="comments" form="add_user_form" value = "<?= $comments ?>"><?= $comments ?></textarea>
<p><input type="hidden" name="id" value="<?= $row['id'] ?>">
<button type="submit" class = "btn btn-primary" >Submit Changes</button><p>
<a href="user_admin.php">Cancel</a></p>
</div>
</form>

<?php
include("../templ/footer.tpl"); 
?>

