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
     $usrn = trim($_POST['un']);
     $passw = $_POST['pw'];
     $email = $_POST['em'];
	 $firstn = ucwords(trim($_POST['fn']));
	 $lastn = ucwords(trim($_POST['ln']));	 
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
          <a class="navbar-brand" href="index.php">My World</a>
        </div>
        <div class="collapse navbar-collapse">
          <form class="navbar-form navbar-right" name="form" action="login.php" method = "post">
            <div class="form-group">
              <input type="text" placeholder="Username" class="form-control" name = "un" id = "un" required>
              <input type="password" placeholder="Password" class="form-control" name = "pw" id = "pw" required>
            </div>
            <button type="submit" class="btn btn-success">Log In</button>
          </form>
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
		?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 		
		echo "This username is already taken.</td></tr></table></div>";
	}
	$dbemail = pg_fetch_result($chkEmail,0,0);
	if ($dbemail == $email) {
		?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 		
		echo "This email is already taken.</td></tr></table></div>";
	}
	}else {
	 $maxId = pg_query($dbconn, "select max(userid) from users");
 	 $newId = pg_fetch_result($maxId,0,0) + 1;
	
	 pg_query($dbconn, "insert into users (username, email, userid, firstn, lastn) values ('$usrn','$email',$newId, '$firstn', '$lastn')");
	 pg_query($dbconn, "insert into password (userid, pw) values ($newId,'$passw')");	 
	 //pg_close($dbconn);
	 
	 
	 ?> <div class="container" style="margin-top:50px"><table align="center"><tr><td align="center"><?php 		
	 echo "Welcome to the club, $usrn. Sign in to access your profile.</td></tr></table></div>";
	}
	pg_close($dbconn);
   ?>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
  </body>
</html>





