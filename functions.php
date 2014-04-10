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

function savePhoto($usrn, $filename, $lat, $long, $alname)	{
	$db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');

	 $maxPhotoId = pg_query($db, "select max(photoid) from photo");
 	 $PhotoId = pg_fetch_result($maxPhotoId,0,0)+1;
	 
	 $match_user_id = pg_query($db, "select userid from users where username='$usrn'");
	 $user_id = pg_fetch_result($match_user_id,0,0);
	 
	 $resultTF = pg_query($db, "select EXISTS (select albumid from albums where userid=$user_id and albumname = '$alname')::int)=true");
	 $result = pg_fetch_result($resultTF,0,0);
	 
	 if($result){
		echo "inside match_album_id is there";
		$match_album_id = pg_query($db, "select albumid from albums where userid=$user_id and albumname = '$alname'");
		$albumID = pg_fetch_result($match_album_id,0,0);
	 }else{
		echo "outside match_album_id is not there ". "<br>";
		$maxAlbumId = pg_query($db, "select max(albumid) from albums");
		$albumId = pg_fetch_result($maxAlbumId,0,0)+1;
		echo "# ". $albumID . "<br>";
		pg_query($db, "insert into albums(userid, albumname, albumid, lon, lat, coverid) values ($user_id, $alname, $albumID, $long, $lat, $PhotoId)");
	 }
	 pg_query($db, "insert into photo (albumid, photoid, photoname, lat, lon) values ($albumID, $PhotoId,'$filename',$lat, $long)");
	 pg_close($db);

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

function check_gps($pos){
	if ($pos != null) 
		return true;
	else 
		return false;
}

function multiexplode ($delimiters,$string) {
    
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

function decimal_lat_long($gps){
	$position = multiexplode(array(' ', '\'','"',','),$gps);
	$degree_lat = doubleval($position[0]);
	$min_lat = doubleval($position[2]);
	$sec_lat = doubleval($position[4]);
	$decimal_lat = $degree_lat + $min_lat/60 + $sec_lat/3600;
	$decimal_lat = round($decimal_lat,6);
	if($position[6] == "S")
		$decimal_lat*=-1;
	$degree_long = doubleval($position[8]);
	$min_long = doubleval($position[10]);
	$sec_long = doubleval($position[12]);
	$decimal_long = $degree_long + $min_long/60 + $sec_long/3600;
	$decimal_long = round($decimal_long,6);
	if($position[14] == "W")
		$decimal_long*=-1;
	return array($decimal_lat,$decimal_long);
}
?>
