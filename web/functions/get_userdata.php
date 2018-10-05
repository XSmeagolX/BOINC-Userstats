<?php
	$query_getUserData = mysqli_query($db_conn, "SELECT * FROM boinc_user");
    if (!$query_getUserData):
		$has_error = true;
		$has_error_description = $has_error_description_no_boinc_user_table;
		include "error.php";
		exit;
	endif; 

	while ($row = mysqli_fetch_assoc($query_getUserData)) {
		$boinc_username = $row["boinc_name"];
		$boinc_wcgname = $row["wcg_name"];
		$boinc_teamname = $row["team_name"];
		$cpid = $row["cpid"];
	}
?>