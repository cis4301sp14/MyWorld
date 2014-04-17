<?php
function createThumbnail($filename) {
     
    require 'config.php';
	
    $im = imagecreatefromjpeg($filename);   //added
	
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
	 
	 $resultTF = pg_query($db, "select EXISTS (select albumid from albums where userid=$user_id and albumname = '$alname')::int");
	 $result = pg_fetch_result($resultTF,0,0);
	 
	 if($result){
		$match_album_id = pg_query($db, "select albumid from albums where userid=$user_id and albumname = '$alname'");
		$albumID = pg_fetch_result($match_album_id,0,0);
	 }else{
		$maxaId = pg_query($db, "select max(albumid) from albums");
		$albumID = pg_fetch_result($maxaId,0,0)+1;
		pg_query($db, "insert into albums values ($user_id, '$alname', $albumID, $long, $lat, $PhotoId)");
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
	pg_close($db);
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

function profile_picture($userid){
	$db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');

	 $picture = pg_query($db, "select max from profilepic where userid=$userid");
 	 $picture_id = pg_fetch_result($picture,0,0);
	 
	if(!($picture_id)){
		$path = pg_query($db, "select photoname from photo where photoid=1");
		$path = pg_fetch_result($path,0,0);
	}
	else {
		$path = pg_query($db, "select photoname from photo where photoid=$picture_id");
		$path = pg_fetch_result($path,0,0);
	}
	pg_close($db);
	return $path;
}

function albumnum($userid){
	$db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');

	 $count = pg_query($db, "select count(albumid) from albums where userid=$userid");
 	 $count = pg_fetch_result($count,0,0);

	pg_close($db);
	return $count;
}

function album_ids($userid, $count){
	$db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');
	$covers = pg_query($db, "select coverid from albums where userid=$userid");
	$stack = array();
	for($i = 0; $i < $count; $i++) {
		$cover = pg_fetch_result($covers,$i,0);
		array_push($stack, $cover); 
	}
	pg_close($db);
	return $stack;
}

function album_cover($covers, $count){
	$db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');
	$path = null;
	$stack=array();
	for($i = 0; $i < $count; $i++) {
		$info = pg_query($db, "select photoname, lat, lon, albumname from photo natural join albums where photoid=$covers[$i]");
		$covername = pg_fetch_result($info,0,0);
		$latitude = pg_fetch_result($info,0,1);
		$longitude = pg_fetch_result($info,0,2);
		$albumname = pg_fetch_result($info,0,3);
		array_push($stack, $latitude, $longitude, $albumname);
		$destination = '<a href="insidealbum.php?an='.$albumname.'">';				
		$covername = $destination.'<img src="'. $covername . '" alt="image" width=150 height=auto class="img-thumbnail"/>';
		echo $covername;
	}
	pg_close($db);
	return $stack;
}



function printalbums($userid){
	$max_cnt = albumnum($userid);
	$covers=album_ids($userid, $max_cnt);
	$gps = album_cover($covers, $max_cnt);
	return $gps;
}

?>
