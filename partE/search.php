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

	$regionsQuery = "SELECT region_id, region_name FROM region";
	$prepRegionsQuery = $db->prepare($regionsQuery);
	$prepRegionsQuery->execute();

	// Adding each region to a block to be put in dropdownlist
	while ($row = $prepRegionsQuery->fetch())
	{
		$regionId = $row[0];
		$regionName = $row[1];

		$t->setVariable("regionId", $regionId);
		$t->setVariable("regionName", $regionName);
		$t->addBlock("region");
	}

	$t->generateOutput();

	// Checking if start session button was pressed
	if($_POST["start"])
	{
		session_start();
	}
?>
