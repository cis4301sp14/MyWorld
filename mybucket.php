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
<li><a href="#">Friends</a></li>
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
require 'functions.php';								     
echo '<br>';
echo '<br>';
echo '<br>';

?>
<h3>My Bucket</h3>
<h5> Description</h5>
<ul class="list-group">
<?php
echo $urid;
$result = pg_query($dbconn, "select count(friendreqid) from friendreq where userid='$urid'");
$results = pg_fetch_result($result, 0, 0);
if(!$results){
echo '<li class="list-group-item">hello</li>';
}else{
echo 'yes';/*
while($arr = pg_fetch_array($result)){
echo 'inside';
echo '<li class="list-group-item">';
echo $arr['lon'];
echo '</li>';
*/
/*<li class="list-group-item">Cras justo odio</li>
<li class="list-group-item">Dapibus ac facilisis in</li>
<li class="list-group-item">Morbi leo risus</li>
<li class="list-group-item">Porta ac consectetur ac</li>
<li class="list-group-item">Vestibulum at eros</li>*/
}

?>
 
</ul>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

 
 </body>	
</html>