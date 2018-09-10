<!doctype html>
<html lang = "<?=$lang; ?>">
	<head>
		<title><?=$text_hp_title; ?></title>

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

		<!-- Popper for Tooltips styling -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<!-- Bootstrap & Datatables from CDN@datatablees.net-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.18/dataRender/ellipsis.js"></script>

		<!--  Fonts and icons  -->
		<link rel = "stylesheet" href = "https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
		<link rel = "stylesheet" href = "https://fonts.googleapis.com/css?family=Montserrat">
		<link rel = "stylesheet" href = "https://fonts.googleapis.com/css?family=Open+Sans:400,300">

		<!--  Highcharts -->
		<script src = "https://code.highcharts.com/stock/highstock.js"></script>
<?php if ($highchartExport): ?>
		<script src = "https://code.highcharts.com/modules/exporting.js"></script>
<?php endif;?>
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
<?php if ($showErrorHeader): ?>
					<h1 class = "title"><font color = "white">UUUUPPSSSSSS.....</font></h1>
<?php elseif ($showProjectHeader): ?>
					<h1 class = "title"><font color = "white"><?=$projectname ?></font></h1>
<?php elseif ($showTasksHeader): ?>
					<h1 class = "title"><font color = "white"><?=$text_header_tasks ?></font></h1>
<?php elseif ($showUpdateHeader): ?>
					<h1 class = "title"><font color = "white"><?=$text_header_update ?></font></h1>
<?php else: ?>
					<h1 class = "title"><font color = "white"><?=$text_header_motto ?></font></h1>
<?php endif; ?>
<?php if (!$showErrorHeader): ?>
					<h3><font color = "white"><?=$boinc_username ?><font size = '3'> <?=$text_header_ot ?></font> <?=$boinc_teamname ?></font></h3>
<?php endif; ?>
				</div>
			</div>
		</div>
