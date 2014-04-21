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
			<a class="navbar-brand" href="home.php">My World</a>
			</div>
        <div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<?php echo '<li><a href="profile.php?un='.$dbusrn.'">Preview</a></li>';?>	
				<li><a href="home.php">Home</a></li>
				<li><a href="friends.php">Friends</a></li>
				<li><a href="friendreq.php"><?php 
				if(!($frdreqcount)) {echo 'Friend Requests';}			
				else{echo 'Friend Requests ('.$frdreqcount.')';}
			?></a></li>
			<li class="active"><a href="#">My Bucket</a></li>
			</ul>
			<form class="navbar-form navbar-right" name="form" action="loggedout.php" method = "post">            
				<button type="submit" class="btn btn-success">Sign Out, <?php echo ucwords($dbusrn);?></button>
			</form>
			<form class="navbar-form navbar-right" name="form" action="search.php" method = "post" id="search">            
				<div class="form-group">
					<input type="text" placeholder="Name or Username" class="form-control" name = "person" id = "person" required>					
				</div>
				<button type="submit" class="btn btn-success">Search</button>
			</form>
			</div><!--/.nav-collapse -->
		</div>
		</div>
    
		
		<?php
			//require 'functions.php';													
			echo '<br>';
			echo '<br>';
			echo '<br>';
			pg_close($dbconn);
			
		?>
		<div>
		<h3>My Bucket</h3>
		<h5> Double click on any picture that you will want to go to and watch it appear in your bucket.</h5>
		<h5> You can double click again to delete it from your bucket.</h5>
		<h5> Once you have gone to the places click the chekbox.</h5>
		<ul class="list-group">
		<?php

		 $db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
		 $result = pg_query($db, "select * from photo natural join bucket where userid = $urid");
		 $results = pg_fetch_result($result, 0, 0);
		 if(!$results)
			echo 'You have no must go places.';
		else{
		
			 while($arr = pg_fetch_array($result)){
				
				echo '<li class="list-group-item">';
				echo '<label><input type="checkbox"> Completed</label>';
				echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
				$path = $arr['photoname'];
				echo '<img src="';
				echo $path;
				echo '" alt"image"  width=50 height=auto class="img-thumbnail"/>';
				echo '<span class="badge">';
				echo $arr['lat'].', '.$arr['lon'];
				echo '</span></li>';
				}
				 
			}
		?>
 
</ul>
</div>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

	
	</body>	
</html>