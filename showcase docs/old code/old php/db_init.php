<?php
//php/db_init.php

require_once "myPDO.php";

class Init{    
    public function __construct(){
        new myPDO();

        // uncomment to start afresh after changing in structure , DROP all tables
        //$this->drop_tables();
        
        // for first time initialisation
        // only for debugging will print the tables on the screen
        $this->create_tables();
        }
    
    private function drop_tables(){
      try{
      $sql = "DROP TABLE IF EXISTS is_admin, hash, salt,tracks_in_playlist, user_details, user_playlists, tags ,track_format, playlists,  users, tracks ";
      echo("<pre>\n".$sql."\n</pre>\n");
      $stmt = myPDO::$pdo->prepare($sql);
      $stmt->execute();
    } catch (Exception $ex ) { 
      echo("Internal error, please contact support");
      error_log("error4.php, SQL error=".$ex->getMessage());
      return;
      } 
    } 
  
    private function create_tables(){
        
            $sql = "CREATE TABLE IF NOT EXISTS users(
             id INT(11) AUTO_INCREMENT PRIMARY KEY,
             username VARCHAR(100) UNIQUE NOT NULL)";
            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

        
            $sql = "CREATE TABLE IF NOT EXISTS tracks(
                    id INT(11) AUTO_INCREMENT PRIMARY KEY,
                    track_name VARCHAR(100) UNIQUE NOT NULL,
                    soundcloud_id INT(11) UNIQUE NOT NULL)";
            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
        $sql = "CREATE TABLE IF NOT EXISTS playlists(
                    id INT(11) AUTO_INCREMENT PRIMARY KEY,
                    playlist_name VARCHAR(100) UNIQUE,
                    customized_page_header VARCHAR(1000),
                    customized_text TEXT)";

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
        $sql = "CREATE TABLE IF NOT EXISTS tags(
                id_tracks INT(11),
                tags VARCHAR(100),
                UNIQUE(id_tracks,tags),
                FOREIGN KEY (id_tracks) REFERENCES tracks (id))";
            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
         
        
        
            $sql = "CREATE TABLE IF NOT EXISTS users_playlist(
                    id_users INT(11) NOT NULL,
                    id_playlists INT(11) NOT NULL,
                    UNIQUE(id_playlists,id_users),
                    FOREIGN KEY (id_playlists) REFERENCES playlists (id),
                    FOREIGN KEY (id_users) REFERENCES users (id))";

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
            
        $sql = "CREATE TABLE IF NOT EXISTS tracks_in_playlist(
                    id_playlist INT(11) NOT NULL,
                    id_tracks INT(11) NOT NULL,
                    UNIQUE(id_playlist,id_tracks),
                    FOREIGN KEY (id_playlist) REFERENCES playlists (id),
                    FOREIGN KEY (id_tracks) REFERENCES tracks (id))";

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
            
        //add a constraint here later for id track and format as primary key together
            $sql = "CREATE TABLE IF NOT EXISTS track_format(
                    id_tracks INT(11),
                    format VARCHAR(5) NOT NULL,
                    UNIQUE(id_tracks,format),
                    FOREIGN KEY (id_tracks) REFERENCES tracks (id))";                    ;

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS is_admin(
                    id_users INT(11) UNIQUE NOT NULL,
                    admin VARCHAR(3) NOT NULL,
                    FOREIGN KEY (id_users) REFERENCES users (id))";

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS hash(
                    id_users INT(11) NOT NULL,
                    hash varchar(255) NOT NULL,
                    UNIQUE(id_users,hash),
                    FOREIGN KEY (id_users) REFERENCES users (id))";

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS salt(
                    id_users INT(11) NOT NULL,
                    salt varchar(16) NOT NULL,
                    UNIQUE(id_users,salt),
                    FOREIGN KEY (id_users) REFERENCES users (id))";

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS user_details(
                    id_users INT(11) NOT NULL,
                    first_name VARCHAR(100),
                    last_name VARCHAR(100),
                    email VARCHAR(100),
                    comments text,
                    FOREIGN KEY (id_users) REFERENCES users (id))";

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
            $sql = "CREATE TABLE IF NOT EXISTS log_index(
                    id INT(11) AUTO_INCREMENT PRIMARY KEY,
                    id_users INT(11) NOT NULL,
                    FOREIGN KEY (id_users) REFERENCES users (id))";

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
            
            $sql = "CREATE TABLE IF NOT EXISTS log(
                    id_log_index INT(12),
                    id_users INT(11) NOT NULL,
                    logged_in DATETIME,
                    logged_out DATETIME,
                    tracks_downloaded VARCHAR(2000),
                    tracks_listened VARCHAR(2000),
                    message TEXT,
                    FOREIGN KEY (id_log_index) REFERENCES log_index (id),
                    FOREIGN KEY (id_users) REFERENCES users (id))";

            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
           

}
}
$init = new Init();
//var_dump ($init);
//var_dump (INIT::$pdo);
/*<!--
<iframe width="100%" height="70" scrolling="no" frameborder="yes" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/342002845&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=false&show_reposts=false&show_teaser=false&visual=true"></iframe>
<iframe width="100%" height="70" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/342002845&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=false&show_reposts=false&show_teaser=false&visual=true"></iframe>

<iframe width="100%" height="300" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/448295415&color=%23ff5500&auto_play=false&hide_related=false&show_comments=false&show_user=true&show_reposts=false&show_teaser=false&visual=true"></iframe>
//
//https://soundcloud.com/sebastianmorawietz/01-silence-i
//<iframe width="100%" height="300" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/166769769&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe>-->
*/?>