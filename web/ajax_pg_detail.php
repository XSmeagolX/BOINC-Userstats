<?php
	include "./settings/settings.php";
	include "./functions/get_lang.php";

	$query_getProjectData = mysqli_query($db_conn,"SELECT * FROM boinc_grundwerte WHERE project_shortname = 'primegrid'") or die(mysqli_error());
	$row = mysqli_fetch_assoc($query_getProjectData);
	$projectkey = $row['authenticator'];



	$xml_string = false;

	$url= "https://www.primegrid.com/home.php?format=xml%26weak=";
	$DownloadLink = $url . $projectkey;
	$xml_string = exec("wget ".$DownloadLink);
	print_r($xml_string);
	exit;
	$xml = @simplexml_load_string($xml_string);
	if($xml_string == false) echo "<div class = 'alert alert-danger'><strong>FEHLER!</strong> Die Liste der Projekte ist derzeit nicht verfügbar!</div>";

	$timestamp = date('Y-m-d H'). ':00:00';
	foreach ($xml->subproject as $project_values)
	{
		$table_row["name"] = strval($project_values->name);
		$table_row["workunits"] = strval($project_values->workunits);
		$table_row["primes"] = strval($project_values->primes);
		$table_row["factors"] = strval($project_values->factors);
		$table_row["credit"] = strval($project_values->credit);
		
		$table[]=$table_row;
	}
?>


	<div class = "container">
		<b><?=$wcg_detail_stats_per_project ?></b>

		<table id = "table_wcg" class = "table table-sm table-striped table-hover table-responsive-xs table-200" width = "100%">
			<thead>
				<tr>
					<th class = "dunkelgrau textgrau align-middle"><b>Suprojekt</b></th>
					<th class = "dunkelgrau textgrau align-middle"><b>Arbeitspakete</b></th>
					<th class = "dunkelgrau textgrau align-middle"><b>Primzahlen</b></th>
					<th class = "dunkelgrau textgrau align-middle"><b>Faktoren</b></th>
					<th class = "dunkelgrau textgrau align-middle"><b>Punkte</b></th>
				</tr>
			</thead>
			<tbody>
<?php foreach($table as $table_row): ?>
				<tr>
					<td class = 'align-middle'><?=$table_row["name"] ?></td>
					<td class = 'align-middle'><?=number_format($table_row["workunits"],0,$dec_point,$thousands_sep) ?></td>
					<td class = 'align-middle'><?=number_format($table_row["primes"],0,$dec_point,$thousands_sep) ?></td>
					<td class = 'align-middle'><?=number_format($table_row["factors"],0,$dec_point,$thousands_sep) ?></td>
					<td class = 'align-middle'><?=number_format($table_row["credit"],0,$dec_point,$thousands_sep) ?></td>
				</tr>
<?php endforeach; ?>
			</tbody>
		</table>
	</div>
