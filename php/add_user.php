<?php
// include utility functions
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

// check if I got POST data
if ( isset($_POST['username']) && isset($_POST['password'])
     && isset($_POST['password_repeat'])) {
    // isset doesnt check if the POST is empty..
    if ( strlen($_POST['username']) < 1 ) {
        $_SESSION['error'] = 'Missing data';
        header("Location: add_user.php");
        return;
    }
    //check if at least both passwords have been entered
    if ( !empty($_POST['password']) && (!empty($_POST['password_repeat']))) {

        // if it has been entered then data validation passwords
        if ($_POST['password']!==$_POST['password_repeat'] ) {
                $_SESSION['error'] = 'Passwords do not match';
                header("Location: add_user.php");
                return;
            }
    }else{
            $_SESSION['error'] = 'missing password';
            header("Location: add_user.php");
            return;
        }

try {
     
    // insert new username from POST in the users table. ID is auto increment
    $sql = "INSERT INTO users (username)
            VALUES (:username)";
    $stmt = myPDO::$pdo->prepare($sql);
    $stmt->execute(array(':username' => $_POST['username']));
    
    // insert the generate password hash in the hash table with the previous automatically generated
    // user ID and insert the random generated 8 byte salt string
    //$salt= openssl_random_pseudo_bytes(8);
    //echo "salt: ".$salt;
    $pass = $_POST['password'];
    $hash = password_hash($pass,PASSWORD_DEFAULT);
    
    // get the last generated id first
    $id_users = myPDO::$pdo->lastInsertId();
    
    // insert into the hash table with the last generated id 
    $sql= "INSERT INTO hash (id_users,hash)
               VALUES(:id_users, :hash)";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
            ':id_users' => $id_users,
            ':hash' => $hash));
    
    // insert into the salt table with the last generated id 
    $length = 8; // the salt is actually fake because the function password_hash() creates a hash with salt already included. it is a honey pot in case somebody gets the tables from the database will think there is a hash.. 
    $salt = bin2hex(random_bytes($length));  //the value is 16 chars because 8 bytes in hex 16 chars
    $sql= "INSERT INTO salt (id_users, salt)
               VALUES(:id_users, :salt)";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
            ':id_users' => $id_users,
            ':salt' => $salt));
    if (! isset($_POST['admin'])){
        $admin = 'no';
    }else{
        $admin = $_POST['admin'];}
        $sql= "INSERT INTO is_admin (id_users,admin)
               VALUES(:id_users, :admin)";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
            ':id_users' => $id_users,
            ':admin' => $admin));    
        $email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $comments = $_POST['comments'];
    
    $sql= "INSERT INTO user_details (id_users, first_name , last_name, email, comments)
               VALUES(:id_users, :first_name , :last_name, :email, :comments)";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
            ':id_users' => $id_users,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':comments' => $comments));    
        $_SESSION['success'] = 'Record Added';
        header( 'Location: user_admin.php' ) ;
        return;
    } catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
    }   
}

//check admin logged in
check_admin_session();

// flash display error or success from the previous page     
flash_message();

// I pass the title of the page to the template as variable
$title = "Add User";

///// header section finished, I can use include
include("../templ/head.tpl");  

    ////////////////////////////////////////// VIEW ///////////////////////
?>
<!-- view-->
<h3>Add A New User</h3>
<form method="post" id="add_user_form">
<p>Is an Admin:
    <input type="checkbox" name="admin" value="yes"></p>
<p>UserName:
<input type="text" autofocus name="username" placeholder="mandatory"></p>

<p>Password:
<input type="password" name="password"></p>
    <p>Repeat Password:
<input type="password" name="password_repeat"></p>
    
    <p>These are optional:
    <p>First Name:
<input type="text" name="first_name"></p>
    <p>Last Name:
<input type="text" name="last_name"></p>
<p>Email:
<input type="text" name="email"></p>
<textarea rows="4" cols="50" name="comments" form="add_user_form" placeholder="Free Text for Comments.."></textarea>

<p><button type="submit" class = "btn btn-primary" name ='add' value='add' >Add New</button></p>
<a href="user_admin.php">Cancel</a></p>
</form>
<!-- close the head.tpl -->
</div>
</body>
<?php
include("../templ/footer.tpl"); 
?>