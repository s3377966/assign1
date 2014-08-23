<?php
	require_once("MiniTemplator.class.php");
	session_start();

	$t = new MiniTemplator;
	$ok = $t->readTemplateFromFile("list.htm");

	if (!$ok)
	{
		die ("MiniTemplator failed to read list.htm");
	}

	if (isset($_SESSION["wines"]))
	{
		$winesArray = $_SESSION["wines"];

		foreach ($winesArray as $wine)
		{
			$t->setVariable("wineName", $wine);
			$t->addBlock("wine");
		}

		$t->generateOutput();
	}
?>
