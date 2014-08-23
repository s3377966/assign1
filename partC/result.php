<?php
	require_once("MiniTemplator.class.php");
	include 'connect.php';

	$region_id = $_GET["region"];

	// Checking for non-numeric input by user
	if (!is_numeric($region_id))
	{
		die("Invalid Region ID");
	}

	$t = new MiniTemplator;
	$ok = $t->readTemplateFromFile("result.htm");

	// Checking for successfully loading template
	if (!$ok)
	{
		die("MiniTemplator failed to read result.htm");
	}

	// Getting the id, name, year, winery name and region name for each wine
	$winesQuery = "SELECT wine_id, wine_name, year, winery_name, region_name 
				   FROM wine 
				   LEFT OUTER JOIN winery ON winery.winery_id = wine.winery_id 
				   LEFT OUTER JOIN region ON winery.region_id = region.region_id";

	// Filtering by users selected reigion, 1 is ALL regions so no filtering is
	// to be done if they picked all on the search page
	if ($region_id != 1)
	{
		$winesQuery .= " WHERE winery.region_id = " . $region_id;
	}

	$winesQuery .= " GROUP BY wine_id";

	$winesResult = mysql_query($winesQuery);

	// Checking for no results
	if (mysql_num_rows($winesResult) == 0)
	{
		die("No records match your search criteria");
	}

	while ($wine = mysql_fetch_row($winesResult))
	{
		$t->setVariable("wineName",$wine[1]);
		$t->setVariable("year", $wine[2]);
		$t->setVariable("winery", $wine[3]);
		$t->setVariable("region", $wine[4]);

		// Getting varieties for current rows wine
		$varietyQuery = "SELECT variety 
						 FROM grape_variety
						 LEFT OUTER JOIN wine_variety ON wine_variety.variety_id = grape_variety.variety_id
						 WHERE wine_variety.wine_id = ".$wine[0]."
						 GROUP BY variety";
		
		$varietyResult = mysql_query($varietyQuery);
		
		while ($variety = mysql_fetch_row($varietyResult))
		{
			 $t->setVariable("variety", $variety[0]);
			 $t->addBlock("varieties");
		}

		// Getting cost and current stock for current rows wine
		$inventoryQuery = "SELECT cost, on_hand
					  	   FROM inventory
					  	   WHERE wine_id = ".$wine[0];

		$inventoryResult = mysql_query($inventoryQuery);
		$inventory = mysql_fetch_row($inventoryResult);

		$t->setVariable("cost", $inventory[0]);
		$t->setVariable("onhand", $inventory[1]);

		// Getting stock sold and revenue for current rows wine
		$itemQuery = "SELECT SUM(qty), SUM(price)
					  FROM items
					  WHERE wine_id = ".$wine[0];

		$itemResult = mysql_query($itemQuery);
		$item = mysql_fetch_row($itemResult);

		$t->setVariable("sold", $item[0]);
		$t->setVariable("revenue", $item[1]);
		$t->addBlock("wine");
	}

	$t->generateOutput();
?>
