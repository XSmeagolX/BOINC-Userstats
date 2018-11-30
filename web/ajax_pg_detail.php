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
			<div class="col-6">
				<div class="container">
					<div class="row">
						<div class ="col-5 d-none d-lg-block align-text-bottom text-right">
							<font size="1" class="textblau align-text-bottom"><?=$pg_detail_credits?>: </h6>
						</div>
						<div class="top text-left align-text-bottom">
							<font size="4" class="textblau align-text-bottom"><?=number_format($table_row["credit"],0,$dec_point,$thousands_sep) ?></font>
						</div>
					</div>
					<div class="row">
						<div class="col-5 d-none d-lg-block align-text-bottom align-text-bottom text-right">
							<font size="1" class="textrot align-text-bottom"><?=$pg_detail_workunits?>: </font>
						</div>
						<div class="top align-text-bottom text-left">
							<font size="3" class="textrot align-text-bottom"><?=number_format($table_row["workunits"],0,$dec_point,$thousands_sep) ?></font>
						</div>
					</div>
<?php foreach($table_hits as $table_row_hits): ?>
					<div class="row">
						<div class="col-5 align-text-bottom text-right">
							<font size="1" class="textgruen align-text-bottom">AP<?=$table_row_hits["length"] ?>:
						</div>
						<div class="align-text-bottom text-left">
							<font size="2" class="textgruen align-text-bottom"><?=$table_row_hits["hits"] ?> <?=$pg_detail_hits?></font>
						</div>
					</div>
<?php endforeach; ?>
				</div>
			</div>
		</div>
		<hr>
<?php else: ?>
		<div class = "rounded row w-100 mb-3">
			<div class="col-6 text-right">
				<h4><?=$table_row["name"] ?></h4>
			</div>
			<div class="col-6">
				<div class="container">
					<div class="row">
						<div class ="col-5 d-none d-lg-block bottom align-text-bottom text-right">
							<font size="1" class="textblau"><?=$pg_detail_credits?>: </font>
						</div>
						<div class="top text-left">
							<font size="4" class="textblau"><?=number_format($table_row["credit"],0,$dec_point,$thousands_sep) ?></font>
						</div>
					</div>
					<div class="row">
						<div class="col-5 d-none d-lg-block bottom align-text-bottom text-right">
							<font size="1" class="textrot"><?=$pg_detail_workunits?>: </font>
						</div>
						<div class="top text-left">
							<font size="3" class="textrot"><?=number_format($table_row["workunits"],0,$dec_point,$thousands_sep) ?></font>
						</div>
					</div>
<?php if ($table_row["primes"] === "0"): ?>
					<div class="row">
						<div class="col-12 bottom align-text-bottom text-left">
							<font class="textrot" size="1"><?=$pg_detail_no_prime?></font>
						</div>
					</div>
<?php elseif ($table_row["primes"] > "0"): ?>
					<div class="row">
						<div class="col-5 d-none d-lg-block bottom align-text-bottom text-right">
							<font size="1" class="textgruen"><?=$pg_detail_prime?>: </font>
						</div>
						<div class="top text-left">
							<font size="3" class="textgruen"><?=number_format($table_row["primes"],0,$dec_point,$thousands_sep) ?></font>
						</div>
					</div>
<?php endif;?>
<?php if ($table_row["factors"] >= "0"):?>
					<div class="row">
						<div class="col-5 d-none d-lg-block bottom align-text-bottom text-right">
							<font size="1" class="textgruen"><?=$pg_detail_factors?>: </font>
						</div>
						<div class="top text-left">
							<font size="3" class="textgruen"><?=number_format($table_row["factors"],0,$dec_point,$thousands_sep) ?></font>
						</div>
					</div>
<?php endif;?>
				</div>
			</div>
		</div>
		<hr>
<?php endif; ?>
<?php endforeach; ?>

