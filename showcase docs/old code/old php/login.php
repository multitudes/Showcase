<?php  //do not put any HTML above this line

/////////////////////////// user or admin login ///////////////////////////////
// start session on server
session_start();

//connect to the database
require_once "db_init.php";




// log the previous user out if not happened yet
unset($_SESSION['username']);
unset($_SESSION['id']);
unset($_SESSION['admin']);


// first check if user wants to cancel
if (isset($_POST['cancel'])){
    //echo $_POST['cancel'];
    header("Location: ../start.php");
    return;
}


// check if I got the POST data username and password 
if ( isset($_POST['username']) && isset($_POST['password'])) {
    
    // Data validation passwords and username
    if((strlen($_POST['password']) < 1 )&&(strlen($_POST['username']) < 1)){
        $_SESSION['error'] = 'email and password are required';
        header("Location: login.php");
        return;
        }
    
    // Data validation passwords only
    if((strlen($_POST['password']) < 1 )){
        $_SESSION['error'] = 'missing password';
        header("Location: login.php");
        return;
        }
    
    // Data validation username only
    if ( strlen($_POST['username']) < 1 ) {
        $_SESSION['error'] = 'Missing username';
        header("Location: login.php");
        return;
    }    
    
    
    // retrieve users based on username
    try { 
    $password = $_POST['password'];   
    //echo ' '.$password."</p>\n";
    //$check = password_hash($password, PASSWORD_DEFAULT);
    //echo ' '.$check."</p>\n";
    
    //echo($_POST['id']);
    // mySQL query for the user details including hash and user ID
    $sql = "SELECT id, username, hash, admin 
            FROM users u 
            INNER JOIN hash h ON u.id = h.id_users 
            INNER JOIN is_admin i ON u.id = i.id_users
            WHERE u.username = :username";
    $stmt = myPDO::$pdo->prepare($sql);
    //print_r( $stmt);
    $stmt->execute(array(
        ':username' => $_POST['username']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
    //If I did not get any row then the username or password was wrong.. so check this first
    // if they are correct I will verify the password and update the session
    if ($row !== false){                // if I get a row out of the query then I have a user
        
        // this next row uses the PHP function password_verify():BOOL
        if(password_verify($password, $row['hash'])) {
            //echo 'it works !! ';
            // if the password is correct then the session will be updated with the ID and username
            $_SESSION['username']=$row['username'];
            $_SESSION['id']=$row['id'];
            // I need to check if the user is an admin or a regular user
            if ($row['admin'] === 'yes'){ 
                $_SESSION['admin']='yes';
                header("Location: admin.php"); 
                return;
            }else{
                $_SESSION['admin']='no';
                header("Location: ../user_session.php"); 
                return;
            }
        }else{                                              // if password verify is false
        $_SESSION['error'] = 'incorrect password';
        header("Location: login.php"); 
        return;    
        }         
    }else{
        $_SESSION['error'] = "user not found";
        header("Location: login.php"); 
        return;
        }   
    }catch (Exception $ex ) { 
    echo("Internal error, please contact support");
    error_log("error4.php, SQL error=".$ex->getMessage());
    return;
    }
}
// Flash pattern will give out the validation result in red only if unsuccessful
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
    }
if ( isset($_SESSION['username']) && isset($_SESSION['id'])){
    echo '<p style="color:red">username: '.$_SESSION['username']."</p>\n";
    echo '<p style="color:red">ID: '.$_SESSION['id']."</p>\n"; 
    echo '<p style="color:red">Is Admin : '.$_SESSION['admin']."</p>\n"; 
    }

//the include has to be put after the header()
include("../templ/head.tpl");
?>
<!-- Here begins my HTML with the form. Form will have a POST-->
<head><title>Login</title>
<?php require_once "bootstrap.php";?>
</head>
<h1>Log In</h1><br/>
<form method="POST" action="login.php">
<label for="username"></label>
<input type="text" name="username" id="username" placeholder="username"><br/>
<label for="id_1723"></label>
<input type="password" name="password" id="id_1723" placeholder="password"><br/><br/>
<input type="submit" value="Log In"> <!-- took out : onclick="return doValidate();"-->
<input type="submit" name="cancel" value="Cancel">
</form>
<p>

</p>
<!--       ////////// javascript validation maybe not needed after all //////
<script>
function doValidate() {
    console.log('Validating...');
    try {
        name = document.getElementById('username').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+name+" pw="+pw);
        if (name == null || name == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>
-->

</div>
</body>