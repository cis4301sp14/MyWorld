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
		
		<title> Be Friends </title>
 </head>
 <body>
	<?php 
	session_start();
	$dbfn = $_SESSION['fn'];
	$dbln = $_SESSION['ln'];
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
   $frd = $_POST['person'];  
   $userid = $_SESSION['userid'];   
   $usrn = $_SESSION["user"];
   $urid = $_SESSION["userid"];
   
   
   $result = pg_query($dbconn, "select username, userid, firstn, lastn from users where username='$frd'");     
		
	$friend = pg_fetch_result($result,0,0);		
	$frdID = pg_fetch_result($result,0,1);
	$che = pg_query($dbconn, "select * from friends where userid = $frdID and friendid = $urid;");
	$che2 = pg_query($dbconn, "select * from friendreq where userid = $frdID and friendreqid = $urid;");
	$check = pg_fetch_result($che, 0,1);
	$check2 = pg_fetch_result($che2, 0,1);
	$frn = pg_fetch_result($result,0,2);
	$lsn = pg_fetch_result($result,0,3);
	
	if($urid == $check) {
		?> &nbsp&nbsp&nbsp&nbsp <?php
		echo "You are already friends with $frn $lsn.";				
	}  
	else if($urid == $check2) {
		?> &nbsp&nbsp&nbsp&nbsp <?php
		echo "You have already friend requested $frn $lsn.";	
	}
    else if ($friend == $frd && $frd != $usrn) {
		$frdId = pg_fetch_result($result,0,1);	
		$tur = pg_query($dbconn, "insert into friendreq values ($frdId, $userid)");  
		?> &nbsp&nbsp&nbsp&nbsp <?php
		echo "Friend request sent to $friend.";		
	}
	else if($frd == $usrn) {
		?> &nbsp&nbsp&nbsp&nbsp <?php
		echo "You cannot friend yourself!";
	}
    else {
		?> &nbsp&nbsp&nbsp&nbsp <?php
		echo "$frd does not exist.";		 
	}
	pg_close($dbconn);
  ?>	
 </body>
</html>


