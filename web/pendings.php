<?php
	include "./settings/settings.php";

	$showProjectHeader = false;
	$showPendingsHeader = true;
	$showTasksHeader = false;
	$showUpdateHeader = false;
	
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
	
	$lastupdate_start = date("d.m.Y H:i:s", $datum_start + $timezoneoffset*3600);
	$lastupdate = date("H:i:s", $datum + $timezoneoffset*3600);
	$pending_credits = "0";

	include("./header.php");
?>

<div id = "pendingCredits" class = "flex1">
	<div class = "alert warning-lastupdate" role = "alert">
		<div class = "container">
			<b><?php echo $tr_hp_pendings_01; ?></b>
		</div>
	</div>

	<div class = "container">
		<div class = "row justify-content-center">
			<?php echo $tr_hp_pendings_03; ?>
		</div>
		<div class = "row justify-content-center">
			<i class="fas fa-spinner fa-spin fa-5x"></i>
		</div>
	</div>
</div>

	<script>
		$(document).ready(function() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("pendingCredits").innerHTML =
						this.responseText;
					$('#table_pendings').DataTable( {
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
						order: [ 1, "desc" ],
						paging: false,
						info: false
					} );
				}
			};
			xhttp.open("GET", "ajax_pendings.php", true);
			xhttp.send(); 
		} );
	</script>

<?php include("./footer.php"); ?>
