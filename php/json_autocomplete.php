<?php
// start the session if not already started
session_start();

header('Content-Type: application/json; charset=utf-8');

// load PDO class for DB connection and the others too 
require_once "../class/autoloader.php";

// initialise DB if needed
require_once "db_init.php";

header('Content-Type: application/json; charset=utf-8');

$stmt = myPDO::$pdo->query("SELECT id, track_name, soundcloud_id 
        FROM tracks");
$rows = array();
while ( $row = $stmt->fetchColumn(1) ) {
  $rows[] = $row;
   // $_SESSION['autocomplete_tracks']['id']=htmlentities($row['id']);   $_SESSION['autocomplete_tracks']['track_name']=htmlentities($row['track_name']); $_SESSION['autocomplete_tracks']['soundcloud_id'] = htmlentities($row['soundcloud_id']);
}
    // put the results in the json file to be displayed 
echo json_encode($rows, JSON_PRETTY_PRINT);
