<?php
	$t = new MiniTemplator;
	$ok = $t->readTemplateFromFile("list.htm");

	if (!$ok)
	{
		die ("MiniTemplator failed to read list.htm");
	}

	$winesArray = $_SESSION["wines"];

	foreach ($wineArray as $wine)
	{
		$t->setVariable("wineName", $wine);
		$t->addBlock("wine");
	}

	$t->generateOutput();
?>
