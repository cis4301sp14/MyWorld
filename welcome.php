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

    <title>Welcome</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

   <?php 
   if ($_POST['pw'] != $_POST['vpw'])
     {
       echo "passwords were not the same";
       exit;       
     }
   if ($_POST['un'] == 'username' || $_POST['pw'] == 'password' || $_POST['em'] == 'email') {
       echo "Please input new items intos the corresponding fields";
       exit;	
     }
   else {
     $usrn = $_POST['un'];
     $passw = $_POST['pw'];
     $email = $_POST['em'];
	 $firstn = $_POST['fn'];
	 $lastn = $_POST['ln'];
     }
  ?>
   
   
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
          <a class="navbar-brand" href="index.php">Project name</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container" style="padding-top:100px;>

	<?php
   $dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
    $chkUsrn = pg_query($dbconn, "select username from users where username='$usrn'");
    $chkEmail = pg_query($dbconn, "select email from users where email='$email'");
	$dbusrn = pg_fetch_result($chkUsrn,0,0);
	if($dbusrn == $usrn || $dbemail == $email){
	if ($dbusrn == $usrn) {
	 echo "This username is already taken.\n";
	}
	$dbemail = pg_fetch_result($chkEmail,0,0);
	if ($dbemail == $email) {
	 echo "This email is already taken.\n";
	}
	}else {
	 $maxId = pg_query($dbconn, "select max(userid) from users");
 	 $newId = pg_fetch_result($maxId,0,0) + 1;
	
	 pg_query($dbconn, "insert into users (username, email, userid, firstn, lastn) values ('$usrn','$email',$newId, '$firstn', '$lastn')");
	 pg_query($dbconn, "insert into password (userid, pw) values ($newId,'$passw')");	 
	 //pg_close($dbconn);
	 
	 echo "Welcome to the club, $usrn";
	}
	pg_close($dbconn);
   ?>
  <form name='form' method='post' action='index.php'>
   <input type="Submit" value="Back">
  </form>




    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
  </body>
</html>





