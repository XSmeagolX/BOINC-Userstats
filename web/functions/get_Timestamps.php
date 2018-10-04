<?php
	$query_getUserData = mysqli_query($db_conn, "SELECT * FROM boinc_user");
	while ($row = mysqli_fetch_assoc($query_getUserData)) {
		$datum_start = $row["lastupdate_start"];
		$datum = $row["lastupdate"];
	}

	$lastupdate_start = date("d.m.Y H:i:s", $datum_start);
	$lastupdate = date("H:i:s", $datum);

	$einsh = mktime(date("H"), 0, 0, date("m"), date("d"), date("Y"));
	$zweih = mktime(date("H")-1, 0, 0, date("m"), date("d"), date("Y"));
	$sechsh = mktime(date("H")-5, 0, 0, date("m"), date("d"), date("Y"));
    $zwoelfh = mktime(date("H")-11, 0, 0, date("m"), date("d"), date("Y"));
    $tagesanfang = mktime(1, 0, 0, date("m"), date("d"), date("Y"));
    $gestern_anfang = mktime(1, 0, 0, date("m"), date("d") - 1, date("Y"));
	$gestern_ende = mktime(2, 0, 0, date("m"), date("d"), date("Y"));
	
?>