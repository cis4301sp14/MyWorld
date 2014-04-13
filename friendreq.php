#!/usr/local/bin/php

<html>
	<head>
		<title>Friend Requests</title>		
		
	</head>
	<body>
		<?php
		
			
			$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
			$requests = pg_query("select friendreqid from friendreq where userid = 1");
			
			$reqid = pg_fetch_result($requests,1,0);
			echo $reqid;
			
			$max_req = pg_num_rows($requests);
			echo $max_req;
			for($i = 0; $i < $max_req; $i++) {
				echo pg_fetch_result($row,$i,0);
			}
			/*
			if($requests != 0) {
				while($req = pg_fetch_result($requests,0,0)) {
					//&uname = pg_query("select firstn, lastn from users where userid = $req")
					$firn = pg_fetch_result($uname, 0,0);
					$lasn = pg_fetch_result($uname, 0,1);
					echo "$firn $lasn: <form name="form" method="post" action="fraccept.php">
							<input type="submit" value="accept">
							</form>";
							
					$n = 1;
				}				
			}
			
			//pg_close($dbconn);
			
			else {
				echo "You have no Friend Requests.";
				echo '<form name="form" method="post" action="profile.php">
						<input type="submit" value="Back"> 
						</form>';
			}
			
			*/
		?>
	</body>	
</html>
