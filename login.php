#!/usr/local/bin/php


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Treasure Hunters - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

   
  </head>

  <body>

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
            <li class="active"><a href="index.php">Home</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container" style="padding-top:200px;">
		
	<p> Username or Password is incorrect.</p>	
   <form name="form" action="login.php" method = "post">           
        <input type="text" placeholder="Username" name = "un" id = "un" required>
          <input type="password" placeholder="Password" name = "pw" id = "pw" required>
     
           <button type="submit">Sign in</button>
        </form>

	<?php 

	session_start();
	
     $usrn = $_POST['un'];
     $passw = $_POST['pw'];
	 $_SESSION['user'] = $usrn;
	 	 
  
   $dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
   $result = pg_query($dbconn, "select username, pw, userid from users natural join password where username='$usrn'");
   pg_close($dbconn);
   	if (!$result) {
	 echo "An error has occurred.\n";
	 exit;
	}
	
	$dbusrn = pg_fetch_result($result, 0, 0);
	$dbpass = pg_fetch_result($result, 0, 1);
	
	
	if ($dbusrn != $usrn || $dbpass != $passw) {
	 exit;
	}
		
	else {
	   $_SESSION['userid'] = pg_fetch_result($result,0,2);
		 
	header("Location: home.php");
	
	}
	exit;
	
  ?>
 


    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
  </body>
</html>


