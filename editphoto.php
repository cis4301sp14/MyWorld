#!/usr/local/bin/php

<!DOCTYPE html>
 <head>
  <title>Edit Photos</title>	 
 </head>
 <body>
  <div style="margin-top:90px; margin-right:-80px; height:100%; width: 100%;">	 
   <?php 
    session_start();
    require 'newsfeed.php';


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
 
 </body>
</html>
