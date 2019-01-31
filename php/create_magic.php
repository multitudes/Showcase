<?php
// start the session if not already started
session_start();

header('Content-Type: application/json; charset=utf-8');

// load PDO class for DB connection and the others too 
require_once "../class/autoloader.php";

// initialise DB if needed
require_once "db_init.php";

// handle the session. 

// Need to load the tracks value for autocomplete (moved to jso_autocomplete)
/*if ( !isset ($_SESSION['autocomplete_tracks'])) $_SESSION['autocomplete_tracks'] = array(); */

// now if I had a post with a request for magic link I will have this session too
 // and I will update the database with a new user
if (isset($_SESSION['magic'])){   
    
    //this wil check if the magic link button has been already pressed. to avoid duplicates 
    if ( $_SESSION['magic'] == 'requested'){

    try {
     
        // insert new username (same as playlist name) in the users table. ID is auto increment. 
        $sql = "INSERT INTO users (username)
                VALUES (:username)";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(':username' => $_SESSION['playlist_name']));
        // I am generating a random password for this user
        $length = 4; 
        //the value is 8 chars because 4 bytes in hex got 8 chars
        $pass = bin2hex(random_bytes($length));  

        // use this password to generate the hash. the salt is included in the hash with this function
        $hash = password_hash($pass,PASSWORD_DEFAULT);

        // get the last generated id first
        $id_users = myPDO::$pdo->lastInsertId();
        
        //keep the id for future use
        $_SESSION['id_users'] = $id_users;

        // insert into the hash table with the last generated id 
        $sql= "INSERT INTO hash (id_users,hash)
                   VALUES(:id_users, :hash)";
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute(array(
                ':id_users' => $id_users,
                ':hash' => $hash));

        // insert the playlist number in the table users_playlist
        $sql= "INSERT INTO users_playlist (id_users, id_playlists)
                   VALUES(:id_users, :id_playlists)";
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute(array(
                ':id_users' => $id_users,
                ':id_playlists' => $_SESSION['playlist_id']));

        // insert into the salt table with the last generated id 
        // here again the salt is actually fake because the function password_hash() creates a hash with salt already included. it is a honey pot in case somebody gets the tables from the database will think there is a hash.. but there is none! 
        $length = 8; 
        $salt = bin2hex(random_bytes($length));  //the value is 16 chars because 8 bytes in hex 16 chars
        $sql= "INSERT INTO salt (id_users, salt)
                   VALUES(:id_users, :salt)";
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute(array(
                ':id_users' => $id_users,
                ':salt' => $salt));
            // of course the user will not be admin
            $admin = 'no';
            $sql= "INSERT INTO is_admin (id_users,admin)
                   VALUES(:id_users, :admin)";
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute(array(
                ':id_users' => $id_users,
                ':admin' => $admin));    

        // better read from the database just to make sure the data are correct
            $stmt = myPDO::$pdo->prepare(
                "SELECT u.id, h.hash
                FROM users u
                LEFT JOIN hash h
                ON u.id = h.id_users
                WHERE u.id = :xyz");
            $stmt->execute(array(":xyz" => $id_users));

            $row = $stmt->fetch(PDO::FETCH_ASSOC); 

        // put the results in the json file to be displayed

            echo json_encode($row, JSON_PRETTY_PRINT);

            $_SESSION['magic'] = 'received';
            $_SESSION['success'] = 'Magic Link created';
            //header( 'Location: user_admin.php' ) ;
            return;
        } catch (Exception $ex ) { 
        //echo("Internal error, please contact support");
        $_SESSION['error'] = 'Internal error please contact support';
        error_log("error4.php, SQL error=".$ex->getMessage());
        return;
        }   
    }
    // if the session has been received already then I will just do an update
    if ( $_SESSION['magic'] == 'received'){
        try{
        // I am generating a random password for this user
        $length = 4; 
        //the value is 8 chars because 4 bytes in hex got 8 chars
        $pass = bin2hex(random_bytes($length));  

        // use this password to generate the hash. the salt is included in the hash with this function
        $hash = password_hash($pass,PASSWORD_DEFAULT);
         
        // update the hash table with the last generated hash and old id
        $sql= "UPDATE hash
               SET  hash = :hash
                WHERE id_users = :id_users";
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute(array(
                ':id_users' => $_SESSION['id_users'],
                ':hash' => $hash));
            
        // update salt
        $length = 8; 
        $salt = bin2hex(random_bytes($length));  //the value is 16 chars because 8 bytes in hex 16 chars
        $sql= "update salt
              SET  salt = :salt
              WHERE id_users = :id_users";
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute(array(
                ':id_users' => $_SESSION['id_users'],
                ':salt' => $salt));
        // better read from the database just to make sure the data are correct
            $stmt = myPDO::$pdo->prepare(
                "SELECT u.id, h.hash
                FROM users u
                LEFT JOIN hash h
                ON u.id = h.id_users
                WHERE u.id = :xyz");
            $stmt->execute(array(":xyz" => $_SESSION['id_users']));

            $row = $stmt->fetch(PDO::FETCH_ASSOC); 

        // put the results in the json file to be displayed
            echo json_encode($row, JSON_PRETTY_PRINT);
        } catch (Exception $ex ) { 
        //echo("Internal error, please contact support");
        $_SESSION['error'] = 'Internal error please contact support';
        error_log("error4.php, SQL error=".$ex->getMessage());
        return;
        }
    }
}


