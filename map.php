#!/usr/local/bin/php

    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
      <script src="http://maps.google.com/maps/api/js?sensor=false"
              type="text/javascript"></script>
    </head>

    <body>
<div id="map" style="width: 500px; height: 400px;"></div>
      <script type="text/javascript">
		     <?php 
	 $db = pg_connect("host=postgres.cise.ufl.edu port=5432 dbname=atheteodb user=jclewis password=2991Uf!1855")or die('connection failed');
	 $picture = pg_query($db, "select photoid, lat, lon from photo limit 5");
 	  $row=0; 
 	  while($pic = pg_fetch_assoc($picture)){ 
		  $location[$row]=$pic['lat'].", ".$pic['lon'];
		  $photo_id[$row++]=$pic['photoid'];
	  }
	 // var_dump($location);
?>
        var locations = [
          ['Stadtbibliothek Zanklhof', 47.06976, 15.43154, 1],
          ['Stadtbibliothek dieMediathek', 47.06975, 15.43116, 2],
          ['Stadtbibliothek Gösting', 47.09399, 15.40548, 3],
          ['Stadtbibliothek Graz West', 47.06993, 15.40727, 4],
          ['Stadtbibliothek Graz Ost', 47.06934, 15.45888, 5],
          ['Stadtbibliothek Graz Süd', 47.04572, 15.43234, 6],
          ['Stadtbibliothek Graz Nord', 47.08350, 15.43212, 7],
          ['Stadtbibliothek Andritz', 47.10280, 15.42137, 8]
        ];

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 5,
          center: new google.maps.LatLng(<?php echo $location[0];?>),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;
        <?php for($i = 0; $i < 5; $i++){
         echo "var marker$i = new google.maps.Marker({
            position: new google.maps.LatLng(".$location[$i]."),
            map: map,
			photoid:$photo_id[$i]
			
          });
		google.maps.event.addListener(marker$i, 'click', (function(marker, index) {
            return function() {
              infowindow.setContent(''+$photo_id[$i]);
              infowindow.open(map, marker);
            }
          })(marker$i, $i));
          ";
        }
		?>
      </script>
    </body>
    </html>

	
	