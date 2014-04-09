<?php
function createThumbnail($filename) {
     
    require 'config.php';
	// if (exif_imagetype($filename) != IMAGETYPE_GIF) {
		 // $im = imagecreatefromgif($path_to_image_directory . $filename);
		 // echo "!!!!!!!!!!gif";
	// }   else if(exif_imagetype($filename) != IMAGETYPE_JPEG) {
        // $im = imagecreatefromjpeg($path_to_image_directory . $filename); echo "!!!!!!!!!!jpeg";
    // }  else if (exif_imagetype($filename) != IMAGETYPE_PNG) {
        // $im = imagecreatefrompng($path_to_image_directory . $filename); echo "!!!!!!!!!!png";
    // }
	
    $im = imagecreatefromjpeg($path_to_image_directory . $filename);   //added
	
    $ox = imagesx($im);
    $oy = imagesy($im);
     
    $nx = $final_width_of_image;
    $ny = floor($oy * ($final_width_of_image / $ox));
     
    $nm = imagecreatetruecolor($nx, $ny);
     
    imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
     
    if(!file_exists($path_to_thumbs_directory)) {
      if(!mkdir($path_to_thumbs_directory)) {
           die("There was a problem. Please try again!");
      } 
       }
 
    imagejpeg($nm, $path_to_thumbs_directory . $filename);
    $tn = '<img src="' . $path_to_thumbs_directory . $filename . '" alt="image" />';
    $tn .= '<br />Congratulations. Your file has been successfully uploaded, and a thumbnail has been created.';
    echo $tn;
}

function savePhoto($albumID, $filename, $location)	{
	
	require 'config.php';
	
	$tn =$path_to_thumbs_directory . $filename;
	$db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');

	 $maxPhotoId = pg_query($db, "select max(photoid) from photo");
 	 $PhotoId = pg_fetch_result($maxPhotoId,0,0)+1;
	 pg_query($db, "insert into photo (albumid, photoid, photoname, lon, lat, date) values ($albumID, $PhotoId,'$tn',NULL,NULL,NULL)");
	 //pg_close($db);
}
	
function getAlbumID($userid, $alname){

	$db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
    $AId = pg_query($db, "select EXISTS (SELECT albumid FROM albums WHERE userid = $userid and albumname = '$alname')::int as answer");
	//pg_close($db);
	
	$albumname = pg_fetch_result($AId,0,0);
	echo $albumname;
	echo $AId;
	if ($AId == $albumname)    
	echo "he";
}

function addFriend($userid,$frdID,$type) {
	$db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
    if ($type=="accept") {
        $query = pg_query($db, "insert into friends(userid, friendid) values ($userid, $frdID)");
		$query2 = pg_query($db, "insert into friends(userid, friendid) values ($frdID, $userid)");
        $delete_request = pg_query($db, "DELETE FROM friendreq WHERE userid=$frdID AND friendreqid=$userid");        
    }
    if ($type=="decline") {
        $delete_request = pg_query($db, "DELETE FROM friendreq WHERE userid=$frdID AND friendreqid=$userid"); 
    }
	//pg_close($db);
}
?>














