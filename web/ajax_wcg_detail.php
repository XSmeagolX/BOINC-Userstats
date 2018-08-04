<?php
	include "./settings/settings.php";

	function zeit($sekunden)
	{
		$jahre = (int) ($sekunden / (365 * 86400));
		$sekunden %= (365 * 86400);
		$tage = (int) ($sekunden / (24 * 3600));
		$sekunden %= (24 * 3600);
		$stunden = (int) ($sekunden / 3600);
		$sekunden %= 3600;
		$minuten = (int) ($sekunden / 60);
		$sekunden %= 60;
		return array ($jahre, $tage, $stunden, $minuten, $sekunden);
	}

	$query_getUserData = mysqli_query($db_conn, "SELECT * FROM boinc_user");
	$boinc_wcgname = "";
	$wcg_verification = "";
	while ($row = mysqli_fetch_assoc($query_getUserData)) {
		$boinc_username = $row["boinc_name"];
		$boinc_wcgname = $row["wcg_name"];
		$wcg_verification = $row["wcg_verificationkey"];
		$boinc_teamname = $row["team_name"];
		$cpid = $row["cpid"];
		$datum_start = $row["lastupdate_start"];
		$datum = $row["lastupdate"];
	}

	if(isset($_GET["lang"])) $lang=$_GET["lang"];
	else $lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2));
	
	if (file_exists("./lang/" .$lang. ".txt.php")) include "./lang/" .$lang. ".txt.php";
	else include "./lang/en.txt.php";

	$xml_string = false;
	$status = [];
	$xml_string = @file_get_contents ("https://www.worldcommunitygrid.org/stat/viewProjects.do?xml=true");
	$xml = @simplexml_load_string($xml_string);
	if ( $xml !== false) {
		if ( $xml->getName() == 'unavailable') {
			echo "<div class = 'alert alert-danger text-center'><strong>" . $wcg_detail_fehler . "</strong> " . $wcg_detail_fehler_text01 . "</div>"; 
		}
	}
	 	else {
			echo "<div class = 'alert alert-danger text-center'><strong>" . $wcg_detail_fehler . "</strong> " . $wcg_detail_fehler_text02 . "</div>"; 
		}

	foreach ($xml->Project as $project_status)
		{
			$status[strval($project_status->Name)] = strval($project_status->Status);
		}
	
	$xml_string = false;
	$xml_string = @file_get_contents ("http://www.worldcommunitygrid.org/verifyMember.do?name=" . $boinc_wcgname . "&code=" . $wcg_verification . "");
	$xml = @simplexml_load_string($xml_string);
	if($xml_string == false) echo "<div class = 'alert alert-danger'><strong>FEHLER!</strong> Die Liste der Projekte ist derzeit nicht verfügbar!</div>";
	$last_result = strval($xml->MemberStats->MemberStat->LastResult);
	
	$total_time_stamp = date('Y-m-d H'). ':00:00';
	$user_total_runtime_seconds = strval($xml->MemberStats->MemberStat->StatisticsTotals->RunTime);
	$a = zeit($user_total_runtime_seconds);
	$user_total_runtime = $a[0].':'.sprintf('%03d',$a[1]).':'.sprintf('%02d',$a[2]).':'.sprintf('%02d',$a[3]).':'.sprintf('%02d',$a[4]);	
	$user_total_runtime_rank = strval($xml->MemberStats->MemberStat->StatisticsTotals->RunTimeRank);
	$user_total_points = strval($xml->MemberStats->MemberStat->StatisticsTotals->Points);
	$user_total_points_rank = strval($xml->MemberStats->MemberStat->StatisticsTotals->PointsRank);
	$user_total_results = strval($xml->MemberStats->MemberStat->StatisticsTotals->Results);
	$user_total_results_rank = strval($xml->MemberStats->MemberStat->StatisticsTotals->ResultsRank);
	
	foreach ($xml->TeamHistory->Team as $team_history)
	{
		$table_row["team_name"] = strval($team_history->Name);
		$table_row["team_join_date"] = strval($team_history->JoinDate);
		$table_row["team_retire_date"] = strval($team_history->RetireDate);
		$team_total_runtime = strval($team_history->StatisticsTotals->RunTime);
		$trt=zeit($team_total_runtime);
		$table_row["team_runtime"] = $trt[0].':'.sprintf('%03d',$trt[1]).':'.sprintf('%02d',$trt[2]).':'.sprintf('%02d',$trt[3]).':'.sprintf('%02d',$trt[4]);	
		$table_row["team_points"] = strval($team_history->StatisticsTotals->Points);
		$table_row["team_results"] = strval($team_history->StatisticsTotals->Results);
		
		$table_team[]=$table_row;
	}
	
	foreach ($xml->MemberStats->MemberStat->Badges->Badge as $project_badge)
	{
		$table_row["project_fullname"] = strval($project_badge->ProjectName);
		$table_row["badge"] = strval($project_badge->Resource->Url);	
		$table_row["description"] = strval($project_badge->Resource->Description);	
		
		$badges[strval($project_badge->ProjectName)]=$table_row;
	}
	
	$timestamp = date('Y-m-d H'). ':00:00';
	foreach ($xml->MemberStatsByProjects->Project as $project_values)
	{
		$table_row["project_shortname"] = strval($project_values->ProjectShortName);
		
		$longname=strval($project_values->ProjectName);
		$table_row["project_longname"] = $longname;
		$project_runtime = strval($project_values->RunTime);
		$prt=zeit($project_runtime);
		$table_row["project_runtime_unix"] = $project_runtime;
		$table_row["project_runtime"] = $prt[0].':'.sprintf('%03d',$prt[1]).':'.sprintf('%02d',$prt[2]).':'.sprintf('%02d',$prt[3]).':'.sprintf('%02d',$prt[4]);		
		$table_row["project_points"] = strval($project_values->Points);
		$table_row["project_results"] = strval($project_values->Results);
		
		if(isset($badges[$longname])){
			$table_row["badge"] = $badges[$longname]["badge"];	
			$table_row["description"] = $badges[$longname]["description"];	
		}
		else{
			$table_row["badge"] = "";	
			$table_row["description"] = "&nbsp;";	
		}

		$table_row["status"] = $status[$longname];	
		
		$table[]=$table_row;
	}
?>

	<div class = "container">
		<b><?php echo $wcg_detail_team_history; ?></b>
		<table id = "table_wcgteams" class = "table table-sm table-striped table-hover table-responsive-xs" width = "100%">
			<thead>
				<tr class = "fixedHeader">
					<th class = "dunkelgrau textgrau align-middle"><b><?=$wcg_detail_team ?></b></th>
					<th class = "dunkelgrau textgrau text-center align-middle"><b><?=$wcg_detail_join ?></b></th>
					<th class = "dunkelgrau textgrau text-center d-none d-sm-table-cell align-middle"><b><?=$wcg_detail_leave ?></b></th>
					<th class = "dunkelgrau textgrau align-middle"><b><?=$wcg_detail_runtime ?></b></th>
					<th class = "dunkelgrau textgrau d-none d-sm-table-cell align-middle"><b><?=$wcg_detail_points ?></b></th>
					<th class = "dunkelgrau textgrau d-none d-md-table-cell align-middle"><b><?=$wcg_detail_results ?></b></th>
				</tr>
			</thead>
			<tbody>
<?php foreach($table_team as $table_row): ?>
<?php if ($table_row["team_retire_date"] > 0): ?>
						<tr>
							<td class = 'text-muted'><?=$table_row["team_name"] ?></td>
							<td class = 'text-muted text-center'><?=$table_row["team_join_date"] ?></td>
							<td class = 'text-muted text-center d-none d-sm-table-cell'><?=$table_row["team_retire_date"] ?></td>
							<td class = 'text-muted'><?=$table_row["team_runtime"] ?></td>
							<td class = 'text-muted d-none d-sm-table-cell'><?=number_format($table_row["team_points"],0,$dec_point,$thousands_sep) ?></td>
							<td class = 'text-muted d-none d-md-table-cell'><?=number_format($table_row["team_results"],0,$dec_point,$thousands_sep) ?></td>
						</tr>
<?php else: ?>
						<tr>
							<td class = 'texthellgruen'><?=$table_row["team_name"] ?></td>
							<td class = 'texthellgruen text-center'><?=$table_row["team_join_date"] ?></td>
							<td class = 'texthellgruen text-center d-none d-sm-table-cell'>&nbsp;</td>
							<td class = 'texthellgruen'><?=$table_row["team_runtime"] ?></td>
							<td class = 'texthellgruen d-none d-sm-table-cell'><?=number_format($table_row["team_points"],0,$dec_point,$thousands_sep) ?></td>
							<td class = 'texthellgruen d-none d-md-table-cell'><?=number_format($table_row["team_results"],0,$dec_point,$thousands_sep) ?></td>	
						</tr>
<?php endif; ?>					
<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td class = 'dunkelblau textblau'><?=$wcg_detail_total ?><br>
					<?=$wcg_detail_position ?></td>
					<td class = 'dunkelblau textblau'><br></td>
					<td class = 'dunkelblau textblau d-none d-sm-table-cell'><br></td>
					<td class = 'dunkelblau textblau'><?=$user_total_runtime ?><br>(# <?=number_format($user_total_runtime_rank,0,$dec_point,$thousands_sep) ?>)</td>
					<td class = 'dunkelblau textblau d-none d-sm-table-cell'><?=number_format($user_total_points,0,$dec_point,$thousands_sep) ?><br>(# <?=number_format($user_total_points_rank,0,$dec_point,$thousands_sep) ?>)</td>
					<td class = 'dunkelblau textblau d-none d-md-table-cell'><?=number_format($user_total_results,0,$dec_point,$thousands_sep) ?><br>(# <?=number_format($user_total_results_rank,0,$dec_point,$thousands_sep) ?>)</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<br>
	<div class = "container">
		<b><?=$wcg_detail_stats_per_project ?></b>

		<table id = "table_wcg" class = "table table-sm table-striped table-hover table-responsive-xs table-200" width = "100%">
			<thead>
				<tr>
					<th class = "dunkelgrau textgrau align-middle"><b><?=$wcg_detail_project ?></b></th>
					<th class = "dunkelgrau textgrau text-center d-none d-sm-table-cell align-middle"><b><?=$wcg_detail_status ?></b></th>
					<th class = "dunkelgrau textgrau d-none d-sm-table-cell align-middle"><b><?=$wcg_detail_points ?></b></th>
					<th class = "dunkelgrau textgrau d-none d-md-table-cell align-middle"><b><?=$wcg_detail_results ?></b></th>
					<th class = "dunkelgrau textgrau align-middle"><b><?=$wcg_detail_runtimedetail ?></b></th>
					<th class = "dunkelgrau textgrau text-center no-sort align-middle"><b><?=$wcg_detail_badge ?></b></th>
				</tr>
			</thead>
			<tbody>
<?php foreach($table as $table_row): ?>
<?php if ( isset($table_row["project_points"]) && $table_row["project_points"] > 0): ?>
<?php if ($table_row["status"] === "Active"): ?>
							<tr>
								<td class = 'texthellgruen align-middle'><?=$table_row["project_longname"] ?></td>
								<td class = 'texthellgruen text-center d-none d-sm-table-cell align-middle' data-order = '1'><i class="fas fa-square"></i></td>
								<td class = 'texthellgruen d-none d-sm-table-cell align-middle'><?=number_format($table_row["project_points"],0,$dec_point,$thousands_sep) ?></td>
								<td class = 'texthellgruen d-none d-md-table-cell align-middle'><?=number_format($table_row["project_results"],0,$dec_point,$thousands_sep) ?></td>
								<td class = 'texthellgruen align-middle' data-order = '<?=$table_row["project_runtime_unix"] ?>'><?=$table_row["project_runtime"] ?></td>
								<td class = 'texthellgruen text-center align-middle'><img title = '<?=$table_row["description"] ?>' src = '<?=$table_row["badge"] ?>' alt = '<?=$table_row["description"] ?>'></td>
							</tr>
<?php elseif ($table_row["status"] === "Intermittent"): ?>
							<tr>
								<td class = 'texthellgelb align-middle'><?=$table_row["project_longname"] ?></td>
								<td class = 'texthellgelb text-center d-none d-sm-table-cell align-middle' data-order = '2'><i class="fas fa-square"></i></td>
								<td class = 'texthellgelb d-none d-sm-table-cell align-middle'><?=number_format($table_row["project_points"],0,$dec_point,$thousands_sep) ?></td>
								<td class = 'texthellgelb d-none d-md-table-cell align-middle'><?=number_format($table_row["project_results"],0,$dec_point,$thousands_sep) ?></td>
								<td class = 'texthellgelb align-middle' data-order = '<?=$table_row["project_runtime_unix"] ?>'><?=$table_row["project_runtime"] ?></td>
								<td class = 'texthellgelb text-center align-middle'><img title = '<?=$table_row["description"] ?>' src = '<?=$table_row["badge"] ?>' alt = '<?=$table_row["description"] ?>'></td>
							</tr>
<?php elseif ($table_row["status"] === "Completed"): ?>
							<tr>
								<td class = 'texthellrot align-middle'><?=$table_row["project_longname"] ?></td>
								<td class = 'texthellrot text-center d-none d-sm-table-cell align-middle' data-order = '3'><i class="fas fa-square"></i></td>
								<td class = 'texthellrot d-none d-sm-table-cell align-middle'><?=number_format($table_row["project_points"],0,$dec_point,$thousands_sep) ?></td>
								<td class = 'texthellrot d-none d-md-table-cell align-middle'><?=number_format($table_row["project_results"],0,$dec_point,$thousands_sep) ?></td>
								<td class = 'texthellrot align-middle' data-order = '<?=$table_row["project_runtime_unix"] ?>'><?=$table_row["project_runtime"] ?></td>
								<td class = 'texthellrot text-center align-middle'><img title = '<?=$table_row["description"] ?>' src = '<?=$table_row["badge"] ?>' alt = '<?=$table_row["description"] ?>'></td>
							</tr>
<?php else: ?>
							<tr>
								<td class = 'align-middle'><?=$table_row["project_longname"] ?></td>
								<td class = 'text-center d-none d-sm-table-cell align-middle' data-order = '3'> - </td>
								<td class = 'd-none d-sm-table-cell align-middle'><?=number_format($table_row["project_points"],0,$dec_point,$thousands_sep) ?></td>
								<td class = 'd-none d-md-table-cell align-middle'><?=number_format($table_row["project_results"],0,$dec_point,$thousands_sep) ?></td>
								<td class = 'align-middle' data-order = '<?=$table_row["project_runtime_unix"] ?>'><?=$table_row["project_runtime"] ?></td>
								<td class = 'text-center align-middle'><img title = '<?=$table_row["description"] ?>' src = '<?=$table_row["badge"] ?>' alt = '<?=$table_row["description"] ?>'></td>
							</tr>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
			</tbody>
		</table>
	</div>
