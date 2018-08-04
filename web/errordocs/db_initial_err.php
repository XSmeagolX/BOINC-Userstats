<?php
	include "./settings/settings.php";

	$query_getUserData = mysqli_query($db_conn, "SELECT * from boinc_user");
	while ($row = mysqli_fetch_assoc($query_getUserData)) {
		$boinc_username = $row["boinc_name"];
		$boinc_wcgname = $row["wcg_name"];
		$boinc_teamname = $row["team_name"];
		$cpid = $row["cpid"];
		$datum_start = $row["lastupdate_start"];
		$datum = $row["lastupdate"];
	}

	if (isset($_GET["lang"])) $lang = $_GET["lang"];
	else $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));

	if (file_exists("./lang/" . $lang . ".txt.php")) include "./lang/" . $lang . ".txt.php";
	else include "./lang/en.txt.php";

	$lastupdate_start = date("d.m.Y H:i:s", $datum_start + $timezoneoffset*3600);
	$lastupdate = date("H:i:s", $datum + $timezoneoffset*3600);

	include("./header.php"); 
?>

	<div class = "container text-center flex1">
		<h1 class = "title"><?php echo $connErrorTitle; ?></h1>
		<h5 class = "description text-center"><?php echo $connErrorDescription; ?></h5>					
	</div>

<?php include("./footer.php"); ?>
