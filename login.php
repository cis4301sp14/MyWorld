#!/usr/local/bin/php

<html>
 <head>
  <title>
   Treasure Hunters - Login
  </title>
 </head>
 
 <body>
	
	<form name="form" action="login.php" method = "post">           
        <input type="text" placeholder="Username" name = "un" id = "un" required>
          <input type="password" placeholder="Password" name = "pw" id = "pw" required>
     
           <button type="submit">Sign in</button>
        </form>

	<?php 

	session_start();
	if ($_POST['un'] == 'username' || $_POST['pw'] == 'password')
     {
       echo "Please input both username and password.";
       exit;       
     } 
	else 
	{    
     $usrn = $_POST['un'];
     $passw = $_POST['pw'];
	 $_SESSION['user'] = $usrn;
	 	 
    }
  
  
   $dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
   $result = pg_query($dbconn, "select username, pw, userid from users natural join password where username='$usrn'");
   
   	if (!$result) {
	 echo "An error has occurred.\n";
	 exit;
	}
	
	$dbusrn = pg_fetch_result($result, 0, 0);
	$dbpass = pg_fetch_result($result, 0, 1);
	
	$valid = 0; 
	
	if ($dbusrn != $usrn || $dbpass != $passw) {
	 echo "Username or Password is incorrect.\n";
	 exit;
	}
		
	else {
	   $_SESSION['userid'] = pg_fetch_result($result,0,2);
		 
	header("Location: home.php");
	
	}
	exit;

  ?>
 
  	


 </body>

</html>
