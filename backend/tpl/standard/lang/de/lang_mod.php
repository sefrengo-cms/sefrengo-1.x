<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Modul
$cms_lang['mod_modul']					= 'Modul';
$cms_lang['mod_parent']				= 'Muttermodul';
$cms_lang['mod_action']				= 'Aktionen';
$cms_lang['mod_edit']					= 'Modul bearbeiten';
$cms_lang['mod_config']				= 'Modul konfigurieren';
$cms_lang['mod_config_save']			= 'Modul Konfiguration behalten';
$cms_lang['mod_update_save']			= 'Modul updaten';
$cms_lang['mod_reinstall_save']		= 'Modul reinstallieren';
$cms_lang['mod_config_change']			= 'Modul Konfiguration &auml;ndern';
$cms_lang['mod_export']				= 'Modul exportieren';
$cms_lang['mod_defaultname']			= 'Neues Modul';
$cms_lang['mod_delete']				= 'Modul l&ouml;schen';
$cms_lang['mod_modulename']			= 'Modulname';
$cms_lang['mod_verbosename']			= 'Alternativ';
$cms_lang['mod_konfiguration']			= 'Konfiguration';
$cms_lang['mod_version']				= 'Version';
$cms_lang['mod_cat']			   		= 'Kategorie';
$cms_lang['mod_description']			= 'Beschreibung';
$cms_lang['mod_import']				= 'Modul importieren';
$cms_lang['mod_input']					= 'Konfiguration';
$cms_lang['mod_new']					= 'Neues Modul';
$cms_lang['mod_nomodules']				= 'Es gibt keine Module.';
$cms_lang['mod_output']				= 'Frontendausgabe';
$cms_lang['mod_duplicate']				= 'Modul duplizieren';
$cms_lang['mod_upload']				= 'Modul uploaden';
$cms_lang['mod_download']	   			= 'Modul downloaden';
$cms_lang['mod_xmlimport']				= 'Modul aus XML importieren';
$cms_lang['mod_xmlexport']				= 'Modul als XML exportieren';

$cms_lang['mod_repository']			= 'Modul-Repository';
$cms_lang['mod_repository_install']	= 'Modul aus Repository installieren';
$cms_lang['mod_repository_upload']		= 'Modul in Repository uploaden';
$cms_lang['mod_repository_update']		= 'Modul aus Repository updaten';
$cms_lang['mod_repository_use_dev']	= 'Developer-Module aus Repository nutzen';
$cms_lang['mod_repository_noupdate']	= 'Modul ist Up2Date';
$cms_lang['mod_repository_notonline']	= 'Repository nicht erreichbar';
$cms_lang['mod_local_update']	     	= 'Modul updaten';
$cms_lang['mod_database']				= 'Modul-Datenbank';
$cms_lang['mod_repository_download']	= 'Modul aus Repository downloaden';
$cms_lang['mod_repository_import']		= 'Modul aus Repository importieren';
$cms_lang['mod_database_import']		= 'Modul aus Datenbank importieren';
$cms_lang['mod_author']		        = 'Autor';
$cms_lang['mod_sql_install']			= 'Sql - Installation';
$cms_lang['mod_sql_uninstall']			= 'Sql - Deinstallation';
$cms_lang['mod_sql_update']			= 'Sql - Update';
$cms_lang['mod_new_sql']			    = 'Sql bearbeiten';
$cms_lang['mod_rebuild_sql']			= 'Sql erneut Installieren';
$cms_lang['mod_no_wedding']            = 'Modul vom ' . $cms_lang['mod_parent'] . ' trennen ' . "&#10;" . '(%s - Version: %s)';
$cms_lang['mod_config_all']            = 'Einstellungen in allen Templates/ Ordnern/ Seiten &uuml;bernehmen, welche dieses Modul verwenden';
$cms_lang['mod_config_return']         = 'Modul auf Grundeinstellung zur&uuml;cksetzen';
$cms_lang['mod_s_overide']             = 'Modul trotz Fehler im Modul-%s speichern';
$cms_lang['mod_confirm_update_config'] = 'Modulkonfiguration updaten und in allen Templates/Ordnern/Seiten zur&uuml;cksetzen?';
$cms_lang['mod_confirm_reinstall']	    = '<h5>Das Modul ist schon vorhanden!</h5><p>Welche Module sollen reinstalliert werden?</p>';
$cms_lang['mod_confirm_update']	    = '<h5>Es wurde ein Update gefunden!</h5><p>Welche Module sollen geupdatet werden?</p>';
$cms_lang['mod_confirm_lupdate']	    = '<h5>Es wurde ein Update gestartet!</h5><p>Wie soll das Module geupdatet werden?</p>';
$cms_lang['mod_confirm_new']	        = 'Modul als \'neues Modul\' speichern?';

// 04xx = Modul
$cms_lang['err_0400']	   				= 'Modul Fehler. Funktion wird nicht ausgef&uuml;hrt!';
$cms_lang['err_0401']					= 'Modul wird verwendet. L&ouml;schen nicht m&ouml;glich.';
$cms_lang['err_0402']					= 'Modul wurde erfolgreich kopiert.';
$cms_lang['err_0403']					= 'Es wurde keine g&uuml;ltige *.cmsmod- Datei hochgeladen';
$cms_lang['err_0404']					= 'Es wurde keine Datei zum hochladen angegeben';
$cms_lang['err_0405']					= 'Modul wurde erfolgreich vom Repository geupdated.';
$cms_lang['err_0406']					= 'Modul wurde erfolgreich vom Repository importiert.';
$cms_lang['err_0407']					= 'Modul-Sql wurde erfolgreich ausgef&uuml;hrt.';
$cms_lang['err_0408']					= 'Modul wurde erfolgreich hochgeladen.';
$cms_lang['err_0409']					= 'Modul wurde erfolgreich installiert.';
$cms_lang['err_0410']					= 'Modul wurde erfolgreich gel&ouml;scht.';
$cms_lang['err_0411']					= 'Modul wurde erfolgreich deinstalliert.';
$cms_lang['err_0412']					= 'Modul wurde erfolgreich gespeichert.';
$cms_lang['err_0413']					= 'Parameter ist fehlerhaft. Funktion wird nicht ausgef&uuml;hrt!';
$cms_lang['err_0414']					= 'Modul wurde erfolgreich exportiert.';
$cms_lang['err_0415']					= 'Modul wurde erfolgreich geupdated.';
$cms_lang['err_0416']					= 'Fehler im Modul-%s, Zeile: <b>%s</b>';
$cms_lang['err_0417']					= 'Update l&auml;uft';
$cms_lang['err_0418']					= 'Aktion abgebrochen';
$cms_lang['err_0419']					= 'Reinstallation l&auml;uft';
$cms_lang['err_0420']					= 'Module erfolgreich geupdated.';
$cms_lang['err_0421']					= 'Module erfolgreich reinstalliert.';
$cms_lang['err_0422']					= 'Module erfolgreich geupdated & reinstalliert.';
$cms_lang['err_0423']					= 'Parameter ist fehlerhaft. Modul Parse-Error!';
$cms_lang['err_0424']					= 'Parameter ist fehlerhaft. Modul ohne Inhalt';
$cms_lang['err_0425']	   				= '- neuen Modulnamen angeben!';
?>
