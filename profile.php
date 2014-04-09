#!/usr/local/bin/php

<html>
 <head>
  <title>
   Login
  </title>
  <?php 

	session_start();
	
	if (empty($_POST['un']) || empty($_POST['pw']) || $_POST['un'] == 'username' || $_POST['pw'] == 'password')
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
  ?>
   
 </head>
 <body>
  <?php  
	$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
    $result = pg_query($dbconn, "select username, pw, userid from users natural join password where username='$usrn'");
	
	
	if (!$result) {
	 echo "An error has occurred.\n";
	 exit;
	}
	$dbusrn = pg_fetch_result($result, 0, 0);
	$dbpass = pg_fetch_result($result, 0, 1);
	
	if ($dbusrn != $usrn || $dbpass != $passw) {
	 echo "Username or Password is incorrect.\n";
	 exit;
	}
	else {
	 echo "Welcome, $dbusrn.\n";	 
	 $_SESSION['userid'] = pg_fetch_result($result,0,2);	 
	}
   ?>
   <form name="form" method="post" action="friendreq.php">
   <input type="submit" value="Friend Requests"> 
   </form>
   <form name="form" method="post" action="befriends.php">
   <input type="Text" value="" name="person" id="person">
	<input type="submit" value="Friend">
   </form>
	<form name="form" method="post" action="index.php">
	<input type="submit" value="Signout">
	</form>
   	<form action="upload_file.php" method="post" enctype="multipart/form-data">
	
	Album Name: <input type = "Text" value = "" name = "an" id="an">
	<br />
	   <label for="file">Filename:</label>
	<input type="file" name="file" id="file"><br>
	<input type="submit" name="submit" value="Upload">
	</form>
 </body>

</html>
