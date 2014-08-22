<?php
	include 'connect.php';

	// Getting the id, name, year, winery name and region name for each wine
	$winesQuery = "SELECT wine_id, wine_name, year, winery_name, region_name 
				   FROM wine 
				   LEFT OUTER JOIN winery ON winery.winery_id = wine.winery_id 
				   LEFT OUTER JOIN region ON winery.region_id = region.region_id";
				   
	$region_id = $_GET["region"];

	// Filtering by users selected reigion, 1 is ALL regions so no filtering is
	// to be done if they picked all on the search page
	if ($region_id != 1)
	{
		$winesQuery .= " WHERE winery.region_id = " . $region_id;
	}

	$winesQuery .= " GROUP BY wine_id";

	$winesResult = mysql_query($winesQuery);

	while ($wine = mysql_fetch_row($winesResult))
	{
		echo '<p>'.$wine[0].' '.$wine[1].' '.$wine[2].' '.$wine[3].' '.$wine[4].' '.$row[5];

		// Getting varieties for current rows wine
		$varietyQuery = "SELECT variety 
						 FROM grape_variety
						 LEFT OUTER JOIN wine_variety ON wine_variety.variety_id = grape_variety.variety_id
						 WHERE wine_variety.wine_id = ".$wine[0]."
						 GROUP BY variety";
		
		$varietyResult = mysql_query($varietyQuery);
		
		while ($variety = mysql_fetch_row($varietyResult))
		{
			 echo ' '.$variety[0];
		}

		// Getting cost and current stock for current rows wine
		$inventoryQuery = "SELECT cost, on_hand
					  	   FROM inventory
					  	   WHERE wine_id = ".$wine[0];

		$inventoryResult = mysql_query($inventoryQuery);
		$inventory = mysql_fetch_row($inventoryResult);

		echo ' '.$inventory[0].' '.$inventory[1];

		// Getting stock sold and revenue for current rows wine
		$itemQuery = "SELECT SUM(qty), SUM(price)
					  FROM items
					  WHERE wine_id = ".$wine[0];

		$itemResult = mysql_query($itemQuery);
		$item = mysql_fetch_row($itemResult);

		echo ' '.$item[0].' '.$item[1];

		echo '</p>';
	}
?>
