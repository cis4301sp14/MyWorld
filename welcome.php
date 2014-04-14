#!/usr/local/bin/php

<html>
 <head>
  <title>
   Welcome
  </title>
  <?php 
   if ($_POST['pw'] != $_POST['vpw'])
     {
       echo "passwords were not the same";
       exit;       
     }
   if ($_POST['un'] == 'username' || $_POST['pw'] == 'password' || $_POST['em'] == 'email') {
       echo "Please input new items intos the corresponding fields";
       exit;	
     }
   else {
     $usrn = $_POST['un'];
     $passw = $_POST['pw'];
     $email = $_POST['em'];
	 $firstn = $_POST['fn'];
	 $lastn = $_POST['ln'];
     }
  ?>
   
 </head>
 <body>
  <?php
   $dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
    $chkUsrn = pg_query($dbconn, "select username from users where username='$usrn'");
    $chkEmail = pg_query($dbconn, "select email from users where email='$email'");
	$dbusrn = pg_fetch_result($chkUsrn,0,0);
	if($dbusrn == $usrn || $dbemail == $email){
	if ($dbusrn == $usrn) {
	 echo "This username is already taken.\n";
	}
	$dbemail = pg_fetch_result($chkEmail,0,0);
	if ($dbemail == $email) {
	 echo "This email is already taken.\n";
	}
	}else {
	 $maxId = pg_query($dbconn, "select max(userid) from users");
 	 $newId = pg_fetch_result($maxId,0,0) + 1;
	
	 pg_query($dbconn, "insert into users (username, email, userid, firstn, lastn) values ('$usrn','$email',$newId, '$firstn', '$lastn')");
	 pg_query($dbconn, "insert into password (userid, pw) values ($newId,'$passw')");	 
	 //pg_close($dbconn);
	 
	 echo "Welcome to the club, $usrn";
	}
   ?>
  <form name='form' method='post' action='index.php'>
   <input type="Submit" value="Back">
  </form>
 </body>

</html>
