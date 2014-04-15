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
	<body>
		<?php
			require 'functions.php';
			session_start();
			$urid = $_SESSION['userid'];
			$dbusrn = $_SESSION['usrn'];
			
			$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');					
			$frdID = $_GET["frd"];
			$frn = $_GET["frn"];				
			
			if($_GET["acc"]) {				
				$str = "You have accepted $frn's friend request <br /><br />";
				?> &nbsp&nbsp&nbsp&nbsp <?php
				echo $str;
				$query = pg_query($dbconn, "insert into friends(userid, friendid) values ($urid, $frdID)");
				$query2 = pg_query($dbconn, "insert into friends(userid, friendid) values ($frdID, $urid)");
				$delete_request = pg_query($dbconn, "DELETE FROM friendreq WHERE userid=$urid AND friendreqid=$frdID");					
			}						
			else if($_GET["dec"]) {				
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
			<a class="navbar-brand" href="#">My World</a>
			</div>
			<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="profile.php">Preview</a></li>
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
			<form class="navbar-form navbar-right" name="form" action="befriends.php" method = "post">            
				<div class="form-group">
					<input type="text" placeholder="Username" class="form-control" name = "person" id = "person">					
				</div>
				<button type="submit" class="btn btn-success">Add Friend</button>
			</form>
			</div><!--/.nav-collapse -->
			</div>
			</div>

			<br /><br /><br />
			<?php				 
			
			$requests = pg_query($dbconn, "select userid, firstn, lastn from (select friendreqid as userid from friendreq where userid = $urid)q natural join users;");			
			
			$max_req = pg_num_rows($requests);	
			if(!($max_req)){
				$str2 = "You have no friend requests at this time";
				?> &nbsp&nbsp&nbsp&nbsp <?php
				echo $str2;
			}
			else {
				for($i = 0; $i < $max_req; $i++) {	
					$frdr = pg_fetch_result($requests,$i,0);
					$frn = pg_fetch_result($requests,$i,1);
					$lsn =	pg_fetch_result($requests,$i,2);
					?>									
				
					<form name="form" method="get" action="friendreq.php">	
					&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<?php echo "$frn $lsn "; ?>
					
					<div class="container">
	
						<?php 												
						$path = null;
						$path=profile_picture($frdr);
						$path = '<img src="'.$path. '" alt="image" width=150 height=auto />';
						echo $path;
						?>
					</div>	
					&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type="hidden" name="frn" value="<?php print "$frn"?>">
					<input type="hidden" name="frd" value="<?php print "$frdr"?>">
					<input type="submit" name="acc" value="accept">	
					<input type="submit" name="dec" value="decline">		
					</form>
					<?php
				}
			}
			pg_close($dbconn);
		?>		
	</body>	
</html>
