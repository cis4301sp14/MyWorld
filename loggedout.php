#!/usr/local/bin/php

<html>
 <head>
 <meta http-equiv="refresh" content="3;URL='index.php'">
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
  Page will return to homepage in 3 seconds. 


 </body>

</html>