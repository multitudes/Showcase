# ShowcaseProject



## Music showcase website for artists.
### Documentation of a 2 weeks PHP Projekt




Januar 2019

Introduction:
Artists often use platforms like Soundcloud to promote their own music.
On the Soundcloud website you can create playlists, but there is no possibility to be truly in control of custom personalized playlists, and to decide ad-hoc who can download tracks and who can only listen to the songs.
My web app allows artists to present their music to potential customers.

The customer receives a magic link to a dynamically generated website with a personalized welcome and a description of the music and a playlist.

The music market is changing, and artists are looking for alternative ways to promote their own product. The target group are music publishers, labels, graphic designers and game designers, filmmakers in search of production music, etc.

The app can be used in the web browser and has two separate page views. For the user who listen, and for the admin who create the playlists. Also he can create, retrieve, edit and cancel new users.

One-time user can log in without profile with token link and hear the playlist and eventually download tracks.

Data from the playlists is stored in a mySQL database and dinamically rendered on the page as needed.









## Part 1 - Set up the Database

I work on a macbook pro on macOS X and I downloaded MAMP with Apache, mySQL and PHP.

When I first login in the database with a root login, I will creat a user log in:

I open Terminal and type:

/Applications/MAMP/Library/bin/mysql -uroot -p 

Then I will be asked for my password and I get the mySQL prompt. 

I type the following after the mySQL prompt to create the user. If my database server is not on my computer localhost will be replaced by its IP address instead.

CREATE DATABASE showcase;
GRANT ALL ON showcase.* TO 'laurent'@'localhost' IDENTIFIED BY '1984!';
GRANT ALL ON showcase.* TO 'laurent'@'127.0.0.1' IDENTIFIED BY '1984!’;
USE showcase;
EXIT

I check for the location of my error log in MAMP and open a new terminal window. In production I will keep an eye on the file with this command:

tail -f /Applications/MAMP/logs/php_error.log

It is super helpful for debugging



I put this login in the file for mySQL with my details. Signing in as root is not recommended.

I created two classes. One class in the file class_myPDO.php  will create a PDO with my login details and will be included on every file making access to the database.

Another class Init would initialise the database create the necessary tables etc.
I could use the mySQL back up or dump instead. 

This is to download the mySQL dump to my folder:

$ /Applications/MAMP/Library/bin/mysqldump -uroot -p showcase > showcase.sql

To restore from the dump is quite easy in terminal again. Just reverse the bracket to read from the file. For Ex:

$ /Applications/MAMP/Library/bin/mysqldump -uroot -p showcase < showcase.sql

















This is the structure of my tables. The tables users playlists and tracks have primary keys auto increment. The others are connected through foreign keys:



This is the Entity Relationship Diagram (crow)



And the Entity Relationship Diagram (Chen)











PHP

The most interesting a challenging part has been to create a secure login.
There is common practice to hash the password values together with a random ‘salt’ and save the hash in the database. When the user logs in the hashes will be compared and if they match, the user has logged in successfully.
Passwords are not saved in the database and the hashes are a oneway encryption.However there is a better way. As described in the book by Robin Nixon “Learning PHP mySQL and Javascript” there is a function in PHP version 5.5 and above called password_hash() and this is the one I used for the encoding of the passwords. The salt is random generated and included in the hash.
To verify the passwords there is the function 

(password_verify($password, $row['hash'])

I still saved a random salt in the database to confuse potential attackers.

The Magic Link potentially is a weakness. It should be made clear that it is only valid for one login since the token is in the URL and can be stolen. HTTPS certification is a must here in any case.





I use many POST forms on this project. I user POST - Redirect - GET pattern to avoid any form being double posted.

On the mySQL queries like DELETE and UPDATE which are destructive, I use a try and catch error handling which will be sent to my terminal.

I used htmlentities() to sanitise the data I get from the database to secure myself from cross-site scripting (XSS) attacks. 
The data I get from the Forms are inserted into the database using the PDO prepare method avoiding the SQL injection vulnerability.

























User Case

The web app is divided in two distinct areas.

Admin and frequent user will have a login. The system will understand upon login if the user is admin or not. A simple user will just see a standard playlist upon login without being able to change the playlist.
The admin login can give the privilege to add, edit view and delete users,  change passwords and give admin rights.
The admin can create playlists and create a magic link. The magic link is login without a password with a token.
The ID of user is in the URL together with the token.Upon clicking on the link the user will be redirected directly to a dynamic generated site with a greeting, a description and a playlist with embedded the Soundcloud mini-players.

Admin Login
For testing the admin username is ‘seba’ and password is 123
Upon login I get to a screen with the playlists.










Viewing the playlist:
I have a screen showing me the songs and the details saved in the playlists. Also there is a magic link. This is the link I send to the user to automatic login and listen to the tracks














If I go back and click on ‘User Admin’ I see:

On this screen I can add new users. The page is self explanatory.






















Going back to the first screen I will  click on ‘add a playlist’:












Then I fill in the form and the details will be displayed on the next page:

There is an autocomplete button, just need to start typing the name of the song..


Adding tracks they will be displayed below the input box
Click on ‘Reset’ will reset the tracks only, ‘Cancel will bring you back to the playlist screen to start again.
Click on ‘Preview’ to preview the playlist
..and click on ‘Create a Magic Link’ to get the link which will appear below the button. Click on ‘Close’ to save. 
Basically the link should be used for one time log in because of security.




The Magic Link will be saved in the user comments. Since no username has been entered the playlist name will be the username for this login and the magic link will be saved in comments. This allows to make Playlist reusable for different users.

This is the result of clicking ‘close’


And ‘Log Out’ will bring the user to the Log In page.










The User View.

The user get the magic link and gets automatically redirected and this is what he will see ( the id and admin message is only for debug and will be taken out in the final version ):

















Using GIT


I have a GitHub account and I used Git to keep versions of my app in the cloud. It is quite easy to get started.
After creating an empty repository on GitHub I open my terminal on Mac and do 
git clone [https://github.com/…]

or I initialise the folder with:

git init
git status
	
//add to the staging area
git add index.php					

or
git add -A
where the dot stands for the current directory, so everything in and beneath it is added. The -A ensures even file deletions are included.
Then I do a git commit with -m for message
	
git commit -m “Added first file"


Then I push the changes in my local repository to GitHub.
git push -u origin master

ex of output of the last command:






