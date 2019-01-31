<?php
//php/db_init.php

require_once "../class/autoloader.php";

class Init{    
    public function __construct(){
        // this will connect to the database
        new myPDO();

        // uncomment to start afresh after changing in structure , DROP all tables
        //$this->drop_tables();
        
        // for first time initialisation
        // only for debugging will print the tables on the screen
        $this->create_tables();
        
        $this->insert_users();
        $this->insert_playlist();
        //$this->display_users();
        }
    
    private function drop_tables(){
      try{
      $sql = "DROP TABLE IF EXISTS log, is_admin, hash, salt, tracks_in_playlist, user_details, users_playlist, tags , track_format, tracks, playlists, users";
      //echo("<pre>\n".$sql."\n</pre>\n");
      $stmt = myPDO::$pdo->prepare($sql);
      $stmt->execute();      
      } catch (Exception $ex ) { 
      echo("Internal error, please contact support");
      error_log("error4.php, SQL error=".$ex->getMessage());
      return;
      } 
    } 
  
    private function create_tables(){
        try{
            $sql = "CREATE TABLE IF NOT EXISTS users(
             id INT(11) AUTO_INCREMENT PRIMARY KEY,
             username VARCHAR(100) UNIQUE NOT NULL,
             INDEX(username(20)))";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

        
            $sql = "CREATE TABLE IF NOT EXISTS tracks(
                    id INT(11) AUTO_INCREMENT PRIMARY KEY,
                    track_name VARCHAR(100) UNIQUE NOT NULL,
                    soundcloud_id INT(11) UNIQUE NOT NULL,
                    INDEX(track_name(20)))";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
        $sql = "CREATE TABLE IF NOT EXISTS playlists(
                    id INT(11) AUTO_INCREMENT PRIMARY KEY,
                    playlist_name VARCHAR(100) UNIQUE,
                    customized_page_header VARCHAR(1000),
                    customized_text TEXT,
                    INDEX(playlist_name(20)),
                    INDEX(customized_text(20)))";

            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
        $sql = "CREATE TABLE IF NOT EXISTS tags(
                id_tracks INT(11) UNIQUE,
                tag VARCHAR(100),
                UNIQUE(id_tracks,tag),
                FOREIGN KEY (id_tracks) REFERENCES tracks (id)
                ON DELETE CASCADE)";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
         
        
        
            $sql = "CREATE TABLE IF NOT EXISTS users_playlist(
                    id_users INT(11) NOT NULL,
                    id_playlists INT(11) NOT NULL,
                    UNIQUE(id_playlists,id_users),
                    FOREIGN KEY (id_playlists) REFERENCES playlists (id)
                    ON DELETE CASCADE,
                    FOREIGN KEY (id_users) REFERENCES users (id)
                    ON DELETE CASCADE)";

            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
            
        $sql = "CREATE TABLE IF NOT EXISTS tracks_in_playlist(
                    id_playlist INT(11) NOT NULL,
                    id_tracks INT(11) NOT NULL,
                    UNIQUE(id_playlist,id_tracks),
                    FOREIGN KEY (id_playlist) REFERENCES playlists (id)
                    ON DELETE CASCADE,
                    FOREIGN KEY (id_tracks) REFERENCES tracks (id)
                    ON DELETE CASCADE)";

            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
            
        //add a constraint here later for id track and format as primary key together
            $sql = "CREATE TABLE IF NOT EXISTS track_format(
                    id_tracks INT(11) UNIQUE NOT NULL,
                    format VARCHAR(5) NOT NULL,
                    UNIQUE(id_tracks,format),
                    FOREIGN KEY (id_tracks) REFERENCES tracks (id)
                    ON DELETE CASCADE)";                    ;

            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS is_admin(
                    id_users INT(11) UNIQUE NOT NULL,
                    admin VARCHAR(3) NOT NULL,
                    FOREIGN KEY (id_users) REFERENCES users (id)
                    ON DELETE CASCADE)";

            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS hash(
                    id_users INT(11) UNIQUE NOT NULL,
                    hash varchar(255) NOT NULL,
                    INDEX(hash(20)),
                    UNIQUE(id_users,hash),
                    FOREIGN KEY (id_users) REFERENCES users (id)
                    ON DELETE CASCADE)";

            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS salt(
                    id_users INT(11) UNIQUE NOT NULL,
                    salt varchar(16) NOT NULL,
                    UNIQUE(id_users,salt),
                    FOREIGN KEY (id_users) REFERENCES users (id)
                    ON DELETE CASCADE)";

            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();

            $sql = "CREATE TABLE IF NOT EXISTS user_details(
                    id_users INT(11) UNIQUE NOT NULL,
                    first_name VARCHAR(100),
                    last_name VARCHAR(100),
                    email VARCHAR(100),
                    comments text,
                    INDEX(comments(20)),
                    INDEX(email(10)),
                    INDEX(last_name(10)),
                    INDEX(first_name(10)),
                    FOREIGN KEY (id_users) REFERENCES users (id)
                    ON DELETE CASCADE)";

            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
            
            $sql = "CREATE TABLE IF NOT EXISTS log(
                    id INT(12)AUTO_INCREMENT PRIMARY KEY,
                    id_users INT(11) NOT NULL,
                    logged_in DATETIME,
                    logged_out DATETIME,
                    tracks_downloaded VARCHAR(2000),
                    tracks_listened VARCHAR(2000),
                    message TEXT,
                    INDEX(message(20)),
                    FOREIGN KEY (id_users) REFERENCES users (id)
                    ON DELETE CASCADE)";

            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        } catch (Exception $ex ) { 
      echo("Internal error, please contact support");
      error_log("error4.php, SQL error=".$ex->getMessage());
      return;
      } 
    
}
    
    private function insert_users(){
    try{
        $sql = "INSERT IGNORE INTO `users`(`username`) VALUES ('seba')";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        $sql = "INSERT IGNORE INTO `user_details`(`id_users`,`first_name`,`last_name`, `email`,`comments`) VALUES (1,'seba', 'mora','sebmo@seba.com','the boss!')";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        $sql = "INSERT IGNORE INTO `is_admin`(`id_users`,`admin`) VALUES (1,'yes')";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        $sql = "INSERT IGNORE INTO `hash`(`id_users`,`hash`) VALUES (1,'$2y$10$5kKVS3ur8kLIhlcv4LEQROtQIPI6m0RZH8.AiuSKTU/roZcxrjmxy')";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        $sql = "INSERT IGNORE INTO `salt`(`id_users`,`salt`) VALUES (1,'3c4060a9e619dfeb')";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
    }catch (Exception $ex ) { 
      echo("Internal error, please contact support");
      error_log("error4.php, SQL error=".$ex->getMessage());
      return;
      } 
    }
    
    private function insert_playlist(){
    try{
        $sql = "INSERT IGNORE INTO `playlists`(`playlist_name`,customized_page_header,customized_text) VALUES ('electro','Electro Playlist','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero dolorum quisquam culpa quae voluptatibus saepe a ad excepturi quam, sunt ex ullam officia porro architecto, nam et esse minus reiciendis? Enim aliquid accusantium repellat, maiores magnam quidem rem cumque officia omnis officiis. Omnis doloremque error, maxime vitae aspernatur nemo eos.
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero dolorum quisquam culpa quae voluptatibus saepe a ad excepturi quam, sunt ex ullam officia porro architecto, nam et esse minus reiciendis? Enim aliquid accusantium repellat, maiores magnam quidem rem cumque officia omnis officiis. Omnis doloremque error, maxime vitae aspernatur nemo eos.')";
        //echo("<pre>\n".$sql."\n</pre>\n");
        $stmt = myPDO::$pdo->prepare($sql);
        $stmt->execute();
        
        $sql = "INSERT IGNORE INTO `tracks`(`track_name`,`soundcloud_id`) VALUES ('Power To Inspire',342002845),('Silence I',166769769)";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        $sql = "INSERT IGNORE INTO `tracks_in_playlist`(`id_playlist`,`id_tracks`) VALUES (1,1),(1,2)";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        $sql = "INSERT IGNORE INTO `tracks`(`track_name`,`soundcloud_id`) VALUES ('Eye Opener (for Choir & Piano)',525633687),('Nocturne No.2',534269169)";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        $sql = "INSERT IGNORE INTO `tracks_in_playlist`(`id_playlist`,`id_tracks`) VALUES (1,2389),(1,2425)";
            //echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
        
        $sql = "INSERT IGNORE INTO `tracks`(`track_name`,`soundcloud_id`) VALUES 
        ('Strange Night In A Strange Town (The Last Rehearsal Mix)',220259722),
        ('Eye Opener (The Last Rehearsal Mix)',220260092),
        ('Be The Change',492263835),
        ('MOOD I',515601660),
        ('Twilight Sky',455073204),
        ('Abandoned Loyalty',441531300),
        ('Window III',213975126),
        ('Silent Wings (Slow Down)',121058300),
        ('The Big Picture',488029833)";
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
        $sql = "INSERT IGNORE INTO tags (`tag`) VALUES 
        ('piano'),('orchestral'),('electronica'),('inspirational'),('sad'),('emotional'),('happy'),('corporate')";
        $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
        
    }catch (Exception $ex ) { 
      echo("Internal error, please contact support");
      error_log("error4.php, SQL error=".$ex->getMessage());
      return;
      } 
    }
    
    private function display_users(){
    try{
        $sql = "select * from users us join user_details ud on us.id = ud.id_users join is_admin i on us.id = i.id_users JOIN hash h ON us.id = h.id_users JOIN salt s ON us.id = s.id_users ";
            echo("<pre>\n".$sql."\n</pre>\n");
            $stmt = myPDO::$pdo->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
        
        print_r($row);    
        
        
    }catch (Exception $ex ) { 
      echo("Internal error, please contact support");
      error_log("error4.php, SQL error=".$ex->getMessage());
      return;
      } 
    
    }
}

// use the class and start initialize the database
new Init();
