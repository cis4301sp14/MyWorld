#!/usr/local/bin/php

<?php  
	session_start();
	if ($_SESSION['user'] == '') {
		header("Location: index.php");
	 exit;
	}
	else {

		$usrn = $_SESSION['user'];
		$fid = $_POST['id'];
		$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
		$result = pg_query($dbconn, "select firstn, lastn, pw, userid, username from users natural join password where username='$usrn'");
		
		if (!$result) {
		 echo "An error has occurred.\n";
		 exit;
		}
		$dbfn = pg_fetch_result($result, 0, 0);
		$dbln = pg_fetch_result($result, 0, 1);
		$dbpass = pg_fetch_result($result, 0, 2);
		$user_id = pg_fetch_result($result,0,3);
		$dbusrn = pg_fetch_result($result, 0, 4);
		
		$resultTF = pg_query($dbconn, "select userid from friends where userid=$fid and friendid=$user_id");
		$resultTF2 = pg_query($dbconn, "select userid from friendreq where userid=$fid and friendreqid=$user_id");
		$friend = pg_fetch_result($resultTF,0,0);
		$friendreq = pg_fetch_result($resultTF2,0,0);
		
		if($friend == $fid){
			echo "0";
		 }
		 else if($friendreq == $fid){			
			echo "0";
		 }
		 else {
			$tur = pg_query($dbconn, "insert into friendreq values ($fid, $user_id)");
			echo "1";
		 }
	
	}
?>