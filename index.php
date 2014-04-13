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

    <title>My World</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
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
        <div class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" name="form" action="login.php" method = "post">
            <div class="form-group">
              <input type="text" placeholder="Username" class="form-control" name = "un" id = "un" required>
              <input type="password" placeholder="Password" class="form-control" name = "pw" id = "pw" required>
            </div>
            <button type="submit" class="btn btn-success">Log In</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron" style="padding-left: 1080px;">
      <div class="container" style="padding-left: 20px;">
	<div id="form-container">
      	<form name='form' method='post' action='welcome.php'>
	<ul>
        <li><label>First Name:</label><input type="Text" value="" name="fn" id="fn" required></li>      	

  	<li><label>Last Name:</label><input type="Text" value="" name="ln" id="ln" required></li>   	

  	<li><label>Username:</label><input type="Text" value="" name="un" id="un" required></li>   	

  	<li><label>Password:</label><input type="password" value="" name="pw" id="pw" required></li>
   	
  	<li><label>Password:</label><input type="password" value="" name="vpw" id="vpw" required></li>
   	
  	<li><label>E-mail:</label><input type="email" value="" name="em" id="em" required></li>

        <li><label></label><button type="submit" class="btn btn-success">Sign up</button></li>
   	</ul>
	        
  	</form> 
	</div>
      </div>
    </div>
      <hr>
      <?php include("Footer.php"); ?>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

