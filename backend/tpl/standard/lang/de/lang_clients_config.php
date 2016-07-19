<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

$cms_lang['set_session_frontend_lifetime']		= 'Lebenszeit Session Frontend';
$cms_lang['set_session_frontend_enabled']		= 'Frontend Session Support';

$cms_lang['setuse_pathes']						= 'Pfad und Dateiangaben';
$cms_lang['setuse_path']						= 'Pfad zum Frontend';
$cms_lang['setuse_html_path']					= 'HTML-Pfad zum Frontend';
$cms_lang['setuse_contentfile']				= 'Name der Frontenddatei';
$cms_lang['setuse_space']						= 'Platzhalter f&uuml;r Bilder';
$cms_lang['setuse_general']					= 'Allgemeine Einstellungen';
$cms_lang['setuse_publish']					= '&Auml;nderungen erst nach Freigabe publizieren';
$cms_lang['setuse_edit_mode']					= 'Edit-Modus 0=Visuell 1=Vis.-Cont. 2=Cont.-Vis. 3=Content';
$cms_lang['setuse_wysiwyg_applet']				= 'WYSIWYG Applet 0=nie, 1=kein IE, 2=immer, 3=kein IE + Mozilla';
$cms_lang['setuse_default_layout']				= 'Layoutvorlage';
$cms_lang['setuse_errorpage']					= '404 Fehlerseite bei nicht existierender idcatside/idcat als idcatside';
$cms_lang['setuse_loginpage']					= 'idcatside f&uuml;r Login-Timeoutseite';
$cms_lang['setuse_cache']						= 'Frontendseiten cachen';
$cms_lang['setuse_session_frontend_domain']		= 'Session Frontend Domain';
$cms_lang['setuse_url_rewrite']				= 'Apache URL-Rewrite Support (mod_rewrite)';
$cms_lang['setuse_url_langid_in_defaultlang']		= 'ID der Standardsprache in URL zeigen';
$cms_lang['setuse_url_rewrite_basepath']		= 'Basepath bei URL-Rewrite=2. Variablen: {%http_host}';
$cms_lang['setuse_url_rewrite_404']				= '404 Fehlerseite bei URL-Rewrite=2. Variablen: {%http_host}, {%request_uri} oder idcatside';
$cms_lang['setuse_url_rewrite_suffix']				= 'URL-Rewrite Seiten Suffix';
$cms_lang['setuse_session_disabled_useragents']		= 'Useragents f&uuml;r die keine Session erzeugt wird (eine pro Zeile)';
$cms_lang['setuse_session_disabled_ips']			= 'IPs f&uuml;r die keine Session erzeugt wird (eine pro Zeile)';
$cms_lang['setuse_manipulate_output']			= 'Ausgabe manipulieren';
$cms_lang['setuse_upl_path']					= 'Startverzeichnis Dateimanager';
$cms_lang['setuse_upl_htmlpath']				= 'Startverzeichnis HTML-Pfad';
$cms_lang['setuse_filemanager']				= 'Einstellungen Dateimanager';
$cms_lang['setuse_forbidden']					= 'Verbotene Dateiendungen';
$cms_lang['setuse_thumb_size']					= 'Gr&ouml;&szlig;e der Vorschaubilder';
$cms_lang['setuse_thumb_aspectratio']			= 'Proportionen beibehalten';
$cms_lang['setuse_upl_addon']					= 'Thumbnails generieren f&uuml;r (falls m&ouml;glich)';
$cms_lang['setuse_thumb_aspectratio']			= 'Proportionen beibehalten 0=nein, 1=ja, 2=Y skaliert, 3= X skaliert';
$cms_lang['setuse_thumb_ext']					= 'Dateikennung f&uuml;r generierte Thumbnails';
$cms_lang['setuse_upl_addon']					= 'Thumbnails generieren f&uuml;r (falls m&ouml;glich)';
$cms_lang['setuse_fm_delete_ignore_404']		= 'Fehlermeldung beim L&ouml;schen von fehlenden Dateien ignorieren 1=ja/0=nein';
$cms_lang['setuse_remove_files_404']			= 'Verwaiste Dateieintr&auml;ge bei Datenbankabgleich l&ouml;schen 1=ja/0=nein';
$cms_lang['setuse_css']						= 'Einstellungen CSS-Editor';
$cms_lang['setuse_css_sort_original']			= 'CSS-Sortierung (0= Alphabet, 1=Eingabereihenfolge)';
$cms_lang['setuse_csschecking']				= 'CSS-Regeln standardm&auml;&szlig;ig auf G&uuml;ltigkeit pr&uuml;fen 1=ja/0=nein';
$cms_lang['setuse_css_ignore_error_rules']		= 'Fehlerhafte CSS-Regeln in CSS-Dateien aufnehmen 2=ja/0=nein';
$cms_lang['setuse_remove_empty_directories']	= 'Leere Dateiverzeichnisse beim Abgleichen l&ouml;schen 1=ja/0=nein';

$cms_lang['set_meta']							= 'Metaangaben vorkonfigurieren';
$cms_lang['set_meta_title']				= 'Seitentitel';
$cms_lang['set_meta_other']				= 'Weitere Meta-Angaben';
$cms_lang['set_meta_description']				= 'Seitenbeschreibung';
$cms_lang['set_meta_keywords']					= 'Suchbegriffe';
$cms_lang['set_meta_robots']					= 'Suchmaschinenanweisung';

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
?>
