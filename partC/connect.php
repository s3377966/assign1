<?php
	require_once('db.php');
	
	if(!$dbconn = mysql_connect(DB_HOST, DB_USER, DB_PW))
	{
		echo 'Could not connect to mysql on ' . DB_HOST . '\n';
		exit;
	}

	if(!mysql_select_db(DB_NAME, $dbconn))
	{
		echo 'Could not connect to database ' . DB_NAME . '\n';
		echo mysql_error() . '\n';
		exit;
	}
?>
