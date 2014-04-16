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
		
		<title> Search </title>
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
            <li><a href="profile.php">Preview</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="friends.php">Friends</a></li>
			<li><a href="friendreq.php"><?php 
				if(!($frdreqcount)) {echo 'Friend Requests';}			
				else{echo 'Friend Requests ('.$frdreqcount.')';}
			?></a></li>
          </ul>
		  <form class="navbar-form navbar-right" name="form" action="loggedout.php" method = "post">            
				<button type="submit" class="btn btn-success">Sign Out, <?php echo ucwords($dbusrn);?></button>
			</form>
		  <form class="navbar-form navbar-right" name="form" action="search.php" method = "post">   <!--this is now a test for search.php -->         
				<div class="form-group">
					<input type="text" placeholder="Name or Username" class="form-control" name = "person" id = "person">					
				</div>
				<button type="submit" class="btn btn-success">Search</button>
			</form>
        </div><!--/.nav-collapse -->
      </div>
    </div>
	<br /> <br /> <br />
 
	<?php  	
	require "functions.php";
	$var = $_POST['person'];  	
	$arr = explode(' ',trim($var));
	$frd = $arr[0];
	$frdU = ucwords($arr[0]);
	$userid = $_SESSION['userid'];   
	$usrn = $_SESSION["user"];
	$urid = $_SESSION["userid"];  
	
	$c = count($arr);		
	$pln = strtolower($arr[1]);
	
	$result = pg_query($dbconn, "select username, userid, firstn, lastn from users where username like '$frd%' or firstn like '$frd%' or firstn like '$frdU%'");
	$max_rows = pg_num_rows($result);
	
	if($c == 1 && $max_rows) {		 
		for($i = 0; $i < $max_rows; $i++) {	
					$uid = pg_fetch_result($result,$i,1);
					$fn = pg_fetch_result($result,$i,2);
					$ln =	pg_fetch_result($result,$i,3);
					$un = pg_fetch_result($result,$i,0);
					?>									
				
					<form name="form" method="get" action="search.php">	
					&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp				
					<?php echo "$fn $ln ";?>
					
					<div class="container">
	
						<?php 												
						$path = null;
						$path=profile_picture($uid);
						$destination = '<a href="friendprofile.php/?frdun='.$un;
						$path = $destination.'"><img src="'.$path. '" alt="image" width=150 height=auto />';
						echo $path;
						?>
					</div>	
					&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp					
					<input type="hidden" name="frn" value="<?php print "$fn"?>">
					<input type="hidden" name="frd" value="<?php print "$uid"?>">
					<input type="submit" name="fav" value="view profile">						
					</form>					
					<?php
				}
	}
	else if($c == 2) {	
		for($i = 0; $i < $max_rows; $i++) {	
					$uid = pg_fetch_result($result,$i,1);
					$fn = pg_fetch_result($result,$i,2);
					$ln =	pg_fetch_result($result,$i,3);
					$lnL = strtolower($ln);
					$un = pg_fetch_result($result,$i,0);
					
					if($lnL == $pln) {					
					?>
					<form name="form" method="get" action="search.php">	
					&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp				
					<?php echo "$fn $ln ";?>
					
					<div class="container">
	
						<?php 												
						$path = null;
						$path=profile_picture($uid);
						$destination = '<a href="friendprofile.php/?frdun='.$un;
						$path = $destination.'"><img src="'.$path. '" alt="image" width=150 height=auto />';
						echo $path;
						?>
					</div>	
					&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp					
					<input type="hidden" name="frn" value="<?php print "$fn"?>">
					<input type="hidden" name="frd" value="<?php print "$uid"?>">
					<input type="submit" name="fav" value="view profile">						
					</form>					
					<?php
					}
					else {
						
					}
				}
	}
	
	pg_close($dbconn);
  ?>	
 </body>
</html>


