<?php
    $output_gesamt_html = "";
	$query_getTotalOutputPerDay = mysqli_query($db_conn,"SELECT time_stamp, total_credits FROM boinc_werte_day WHERE project_shortname = 'gesamt'");
	while ($row2 = mysqli_fetch_assoc($query_getTotalOutputPerDay)) {
			$timestamp2 = ($row2["time_stamp"] - 3601) * 1000;
			$output_gesamt_html .= "[" . $timestamp2 . ", " . $row2["total_credits"] . "], ";
	}
	$output_gesamt_html = substr($output_gesamt_html, 0, -2);
?>