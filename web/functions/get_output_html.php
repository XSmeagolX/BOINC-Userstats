<?php
    $output_html = "";
	$query_getTotalOutputPerHour = mysqli_query($db_conn,"SELECT time_stamp, credits FROM boinc_werte WHERE project_shortname = 'gesamt'");
	while ($row = mysqli_fetch_assoc($query_getTotalOutputPerHour)) {
			$timestamp = ($row["time_stamp"] - 3601) * 1000;
			$output_html .= "[" . $timestamp . ", " . $row["credits"] . "], ";
	}
	$output_html = substr($output_html, 0, -2);
?>