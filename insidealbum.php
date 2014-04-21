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
    
    <link rel="stylesheet" href="nivo-slider/nivo-slider.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="nivo-slider/themes/default/default.css" type="text/css" media="screen"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="nivo-slider/jquery.nivo.slider.pack.js" type="text/javascript"></script>

 
    <!-- Custom styles for this template -->
    <!--link href="css/starter-template.css" rel="stylesheet"-->
    <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

	<!--script> 
	$( document ).ready(function() {
		//$(document).ajaxComplete(function(){
        console.log( "document loaded" );
		$('#propic1').dbclick(function(){
			console.log("propic click");
			$.ajax({
			  type: "POST",
			  url: "bucket.php",
			  data: { name: "John", location: "Boston" }
			})
			  .done(function( msg ) {
				alert( "Data Saved: " + msg );
			  })
			  .fail(function() {
					return false;
				})
		});
		//});
	});
	</script-->
	<script type="text/javascript">
	
	function togglebucklist(theid)
	{
		console.log("inside");
		$.ajax({
		type: "POST",
		url: "bucket.php",
		data: {id:theid},
		success:  function( data,  textStatus,  jqXHR){
				$("#message_box").html("");
				$("#message_box").hide();
				$("#message_box").html(data);
				$("#message_box").fadeIn( "slow", function (){});
				$("#message_box").fadeOut( 2000, function (){});	
			}
		});
	}
	</script>
	
	<style type="text/css" >
#map {
    left: 0; 
    right: 0; 
    bottom:0; 
    z-index: 1; 
    overflow: hidden;
    border:solid 3px #FFFFFF; 
    border-radius:15px; 
    -moz-border-radius:15px;
    -webkit-mask-border-radius:15px;
    -webkit-border-radius:15px;
}
.success{
		border: 1px solid;
		margin: 10px 0px;
		padding: 15px 10px 15px 50px;
		background-repeat: no-repeat;
		background-position: 10px center;
		color: #4F8A10;
		background-color: #DFF2BF;
		display:none;
		 border-radius:15px; 
    -moz-border-radius:15px;
    -webkit-mask-border-radius:15px;
    -webkit-border-radius:15px;
		
	}
</style>
  <title>
   My World
  </title>
    
 </head>
 <body style="background-color:#E6E6E6;">
  <?php  
	session_start();
	require 'functions.php';
	if ($_SESSION['user'] == '') {
		header("Location: index.php");
	 exit;
	}
	else {

	$usrn = $_SESSION['user'];
	$unalbum = $_GET['un'];
	$albumid = $_GET['an'];
	
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
          <a class="navbar-brand" href="home.php">My World</a>
      </div>
      <div class="collapse navbar-collapse">
       <ul class="nav navbar-nav">
        <?php echo '<li><a href="profile.php?un='.$dbusrn.'">Preview</a></li>';?>	
        <li><a href="home.php">Home</a></li>
        <li><a href="friends.php">Friends</a></li>
	    <li><a href="friendreq.php">
	     <?php 
	 	  if(!($frdreqcount)) {echo 'Friend Requests';}			
		  else{echo 'Friend Requests ('.$frdreqcount.')';}
	     ?>
	    </a></li>	
		<li><a href="jsbucket.php"> My Bucket</a></li>		
       </ul>
	   <form class="navbar-form navbar-right" name="form" action="loggedout.php" method = "post">            
	    <button type="submit" class="btn btn-success">Sign Out, <?php echo ucwords($dbusrn);?></button>
	   </form>
	   <form class="navbar-form navbar-right" name="form" action="search.php" method = "post">   <!--this is now a test for search.php -->         
	    <div class="form-group">
	     <input type="text" placeholder="Name or Username" class="form-control" name = "person" id = "person">					
	    </div>
			<button type="submit" class="btn btn-success">Search</button>
			</form>

        </div><!--/.nav-collapse -->
      </div>
    </div>    

<table align="center" style="margin-top:30px; margin-left:375px;">
	<tr>
        <div class="container" style="margin-top:70px"></div>
	<?php 
	
	$frdinfo = pg_query($dbconn, "select userid, firstn, lastn from users where username='$unalbum'");
	$frd_id = pg_fetch_result($frdinfo,0,0);
	$frd_fn = pg_fetch_result($frdinfo,0,1);
	$frd_ln = pg_fetch_result($frdinfo,0,2);
	
	/*$path = null;
	$path = profile_picture($frd_id);
	$path = '<td ALIGN=CENTER><img src="'.$path. '" alt"image"  width=300 height=auto id="propic1" class="img-thumbnail" />';
	echo $path;*/?>	

	<td ALIGN=CENTER width="30%">	
	<h2><?php echo $frd_fn.' '.$frd_ln; ?></h2>
	<div class="slider-wrapper theme-default" style="width:500px; height:400px;">    
	<div id="slider" class="nivoSlider">
			<?php
			$path = display_photos($albumid);	
			

		$count = pg_query($dbconn, "select count(photoid) from photo where albumid=$albumid");	
			$count = pg_fetch_result($count,0,0);		
			for($i = 0; $i< $count; $i++) {
				echo '<img src="'.$path[$i].'" data-thumb="'.$path[$i].'" alt="" title="" />';			
			}
			?>				        
	</div>
</div>
	<div style="height:40px;"><div class="success" id="message_box"></div></div>
	
	</td>
	<td ALIGN=CENTER >
	<div class="col-md-4 col-md-offset-4" id="map" style="margin-left:20%; width: 500px; height: 400px;"></div>
	</td></tr>
 </table>
	
<div class="container" style="margin-top:50px"><table align="center"> 
	<tr>	
	<?php
	$ids = show_id($albumid);
	for($i = 0; $i < $count; $i++) {
		$pic = '<td ALIGN=CENTER><div ondblClick="javascript:togglebucklist('.$ids[$i].')" id="propic1"><img src="'.$path[$i]. '" alt"image"  
		width=150 height=auto id="propic1" class="img-thumbnail"/></div>';
		echo $pic;
		}
	?>
	</tr>
</table></div>
      
<script type="text/javascript">
	<?php 
	 $db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');
	 $picture = pg_query($db, "select photoname, lat, lon from photo where albumid=$albumid");
 	  $row=0; 
 	  while($pic = pg_fetch_assoc($picture)){ 
		  $location[$row]=$pic['lat'].", ".$pic['lon'];
		  $albname[$row++]=$pic['photoname']; 
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
<!--<table align="center" ><tr><td>
	<?php
	for($i = 0; $i < count($albname); $i++){	
		$covername = '<img src="'. $albname[$i] . '" alt="image" width=150 height=auto class="img-thumbnail" />';
		echo $covername;
	}
?>
</td></tr></table>-->

    </div><!-- /.container -->	 
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
$(window).load(function() {
    $('#slider').nivoSlider({effect: 'fade'});
});
</script> 
 </body>

</html>
