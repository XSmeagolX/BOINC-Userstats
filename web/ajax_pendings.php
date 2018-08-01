<?php
	include "./settings/settings.php";

	$result_user = mysqli_query($db_conn, "SELECT * FROM boinc_user");
	if ( !$result_user || mysqli_num_rows($result_user) === 0 ) { 	
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
			Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	while ($row = mysqli_fetch_assoc($result_user)) {
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
?>
<?php if ($hasPendings) : ?>
	<div class = "alert info-lastupdate" role = "alert">
		<div class = "container">
			<b><?php echo $tr_hp_pendings_02; ?></b>
		</div>
	</div>

	<div class = "container text-center">
		<table id = "table_pendings" class = "table table-striped table-hover table-sm table-responsive-xs table-200" width = "100%">
			<thead>
				<th class = "dunkelgrau textgrau"><?php echo $tr_tb_pr ?></th>
				<th class = "dunkelgrau textgrau text-left"><?php echo $tr_tb_pe ?></th>
			</thead>
			<tbody>									

			<?php $query = mysqli_query($db_conn, "SELECT * FROM boinc_grundwerte WHERE project_status = 1;");
					if ( !$query ) { 	
						$connErrorTitle = "Datenbankfehler";
						$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
							Es bestehen wohl Probleme mit der Datenbankanbindung.";
						include "./errordocs/db_initial_err.php";
						exit();
					} elseif ( mysqli_num_rows($query) === 0 ) { 
						$connErrorTitle = "Datenbankfehler";
						$connErrorDescription = "Es existiern keine Projekte in deiner Datenbank, welche als aktiv (1) gesetzt sind.";
						include "./errordocs/db_initial_err.php";
						exit();
					}
					$ctx = stream_context_create(array(
							'http' => array(
							'timeout' => 1
							)
						)
					);
					$xml_string_pendings = "false";
					$pendings_gesamt = 0;
					
					while ($row = mysqli_fetch_assoc($query)):
						$xml_string_pendings = @file_get_contents($row['url'] . "pending.php?format=xml&authenticator=" . $row['authenticator'], 0, $ctx);
						if ($xml_string_pendings == false) {
							$projectname = $row['project'];
							$pending_credits = $row['pending_credits'];
						} else {
							$projectname = $row['project'];
							$xml_pendings = @simplexml_load_string($xml_string_pendings);

							if ( is_object($xml_pendings) && property_exists($xml_pendings, 'total_claimed_credit')) {
								$pending_credits = intval($xml_pendings->total_claimed_credit);
							} else {
								 $pending_credits = $row['pending_credits'];
							}
						}

						$pendings_gesamt = $pendings_gesamt + $pending_credits;
						$sql_pendings = "UPDATE boinc_grundwerte SET pending_credits = '" . $pending_credits . "' WHERE project_shortname = '" . $row['project_shortname'] . "'";
						mysqli_query($db_conn, $sql_pendings);
						?>
						
<?php if ($pending_credits > 0): ?>
							<tr><td><?=$projectname ?></td>
							<td class = 'text-left'><?=number_format($pending_credits, 0, $dec_point, $thousands_sep) ?></td></tr>
<?php endif; ?>
<?php endwhile; ?>
					<tfoot>
						<tr>
							<td class = 'dunkelblau textblau'><?=$text_total_pendings ?></td>
							<td class = 'dunkelblau textblau text-left'><?=number_format($pendings_gesamt, 0, $dec_point, $thousands_sep) ?></td>
						</tr>
					</tfoot>
			</tbody>
		</table>
		<div class = "alert alert-danger" role = "alert">
			<div class = "container">
				<?php echo $zero_pendings ?>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class = "alert warning-lastupdate" role = "alert">
		<div class = "container">
			<b><?php echo $text_pendings_false; ?></b>
		</div>
	</div>
<?php endif; ?>		

