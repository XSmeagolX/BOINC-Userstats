<?php
	include "./settings/settings.php";
	include "./functions/get_lang.php";

	$showProjectHeader = false;
	$showTasksHeader = false;
	$showUpdateHeader = false;
	$showErrorHeader = false;

	include "./functions/initialize_variables.php";
	include "./functions/get_userdata.php";

	if ($cpid === "") {
		$showFreeDCBadges = false;
	} else {
		$linkFreeDCBadges = $linkFreeDCBadges.$cpid;
	}

	include "./functions/get_Timestamps.php";

	include "./functions/get_TotalCredits.php";

	$query_getAllProjects = mysqli_query($db_conn, "SELECT * FROM boinc_grundwerte ORDER BY project ASC");
	while ($row = mysqli_fetch_assoc($query_getAllProjects)) {
		if ($row["project_status"] <= 1) {
			$hasactiveProject = true;			
			$shortname = $row["project_shortname"];
			$table_row["project_name"] = $row["project"];
			$table_row["total_credits"] = $row["total_credits"];
			$table_row["project_home_link"] = $row["project_homepage_url"];
			$table_row["user_stats_vorhanden"] = $row["project_status"];
			if ($hasXML) {
				$table_row["xml"] = $row["xml"];
			}
			$query_getOutput1h = mysqli_query($db_conn,"SELECT sum(credits) AS sum1h FROM boinc_werte WHERE project_shortname = '" . $shortname . "' AND time_stamp>'" . $einsh . "'");
			$row2 = mysqli_fetch_assoc($query_getOutput1h);
			$table_row["sum1h"] = $row2["sum1h"];
			$sum1h_total += $table_row["sum1h"];
			
			$query_getOutput2h = mysqli_query($db_conn,"SELECT sum(credits) AS sum2h FROM boinc_werte WHERE project_shortname = '" . $shortname . "' AND time_stamp>'" . $zweih . "'");
			$row2 = mysqli_fetch_assoc($query_getOutput2h);
			$table_row["sum2h"] = $row2["sum2h"];
			$sum2h_total += $table_row["sum2h"];
			
			$query_getOutput6h = mysqli_query($db_conn,"SELECT sum(credits) AS sum6h FROM boinc_werte WHERE project_shortname = '" . $shortname . "' AND time_stamp>'" . $sechsh . "'");
			$row2 = mysqli_fetch_assoc($query_getOutput6h);
			$table_row["sum6h"] = $row2["sum6h"];
			$sum6h_total += $table_row["sum6h"];
			
			$query_getOutput12h = mysqli_query($db_conn,"SELECT sum(credits) AS sum12h FROM boinc_werte WHERE project_shortname = '" . $shortname . "' AND time_stamp>'" . $zwoelfh . "'");
			$row2 = mysqli_fetch_assoc($query_getOutput12h);
			$table_row["sum12h"] = $row2["sum12h"];
			$sum12h_total += $table_row["sum12h"];
					
			$query_getOutputToday = mysqli_query($db_conn,"SELECT sum(credits) AS sum_today FROM boinc_werte WHERE project_shortname = '" . $shortname . "' AND time_stamp > '" . $tagesanfang . "'");
			$row2 = mysqli_fetch_assoc($query_getOutputToday);
			$table_row["sum_today"] = $row2["sum_today"];
			$sum_today_total += $table_row["sum_today"];
			
			$query_getOutputYesterday = mysqli_query($db_conn,"SELECT sum(credits) AS sum_yesterday FROM boinc_werte WHERE project_shortname = '" . $shortname . "' AND time_stamp > '" . $gestern_anfang . "' AND time_stamp < '" . $gestern_ende . "'");
			$row2 = mysqli_fetch_assoc($query_getOutputYesterday);
			$table_row["sum_yesterday"] = $row2["sum_yesterday"];
			$sum_yesterday_total += $table_row["sum_yesterday"];
			
			$table_row["proz_anteil"] = sprintf("%01.2f", $row["total_credits"] * 100 / $sum_total);
			$table_row["project_link"] = "project.php?projectid=" . $shortname . "";
			$table_row["retired"] = false;
			
			$table[] = $table_row;
			$pie_array = $table_row;
			
			} else {

			$hasretiredProject = true;			
			$shortname = $row["project_shortname"];
			$table_row["project_name"] = $row["project"];
			$table_row["total_credits"] = $row["total_credits"];
			$table_row["project_home_link"] = $row["project_homepage_url"];
			$table_row["user_stats_vorhanden"] = $row["project_status"];
			$table_row["proz_anteil"] = sprintf("%01.2f", $row["total_credits"] * 100 / $sum_total);
			$table_row["project_link"] = "project.php?projectid=" . $shortname . "";
			$table_row["retired"] = true;
			$total_credits_retired = $total_credits_retired + $row["total_credits"];
			$table_retired[] = $table_row;
			$pie_array = $table_row;
		}

		if ($table_row["proz_anteil"] >= $separat && !$table_row["retired"]) {
			$pie_html .= "	['" . $pie_array["project_name"] . "',	 " . round($pie_array["total_credits"] * 100 / $sum_total, 2) . "],\n";
			} else {
			if (!$table_row["retired"]) $pie_other += ($pie_array["total_credits"] * 100 / $sum_total);
			else $pie_other_retired += ($pie_array["total_credits"] * 100 / $sum_total);
		}		
	}
	
	if ($pie_other > 0) {
		$pie_html .= "	['" . $tr_ch_pie_zwp . "',	 " . round($pie_other, 2) . "],\n";
	}
	if ($pie_other_retired > 0) {
		$pie_html .= "	['" . $tr_ch_pie_ret . "',	 " . round($pie_other_retired, 2) . "]\n";
	}

	include("./functions/get_output_html.php");
	include("./functions/get_output_gesamt_html.php"); 

	include("./header.php"); 

	include("./assets/js/highcharts/global_settings.php");
	include("./assets/js/highcharts/highcharts_color.php");
	include("./assets/js/highcharts/pie.js");
	include("./assets/js/highcharts/output_gesamt.js");
	include("./assets/js/highcharts/output_gesamt_hour.js");
	include("./assets/js/highcharts/output_gesamt_day.js");
	include("./assets/js/highcharts/output_gesamt_week.js");
	include("./assets/js/highcharts/output_gesamt_month.js");
	include("./assets/js/highcharts/output_gesamt_year.js");

if ($datum < $datum_start): ?>
		<div class = "alert warning-lastupdate" role = "alert">
			<div class = "container"><?=$text_info_update_inprogress?><?=$lastupdate_start?> (<?=$my_timezone?>)</div>
		</div>
<?php else: ?>
		<div class = "alert info-lastupdate" role = "alert">
			<div class = "container">
				<b><?=$text_header_lu?>:</b> <?=$lastupdate_start?> - <?=$lastupdate?> (<?=$my_timezone?>)
			</div>
		</div>
<?php endif; ?>

		<nav>
			<div class = "nav nav-tabs nav-space justify-content-center nav-tabs-userstats">
				<a class = "nav-item nav-link active" id = "projekte-tab" data-toggle = "tab" href = "#projekte" role = "tab" aria-controls = "projekte" aria-selected = "true"><i class="fas fa-list"></i> <?php echo "$tabs_projects" ?></a>
				<a class = "nav-item nav-link" id = "pie-tab" data-toggle = "tab" href = "#pie" role = "tab" aria-controls = "pie" aria-selected = "false"><i class="fas fa-chart-pie"></i> <?php echo "$tabs_pie" ?></a>
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

			<div id = "projekte" class = "container-fluid tab-pane fade show active" role = "tabpanel" aria-labelledby = "projekte-tab">
				<table id = "table_projects" class = "table table-sm table-striped table-hover table-responsive-xs table-200" width = "100%">					
					<thead>
						<tr>
							<th class = "dunkelblau textblau align-middle"><b><?php echo $text_boinc_total ?></b></th>
							<th class = "dunkelblau textblau align-middle"><b><?php echo number_format($sum_total, 0, $dec_point, $thousands_sep) ?></b></th>
							<th class = "dunkelblau textblau d-none d-sm-table-cell align-middle"><b>100%</b></th>
							<th class = "dunkelblau textblau d-none d-sm-table-cell align-middle">
							<b><?php echo number_format($sum1h_total, 0, $dec_point, $thousands_sep) ?></b></th>
							<th class = "dunkelblau textblau d-none d-lg-table-cell align-middle">
							<b><?php echo number_format($sum2h_total, 0, $dec_point, $thousands_sep) ?></b></th>
							<th class = "dunkelblau textblau d-none d-lg-table-cell align-middle">
							<b><?php echo number_format($sum6h_total, 0, $dec_point, $thousands_sep) ?></b></th>
							<th class = "dunkelblau textblau d-none d-lg-table-cell align-middle">
							<b><?php echo number_format($sum12h_total, 0, $dec_point, $thousands_sep) ?></b></th>
							<th class = "dunkelgruen textgruen d-none d-sm-table-cell align-middle">
							<b><?php echo number_format($sum_today_total, 0, $dec_point, $thousands_sep) ?></b></th>
							<th class = "dunkelgelb textgelb d-none d-sm-table-cell align-middle">
							<b><?php echo number_format($sum_yesterday_total, 0, $dec_point, $thousands_sep) ?></b></th>
<?php if ($hasXML): ?>
							<th class = "dunkelblau textblau d-none d-md-table-cell align-middle"></th>
<?php endif; ?>
						</tr>
						<tr>
							<th class = "dunkelgrau textgrau align-middle"><?php echo $tr_tb_pr; ?></th>
							<th class = "dunkelgrau textgrau align-middle"><?php echo $tr_tb_cr; ?></th>
							<th class = "dunkelgrau textgrau d-none d-sm-table-cell align-middle">%</th>
							<th class = "dunkelgrau textgrau d-none d-sm-table-cell align-middle"><?php echo $tr_tb_01; ?></th>
							<th class = "dunkelgrau textgrau d-none d-lg-table-cell align-middle"><?php echo $tr_tb_02; ?></th>
							<th class = "dunkelgrau textgrau d-none d-lg-table-cell align-middle"><?php echo $tr_tb_06; ?></th>
							<th class = "dunkelgrau textgrau d-none d-lg-table-cell align-middle"><?php echo $tr_tb_12; ?></th>
							<th class = "dunkelgruen textgruen d-none d-sm-table-cell align-middle"><?php echo $tr_tb_to; ?></th>
							<th class = "dunkelgelb textgelb d-none d-sm-table-cell align-middle"><?php echo $tr_tb_ye; ?></th>
<?php if ($hasXML): ?>
							<th class = "dunkelblau textblau no-sort d-none d-md-table-cell text-center align-middle"><?php echo $tr_tb_xml; ?></th>
<?php endif; ?>
						</tr>
					</thead>
					<tbody>
<?php if ($hasactiveProject): ?>
<?php foreach ($table as $table_row): ?>
						<tr>
<?php if ($table_row["user_stats_vorhanden"] === "1"): ?>
							<td class = 'align-middle'>
								<a href = '<?=$table_row["project_link"] ?>'><?=$table_row["project_name"] ?></a>
							</td>
<?php else: ?>
							<td class = 'align-middle'>
								<a href = '<?=$table_row["project_link"] ?>'><?=$table_row["project_name"] ?></a>
								<sup><i class="fas fa-exclamation-circle textgelb" data-toggle="tooltip" data-html="true" title="<?=$tooltip_project_noupdate?>"></i></sup>
							</td>
<?php endif; ?>
							<td class = 'align-middle' data-order = '<?=$table_row["total_credits"] ?>'><b><?=number_format($table_row["total_credits"], 0, $dec_point, $thousands_sep) ?></b></td>
							<td class = 'd-none d-sm-table-cell align-middle'><?=number_format($table_row["proz_anteil"], 2, $dec_point, $thousands_sep) ?></td>
<?php if ($table_row["sum1h"] != ""): ?>
							<td class = 'd-none d-sm-table-cell align-middle' data-order = '<?=$table_row["sum1h"] ?>'><?=number_format($table_row['sum1h'], 0, $dec_point, $thousands_sep) ?></td>
<?php else: ?>
							<td class = 'd-none d-sm-table-cell align-middle'>-</td>
<?php endif; ?>
<?php if ($table_row["sum2h"] != ""): ?>
							<td class = 'd-none d-lg-table-cell align-middle' data-order = '<?=$table_row["sum2h"] ?>'><?=number_format($table_row["sum2h"], 0, $dec_point, $thousands_sep) ?></td>
<?php else: ?>
							<td class = 'd-none d-lg-table-cell align-middle'>-</td>
<?php endif; ?>
<?php if ($table_row["sum6h"] != ""): ?>
							<td class = 'd-none d-lg-table-cell align-middle' data-order = '<?=$table_row["sum6h"] ?>'><?=number_format($table_row["sum6h"], 0, $dec_point, $thousands_sep) ?></td>
<?php else: ?>
							<td class = 'd-none d-lg-table-cell align-middle'>-</td>
<?php endif; ?>
<?php if ($table_row["sum12h"] != ""): ?>
							<td class = 'd-none d-lg-table-cell align-middle' data-order = '<?=$table_row["sum12h"] ?>'><?=number_format($table_row["sum12h"], 0, $dec_point, $thousands_sep) ?></td>
<?php else: ?>
							<td class = 'd-none d-lg-table-cell align-middle'>-</td>
<?php endif; ?>
<?php if ($table_row["sum_today"] != ""): ?>
							<td class = 'gruen textgruen d-none d-sm-table-cell align-middle' data-order = '<?=$table_row["sum_today"] ?>'><b><?=number_format($table_row["sum_today"], 0, $dec_point, $thousands_sep) ?></b></td>
<?php else: ?>
							<td class = 'gruen textgruen d-none d-sm-table-cell align-middle'>-</td>
<?php endif; ?>
<?php if ($table_row["sum_yesterday"] != ""): ?>
							<td class = 'gelb textgelb d-none d-sm-table-cell align-middle' data-order = '<?=$table_row["sum_yesterday"] ?>'><b><?=number_format($table_row["sum_yesterday"], 0, $dec_point, $thousands_sep) ?></b></td>
<?php else: ?>
							<td class = 'gelb textgelb d-none d-sm-table-cell align-middle'>-</td>
<?php endif; ?>
<?php if ($hasXML): ?>
<?php if ($table_row["xml"] != ""): ?>
							<td class = 'blau textblau d-none d-md-table-cell text-center align-middle'><a href="./<?=$namesubdirectoryXML?>/<?=$table_row["xml"]?>"><i class="fas fa-download"></i></a></td>
<?php else: ?>
							<td class = 'blau textblau d-none d-md-table-cell text-center align-middle'>-</td>
<?php endif; ?>
<?php endif; ?>
						</tr>
<?php endforeach; ?>
<?php endif; ?>
					</tbody>
					<tfoot>
<?php if ($hasretiredProject): ?>
						<tr>
							<td class = 'dunkelgrau textgrau align-middle'><b><?=$tr_th2_rp ?></b>
								<a class = 'toggle-text aling-middle' data-toggle = 'collapse' data-target = '.retiredProjects'>
									<span><i class = 'aling-middle textrot fas fa-toggle-on'></i></span>
									<span class = 'hidden'><i class = 'allign-middle textgruen fas fa-toggle-on fa-rotate-180'></i></span>
								</a>
							</td>
							<td class = 'dunkelgrau textgrau align-middle'>
								<b><?=number_format($total_credits_retired, 0, $dec_point, $thousands_sep) ?></b>
							</td>
							<td class = 'dunkelgrau textgrau d-none d-sm-table-cell align-middle'><?=number_format($pie_other_retired, 2, $dec_point, $thousands_sep) ?></td>
							<td class = 'dunkelgrau textgrau d-none d-sm-table-cell align-middle'><b></b></td>
							<td class = 'dunkelgrau textgrau d-none d-lg-table-cell align-middle'><b></b></td>
							<td class = 'dunkelgrau textgrau d-none d-lg-table-cell align-middle'><b></b></td>
							<td class = 'dunkelgrau textgrau d-none d-lg-table-cell align-middle'><b></b></td>
							<td class = 'dunkelgrau textgrau d-none d-sm-table-cell align-middle'><b></b></td>
							<td class = 'dunkelgrau textgrau d-none d-sm-table-cell align-middle'><b></b></td>
<?php if ($hasXML): ?>
							<td class = 'dunkelgrau textgrau d-none d-md-table-cell align-middle'><b></b></td>
<?php endif; ?>
						</tr>

<?php foreach ($table_retired as $table_row_retired): ?>
						<tr class = 'collapse retiredProjects'>
							<td class = 'text-muted text-sm align-middle'><a href = '<?=$table_row_retired["project_link"] ?>'><?=$table_row_retired["project_name"] ?></a></td>
							<td class = 'text-muted text-sm align-middle'><b><?=number_format($table_row_retired["total_credits"], 0, $dec_point, $thousands_sep) ?></b></td>
							<td class = 'text-muted text-sm d-none d-sm-table-cell align-middle'><?=number_format($table_row_retired["proz_anteil"], 2, $dec_point, $thousands_sep) ?></td>
							<td class = 'text-muted text-sm d-none d-sm-table-cell align-middle'></td>
							<td class = 'text-muted text-sm d-none d-lg-table-cell align-middle'></td>
							<td class = 'text-muted text-sm d-none d-lg-table-cell align-middle'></td>
							<td class = 'text-muted text-sm d-none d-lg-table-cell align-middle'></td>
							<td class = 'text-muted text-sm d-none d-sm-table-cell align-middle'></td>
							<td class = 'text-muted text-sm d-none d-sm-table-cell align-middle'></td>
<?php if ($hasXML): ?>
							<td class = 'text-muted text-sm d-none d-md-table-cell align-middle'></td>
<?php endif; ?>
						</tr>
<?php endforeach; ?>
<?php endif; ?>
					</tfoot>
				</table>
			</div>
		
			<div id = "pie" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "pie-tab">
				<div>
					<div id = "projektverteilung"></div>
				</div>
			</div>
			
			<div id = "gesamt" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "gesamt-tab">
				<div>
					<div id = "output"></div>
				</div>
			</div>

			<div id = "stunde" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "stunde-tab">
				<div>
					<div id = "output_gesamt_hour"></div>
				</div>
			</div>

			<div id = "tag" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "tag-tab">
				<div>
					<div id = "output_gesamt_day"></div>
				</div>
			</div>

			<div id = "woche" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "woche-tab">
				<div>
					<div id = "output_gesamt_week"></div>
				</div>
			</div>

			<div id = "monat" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "monat-tab">
				<div>
					<div id = "output_gesamt_month"></div>
				</div>
			</div>

			<div id = "jahr" class = "tab-pane fade" role = "tabpanel" aria-labelledby = "jahr-tab">
				<div>
					<div id = "output_gesamt_year"></div>
				</div>
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
			$('.hidden').removeClass('hidden').hide();
			$('.toggle-text').click(function() {
				$(this).find('span').each(function() { $(this).toggle(); });
			});
		</script>

		<script>
			(function($) {
				$(function() {
				$(document).tooltip({ selector: '[data-toggle="tooltip"]' });
				});
			})(jQuery);
		</script>
		
		<script>
			$(document).ready(function() {
				$('#table_projects').DataTable( {
					fixedHeader: {
						headerOffset: 56
					},
					bSortCellsTop: false,
					language: {
                		url: "./lang/<?=$lang?>.datatables.json"
					},
					columnDefs: [ {
						targets: "no-sort",
						orderable: false
						},{
						orderSequence:["desc", "asc"]
						} ],
					order: [ 0, "asc" ],
					paging: false,
					info: false
				} );
			} );
		</script>

<?php include("./footer.php"); ?>
