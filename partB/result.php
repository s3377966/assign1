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
		echo '<p>'.$row[0].' '.$row[1].' '.$row[2].' '.$row[3].' '.$row[4].' '.$row[5];

		// Getting varieties for current rows wine
		$varietyQuery = "SELECT variety 
						 FROM grape_variety
						 LEFT OUTER JOIN wine_variety ON wine_variety.variety_id = grape_variety.variety_id
						 WHERE wine_variety.wine_id = ".$row[0]."
						 GROUP BY variety";
		
		$varietyResult = mysql_query($varietyQuery);
		
		while ($variety = mysql_fetch_row($varietyResult))
		{
			 echo ' '.$variety[0];
		}

		// Getting cost and current stock for current rows wine
		$inventoryQuery = "SELECT cost, on_hand
					  	   FROM inventory
					  	   WHERE wine_id = ".$row[0];

		$inventoryResult = mysql_query($inventoryQuery);
		$inventory = mysql_fetch_row($inventoryResult);

		echo ' '.$inventory[0].' '.$inventory[1];

		echo '</p>';
	}
?>
