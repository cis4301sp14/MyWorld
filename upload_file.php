#!/usr/local/bin/php

<html>
 <head>
  <title>
   File Upload
  </title>
 </head>
<body>
<?php 

require 'functions.php';
session_start();
$usrn = $_SESSION['user'];
$alname = $_POST['an'];

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

if ((($_FILES["file"]["type"] == "image/gif")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/jpg")
	|| ($_FILES["file"]["type"] == "image/png"))
	&& in_array(strtolower($extension), $allowedExts))
	  {
	  if ($_FILES["file"]["error"] > 0)
		{
		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
		}
	  else
		{
		
		if (file_exists("upload/" . $_FILES["file"]["name"]))
		  {
		  echo $_FILES["file"]["name"] . " already exists. ";
		  }
		else
		  {
			move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
			$filename = "upload/" . $_FILES["file"]["name"];
			
			$pic = null;
			exec("./exif/Image-ExifTool-9.56/exiftool -a -u -j -g1 '$filename' ", $pic);
			$pic = join("\n", $pic);
			$g = json_decode($pic, true);
			$pos = $g[0]["Composite"]["GPSPosition"];
			$result = check_gps($pos);
			
			if($result){
				$location = decimal_lat_long($pos);
				$lat = $location[0];
				$long = $location[1];
				savePhoto($usrn, $filename, $lat, $long, $alname);
			}else{ 
			echo "Unable to upload image. No location detected. ";
			unlink(realpath($filename));
			}
			  //createThumbnail($filename);  

		  }
		}
	  }
	else
	  {
	  echo "Invalid file";
	  }
?>
<form name = 'form' method = 'post' action = 'profile.php'>
			<input type = "Submit" value = "Go Back">
</body>
</html>


