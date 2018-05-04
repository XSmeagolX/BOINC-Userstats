<!doctype html>
<html lang = "<?php echo $lang; ?>">
	<head>
		<title><?php echo $text_hp_title; ?></title>

		<!-- Icons -->
		<link rel="shortcut icon" type="image/x-icon" href="./assets/images/icons/favicon.ico"/>
		<link rel="icon" type="image/x-icon" href="./assets/images/icons/favicon.ico"/>
		<link rel="icon" type="image/gif" href="./assets/images/icons/favicon.gif"/>
		<link rel="icon" type="image/png" href="./assets/images/icons/favicon.png"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon.png"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-57x57.png" sizes="57x57"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-60x60.png" sizes="60x60"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-72x72.png" sizes="72x72"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-76x76.png" sizes="76x76"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-114x114.png" sizes="114x114"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-120x120.png" sizes="120x120"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-128x128.png" sizes="128x128"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-144x144.png" sizes="144x144"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-152x152.png" sizes="152x152"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-180x180.png" sizes="180x180"/>
		<link rel="apple-touch-icon" href="./assets/images/icons/apple-touch-icon-precomposed.png"/>
		<link rel="icon" type="image/png" href="./assets/images/icons/favicon-16x16.png" sizes="16x16"/>
		<link rel="icon" type="image/png" href="./assets/images/icons/favicon-32x32.png" sizes="32x32"/>
		<link rel="icon" type="image/png" href="./assets/images/icons/favicon-96x96.png" sizes="96x96"/>
		<link rel="icon" type="image/png" href="./assets/images/icons/favicon-160x160.png" sizes="160x160"/>
		<link rel="icon" type="image/png" href="./assets/images/icons/favicon-192x192.png" sizes="192x192"/>
		<link rel="icon" type="image/png" href="./assets/images/icons/favicon-196x196.png" sizes="196x196"/>
		<meta name="msapplication-TileImage" content="./assets/images/icons/win8-tile-144x144.png"/> 
		<meta name="msapplication-TileColor" content="#ffffff"/> 
		<meta name="msapplication-navbutton-color" content="#ffffff"/> 
		<meta name="application-name" content="BOINC Userstats"/> 
		<meta name="msapplication-tooltip" content="BOINC Userstats"/> 
		<meta name="apple-mobile-web-app-title" content="BOINC Userstats"/> 
		<meta name="msapplication-square70x70logo" content="./assets/images/icons/win8-tile-70x70.png"/> 
		<meta name="msapplication-square144x144logo" content="./assets/images/icons/win8-tile-144x144.png"/> 
		<meta name="msapplication-square150x150logo" content="./assets/images/icons/win8-tile-150x150.png"/> 
		<meta name="msapplication-wide310x150logo" content="./assets/images/icons/win8-tile-310x150.png"/> 
		<meta name="msapplication-square310x310logo" content="./assets/images/icons/win8-tile-310x310.png"/>

		<!-- Required meta tags -->
		<meta charset = "utf-8">
		<meta name = "viewport" content = "width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap 4 -->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

		<!-- DataTables -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/fh-3.1.3/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/fh-3.1.3/datatables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.16/dataRender/ellipsis.js"></script>

		<!--  Fonts and icons  -->
		<link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
		<link rel = "stylesheet" href = "https://cdn.datatables.net/plug-ins/1.10.16/integration/font-awesome/dataTables.fontAwesome.css">
		<link rel = "stylesheet" href = "https://fonts.googleapis.com/css?family=Montserrat">
		<link rel = "stylesheet" href = "https://fonts.googleapis.com/css?family=Open+Sans:400,300">

		<!--  Highcharts -->
		<script src = "https://code.highcharts.com/stock/highstock.js"></script>
		<script src = "https://code.highcharts.com/modules/exporting.js"></script>
		<script src = "https://code.highcharts.com/modules/no-data-to-display.js"></script>

		<!--  Moment.js local -->
		<script src = "./assets/js/moment/moment-with-locales.min.js"></script>
		<script src = "./assets/js/moment/moment-timezone-with-data-2012-2022.js"></script>

		<!-- Layout CSS for Userstats-->
		<link rel = "stylesheet" href = "./assets/css/userstats_layout.css">
		<link rel = "stylesheet" href = "./assets/css/userstats_style.css"> 
	</head>

	<body>
		<?php if ( $showNavbar ) include("./nav.php"); ?>
			<div class = "force_min_height">
				<div class = "jumbotron jumbotron-fluid" style = "background-image: url('<?php echo $header_backround_url; ?>');">
					<div class = "container">
						<div class = "d-inline-flex flex-column" style = "background: rgba(255, 255, 255, 0.3); border-radius: 12px; padding: 12px; border: 1px solid #d3d3d3">
							<?php 
								if ($showProjectHeader) { echo '
									<h1 class = "title"><font color = "white">' . $projectname . '</font></h1>
							';} else if ($showPendingsHeader) { echo '
								<h1 class = "title"><font color = "white">' . $text_header_pendings . '</font></h1>
							';} else if ($showTasksHeader) { echo '
								<h1 class = "title"><font color = "white">' . $text_header_tasks . '</font></h1>
							';} else if ($showUpdateHeader) { echo '
								<h1 class = "title"><font color = "white">' . $text_header_update . '</font></h1>
							';} else { echo '
								<h1 class = "title"><font color = "white">' . $text_header_motto .  '</font></h1>
							';};
							?>
							<h3><font color = "white"><?php echo "$boinc_username" . " <font size = '3'> " . $text_header_ot . "</font> " . $boinc_teamname ?></font></h3>
						</div>
					</div>
				</div>
