#!/usr/local/bin/php
<?php 

require 'functions.php';
session_start();
$usrn = $_SESSION['user'];
$alname = $_POST['an'];

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

if (true)/*(($_FILES["file"]["type"] == "image/gif")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/jpg")
	|| ($_FILES["file"]["type"] == "image/png"))
	&& in_array(strtolower($extension), $allowedExts))*/
	  {
	  /*if ($_FILES["file"]["error"] > 0)
		{
		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
		}
	  else
		{*/
			$db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');
			$maxPhotoId = pg_query($db, "select max(photoid) from photo");
			$PhotoId = pg_fetch_result($maxPhotoId,0,0)+1;
			move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $PhotoId . $_FILES["file"]["name"]);
			
			$filename = "upload/" . $PhotoId . $_FILES["file"]["name"];
			echo $filename;
			$_SESSION['filenameUp'] = $filenameUp;
			
			$pic = null;
			exec("./exif/Image-ExifTool-9.56/exiftool -a -u -j -g1 '$filename' ", $pic);
			
			$pic = join("\n", $pic);
			$g = json_decode($pic, true);
			$pos = $g[0]["Composite"]["GPSPosition"];
			$gpsresult = check_gps($pos);
			$_SESSION['gpsresult'] = $gpsresult;
			
			if($gpsresult){
				$location = decimal_lat_long($pos);
				$lat = $location[0];
				$long = $location[1];
				$_SESSION['lat'] = $lat;
				$_SESSION['lon'] = $long;
				savePhoto($usrn, $filename, $lat, $long, $alname);
			}else{ 
			unlink(realpath($filename));
			}
		}
	 // }
	/*else
	  {
	  echo "Invalid file";
	  }*/
?>

<html>
 <head>
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
    
  <title>
   File Upload
  </title>
 </head>
<body>

<? if($gpsresult) { ?>
	 <div id="map_canvas"></div>
<?php } else { ?>
    <div class="myotherdiv">Unable to upload image. No location detected.</div>
<?php } 
pg_close($db);
header("Location: editphoto.php")
?> 

<form name = 'form' method = 'post' action = 'home.php'>
			<input type = "Submit" value = "Go Back">
</body>
</html>


