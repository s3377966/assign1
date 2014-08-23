<?php
	require_once('db.php');
	
	try
	{
		$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname='. DB_NAME;
		$db = new PDO($dsn, DB_USER, DB_PW);
	}
	catch(PDOException $e)
	{
		die($e->getMessage());
	}
?>
