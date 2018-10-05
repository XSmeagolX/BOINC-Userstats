<?php
	include "./settings/settings.php";
	include "./functions/get_lang.php";

	$showProjectHeader = true;
	$showTasksHeader = false;
	$showUpdateHeader = false;
	$showErrorHeader = false;

	include "./functions/initialize_variables.php";

	$novalues = false;

	include "./functions/get_userdata.php";

	if ($cpid === "") {
		$showFreeDCBadges = false;
	} else {
		$linkFreeDCBadges = $linkFreeDCBadges.$cpid;
	}
	
	$goon = 0;
	$projectid = addslashes($_GET["projectid"]);
	$query_check = mysqli_query($db_conn,"SELECT project_shortname FROM boinc_grundwerte" );
	while ( $row = mysqli_fetch_assoc($query_check) ) {
		$project_check = $row["project_shortname"];
		if ( $project_check === $projectid ) { 
			$goon = 1;
		}
	};
	
	if ( $goon != 1 ) {
		$has_error = true;
		$no_header = false;
		$has_error_description = "Es wurden keine Werte zurückgegeben.</br>
								Das Projekt existiert offenbar nicht in der Datenbank.";
		include "./error.php";
		exit();
	} 

	include "./functions/get_Timestamps.php";

	include "./functions/get_TotalCredits.php";

	$query_getProjectData = mysqli_query($db_conn,"SELECT * FROM boinc_grundwerte WHERE project_shortname = '$projectid'") or die(mysqli_error());
	$row = mysqli_fetch_assoc($query_getProjectData);
	$projectname = $row['project'];
	$projectuserid = $row['project_userid'];
	$status = $row['project_status'];
	$minimum = $row['begin_credits'];
	$project_total_credits = $row["total_credits"];

	if ($project_total_credits > 0) {
		$output_project_html = "";
		$query_getProjectOutputPerHour = mysqli_query($db_conn,"SELECT time_stamp, credits FROM boinc_werte WHERE project_shortname = '" .$projectid. "'");
		if (!$query_getProjectOutputPerHour):
			$has_error = true;
			$has_error_description = $has_error_description_no_boinc_werte_table;
			include "error.php";
			exit;
		endif;
		while($row = mysqli_fetch_assoc($query_getProjectOutputPerHour)){
				$timestamp = ($row["time_stamp"] - 3601) * 1000;
				$output_project_html.= "[(" .$timestamp. "), " .$row["credits"]. "], ";	
		}
		$output_project_html = substr($output_project_html,0,-2);
		
		$output_project_gesamt_html = "";
		$query_getProjectOutputPerDay = mysqli_query($db_conn,"SELECT time_stamp, total_credits FROM boinc_werte_day WHERE project_shortname = '" .$projectid. "'");
		if (!$query_getProjectOutputPerDay):
			$has_error = true;
			$has_error_description = $has_error_description_no_boinc_werte_day_table;
			include "error.php";
			exit;
		endif;
		while($row = mysqli_fetch_assoc($query_getProjectOutputPerDay)){
			$timestamp1 = ($row["time_stamp"] - 3601) * 1000;
			$output_project_gesamt_html.= "[(" .$timestamp1. "), " .$row["total_credits"]. "], ";	
		}
		$output_project_gesamt_html = substr($output_project_gesamt_html,0,-2);
		
		$einsh = mktime(date("H"), 0, 0, date("m"), date ("d"), date("Y"));
		$zweih = mktime(date("H")-1, 0, 0, date("m"), date ("d"), date("Y"));
		$sechsh = mktime(date("H")-5, 0, 0, date("m"), date ("d"), date("Y"));
		$zwoelfh = mktime(date("H")-11, 0, 0, date("m"), date ("d"), date("Y"));
	} else {
		$output_project_html = "";
		$output_project_gesamt_html = "";
	}

	$query_getProjetData = mysqli_query($db_conn,"SELECT * FROM boinc_grundwerte WHERE project_shortname = '$projectid'");
	while($row = mysqli_fetch_assoc($query_getProjetData)){
		$shortname = $row["project_shortname"];
		$table_row["project_name"] = $row["project"];
		$table_row["total_credits"] = $row["total_credits"];
		$table_row["project_status"] = $row["project_status"];
		$pstatus = $row["project_status"];
		$table_row["project_home_link"] = $row["project_homepage_url"];
		$table_row["user_stats_vorhanden"] = $row["project_status"];
		if ($hasXML) {
			$table_row["xml"] = $row["xml"];
		}
		$project_total_credits = $row["total_credits"];

		if ($project_total_credits > 0) {
			$query_getProjectOutput1h = mysqli_query($db_conn,"SELECT sum(credits) AS sum1h FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp>'" .$einsh. "'");
			$row2 = mysqli_fetch_assoc($query_getProjectOutput1h);
			$table_row["sum1h"] = $row2["sum1h"];
			$sum1h_total += $table_row["sum1h"];
			
			$query_getProjectOutput2h = mysqli_query($db_conn,"SELECT sum(credits) AS sum2h FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp>'" .$zweih. "'");
			$row2 = mysqli_fetch_assoc($query_getProjectOutput2h);
			$table_row["sum2h"] = $row2["sum2h"];
			$sum2h_total += $table_row["sum2h"];
			
			$query_getProjectOutput6h = mysqli_query($db_conn,"SELECT sum(credits) AS sum6h FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp>'" .$sechsh. "'");
			$row2 = mysqli_fetch_assoc($query_getProjectOutput6h);
			$table_row["sum6h"] = $row2["sum6h"];
			$sum6h_total += $table_row["sum6h"];
			
			$query_getProjectOutput12h = mysqli_query($db_conn,"SELECT sum(credits) AS sum12h FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp>'" .$zwoelfh. "'");
			$row2 = mysqli_fetch_assoc($query_getProjectOutput12h);
			$table_row["sum12h"] = $row2["sum12h"];
			$sum12h_total += $table_row["sum12h"];

			$tagesanfang = mktime(1, 0, 0, date("m"), date ("d"), date("Y"));
			
			$query_getProjectOutputToday = mysqli_query($db_conn,"SELECT sum(credits) AS sum_today FROM boinc_werte WHERE project_shortname = '" .$shortname. "' and time_stamp > '" .$tagesanfang. "'");
			$row2 = mysqli_fetch_assoc($query_getProjectOutputToday);
			$table_row["sum_today"] = $row2["sum_today"];
			$sum_today_total += $table_row["sum_today"];
			
			$gestern_anfang = mktime(1, 0, 0, date("m"), date("d") - 1, date("Y"));
			$gestern_ende = mktime(2, 0, 0, date("m"), date("d"), date("Y"));

			$query_getProjectOutputYesterday = mysqli_query($db_conn,"SELECT sum(credits) AS sum_yesterday FROM boinc_werte WHERE project_shortname = '" .$shortname. "' AND time_stamp > '" .$gestern_anfang. "' AND time_stamp < '" .$gestern_ende. "'");
			$row2 = mysqli_fetch_assoc($query_getProjectOutputYesterday);
			$table_row["sum_yesterday"] = $row2["sum_yesterday"];
			$sum_yesterday_total += $table_row["sum_yesterday"];
			
			$table_row["project_link"] = "project.php?projectid=" .$shortname. "";
			$table_row["proz_anteil"] = sprintf("%01.2f", $row["total_credits"] * 100 / $sum_total);
		} else {
			$novalues = true;
			$table_row["sum1h"] = 0;
			$table_row["sum2h"] = 0;
			$table_row["sum6h"] = 0;
			$table_row["sum12h"] = 0;
			$table_row["sum_today"] = 0;
			$table_row["sum_yesterday"] = 0;
			$table_row["proz_anteil"] = 0;
		}
		$table[] = $table_row;
	}
?>

<?php
	include("./header.php"); 

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
	include("./assets/js/highcharts/output_project_day_inline.js");
	include("./assets/js/highcharts/output_project_week.js");
	include("./assets/js/highcharts/output_project_month.js");
	include("./assets/js/highcharts/output_project_year.js"); 
?>

<?php if ($status == "2"): ?>
		<div class = "alert danger-lastupdate" role = "alert">
			<div class = "container">
				<?=$text_info_project_retired?>
			</div>
		</div>
<?php elseif ($novalues): ?>
		<div class = "alert danger-lastupdate" role = "alert">
			<div class = "container">
				<?=$text_info_project_novalues?>
			</div>
		</div>
<?php elseif ($datum < $datum_start): ?>
		<div class = "alert warning-lastupdate" role = "alert">
			<div class = "container">
				<?=$text_info_update_inprogress?><?=$lastupdate_start?><font size="1">(<?=$my_timezone?>)</font>
			</div>
		</div>
<?php else: ?>
		<div class = "alert info-lastupdate" role = "alert">
			<div class = "container">
				<b><?=$text_header_lu?></b>: <?=$lastupdate_start?> - <?=$lastupdate?> <font size="1">(<?=$my_timezone?>)</font>
			</div>
		</div>
<?php endif; ?>

		<nav>
			<div class = "nav nav-tabs nav-space justify-content-center nav-tabs-userstats">
				<a class = "nav-item nav-link active" id = "projekte-tab" data-toggle = "tab" href = "#projekte" role = "tab" aria-controls = "projekte" aria-selected = "true"><i class="fas fa-list"></i> <?php echo "$tabs_project" ?></a>
<?php if ($showWCGDetails): ?>
				<a class = "nav-item nav-link" id = "wcgdetails-tab" data-toggle = "tab" href = "#wcgdetails" role = "tab" aria-controls = "wcgdetails" aria-selected = "false"><i class="fas fa-info-circle"></i> Details</a>
<?php endif; ?>
				<a class = "nav-item nav-link" id = "gesamt-tab" data-toggle = "tab" href = "#gesamt" role = "tab" aria-controls = "gesamt" aria-selected = "false"><i class="fas fa-chart-area"></i> <?php echo "$tabs_total" ?></a>
				<a class = "nav-item nav-link" id = "stunde-tab" data-toggle = "tab" href = "#stunde" role = "tab" aria-controls = "stunde" aria-selected = "false"><i class="fas fa-chart-bar"></i> <?php echo "$tabs_hour" ?></a>
				<a class = "nav-item nav-link" id = "tag-tab" data-toggle = "tab" href = "#tag" role = "tab" aria-controls = "tag" aria-selected = "false"><i class="fas fa-chart-bar"></i> <?php echo "$tabs_day" ?></a>
				<a class = "nav-item nav-link" id = "woche-tab" data-toggle = "tab" href = "#woche" role = "tab" aria-controls = "woche" aria-selected = "false"><i class="fas fa-chart-bar"></i> <?php echo "$tabs_week" ?></a>
				<a class = "nav-item nav-link" id = "monat-tab" data-toggle = "tab" href = "#monat" role = "tab" aria-controls = "monat" aria-selected = "false"><i class="fas fa-chart-bar"></i> <?php echo "$tabs_month" ?></a>
				<a class = "nav-item nav-link" id = "jahr-tab" data-toggle = "tab" href = "#jahr" role = "tab" aria-controls = "jahr" aria-selected = "false"><i class="fas fa-chart-bar"></i> <?php echo "$tabs_year" ?></a>
				<a class = "nav-item nav-link" id = "badges-tab" data-toggle = "tab" href = "#badges" role = "tab" aria-controls = "badges" aria-selected = "false"><i class="fas fa-certificate"></i> <?php echo "$tabs_badge" ?></a>
			</div>
		</nav>

		<div class = "tab-content flex1" id = "myTabContent">

			<div id = "projekte" class = "tab-pane fade show active" role = "tabpanel" aria-labelledby = "projekte-tab">
				<br>
				<div class="container flex-column align-items-start">
					<div class="d-flex w-100 mb-3">

<?php foreach($table as $table_row): ?>
					<div class = "row w-100">
						<div class="col-sm-12 col-lg-8 mb-1 text-left">
<?php if ($table_row["project_status"]=== "1"): ?>
										<h1><a href = '<?=$table_row["project_home_link"] ?>'><?=$table_row["project_name"] ?> <i class = 'h5 fas fa-home'></i></a></h1>
<?php else: ?>
										<h1><?=$table_row["project_name"] ?></h1>
<?php endif; ?>
								<div class = "d-none d-lg-flex" id = "output_project_day_inline" style = "height:200px;">
								</div>
						</div>
						<div class="col-sm-12 col-lg-4 mb-1 text-right">
										<h1 class = "textblau"><font size = "1"><?=$text_total?>:</font> <?=number_format($table_row["total_credits"],0,$dec_point,$thousands_sep) ?></h1>
										<div class = "text-sm"><?=$table_row["proz_anteil"] ?><?=$text_proz_anteil?></div>
<?php if ($table_row["project_status"] === "1"): ?>
										<div class = "h3 textgruen"><font size = "1"><?=$tr_tb_to?>:</font> <?=number_format($table_row["sum_today"],0,$dec_point,$thousands_sep) ?></div>
										<div class = "text-sm text-muted"><font size = "1">-1h:</font> <?=number_format($table_row["sum1h"],0,$dec_point,$thousands_sep) ?></div>
										<div class = "text-sm text-muted"><font size = "1">-2h:</font> <?=number_format($table_row["sum2h"],0,$dec_point,$thousands_sep) ?></div>
										<div class = "text-sm text-muted"><font size = "1">-6h:</font> <?=number_format($table_row["sum6h"],0,$dec_point,$thousands_sep) ?></div>
										<div class = "text-sm text-muted"><font size = "1">-12h:</font> <?=number_format($table_row["sum12h"],0,$dec_point,$thousands_sep) ?></div>
										<div class = "h4 textgelb"><font size = "1"><?=$tr_tb_ye?>:</font> <?=number_format($table_row["sum_yesterday"],0,$dec_point,$thousands_sep) ?></div>
<?php if ($hasXML): ?>
<?php if ($table_row["xml"] != ""): ?>
										<div class = "text-sm textgrau"><a href="./<?=$namesubdirectoryXML?>/<?=$table_row["xml"]?>"><i class="fas fa-download"></i> XML</a></div>
<?php else: ?>
										<div class = "text-sm textgrau"></div>
<?php endif;?>
<?php endif;?>
<?php endif; ?>
						</div>
					</div>
<?php endforeach; ?>				
				</div>
<?php if ($status == "0"): ?>
				<div class = "alert warning-lastupdate" role = "alert">
					<div class = "container">
						<?=$text_info_noupdate?>
					</div>
				</div>
<?php endif; ?>
			</div>
		</div>

<?php if ($showWCGDetails): ?>
			<div id = "wcgdetails" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "wcgdetails-tab">
				<div class = "container">
					<div class = "row justify-content-md-center">
						<?=$tr_hp_loadProjectDetails ?>
					</div>
					<div class = "row justify-content-md-center">
						<i class="fas fa-spinner fa-spin fa-3x"></i> 
					</div>
				</div>
			</div>
<?php endif; ?>

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
<?php if (!$showstatsebBadges AND !$showFreeDCBadges AND !$showWcgLogo AND !$showSgWcgBadges): ?>
						<?=$no_badge ?><br>
<?php endif; ?>
<?php if ($showstatsebBadges): ?>
						<img src = "<?=$linkstatsebBadges ?>" class = "img-fluid center-block"><br>
<?php endif; ?>
<?php if ($showFreeDCBadges): ?>
						<img src = "<?=$linkFreeDCBadges ?>" class = "img-fluid center-block"><br>
<?php endif; ?>
<?php if ($showWcgLogo): ?>
						<img src = "<?=$linkWcgSig ?>" class = "img-fluid center-block"><br>
<?php endif; ?>
<?php if ($showSgWcgBadges): ?>
						<img src = "<?=$linkSgWcgBadges ?>" class = "img-fluid center-block"><br>
<?php endif; ?>
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


		<script>
			(function($) {
				$(function() {
				$(document).tooltip({ selector: '[data-toggle="tooltip"]' });
				});
			})(jQuery);
		</script>
		

<?php include("./footer.php"); ?>
