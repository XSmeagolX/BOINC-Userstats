<?php
	// -----------------------------------------------------------------------------------
	// In dem folgenden Block bitte die Variablen mit Werten entsprechend der Anmerkung vornehmen
	// Change the values of the following section as descriped
	// -----------------------------------------------------------------------------------
	
	// Anzeigen von Fehlermeldungen
	// Show Error-Messages
	error_reporting(E_ALL);
	ini_set ('display_errors', 'Off'); // für Testumgebung auf 'On' setzen - set to 'On' for test environment

	// Deine Zeitzonenbezeichnung. Wird in der Infobar neben den Zeitangabe für das letzte Update angezeigt. 
	// Hiermit wird auch automatisch auf Sommer-/Winterzeit umgesetzt und die Zeitleiste in den Charts berechnet.
	// Der Wert für timezone_name muss mit php interpretierbar sein.
	// http://php.net/manual/timezones.php
	// Your Timezone name. Will be shown in the Infobar next to the last update dates.
	// This will automatically support Daylight Savings on the timeline of your Charts.
	// This timezone_name has to be supported by php!
	// http://php.net/manual/timezones.php
	$my_timezone = "Europe/Berlin"; // Format "Kontinent/Stadt"

	// Zeitzonen Einstellungen  // Timezone Settings
	// Deine Zeitzone  // Enter your timezone
	date_default_timezone_set($my_timezone);
	
	// Diese Variable bitte NICHT ändern, ansosten werden deine Werte falsch berechnet!
	// Do NOT change the this option, otherwise your data will be calculated wrong!
	$useUTCHighchartsOption = "true"; // default = "true"

	// Option zum Aktivieren der Highchart Export Funktion in den Charts (JPG, PNG, SVG etc.)
	// Option to activate Highchart export function in charts (JPG, PNG, SVG etc.)
	$highchartExport = false; //default = "false"

	// Version einbinden   
	// Include version
	include "version.php"; // Den Inhalt dieser Datei nicht verändern!    // Do not change the content of this file

	// Pfad zur Datenbank-Verbindung     
	// Path to database connect
	include "../database/boinc_db_connect.php";
	
	// Daten für Impressum 
	// Data for Disclaimer
	$hp_username = "Dein Name";
	$hp_email = "deine@email.adresse";
	
	// Navbar - DAS DEAKTIVIEREN FÜHRT ZU FEHLERHAFTER DESIGN-DARSTELLUNG
	// Navbar - set to false, will result in design error
	$showNavbar = true; // true - wenn angezeigt werden soll, ansonten auf false setzen. Standard ist true

	// Konfiguration des Brand-Logos in der Navbar
	// Configure the brand logo in Navbar
	$brand_logo = "./assets/images/brand_logo.png"; //das kleine Logo oben links neben dem Namen in der Navbar  // link to the image-file for the brand-logo top left
	$hp_nav_brand_link = "./index.php"; //wohin der Link auf dem Brand-Logo führen soll   // the target of the link on the brand logo

	// individuelle Links
	// Individual Links
	$showLinks = false; // true - wenn die folgenden Links in der Navbar angezeigt werden sollen  // if "true", those links and names will be shown in the navbar addtionally
	$hp_nav_name01 = "Link-Name#1";
	$hp_nav_link01 = "http://link.zu.Link-Name#1";
	$hp_nav_name02 = "Link-Name#2";
	$hp_nav_link02 = "http://link.zu.Link-Name#2";
	$hp_nav_name03 = "Link-Name#3";
	$hp_nav_link03 = "http://link.zu.Link-Name#3";
	
	// Dies sind Info-Links zur HP der Userstats, SETI.Germany, GitHub-Projekt-Seite
	// show the Links to the Developer Sites
	$showMoreLinks = true; // true - wenn Links zu Seiten des Programmierers (XSmeagolX) angezeigt werden sollen, Standard ist true
	$showMoreLinksName = "weitere Links";

	// Header Hintergrund
	// Header Background
	$header_backround_url = "./assets/images/header_background.jpg"; //Hier den Link zu deiner Header-Hintergrundgrafik einfuegen

	// SG-WCG-Badges
	// Die Badge-Grafik kann man auch per cronjob 1x täglich in sein Webverzeichnis kopieren und dann hier relativ verlinken.
	// Hier kannst du die WCG-Badges des Badge-Generators von SETI.Germany integrieren. 
	// Hinter badge_ folgt dein WCG-Name, danach _ gefolgt von der Anzahl an Reihen. 0 steht für alle Badges in einer Reihe (halte ich am sinnvollsten)
	// You can fetch this signature by a daylie cronjog as well and link them as a relative link
	// to configure this signature change your WCG-Name (XSmeagolX) and the number of rows (0) that should be used
	$showSgWcgBadges = false; // true - wenn angezeigt werden soll, ansonsten auf false setzen. Standard ist false
	$linkSgWcgBadges = "https://seti-germany.de/wcg/badge_XSmeagolX_0.png"; // WCG-Badges von XSmeagolX, alle in einer Reihe

	// User BOINC-Badges
	// Die Badge-Grafik kann man auch per cronjob 1x täglich in sein Webverzeichnis kopieren und dann hier relativ verlinken.
	// Dafür ist die Einrichtung der Badges über die Registrierung auf der Homepage von http://signature.statseb.fr notwendig.
	// You can fetch this signature by a daylie cronjob as well and link them as a relative link
	// You need to register and configure a signature on http://signature.statsseb.fr
	// $showUserBadges = false; // true - wenn angezeigt werden soll, ansonsten auf false setzen. Standard ist false
	// $linkUserBadges = "https://signature.statseb.fr/sig-12.png"; //Hier den Link zu Deinem User-Badge von http://signature.statseb.fr einfuegen
	$showstatsebBadges = false; // true - wenn angezeigt werden soll, ansonsten auf false setzen. Standard ist false
	$linkstatsebBadges = "https://signature.statseb.fr/sig-12.png"; //Hier den Link zu Deinem User-Badge von http://signature.statseb.fr einfuegen
	$showFreeDCBadges = false; // true - wenn das Badgebanner von Free-DC angezeigt werden soll. Standard ist false
	$linkFreeDCBadges = "https://stats.free-dc.org/badgesbanner.php?cpid="; // Der Link zum Badgebanner von Free-DC. Die cpid muss in der boinc_user hinterlegt sein
	
	// WCG-Badge-Signatur
	// Die WCG-Signatur kann man auch per cronjob 1x täglich in sein Webverzeichnis kopieren und dann hier relativ verlinken.
	// Dafür ist die Einrichtung der WCG-Signatur auf der Homepage von wcgsig.com notwendig (oben in der Mitte - Request a signature).
	// You can fetch this signature by a daylie cronjob as well an link them as a relative link
	// You need to have a configure your WCG-Signature on wcgsig.com
	$showWcgLogo = false; // true - wenn angezeigt werden soll, ansonsten auf false setzen. Standard ist false
	$linkWcgSig = "http://wcgsig.com/653215.gif"; //Hier den Link zu deinem WCG-Logo von http://wcgsig.com einfuegen
	
	// Hier dein Teamname und die URL zu der Homepage deines Teams eintragen
	// Your Teamname, Homepage of your team
	// Falls die Links zum Dev nicht angezeigt werden sollen, oder man ist Mitglied eines anderen Teams,
	// so kann hier individuell der Link zum BOINC-Team aktiviert werden
	$hasTeamHp = false; // true - wenn angezeigt werden soll, ansonten auf false setzen. Standard ist false
	$teamHpName = "SETI.Germany";
	$teamHpURL = "https://www.seti-germany.de";
	
	// Hier die URL zu deinen Statistiken bei boincstats.com
	// Link to your stats on boincstats.com. Set to true will activate a link in the nav-bar
	$hasBoincstats = false; // true - wenn angezeigt werden soll, ansonten auf false setzen. Standard ist false
	$linkNameBoincstats = "BOINCStats";
	$linkBoincstats = "https://boincstats.com/de/stats/-5/user/detail/15232873522/projectList"; //Link zu boincstats.com von XSmeagolX
	
	// Hier die URL zu SG-Stats von XSmeagolX aktivieren
	// set to "true" will show a link to team statistics of Team SETI.Germany
	$hasSGStats = true; // true - wenn angezeigt werden soll, ansonten auf false setzen. Standard ist true
	$linkNameSGStats = "SG-Stats";
	$linkSGStats = "https://stats.seti-germany.de"; //Link zu den SG-Stats von XSmeagolX

	// Hier die URL zu den laufenden WUs, falls Du diese mit Boinctasks veröffentlichst
	// wenn ja, nutze bitte meine boinctasks-Vorlage
	// if you use BoincTasks and publish your tasks to the internet, set the link to the tasks.html-file on the web
	// be aware to use my boinctasks-template
	$hasBoinctasks = false; // true - wenn angezeigt werden soll, ansonten auf false setzen. Standard ist false
	$linkNameBoinctasks = "Tasks";
	$linkBoinctasks = "./tasks.php"; // Link zu den laufenden WUs von XSmeagolX
	$linkUploadFileBoinctasks = "./tasks/tasks.html"; // Link zum Upload-File von Boinctasks

	// Hier besteht die Option, deine Computer der Seite mit den laufenden Berechnungen hinzuzufügen
	// Diese müssen (derzeit noch) in der Datei tasks.php ab Zeile 73 manuell konfiguriert werden
	// Dort sind beispielhaft drei Computer aufgeführt
	// Option to show/hide your computers at the end of the task list
	// You need to configure the table in the file tasks.php starting at line 73, if this option is set to true
	// Three computers were preconfigured as an example
	$showHostsEndofTasks = false; // true - wenn angezeigt werden soll, ansonten auf false setzen. Standard ist false

	// Hier die URL zum WCG, wird im Seitenkopf angezeigt
	// Falls die "show more"-Links nicht angezeigt werden sollen, so kann hier individuell der Link zum WCG aktiviert werden
	// Invite-Link to World Community Grid
	$hasWcg = false; // true - wenn angezeigt werden soll, ansonten auf false setzen. Standard ist false
	$linkNameWcg = "WCG";
	$linkWcg = "https://join.worldcommunitygrid.org/?recruiterId=653215&teamId=4VVG5BDPP1";

	// Option zum Anzeigen eines Links zum Aktualisieren der Pending Credits
	// Option to show Link to refresh pending credits
	$hasPendings = true; // true - wenn angezeigt werden soll, ansonten auf false setzen. Standard ist false
	$linkNamePendings = "Pendings";
	$linkPendings = "./pendings.php"; // Link zu den laufenden WUs von XSmeagolX
	
	// Update-Check
	// Option zur automatischen Prüfung, ob ein Update verfügbar ist und dies in der Navbar angezeigt wird.
	// Option to automatically check for updates and show in Navbar if update is available
	// Wenn diese Option auf "true" gesetzt wird, so werden GET-Anfragen auf die Datei latest_release.xml auf meinem Webservers generiert, um die aktuelle Versionsnummer zu erhalten.
	// Diese Anfragen werden in meinem Apache-Log gespeichert. Eine Auswertung, Verarbeitung oder Weitergabe dieser Daten findet nicht statt.
	// If this option is set to "true", your server will generate GET-requests to my Webserver to the file latest_release.xml to get the latest version number.
	// Those will be stored in my apache web logfiles but those will not be analysed or published.
	$setUpdatecheck = false; // true - wenn angezeigt werden soll, ansonten auf false setzen. Standard ist true
	
	// Auswahl der Projekte fuer Tortendiagramm
	// Option for Pie Chart
	// Hier den Wert eintragen, ab welchem Prozentanteil die Projekte separat im Kuchen ausgegeben werden sollen (Standard 1 fuer ab 1%, kann aber auch 10, 2, 0.3 eingetragen werden)
	// set to a value (%), where projects should be shown individually in the pie chart. All project less than this value are "other". 
	// 1 für Projekte ab 1% Gesamtanteil
	// 0 für alle Projekte
	$separat = 0.9;

	// Nur für Testzwecke in der Entwicklung erfoderlich
	// Only for testing in development
	$timezoneoffset = 0; // DO NOT CHANGE!!!
?>
