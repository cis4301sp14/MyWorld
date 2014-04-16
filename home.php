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

  <title>
   Login
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
	
	$frdreq = pg_query($dbconn, "select count(friendreqid) from friendreq where userid='$user_id'");
	$frdreqcount = pg_fetch_result($frdreq,0,0);
	
	  
	 $_SESSION['userid'] = pg_fetch_result($result,0,3);	
	 $_SESSION['fn'] = pg_fetch_result($result,0,0);
	 $_SESSION['ln'] = pg_fetch_result($result,0,1);
	 $_SESSION['usrn'] = pg_fetch_result($result, 0, 4);
	 }
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
            <li class="active"><a href="#">Home</a></li>
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
 

   
<div class="container">
<br /><br /><br />
	<?php 
	echo "<h2> Welcome, $dbfn $dbln </h2><br/>";	
	require 'functions.php';	
	$path = null;
	$path=profile_picture($user_id);
	$path = '<img src="'.$path. '" alt="image" width=200 height=auto />';
	echo $path;
	?>
</div>

	<div class="container">
		<div class="starter-template">
			<h1></h1> 
			<form action="upload_file.php" method="post" enctype="multipart/form-data">	
				Album Name: <input type = "Text" value = "" name = "an" id="an"><br />
				<label for="file">Filename: </label>
				<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="Upload">
			</form>
		</div>

		<div class="container">
			<?php 	?>
		</div>

	
        <div style="height: 500px; width:600px;">
	 <?php
	  include 'newsfeed.php';
	  
  	  if (!$news_feed_result ) {
    	   echo "An error occurred.\n";
    	   exit;
  	  }
	  
	  echo '<table style="width:550px;">';
  	  while ( $row = pg_fetch_row($news_feed_result )) {
    	   //echo "$row[0]<br /> <br />";
	   echo '<tr> <td>';
    	   echo "$row[2] $row[3] added photo to $row[1]";
	   echo '</td> <td>';
    	   echo '<img src="'.$row[0].'" alt="image" width="200" height="auto"/> <br /> <br />';
       	   echo '</td></tr>';	
	  }	
	  echo'</table>';
	 ?>
	</div>

	

    </div><!-- /.container -->
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
 </body>

</html>


