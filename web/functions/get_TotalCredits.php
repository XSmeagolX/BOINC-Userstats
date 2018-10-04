<?php
    $query_getTotalCredits = mysqli_query($db_conn, "SELECT SUM(total_credits) AS sum_total FROM boinc_grundwerte");
	$row2 = mysqli_fetch_assoc($query_getTotalCredits);
	$sum_total = $row2["sum_total"];
?>