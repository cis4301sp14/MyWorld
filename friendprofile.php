#!/usr/local/bin/php
<!DOCTYPE html>
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
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script> $( document ).ready(function() {
        console.log( "document loaded" );
		$('#propic1').click(function(){
			console.log("propic click");
			$.ajax({
			  type: "POST",
			  url: "some.php",
			  data: { name: "John", location: "Boston" }
			})
			  .done(function( msg ) {
				alert( "Data Saved: " + msg );
			  });
		return false;
		});
		
    });
	</script>
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
	$frd_un = $_GET['frdun'];
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
			<li style="padding-left:250px" ><a href="friends.php">Sign Out?</a></li>
          </ul>
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
	$frdinfo = pg_query($dbconn, "select userid, firstn, lastn from users where username='$frd_un'");
	$frd_id = pg_fetch_result($frdinfo,0,0);
	$frd_fn = pg_fetch_result($frdinfo,0,1);
	$frd_ln = pg_fetch_result($frdinfo,0,2);
	echo $frd_fn ."   ". $frd_ln."!!!!!!!!!!!!!!!<br>";
	$path = null;
	$path = profile_picture($frd_id);
	$path = '<img src="'.$path. '" alt"image"  width=300 height=auto id="propic1" />';
	echo $path;
	?>

	<h2><?php echo $frd_fn.' '.$frd_ln; ?></h2>
</div>

	

<div class="container">
	<?php 		
	printalbums($frd_id);

	?>
</div>	

    </div><!-- /.container -->
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
 </body>

</html>
