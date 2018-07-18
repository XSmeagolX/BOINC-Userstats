<?php
	include "./settings/settings.php";

	$showProjectHeader = false;
	$showPendingsHeader = false;
	$showTasksHeader = true;
	$showUpdateHeader = false;

	$query_getUserData = mysqli_query($db_conn, "SELECT * FROM boinc_user");
	if ( !$query_getUserData ) { 	
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Es wurden keine Werte zurückgegeben.</br>
								Es bestehen wohl Probleme mit der Datenbankanbindung.";
		include "./errordocs/db_initial_err.php";
		exit();
	} elseif ( mysqli_num_rows($query_getUserData) === 0 ) { 
		$connErrorTitle = "Datenbankfehler";
		$connErrorDescription = "Die Tabelle boinc_user enthält keine Daten.";
		include "./errordocs/db_initial_err.php";
		exit();
	}
	while ($row = mysqli_fetch_assoc($query_getUserData)) {
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

	include("./header.php"); 
?>

	<div id = "boincTasks" class = "flex1">
		<div class = "alert warning-lastupdate" role = "alert">
			<div class = "container">
				<b><?php echo $tr_hp_tasks_01; ?></b>
			</div>
		</div>

		<div class = "container">
			<div class = "row justify-content-center">
				<?php echo $tr_hp_tasks_03; ?>
			</div>
			<div class = "row justify-content-center">
				<i class="fas fa-spinner fa-spin fa-5x"></i> 
			</div>
		</div>
	</div>

	<?php if ($showHostsEndofTasks): ?>
		<hr>
		<div class = "container-fluid">
				<b>Computer:</b>
		</div>				
		<div>
			<table id = "table_computer" class = "table table-sm table-striped table-hover table-responsive-xs" width = "100%">
				<thead>
					<tr>
						<th class="text-left align-middle dunkelgrau textgrau"><b>Name</b></th>
						<th class="text-left align-middle dunkelgrau textgrau">Prozessor</th>
						<th class="text-left align-middle dunkelgrau textgrau d-none d-sm-table-cell">Cores (Threads)</th>
						<th class="text-left align-middle dunkelgrau textgrau d-none d-sm-table-cell">Takt</th>
						<th class="text-left align-middle dunkelgrau textgrau d-none d-sm-table-cell">RAM</th>
						<th class="text-left align-middle dunkelgrau textgrau">GPU</th>							
					</tr>
				</thead>		
				<tbody>
					<tr>
						<td class="text-left align-middle"><b>PC01:</b></td>
						<td class="text-left align-middle">Intel(R) Core(TM) i7-4930K</td>
						<td class="text-left align-middle d-none d-sm-table-cell" data-order = "12">6 (12)</td>
						<td class="text-left align-middle d-none d-sm-table-cell" data-order = "3.4">@ 3.4 GHz</td>
						<td class="text-left align-middle d-none d-sm-table-cell" data-order = "16">16 GB RAM</td>
						<td class="text-left align-middle" data-order = "770">NVIDIA GeForce GTX 770 (4095MB)</td>							
					</tr>
					<tr>
						<td class="text-left align-middle"><b>PC02:</b></td>
						<td class="text-left align-middle">Intel(R) Core(TM) i7-4930K</td>
						<td class="text-left align-middle d-none d-sm-table-cell" data-order = "12">6 (12)</td>
						<td class="text-left align-middle d-none d-sm-table-cell" data-order = "3.4">@ 3.4 GHz</td>
						<td class="text-left align-middle d-none d-sm-table-cell" data-order = "16">16 GB RAM</td>
						<td class="text-left align-middle" data-order = "770">NVIDIA GeForce GTX 770 (4095MB)</td>							
					</tr>
					<tr>
						<td class="text-left align-middle"><b>PC03:</b></td>
						<td class="text-left align-middle">Intel(R) Core(TM) i7-3970X</td>
						<td class="text-left align-middle d-none d-sm-table-cell" data-order = "12">6 (12)</td>
						<td class="text-left align-middle d-none d-sm-table-cell" data-order = "3.5">@ 3.5 GHz</td>
						<td class="text-left align-middle d-none d-sm-table-cell" data-order = "16">16 GB RAM</td>
						<td class="text-left align-middle" data-order = "770">NVIDIA GeForce GTX 770 (4095MB)</td>							
					</tr>
				</tbody>
				<tfooter>
					<tr>
						<td class="text-center align-middle" colspan = "6"><small>Dies ist nur eine beispielhafte Auflistung. Bitte editiere die tasks.php, um deine PC-Konfigurationen anzuzeigen</small></td>					
					</tr>
				</tfooter>
			</table>		
		</div>	
	<?php endif; ?>

	<script>
		(function($) {
			$(function() {
			$(document).tooltip({ selector: '.ellipsis' });
			$(document).popover({ selector: '.ellipsis' });
			});
		})(jQuery);
	</script>

	<script>
		$('#table_computer').DataTable( {
			fixedHeader: {
				headerOffset: 56
			},
			language: {
				decimal: "<?php echo $dec_point; ?>",
				thousands: "<?php echo $thousands_sep; ?>",
				search:	"<?php echo $text_search; ?>"
			},
			order: [ 0, "asc" ],
			paging: false,
			info: false,
			searching: false
		} );
	</script>


	<script>
		$(document).ready(function() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("boincTasks").innerHTML =
						this.responseText;
					$('#table_tasks').DataTable( {
						fixedHeader: {
							headerOffset: 56
						},
						language: {
							decimal: "<?php echo $dec_point; ?>",
							thousands: "<?php echo $thousands_sep; ?>",
							search:	"<?php echo $text_search; ?>"
						},
						columnDefs: [ {
							targets: [3,4,7],
							render: $.fn.dataTable.render.ellipsis(11, true, false)
						},{
							targets: [0,1],
							render: $.fn.dataTable.render.ellipsis(20, true)
						},{
							targets: [2],
							render: $.fn.dataTable.render.ellipsis(15, true)
						} ],
						order: [[ 9, "asc" ],[ 0, "asc" ]],
						paging: false,
						info: false
					} );
				}
			};
			xhttp.open("GET", "<?php echo $linkUploadFileBoinctasks; ?>", true);
			xhttp.send(); 
		} );
	</script>

<?php include("./footer.php"); ?>
