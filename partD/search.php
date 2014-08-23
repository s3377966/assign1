<?php
	require_once("MiniTemplator.class.php");
	include 'connect.php';

	$t = new MiniTemplator;
	$ok = $t->readTemplateFromFile("search.htm");

	// Checking for successfully loading template
	if (!$ok)
	{
		die ("MiniTemplator failed to read search.htm");
	}

	$regions = mysql_query("SELECT region_id, region_name FROM region");

	// Adding each region to a block to be put in dropdownlist
	while ($row = mysql_fetch_row($regions))
	{
		$regionId = $row[0];
		$regionName = $row[1];

		$t->setVariable("regionId", $regionId);
		$t->setVariable("regionName", $regionName);
		$t->addBlock("region");
	}

	$t->generateOutput();
?>
