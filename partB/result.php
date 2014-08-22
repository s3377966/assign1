<?php
	include 'connect.php';

	$region_id = $_GET["region"];

	// Checking for non-numeric input by user
	if (!is_numeric($region_id))
	{
		echo '<p> Invalid Region ID </p>';
		exit();
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
		echo '<p> No results found </p>';
		exit();
	}
?>

<table border="1">
	<tr>
		<td><b>Wine</b></td>
		<td><b>Year</b></td>
		<td><b>Winery</b></td>
		<td><b>Region</b></td>
		<td><b>Varieties</b></td>
		<td><b>Cost</b></td>
		<td><b>OnHand</b></td>
		<td><b>Sold</b></td>
		<td><b>Revenue</b></td>
	</tr>

<?php
	while ($wine = mysql_fetch_row($winesResult))
	{
		echo '<tr>';
		echo '<td>'.$wine[1].'</td>';
		echo '<td>'.$wine[2].'</td>';
		echo '<td>'.$wine[3].'</td>';
		echo '<td>'.$wine[4].'</td>';

		// Getting varieties for current rows wine
		$varietyQuery = "SELECT variety 
						 FROM grape_variety
						 LEFT OUTER JOIN wine_variety ON wine_variety.variety_id = grape_variety.variety_id
						 WHERE wine_variety.wine_id = ".$wine[0]."
						 GROUP BY variety";
		
		$varietyResult = mysql_query($varietyQuery);
		
		echo '<td>';

		while ($variety = mysql_fetch_row($varietyResult))
		{
			 echo $variety[0].'<br/>';
		}

		echo '</td>';

		// Getting cost and current stock for current rows wine
		$inventoryQuery = "SELECT cost, on_hand
					  	   FROM inventory
					  	   WHERE wine_id = ".$wine[0];

		$inventoryResult = mysql_query($inventoryQuery);
		$inventory = mysql_fetch_row($inventoryResult);

		echo '<td>$'.$inventory[0].'</td>';
		echo '<td>'.$inventory[1].'</td>';

		// Getting stock sold and revenue for current rows wine
		$itemQuery = "SELECT SUM(qty), SUM(price)
					  FROM items
					  WHERE wine_id = ".$wine[0];

		$itemResult = mysql_query($itemQuery);
		$item = mysql_fetch_row($itemResult);

		echo '<td>'.$item[0].'</td>';
		echo '<td>$'.$item[1].'</td>';
		echo '</tr>';
	}
?>

</table>
