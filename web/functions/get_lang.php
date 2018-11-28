<?php
    if (isset($_GET["lang"])) {$lang = $_GET["lang"];}
    elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$lang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
	} else $lang = "";

	if (file_exists("./lang/" . $lang . ".txt.php")) include "./lang/" . $lang . ".txt.php";
	else include "./lang/en.txt.php";
	
	if (file_exists("./lang/" . $lang . ".highstock.js")) include "./lang/" . $lang . ".highstock.js";
	else include "./lang/en.highstock.js";
?>