<?php
	$query_getUserData = mysqli_query($db_conn, "SELECT * FROM boinc_user");
    if (!$query_getUserData):
		$uups_error = true;
		$uups_error_description = $uups_error_description_no_boinc_user_table;
		include "error.php";
		exit;
	endif; 

	while ($row = mysqli_fetch_assoc($query_getUserData)) {
		$datum_start = $row["lastupdate_start"];
		$datum = $row["lastupdate"];
	}

	$lastupdate_start = date("d.m.Y H:i:s", $datum_start);
	$lastupdate = date("H:i:s", $datum);
?>