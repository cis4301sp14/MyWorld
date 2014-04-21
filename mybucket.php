#!/usr/local/bin/php
<?php  
	session_start();
	if ($_SESSION['user'] == '') {
		header("Location: index.php");
	 exit;
	}
	else {
		$usrn = $_SESSION['user'];
		$pid = $_POST['pid'];
		$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
		$result = pg_query($dbconn, "select userid from users natural join password where username='$usrn'");
		
		if (!$result) {
		 echo "An error has occurred.\n";
		 exit;
		}
		$user_id = pg_fetch_result($result,0,0);
		$resultTF = pg_query($dbconn, "update bucket set gone=true where photoid = $pid and userid = $user_id");
		//echo "$pid $user_id";
	}
	  ?>
