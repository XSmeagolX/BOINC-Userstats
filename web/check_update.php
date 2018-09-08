<?php
    include "./settings/settings.php";
    
	$showProjectHeader = false;
	$showPendingsHeader = false;
	$showTasksHeader = false;
	$showUpdateHeader = true;
	$showErrorHeader = false;
    
	$query_getUserData = mysqli_query($db_conn, "SELECT * FROM boinc_user");
	if (!$query_getUserData):
		$uups_error = true;
		$uups_error_description = "Es ist keine Tabelle boinc_user vorhanden!";
		include "error.php";
		exit;
	endif; 
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

	# Update-Check
	if( !ini_get('safe_mode') ){ 
		set_time_limit(10); 
	}

	$ctx = stream_context_create(array(
		'http' => array(
			'timeout' => 10
			)
		)
	);

	$xml_string = false;
	$xml_string = @file_get_contents ("https://boinc-userstats.de/latest_release.xml", 0, $ctx);
	$xml = @simplexml_load_string($xml_string);
	$update_available = false;
	if($xml_string == false) {
		$update_available = false;
		$output = $text_update_nocheck;
	}
	elseif($xml == $userstats_release_version) {
		$update_available = false;
		$output = $text_update_false;
	}
	elseif ($xml > $userstats_release_version) {
		$update_available = true;
		$output = $text_update_print_version_local . $userstats_release_version . "<br>" .$text_update_print_version_remote . $xml . "<br><br>" .$text_update_true;
	}
	else {
		$update_available = true;
		$didVersionEdit = true;
		$output = $text_edit_version;
	}

	include("./header.php");
	
	if (file_exists("./lang/" . $lang . ".highstock.js")) include "./lang/" . $lang . ".highstock.js";
	else include "./lang/en.highstock.js";
?>
    
    <div id = "updateCheck" class = "flex1">
	
<?php if ($update_available): ?>
				<div class = "alert danger-lastupdate" role = "alert">
					<div class = "container">
						<?=$text_update_info_true ?>
					</div>
				</div>
				<div class = "container">
					<div class = "row justify-content-center"><p class = "textrot"><i class="far fa-4x fa-times-circle"></i></p></div>
					<div class = "row justify-content-center"><p class = "textrot"><?=$output ?></p>
					</div>
					<div class = "row justify-content-center">
						<a href="https://github.com/XSmeagolX/BOINC-Userstats/releases/latest">https://github.com/XSmeagolX/BOINC-Userstats/releases/latest</a>
					</div>
				</div>
<?php else: ?>
				<div class = "alert info-lastupdate" role = "alert">
					<div class = "container">
						<?=$text_update_info_false ?>
					</div>
				</div>
				<div class = "container">
					<div class = "row justify-content-center"><p class = "textgruen"><i class="far fa-4x fa-check-circle"></i></p></div>
					<div class = "row justify-content-center"><p class = "textgruen"><?=$output ?></p>
					</div>
					<div class = "row justify-content-center">
						<a href="https://github.com/XSmeagolX/BOINC-Userstats/releases/latest">https://github.com/XSmeagolX/BOINC-Userstats/releases/latest</a>
					</div>
				</div>
<?php endif; ?>

	</div>

<?php
    include("./footer.php");
?>
