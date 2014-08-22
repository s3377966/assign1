<?php
	include 'connect.php';

	// Getting the id, name, year, winery name and region name for each wine
	$winesQuery = "SELECT wine_id, wine_name, year, winery_name, region_name 
				   FROM wine 
				   LEFT OUTER JOIN winery ON winery.winery_id = wine.winery_id 
				   LEFT OUTER JOIN region ON winery.region_id = region.region_id 
				   GROUP BY wine_id";

	$winesResult = mysql_query($winesQuery);

	while ($row = mysql_fetch_row($winesResult))
	{
		echo '<p>'.$row[0].' '.$row[1].' '.$row[2].' '.$row[3].' '.$row[4].'</p>';
	}
?>
