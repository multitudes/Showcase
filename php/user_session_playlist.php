<?php
// start the session if not already started
session_start();

header('Content-Type: application/json; charset=utf-8');

// load PDO class for DB connection and the others too 
require_once "../class/autoloader.php";

// initialise DB if needed
require_once "db_init.php";


if (isset($_SESSION['id'])){
    $id = $_SESSION['id'];
    
    try{
            // retrieve the data from the database
        $stmt = myPDO::$pdo->prepare(
            "SELECT u.id AS user_id, p.id AS playlist_id, p.playlist_name, customized_page_header, customized_text, t.id, track_name, soundcloud_id 
            FROM users u
            LEFT JOIN users_playlist up
            ON u.id = up.id_users
            LEFT JOIN playlists p
            ON up.id_playlists = p.id
            LEFT JOIN tracks_in_playlist tp
            ON p.id = tp.id_playlist
            LEFT JOIN tracks t
            ON tp.id_tracks = t.id
            WHERE u.id = :xyz");

        $stmt->execute(array(':xyz' => $id));
        $rows = array();
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        $rows[] = $row;
        };

        echo json_encode($rows, JSON_PRETTY_PRINT);
    }catch (Exception $ex ) { 
                error_log("error4.php, SQL error=".$ex->getMessage());
                return;
        }   
    }


    
