#!/usr/local/bin/php

<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="shortcut icon" href="../../assets/ico/favicon.ico">

		<title>My World</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="starter-template.css" rel="stylesheet">
	
	
		<title>Friend Requests</title>		
		
	 </head>
	<body style="background-color:#E6E6E6;">
		<?php
			require 'functions.php';
			session_start();
			$urid = $_SESSION['userid'];
			$dbusrn = $_SESSION['usrn'];
			
			$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');					
			$frdID = $_POST["frd"];
			$frn = $_POST["frn"];				
			
			if($_POST["acc"]) {				
				$str = "You have accepted $frn's friend request <br /><br />";
				?> &nbsp&nbsp&nbsp&nbsp <?php
				echo $str;
				$query = pg_query($dbconn, "insert into friends(userid, friendid) values ($urid, $frdID)");
				$query2 = pg_query($dbconn, "insert into friends(userid, friendid) values ($frdID, $urid)");
				$delete_request = pg_query($dbconn, "DELETE FROM friendreq WHERE userid=$urid AND friendreqid=$frdID");					
			}						
			else if($_POST["dec"]) {				
				$delete_request = pg_query($dbconn, "DELETE FROM friendreq WHERE userid=$urid AND friendreqid=$frdID");				
			}
			
			$frdreq = pg_query($dbconn, "select count(friendreqid) from friendreq where userid='$urid'");
			$frdreqcount = pg_fetch_result($frdreq,0,0);
			?>
			<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="home.php">My World</a>
			</div>
			<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<?php echo '<li><a href="profile.php?un='.$dbusrn.'">Preview</a></li>';?>		
				<li><a href="home.php">Home</a></li>
				<li><a href="friends.php">Friends</a></li>
				<li class="active"><a href="friendreq.php"><?php 
				if(!($frdreqcount)) {echo 'Friend Requests';}			
				else{echo 'Friend Requests ('.$frdreqcount.')';}
			?></a></li>
			</ul>
			<form class="navbar-form navbar-right" name="form" action="loggedout.php" method = "post">            
				<button type="submit" class="btn btn-success">Sign Out, <?php echo ucwords($dbusrn);?></button>
			</form>
			<form class="navbar-form navbar-right" name="form" action="search.php" method = "post">            
				<div class="form-group">
					<input type="text" placeholder="Name or Username" class="form-control" name = "person" id = "person">					
				</div>
				<button type="submit" class="btn btn-success">Search</button>
			</form>
			</div><!--/.nav-collapse -->
			</div>
			</div>

			<div class="container" style="margin-top:60px"></div>
			<?php				 
			
			$requests = pg_query($dbconn, "select userid, firstn, lastn from (select friendreqid as userid from friendreq where userid = $urid)q natural join users;");			
			
			$max_rows = pg_num_rows($requests);	
			if(!($max_rows)){				
				?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 		
				echo "You have no friend requests at this time.</td></tr></table></div>";

			}
			else {
				echo '<div class="container" style="margin-top:50px"><table align="center">';		 
				$tmp = 0;
				for($row = 0; $row < ($max_rows/5); $row++) {
					echo '<tr>';	
					for($col = 0; $col < 5 && $col != $max_rows; $col++) {	
						$frdr = pg_fetch_result($requests,$tmp+$col,0);
						$frn = pg_fetch_result($requests,$tmp+$col,1);
						$lsn =	pg_fetch_result($requests,$tmp+$col,2);
						$frun = pg_fetch_result($requests,$tmp+$col,3);
					
						
						if($frdr) {
						?>
														
							<td ALIGN=CENTER>
							
							<form class="navbar-form navbar-right" name="form" action="friendreq.php" method = "post">
							<ul style="list-style: none;"><li>												
							<label><?php echo "$frn $lsn";?></label>										
							<div class="container" style="width: 175px">
	
							<?php 												
							$path = null;
							$path=profile_picture($frdr);
							$destination = '<a href="profile.php?frdun='.$frun;
							$path = $destination.'" style="outline : 0; border: 0; text-decoration:none;"><img src="'.$path. '" alt="image" width=150 height=auto class="img-circle" />';
							echo $path;
							?>								
							
							<input type="hidden" name="frn" value="<?php print "$frn"?>">
							<input type="hidden" name="frd" value="<?php print "$frdr"?>"><br/>								
							<button type="submit" class="btn btn-info btn-xs" name="acc" value="Accept">accept</button>	
							<button type="submit" class="btn btn-info btn-xs" name="dec" value="Decline">decline</button>
							</div>
							</form></li></ul></td>					
							<?php
						}	
					}
				$tmp = $tmp+5;	
				}
			echo '</table></div>';	
			}
			pg_close($dbconn);
		?>		
	</body>	
</html>
