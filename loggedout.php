#!/usr/local/bin/php

<html>
 <head>
	<meta http-equiv="refresh" content="3;URL='index.php'">
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
    <link href="jumbotron.css" rel="stylesheet">
  <title>
	LoggedOut
  </title>
 <head>

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
        <div class="navbar-collapse collapse">
          
        </div><!--/.navbar-collapse -->
      </div>
    </div>
 
  <?php 
    session_start();
    $usrn = $_SESSION['usrn'];
	
	?> <div class="container" style="margin-top:100px"><table align="center"><tr><td align="center"><?php 
	echo 'You have been successfully logged out of '.$usrn.'. Page will return to homepage in 3 seconds. </td></tr></table></div>';	
	session_unset();
  ?>

  


 </body>

</html>