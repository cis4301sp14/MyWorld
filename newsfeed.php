
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

   pg_close($dbconn);
 
  ?>