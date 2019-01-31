<?php
// start the session if not already started
session_start();

header('Content-Type: application/json; charset=utf-8');

// load PDO class for DB connection and the others too 
require_once "../class/autoloader.php";

// init DB
new myPDO();


if (isset($_SESSION['playlist'])){
    $j = count($_SESSION['playlist']);
    
    $data = array();
    for ($i = 0; $i < count($_SESSION['playlist']); $i++){  
        try{
            $stmt = myPDO::$pdo->prepare("SELECT `id`,soundcloud_id FROM `tracks` WHERE track_name = :track_name " );
            $stmt->execute(array(
                ':track_name' => $_SESSION['playlist'][$i]));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_track = $row['id']; 
            $soundcloud_id = $row['soundcloud_id'];
            $data[]=$row;       
        $sql = "INSERT IGNORE INTO `tracks_in_playlist`(`id_playlist`,`id_tracks`) VALUES (:id_playlist,:id_tracks)";
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute(array(
                ':id_playlist' => $_SESSION['playlist_id'],
                ':id_tracks' => $row['id']));
           }catch (Exception $ex ) { 
                error_log("error4.php, SQL error=".$ex->getMessage());
                return;
        }   
    }
}

    
// retrieve the data from the database
$stmt = myPDO::$pdo->prepare(
            "SELECT p.id, p.playlist_name, customized_page_header, customized_text, track_name, soundcloud_id 
            FROM playlists p
            LEFT JOIN tracks_in_playlist tp
            ON p.id = tp.id_playlist
            LEFT JOIN tracks t
            ON tp.id_tracks = t.id
            WHERE p.playlist_name = :xyz");

$stmt->execute(array(':xyz' => $_SESSION['playlist_name']));
$rows = array();
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
  $rows[] = $row;
};

echo json_encode($rows, JSON_PRETTY_PRINT);