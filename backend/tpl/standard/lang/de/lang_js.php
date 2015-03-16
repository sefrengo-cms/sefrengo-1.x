<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

	include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_upl.php');

$cms_lang['js_action']					= 'Aktionen';
$cms_lang['js_cancel_form']			= 'Eingabe abbrechen, zur&uuml;ck zur letzten Ansicht';
$cms_lang['js_created']				= 'Erstellt am';
$cms_lang['js_description']			= 'Beschreibung';
$cms_lang['js_editor']					= 'Redakteur';
$cms_lang['js_filename']				= 'Dateiname / Url';
$cms_lang['js_file_content']			= 'Javascript-Code';
$cms_lang['js_file_delete']			= 'Datei l&ouml;schen';
$cms_lang['js_file_delete_confirm']	= 'Best&auml;tige L&ouml;schen der Datei';
$cms_lang['js_file_delete_cancel']		= 'Abbrechen';
$cms_lang['js_file_duplicate']			= 'Datei kopieren';
$cms_lang['js_file_edit']				= 'Datei bearbeiten';
$cms_lang['js_file_new']				= 'Neue Datei';
$cms_lang['js_fileimport']				= 'Javascript-Datei hochladen';
$cms_lang['js_import']					= 'Datei importieren';
$cms_lang['js_export']					= 'Datei exportieren';
$cms_lang['js_lastmodified']			= 'Ge&auml;ndert am';
$cms_lang['js_nofile']					= 'Es gibt keine Javascript-Datei.';
$cms_lang['js_nofiles']				= 'Es gibt keine Javascript-Datei.';
$cms_lang['js_submit_form']			= '&Auml;nderungen speichern';
$cms_lang['js_file_download']         	= 'Datei herunterladen';
$cms_lang['js_edit_rights']         	= 'Rechte bearbeiten';

// 12xx = JS
$cms_lang['err_1201']					= 'Name f&uuml;r die Javascript-Datei fehlerhaft oder nicht angegeben.';
$cms_lang['err_1202']					= 'Es existiert schon eine Javascript-Datei mit diesem Namen. Bitte w&auml;hle einen anderen Namen.';
$cms_lang['err_1203']					= 'Javascript-Datei konnte nicht angelegt werden.';
$cms_lang['err_1204']					= 'Javascript-Datei existiert nicht.';
$cms_lang['err_1205']					= 'Javascript-Datei konnte nicht exportiert werden.';
$cms_lang['err_1206']					= 'Javascript-Datei erfolgreich exportiert.';
$cms_lang['err_1207']					= 'Javascript-Datei konnte nicht importiert werden.';
$cms_lang['err_1208']					= $cms_lang['err_1207']. ' Keine Daten gefunden.';
$cms_lang['err_1209']					= $cms_lang['err_1207']. ' Es existiert schon eine Datei gleichen Namens.';
$cms_lang['err_1210']					= 'Javascript-Datei erfolgreich importiert.';
$cms_lang['err_1211']					= 'Javascript-Datei wird verwendet. L&ouml;schen nicht m&ouml;glich.';
$cms_lang['err_1212']					= 'Javascript-Datei konnte nicht ermittelt werden. L&ouml;schen nicht m&ouml;glich.';
$cms_lang['err_1213']					= $cms_lang['err_1205']. ' Keine Daten gefunden.';
$cms_lang['err_1214']					= $cms_lang['err_1205']. ' Es existiert schon eine Datei gleichen Namens.';
$cms_lang['err_1215']					= 'Javascript-Datei konnte nicht aktualisiert werden! Bitte pr&uuml;fen Sie die Systemrechte.';
$cms_lang['err_1216']				   	= 'Javascript-Datei konnte nicht gel&ouml;scht werden! Bitte pr&uuml;fen Sie die Systemrechte.';
$cms_lang['err_1217']				   	= 'Upload der Javascript-Datei fehlgeschlagen. Inhalt konnte nicht in Datenbank eingetragen werden.';
$cms_lang['err_1218']					= 'Javascript-Datei konnte nicht aktualisiert werden!';
?>
