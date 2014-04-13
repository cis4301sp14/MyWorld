#!/usr/local/bin/php

<html>
 <head>
 <meta http-equiv="refresh" content="5;URL='index.php'">
  <title>
	LoggedOut
  </title>
 <head>

 <body>
  <?php 
    session_start();
    session_unset();
  ?>

  You have been successfully logged out of app. 
  Page will return to homepage in 5 seconds. 


 </body>

</html>