<?php
	$showProjectHeader = false;
	$showPendingsHeader = false;
	$showTasksHeader = false;
	$showUpdateHeader = false;
	$showErrorHeader = true;

	include "./settings/settings.php";

	$query_getUserData = mysqli_query($db_conn, "SELECT * FROM boinc_user");
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

	if (!isset($_GET['error'])) { $errorcode = ""; } else { $errorcode = $_GET['error'];}
	if (!isset($_SERVER['HTTP_REFERER'])) { $HTTP_REFERER = ""; } else { $HTTP_REFERER = $_SERVER['HTTP_REFERER'];}
	if (!isset($_SERVER['REDIRECT_URL'])) { $REDIRECT_URL = ""; } else { $REDIRECT_URL = $_SERVER['REDIRECT_URL'];}
	if (!isset($_SERVER['REDIRECT_REQUEST_METHOD'])) { $REDIRECT_REQUEST_METHOD = ""; } else { $REDIRECT_REQUEST_METHOD = $_SERVER['REDIRECT_REQUEST_METHOD'];}
	if (!isset($_SERVER['REDIRECT_ERROR_NOTES'])) { $REDIRECT_ERROR_NOTES = ""; } else { $REDIRECT_ERROR_NOTES = $_SERVER['REDIRECT_ERROR_NOTES'];}

	$error_description = "";
	$err_de = "";
	$err_en = "";
	$err_fr = "";

	switch ($errorcode) {
		case "400": 
			$error_description = "400 Bad Request"; 
			$err_de = "Fehlerhafte Anfrage!</br>".
			"Ihr Browser (oder Proxy) hat eine ung&uuml;ltige Anfrage gesendet, die vom Server nicht beantwortet werden kann.";
			$err_en = "Bad request!</br>".
			"Your browser (or proxy) sent a request that this server could not understand.";
			$err_fr = "Demande incorrecte!</br>".
			"Votre navigateur (ou votre proxy) a envoy&eacute; une demande que ce serveur n'a pas comprise.";
			break;
		case "401": 
			$error_description = "401 Unauthorised"; 
			$err_de = "Authentisierung fehlgeschlagen!</br>".
			"Der Server konnte nicht verifizieren, ob Sie autorisiert sind, auf den URL ".$REDIRECT_URL." zuzugreifen. Entweder wurden falsche Referenzen (z.B. ein falsches Passwort) angegeben oder ihr Browser versteht nicht, wie die geforderten Referenzen zu &uuml;bermitteln sind.<br>".
			"Sofern Sie f&uuml;r den Zugriff berechtigt sind, &uuml;berpr&uuml;fen Sie bitte die eingegebene User-ID und das Passwort und versuchen Sie es erneut.";
			$err_en = "Authentication required!<br>".
			"This server could not verify that you are authorized to access the URL ".$REDIRECT_URL.". You either supplied the wrong credentials (e.g., bad password), or your browser doesn't understand how to supply the credentials required.<br>".
			"In case you are allowed to request the document, please check your user-id and password and try again.";
			$err_fr = "Autorisation requise!</br>".
			"Ce server n'a pas &eacute;t&eacute; en mesure de v&eacute;rifier que vous &ecirc;tes autoris&eacute; &agrave; acc&eacute;der &agrave; cette URL ".$REDIRECT_URL.". Vous avez ou bien fourni des coordonn&eacute;es erron&eacute;es (p.ex. mot de passe inexact) ou bien votre navigateur ne parvient pas &agrave; fournir les donn&eacute;es exactes.<br>".
			"Si vous &ecirc;tes autoris&eacute; &agrave; requ&eacute;rir le document, veuillez v&eacute;rifier votre nom d'utilisateur et votre mot de passe et r&eacute;essayer.";
			break;
		case "402": 
			$error_description = "402 Payment Required"; 
			break;
		case "403": 
			$error_description = "403 Forbidden"; 
			$err_de = "Zugriff verweigert!</br>".
			(($REDIRECT_URL != "") 
			? "Der Zugriff auf das angeforderte Verzeichnis ist nicht m&ouml;glich. Entweder ist kein Index-Dokument vorhanden oder das Verzeichnis ist zugriffsgesch&uuml;tzt." 
			: "Der Zugriff auf das angeforderte Objekt ist nicht m&ouml;glich. Entweder kann es vom Server nicht gelesen werden oder es ist zugriffsgesch&uuml;tzt.");
			$err_en = "Access forbidden!</br>".
			(($REDIRECT_URL != "") 
			? "You don't have permission to access the requested directory. There is either no index document or the directory is read-protected." 
			: "You don't have permission to access the requested object. It is either read-protected or not readable by the server.");
			$err_fr = "Acc&egrave;s interdit!</br>".
			(($REDIRECT_URL != "") 
			? "Vous n'avez pas le droit d'acc&eacute;der au r&eacute;pertoire demand&eacute;. Soit il n'y a pas de document index soit le r&eacute;pertoire est prot&eacute;g&eacute;." 
			: "Vous n'avez pas le droit d'acc&eacute;der &agrave; l'objet demand&eacute;. Soit celui-ci est prot&eacute;g&eacute;, soit il ne peut &ecirc;tre lu par le serveur.");
			break;
		case "404": 
			$error_description = "404 Not Found"; 
			$err_de = "Objekt nicht gefunden!".
			"</br>Der angeforderte URL konnte auf dem Server nicht gefunden werden.<br>".
			(($HTTP_REFERER != "") 
			? "Der Link auf der <a href='".$HTTP_REFERER."'>verweisenden Seite</a> scheint falsch oder nicht mehr aktuell zu sein. Bitte informieren Sie den Autor ".
			"<a href='".$HTTP_REFERER."'>dieser Seite</a> &uuml;ber den Fehler." 
			: "Sofern Sie den URL manuell eingegeben haben, &uuml;berpr&uuml;fen Sie bitte die Schreibweise und versuchen Sie es erneut.");
			$err_en = "Object not found!<br>".
			"The requested URL was not found on this server.<br>".
			(($HTTP_REFERER != "") 
			? "The link on the <a href='".$HTTP_REFERER."'>referring page</a> seems to be wrong or outdated. Please inform the author of ".
			"<a href='".$HTTP_REFERER."'>that page</a> about the error." 
			: "If you entered the URL manually please check your spelling and try again.");
			$err_fr = "Objet non trouv&eacute;!<br>".
			"L'URL demand&eacute;e n'a pas pu &ecirc;tre trouv&eacute;e sur ce serveur.<br>".
			(($HTTP_REFERER != "") 
			? "La r&eacute;f&eacute;rence sur <a href='".$HTTP_REFERER."'>la page cit&eacute;e</a> semble &ecirc;tre erron&eacute;e ou perim&eacute;e. Nous vous prions d'informer l'auteur de ".
			"<a href='".$HTTP_REFERER."'>cette page</a> de cette erreur." 
			: "Si vous avez tap&eacute; l'URL &agrave; la main, veuillez v&eacute;rifier l'orthographe et r&eacute;essayer.");
			break;
		case "405": 
			$error_description = "405 Das Ende des Internets"; 
			$err_de = "Du hast das Ende des Internets erreicht!".
			"</br>Mehr gibt es leider nicht mehr zu finden.<br>".
			"Sofern Du noch Freunde hast, rufe sie am besten gleich an und verabrede dich mit ihnen.".
			"Ich wünsche Dir noch viel Spaß im realen Leben, fern ab jeglichen Internets!";
			$err_en = "Object not found!<br>".
			"The requested URL was not found on this server.<br>".
			(($HTTP_REFERER != "") 
			? "The link on the <a href='".$HTTP_REFERER."'>referring page</a> seems to be wrong or outdated. Please inform the author of ".
			"<a href='".$HTTP_REFERER."'>that page</a> about the error." 
			: "If you entered the URL manually please check your spelling and try again.");
			$err_fr = "Objet non trouv&eacute;!<br>".
			"L'URL demand&eacute;e n'a pas pu &ecirc;tre trouv&eacute;e sur ce serveur.<br>".
			(($HTTP_REFERER != "") 
			? "La r&eacute;f&eacute;rence sur <a href='".$HTTP_REFERER."'>la page cit&eacute;e</a> semble &ecirc;tre erron&eacute;e ou perim&eacute;e. Nous vous prions d'informer l'auteur de ".
			"<a href='".$HTTP_REFERER."'>cette page</a> de cette erreur." 
			: "Si vous avez tap&eacute; l'URL &agrave; la main, veuillez v&eacute;rifier l'orthographe et r&eacute;essayer.");
			break;
	/*	case "405": 
			$error_description = "405 Method Not Allowed"; 
			$err_de = "Methode nicht erlaubt!</br>".
			"Die ".$REDIRECT_REQUEST_METHOD."-Methode ist f&uuml;r den angeforderten URL nicht erlaubt.";
			$err_en = "Method not allowed!</br>".
			"The ".$REDIRECT_REQUEST_METHOD." method is not allowed for the requested URL.";
			$err_fr = "M&eacute;thode interdite!</br>".
			"La m&eacute;thode ".$REDIRECT_REQUEST_METHOD." n'est pas utilisable pour l'URL demand&eacute;e.";
			break;
	*/
		case "406": 
			$error_description = "406 Not Acceptable"; 
			break;
		case "407": 
			$error_description = "407 Proxy Authentication Required"; 
			break;
		case "408": 
			$error_description = "408 Request Time-Out"; 
			$err_de = "Zeitlimit &uuml;berschritten!</br>".
			"Der Server konnte nicht mehr l&auml;nger auf die Beendigung der Browseranfrage warten; die Netzwerkverbindung wurde vom Server geschlossen.";
			$err_en = "Request time-out!</br>".
			"The server closed the network connection because the browser didn't finish the request within the specified time.";
			$err_fr = "Requ&ecirc;te trop longue !</br>".
			"Le serveur a ferm&eacute; la connection car le navigateur n'a pas fini la requ&ecirc;te dans le temps sp&eacute;cifi&eacute;.";
			break;
		case "409": 
			$error_description = "409 Conflict"; 
			break;
		case "410": 
			$error_description = "410 Gone";
			$err_de = "Objekt nicht mehr verf&uuml;gbar!<br>".
			"Der angeforderte URL existiert auf dem Server nicht mehr und wurde dauerhaft entfernt. Eine Weiterleitungsadresse ist nicht verf&uuml;gbar.<br>".
			(($HTTP_REFERER != "") 
			? "Bitte informieren Sie den Autor der ".
			"<a href='".$HTTP_REFERER."'>verweisenden Seite</a>, dass der Link nicht mehr aktuell ist." 
			: "Falls Sie einem Link von einer anderen Seite gefolgt sind, informieren Sie bitte den Autor dieser Seite hier&uuml;ber.");
			$err_en = "Resource is no longer available!<br>".
			"The requested URL is no longer available on this server and there is no forwarding address.<br>".
			(($HTTP_REFERER != "") 
			? "Please inform the author of the ".
			"<a href='".$HTTP_REFERER."'>referring page</a> that the link is outdated." 
			: "If you followed a link from a foreign page, please contact the author of this page.");
			$err_fr = "Cette ressource n'existe plus!<br>".
			"L'URL demand&eacute;e n'est plus accessible sur ce serveur et il n'y a pas d'adresse de redirection.<br>".
			(($HTTP_REFERER != "") 
			? "Nous vous prions d'informer l'auteur de ".
			"<a href='".$HTTP_REFERER."'>la page en question</a> que la r&eacute;f&eacute;rence n'est plus valable." 
			: "Si vous avez suivi une r&eacute;f&eacute;rence issue d'une page autre, veuillez contacter l'auteur de cette page.");
			break;
		case "411": 
			$error_description = "411 Length Required"; 
			$err_de = "Content-Length-Angabe fehlerhaft!</br>".
			"Die Anfrage kann nicht beantwortet werden. Bei Verwendung der ".$REDIRECT_REQUEST_METHOD."-Methode mu&szlig; ein korrekter <code>Content-Length</code>-Header angegeben werden. ";
			$err_en = "Bad Content-Length!</br>".
			"A request with the ".$REDIRECT_REQUEST_METHOD." method requires a valid <code>Content-Length</code> header.";
			$err_fr = "Longueur du contenu ill&eacute;gal!</br>".
			"Une requ&ecirc;te utilisant la m&eacute;thode ".$REDIRECT_REQUEST_METHOD." n&eacute;cessite un en-t&ecirc;te <code>Content-Length</code> (indiquant la longueur) valable.";
			break;
		case "412": 
			$error_description = "412 Precondition Failed"; 
			$err_de = "Vorbedingung verfehlt!</br>".
			"Die f&uuml;r den Abruf der angeforderten URL notwendige Vorbedingung wurde nicht erf&uuml;llt.";
			$err_en = "Precondition failed!</br>".
			"The precondition on the request for the URL failed positive evaluation.";
			$err_fr = "Pr&eacute;condition n&eacute;gative!</br>".
			"La pr&eacute;condition pour l'URL demand&eacute; a &eacute;t&eacute; &eacute;valu&eacute;e n&eacute;gativement.";
			break;
		case "413": 
			$error_description = "413 Request Entity Too Large"; 
			$err_de = "&Uuml;bergebene Daten zu gro&szlig;!</br>".
			"Die bei der Anfrage &uuml;bermittelten Daten sind f&uuml;r die ".$REDIRECT_REQUEST_METHOD."-Methode nicht erlaubt oder die Datenmenge hat das Maximum &uuml;berschritten.";
			$err_en = "Request entity too large!</br>".
			"The ".$REDIRECT_REQUEST_METHOD." method does not allow the data transmitted, or the data volume exceeds the capacity limit.";
			$err_fr = "Volume de la demande trop grand!</br>".
			"La m&eacute;thode ".$REDIRECT_REQUEST_METHOD." n'autorise pas le transfert de ces donn&eacute;es ou bien le volume des donn&eacute;es exc&egrave;de la limite de capacit&eacute;.";
			break;
		case "414": 
			$error_description = "414 Request-URL Too Large"; 
			$err_de = "&Uuml;bergebener URI zu gro&szlig;!</br>".
			"Der bei der Anfrage &uuml;bermittelte URI &uuml;berschreitet die maximale L&auml;nge. Die Anfrage kann nicht ausgef&uuml;hrt werden.";
			$err_en = "Submitted URI too large!</br>".
			"The length of the requested URL exceeds the capacity limit for this server. The request cannot be processed.";
			$err_fr = "L'URI demandee est trop longue!</br>".
			"La longueur de l'URL demand&eacute;e exc&egrave;de la limite de capacit&egrave; pour ce serveur. Nous ne pouvons donner suite &agrave; votre requ&ecirc;te.";
			break;
		case "415": 
			$error_description = "415 Unsupported Media Type"; 
			$err_de = "Nicht unterst&uuml;tztes Format!</br>".
			"Das bei der Anfrage &uuml;bermittelte Format (Media Type) wird vom Server nicht unterst&uuml;tzt.";
			$err_en = "Unsupported media type!</br>".
			"The server does not support the media type transmitted in the request.";
			$err_fr = "Type de m&eacute;dia invalide!</br>".
			"Le serveur ne supporte pas le type de m&eacute;dia utilis&eacute; dans votre requ&ecirc;te.";
			break;
		case "500": 
			$error_description = "500 Server Error"; 
			$err_de = "Serverfehler!<br>".
			(($REDIRECT_ERROR_NOTES != "") 
			? "Die Anfrage kann nicht beantwortet werden, da im Server ein interner Fehler aufgetreten ist. Fehlermeldung:<br>".$REDIRECT_ERROR_NOTES 
			: "Die Anfrage kann nicht beantwortet werden, da im Server ein interner Fehler aufgetreten ist. Der Server ist entweder &uuml;berlastet oder ein Fehler in einem CGI-Skript ist aufgetreten.");
			$err_en = "Server error!<br>".
			(($REDIRECT_ERROR_NOTES != "") 
			? "The server encountered an internal error and was unable to complete your request. Error message: <br>".$REDIRECT_ERROR_NOTES
			: "The server encountered an internal error and was unable to complete your request. Either the server is overloaded or there was an error in a CGI script.");
			$err_fr = "Erreur du serveur!<br>".
			(($REDIRECT_ERROR_NOTES != "") 
			? "Le serveur a &eacute;t&eacute; victime d'une erreur interne et n'a pas &eacute;t&eacute; capable de faire aboutir votre requ&ecirc;te. Message d'erreur: <br>".$REDIRECT_ERROR_NOTES 
			: "Le serveur a &eacute;t&eacute; victime d'une erreur interne et n'a pas &eacute;t&eacute; capable de faire aboutir votre requ&ecirc;te. Soit le server est surcharg&eacute; soit il s'agit d'une erreur dans le script CGI.");
			break;
		case "501": 
			$error_description = "501 Not Implemented"; 
			$err_de = "Anfrage nicht ausf&uuml;hrbar!</br>".
			"Die vom Browser angeforderte Aktion wird vom Server nicht unterst&uuml;tzt.";
			$err_en = "Cannot process request!</br>".
			"The server does not support the action requested by the browser.";
			$err_fr = "La requ&ecirc;te ne peut &ecirc;tre effectu&eacute;e!</br>".
			"Le serveur n'est pas en mesure d'effectuer l'action demand&eacute;e par le navigateur.";
			break;
		case "502": 
			$error_description = "502 Bad Gateway"; 
			$err_de = "Fehlerhaftes Gateway!<br>".
			"Der Proxy-Server erhielt eine fehlerhafte Antwort eines &uuml;bergeordneten Servers oder Proxies.<br>".
			($REDIRECT_ERROR_NOTES != "") ? $REDIRECT_ERROR_NOTES : "";
			$err_en = "Bad Gateway!<br>".
			"The proxy server received an invalid response from an upstream server.<br>".
			($REDIRECT_ERROR_NOTES != "") ? $REDIRECT_ERROR_NOTES : "";
			$err_fr = "Gateway incorrecte!<br>".
			"Le serveur proxy a re&ccedil;u une r&eacute;ponse incorrecte de la part d'un serveur sup&eacute;rieur.<br>".
			($REDIRECT_ERROR_NOTES != "") ? $REDIRECT_ERROR_NOTES : "";
			break;
		case "503": 
			$error_description = "503 Out of Resources"; 
			$err_de = "Zugriff nicht m&ouml;glich!</br>".
			"Der Server ist derzeit nicht in der Lage die Anfrage zu bearbeiten. Entweder ist der Server derzeit &uuml;berlastet oder wegen Wartungsarbeiten nicht verf&uuml;gbar. Bitte versuchen Sie es sp&auml;ter wieder. ";
			$err_en = "Service unavailable!</br>".
			"The server is temporarily unable to service your request due to maintenance downtime or capacity problems. Please try again later.";
			$err_fr = "Service inaccessible!</br>".
			"En raison de travaux de maintenance ou de probl&egrave;mes de capacit&eacute; le serveur n'est pas en mesure de r&eacute;pondre &agrave; votre requ&ecirc;te pour l'instant. Veuillez r&eacute;essayer plus tard.";
			break;
		case "504": 
			$error_description = "504 Gateway Time-Out"; 
			break;
		case "505": 
			$error_description = "505 HTTP Version not supported"; 
			break;
		default: 
			$error_description = "not supported";
			$err_de = "Irgendwas ist schief gelaufen!";
			$err_en = "Something went wrong!";
			$err_fr = "Quelque chose s'est mal passé";
	}

	include("./header.php"); 
?>

	<div class = "container text-center flex1">
<?php if (!$uups_error): ?><h1 class = "title text-center"><?php echo $error_description; ?></h1><?php endif; ?>
		<h5 class = "description text-center">
			<?php
			if ($uups_error) echo $uups_error_description;
			else
				if ($lang === "de") echo $err_de;
				elseif ($lang === "fr") echo $err_fr;
				else echo $err_en;
			?>
		</h5>					
	</div>

<?php include("./footer.php"); ?>
