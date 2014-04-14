#!/usr/local/bin/php

<html>
	<head>
		<title>Friend Requests</title>		
		
	</head>
	<body>
		<?php
			session_start();
			$urid = $_SESSION['userid'];
			
			$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
			$requests = pg_query($dbconn, "select userid, firstn, lastn from (select friendreqid as userid from friendreq where userid = $urid)q natural join users;");		
						
			
			$max_req = pg_num_rows($requests);	
			for($i = 0; $i < $max_req; $i++) {	
				$frdr = pg_fetch_result($requests,$i,0);
				$frn = pg_fetch_result($requests,$i,1);
				$lsn =	pg_fetch_result($requests,$i,2);
				//$temp = pg_query("select firstn, lastn from users where userid = $frdr")
				//pg_close($dbconn);				
				
				echo "<p> $frn $lsn </p>";
			}
			
		?>
	</body>	
</html>
