
  <?php
  
   $usrn = $_SESSION['user'];
   $usrid = $_SESSION['userid'];
   if(!$_SESSION['deletephotoname'] ='') {
    $deletename = $_SESSION['deletephotoname'];   
   }
   
   $dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
   $news_feed_result = pg_query($dbconn, "SELECT photoname, albumname, firstn, lastn FROM newsfeedview
				WHERE userid IN ( SELECT friendid FROM friends
						  WHERE userid = $usrid)
				ORDER BY photoid DESC
				LIMIT 10");


   $edit_photos_result = pg_query($dbconn, "SELECT photoname, albumname FROM photo 
						LEFT OUTER JOIN albums on albums.albumid = photo.albumid
						WHERE userid = $usrid
						ORDER BY albums.albumid, photoid");
   
   



   function deletephoto($photoname){
   	$dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")
			or die('connection failed');
	$getalbum = pg_query("SELECT albumid, photoid FROM photo WHERE photoname ='$photoname'");
	$albumid = pg_fetch_result($getalbum, 0, 0);
	$photoid = pg_fetch_result($getalbum, 0, 1);

	pg_query("DELETE FROM photo WHERE photoname='$photoname'");
	
	$checkalbum = pg_query("SELECT count(albumid) FROM photo WHERE albumid = $albumid");
	$count = pg_fetch_result($checkalbum, 0, 0);
	if($count == 0){
	 pg_query("DELETE FROM albums WHERE albumid = $albumid");
	}
	else {
	 $checkcoverphoto = pg_query("SELECT coverid FROM albums WHERE albumid = $albumid");
	 $coverid = pg_fetch_result($checkcoverphoto , 0, 0);

	 if ($coverid == $photoid) {
	  pg_query("UPDATE albums 
		   SET coverid = (SELECT MAX(photoid) FROM photo WHERE albumid = $albumid) 
		   WHERE albumid = $albumid");
	  pg_query("UPDATE albums 
		   SET lon = (SELECT lon FROM photo 
				  WHERE photoid = (SELECT MAX(photoid) FROM photo WHERE albumid = $albumid) ) 
		   WHERE albumid = $albumid");
	  pg_query("UPDATE albums 
		   SET lat = (SELECT lat FROM photo 
				WHERE photoid = (SELECT MAX(photoid) FROM photo WHERE albumid = $albumid) ) 
		   WHERE albumid = $albumid");
	 }
	}

   }

   
  ?>
