#!/usr/local/bin/php

<html>
	
 <head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Profile</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

  <title>
   My World
  </title>
    
 </head>
 <body>
  <?php  
	session_start();
	if ($_SESSION['user'] == '') {
		header("Location: index.php");
	 exit;
	}
	else {

	$usrn = $_SESSION['user'];

	$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
    $result = pg_query($dbconn, "select firstn, lastn, pw, userid, username from users natural join password where username='$usrn'");
	
	
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
	 
	$frdreq = pg_query($dbconn, "select count(friendreqid) from friendreq where userid='$user_id'");
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
            <li class="active"><a href="#">Preview</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="friends.php">Friends</a></li>
			<li><a href="friendreq.php"><?php 
				if(!($frdreqcount)) {echo 'Friend Requests';}			
				else{echo 'Friend Requests ('.$frdreqcount.')';}
			?></a></li>			
          </ul>
		  <form class="navbar-form navbar-right" name="form" action="loggedout.php" method = "post">            
				<button type="submit" class="btn btn-success">Sign Out, <?php echo $dbfn.' '.$dbln;?></button>
			</form>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    
<div class="container">
	
	<?php 
	require 'functions.php';
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	$path = null;
	$path=profile_picture($user_id);
	$path = '<img src="'.$path. '" alt="image" width=300 height=auto />';
	echo $path;
	?>
	<h2><?php echo $dbfn.' '.$dbln; ?></h2>
</div>

	

<div class="container">
	<?php 		
	printalbums($user_id);
	?>
</div>	

    </div><!-- /.container -->
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
 </body>

</html>



<html>
 <head>
	  <style>
      #map_canvas {
        width: 500px;
        height: 400px;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script>
      function initialize() {
        var map_canvas = document.getElementById('map_canvas');
        var myLatlng = new google.maps.LatLng(<?php echo $lat.', '.$long; ?>);
        
        var map_options = {
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          center: myLatlng
        }
        var map = new google.maps.Map(map_canvas, map_options);
        var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title: 'Hello World!'
		});

      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    
  <title>
   File Upload
  </title>
 </head>
<body>

	 <div id="map_canvas"></div>

</body>
</html>
