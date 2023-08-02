#!/usr/local/bin/php
<?php
	session_start();
	   $lat = $_SESSION['lat'];
   $long = $_SESSION['lon'];
   $gpsresult = $_SESSION['gpsresult'];
   $filename = $_SESSION['filenameUp'];


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Edit Photo</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet"> 

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
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
          <a class="navbar-brand" href="#">My World</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="profile.php">Preview</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="friends.php">Friends</a></li>
			<li><a href="friendreq.php"><?php 
				if(!($frdreqcount)) {echo 'Friend Requests';}			
				else{echo 'Friend Requests ('.$frdreqcount.')';}
			?></a></li>
			<li><a href="jsbucket.php"> My Bucket</a></li>
          </ul>
		  <form class="navbar-form navbar-right" name="form" action="loggedout.php" method = "post">            
				<button type="submit" class="btn btn-success">Sign Out, <?php echo ucwords($dbusrn);?></button>
			</form>
		  <form class="navbar-form navbar-right" name="form" action="search.php" method = "post">   <!--this is now a test for search.php -->         
				<div class="form-group">
					<input type="text" placeholder="Name or Username" class="form-control" name = "person" id = "person" required>					
				</div>
				<button type="submit" class="btn btn-success">Search</button>
			</form>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

      <div class="starter-template">
		  
        <div style="margin-top:90px; margin-right:-80px; height:100%; width: 100%;">	 
   <?php 
    session_start();
    require 'newsfeed.php';
    //include 'upload_file.php';

	if($gpsresult) {
	 $_SESSION['lat'] = '';
	 $_SESSION['long'] = '';
	 
	 $_SESSION['gpsresult']= false;	 
	 echo '<div id="map_canvas"></div>';
    } else { 
		if( $gpsresult){
    echo '<div class="myotherdiv">Unable to upload image. No location detected.</div>';
    } 
}






if ($_GET['delete']) {
     	$pic_name = $_GET['picname'];
     deletephoto($pic_name);
	$_SESSION['deleted'] = 1;

    }
	
    /*if ($_GET['rotate']) {
	$pic_name = $_GET['picname'];
     	$filename = substr($_GET['picname'], 7);
	header('Content-type: jpg');
	$source = imagecreatefromjpg($filename);
	$rotate = imagerotate($source, 90, 0);
	imagejpg($rotate);
	
    }*/




   $usrn = $_SESSION['user'];
   $usrid = $_SESSION['userid'];

   if(!$_SESSION['deletephotoname'] ='') {
    $deletename = $_SESSION['deletephotoname'];   
   }
   
   
   
   

   
   $dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
   $news_feed_result = pg_query($dbconn, "SELECT photoname, albumname, firstn, lastn, photoid FROM newsfeedview
				WHERE userid IN ( SELECT friendid FROM friends
						  WHERE userid = $usrid)
				ORDER BY photoid DESC
				LIMIT 10");


   $edit_photos_result = pg_query($dbconn, "SELECT photoname, albumname FROM photo 
						LEFT OUTER JOIN albums on albums.albumid = photo.albumid
						WHERE userid = $usrid
						ORDER BY albums.albumid, photoid");
   

    if (!$edit_photos_result) {
     echo "An error occurred.\n";
     exit;
    }
    echo '<div style = "height:300px; width;500px">';
    echo '<table style="width:560px; height:200px; overflow:auto;">';
    while ( $row = pg_fetch_row($edit_photos_result )) {    	   
     echo '<tr> <td style="width:300px;" align=left>';
     echo '</td> <td align=right>';
     $path = null;
	
     $destination = '<a href="editphoto.php?picname='.$row[0];
     $path = $destination.'" style="outline : 0; border: 0; text-decoration:none;">';
     echo $path;
     echo '<img src="'.$row[0].'" alt="image" width="200" height="auto" class="img-thumbnail"/> <br /> <br />';
     echo '</td></tr>';	
    }	
    echo'</table>';
    echo'</div>';

   ?>
  
  
  <div style="height:300px; width:600px;">
   <?php 
    session_start();
    //include 'newsfeed.php'
    if($_GET['picname'] == '' ) {
	 echo "Select a photo to edit.";
    }
    
    else if ($_SESSION['deleted'] ==1) {
     $pic_name = $_GET['picname'];   

     $pic_name = substr($_GET['picname'], 7);
     echo "<p>You have deleted $pic_name.</p>";
     echo "<p>Select another photo to edit.</p>";
	 $_SESSION['picname'] ='';
	 $_SESSION['deleted'] ='0';
    
    }
    else {
     $_SESSION['picname'] = $_GET['picname'];	
     $pic_name = $_SESSION['picname']; 
     echo '<img src="'.$pic_name.'" width="200" height="auto" class="img-thumbnail"/> <br />';
     echo '<form name="edit" method="get" action="editphoto.php">';
     echo '<input type="submit" name="delete" value="delete">';
     //echo '<input type="submit" name="rotate" value="rotate Left">';
     echo '<input type="hidden" name="picname" value="'.$pic_name.'">';
     echo '</form>';
    }
   ?>
</div>
   
  </div>
  <form action="home.php" method="post" name="goBack">
   <input type="submit" value="Go back">
  </form>
 

      
      
      
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.7.0.slim.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
  </body>
</html>














