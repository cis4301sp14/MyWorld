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
	
		<title>Friends</title>		
		
	 </head>
	<body>
		<?php 
		session_start();		
		$urid = $_SESSION['userid'];
		$dbusrn = $_SESSION['usrn'];		
		
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
				<?php echo '<li><a href="profile.php?un='.$dbusrn.'">Preview</a></li>';?>	
				<li><a href="home.php">Home</a></li>
				<li class="active"><a href="#">Friends</a></li>
				<li><a href="friendreq.php"><?php 
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
    
		
		<?php
			require 'functions.php';											
			$frdID = $_POST["frd"];
			$frn = $_POST["frn"];			
			
			if($_POST["fav"]) {				
				$query = pg_query($dbconn, "insert into fav(userid, friendid) values ($urid, $frdID)");							
			}						
			else if($_POST["rem"]) {	
				echo "You have removed $frn's from your friends list.";
				$delete_request = pg_query($dbconn, "DELETE FROM friends WHERE userid=$urid AND friendid=$frdID");
				$delete_request2 = pg_query($dbconn, "DELETE FROM friends WHERE userid=$frdID AND friendid=$urid");				
			}
			
			$requests = pg_query($dbconn, "select userid, firstn, lastn, username from (select friendid as userid from friends where userid = $urid)q natural join users;");
			
			$max_rows = pg_num_rows($requests);	
			?> <div class="container" style="margin-top:60px"></div> <?php
			if(!($max_rows)){
				?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 		
				echo "You have no friends at this time.</td></tr></table></div>";
			
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
							
							<form class="navbar-form navbar-right" name="form" action="friends.php" method = "post">
							<ul style="list-style: none;"><li>												
							<label><?php echo "$frn $lsn";?></label>										
							<div class="container" style="width: 175px">
	
							<?php 												
							$path = null;
							$path=profile_picture($frdr);
							$destination = '<a href="profile.php?un='.$frun;
							$path = $destination.'"><img src="'.$path. '" alt="image" width=150 height=auto class="img-circle" />';
							echo $path;
							?>
																	
							<input type="hidden" name="frn" value="<?php print "$frn"?>">
							<input type="hidden" name="frd" value="<?php print "$frdr"?>"><br/>
							<button type="submit" class="btn btn-info btn-xs" name="fav" value="no">Favorite</button>	
							<button type="submit" class="btn btn-info btn-xs" name="rem" value="go">Remove</button>
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
