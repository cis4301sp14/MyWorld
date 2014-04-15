#!/usr/local/bin/php

<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="shortcut icon" href="../../assets/ico/favicon.ico">

		<title>Starter Template for Bootstrap</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="starter-template.css" rel="stylesheet">
	
		<title>Friends</title>		
		
	 </head>
	<body>
		<?php 
		session_start();		
		$urid = $_SESSION['userid'];
		
		$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
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
				<li class="active"><a href="#">Friends</a></li>
				<li><a href="friendreq.php"><?php 
				if(!($frdreqcount)) {echo 'Friend Requests';}			
				else{echo 'Friend Requests ('.$frdreqcount.')';}
			?></a></li>
			</ul>
			<form class="navbar-form navbar-right" name="form" action="loggedout.php" method = "post">            
				<button type="submit" class="btn btn-success">Sign Out</button>
			</form>
			</div><!--/.nav-collapse -->
		</div>
		</div>
    
		
		<?php
			require 'functions.php';											
			$frdID = $_GET["frd"];
			$frn = $_GET["frn"];			
						
			if($_GET["fav"]) {				
				$query = pg_query($dbconn, "insert into fav(userid, friendid) values ($urid, $frdID)");							
			}						
			if($_GET["rem"]) {	
				echo "You have removed $frn's from your friends list.";
				$delete_request = pg_query($dbconn, "DELETE FROM friends WHERE userid=$urid AND friendid=$frdID");
				$delete_request2 = pg_query($dbconn, "DELETE FROM friends WHERE userid=$frdID AND friendid=$urid");				
			}
			
			$requests = pg_query($dbconn, "select userid, firstn, lastn from (select friendid as userid from friends where userid = $urid)q natural join users;");
			
			$max_req = pg_num_rows($requests);	
			?> <br /><br /><br /> <?php
			if(!($max_req)){
				$str = "You have no friends at this time";
				?> &nbsp&nbsp&nbsp&nbsp <?php
				echo $str;
				?> <br /> <?php
			}
			else {
				for($i = 0; $i < $max_req; $i++) {	
					$frdr = pg_fetch_result($requests,$i,0);
					$frn = pg_fetch_result($requests,$i,1);
					$lsn =	pg_fetch_result($requests,$i,2);
					?>

									
				
					<form name="form" method="get" action="friends.php">	
					&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp				
					<?php echo "$frn $lsn ";?>
					
					<div class="container">
	
						<?php 												
						$path = null;
						$path=profile_picture($frdr);
						$path = '<a href="friendprofile.php"><img src="'.$path. '" alt="image" width=150 height=auto />';
						echo $path;
						?>
					</div>	
					&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp					
					<input type="hidden" name="frn" value="<?php print "$frn"?>">
					<input type="hidden" name="frd" value="<?php print "$frdr"?>">
					<input type="submit" name="fav" value="favorite">	
					<input type="submit" name="rem" value="remove">		
					</form>					
					<?php
				}
			}	
			pg_close($dbconn);
		?>		
	</body>	
</html>
