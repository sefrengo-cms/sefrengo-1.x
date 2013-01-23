<?PHP
// Statische Inhalte

$template_lang['welcome_text']                  = 'Willkommen beim Sefrengo Setup.<br />Klicken Sie auf &quot;Weiter&quot; um die Installation zu starten.';

$template_lang['setup_kind_title']              = 'Installationsart w&#228;hlen';
$template_lang['setup_kind_title_text']         = 'Entscheiden Sie sich, welche Art der Installation Sie ausf&#252;hren m&#246;chten. Vor einem Update sollten Sie eine Sicherung durchf&#252;hren';
$template_lang['setup_kind_1_title']            = 'Komplettes Setup mit Beispielen und Modulen';
$template_lang['setup_kind_1_text']             = 'Wenn Sie zum ersten mal mit Sefrengo arbeiten und Sie  das
       Tutorial nicht nutzen wollen, empfiehlt sich diese Installationsart.
       Hier bekommen Sie einen schnellen Uerblick, wie Sefrengo arbeitet.
       Eine einfache Demo - Homepage wird mitinstalliert.';
$template_lang['setup_kind_2_title']            = 'Neuinstallation';
$template_lang['setup_kind_2_text']             = 'M&#246;chten Sie eine Webseite von Grund auf neu einrichten.';
$template_lang['setup_kind_3_title']            = 'Update von einer Vorg&#228;ngerversion';
$template_lang['setup_kind_3_text']             = 'W&#228;hlen Sie diese Installationsart, wenn Sie ihre bestehende
           Installation updaten wollen. Es wird empfohlen, vor der Ausf&#252;hrung
           des Updates die Datenbankdaten zu sichern.';


$template_lang['config_title']                  = 'Konfigurationsdatei herunterladen';
$template_lang['config_title_text']             = 'Sie k&#246;nnen jetzt die Konfigurationsdatei herunterladen und in Ihre Installation einspielen.';
$template_lang['config_ok']                     = 'Die Konfigurationsdatei wurde erstellt!';
$template_lang['config_download']               = 'Konfigurationsdatei downloaden';
$template_lang['config_text']                   = 'Laden Sie sich bitte nun die Konfigurationsdatei "config.php"
         herunter und kopieren Sie diese Datei mit einem FTP-Client auf ihren
         Server in das Verzeichnis "backend/inc/".
        </p><p>
         Eine eventuell schon existierende Konfigurationsdatei kann &#252;berschrieben
         werden. Klicken Sie danach auf <strong>"<!--[next]--> &raquo;"</strong> um zum letzten Schritt
         der Installationsroutine zu gelangen.';

$template_lang['account_title']                 = 'Administrator Kennwort festlegen';
$template_lang['account_title_text']            = 'Bitte geben Sie das Kennwort f&#252;r den Administrator dieser Installation an. Bitte w&#228;hlen Sie ein sicheres Kennwort.';
$template_lang['account_adminpass']             = 'Passwort :';
$template_lang['account_adminpass1']            = 'Passwort wiederholen :';

$template_lang['mysql_title']                   = 'Datenbank Zugangsdaten';
$template_lang['mysql_title_text']              = 'Bitte die erforderlichen Daten eingeben! Falls Sie diese Daten nicht haben, fragen Sie bei Ihrem Provider nach!';
$template_lang['mysql_host']                    = 'Hostname des Datenbankservers (meist localhost)';
$template_lang['mysql_db']                      = 'Name der Datenbank';
$template_lang['mysql_prefix']                  = 'Tabellenpr&#228;fix (wird vor der Tabelle eingef&#252;gt)';
$template_lang['mysql_user']                    = 'Datenbank-Username';
$template_lang['mysql_pass']                    = 'Datenbank-Passwort';

$template_lang['path_title']                    = 'Pfadangaben festlegen';
$template_lang['path_title_text']               = 'Bitte geben Sie die erforderlichen Daten ein. Im Zweifelsfall fragen Sie die Werte bitte bei Ihrem Provider nach.';
$template_lang['path_path']                     = 'Systempfad:';
$template_lang['path_url']                      = 'URL:';

$template_lang['finish_title']                  = 'Herzlichen Gl&#252;ckwunsch, Sie haben Sefrengo CMS erfolgreich installiert!';
$template_lang['finish_title_text']             = 'Vergessen Sie nicht die Zugriffsrechte der folgenden Dateiordner korrekt zu setzen, damit Sefrengo CMS korrekt funktioniert.';
$template_lang['finish_text']                   = 'Wenn Sie die config.php mit einem FTP-Client auf ihren Server
          geladen haben und die Dateirechte entsprechend angepasst haben,
          k&#246;nnen Sie sich nun mit dem <strong>Usernamen "admin" </strong>
          und Ihrem <strong>Passwort</strong> anmelden.
          </p><p>
         <em>Bitte l&#246;schen Sie aus Sicherheitsgr&#252;nden auch den kompletten
         Ordner "setup" aus Ihrer Installation.</em>';
$template_lang['finish_folder_title']           = 'Diese Ordner ben&#246;tigen die Dateirechte auf rwxrwxrwx (oder 777)';

$template_lang['finish_update_title']           = 'Herzlichen Gl&#252;ckwunsch, das Update wurde erfolgreich ausgef&#252;hrt!';
$template_lang['finish_update_title_text']      = 'Sie k&#246;nnen sich jetzt mit ihren gewohnten Daten einloggen.
          </p><p>
           <em>Bitte l&#246;schen Sie aus Sicherheitsgr&#252;nden auch den kompletten
           Ordner "setup" aus Ihrer Installation.</em>';

$template_lang['insert_title']                  = 'Sie haben alle erforderlichen Daten eingegeben.';
$template_lang['insert_title_text']             = 'Es werden nun alle erforderlichen Daten in Ihre Datenbank eingef&#252;gt. Klicken Sie nun bitte auf "<!--[next]--> &raquo;" und warten Sie einen Augenblick.';
$template_lang['insert_text']                   = 'Sie haben alle erforderlichen Daten angegeben.
          </p><p>
           Es werden nun alle erforderlichen Daten in ihre Datenbank eingef&#252;gt.
           Klicken Sie nun bitte auf <strong>"<!--[next]--> &raquo;"</strong> und warten Sie einen Augenblick.';

$template_lang['check_title']                   = 'Die eingespielte Version ist nicht akuell';
$template_lang['check_title_text']              = 'Es wird nun ein Update gestartet.';

$template_lang['license_title']                 = 'Lizenzbedingungen';
$template_lang['license_title_text']            = 'Sie m&#252;ssen die Lizenzbestimmungen von Sefrengo akzeptieren, um mit dem Setup fortfahren zu k&#246;nnen.';
$template_lang['license_accept']                = 'Ich akzeptiere die Sefrengo Lizenzbedingungen';

$template_lang['pretest_title']                 = 'Pre-Test f&#252;r die Installation';
$template_lang['pretest_title_text']            = 'Wenn einer der folgenden Punkte <img src="templates/img/warning.gif" alt="warning" title="warning" />
           gekennzeichnet ist, sollten Sie Ihre Konfiguration &#252;berpr&#252;fen.
           Ansonsten kann es sein, dass Ihre Installation nicht korrekt
           funktioniert.';
$template_lang['pretest_check']                 = 'PHP-Check';
$template_lang['pretest_config']                = 'PHP-Einstellungen';

$template_lang['test_title']                    = 'Ordnerrechte der Konfiguration testen';
$template_lang['test_title_text']               = 'Bitte pr&#252;fen Sie, ob alle Rechte korrekt gesetzt sind. Mit "<img src="templates/img/warning.gif" alt="warning" title="warning" />"  markierte Punkte k&#246;nnen Fehler verursachen.
         Ansonsten kann es sein das Ihre Installation nicht korrekt funktioniert.
         Die Schreibrechte werden f&#252;r Windowssysteme nicht getestet.';
$template_lang['test_text']                     = 'Klicken Sie "Zum Login &raquo;", um sich mit dem <strong>Usernamen "admin" </strong> und Ihrem gew&#228;hlten <strong>Passwort</strong> anzumelden.';
$template_lang['test_folder']                   = 'Pr&#252;fung der Ordner-Schreibrechte';
$template_lang['test_config']                   = 'Konfiguration';

$template_lang['convert_title']                 = 'Datenbankinhalte werden angepasst.';
$template_lang['convert_title_text']            = 'Es werden nun alle Datenbankinhalte in den Zeichensatz UTF-8 konvertiert. ';
$template_lang['convert_text']                  = 'Je nach Datenbankgr&#246;&szlig;e kann dies mehrere Minuten dauern.
         Es gehen dabei keine Tabelleninhalte verloren. Sie finden Ihre alten
         Daten nach dem Konvertiervorgang in der MySql Datenbank unter
         "&lt;TABELLENNAME&gt;_backup" wieder.';
$template_lang['convert_text_2']                = 'Klicken Sie nach dem Konvertiervorgang bitte auf <strong>"<!--[next]--> &raquo;"</strong> und warten Sie einen Augenblick.';


$template_lang['next']                          = 'Weiter';
$template_lang['back']                          = 'Zur&#252;ck';


// Dynamische Inhalte
$cms_lang['error']					= '<b>FEHLER</b>';
$cms_lang['back']					= '<!--[back]-->';
$cms_lang['again']					= 'erneut Pr&#252;fen';
$cms_lang['adminpass_error']		= $cms_lang['error'].' Fehlerhaftes Administrator-Passwort. Passwort und Wiederholung stimmen nicht &#252;berein oder nicht erlaubte Zeichen verwendet: [\']["][#][<][>]<br><br>';
$cms_lang['path_error']				= $cms_lang['error'].' Der \'root_path\' exestiert nicht!<br><br>';
$cms_lang['connection_error1']		= $cms_lang['error'].' Fehlerhaftes Tabellen-Pr&#228;fix. Erlaubte Zeichen: [a-z][A-Z][0-9][-][_]<br><br>';
$cms_lang['connection_error2']		= $cms_lang['error'].' Bitte &#252;berpr&#252;fen Sie HOST, USERNAME UND PASSWORT<br><br>';
$cms_lang['connection_error3']		= $cms_lang['error'].' Die Datenbank "';
$cms_lang['connection_error4']		= '" existiert nicht oder ist nicht erreichbar! &#220;berpr&#252;fen Sie bitte, ob diese Datenbank schon angelegt ist.<br><br>';
$cms_lang['connection_error5']		= $cms_lang['error'].' Das Update kann nicht durchgef&#252;hrt werden, da keine Sefrengo - Version in der Datenbank gefunden wurde. Bitte &#252;berpr&#252;fen Sie den Datenbanknamen und ggf. das Prefix';
$cms_lang['path_error']				= $cms_lang['error'].' Der \'root_path\' exestiert nicht!<br><br>';

$cms_lang['info']					= 'Info';

$cms_lang['pretest_version']		= 'Ihre PHP Version: ';
$cms_lang['pretest_recommended']	= 'Empfohlen ';
$cms_lang['pretest_MySQL']			= 'Eine Mysql Version gr&#246;&szlig;er oder gleich 3.23.58 ist erforderlich und der Mysql-Support f&#252;r PHP muss installiert sein, damit Sie mit Sefrengo arbeiten k&#246;nnen.';
$cms_lang['pretest_zlib']			= 'Empfohlen, damit Sefrengo komprimierte tar Archive verarbeiten kann. Sefrengo Plugins liegen in der Regel als komprimierte (*.cmsplugin) tar Archive vor.';
$cms_lang['pretest_gdlib']			= 'Empfohlen zum Erstellen der Thumbnails ';
$cms_lang['pretest_zip']			= 'Ist diese Bibliothek installiert, k&#246;nnen im Sefrengo Dateimanager zip- Dateien hochgeladen und auf dem Server entpackt werden.';
$cms_lang['pretest_safe_mode']		= 'Je nach Serverkonfiguration kann es bei aktiviertem Safemode (Safemode = on) zu Problemen kommen. Falls auf ihrem Webspace der Safemode aktiviert sein sollte, versuchen Sie bitte nach der Installation im Sefrengo Dateimanager Ordner anzulegen und dort Dateien hochzuladen. Funktioniert dies, k&#246;nnen Sie Sefrengo auch mit aktiviertem Safemode nutzen. Andernfalls wenden Sie sich bitte an Ihren Provider.';
$cms_lang['pretest_file_uploads']	= 'Zwingend erforderlich, damit in Sefrengo Dateien hochgeladen werden k&#246;nnen.';

$cms_lang['test_file_ok']	        = 'ist vorhanden in backend/inc/';
$cms_lang['test_file_not_ok']	    = 'ist nicht vorhanden in backend/inc/';
$cms_lang['test_no_folder']			= 'Nicht vorhanden';
$cms_lang['test_system_test']		= 'Dieser Test ist nur f&#252;r Linuxsysteme verf&#252;gbar';

$cms_lang['show_txt_only_by_update']		= '<div align="center"><b>Auch wenn Sie die Updateoption gew&#228;hlt haben, m&#252;ssen Sie die Konfigurationsdatei neu herunterladen!"</b></div>';
$this -> cms_lang['adminpass_error']
?>
