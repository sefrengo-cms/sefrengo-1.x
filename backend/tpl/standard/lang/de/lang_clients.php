<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['clients_new_client'] = 'Neues Projekt';
$cms_lang['clients_submit'] = 'Abschicken';
$cms_lang['clients_clients'] = 'Projekte';
$cms_lang['clients_headline'] = 'Projekte / Sprachen';
$cms_lang['clients_desc'] = 'Beschreibung';
$cms_lang['clients_actions'] = 'Aktionen';
$cms_lang['clients_lang_start'] = 'Globale Startsprache setzen';
$cms_lang['clients_lang_edit'] = 'Sprache bearbeiten / umbenennen';
$cms_lang['clients_lang_delete'] = 'Sprache l&ouml;schen';
$cms_lang['clients_abort'] = 'Abbrechen';
$cms_lang['clients_lang_new'] = 'Neue Sprache';
$cms_lang['clients_charset'] = 'Sprachcodierung';
$cms_lang['clients_collapse'] = 'Zuklappen';
$cms_lang['clients_expand'] = 'Aufklappen';
$cms_lang['clients_make_new_lang'] = 'Neue Sprache anlegen';
$cms_lang['clients_modify'] = 'Projektnamen &auml;ndern';
$cms_lang['clients_config'] = 'Projekteinstellungen / Projekt konfigurieren';
$cms_lang['clients_delete'] = 'Projekt l&ouml;schen';
$cms_lang['clients_client'] = 'Projekt';
$cms_lang['clients_client_desc'] = 'Projektbeschreibung';
$cms_lang['clients_client_path'] = 'Projektpfad';
$cms_lang['clients_client_url'] = 'Projekturl';
$cms_lang['clients_client_directory'] = 'Verzeichnis anlegen';
$cms_lang['clients_client_start_lang'] = 'Startsprache';
$cms_lang['clients_lang_desc'] = 'Sprachbeschreibung';
$cms_lang['clients_lang_charset'] = 'Sprachcodierung';

//Erfolgsmeldungen
$cms_lang['success_delete_lang'] = 'Sprache erfolgreich gel&ouml;scht.';
$cms_lang['success_delete_client'] = 'Projekt erfolgreich aus der Datenbank gel&ouml;scht. Bitte l&ouml;schen Sie das physikalische Verzeichnis im Dateisystem noch nachtr&auml;glich von Hand';
$cms_lang['success_new_lang'] = 'Sprache erfolgreich angelegt.';
$cms_lang['success_new_client'] = 'Neues Projekt erfolgreich angelegt.';

//Errors
$cms_lang['err_cant_make_path'] = "Verzeichnis konnte nicht erstellt werden. Bitte &uuml;berpr&uuml;fen Sie die Rechte des Dateisystems. Dieser Fehler kann auch auftreten, wenn das entsprechende Verzeichnis schon existiert.";
$cms_lang['err_cant_extract_tar']   = "Die Projektvorlage konnte nicht entpackt werden. TAR- Archiv defekt?";

?>