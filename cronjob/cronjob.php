<?php
	// Hier den absoluten Pfad zur DB-Verbindungsdatei boinc_db_connect.php eintragen
	// Set the absolute path to your boinc_db_connect.php file
	include "/absolute/path/to/boinc_db_connect.php";

	// Setze hier den Zeitpunkt von Mitternacht. 
	// Dies wird für die Berechnung der Werte für die Gesamt- und Pending Credits eines Tages in deiner Zeitzone benötigt, welche in den Gesamt-Charts dargestellt wird
	// Deine Zeitzonenbezeichnung. Wird in der Infobar neben den Zeitangabe für das letzte Update angezeigt. 
	// Hiermit wird auch automatisch auf Sommer-/Winterzeit umgesetzt und die Zeitleiste in den Charts berechnet.
	// Der Wert für timezone_name muss mit php interpretierbar sein.
	// http://php.net/manual/timezones.php
	// Set your midnight here. 
	// This is used for setting daylie total and pending credits to the values of midnight for your timezone for the total charts
	// Your Timezone name. Will be shown in the Infobar next to the last update dates.
	// This will automatically support Daylight Savings on the timeline of your Charts.
	// This timezone_name has to be supported by php!
	// http://php.net/manual/timezones.php
	$my_timezone = "Europe/Berlin"; 


	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	//====================================================================================================================================
	// Ab HIER NICHTS MEHR ÄNDERN !!!!
	// Do NOT CHANGE ANYTHING AFTER !!!!
	date_default_timezone_set($my_timezone);


	if( !ini_get('safe_mode') ){ 
		set_time_limit(120); 
	}

	$total_credits_day = "0";
	$pending_credits = "0";
	$total_credits_hour = "0";

	$unixtime = time();
	$timestamp_hour = ceil($unixtime/3600) * 3600;

	$isMidnight = false;
	$timestamp_timezone = date(('Y-m-d H').':00:00', $unixtime);
	$timestamp_timezone_midnight = date(('Y-m-d ').'00:00:00', $unixtime);

	if ($timestamp_timezone == $timestamp_timezone_midnight) {
		$isMidnight = true;
	}

	$ctx = stream_context_create(array(
			'http' => array(
				'timeout' => 10
			)
		)
	);

	//Diese Zeitzoneneinstellung NICHT verändern, auch wenn deine Stats in einer anderen Zeitzone angezeigt werden sollen!!
	//DO NOT change this timezone setting, even if your stats should be displayed in a different timezone !! 
	date_default_timezone_set('UTC'); 
	$unixtime = time();
	$timestamp_hour = ceil($unixtime/3600) * 3600;
	$timestamp_timezone = date(('Y-m-d H').':00:00', $unixtime);

	$query_getUserData = mysqli_query($db_conn, "SELECT * from boinc_user");
	while ($row = mysqli_fetch_assoc($query_getUserData)) {
		$datum_start = $row["lastupdate_start"];
	}
	$datum_check = ceil($datum_start/3600) * 3600;

	if ($datum_check === $timestamp_hour) {
		echo '
		<div class = "container text-center flex1">
			<h1 class = "title">ACHTUNG -- ATTENTION</h1>
			<h3 class = "description text-center">Daten dürfen nur 1x pro Stunde abgerufen werden!!<br>Data could be fetched only once per hour!</h3>
		</div>';
		exit;
	}

	$sqlupdatestarttime = "UPDATE boinc_user SET lastupdate_start='" .$unixtime. "'";
	mysqli_query($db_conn,$sqlupdatestarttime);

	$query=mysqli_query($db_conn,"SELECT * FROM boinc_grundwerte WHERE project_status = 1;") or die (mysqli_error());	
	while($row=mysqli_fetch_assoc($query))
	{
		$xml_string = false;
		$xml_string = @file_get_contents ($row['url'] . "show_user.php?userid=" . $row['project_userid'] . "&format=xml", 0, $ctx);
		$xml = @simplexml_load_string($xml_string);
		
		if($xml_string == false || intval($xml->total_credit)==0) {
			$total_credits = $row['total_credits'];
			$diff1h = 0;
		}
		else {
			$total_credits = intval($xml->total_credit);
			$diff1h = $total_credits - $row['total_credits'];
			$total_credits_hour = $total_credits_hour + $diff1h;
		}
		if ($diff1h > 0) {
			$sql= "INSERT boinc_werte (project_shortname, time_stamp, credits) 
			VALUES ('" .$row['project_shortname']. "', '" .$timestamp_hour. "','" .$diff1h. "')";
			mysqli_query($db_conn,$sql);
		}
		
		$sql = "UPDATE boinc_grundwerte 
		SET total_credits='" .$total_credits. "' WHERE project_shortname='" .$row['project_shortname']. "'";
		mysqli_query($db_conn,$sql);
		
		if ($isMidnight) {
			// Pending Credits sind nicht mehr Bestandteil des BOINC-Servers und wird mit zukünftigen Releases entfernt
			// Die Pending Credits werden automatisiert nur einmal um Mitternacht aktualisiert. Ein manuelles Update kann jederzeit über die pendings.php erfolgen
			// Pending Credits are depreciated and will be removed in future Releases
			// automatic update of pending credits only on midnight. manual update can be done by using pendings.php
			$xml_string_pendings = false;
			$xml_string_pendings = @file_get_contents ($row['url'] . "pending.php?format=xml&authenticator=" . $row['authenticator'], 0, $ctx);
			if ($xml_string_pendings == false) {
				$pending_credits = $row['pending_credits'];
			} 
			else {
				$xml_pendings = @simplexml_load_string($xml_string_pendings);
				$pending_credits = intval($xml_pendings->total_claimed_credit);
			}

			$sql_insertProjectPendings= "INSERT INTO boinc_werte_day (project_shortname, time_stamp, total_credits, pending_credits) 
			VALUES ('" .$row['project_shortname']. "', '" .$timestamp_hour. "', ".$total_credits.", ".$pending_credits.")";
			mysqli_query($db_conn,$sql_insertProjectPendings);

			$sql_pendings = "UPDATE boinc_grundwerte SET pending_credits='" .$pending_credits. "' WHERE project_shortname='" .$row['project_shortname']. "'";
			mysqli_query($db_conn,$sql_pendings); 
		}
	}

	if ($total_credits_hour > 0) {
		$sql= "INSERT boinc_werte (project_shortname, time_stamp, credits) 
		VALUES ('gesamt', '" .$timestamp_hour. "','" .$total_credits_hour. "')";
		mysqli_query($db_conn,$sql);
	}
	
	if ($isMidnight) {
		$querySumTotalCredits = "SELECT sum(total_credits) AS total_credits FROM boinc_grundwerte";
		$gesamt = mysqli_query($db_conn,$querySumTotalCredits);
		$gesamt_vortag = mysqli_fetch_assoc($gesamt);
		$total_credits_gestern = $gesamt_vortag['total_credits'];

		$querySumPendings = "SELECT sum(pending_credits) AS pending_credits FROM boinc_grundwerte";
		$pendings = mysqli_query($db_conn,$querySumPendings);
		$pendings_vortag = mysqli_fetch_assoc($pendings);
		$total_pendings_gestern = $pendings_vortag['pending_credits'];

		$sql_gesamt= "INSERT INTO boinc_werte_day (project_shortname, time_stamp, total_credits, pending_credits) 
		VALUES ('gesamt', '" .$timestamp_hour. "', '" .$total_credits_gestern. "', '" .$total_pendings_gestern. "')";
		mysqli_query($db_conn,$sql_gesamt);
	}
	
	$updateendtime = time();
	$sqlupdateendtime = "UPDATE boinc_user SET lastupdate='" .$updateendtime. "'";
	mysqli_query($db_conn,$sqlupdateendtime);
?>
