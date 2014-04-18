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
 <body style="background-color:#E6E6E6;">
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
	<div class="container" style="margin-top:60px"></div>
 
	<?php  	
	require "functions.php";
	$var = $_POST['person'];  	
	$arr = explode(' ',trim($var));
	$frd = $arr[0];
	$frdU = ucwords($arr[0]);
	$userid = $_SESSION['userid'];   
	$usrn = $_SESSION["user"]; 	
	
	if($_POST['fir']) {
		$frid = $_POST["friendid"];
		$frn = $_POST["friendname"];
		$fr = $_POST["name"];
		
		$fd = pg_query($dbconn, "select userid from friends where friendid = $urid and userid = $frid");		
		$friend = pg_fetch_result($fd,0,0);
		$fr = pg_query($dbconn, "select userid from friendreq where friendreqid = $urid and userid = $frid");
		$friendr = pg_fetch_result($fr,0,0);
		
		if($frid == $userid) {
			?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 
			echo "You cannot friend yourself.</td></tr></table></div>";	
		}
		else if($friendr) {
			?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 
			echo "You have already sent a friend request to $frn.</td></tr></table></div>";	
		}

		else if(!$friend) {
			$tur = pg_query($dbconn, "insert into friendreq values ($frid, $urid)"); 
			?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 		
			echo "Friend request sent to $frn.</td></tr></table></div>";
		}
		else {
			?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 
			echo "You are already friends with ".ucwords($frn).".</td></tr></table></div>";	
		}
		exit;	
	}
	
	$c = count($arr);		
	$pln = strtolower($arr[1]);
	
	$result = pg_query($dbconn, "select username, userid, firstn, lastn from users where username like '$frd%' or firstn like '$frd%' or firstn like '$frdU%'");
	$max_rows = pg_num_rows($result);
	
	if($arr[0] == "") {
		?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 
		echo "Please enter a name or username.</td></tr></table></div>";	
	}
	else if($c == 1 && $max_rows) {
		echo '<div class="container" style="margin-top:50px"><table align="center">';		 
		$tmp = 0;
		echo '<tr>';
			for($row = 0; $row < ($max_rows/5); $row++) {
					
				for($col = 0; $col < 5 && $col != $max_rows; $col++) {	
					$uid = pg_fetch_result($result,$tmp+$col,1);
					$fn = pg_fetch_result($result,$tmp+$col,2);
					$ln =	pg_fetch_result($result,$tmp+$col,3);
					$un = pg_fetch_result($result,$tmp+$col,0);

					if($uid) {
						?>
														
						<td ALIGN=CENTER>			
						
						<form class="navbar-form navbar-right" name="form" action="search.php" method = "post">
						<ul style="list-style: none;"><li>
						<button type="submit" class="btn btn-info btn-xs" name="fir" value="go">Friend</button>						
						<label><?php echo "$fn $ln";?></label>										
						<div class="container" style="width: 175px">
	
						<?php 												
						$path = null;
						$path=profile_picture($uid);
						$destination = '<a href="profile.php?un='.$un;
						$path = $destination.'" style="outline : 0; border: 0; text-decoration:none;"><img src="'.$path. '" alt="image" width=150 height=auto class="img-circle" />';
						echo $path;
						?>					
								
						<input type="hidden" name="friendid" value="<?php print "$uid"?>">
						<input type="hidden" name="friendname" value="<?php print "$fn"?>"><br/>
						<input type="hidden" name="name" value="<?php print "$frd"?>"><br/>
												
						</div>															
						</li></ul></td></form>					
						<?php
					}
				}
			echo '</tr>';
			$tmp = $tmp+5;
			}		
		echo '</table></div>';
	}
	else if($c == 2) {	
		echo '<table align="center">';		 
		$tmp = 0;
			for($row = 0; $row < ($max_rows/5); $row++) {
				echo '<tr>';	
				for($col = 0; $col < ($tmp+5) && $col != $max_rows; $col++) {
					$uid = pg_fetch_result($result,$tmp+$col,1);
					$fn = pg_fetch_result($result,$tmp+$col,2);
					$ln =	pg_fetch_result($result,$tmp+$col,3);
					$lnL = strtolower($ln);
					$un = pg_fetch_result($result,$tmp+$col,0);
					
					if($lnL == $pln && $uid) {					
					?>
						<td ALIGN=CENTER>
						<form name="form" method="get" action="search.php">	
						<ul style="list-style: none;"><li>
						<input type="submit" name="fir" value="friend">

						<label><?php echo "$fn $ln ";?></label>					
						<div class="container" style="width: 175px">
	
						<?php 												
						$path = null;
						$path=profile_picture($uid);
						$destination = '<a href="profile.php?un='.$un;
						$path = $destination.'" style="outline : 0; border: 0; text-decoration:none;"><img src="'.$path. '" alt="image" width=150 height=auto />';
						echo $path;
						?>
						
						<br/>			
						<input type="hidden" name="friendid" value="<?php print "$uid"?>">
						<input type="hidden" name="friendname" value="<?php print "$fn"?>"><br/>					
						<input type="hidden" name="name" value="<?php print "$frd"?>"><br/>												
						</div>						
						</form></li></ul></td>							
						<?php
					}
					else {
						
					}
				}
			echo '</tr>';	
			$tmp = $tmp+5;
			}		
	echo '</table>';	
	}
	
	pg_close($dbconn);
  ?>	
 </body>
</html>


