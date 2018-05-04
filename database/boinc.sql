-- phpMyAdmin SQL Dump
	-- version 3.4.11.1deb2+deb7u8
	-- http://www.phpmyadmin.net
	-- 
	-- Host: localhost
	-- Erstellungszeit: 27. Apr 2017 um 23:15
	-- Server Version: 5.5.54
-- PHP-Version: 5.4.45-0+deb7u8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
	-- Datenbank: `boinc`
--

-- --------------------------------------------------------

--
	-- Tabellenstruktur für Tabelle `boinc_grundwerte`
--


-- Fragen:
-- für alle nachfolgenden int und bigint - warum unterschiedliche (x)-Werte für die Länge? Haben keine Bedeutung hier.
-- nicht int(1), eher tinyint oder gleich bit/boolean.
-- warum MyISAM? Besser gleich InnoDB.
-- warum gesetztes Auto increment?
-- warum latin1?

CREATE TABLE IF NOT EXISTS `boinc_grundwerte` (
	`project` varchar(50) NOT NULL,
	`project_userid` int(10) NOT NULL,
	`authenticator` varchar(40) NOT NULL,
	`url` varchar(100) NOT NULL,
	`project_homepage_url` varchar(100) NOT NULL,
	`begin_credits` bigint(20) NOT NULL,
	`project_status` int(1) NOT NULL,
	`project_shortname` varchar(15) NOT NULL,
	`total_credits` bigint(20) NOT NULL,
	`pending_credits` bigint(20) NOT NULL,
	`results_ready` bigint(20) NOT NULL,
	`expavg_credit` bigint(20) NOT NULL,
	`expavg_time` int(10) NOT NULL,
	`project_rank_total_credit` int(10) NOT NULL,
	`project_rank_expavg_credit` int(11) NOT NULL,
	`create_time` int(10) NOT NULL,
	`country` varchar(50) NOT NULL,
	`user_name` varchar(50) NOT NULL,
	`user_url` varchar(50) NOT NULL,
	`team_id` int(10) NOT NULL,
	`team_name` varchar(50) NOT NULL,
	`team_url` varchar(50) NOT NULL,
	`team_rank_total_credit` int(10) NOT NULL,
	`team_rank_expavg_credit` int(10) NOT NULL,
	`team_member_count` int(10) NOT NULL,
	`computer_count` int(6) NOT NULL,
	`active_computer_count` int(6) NOT NULL,
	UNIQUE KEY `project_shortname` (`project_shortname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
	-- Tabellenstruktur für Tabelle `boinc_user`
--

CREATE TABLE IF NOT EXISTS `boinc_user` (
	`language` varchar(3) NOT NULL,
	`boinc_name` varchar(50) NOT NULL,
	`wcg_name` varchar(50) NOT NULL,
	`team_name` varchar(50) NOT NULL,
	`cpid` varchar(50) NOT NULL,
	`wcg_verificationkey` varchar(40) NOT NULL,
	`lastupdate_start` int(11) NOT NULL,
	`lastupdate` int(11) NOT NULL,
	UNIQUE KEY `cpid` (`cpid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
	-- Tabellenstruktur für Tabelle `boinc_werte`
--

CREATE TABLE IF NOT EXISTS `boinc_werte` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`project_shortname` varchar(15) NOT NULL,
	`credits` bigint(20) NOT NULL,
	`time_stamp` int(11) NOT NULL,
	`pending_credits` bigint(20) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `project_shortname` (`project_shortname`),
	KEY `time_stamp` (`time_stamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
	-- Tabellenstruktur für Tabelle `boinc_werte_day`
--

CREATE TABLE IF NOT EXISTS `boinc_werte_day` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`project_shortname` varchar(15) NOT NULL,
	`total_credits` bigint(20) NOT NULL,
	`pending_credits` int(11) NOT NULL,
	`time_stamp` int(11) NOT NULL,
	`rank` int(10) NOT NULL,
	`rank_team` int(10) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `project_shortname` (`project_shortname`),
	KEY `time_stamp` (`time_stamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
