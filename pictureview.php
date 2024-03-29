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

    <link rel="stylesheet" href="nivo-slider/nivo-slider.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="nivo-slider/themes/default/default.css" type="text/css" media="screen"/>
    <script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"></script>
    <script src="nivo-slider/jquery.nivo.slider.pack.js" type="text/javascript"></script>
        
 </head>
 <body>
  <?php  
	session_start();
	require 'functions.php';
	if ($_SESSION['user'] == '') {
		header("Location: index.php");
	 exit;
	}
	else {

	$usrn = $_SESSION['user'];

	$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
    $result = pg_query($dbconn, "select firstn, lastn, pw, userid, username from users natural join password where username='$usrn'");  	
    		
    $albumid = 1;
	$path = display_photos($albumid);	
	
	$count = pg_query($dbconn, "select count(photoid) from photo where albumid=$albumid");	
 	$count = pg_fetch_result($count,0,0);		
    		
	if (!$result) {
	 echo "An error has occurred.\n";
	 exit;
	}
	
		
	
	$dbfn = pg_fetch_result($result, 0, 0);
	$dbln = pg_fetch_result($result, 0, 1);
	$dbpass = pg_fetch_result($result, 0, 2);
	$user_id = pg_fetch_result($result,0,3);
	$dbusrn = pg_fetch_result($result, 0, 4);
	
	 echo "Welcome, $dbfn $dbln\n";	 
	 $_SESSION['userid'] = pg_fetch_result($result,0,3);	
	 }
	 
	$frdreq = pg_query($dbconn, "select count(friendreqid) from friendreq where userid=$user_id");
	$frdreqcount = pg_fetch_result($frdreq,0,0);
	/*if(!($picture_id)){
		$path = pg_query($dbconn, "select photoname from photo where photoid=1");
		$path = pg_fetch_result($path,0,0);
	}
	else {
		$path = pg_query($dbconn, "select photoname from photo where photoid=$picture_id");
		$path = pg_fetch_result($path,0,0);
	}*/
	
	pg_close($dbconn);	
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
            <li class="active"><a href="#">Preview</a></li>
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
		  <form class="navbar-form navbar-right" name="form" action="search.php" method = "post">            
				<div class="form-group">
					<input type="text" placeholder="Name or Username" class="form-control" name = "person" id = "person">					
				</div>
				<button type="submit" class="btn btn-success">Search</button>
		  </form>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    
<div class="slider-wrapper theme-default">    
	<div id="slider" class="nivoSlider">
			<?php			
			for($i = 0; $i< $count; $i++) {
				echo '<img src="'.$path[$i].'" data-thumb="'.$path[$i].'" alt="" title="" />';				
			}
			?>				        
	</div>
</div>

<script type="text/javascript">
$(window).load(function() {
    $('#slider').nivoSlider({effect: 'fade'});
});
</script> 

 </body>
</html>

