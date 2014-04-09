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
print( "Hello World<br />"); 
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
//phpinfo();
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
		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		echo "Type: " . $_FILES["file"]["type"] . "<br>";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		
		if (file_exists("upload/" . $_FILES["file"]["name"]))
		  {
		  echo $_FILES["file"]["name"] . " already exists. ";
		  }
		else
		  {
		  move_uploaded_file($_FILES["file"]["tmp_name"],
		  "upload/" . $_FILES["file"]["name"]);
		  echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
		  $filename = $_FILES["file"]["name"];

		  $userid = 123;
		  //$alname = $_POST['an'];
		  //$albumID = getAlbumID($userid, $alname); 
		  //echo $albumID . "!!!!!!!!!!!!!!!!" . <br />;
		  //$exif = exif_read_data($filename, 'IFDO');

		  // foreach ($exif as $key => $section) {
			// foreach ($section as $name => $val) {
				// echo "$key.$name: $val<br />\n";
			// }
		  // }
		  //$lon = getGps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
		  //$lat = getGps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);
		  //var_dump($lat, $lon);
		  createThumbnail($filename);  
		  $albumID = 123;
		  $location = 3;
		  savePhoto($albumID, $filename, $location);
		  }
		}
	  }
	else
	  {
	  echo "Invalid file";
	  }
?>
</body>
</html>


