
<html>
 <head>
  <title>
   My World
  </title>
 </head>

 <body>
  
  <?php
   session_start();
   $usrn = $_SESSION['user'];
   $usrid = $_SESSION['userid'];
   $dbconn = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855") or die('connection failed');
   $news_feed_result = pg_query($dbconn, "SELECT photoname, albumname, firstn, lastn FROM newsfeedview
				WHERE userid IN ( SELECT friendid FROM friends
						  WHERE userid = $usrid)
				ORDER BY photoid DESC
				LIMIT 10");
   /*if (!$news_feed_result ) {
    echo "An error occurred.\n";
    exit;
   }

   while ( $row = pg_fetch_row($news_feed_result )) {
    //echo "$row[0]<br /> <br />";
    echo "$row[2] $row[3] added photo to $row[1]";
    echo '<img src="'.$row[0].'" alt="image" width="200" height="auto"/> <br /> <br />';
    
   }*/

  ?>
 </body>
 

</html>