<?php
	$didVersionEdit = false;
	if ($setUpdatecheck) {
		$xml_string = false;
		$xml_string = @file_get_contents ("https://boinc-userstats.de/latest_release.xml", 0, $ctx);
		$xml = @simplexml_load_string($xml_string);
		$update_available = false;
		if($xml_string == false) {
			$update_available = false;
		}
		elseif($xml == $userstats_version) {
			$update_available = false;
		}
		elseif ($xml > $userstats_version) {
			$update_available = true;
		}
		else {
			$update_available = true;
			$didVersionEdit = true;
		}
	};
	
?>

<nav class = "navbar navbar-expand-lg navbar-light bg-light fixed-top navbar-userstats">
	<div class = "container">
		<a class = "navbar-brand" href = "<?php echo $hp_nav_brand_link; ?>">
			<img src = "<?php echo $brand_logo; ?>" width = "30" height = "30" alt = "">
		</a>
		<a class = "nav-link btn btn-neutral btn-simple" href = "./index.php"><i class = "fa fa-home" aria-hidden = "true"></i> <?php echo $linkNameOverview; ?></a>
		<button class = "navbar-toggler" type = "button" data-toggle = "collapse" data-target = "#navbarNav" aria-controls = "navbarNav" aria-expanded = "false" aria-label = "Toggle navigation">
			<span class = "navbar-toggler-icon"></span>
		</button>
		<div class = "collapse navbar-collapse justify-content-end" id = "navbarNav">
			<ul class = "navbar-nav">
				<?php if ( $hasBoinctasks ): ?>
					<li class = 'nav-item'>
						<a class = 'nav-link btn btn-neutral btn-simple' href = '<?=$linkBoinctasks ?>'><i class = 'text-md fa fa-tasks'></i> <?=$linkNameBoinctasks ?></a>
					</li>
				<?php endif; ?>

				<?php if ( $hasPendings ): ?>
					<li class = 'nav-item'>
					<a class = 'nav-link btn btn-neutral btn-simple' href = '<?=$linkPendings ?>'><i class = 'fa fa-refresh'></i> <?=$linkNamePendings ?></a>
					</li>
				<?php endif ; ?>

				<?php if ( $hasTeamHp ): ?>
					<li class = 'nav-item'>
					<a class = 'nav-link btn btn-neutral btn-simple' href = '<?=$teamHpURL ?>' target = '_new'><i class = 'fa fa-globe'></i> <?=$teamHpName ?></a>
					</li>
				<?php endif; ?>

				<?php if ( $hasBoincstats ): ?>
					<li class = 'nav-item'>
					<a class = 'nav-link btn btn-neutral btn-simple' href = '<?=$linkBoincstats ?>' target = '_new'><i class = 'fa fa-bar-chart'></i> <?=$linkNameBoincstats ?></a>
					</li>
				<?php endif; ?>

				<?php if ( $hasSGStats ): ?>
					<li class = 'nav-item'>
					<a class = 'nav-link btn btn-neutral btn-simple' href = '<?=$linkSGStats ?>' target = '_new'><i class = 'fa fa-bar-chart'></i> <?=$linkNameSGStats ?></a>
					</li>
				<?php endif; ?>

				<?php if ( $hasWcg ): ?>
					<li class = 'nav-item'>
					<a class = 'nav-link btn btn-neutral btn-simple' href = '<?=$linkWcg ?>' target = '_new'><i class = 'fa fa-globe'></i> <?=$linkNameWcg ?></a>
					</li>
				<?php endif; ?>

				<?php if ($showLinks): ?>
					<li class = "nav-item">
						<a class = "nav-link btn btn-neutral" href = "<?=$hp_nav_link01 ?>"><?=$hp_nav_name01 ?></a>
					</li>
					<li class = "nav-item">
						<a class = "nav-link btn btn-neutral" href = "<?=$hp_nav_link02 ?>"><?=$hp_nav_name02 ?></a>
					</li>
					<li class = "nav-item">
						<a class = "nav-link btn btn-neutral" href = "<?=$hp_nav_link03 ?>"><?=$hp_nav_name03 ?></a>
					</li>
				<?php endif; ?>

				<?php if ($showMoreLinks): ?>
					<li class = "nav-item dropdown">
						<a class = "nav-link btn dropdown-toggle" href = "#" id = "dropdownId" data-toggle = "dropdown" aria-haspopup = "true" aria-expanded = "true"><i class = "fa fa-info-circle" aria-hidden = "true"></i> <?=$showMoreLinksName ?></a>
						<div class = "dropdown-menu dropdown-menu-right" aria-labelledby = "dropdownId">
							<a class = "dropdown-item" href = "https://github.com/XSmeagolX" target = "_new"><i class = "fa fa-github" aria-hidden = "true"></i> Userstats on GitHub</a>
							<a class = "dropdown-item" href = "https://boinc-userstats.de" target = "_new"><i class = "fa fa-home" aria-hidden = "true"></i> Userstats Website</a>
						<div class = "dropdown-divider"></div>
							<a class = "dropdown-item" href = "https://www.seti-germany.de" target = "_new"><i class = "fa fa-globe" aria-hidden = "true"></i> SETI.Germany</a>
							<a class = "dropdown-item" href = "https://join.worldcommunitygrid.org/?recruiterId=653215&teamId=4VVG5BDPP1" target = "_new"><i class = "fa fa-globe" aria-hidden = "true"></i> World Community Grid</a>
						</div>
					</li>
				<?php endif; ?>
				<?php if (!$didVersionEdit): ?>
					<?php if ($setUpdatecheck): ?>
						<?php if ( $update_available ): ?>
							<li class = 'nav-item'>
							<a class = 'nav-link' href = 'check_update.php' class = 'btn btn-neutral btn-simple textgelb'><i class = 'text-md fa fa-exclamation-circle textgelb'></i><font class = 'textgelb'> <?=$linkNameUpdate ?></font></a>
							</li>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>
