<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Navigation
$cms_lang['nav_1_0']					= 'Redaktion';
$cms_lang['nav_1_1']					= 'Seiten';
$cms_lang['nav_1_2']					= 'Dateimanager';

$cms_lang['nav_2_0']					= 'Design';
$cms_lang['nav_2_1']					= 'Layouts';
$cms_lang['nav_2_2']					= 'Stylesheet';
$cms_lang['nav_2_3']					= 'Javascript';
$cms_lang['nav_2_4']					= 'Module';
$cms_lang['nav_2_5']					= 'Templates';

$cms_lang['nav_3_0']					= 'Administration';
$cms_lang['nav_3_1']					= 'Benutzer';
$cms_lang['nav_3_2']					= 'Gruppen';
$cms_lang['nav_3_3']					= 'Projekte';
$cms_lang['nav_3_4']					= 'System';
$cms_lang['nav_3_5']					= 'Plugins';

$cms_lang['nav_4_0']					= 'Plugins';

$cms_lang['login_pleaselogin']			= 'Bitte geben Sie Ihren Benutzernamen &amp; Ihr Kennwort ein.';
$cms_lang['login_username']			= 'Benutzername';
$cms_lang['login_password']			= 'Kennwort';
$cms_lang['login_invalidlogin']		= 'Entweder ist Ihr Benutzername oder Ihr Kennwort ung&uuml;ltig. Bitte versuchen Sie es nochmal!';
$cms_lang['login_logininuse']		= 'Ihr Account ist zur Zeit in Benutzung.<br>Bitte versuchen Sie es sp&auml;ter nochmal!';
$cms_lang['login_challenge_fail']		= 'Ihre Challenge ist fehlgeschlagen.<br>Bitte versuchen Sie es nochmal!';

$cms_lang['login_nolang']				= 'Ihnen wurde noch keine Sprache zugewiesen.';
$cms_lang['login_nojs']				= 'Bitte aktivieren Sie Javascript in Ihrem Browser, damit das Backend korrekt funktioniert.';
$cms_lang['login_licence']				= '&copy; <a href="http://www.sefrengo.org" target="_blank">sefrengo.org</a>. This is free software, and you may redistribute it under the GPL V2. Sefrengo&reg; comes with absolutely no warranty; for details, see the <a href="license.html" target="_blank">license</a>.';

$cms_lang['logout_thanksforusingcms']	= 'Vielen Dank, dass sie "Sefrengo" benutzt haben. Sie werden in wenigen Sekunden zum Login weitergeleitet.';
$cms_lang['logout_youareloggedout']    = 'Sie sind jetzt abgemeldet.';
$cms_lang['logout_backtologin1']		= 'Hier kommen Sie wieder zur';
$cms_lang['logout_backtologin2']	    = 'Anmeldung';

$cms_lang['area_mod']					= $cms_lang['nav_2_0'].' - installierte '.$cms_lang['nav_2_4'];
$cms_lang['area_mod_new']				= $cms_lang['nav_2_0'].' - neues Modul';
$cms_lang['area_mod_edit']				= $cms_lang['nav_2_0'].' - Modul bearbeiten';
$cms_lang['area_mod_edit_sql']	     	= $cms_lang['nav_2_0'].' - Modul-SQL bearbeiten';
$cms_lang['area_mod_config']	     	= $cms_lang['nav_2_0'].' - Modul konfigurieren';
$cms_lang['area_mod_import']	    	= $cms_lang['nav_2_0'].' - Modul importieren';
$cms_lang['area_mod_duplicate']	    = $cms_lang['nav_2_0'].' - Modul kopieren';
$cms_lang['area_mod_xmlimport']	    = $cms_lang['nav_2_0'].' - Modul aus XML importieren';
$cms_lang['area_mod_xmlexport']	   	= $cms_lang['nav_2_0'].' - Modul als XML exportieren';
$cms_lang['area_mod_database']		    = $cms_lang['area_mod_import'] . '- Datenbank';
$cms_lang['area_mod_repository']		= $cms_lang['area_mod_import'] . '- Repository';

$cms_lang['area_plug']					= $cms_lang['nav_3_0'].' - installierte '.$cms_lang['nav_3_5'];
$cms_lang['area_plug_new']		      	= $cms_lang['nav_3_0'].' - neues Plugin';
$cms_lang['area_plug_new_import']	  	= $cms_lang['area_plug_new'].'importieren';
$cms_lang['area_plug_new_create']	 	= $cms_lang['area_plug_new'].'erzeugen';
$cms_lang['area_plug_edit']		 	= $cms_lang['nav_3_0'].' - Plugin bearbeiten';
$cms_lang['area_plug_edit_sql']		= $cms_lang['nav_3_0'].' - Plugin-Meta bearbeiten';
$cms_lang['area_plug_config']		  	= $cms_lang['nav_3_0'].' - Plugin konfigurieren';
$cms_lang['area_plug_import']	        = $cms_lang['nav_3_0'].' - Plugin importieren';
$cms_lang['area_plug_folder']			= $cms_lang['area_plug_import'] . '- Verzeichnis';
$cms_lang['area_plug_repository']      = $cms_lang['area_plug_import'] . '- Repository';

$cms_lang['area_con']					= $cms_lang['nav_1_0'].' - '.$cms_lang['nav_1_1'];
$cms_lang['area_con_configcat']	    = $cms_lang['nav_1_0'].' - Ordner konfigurieren';
$cms_lang['area_con_configside']	   	= $cms_lang['nav_1_0'].' - Seite konfigurieren';
$cms_lang['area_con_edit']				= $cms_lang['nav_1_0'].' - Seite bearbeiten';

$cms_lang['area_upl']					= $cms_lang['nav_1_0'].' - '.$cms_lang['nav_1_2'];

$cms_lang['area_lay']					= $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_1'];
$cms_lang['area_lay_edit']				= $cms_lang['nav_2_0'].' - Layout bearbeiten';

$cms_lang['area_css']					= $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'];
$cms_lang['area_css_edit']				= $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - CSS-Regel bearbeiten';
$cms_lang['area_css_edit_file']	    = $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - CSS-Datei bearbeiten';
$cms_lang['area_css_new_file']		 	= $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - CSS-Datei neu anlegen';
$cms_lang['area_css_import']			= $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - CSS-Regeln importieren';

$cms_lang['area_js']					= $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_3'];
$cms_lang['area_js_edit_file']			= $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_3'].' - Javascript-Datei bearbeiten';
$cms_lang['area_js_import']			= $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_3'].' - Javascript-Datei importieren';

$cms_lang['area_tpl']					= $cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_5'];
$cms_lang['area_tpl_edit']				= $cms_lang['nav_2_0'].' - Template bearbeiten';

$cms_lang['area_user']					= $cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_1'];
$cms_lang['area_user_edit']   		   	= $cms_lang['nav_3_0'].' - Benutzer bearbeiten';
$cms_lang['area_group']			 	= $cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_2'];
$cms_lang['area_group_edit']		 	= $cms_lang['nav_3_0'].' - Gruppe bearbeiten';
$cms_lang['area_group_config']		 	= $cms_lang['nav_3_0'].' - Gruppe konfigurieren';
$cms_lang['area_lang']					= $cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_3'];
$cms_lang['area_settings']				= $cms_lang['nav_3_0'].' - Systemeinstellungen';
$cms_lang['area_settings_general']	  	= $cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_4'];

$cms_lang['lay_action']				= 'Aktionen';
$cms_lang['lay_edit']					= 'Layout bearbeiten';
$cms_lang['lay_export']			 	= 'Layout exportieren';
$cms_lang['lay_defaultname']			= 'Neues Layout';
$cms_lang['lay_delete']			    = 'Layout l&ouml;schen';
$cms_lang['lay_import']				= 'Layout importieren';
$cms_lang['lay_layoutname']			= 'Layoutname';
$cms_lang['lay_description']		 	= 'Beschreibung';
$cms_lang['lay_doctype']		 	= 'Doctype';
$cms_lang['lay_doctype_autoinsert']		 	= 'Doctype automatisch an den Anfang des Layouts einf&uuml;gen';
$cms_lang['lay_doctype_none']		 	= 'Keiner';
$cms_lang['lay_code']					= 'Quellcode';
$cms_lang['lay_new']					= 'Neues Layout';
$cms_lang['lay_nolayouts']				= 'Es gibt keine Layouts.';
$cms_lang['lay_cmshead']				= 'Meta-Dateien';
$cms_lang['lay_css']					= 'Stylesheet';
$cms_lang['lay_js']					= 'Javascript';
$cms_lang['lay_nofile']			    = 'keine Datei';
$cms_lang['lay_duplicate']				= 'Layout duplizieren';
$cms_lang['lay_copy_of']				= 'Kopie von ';

$cms_lang['tpl_action']			 	= 'Aktionen';
$cms_lang['tpl_actions']['10']			= 'Neues Template';
$cms_lang['tpl_edit']					= 'Template bearbeiten';
$cms_lang['tpl_defaultname']		  	= 'Neues Template';
$cms_lang['tpl_is_start']		  	= 'Als Starttemplate festlegen';
$cms_lang['tpl_delete']			  	= 'Template l&ouml;schen';
$cms_lang['tpl_templatename']		  	= 'Templatename';
$cms_lang['tpl_description']		  	= 'Beschreibung';
$cms_lang['tpl_container']				= 'Container';
$cms_lang['tpl_notemplates']		 	= 'Es gibt keine Templates.';
$cms_lang['tpl_layout']			 	= 'Layout';
$cms_lang['tpl_duplicate']				= 'Template duplizieren';
$cms_lang['tpl_copy_of']				= 'Kopie von ';
$cms_lang['tpl_overwrite_all']		  	= '&Auml;nderungen in den Templatekopien f&uuml;r die Ordner und Seiten &uuml;bernehmen.';
$cms_lang['tpl_devmessage']		 	= 'Dies ist eine Entwicklerversion. Sie ist nicht f&uuml;r den produktiven Einsatz geeignet!';

$cms_lang['lang_language']				= 'Sprache';
$cms_lang['lang_actions']				= 'Aktionen';
$cms_lang['lang_rename']				= 'umbenennen';
$cms_lang['lang_delete']				= 'l&ouml;schen';
$cms_lang['lang_newlanguage']		  	= 'Neue Sprache';

$cms_lang['form_nothing']				= '--- kein ---';
$cms_lang['form_select']				= '--- Bitte w&auml;hlen ---';

$cms_lang['gen_back']					= 'Zur&uuml;ck';
$cms_lang['gen_abort']					= 'Abbrechen';
$cms_lang['gen_sort']					= 'Eintr&auml;ge sortieren';
$cms_lang['gen_default']				= 'default';
$cms_lang['gen_welcome']                = 'Willkommen';
$cms_lang['gen_logout']			  	= 'Logout';
$cms_lang['gen_deletealert']		  	= 'Wirklich l\u00f6schen?';
$cms_lang['gen_mod_active']		  	= 'Modul ist aktiviert';
$cms_lang['gen_mod_deactive']		 	= 'Modul ist deaktiviert';
$cms_lang['gen_mod_edit_allow']	    = 'Seiteninhalt kann editiert werden';
$cms_lang['gen_mod_edit_disallow']	 	= 'Seiteninhalt kann nicht editiert werden';
$cms_lang['gen_rights']			 	= 'Rechte';
$cms_lang['gen_overide']				= '&Uuml;bernahme';
$cms_lang['gen_reinstall']				= 'Reinstallation';
$cms_lang['gen_expand']			 	= 'Erweitert';
$cms_lang['gen_parent']				= 'Abh&auml;ngigkeiten';
$cms_lang['gen_delete']				= 'L&ouml;schen';
$cms_lang['gen_update']				= 'Updaten';
$cms_lang['gen_fundamental']           = 'Grundeinstellung';
$cms_lang['gen_configuration']			= 'Konfiguration';
$cms_lang['gen_version']				= 'Version';
$cms_lang['gen_description']			= 'Beschreibung';
$cms_lang['gen_verbosename']			= 'Alternativ';
$cms_lang['gen_name']			        = 'Name';
$cms_lang['gen_author']			    = 'Autor';
$cms_lang['gen_cat']			        = 'Kategorie';
$cms_lang['gen_original']			    = 'Original';
$mod_lang['gen_font']					= 'Schriftart';
$mod_lang['gen_errorfont']				= 'Schriftart f&uuml;r Fehlermeldungen';
$mod_lang['gen_inputformfont']		  	= 'Schriftart f&uuml;r die Eingabefelder';
$mod_lang['gen_picforsend']				= 'Bild f&uuml;r den Sendebutton';
$mod_lang['gen_select']					= 'Auswahlm&ouml;glichkeiten';
$cms_lang['gen_select_actions']	    = 'Aktionen...';
$cms_lang['gen_select_view'] 		    = 'Ansicht...';
$cms_lang['gen_select_change_to']	  	= 'Wechseln zu...';
$cms_lang['gen_help']                  = 'Hilfe';
$cms_lang['gen_logout_wide']           = 'Logout';
$cms_lang['gen_user']			        = 'Benutzer: ';
$cms_lang['gen_licence']			     = 'Lizenz';

$cms_lang['gen_save']			        = 'Speichern';
$cms_lang['gen_apply']			        = '&Uuml;bernehmen';
$cms_lang['gen_cancel']			        = 'Abbrechen';

$cms_lang['gen_save_titletext']			= 'Daten speichern und zur&uuml;ck zur vorherigen Ansicht';
$cms_lang['gen_apply_titletext']		= 'Daten speichern und in dieser Ansicht bleiben';
$cms_lang['gen_cancel_titletext']		= 'Abbrechen und zur&uuml;ck zur vorherigen Ansicht';

//contentflex
$mod_lang['cf_add_first_pos']			= 'Als erstes Element einf&uuml;gen';
$mod_lang['cf_insert_p1']			= 'Nach Element';
$mod_lang['cf_insert_p2']			= 'einf&uuml;gen';

$mod_lang['news_inputname']				= 'Feld f&uuml;r Namen';
$mod_lang['news_email']					= 'E-Mail-Adresse';
$mod_lang['news_name']					= 'Name (freiwillig)';
$mod_lang['news_subcribe']				= 'anmelden';
$mod_lang['news_unsubcribe']		    = 'abmelden';
$mod_lang['news_both']					= 'beides';
$mod_lang['news_headline']				= 'Stets die neusten Informationen per E-Mail.';
$mod_lang['news_headlineback']		    = '&Uuml;berschrift';
$mod_lang['news_subcribemessageback']	= 'Anmeldebest&auml;tigung';
$mod_lang['news_unsubcribemessageback'] = 'Abmeldebest&auml;tigung';
$mod_lang['news_subcribemessage']	    = 'Wir haben Ihre Daten in unsere Datenbank aufgenommen.';
$mod_lang['news_unsubcribemessage']	   	= 'Wir haben Sie aus unserem Newsletterverteiler gel&ouml;scht.';
$mod_lang['news_stopmessage']		   	= 'Der Newsletterempfang wurde deaktiviert.';
$mod_lang['news_goonmessage']		   	= 'Der Newsletterempfang wurde aktiviert.';
$mod_lang['news_err_1001']				= 'Es wurde keine E-Mail-Adresse eingegeben.';
$mod_lang['news_err_1002']				= 'Die E-Mail-Adresse hat nicht das richtige Format.';
$mod_lang['news_err_1003']				= 'Diese E-Mail-Adresse ist schon registriert.';
$mod_lang['news_err_1004']				= 'Konnte E-Mail-Adresse nicht finden.';

$mod_lang['login_error']			    = 'Fehlermeldung';
$mod_lang['login_errormsg']				= 'Logindaten sind nicht korrekt.';
$mod_lang['login_send']					= 'Login now';
$mod_lang['login_sendout']				= 'logout';
$mod_lang['login_name']					= 'Bitte Benutzernamen eintragen';
$mod_lang['login_password']				= 'Bitte Passwort eintragen';
$mod_lang['login_login']			    = 'Bitte klicken um einzuloggen';
$mod_lang['login_logout']				= 'Bitte klicken um auszuloggen';
$mod_lang['login_picforlogout']		    = 'Bild f&uuml;r Logout';

$mod_lang['link_extern']			    = 'externer Link:';
$mod_lang['link_intern']			    = 'oder interner Link:';
$mod_lang['link_blank']					= 'Link in einem neuen Browserfenster &ouml;ffnen';
$mod_lang['link_self']					= 'Link im gleichen Browserfenster &ouml;ffnen';
$mod_lang['link_parent']	  		    = 'Link sprengt aktuelles Frame';
$mod_lang['link_top']					= 'Link sprengt alle Frames';
$mod_lang['link_edit']					= 'Link bearbeiten';

$mod_lang['type_text']					= 'Text';
$mod_lang['type_textarea']				= 'Textarea';
$mod_lang['type_wysiwyg']				= 'WYSIWYG';
$mod_lang['type_link']					= 'Link';
$mod_lang['type_file']					= 'Dateilink';
$mod_lang['type_image']					= 'Bild';
$mod_lang['type_sourcecode']		    = 'Sourcecode';
$mod_lang['type_select']		    	= 'Select';
$mod_lang['type_hidden']		    	= 'Hidden';
$mod_lang['type_checkbox']		    	= 'Checkbox';
$mod_lang['type_radio']		    		= 'Radio';
$mod_lang['type_date']		    		= 'Datum';
$mod_lang['type_time']		    		= 'Zeit';
$mod_lang['type_typegroup']				= 'Content';
$mod_lang['type_container']				= 'Content';
$mod_lang['type_edit_container']	    = 'Modul';
$mod_lang['type_edit_side']				= 'Seite';
$mod_lang['type_edit_folder']		    = 'Ordner';
$mod_lang['type_save']					= 'speichern';
$mod_lang['type_edit']					= 'bearbeiten';
$mod_lang['type_new']					= 'neu';
$mod_lang['side_new']					= 'anlegen';
$mod_lang['side_config']			    = 'konfigurieren';
$mod_lang['side_mode']					= 'Ansichtmodus';
$mod_lang['side_publish']				= 'publizieren';
$mod_lang['side_delete']			    = 'l&ouml;schen';
$mod_lang['side_edit']					= 'Seite bearbeiten';
$mod_lang['side_overview']				= 'Seiten&uuml;bersicht';
$mod_lang['side_preview']				= 'Vorschau';
$mod_lang['type_delete']			    = 'l&ouml;schen';
$mod_lang['type_up']					= 'nach oben';
$mod_lang['type_down']					= 'nach unten';

$mod_lang['img_edit']					= 'Bild &auml;ndern';
$mod_lang['imgdescr_edit']				= 'Bildbeschreibung &auml;ndern';
$mod_lang['link_intern']			    = 'interner Link';
$mod_lang['link_extern']			    = 'externer Link';

$mod_lang['err_id']					    = 'ID ist falsch.';
$mod_lang['err_type']					= 'Typ ist falsch.';

$cms_lang['title_rp_popup']			= 'Rechte bearbeiten';

// 01xx = Con

// 02xx = Str
$cms_lang['err_0201']					= 'Der zu l&ouml;schende Ordner hat noch Unterordner. L&ouml;schen nicht m&ouml;glich.';
$cms_lang['err_0202']					= 'Es gibt noch Seiten in diesem Ordner. L&ouml;schen nicht m&ouml;glich.';

// 03xx = Lay
$cms_lang['err_0301']					= 'Layout wird verwendet. L&ouml;schen nicht m&ouml;glich.';
$cms_lang['err_0302']					= '<font color="black">Layout wurde erfolgreich kopiert.</font>';

// 05xx = Tpl
$cms_lang['err_0501']					= 'Template wird verwendet. L&ouml;schen nicht m&ouml;glich.';

// 06xx = Dis
$cms_lang['err_0601']					= 'Seite konnte nicht erzeugt werden. Weisen Sie allen Ordnern ein Template zu.';

// 07xx = Upl
$cms_lang['err_0701']					= 'Datei wird verwendet. L&ouml;schen nicht m&ouml;glich.';
$cms_lang['err_0702']					= 'Dieses Verzeichnis existiert bereits.';
$cms_lang['err_0703']					= 'Datei konnte nicht auf den Server geladen werden.';
$cms_lang['err_0704']					= 'Dieser Name ist nicht g&uuml;ltig.';
$cms_lang['err_0705']					= 'Dieser Dateityp ist nicht zugelassen.';
$cms_lang['err_0706']					= 'Das Zielverzeichnis wurde nicht gefunden.';
$cms_lang['err_0707']					= 'Dateiupload wurde nicht abgeschlossen.';
$cms_lang['err_0708']					= 'Die hochgeladene Datei &uuml;berschreitet die in der Anweisung \'upload_max_filesize\' in php.ini festgelegte Gr&ouml;&szlig;e.';
$cms_lang['err_0709']					= 'Die hochgeladene Datei &uuml;berschreitet die in dem HTML Formular mittels der Anweisung \'MAX_FILE_SIZE\' angegebene maximale Dateigr&ouml;&szlig;e.';
$cms_lang['err_0710']					= 'Die Datei wurde nur teilweise hochgeladen.';
$cms_lang['err_0711']					= 'Es wurde keine Datei hochgeladen';


// 08xx = Lang
$cms_lang['err_0801']					= 'Es existiert bereits eine Sprache mit diesem Namen. Es wurde keine Sprache angelegt.';
$cms_lang['err_0802']					= 'Es gibt noch Seiten, die online sind oder Ordner, die sichtbar sind. Wenn sie diese Sprache bei diesem Projekt wirklich l&ouml;schen wollen, dann setzten sie alle Seiten offline und schalten sie alle Ordner auf unsichtbar.<br> VORSICHT/BEMERKUNG: L&ouml;schen ist nicht r&uuml;ckg&auml;ngig zu machen.';

// 09xx = Projekte
$cms_lang['err_0901']					= '';
$cms_lang['err_0902']					= '';

// 13xx = SQL-Schicht
$cms_lang['err_1310']					= 'Zu wenige Werte f&uuml;r ein INSERT-Query!';
$cms_lang['err_1320']					= 'Zu wenige Werte f&uuml;r eine UPDATE-Query!';
$cms_lang['err_1321']					= 'WHERE-Angabe fehlt. UPDATE wird nicht durchgef&uuml;hrt.';
$cms_lang['err_1330']					= 'Zu wenige Werte f&uuml;r eine DELETE-Query!';
$cms_lang['err_1331']					= 'WHERE-Angabe fehlt. DELETE wird nicht durchgef&uuml;hrt.';
$cms_lang['err_1340']					= 'Zu wenige Werte f&uuml;r eine SELECT-Query!';
$cms_lang['err_1350']					= 'Keine Parameter f&uuml;r die Erstellung von SQL-Daten &uuml;bergeben!';
$cms_lang['err_1360']					= 'Datenbankfehler: Die Daten betreffenden Datens&auml;tze sind inkonsitent!';

// 15xx = Repository
$cms_lang['err_1500']	   				= 'Repository-Fehler: Funktion wird nicht ausgef&uuml;hrt!';
$cms_lang['err_1501']	   				= 'Repository-Fehler: Datei existiert nicht!';
$cms_lang['err_1502']	   				= 'Repository-Fehler: XML-Format unbekannt!';
$cms_lang['err_1503']	   				= 'Repository-Fehler: XML-Format fehlerhaft!';
$cms_lang['err_1504']	   				= 'Repository-Fehler: XML-Parser kann nicht gestartet werden!';
$cms_lang['err_1505']	   				= 'Repository-Fehler: Kann Datei nicht schreiben!';
$cms_lang['err_1506']	   				= 'Repository-Fehler: Kann Datei nicht lesen!';
$cms_lang['err_1507']	   				= 'Repository-Fehler: Repository-Id doppelt!';
$cms_lang['err_1508']	   				= 'Repository-Fehler: Repository kann Datenbank nicht lesen!';
$cms_lang['err_1509']	   				= 'Repository-Fehler: Kann Klasse nicht laden!';
$cms_lang['err_1510']	   				= 'Repository-Fehler: Kann Datei nicht l&ouml;schen!';

// 17xx = Allgemeine Rechtefehler
$cms_lang['err_1701']					= 'Keine ausreichenden Rechte f&uuml;r diese Funktion!';

// 18xx = Konfigurationsfehler
$cms_lang['err_1800']                  = 'Allgemeiner Konfigurationsfehler. Setup neu ausf&uuml;hren!';
$cms_lang['err_1801']                  = 'Safe-Mode ist aktiviert. Die Funktion kann nicht ausgef&uuml;hrt werden!';

// 19xx = Debugmessages
$cms_lang['err_1900']                  = 'Achtung, Debug l&auml;uft!';
$cms_lang['err_1901']                  = 'Debugmessage: 1!';
$cms_lang['err_1902']                  = 'Debugmessage: 2!';
$cms_lang['err_1903']                  = 'Debugmessage: 3!';
$cms_lang['err_1904']                  = 'Debugmessage: 4!';
$cms_lang['err_1905']                  = 'Debugmessage: 5!';
?>
