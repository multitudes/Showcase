# ShowcaseProject

School project in PHP. 

Showcase is a music playlist generator for artists interested in showcasing their music to a potential target group such as music publishers, labels, graphic designers and game designers, filmmakers in search of production music, etc.
Artists often use platforms like Soundcloud to promote their own music.
On the Soundcloud website you can create playlists, but there is no possibility to create custom personalized playlists where the tracks can be ad hoc downloaded in different bitrates or depending from the customer agreements, just previewed.
My web app allows artists to present their music to potential customers.
The customer receives a magic link to a dynamically generated website with a personalized welcome and a description of the music and a playlist.
In this version of Showcase 1.0, the download links are not yet implemented. 

The app is web based and has separate pages.
1. There is a login for admin and users. The admin can create, retrieve, edit and cancel new users. Create new playlists of their own music which will be stored locally independently of soundcloud.
2. One-time user can log in without profile with token link and hear the playlist which will be streamed from Soundcloud using their enbedded player. The statistic will count toward the Soundcloud ones, but downloads will be from the local server.

Example of UI:

When logged in as Admin 

<img src="/pasted-image-33.png" alt="drawing" width="300"/>

<img src="/pasted-image-35.png" alt="drawing" width="300"/>

creating a playlist

<img src="/pasted-image-37.png" alt="drawing" width="600"/>

with autocomplete

<img src="/pasted-image-39.png" alt="drawing" width="600"/>

the user will see the playlist with the player (here just one song)

<img src="/pasted-image-51 copy.png" alt="drawing" width="600"/>

The final design of the website is not yet done, this is just the php server side code, to store the data in a mySQL database and retrieve the information needed.
It is a secure login. The one time magic link is not secure. Eventually a better solution should be implemented creating a user account for recurring users. However being aware that the prospect of creating yet another account with password could be daunting for some customers, I decided to implemented a passwordless login. This type of login contain a magic link sent per email to the customer and will be valid for just one login.
