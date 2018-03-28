<?php
	include "./settings/settings.php";

	$showProjectHeader = true;
	$showPendingsHeader = false;
	$showTasksHeader = false;
	$showUpdateHeader = false;

	$sum1h_total = 0;
	$sum2h_total = 0;
	$sum6h_total = 0;
	$sum12h_total = 0;
	$sum_today_total = 0;
	$sum_yesterday_total = 0;

	$query_getUserData = mysqli_query($db_conn,"SELECT * FROM boinc_user");
	if ( !$query_getUserData || mysqli_num_rows($query_getUserData) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}

	while($row = mysqli_fetch_assoc($query_getUserData)){
		$boinc_username = $row["boinc_name"];
		$boinc_wcgname = $row["wcg_name"];
		$wcg_verification = $row["wcg_verificationkey"];
		$boinc_teamname = $row["team_name"];
		$cpid = $row["cpid"];
		$datum_start = $row["lastupdate_start"];
		$datum = $row["lastupdate"];
	}
	
	if (isset($_GET["lang"])) $lang = $_GET["lang"];
	else $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));

	if (file_exists("./lang/" . $lang . ".txt.php")) include "./lang/" . $lang . ".txt.php";
	else include "./lang/en.txt.php";

	$goon = 0;
	$projectid = addslashes($_GET["projectid"]);
	$query_check = mysqli_query($db_conn,"SELECT project_shortname FROM boinc_grundwerte" );
	if ( !$query_check ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es konnte keine Verbindung zur Datenbank aufgebaut werden.";
		include "./errordocs/db_initial_err.php";
		exit();
	} elseif ( mysqli_num_rows($query_check) === 0 ) { 
		$connErrorTitle = "Fehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Offenbar existieren keine Werte in deiner Datenbank";
		include "./errordocs/db_initial_err.php";
		exit();	
	}

	while ( $row = mysqli_fetch_assoc($query_check) ) {
		$project_check = $row["project_shortname"];
		if ( $project_check === $projectid ) { 
			$goon = 1;
		}
	};
	
	if ( $goon != 1 ) {
		$projectname = $wrong_project;
		$connErrorTitle = "Fehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Das Projekt existiert offenbar nicht in der Datenbank.";
		include "./error.php";
		exit();
	} 

	$lastupdate_start = date("d.m.Y H:i:s", $datum_start + $timezoneoffset*60);
	$lastupdate = date("H:i:s", $datum + $timezoneoffset*60);

	$query_getTotalCredits = mysqli_query($db_conn, "SELECT SUM(total_credits) AS sum_total FROM boinc_grundwerte");
	if ( !$query_getTotalCredits ) { 	
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	} elseif  ( mysqli_num_rows($query_getTotalCredits) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Die Tabelle boinc_grundwerte enthält keine Daten.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	$row2 = mysqli_fetch_assoc($query_getTotalCredits);
	$sum_total = $row2["sum_total"];

	$query_getProjectData = mysqli_query($db_conn,"SELECT * FROM boinc_grundwerte WHERE project_shortname = '$projectid'") or die(mysqli_error());
	if ( !$query_getProjectData || mysqli_num_rows($query_getProjectData) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}

	$row = mysqli_fetch_assoc($query_getProjectData);
	$projectname = $row['project'];
	$projectuserid = $row['project_userid'];
	#$start_time = $row['start_time'];
	$status = $row['project_status'];
	$minimum = $row['begin_credits'];
	$project_total_credits = $row["total_credits"];

	if ($project_total_credits > 0) {

	$output_project_html = "";
	$query_getProjectOutputPerHour = mysqli_query($db_conn,"SELECT time_stamp, credits FROM boinc_werte WHERE project_shortname = '" .$projectid. "'");
	if ( !$query_getProjectOutputPerHour || mysqli_num_rows($query_getProjectOutputPerHour) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	while($row = mysqli_fetch_assoc($query_getProjectOutputPerHour)){
			$timestamp = ($row["time_stamp"] - 3601) * 1000;
			$output_project_html.= "[(" .$timestamp. "), " .$row["credits"]. "], ";	
	}
	$output_project_html = substr($output_project_html,0,-2);
	
	$output_project_gesamt_pendings_html = "";
	$output_project_gesamt_html = "";
	$query_getProjectOutputPerDay = mysqli_query($db_conn,"SELECT time_stamp, total_credits, pending_credits FROM boinc_werte_day WHERE project_shortname = '" .$projectid. "'");
/*	if ( !$query_getProjectOutputPerDay || mysqli_num_rows($query_getProjectOutputPerDay) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	} */
	while($row = mysqli_fetch_assoc($query_getProjectOutputPerDay)){
		$timestamp1 = ($row["time_stamp"] - 3601) * 1000;
		$output_project_gesamt_html.= "[(" .$timestamp1. "), " .$row["total_credits"]. "], ";	
		$output_project_gesamt_pendings_html.= "[(" .$timestamp1. "), " .$row["pending_credits"]. "], ";
	}
	$output_project_gesamt_html = substr($output_project_gesamt_html,0,-2);
	$output_project_gesamt_pendings_html = substr($output_project_gesamt_pendings_html,0,-2);
	
	$einsh = mktime(date("H"), 0 + $timezoneoffset, 0, date("m"), date ("d"), date("Y"));
	$zweih = mktime(date("H")-1, 0 + $timezoneoffset, 0, date("m"), date ("d"), date("Y"));
	$sechsh = mktime(date("H")-5, 0 + $timezoneoffset, 0, date("m"), date ("d"), date("Y"));
	$zwoelfh = mktime(date("H")-11, 0 + $timezoneoffset, 0, date("m"), date ("d"), date("Y"));
	}

	$query_getProjetData = mysqli_query($db_conn,"SELECT * FROM boinc_grundwerte WHERE project_shortname = '$projectid'");
	if ( !$query_getProjetData || mysqli_num_rows($query_getProjetData) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	
	while($row = mysqli_fetch_assoc($query_getProjetData)){

		$shortname = $row["project_shortname"];
		$table_row["project_name"] = $row["project"];
		$table_row["total_credits"] = $row["total_credits"];
		$table_row["project_status"] = $row["project_status"];
		$pstatus = $row["project_status"];
		$table_row["pending_credits"] = $row["pending_credits"];
		$table_row["project_home_link"] = $row["project_homepage_url"];
		$table_row["user_stats_vorhanden"] = $row["project_status"];
		$project_total_credits = $row["total_credits"];

		if ($project_total_credits > 0) {
		
		$query_getProjectOutput1h = mysqli_query($db_conn,"SELECT sum(credits) AS sum1h FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp>'" .$einsh. "'");
		if ( !$query_getProjectOutput1h || mysqli_num_rows($query_getProjectOutput1h) === 0 ) {
			$connErrorTitle = "Datenbankfehler";
			$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
									Es bestehen wohl Probleme mit der Datenbankanbindung.";
			include "./errordocs/db_initial_err.php";
			exit();
		}
		$row2 = mysqli_fetch_assoc($query_getProjectOutput1h);
		$table_row["sum1h"] = $row2["sum1h"];
		$sum1h_total += $table_row["sum1h"];
		
		$query_getProjectOutput2h = mysqli_query($db_conn,"SELECT sum(credits) AS sum2h FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp>'" .$zweih. "'");
		if ( !$query_getProjectOutput2h || mysqli_num_rows($query_getProjectOutput2h) === 0 ) { 
			$connErrorTitle = "Datenbankfehler";
			$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
									Es bestehen wohl Probleme mit der Datenbankanbindung.";
			include "./errordocs/db_initial_err.php";
			exit();
		}
		$row2 = mysqli_fetch_assoc($query_getProjectOutput2h);
		$table_row["sum2h"] = $row2["sum2h"];
		$sum2h_total += $table_row["sum2h"];
		
		$query_getProjectOutput6h = mysqli_query($db_conn,"SELECT sum(credits) AS sum6h FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp>'" .$sechsh. "'");
		if ( !$query_getProjectOutput6h || mysqli_num_rows($query_getProjectOutput6h) === 0 ) { 
			$connErrorTitle = "Datenbankfehler";
			$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
									Es bestehen wohl Probleme mit der Datenbankanbindung.";
			include "./errordocs/db_initial_err.php";
			exit();
		}
		$row2 = mysqli_fetch_assoc($query_getProjectOutput6h);
		$table_row["sum6h"] = $row2["sum6h"];
		$sum6h_total += $table_row["sum6h"];
		
		$query_getProjectOutput12h = mysqli_query($db_conn,"SELECT sum(credits) AS sum12h FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp>'" .$zwoelfh. "'");
		if ( !$query_getProjectOutput12h || mysqli_num_rows($query_getProjectOutput12h) === 0 ) { 
			$connErrorTitle = "Datenbankfehler";
			$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
									Es bestehen wohl Probleme mit der Datenbankanbindung.";
			include "./errordocs/db_initial_err.php";
			exit();
		} 
		$row2 = mysqli_fetch_assoc($query_getProjectOutput12h);
		$table_row["sum12h"] = $row2["sum12h"];
		$sum12h_total += $table_row["sum12h"];

		$tagesanfang = mktime(1, 0 + $timezoneoffset, 0, date("m"), date ("d"), date("Y"));
		
		$query_getProjectOutputToday = mysqli_query($db_conn,"SELECT sum(credits) AS sum_today FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp > '" .$tagesanfang. "'");
		if ( !$query_getProjectOutputToday || mysqli_num_rows($query_getProjectOutputToday) === 0 ) { 
			$connErrorTitle = "Datenbankfehler";
			$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
									Es bestehen wohl Probleme mit der Datenbankanbindung.";
			include "./errordocs/db_initial_err.php";
			exit();
		}
		$row2 = mysqli_fetch_assoc($query_getProjectOutputToday);
		$table_row["sum_today"] = $row2["sum_today"];
		$sum_today_total += $table_row["sum_today"];
		
		$gestern_anfang = mktime(1, 0 + $timezoneoffset, 0, date("m"), date("d") - 1, date("Y"));
		$gestern_ende = mktime(2, 0 + $timezoneoffset, 0, date("m"), date("d"), date("Y"));

		$query_getProjectOutputYesterday = mysqli_query($db_conn,"SELECT sum(credits) AS sum_yesterday FROM boinc_werte WHERE project_shortname = '" .$shortname. "' AND time_stamp > '" .$gestern_anfang. "' AND time_stamp < '" .$gestern_ende. "'");
		if ( !$query_getProjectOutputYesterday || mysqli_num_rows($query_getProjectOutputYesterday) === 0 ) { 
			$connErrorTitle = "Datenbankfehler";
			$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
									Es bestehen wohl Probleme mit der Datenbankanbindung.";
			include "./errordocs/db_initial_err.php";
			exit();
		}
		$row2 = mysqli_fetch_assoc($query_getProjectOutputYesterday);
		$table_row["sum_yesterday"] = $row2["sum_yesterday"];
		$sum_yesterday_total += $table_row["sum_yesterday"];
		
		$table_row["project_link"] = "project.php?projectid=" .$shortname. "";
		$table_row["proz_anteil"] = sprintf("%01.2f", $row["total_credits"] * 100 / $sum_total);
		
		$table[] = $table_row;
	} else {
		$novalues = true;
	} 
}
?>

<?php
	include("./header.php"); 

	if (file_exists("./lang/" . $lang . ".highstock.js")) include "./lang/" . $lang . ".highstock.js";
	else include "./lang/en.highstock.js";

	$showWCGDetails = false;
	if ($table_row["project_name"] == "World Community Grid" || $table_row["project_name"] == "WCG" || $table_row["project_name"] == "WCGrid") {
		if ($wcg_verification === NULL || $wcg_verification === "") {
			$showWCGDetails = false; 
		} else {
			$showWCGDetails = true;
		}
	} 

	include("./assets/js/highcharts/global_settings.php");
	include("./assets/js/highcharts/highcharts_color.php");
	include("./assets/js/highcharts/output_project.js");
	include("./assets/js/highcharts/output_project_hour.js");
	include("./assets/js/highcharts/output_project_day.js");
	include("./assets/js/highcharts/output_project_week.js");
	include("./assets/js/highcharts/output_project_month.js");
	include("./assets/js/highcharts/output_project_year.js"); 
?>

<?php 
	if ($novalues) {
		echo '
		<div class = "alert danger-lastupdate" role = "alert">
			<div class = "container">
				' . $text_info_project_novalues .'
			</div>
		</div>
		';		
	}
	elseif ($status == "2") {
		echo '
		<div class = "alert danger-lastupdate" role = "alert">
			<div class = "container">
				' . $text_info_project_retired .'
			</div>
		</div>
		';	
	} elseif ($status == "0") {
		echo '
		<div class = "alert warning-lastupdate" role = "alert">
			<div class = "container">
				' . $text_info_noupdate. '
			</div>
		</div>
		';
	} elseif ($datum < $datum_start) {
		echo '
		<div class = "alert warning-lastupdate" role = "alert">
			<div class = "container">
				' . $text_info_update_inprogress .  $lastupdate_start .' (' . $my_timezone . ')
			</div>
		</div>
		';
	} else {
		echo '
		<div class = "alert info-lastupdate" role = "alert">
			<div class = "container">
				<b>' . $text_header_lu . ':</b> ' . $lastupdate_start . ' - ' . $lastupdate . ' (' . $my_timezone . ')
			</div>
		</div>
		';
	}
?>

		<nav>
			<div class = "nav nav-tabs nav-space justify-content-center nav-tabs-userstats">
				<a class = "nav-item nav-link active" id = "projekte-tab" data-toggle = "tab" href = "#projekte" role = "tab" aria-controls = "projekte" aria-selected = "true"><i class = "fa fa-table"></i> <?php echo "$tabs_project" ?></a>
				<?php
					if ($showWCGDetails) { echo '
							<a class = "nav-item nav-link" id = "wcgdetails-tab" data-toggle = "tab" href = "#wcgdetails" role = "tab" aria-controls = "wcgdetails" aria-selected = "false"><i class = "fa fa-table"></i> Details</a>
					';
					}
				?>
				<a class = "nav-item nav-link" id = "gesamt-tab" data-toggle = "tab" href = "#gesamt" role = "tab" aria-controls = "gesamt" aria-selected = "false"><i class = "fa fa-area-chart"></i> <?php echo "$tabs_total" ?></a>
				<a class = "nav-item nav-link" id = "stunde-tab" data-toggle = "tab" href = "#stunde" role = "tab" aria-controls = "stunde" aria-selected = "false"><i class = "fa fa-bar-chart"></i> <?php echo "$tabs_hour" ?></a>
				<a class = "nav-item nav-link" id = "tag-tab" data-toggle = "tab" href = "#tag" role = "tab" aria-controls = "tag" aria-selected = "false"><i class = "fa fa-bar-chart"></i> <?php echo "$tabs_day" ?></a>
				<a class = "nav-item nav-link" id = "woche-tab" data-toggle = "tab" href = "#woche" role = "tab" aria-controls = "woche" aria-selected = "false"><i class = "fa fa-bar-chart"></i> <?php echo "$tabs_week" ?></a>
				<a class = "nav-item nav-link" id = "monat-tab" data-toggle = "tab" href = "#monat" role = "tab" aria-controls = "monat" aria-selected = "false"><i class = "fa fa-bar-chart"></i> <?php echo "$tabs_month" ?></a>
				<a class = "nav-item nav-link" id = "jahr-tab" data-toggle = "tab" href = "#jahr" role = "tab" aria-controls = "jahr" aria-selected = "false"><i class = "fa fa-bar-chart"></i> <?php echo "$tabs_year" ?></a>
				<a class = "nav-item nav-link" id = "badges-tab" data-toggle = "tab" href = "#badges" role = "tab" aria-controls = "badges" aria-selected = "false"><i class = "fa fa-certificate"></i> <?php echo "$tabs_badge" ?></a>
			</div>
		</nav>

		<div class = "tab-content flex1" id = "myTabContent">

			<div id = "projekte" class = "tab-pane fade show active" role = "tabpanel" aria-labelledby = "projekte-tab">
				<br>
				<table id = "table_project" class = "table table-sm table-striped table-hover table-responsive-xs table-200" width = "100%">	
					<thead>
						<tr>
							<th class = "dunkelgrau textgrau text-center align-middle"><?php echo "$project_project" ?></th>
							<th class = "dunkelgrau textgrau text-center align-middle"><?php echo "$tr_tb_cr" ?></th>
							<th class = "dunkelgrau textgrau d-none d-sm-table-cell text-center align-middle">%</th>
							<th class = "dunkelgrau textgrau d-none d-sm-table-cell text-center align-middle"><?php echo "$tr_tb_01" ?></th>
							<th class = "dunkelgrau textgrau d-none d-lg-table-cell text-center align-middle"><?php echo "$tr_tb_02" ?></th>
							<th class = "dunkelgrau textgrau d-none d-lg-table-cell text-center align-middle"><?php echo "$tr_tb_06" ?></th>
							<th class = "dunkelgrau textgrau d-none d-md-table-cell text-center align-middle"><?php echo "$tr_tb_12" ?></th>
							<th class = "dunkelgruen textgruen d-none d-sm-table-cell text-center align-middle"><?php echo $tr_tb_to; ?></th>
							<th class = "dunkelblau text-blau d-none d-sm-table-cell text-center align-middle"><?php echo $tr_tb_ye; ?></th>
							<th class = "dunkelrot textrot d-none d-md-table-cell text-center align-middle"><?php echo $tr_tb_pe; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($table as $table_row){
								echo "<tr>";
								if ($table_row["project_status"]=== "1") { echo "
										<td class = 'text-center align-middle'><a href = '" .$table_row["project_home_link"] . "'>" .$table_row["project_name"] . "</a>";
								} else { echo "
										<td class = 'text-center align-middle'>" .$table_row["project_name"] . "</td>";
								};
								echo "	<td class = 'text-center align-middle'>" .number_format($table_row["total_credits"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'd-none d-sm-table-cell text-center align-middle'>" . $table_row["proz_anteil"] . "</td>
										<td class = 'd-none d-sm-table-cell text-center align-middle'>" .number_format($table_row["sum1h"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'd-none d-lg-table-cell text-center align-middle'>" .number_format($table_row["sum2h"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'd-none d-lg-table-cell text-center align-middle'>" .number_format($table_row["sum6h"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'd-none d-md-table-cell text-center align-middle'>" .number_format($table_row["sum12h"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'gruen textgruen d-none d-sm-table-cell text-center align-middle'>" .number_format($table_row["sum_today"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'blau textblau d-none d-sm-table-cell text-center align-middle'>" .number_format($table_row["sum_yesterday"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'rot textrot d-none d-md-table-cell text-center align-middle'>" .number_format($table_row["pending_credits"],0,$dec_point,$thousands_sep). "</td>
									</tr>";
							}
						?>
					</tbody>
				</table>
			</div>

			<?php
			if ($showWCGDetails) { echo '
			<div id = "wcgdetails" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "wcgdetails-tab">
				<div class = "container">
					<div class = "row justify-content-md-center">
						' . $tr_hp_loadProjectDetails . '
					</div>
					<div class = "row justify-content-md-center">
						<i class = "fa fa-spinner fa-pulse fa-2x fa-fw"></i> 
					</div>
				</div>
			</div>'; }
			?>

			<div id = "gesamt" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "gesamt-tab">
				<div id = "output_project"></div>
			</div>

			<div id = "stunde" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "stunde-tab">
				<div id = "output_project_hour"></div>
			</div>
			
			<div id = "tag" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "tag-tab">
				<div id = "output_project_day"></div>
			</div>
		
			<div id = "woche" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "woche-tab">
				<div id = "output_project_week"></div>
			</div>
				
			<div id = "monat" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "monat-tab">
				<div id = "output_project_month"></div>
			</div>
		
			<div id = "jahr" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "jahr-tab">
				<div id = "output_project_year"></div>
			</div>


			<div id = "badges" class = "tab-pane fade text-center" role = "tabpanel" aria-labelledby = "badges-tab">
				<div>
					<br>
					<?php
						if (!$showUserBadges AND !$showWcgLogo AND !$showSgWcgBadges) echo $no_badge ."<br>";
						if ($showUserBadges) {
							echo '<img src = "' . $linkUserBadges . '" class = "img-fluid center-block"><br>';
						};
						if ($showWcgLogo) {
							echo '<img src = "' . $linkWcgSig . '" class = "img-fluid center-block"><br>';
						};
						if ($showSgWcgBadges) {
							echo '<img src = "' . $linkSgWcgBadges . '" class = "img-fluid center-block"><br>';
						};
					?>
					<br>
				</div>
			</div>				
		</div>

		<script>
			$(document).on('click','#wcgdetails-tab',function(){
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							document.getElementById("wcgdetails").innerHTML =
								this.responseText;
								$('#table_wcgteams, #table_wcg').DataTable( {
									fixedHeader: {
												headerOffset: 56
											},
									language: {
										decimal: "<?php echo $dec_point; ?>",
										thousands: "<?php echo $thousands_sep; ?>",
										search:	"<?php echo $text_search; ?>"
									},
									columnDefs: [ {
										targets: 'no-sort',
										orderable: false,
									}],
									order: [[ 1, "asc" ],[ 0, "asc" ]],
									paging: false,
									info: false,
									searching: false
								} );
						}
					};
					xhttp.open("GET", "./ajax_wcg_detail.php", true);
					xhttp.send(); 
			} );
		</script>
		<script>
			$(document).ready(function() {
				$('#table_project').DataTable( {
					bSortCellsTop: false,
					language: {
						decimal: "<?php echo $dec_point; ?>",
						thousands: "<?php echo $thousands_sep; ?>",
						search:	"<?php echo $text_search; ?>"
					},
					columnDefs: [ {
						targets  : 'no-sort',
						orderable: false,
					}],
					paging: false,
					info: false,
					sorting: false,
					searching: false
				} );
			} );
		</script>

<?php include("./footer.php"); ?>
