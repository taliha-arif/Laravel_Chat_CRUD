# Mattermost

A chat application built with Laravel and mySQL. We can send both text messages and audio through this app.
## Features:

<ol>
  <li>Signup and login</li>
  <li>Forget Password</li>
  <li>Reset Password</li>
  <li>Chatting</li>
</ol>

## Getting Started  

Open cmd and go to your project directory folder.
### Installation Guide
Install the Laravel Installer globally with Composer.<br>
composer global require "laravel/installer"
<br>Next, edit your bash profile using Nano (or your editor of choice):<br>
nano ~/.bash_profile

Add the following line:<br>
export PATH="$PATH:$HOME/.composer/vendor/bin"
<br>Press CTRL + X to exit Nano. If you are prompted to save the file, enter Y, and then press enter. If you are not prompted to save the file, no worries, just press enter.<br>
Finally, close and reopen your terminal window, or you can use the source command to reload the bash profile.<br>
source ~/.bash_profile

## Database Setup and Migrations 

Create database in mysql(mattermost) and set database(mysql) connection in .env file.<br>
DB_CONNECTION=mysql<br>
DB_HOST=127.0.0.1<br>
DB_PORT=3306<br>
DB_DATABASE=mattermost *\<your database name, create first if you don\'t have\>*<br>
DB_USERNAME=root *\<your mysql username, by default `root`\>*<br>
DB_PASSWORD=root *\<your mysql password>*<br>
DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock *\<add this line for mac not needed in windows>*<br>

Run migrations to generate tables in your database<br>
php artisan migrate
## Models
### Users
Relationship is defined with message table. One user can have many messages. So, 1 to many(hasmany) realtion is defined. Using the fillable property mass assignment is done. 
### Message
Using fillable property mass assignment is done.
## Controllers
Create the controller using the command:<br>
php artisan make:controller users *\<your controller name\>*<br>
### users

Users controller is created for the functionalities of signup, login, forget passowrd, logout and reset password.


<ul>
  <li>Signup</li><p>User can signup with last-name, first-name, dob, email and password.</p>
  <li>Login</li><p>After creating account user can login with the registered credentials.After login unique access token will be assigned for the further actions. Before any request that assigned token will be checked to authenticate the user.</p>
  <li>Forget Password</li><p>If the user forgets password then email will be sent to that user for resetting password. </p>
  <li>Reset Password</li><p>After resetting password user can login again with new passowrd. </p>
</ul>

### Chatting

Chatting controller is created for the funtionality of sending messages. Sender can send the audio or text message to the receiver. user can update, delete and read all messages.

## Email
Make the class of email using command of laravel and set all the setting of email.<br>
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=talihaarif13@gmail.com *\<your sender name\>*<br>
MAIL_PASSWORD=ybdtjqvjzwmmtjrq
MAIL_ENCRYPTION=tls
## Run Server 

Open cmd and go to your project directory folder and run <br>
php artisan serve


<b>Enjoy Text or Audio Messaging!<b>





