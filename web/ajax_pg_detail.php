<?php
	include "./settings/settings.php";
	include "./functions/get_lang.php";

	$query_getProjectData = mysqli_query($db_conn,"SELECT * FROM boinc_grundwerte WHERE project_shortname = 'primegrid'") or die(mysqli_error());
	$row = mysqli_fetch_assoc($query_getProjectData);

	$xml_string = false;

	$url= "https://www.primegrid.com/home.php?format=xml&weak=";
	$DownloadLink = $url . $pg_weak_accountkey;

	$xml_string = @file_get_contents ($DownloadLink);
	$xml = @simplexml_load_string($xml_string);

	if($xml_string === "" OR $xml_string == false) echo "<div class = 'alert alert-danger'><strong>FEHLER!</strong> Die Liste der Projekte ist derzeit nicht verfügbar!</div>";
	$timestamp = date('Y-m-d H'). ':00:00';

	foreach ($xml->user->subproject as $project_values)
		{
			$table_row["name"] = strval($project_values['name']);
			if ($table_row["name"]==="AP26") {
				foreach ($xml->user->subproject->hits as $hits_values) {
					$table_row_hits["length"] = strval($hits_values['length']);
					$table_row_hits["hits"] = strval($hits_values[0]);
					$table_hits[]=$table_row_hits;
				}
			}
			$table_row["workunits"] = strval($project_values->workunits);
			$table_row["primes"] = strval($project_values->primes);
			$table_row["factors"] = strval($project_values->factors);
			$table_row["credit"] = strval($project_values->credit);			
			$table[]=$table_row;
		} 

?>


	<div class = "container text-center">
<?php foreach($table as $table_row): ?>
<?php if ($table_row["name"] === "AP26"): ?>
		<div class = "rounded row w-100 mb-3">
			<div class="col-6 text-right">
				<h4><?=$table_row["name"] ?></h4>
			</div>
			<div class="col-6 text-left">
				<font size="1">Punkte: </font><font size="4" class="textblau"><?=number_format($table_row["credit"],0,$dec_point,$thousands_sep) ?></font><br />
				<font size="1">Berechnungen: </font><font size="3" class="textrot"><?=number_format($table_row["workunits"],0,$dec_point,$thousands_sep) ?></font><br />
<?php foreach($table_hits as $table_row_hits): ?>
				<font size="1">AP<?=$table_row_hits["length"] ?>: </font><font size="2" class="textgruen"><?=$table_row_hits["hits"] ?> Treffer</font><br />
<?php endforeach; ?>
			</div>
		</div>
		<hr>
<?php else: ?>
		<div class = "rounded row w-100 mb-3">
			<div class="col-6 text-right">
				<h4><?=$table_row["name"] ?></h4>
			</div>
			<div class="col-6 text-left">
				<font size="1">Punkte: </font><font size="4" class="textblau"><?=number_format($table_row["credit"],0,$dec_point,$thousands_sep) ?></font><br />
				<font size="1">Berechnungen: </font><font size="3" class="textrot"><?=number_format($table_row["workunits"],0,$dec_point,$thousands_sep) ?></font><br />
<?php if ($table_row["primes"] === "0"): ?>
				<font size="1" class="textrot">Leider bisher noch keine Primzahl gefunden</font><br />
<?php elseif ($table_row["primes"] > "0"): ?>
				<font size="1">gefundene Primzahlen: </font><font size="3" class="textgruen"><?=number_format($table_row["primes"],0,$dec_point,$thousands_sep) ?></font><br />
<?php endif;?>
<?php if ($table_row["factors"] >= "0"):?>
				<font size="1">Faktoren: </font><font size="3" class="textgruen"><?=number_format($table_row["factors"],0,$dec_point,$thousands_sep) ?></font><br />
<?php endif;?>
			</div>
		</div>
		<hr>
<?php endif; ?>
<?php endforeach; ?>

