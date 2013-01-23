<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['set_action']					= 'Aktionen';
$cms_lang['set_general']				= 'Allgemeine Einstellungen';
$cms_lang['set_submit']					= 'Speichern';
$cms_lang['set_cancel']					= 'Abbrechen';
$cms_lang['set_edit']					= 'Bearbeiten';
$cms_lang['set_gzip']					= 'Alle Seiten gzip komprimieren';
$cms_lang['set_backend_cache']	  			= 'Browsern das Cachen von Backendseiten verbieten';
$cms_lang['set_chmod_enable']				= 'CHMOD auf Uploads anwenden 0=nein, 1=ja';
$cms_lang['set_chmod_value']				= 'CHMOD Basiswert (oktal, z.B. 777)';
$cms_lang['set_gzip_enable']				= 'Kompression auf Downloads anwenden 0=nein, 1=ja';
$cms_lang['set_manipulate_output']			= 'Ausgabe manipulieren';
$cms_lang['set_cms_path']				= 'Pfad zum Backend';
$cms_lang['set_html_path']				= 'HTML-Pfad zum Backend';
$cms_lang['set_filebrowser']				= 'Dateimanager';
$cms_lang['set_backend_lang']				= 'Sprache des Backends';
$cms_lang['set_skin']					= 'Skin';
$cms_lang['set_image']					= 'Grafikeinstellungen';
$cms_lang['set_image_mode']				= "Treiber w&auml;hlen 'GD', 'IM', 'Imagick', 'NetPBM'";

$cms_lang['set_paging_items_per_page'] = 'Angezeigte Eintr&auml;ge pro Seite, wenn Paging unterst&uuml;tzt wird';


$cms_lang['set_logs']					= 'Logfiles';
$cms_lang['set_log_enabled']				= 'Logfiles aktiviert';
$cms_lang['set_log_prunetime']				= 'Logfiles automatisch l&ouml;schen nach X Tagen';
$cms_lang['set_log_entries']				= 'Eintr&auml;ge pro Seite';
$cms_lang['set_time']					= 'Zeitangaben';
$cms_lang['set_FormatDate']				= 'Datumsformat';
$cms_lang['set_FormatTime']				= 'Zeitformat';
$cms_lang['set_session_backend_lifetime']		= 'Lebenszeit Session Backend';
$cms_lang['set_session_backend_domain']		= 'Session Backend Domain';
$cms_lang['set_stat_enabled']				= 'Statistik aktiviert';

$cms_lang['set_repository']				= 'Repository konfigurieren';
$cms_lang['set_repository_enabled']			= 'Repository benutzen';
$cms_lang['set_repository_server']			= 'Repository-Server';
$cms_lang['set_repository_updatetime']			= 'Updateintervall';
$cms_lang['set_repository_path']			= 'Repository-Service';
$cms_lang['set_repository_pingtime']			= 'Pingintervall';
$cms_lang['set_repository_show_up2date']		= 'Repository-Status Up2Date anzeigen';
$cms_lang['set_repository_show_offline']		= 'Repository-Status Offline anzeigen';
$cms_lang['set_auto_repair_dependency']		= 'Repository-Abh&auml;ngigkeiten automatisch aufl&ouml;sen';

$cms_lang['set_db_cache']				= 'Datenbank-Cache konfigurieren';
$cms_lang['set_db_cache_enabled']			= 'Datenbank-Cache benutzen';
$cms_lang['set_db_cache_name']				= 'Datenbank-Cache name';
$cms_lang['set_db_cache_groups']			= 'Cache-Gruppen konfigurieren (in sec.)';
$cms_lang['set_db_cache_group_default']			= 'Cache-Gruppe "Default"';
$cms_lang['set_db_cache_group_standard']		= 'Cache-Gruppe "Standard"';
$cms_lang['set_db_cache_group_frontend']		= 'Cache-Gruppe "Frontend"';
$cms_lang['set_db_cache_items']			= 'Cache-Items konfigurieren (in sec.)';
$cms_lang['set_db_cache_item_tree']		= 'Cache-Item "Frontend-Ordner & Seitenstruktur"';
$cms_lang['set_db_cache_item_content']		= 'Cache-Item "Frontend-Seitencontent"';

$cms_lang['set_db_optimice_tables']		    = 'Datenbank-Optimierung konfigurieren';
$cms_lang['set_db_optimice_tables_enable']		= 'Datenbank-Optimierung benutzen';
$cms_lang['set_db_optimice_tables_time']		= 'Datenbank-Optimierung intervall';
$cms_lang['set_db_optimice_tables_lastrun']	= 'Datenbank-Optimierung letzter Durchlauflauf';
?>
