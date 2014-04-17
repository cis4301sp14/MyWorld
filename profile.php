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

    <title>My World</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
    <script src="http://maps.google.com/maps/api/js?sensor=false"
              type="text/javascript"></script>
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
	$frd_un = $_GET['un'];
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
				<br/><br/><br/>
			  <?php 
				echo $frd_un.' '.$dbusrn;
				if($frd_un != $dbusrn) {
					echo '<li class="active"><a href="profile.php?un='.$dbusrn.'">Preview</a></li>
					<li><a href="home.php">Home</a></li>
					<li><a href="friends.php">Friends</a></li>';
					}			
				else{
					echo '<li><a href="profile.php?un='.$frd_un.'">Preview</a></li>
					<li><a href="home.php">Home</a></li>
					<li class="active"><a href="friends.php">Friends</a></li>';
					}
			?>
			
			<li><a href="friendreq.php"><?php 
				if(!($frdreqcount)) {echo 'Friend Requests';}			
				else{echo 'Friend Requests ('.$frdreqcount.')';}
			?></a></li></ul>
			<form class="navbar-form navbar-right" name="form" action="loggedout.php" method = "post">            
			<button type="submit" class="btn btn-success">Sign Out, <?php echo ucwords($dbusrn);?></button>
			</form>
			<form class="navbar-form navbar-right" name="form" action="search.php" method = "post">            
			<div class="form-group">
			<input type="text" placeholder="Name or Username" class="form-control" name = "person" id = "person">					
			</div>
			<button type="submit" class="btn btn-success">Search</button>
			</form>

        </div><!--/.nav-collapse -->
      </div>
    </div>
  
<div class="container" style="padding-top: 150px">  
<table align="center" >
	<tr>
	<?php 
	require 'functions.php';
	
	$frdinfo = pg_query($dbconn, "select userid, firstn, lastn from users where username='$frd_un'");
	$frd_id = pg_fetch_result($frdinfo,0,0);
	$frd_fn = pg_fetch_result($frdinfo,0,1);
	$frd_ln = pg_fetch_result($frdinfo,0,2);
	
	$path = null;
	$path = profile_picture($frd_id);
	$path = '<td ALIGN=CENTER><img src="'.$path. '" alt"image"  width=300 height=auto id="propic1" class="img-thumbnail" />';
	echo $path;
	
	?>
	<h2><?php echo $frd_fn.' '.$frd_ln; ?></h2>
	</td>
	<td ALIGN=CENTER>
	<div class="col-md-4 col-md-offset-4" id="map" style="width: 500px; height: 400px;"></div>
	</td></tr>
 </table>
</div>	

<div class="container" style="margin-top:50px"><table align="center"> 
	<tr>
		<td ALIGN=CENTER>
	<div class="container">
		<?php printalbums($frd_id);	?>
	</div>
	</td>
	</tr>
</table></div>
 <script type="text/javascript">
	<?php 
	 $db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');
	 $picture = pg_query($db, "select albumname, lat, lon from albums where userid = $frd_id");
 	  $row=0; 
 	  while($pic = pg_fetch_assoc($picture)){ 
		  $location[$row]=$pic['lat'].", ".$pic['lon'];
		  $albname[$row++]=$pic['albumname'];
	  }
	?>
      

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5,
          center: new google.maps.LatLng(<?php echo $location[0];?>),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;
        <?php for($i = 0; $i < count($albname); $i++){
         echo "var marker$i = new google.maps.Marker({
            position: new google.maps.LatLng(".$location[$i]."),
            map: map,
			albumname:\"$albname[$i]\"
			
          });
		google.maps.event.addListener(marker$i, 'click', (function(marker, index) {
            return function() {
              infowindow.setContent(\"$albname[$i]\");
              infowindow.open(map, marker);
            }
          })(marker$i, $i));
          ";
        }
		?>
      </script>

    </div><!-- /.container -->
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
 </body>

</html>
