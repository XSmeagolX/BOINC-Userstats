-- Tabellenstruktur für Tabelle `boinc_grundwerte`
CREATE TABLE IF NOT EXISTS `boinc_grundwerte` (
	`project` varchar(50) NOT NULL,
	`project_userid` int NOT NULL,
	`authenticator` varchar(40) NOT NULL,
	`url` varchar(100) NOT NULL,
	`project_homepage_url` varchar(100) NOT NULL,
	`begin_credits` bigint NOT NULL,
	`project_status` int NOT NULL,
	`project_shortname` varchar(15) NOT NULL,
	`total_credits` bigint NOT NULL,
	UNIQUE KEY `project_shortname` (`project_shortname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabellenstruktur für Tabelle `boinc_user`
CREATE TABLE IF NOT EXISTS `boinc_user` (
	`language` varchar(3) NOT NULL,
	`boinc_name` varchar(50) NOT NULL,
	`wcg_name` varchar(50) NOT NULL,
	`team_name` varchar(50) NOT NULL,
	`cpid` varchar(50) NOT NULL,
	`wcg_verificationkey` varchar(40) NOT NULL,
	`lastupdate_start` int NOT NULL,
	`lastupdate` int NOT NULL,
	UNIQUE KEY `cpid` (`cpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabellenstruktur für Tabelle `boinc_werte`
CREATE TABLE IF NOT EXISTS `boinc_werte` (
	`id` int NOT NULL AUTO_INCREMENT,
	`project_shortname` varchar(15) NOT NULL,
	`credits` bigint NOT NULL,
	`time_stamp` int NOT NULL,
	PRIMARY KEY (`id`),
	KEY `project_shortname` (`project_shortname`),
	KEY `time_stamp` (`time_stamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- Tabellenstruktur für Tabelle `boinc_werte_day`
CREATE TABLE IF NOT EXISTS `boinc_werte_day` (
	`id` int NOT NULL AUTO_INCREMENT,
	`project_shortname` varchar(15) NOT NULL,
	`total_credits` bigint NOT NULL,
	`time_stamp` int NOT NULL,
	PRIMARY KEY (`id`),
	KEY `project_shortname` (`project_shortname`),
	KEY `time_stamp` (`time_stamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
