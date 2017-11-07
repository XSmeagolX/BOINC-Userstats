<?php
	include "./settings/settings.php";
	date_default_timezone_set('UTC');
	//-----------------------------------------------------------------------------------
	// ab hier bitte keine Aenderungen vornehmen, wenn man nicht weiß, was man tut!!! :D
	//-----------------------------------------------------------------------------------
	
	//Variablen initialisieren
	$sum1h_total = 0;
	$sum2h_total = 0;
	$sum6h_total = 0;
	$sum12h_total = 0;
	$sum_today_total = 0;
	$sum_yesterday_total = 0;
	$showProjectHeader = true;

	$goon = false;
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
			$goon = true;
		}
	};
	
	if ( !$goon ) {
		$connErrorTitle = "Fehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Das Projekt existiert offenbar nicht in der Datenbank.";
		include "./errordocs/db_initial_err.php";
		exit();
	} 

	
	############################################################
	# Beginn fuer Datenzusammenstellung User
	$query_getUserData=mysqli_query($db_conn,"SELECT * from boinc_user"); //alle Userdaten einlesen
	if ( !$query_getUserData || mysqli_num_rows($query_getUserData) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	# und hier geht es nun weiter, wenn die Abfrage erwartete Werte liefert.
	while($row=mysqli_fetch_assoc($query_getUserData)){
		$boinc_username = $row["boinc_name"];
		$boinc_wcgname = $row["wcg_name"];
		$wcg_verification = $row["wcg_verificationkey"];
		$boinc_teamname = $row["team_name"];
		$cpid = $row["cpid"];
		$datum_start = $row["lastupdate_start"];
		$datum = $row["lastupdate"];
	}
	
	$lastupdate_start = date("d.m.Y H:i:s",$datum_start);
	$lastupdate = date("H:i:s",$datum);
	# Ende Datenzusammenstellung User
	############################################################
	
	############################################################
	# Beginn fuer Datenzusammenstellung Projekt
	$query_getProjectData =mysqli_query($db_conn,"SELECT * FROM boinc_grundwerte WHERE project_shortname = '$projectid'") or die(mysqli_error());
	if ( !$query_getProjectData || mysqli_num_rows($query_getProjectData) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	# und hier geht es nun weiter, wenn die Abfrage erwartete Werte liefert.	
	$row = mysqli_fetch_assoc($query_getProjectData);
	$projectname = $row['project'];
	$projectuserid = $row['project_userid'];
	#$start_time = $row['start_time'];
	$status = $row['project_status'];
	$minimum = $row['begin_credits'];
	$output_project_html = "";
	$output_project_gesamt_pendings_html = "";
	$output_project_gesamt_html = "";
	$query_getProjectOutputPerHour=mysqli_query($db_conn,"SELECT time_stamp, credits from boinc_werte where project_shortname='" .$projectid. "'");
	if ( !$query_getProjectOutputPerHour || mysqli_num_rows($query_getProjectOutputPerHour) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	while($row=mysqli_fetch_assoc($query_getProjectOutputPerHour)){
		$timestamp = ($row["time_stamp"]) * 1000;
		$output_project_html.= "[(" .$timestamp. "), " .$row["credits"]. "], ";	
	}
	$output_project_html=substr($output_project_html,0,-2);
	
	$query_getProjectOutputPerDay=mysqli_query($db_conn,"SELECT time_stamp, total_credits, pending_credits from boinc_werte_day where project_shortname='" .$projectid. "'");
	if ( !$query_getProjectOutputPerDay || mysqli_num_rows($query_getProjectOutputPerDay) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	while($row=mysqli_fetch_assoc($query_getProjectOutputPerDay)){
		$timestamp1 = ($row["time_stamp"]) * 1000;
		$output_project_gesamt_html.= "[(" .$timestamp1. "), " .$row["total_credits"]. "], ";	
		$output_project_gesamt_pendings_html.= "[(" .$timestamp1. "), " .$row["pending_credits"]. "], ";
	}
	$output_project_gesamt_html=substr($output_project_gesamt_html,0,-2);
	$output_project_gesamt_pendings_html=substr($output_project_gesamt_pendings_html,0,-2);
	#
	# Ende Datenzusammenstellung Projekt
	############################################################	
	
	$einsh = mktime(date("H"), 0, 0, date("m"), date ("d"), date("Y"));
	$zweih = mktime(date("H")-1, 0, 0, date("m"), date ("d"), date("Y"));
	$sechsh = mktime(date("H")-5, 0, 0, date("m"), date ("d"), date("Y"));
	$zwoelfh= mktime(date("H")-11, 0, 0, date("m"), date ("d"), date("Y"));
	
	#####################################
	# Daten fuer Tabelle holen
	$query_getProjetData=mysqli_query($db_conn,"SELECT * from boinc_grundwerte where project_shortname = '$projectid'"); //alle Projektgrunddaten einlesen
	if ( !$query_getProjetData || mysqli_num_rows($query_getProjetData) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	while($row=mysqli_fetch_assoc($query_getProjetData)){
		
		############################################################
		# Daten fuer Tabelle zuammenstellen
		$shortname=$row["project_shortname"];
		$table_row["project_name"]=$row["project"];
		$table_row["total_credits"]=$row["total_credits"];
		$table_row["project_status"]=$row["project_status"];
		$pstatus=$row["project_status"];
		$table_row["pending_credits"]=$row["pending_credits"];
		$table_row["project_home_link"]=$row["project_homepage_url"];
		$table_row["user_stats_vorhanden"]=$row["project_status"];
		
		#Daten fuer letzte Stunde holen
		$query_getProjectOutput1h = mysqli_query($db_conn,"SELECT sum(credits) AS sum1h FROM boinc_werte WHERE project_shortname='" .$shortname. "' and time_stamp>'" .$einsh. "'");
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
		
		#Daten der letzten 2 Stunden holen
		$query_getProjectOutput2h = mysqli_query($db_conn,"SELECT sum(credits) AS sum2h FROM boinc_werte WHERE project_shortname='" .$shortname. "' and time_stamp>'" .$zweih. "'");
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
		
		#Daten der letzten 6 Stunden holen
		$query_getProjectOutput6h = mysqli_query($db_conn,"SELECT sum(credits) AS sum6h FROM boinc_werte WHERE project_shortname='" .$shortname. "' and time_stamp>'" .$sechsh. "'");
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
		
		#Daten der letzten 12 Stunden holen
		$query_getProjectOutput12h = mysqli_query($db_conn,"SELECT sum(credits) AS sum12h FROM boinc_werte WHERE project_shortname='" .$shortname. "' and time_stamp>'" .$zwoelfh. "'");
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
		
		#Aktueller Tagesoutput
		$tagesanfang = mktime(0, 0, 0, date("m"), date ("d"), date("Y"));
		$query_getProjectOutputToday = mysqli_query($db_conn,"SELECT sum(credits) AS sum_today FROM boinc_werte WHERE project_shortname='" .$shortname. "' and time_stamp>'" .$tagesanfang. "'");
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
		
		#Tagesoutput gestern
		$gestern_anfang = mktime(0, 0, 1, date("m"), date ("d")-1, date("Y"));
		$gestern_ende = mktime(0, 0, 0, date("m"), date ("d"), date("Y"));
		$query_getProjectOutputYesterday = mysqli_query($db_conn,"SELECT sum(credits) AS sum_yesterday FROM boinc_werte WHERE project_shortname='" .$shortname. "' AND time_stamp BETWEEN '" .$gestern_anfang. "' AND '" .$gestern_ende. "'");
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
		
		$table_row["project_link"]= "project.php?projectid=" .$shortname. "";
		
		$table[]=$table_row;
		# Ende Datenzusammenstellung fuer Tabelle
	}
	# Ende Datenzusammenstellung fuer Tabelle
	##########################################

?>

<?php
	//Sprache feststellen
	if (isset($_GET["lang"])) $lang = $_GET["lang"];
	else $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
	
	//Sprachpaket HP einlesen
	if (file_exists("./lang/" . $lang . ".txt.php")) include "./lang/" . $lang . ".txt.php";
	else include "./lang/en.txt.php";

	//Sprachpaket Highcharts einlesen
	if (file_exists("./lang/highstock_" . $lang . ".js")) include "./lang/highstock_" . $lang . ".js";
	else include "./lang/highstock_en.js";

	//Check für WCG-Details
	$showWCGDetails = false;
	if ($table_row["project_name"] == "World Community Grid" || $table_row["project_name"] == "wcg") {
		if ($wcg_verification === NULL || $wcg_verification === "") {
			$showWCGDetails = false; 
		} else {
			$showWCGDetails = true;
		}
	} 
?>

<?php include("./header.php"); ?>


<!-- Highcharts definieren  -->
<?php include("./modules/highcharts/output_project.js"); ?>
<?php include("./modules/highcharts/output_project_hour.js"); ?>
<?php include("./modules/highcharts/output_project_day.js"); ?>
<?php include("./modules/highcharts/output_project_week.js"); ?>
<?php include("./modules/highcharts/output_project_month.js"); ?>
<?php include("./modules/highcharts/output_project_year.js"); ?>


		<div class="alert alert-info" role="alert">
			<div class="container">
				<?php echo $text_header_lu ?>: <?php echo $lastupdate_start ?> - <?php echo $lastupdate ?> (UTC)
			</div>
		</div>

		<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="projekte-tab" data-toggle="tab" href="#projekte" role="tab" aria-controls="projekte" aria-selected="true"><i class="fa fa-table"></i> <?php echo "$tabs_project" ?></a>
			</li>
			<?php
				if ($showWCGDetails) { echo '
					<li class="nav-item">
						<a class="nav-link" id="wcgdetails-tab" data-toggle="tab" href="#wcgdetails" role="tab" aria-controls="wcgdetails" aria-selected="false"><i class="fa fa-table"></i> Details</a>
					</li>
				';
				}
			?>

			<li class="nav-item">
				<a class="nav-link" id="gesamt-tab" data-toggle="tab" href="#gesamt" role="tab" aria-controls="gesamt" aria-selected="false"><i class="fa fa-area-chart"></i> <?php echo "$tabs_total" ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="stunde-tab" data-toggle="tab" href="#stunde" role="tab" aria-controls="stunde" aria-selected="false"><i class="fa fa-bar-chart"></i> <?php echo "$tabs_hour" ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="tag-tab" data-toggle="tab" href="#tag" role="tab" aria-controls="tag" aria-selected="false"><i class="fa fa-bar-chart"></i> <?php echo "$tabs_day" ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="woche-tab" data-toggle="tab" href="#woche" role="tab" aria-controls="woche" aria-selected="false"><i class="fa fa-bar-chart"></i> <?php echo "$tabs_week" ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="monat-tab" data-toggle="tab" href="#monat" role="tab" aria-controls="monat" aria-selected="false"><i class="fa fa-bar-chart"></i> <?php echo "$tabs_month" ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="jahr-tab" data-toggle="tab" href="#jahr" role="tab" aria-controls="jahr" aria-selected="false"><i class="fa fa-bar-chart"></i> <?php echo "$tabs_year" ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="badges-tab" data-toggle="tab" href="#badges" role="tab" aria-controls="badges" aria-selected="false"><i class="fa fa-certificate"></i> <?php echo "$tabs_badge" ?></a>
			</li>
		</ul>

		<div class="tab-content flex1" id="myTabContent">

			<div id="projekte" class="tab-pane fade show active" role="tabpanel" aria-labelledby="projekte-tab">
				<br>
				<table class="table table-sm table-striped table-hover table-responsive-sm" width="100%">	
					<thead>
						<tr class = "alert alert-warning">
							<th><?php echo "$project_project" ?></th>
							<th><?php echo "$tr_tb_cr" ?></th>
							<th><?php echo "$tr_tb_01" ?></th>
							<th><?php echo "$tr_tb_02" ?></th>
							<th><?php echo "$tr_tb_06" ?></th>
							<th><?php echo "$tr_tb_12" ?></th>
							<th class = "alert-success"><?php echo $tr_tb_to; ?></th>
							<th class = "alert-info"><?php echo $tr_tb_ye; ?></th>
							<th class = "alert-danger"><?php echo $tr_tb_pe; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($table as $table_row){
								echo "<tr class='alert-default'>";
								if ($table_row["project_status"]=== "1") { echo "
										<td><a href='" .$table_row["project_home_link"] . "'>" .$table_row["project_name"] . "</a>";
								} else { echo "
										<td>" .$table_row["project_name"] . "</td>";
								};
								echo "	<td>" .number_format($table_row["total_credits"],0,$dec_point,$thousands_sep). "</td>
										<td>" .number_format($table_row["sum1h"],0,$dec_point,$thousands_sep). "</td>
										<td>" .number_format($table_row["sum2h"],0,$dec_point,$thousands_sep). "</td>
										<td>" .number_format($table_row["sum6h"],0,$dec_point,$thousands_sep). "</td>
										<td>" .number_format($table_row["sum12h"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'success text-success'>" .number_format($table_row["sum_today"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'info text-info'>" .number_format($table_row["sum_yesterday"],0,$dec_point,$thousands_sep). "</td>
										<td class = 'danger text-danger'>" .number_format($table_row["pending_credits"],0,$dec_point,$thousands_sep). "</td>
									</tr>";
							}
						?>
					</tbody>
				</table>
				<?php
					
				?>
			</div>

			<?php
			if ($showWCGDetails) { echo '
			<div id="wcgdetails" class="tab-pane fade" role="tabpanel" aria-labelledby="wcgdetails-tab">';
				include ("./modules/project_details/wcg_detail.php");
			echo '</div>'; }
			?>

			<div id="gesamt" class="tab-pane fade" role="tabpanel" aria-labelledby="gesamt-tab">
				<div id="output_project"></div>
			</div>

			<div id="stunde" class="tab-pane fade" role="tabpanel" aria-labelledby="stunde-tab">
				<div id="output_project_hour"></div>
			</div>
			
			<div id="tag" class="tab-pane fade" role="tabpanel" aria-labelledby="tag-tab">
				<div id="output_project_day"></div>
			</div>
		
			<div id="woche" class="tab-pane fade" role="tabpanel" aria-labelledby="woche-tab">
				<div id="output_project_week"></div>
			</div>
				
			<div id="monat" class="tab-pane fade" role="tabpanel" aria-labelledby="monat-tab">
				<div id="output_project_month"></div>
			</div>
		
			<div id="jahr" class="tab-pane fade" role="tabpanel" aria-labelledby="jahr-tab">
				<div id="output_project_year"></div>
			</div>
				
			<div id="badges" class="tab-pane fade text-center" role="tabpanel" aria-labelledby="badges-tab">
				<br>
				<?php //Userbadge
					if (!$showUserBadges AND !$showWcgLogo AND !$showSgWcgBadges) echo $no_badge ."<br>";
					if ($showUserBadges) {
						echo '<img src="' . $linkUserBadges . '" class="img-responsive center-block"></img><br>';
					};
					if ($showWcgLogo) {
						echo '<img src="' . $linkWcgSig . '" class="img-responsive center-block"></img><br>';
					};
					if ($showSgWcgBadges) {
						echo '<img src="' . $linkSgWcgBadges . '" class="img-responsive center-block"></img><br>';
					};
				?>
				<br>
			</div>
		</div>

	<?php include("./footer.php"); ?>
