<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['upl_action']			  = 'Aktionen';
$cms_lang['upl_directoriesandfiles'] = 'Verzeichnisse / Dateien';
$cms_lang['upl_description']		  = 'Beschreibung';
$cms_lang['upl_cancel']			  = 'Abbrechen';
$cms_lang['upl_popupclose']		  = 'Fenster schliessen';
$cms_lang['upl_dirisempty']		  = 'Verzeichnis ist leer';
$cms_lang['upl_upload']			  = 'Upload Dateien';
$cms_lang['upl_editvisible']		  = 'versteckt';
$cms_lang['upl_editprotected']		  = 'gesch&uuml;tzt';
$cms_lang['upl_opendir']			  = 'Verzeichnis &ouml;ffnen';
$cms_lang['upl_closedir']			  = 'Verzeichnis schlie&szlig;en';
$cms_lang['upl_createdir']			  = 'Verzeichnis erstellen';
$cms_lang['upl_editdir']			  = 'Verzeichnis bearbeiten';
$cms_lang['upl_movedirtodir']		  = 'Verzeichnis mit allen Dateien verschieben nach';
$cms_lang['upl_movedirname']		  = 'Verzeichnisname';
$cms_lang['upl_copydirtodir']		  = 'Verzeichnis mit allen Dateien kopieren nach';
$cms_lang['upl_deletedir']			  = 'Verzeichnis mit allen Dateien und Unterverzeichnissen l&ouml;schen';
$cms_lang['upl_scandir']			  = 'Verzeichnis mit Datenbank abgleichen';
$cms_lang['upl_scandir_root']		  = 'Basisverzeichnis importieren';
$cms_lang['upl_deletefile']		  = 'Datei l&ouml;schen';
$cms_lang['upl_editfile']			  = 'Datei bearbeiten';
$cms_lang['upl_movefiletodir']		  = 'Datei verschieben nach';
$cms_lang['upl_copyfiletodir']		  = 'Datei kopieren nach';
$cms_lang['upl_downloadfile']  	  = 'Datei herunterladen';
$cms_lang['upl_copyfilename']		  = 'Dateiname';
$cms_lang['upl_file']				  = 'Datei';
$cms_lang['upl_fileopen']			  = 'Datei &ouml;ffnen';
$cms_lang['upl_delete']			  = 'l&ouml;schen';
$cms_lang['upl_changeviewdetail']	  = 'Zur Detailansicht wechseln';
$cms_lang['upl_changeviewcompact']	  = 'Zur Kompaktansicht wechseln';
$cms_lang['upl_showfilesindir']	  = 'Zeige Dateien im Verzeichnis ...';
$cms_lang['upl_selectuploaddir']	  = 'W&auml;hle Verzeichnis ...';
$cms_lang['upl_bulkupload']		  = 'Bulk-Upload ZIP (max. ' . get_cfg_var('upload_max_filesize') . 'Byte)';
$cms_lang['upl_tarupload']			  = 'Bulk-Upload TAR (max. ' . get_cfg_var('upload_max_filesize') . 'Byte)';
$cms_lang['upl_openfileinnewwindow'] = 'Datei in Originalgr&ouml;&szlig;e anzeigen';
$cms_lang['upl_titel']			   	  = 'Titel';
$cms_lang['upl_download']  		  = 'Download';
$cms_lang['upl_edit']  			  = 'Bearbeiten';
$cms_lang['upl_copy']				  = 'Kopieren';
$cms_lang['upl_move']				  = 'Verschieben';
$cms_lang['upl_del']				  = 'L&ouml;schen';
$cms_lang['upl_editrights']		  = 'Rechte bearbeiten';
$cms_lang['upl_confirm_delete']	  = 'Datei wirklich l&ouml;schen?';
$cms_lang['upl_newplace']			  = 'nach';
$cms_lang['upl_root_dir']			  = 'Basisverzeichnis';

$cms_lang['scan_title']			  = 'Verzeichnisabgleich';
$cms_lang['scan_error']			  = 'Fehler:';
$cms_lang['scan_done_start']         = 'Gesamtverlauf in Prozent: ';
$cms_lang['scan_done_end']			  = '% erledigt';
$cms_lang['scan_status']			  = 'Scanstatus: ';
$cms_lang['scan_status_done_start']  = 'beendet<br />Dauer: ';
$cms_lang['scan_status_done_end']	  = ' Sekunden.';
$cms_lang['scan_status_active']      = 'aktiv';
$cms_lang['scan_status_total']		  = 'Gesamt';
$cms_lang['scan_status_processed']	  = 'Bearbeitet';
$cms_lang['scan_status_todo']        = 'Offen';
$cms_lang['scan_directroies']		  = 'Verzeichnisse';
$cms_lang['scan_files']			  = 'Dateien';
$cms_lang['scan_thumbs']			  = 'Vorschaubilder';
$cms_lang['scan_errors']			  = 'Fehlerhafte Verzeichnisse oder Dateien:';
$cms_lang['scan_errors_none']		  = 'keine';
$cms_lang['scan_closing_time']		  = 'Fenster schlie&szlig;t in ca. 10 Sekunden.';
$cms_lang['scan_close_now']		  = 'Schlie&szlig;en';

$cms_lang['scan_thumbs_checkbox']	  = 'Thumbnails neu generieren';
$cms_lang['scan_nosubdir_checkbox']  = 'Unterverzeichnisse nicht scannen';
$cms_lang['scan_options']            = 'Einstellungen f&uuml;r Verzeichnisscan';
$cms_lang['scan_start_dirscan']      = 'Starten';

$cms_lang['upl_js_texte_pp_title']			= 'Dateiinformationen<br />f&uuml;r Ansicht bitte klicken';
$cms_lang['upl_js_texte_pp_header_bild']	= 'Bildvorschau und -information<br />f&uuml;r Originalgr&ouml;&szlig;e bitte klicken';
$cms_lang['upl_js_texte_pp_header_datei']	= 'Bildvorschau und -information<br />in Originalgr&ouml;&szlig;e';
$cms_lang['upl_js_texte_pp_created']		= 'Erstellt am: ';
$cms_lang['upl_js_texte_pp_modified']		= 'Letzte &Auml;nderung: ';
$cms_lang['upl_js_texte_pp_author']		= 'Redakteur: ';
$cms_lang['upl_js_texte_pp_size']			= 'Gr&ouml;&szlig;e: ';

// 14xx = Filemanager
$cms_lang['err_1400']					= 'Dateiname fehlt oder enth&auml;lt unzul&auml;ssige Zeichen!';
$cms_lang['err_1401']					= 'Es existiert schon eine Datei mit diesem Namen!';
$cms_lang['err_1402']					= 'Datei wurde nicht gefunden!';
$cms_lang['err_1403']					= 'Datei konnte nicht kopiert werden!';
$cms_lang['err_1404']					= 'Datei konnte nicht verschoben werden!';
$cms_lang['err_1405']					= 'Zuwenig Parameter f&uuml;r diese Funktion &uuml;bergeben!';
$cms_lang['err_1406']					= 'Verzeichnisname fehlt oder enth&auml;lt unzul&auml;ssige Zeichen!';
$cms_lang['err_1407']					= 'Dateierweiterung kann nicht ver&auml;ndert werden!';
$cms_lang['err_1408']					= 'Verzeichnis konnte nicht gel&ouml;scht werden!';
$cms_lang['err_1409']					= 'Datei konnte nicht gel&ouml;scht werden!';
$cms_lang['err_1410']					= $cms_lang['err_1409'].' Datei ist in Gebrauch.';
$cms_lang['err_1411']					= 'Datei konnte nicht gel&ouml;scht werden!';
$cms_lang['err_1412']					= 'Datei konnte nicht umbenannt werden!';
$cms_lang['err_1413']					= 'Es existiert schon ein Verzeichnis mit diesem Namen!';
$cms_lang['err_1414']					= 'Verzeichnis konnte nicht umbenannt werden!';
$cms_lang['err_1415']	   				= 'Parameter ist fehlerhaft. Funktion wird nicht ausgef&uuml;hrt!';
$cms_lang['err_1416']	   		  		= '&Uuml;berschreiben der Datei nicht erlaubt. Funktion abgebrochen!';
$cms_lang['err_1417']					= 'Datei konnte nicht erstellt werden!';
$cms_lang['err_1418']					= 'Verzeichnis konnte nicht erstellt werden!';
$cms_lang['err_1419']					= 'Datei konnte nicht verschoben werden. Die Datei ist vom Dateisystem gesch&uuml;tzt!';
$cms_lang['err_1420']					= 'Dateiname enth&auml;lt unzul&auml;ssige Zeichen!';
$cms_lang['err_1421']					= 'Upload gescheitert!';
$cms_lang['err_1422']					= $cms_lang['err_1421'] . ' Archiv enth&auml;lt Verzeichnisse mit unzul&auml;ssigen Verzeichnisnamen!';
$cms_lang['err_1423']					= 'Upload wurde durchgef&uuml;hrt. Datei konnte aber nicht in Datenbank eingetragen werden!';
$cms_lang['err_1424']					= $cms_lang['err_1421'] . ' Datei konnte nicht ins gew&uuml;nschte Verzeichnis geschrieben werden!';
$cms_lang['err_1425']					= 'Verzeichnis f&uuml;r Dateiabgleich in der Datenbank nicht gefunden!';
$cms_lang['err_1426']					= 'Verzeichnis f&uuml;r Dateiabgleich existiert nicht, bitte pr&uuml;fen Sie die Konfiguration!';



?>