#!/usr/local/bin/php

<html>
 <head> 
	
 </head>
 <body>
  <?php
	session_start();
  
   $frd = $_POST['person'];  
   $userid = $_SESSION['userid'];
   echo "$frd $userid";
   
   $dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
   $result = pg_query($dbconn, "select username, userid from users where username='$frd'");   
   pg_close($dbconn);
   
   $friend = pg_fetch_result($result,0,0);      
   if ($friend == $frd && $frd != $usrn) {
	$frdId = pg_fetch_result($result,0,1);	
	$tur = pg_query($dbconn, "insert into friendreq (userid, friendreqid) values ($frdId, $userid)");
   
	echo "Friend request sent to $friend.";
	}
   else {
    echo "$frd does not exist.";
	exit; 
	}
  ?>
 </body>
</html>


