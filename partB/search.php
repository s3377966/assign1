<?php
	include 'connect.php';
	$regions = mysql_query("SELECT region_id, region_name FROM region");
?>

<form action="result.php" method="GET" >
	<select name="region" onchange="this.form.submit();">
		<?php
			while ($row = mysql_fetch_row($regions))
			{
				$regionId = $row[0];
				$regionName = $row[1];
				echo '<option value ="'.$regionId.'">'.$regionName.'</option>';
			}
		?>
	</select>
</form>
