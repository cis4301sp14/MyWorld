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
	$dbusrn = pg_fetch_result($result, 0, 4);
	
	
	
	 echo "Welcome, $dbfn $dbln\n";	 
	 $_SESSION['userid'] = pg_fetch_result($result,0,3);	
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
          <a class="navbar-brand" href="#"><?php echo $dbfn.' '.$dbln; ?></a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#friends">Friends</a></li>
			<li><a href="friendreq.php">Friends Request</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
	<div class="starter-template">
   <form name="form" method="post" action="friendreq.php">
   <input type="submit" value="Friend Requests"> 
   </form>
   <form name="form" method="post" action="befriends.php">
   <input type="Text" value="" name="person" id="person">
	<input type="submit" value="Friend">
   </form>
	<form name="form" method="post" action="loggedout.php">
	<input type="submit" value="Signout">
	</form>
   	<form action="upload_file.php" method="post" enctype="multipart/form-data">
	
	Album Name: <input type = "Text" value = "" name = "an" id="an">
	<!--<select name="dropdown">
		<option value="">item1</option>
		<option value="">item2</option>
		<option value="">item3</option>
	</select>-->

	<br />
	   <label for="file">Filename:</label>
	<input type="file" name="file" id="file"><br>
	<input type="submit" name="submit" value="Upload">
	</form>
	</div>
	
	<div class="container">

      <div class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
      </div>

    </div><!-- /.container -->
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
 </body>

</html>

