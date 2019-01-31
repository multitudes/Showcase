<?php


// Make sure that the user id is on the get request to view delete or edit users
function check_get_userid()
    {
    // Make sure that the user id is on the get request to view delete or edit users
    if ( !isset($_GET['id'])){
        $_SESSION['error'] = "Missing id";
        header( 'Location: user_admin.php' ) ;
        return;
    }
}

// check if a user is logged in as admin with the sessions
function check_admin_session()
{    
    if ( isset($_SESSION['username']) && isset($_SESSION['id'])){
        //first check if user is admin. if not redirect
        if ($_SESSION['admin'] !== 'yes'){
            $_SESSION['error']='admin login required';
            header('Location: login.php');
            return;
        }else{
        // this is for debugging    
        echo '<p style="color:red">'."&nbsp".'username: '.$_SESSION['username']."</p>\n";
        echo '<p style="color:red">'."&nbsp".'ID: '.$_SESSION['id']."</p>\n"; 
        echo '<p style="color:red">'."&nbsp".'Is Admin : '.$_SESSION['admin']."</p>\n"; 
        }
    }else{
        $_SESSION['error']='admin login required';
        header('Location: login.php');
        return;
        
    }
}

function flash_message(){
    // Flash pattern: display messages once
    if ( isset($_SESSION['error']) ) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
        }
    if ( isset($_SESSION['success'])) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }      
}


// this function will include the necessary files. 
function admin_session_start(){
    // start the session if not already started
    session_start();

    // load PDO class for DB connection and the others too 
    require_once "../class/autoloader.php";
    
    // initialise DB if needed
    //require_once "db_init.php";      
}