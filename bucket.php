#!/usr/local/bin/php
<?php  
	session_start();
	if ($_SESSION['user'] == '') {
		header("Location: index.php");
	 exit;
	}
	else {

		$usrn = $_SESSION['user'];
		$pid = $_POST['id'];
		$dbsconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
		$result = pg_query($dbsconn, "select firstn, lastn, pw, userid, username from users natural join password where username='$usrn'");
		
		if (!$result) {
		 echo "An error has occurred.\n";
		 exit; 
		}
		$dbfn = pg_fetch_result($result, 0, 0);
		$dbln = pg_fetch_result($result, 0, 1);
		$dbpass = pg_fetch_result($result, 0, 2);
		$user_id = pg_fetch_result($result,0,3);
		$dbusrn = pg_fetch_result($result, 0, 4);

		$resultTF = pg_query($dbsconn, "select EXISTS (select * from bucket where photoid=$pid and userid = $user_id)::int");
		$result = pg_fetch_result($resultTF,0,0);
		 
		 if(!$result){
			$result = pg_query($dbsconn, "insert into bucket values ($pid,false,$user_id)");
			echo "This picture has been successfully added to your bucket.";
		 }else{
			$result = pg_query($dbsconn, "delete from bucket where photoid=$pid and userid = $user_id");
			echo "This picture has been successfully deleted from your bucket.";
		 }
	}
	  ?>
