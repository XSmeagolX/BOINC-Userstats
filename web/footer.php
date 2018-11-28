			<footer class = "footer bg-dark">
				<div class = "text-light">
						<div class = "row">
							<div class = "col-12 col-md-6 mx-auto texthellgrau text-center align-self-center">
							Diese Seite verwendet <a href = "https://www.highcharts.com"><i class="far fa-copyright"></i> Highcharts</a><br>
								<a href = "#" data-toggle = "modal" data-target = "#modalImpressum">Impressum</a> | 
								<a href = "#" data-toggle = "modal" data-target = "#modalDisclaimer">Disclaimer</a> | 
								<a href = "#" data-toggle = "modal" data-target = "#modalDatenschutz">Datenschutz</a> | 
								<a href = "https://github.com/XSmeagolX/Pers-nliche-BOINC-Userstats/blob/Version-6/LICENSE" target = "_new">License</a> 
							</div>
							<div class = "col-12 col-md-6 mx-auto texthellgrau text-center align-self-center order-first">
								<a href = "https://boinc-userstats.de" target = "_blank">BOINC Userstats</a> - Version 
								<?php if ($dev) { echo $dev_version; } else {echo $userstats_release_version;} ?><br>made with <font color = "#f57c7c"><i class="fas fa-heart"></i></font> by <a href = "https://timo-schneider.de" target = "_blank">Timo Schneider</a><br><font size = "1">XSmeagolX - Team <a href = "https://seti-germany.de"><img src = "./assets/images/sg_logo_klein.png"></a></font>
							</div>
						</div>
				</div>
			</footer>
			<!-- Modal -->
			<div class = "modal fade" id = "modalImpressum" tabindex = "-1" role = "dialog" aria-labelledby = "modalImpressum" aria-hidden = "true">
				<div class = "modal-dialog">
					<div class = "modal-content ">
						<div class = "modal-header">
							<h4 class = "modal-title" id = "modelTitleId">Impressum</h4>
							<button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
								<span aria-hidden = "true">&times;</span>
							</button>
						</div>
						<div class = "modal-body">
							Angaben gemäß § 5 TMG:<br>
							<br>
							<u>Kontakt:</u><br>
							<?=$hp_username; ?><br>
							<strong>E-Mail: </strong><?=$hp_email; ?>
						</div>
						<div class = "modal-footer">
							<button type = "button" class = "btn btn-default btn-simple" data-dismiss = "modal">OK</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class = "modal fade" id = "modalDisclaimer" tabindex = "-1" role = "dialog" aria-labelledby = "modalDisclaimer" aria-hidden = "true">
				<div class = "modal-dialog">
					<div class = "modal-content">
						<div class = "modal-header">
							<h4 class = "modal-title" id = "modelTitleId">Haftungsausschluss (Disclaimer)</h4>
							<button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
								<span aria-hidden = "true">&times;</span>
							</button>
						</div>
						<div class = "modal-body ">
							<strong>Haftung für Inhalte</strong>
							<br>
							Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.
							<br>
							<br>
							<strong>Haftung für Links</strong>
							<br>
							Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.
							<br>
							<br>
							<strong>Urheberrecht</strong>
							<br>
							Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.
						</div>
						<div class = "modal-footer">
							<button type = "button" class = "btn btn-default btn-simple" data-dismiss = "modal">OK</button>
						</div>
					</div>
				</div>
			</div> 
			
			<div class = "modal fade" id = "modalDatenschutz" tabindex = "-1" role = "dialog" aria-labelledby = "modalDatenschutz" aria-hidden = "true">
				<div class = "modal-dialog">
					<div class = "modal-content">
						<div class = "modal-header">
						<h4 class = "modal-title" id = "modelTitleId">Datenschutz</h4>
							<button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close">
								<span aria-hidden = "true">&times;</span>
							</button>
						</div>
						<div class = "modal-body">
							<strong>Auskunft, Löschung, Sperrung</strong>
							<br>
							Sie haben jederzeit das Recht auf unentgeltliche Auskunft über Ihre gespeicherten personenbezogenen Daten, deren Herkunft und Empfänger und den Zweck der Datenverarbeitung sowie ein Recht auf Berichtigung, Sperrung oder Löschung dieser Daten. Hierzu sowie zu weiteren Fragen zum Thema personenbezogene Daten können Sie sich jederzeit unter der im Impressum angegebenen Adresse an uns wenden.
							<br>
							<br>
							<strong>Cookies</strong>
							<br>
							Die Internetseiten verwenden teilweise so genannte Cookies. Cookies richten auf Ihrem Rechner keinen Schaden an und enthalten keine Viren. Cookies dienen dazu, unser Angebot nutzerfreundlicher, effektiver und sicherer zu machen. Cookies sind kleine Textdateien, die auf Ihrem Rechner abgelegt werden und die Ihr Browser speichert.
							<br>
							Die meisten der von uns verwendeten Cookies sind so genannte „Session-Cookies“. Sie werden nach Ende Ihres Besuchs automatisch gelöscht. Andere Cookies bleiben auf Ihrem Endgerät gespeichert, bis Sie diese löschen. Diese Cookies ermöglichen es uns, Ihren Browser beim nächsten Besuch wiederzuerkennen.
							<br>
							Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browser aktivieren. Bei der Deaktivierung von Cookies kann die Funktionalität dieser Website eingeschränkt sein.
							<br>
							<br>
							<strong>Server-Log-Files</strong>
							<br>
							Der Provider der Seiten erhebt und speichert automatisch Informationen in so genannten Server-Log Files, die Ihr Browser automatisch an uns übermittelt. Dies sind:
							<br>
							Browsertyp/ Browserversion
							verwendetes Betriebssystem
							Referrer URL
							Hostname des zugreifenden Rechners
							Uhrzeit der Serveranfrage
							<br>
							<br>
							Diese Daten sind nicht bestimmten Personen zuordenbar. Eine Zusammenführung dieser Daten mit anderen Datenquellen wird nicht vorgenommen. Wir behalten uns vor, diese Daten nachträglich zu prüfen, wenn uns konkrete Anhaltspunkte für eine rechtswidrige Nutzung bekannt werden.
							<br>
							<br>
							<strong>Kontaktformular</strong>
							<br>
							Wenn Sie uns per Kontaktformular Anfragen zukommen lassen, werden Ihre Angaben aus dem Anfrageformular inklusive der von Ihnen dort angegebenen Kontaktdaten zwecks Bearbeitung der Anfrage und für den Fall von Anschlussfragen bei uns gespeichert. Diese Daten geben wir nicht ohne Ihre Einwilligung weiter.
						</div>
						<div class = "modal-footer">
							<button type = "button" class = "btn btn-default btn-simple" data-dismiss = "modal">OK</button>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- force-min-height -->
    </body>
</html>
