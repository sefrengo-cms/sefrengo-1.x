Changelog
================================================================================================


Sefrengo v1.6.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.00<br/>
Release: 18.02.2014

See full changelog at <https://github.com/sefrengo-cms/sefrengo-1.x/commits/v1.6.0>

* FIXED: Bugfix for unwanted http://cms:// in CKEditor link dialog
* FIXED: Updated CKEditor to v4.3.2 (dropped browser compatibility for IE7 and Firefox 3.6)
* FIXED: Bugfix for entity encoding in ContentFlex modul on new installation 



Sefrengo v1.5.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.05.01<br/>
Release: 15.11.2013

See full changelog at <https://github.com/sefrengo-cms/sefrengo-1.x/commits/v1.5.1>

* FIXED: [Template bearbeiten: Nach Auswahl direkt zum Modul springen](https://github.com/sefrengo-cms/sefrengo-1.x/issues/2)
* FIXED: [PHP 5.5: /e modifier is deprecated](https://github.com/sefrengo-cms/sefrengo-1.x/issues/4)
* FIXED: [class.SF_GUI_ContentStylerHTML.php: Illegal string offset 'type'](https://github.com/sefrengo-cms/sefrengo-1.x/issues/5)
* FIXED: [Dateimanager: Datei kann nicht gelöscht/kopiert/verschoben werden](https://github.com/sefrengo-cms/sefrengo-1.x/issues/6)
* FIXED: [Seite bearbeiten: Bearbeitungsmenü verschwindet bei einer Neuinstallation](https://github.com/sefrengo-cms/sefrengo-1.x/issues/7)
* FIXED: [Ressource Browser: ckeditor_funcNum is not defined](https://github.com/sefrengo-cms/sefrengo-1.x/issues/8)
* FIXED: [Ressource Browser: Verzeichnisse werden nicht angezeigt](https://github.com/sefrengo-cms/sefrengo-1.x/issues/10)



Sefrengo v1.5.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.05.00<br/>
Release: 17.10.2013

See full changelog at <https://github.com/sefrengo-cms/sefrengo-1.x/commits/v1.5.0>

* Modernized backend style and rebuilt header
  * CHANGED: Rebuild the Sefrengo backend header
  * ADDED: Used style and some JS functionalities of unreleased Sefrengo v2.0.0-beta3
  * ADDED: Added jQuery 1.10.2 + qTip 2.1.1 library
  * ADDED: Show name for logged in user in header
  * REMOVED: Removed overlib.js; use jQuery qTip instead
* Switched WYSIWYG to CKEditor 4.1.3 
  * REMOVED: FCKEditor
  * ADDED: CKEditor 4.1.3



Sefrengo v1.4.6
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.04.06<br/>
Release: 25.02.2013

See full changelog at <https://github.com/sefrengo-cms/sefrengo-1.x/commits/v1.4.6>

* ADDED: Updated ADOdb to v4.992
* ADDED: Update mod: ContentFlex 1.8.8 (thx to amk)
* CHANGED: Changes to run Sefrengo on PHP 5.4
* CHANGED: Replaced deprecated split() function



Sefrengo v1.4.5
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.04.05<br/>
Release: 30.05.2011

* ADDED: Update mod: ContentFlex 1.8.6 (Jörn)
* ADDED: Update class.SF_PAGE_ContentFactory (Jörn)
* ADDED: Update class.SF_API_ObjectFactory.php (thx to AMK)
* FIXED: Problems installing under MySql 5.5 solved (Jörn)
* FIXED: CMS date tag: Max Year 2010 (thx to Hr.Rossi)
* FIXED: class.SF_ASSETS_DbFile.php: File upload in the frontend not visible in the file manager (thx to amk)
* FIXED: class.SF_UTILS_Mail.php: Problem with addAdressByGroupname (thx to CarstingAxion)



Sefrengo v1.4.4
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.04.04<br/>
Release: 12.08.2010

* ADDED: update fckeditor from 2.6.4 to 2.6.6 (Andre)
* FIXED: patch for php 5.3 included (Andre)



Sefrengo v1.4.3
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.04.03<br/>
Release: 28.02.2009

* ADDED: update fckeditor from 2.6.3 to 2.6.4 (bjoern)
* FIXED: mozileLoader.js: JS Errors in FF3 (thx to tobaco)
* FIXED: inc.lay_edit.php: problems with saving selcected javascript and css files (bjoern)
* FIXED: mod.contentflex_cache.php: contentflex {filelist} Division by zero problem (amk)



Sefrengo v1.4.2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.04.02<br/>
Release: 25.11.2008

* ADDED: update fckeditor from 2.6.2 to 2.6.3 (bjoern)
* ADDED: update french langfiles
* ADDED: update mod: Contentflex 1.8.1 (bjoern)
* CHANGED: index.php: Move API unload methode to the scriptend (bjoern)
* FIXED: class.SF_UTILS_Mail.php: It was not possible to add attechements (bjoern)
* FIXED: class.SF_PAGE_Pageinfos.php: Correct sourcedoku (bjoern)
* FIXED: inc.plug_config.php, plug_settings.tpl: Correct HTML table in Plugin config settings (thx to STam)
* FIXED: inc.lay_edit.php: CSS/ JS files from the filemanager was shown as chooseable meta files (bjoern)
* FIXED: inc.user.php, user.tpl: Lost session if cookies are disabled and the backenduseder wants to use a selectbox option (bjoern)
* FIXED: inc.user_edit.php: on create an new user the field firm_email wasn't saveed (thx to bkm)
* FIXED: frontend.php: error redirect does not work by using mod_rewrite=1 support (bjoern)
* FIXED: fnc.type.php: cms:tags with the attribute mode="editbutton" returns all their contents in the frontend (bjoern)
* FIXED: fnc.plug.php: plugin install/ reinstall routine does not work correctly in some cases (thx to STam, mvsxyz)



Sefrengo v1.4.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.04.01<br/>
Release: 11.08.2008

* FIXED: inc.con_edit.php: remove double attribute type="text/javascript" in script tag (bjoern)
* ADDED: add metatag "roobots = noindex, nofollow" to backend header.tpl (bjoern)
* FIXED: fckconfig.php: show text small, medium, large... in fontsize box but 1,2,3... (bjoern)
* FIXED: setup: numeric table prefixes wasn't possible (bjoern)
* FIXED: administration > user: it was not possible to change a part of an existing username to upper or lowercase (bjoern)
* ADDED: class.SF_ADMINISTRATION_User.php: add Method loadByUsername (bjoern)
* ADDED: class.SF_PAGE_Catinfos.php: add Method isChildOf, getChilds (bkm, Chregu, bjoern)
* ADDED: class.SF_PAGE_Pageinfos.php: add Methods getSummary, getMetaDescription, getMetaAuthor, getMetaKeywords, isChildOf (bkm, Chregu, bjoern)
* FIXED: some slashing troubles in cms:tag sourcecode (bjoern)
* ADDED: Update Fckeditor to version 2.6.2 (bkm, bjoern)
* ADDED: update mods (bjoern)
  Kontaktformular 2.2, Login 2.2, Sprachauswahl 2.0 (is identical to Sprachauswahl3 V. 1.3),
  PigGallerie 3.5, COntentflex 1.8
* REMOVED: remove mod treemenu. this mod is not longer supported. (bjoern)
* ADDED: add documentation and some settings to the ressources file and link (bjoern)
* FIXED: fnc.con.php fix (bjoern)
* ADDED: add modes 'thumbamplitude', 'thumbwidth', 'thumbheight', 'thumburl', 'thumbpath' to cms:image tag (bjoern)
* FIXED: class.plugin_meta.php, fnc.plug.php: PHP Statements in install, update, uninstall metafiles does not work (bjoern)
* FIXED: inc.con.php: quicksort options 'sort by lastmodified date' was shown twice (bjoern)
* REMOVED:system settings: remove unused setting 'article lock in minutes after edit' (bjoern)
* FIXED: class.SF_PAGE_Catinfos.php: PHP Error, if method generate() doesn't find a page (bjoern)
* ADDED: add Contentapi (by Mistral)
* ADDED: add ContentFactory (by bjoern)
* ADDED: add Userclass addons (by Tiger)
* FIXED: fnc.search.php: In some cases 'AS' tokens in the sql statement are stripped out
* ADDED: class.SF_GUI_RessourceBrowser.php: Add a compression routine to get smaller Ressourcebrowser urls (Reto)
* FIXED: phplib/local.php : No session lifetime refresh. User automaticly logged off after 10 minutes (thx to STam)
* FIXED: class.plugin_meta.php: It was not possible to run phpcode in the plugin* REMOVED: install, uninstall metafiles
* FIXED: fnc.con.php: By copying a page the uri update does not work
* FIXED: fnc.type.php: No more border="0" Attributes in the image tags
* FIXED: inc.header.php: Parsing arrays as hidden values in the lang form results in warnings. We parse hidden arrays not even longer.
* ADDED: class.SF_API_ObjectFactory.php: add method addIncludePath
* ADDED: fnc.type.php: add mode="id" for cms:tag image - returns the idupl of the selected file
* FIXED: area administration - groups: in IE7 the "group edit icon" was not shown (thx to STam)
* FIXED: class.values_ct.php inser sql statement for new values does not work
* ADDED: class.SF_PAGE_Catinfos.php: add method getChilds() to get all idcats of one parentcat, sortoptions are possible
* FIXED: class.SF_PAGE_Catinfos.php: PHP Error, if method generate() doesn't find a category
* ADDED: class.SF_PAGE_Pageinfos.php: add method getIdcatsideByIdcat() to get all idcatsides of one parentcat, sortoptions are possible
* FIXED: class.SF_PAGE_Pageinfos.php: PHP Error, if method generate() doesn't find a page
* FIXED: class.plugin_meta.php, fnc.plug.php: PHP Statements in install, update, uninstall metafiles does not work.
* FIXED: inc.con.php: quicksort options 'sort by lastmodified date' was shown twice
* REMOVED:system settings: remove unused setting 'article lock in minutes after edit'
* ADDED: ContentAPI (Reto)
* ADDED: class.SF_ASSETS_DbFile.php: add method getCmsThumbLink (Reto)
* CHANGED: class.SF_ASSETS_DbFile.php: name of methode getPictthumbheight (Reto)
* ADDED: edit content: better usability (Reto)
* FIXED: keep alternative name at modul update (thx to STam)
* ADDED: Now its possible to copy a modul with configuration (thx to STam)
* ADDED: edit content: better usability (Reto)



Sefrengo v1.4.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.04.00<br/>
Release: 25.04.2007

* ADDED: catinfos: new method getIdcatsideStartpage($idcat) (Björn)
* ADDED: update PEAR mail package (Björn)
* ADDED: fckediotor 1.4.2 (amk)
* ADDED: set correct 404 header while using  mod_rewrite 2 and given internal error page idcatside (Björn)
* ADDED: optimize captcha gc - delete captchaimage after verifying the usergiven captchacode (Björn)
* ADDED: module repository: Listennavigation is now an supported standard module in core (Björn)
* CHANGED: module repository: update mods Bild 1.2, ContentFlex 1.4, Kontaktformular 2.0.1, Login 2.0.2 (Björn)
* CHANGED: enhance mod_rewrite calls (Björn)
* REMOVED:module repository: remove standard moduls webgrab and guestbook, they are not longer supported by the core (Björn)
* FIXED: event handler can now handle standard and individual events the same time (Björn)
* FIXED: page copying throws an error if user does not have admin perms (Björn)
* FIXED: by deleting a language in a project the cfg_lang values in the table cfg_lang were not deleted (Björn)
* FIXED: setting perms in the modul configuration does not work (Björn)
* FIXED: project settings: by creating a language, the base-href configuration field had always the string 'projekt01/' as postfix (Björn)
* FIXED: cms:tags: in some cases there was a JS alert whilst choosing a very small image. (Björn)
* FIXED: fckeditor replaces öäü... with entities. Resulting in errors using the search module. Words with entities were not found. (Björn)
* FIXED: test backend visible-perm for pages and cats in edit mode - pages and cats without permissions are now hidden in all navigation modules using the $con_side and $con_tree array. (Björn)
* FIXED: sortindex corrections - if user move, clone or delete a page the sortindex was not set correctly. Aftermath: "jumping" page listings in pagetree, sort pages does not work for some pages (Björn)
* FIXED: copied pages displayed in the time control field (page settings) not the page create date (Björn)
* FIXED: mod_rewrite gives now unique name if two or more pages in same category have the same name (Björn)
* FIXED: deleting users not in  group wasn't possible (Björn)
* FIXED: the default status of a category after creation is now "offline" again (Björn)
* FIXED: troubles with hover menu position by editing page content in IE7 (saschapi)
* FIXED: area "content->pages (overview)": in some cases the drop downs at the top of the content flip left (Olaf)
* FIXED: fck editor JS error alert: toggle table border (Björn)
* FIXED: add utf-8 header in setup (Björn)
* FIXED: setup write permission test: correct "filses/" to "files/" (Björn)
* FIXED: header redirect after moving content up and down or delete  (Björn)
* FIXED: small pictures have a wrong size in the "cms:tag image" preview window (Björn)
* FIXED: captcha class flushes output buffer (Björn)
* FIXED: Linefeed in class.SF_UTILS_Mail.php is now "\r\n" and not "\n" (thx to STam)
* FIXED: Email address Handling does not work correctly in class.SF_UTILS_Mail.php (thx to STam)
* FIXED: UTF-8 Typo in fnc.mipforms (thx to andi)
* FIXED: formtypo in inc.header.php (thx to mrtt)
* FIXED: project settings: generating thumbs in mode 2+3 does not work correctly (thx to bkm)
* FIXED: administration->user: clicking the user deactivation button resets filter and page position (Björn)
* FIXED: adminstration->projects->langsettings: automatic iso code langswitch scanner does not work with more than two lettercodes. Examples: 'en', 'de' works, 'en_us', 'de_de' fails (Björn)
* FIXED: API: typo in class.SF_API_ObjectStore.php: change $this->stroe to $this->_store (Björn)



Sefrengo v1.4.0-beta2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.03.01<br/>
Release: 16.11.2006

* ADDED: update standard mods to newest version: 
  navigation 2.1, pic-galerie 3.4.3,  URHere 1.1.1, 
  Druckversion 1.3, Login 2.0, Contentflex 1.1.2, Kontakformular 2.0 (Björn)
* ADDED: new backend design (Olaf, Alexander, Björn)
* ADDED: add new directory files to projektXX/cms/ folder - must have write permission (Björn)
* ADDED: enhanced rightmanagement [recursive delete, recursive have_perm(), user can be member in more than one group] (Björn)
* ADDED: enhanced usermanagement [paging, user full text search, informations about last login, failed logins, etc.] (Björn and Alexander)
* ADDED: recursive copying of folders (Björn)
* ADDED: API class to generate CaptchaImages (amk and Björn)
* ADDED: .htaccess 'deny from all' protection in log folders (Björn)
* ADDED: fckeditor 2.3.1 (amk)
* ADDED: Tab-Support in mip_forms (Reto)
* ADDED: utf8 collation for mysql database connection selectable in config.php (Björn)
* ADDED: possibility to set pconnect parameter in config.php (Björn)
* ADDED: show idupl in file manager popup (Björn)
* ADDED: new permissions for backend user. Gives possibility to administrate area users and groups.
* CHANGED: update projektvorlage (Björn)
* CHANGED: update readme.txt (Alexander)
* REMOVED: remove backend/phpinfo.php for security reasons (Björn)
* REMOVED: remove knownissues.txt (Björn)
* FIXED: ADO uses only pconnect (Björn)
* FIXED: module update overwrites alternative name (Björn)
* FIXED: wrong mysql_pconnect call in  db_mysql.php (Alexander)
* FIXED: in some cases user can't delete an unused templates (Björn)
* FIXED: if a page is not found send correct 404 header (Björn)
* FIXED: on session timeout redirect to backendlogin if backenduser edit a "frontendpage" (Björn)
* FIXED: Pluginerror Fatal error: Cannot redeclare class xyz (STam)
* FIXED: if frontend hase no pages an error occurs (Call to a member function MoveNext() on a non-object) (Björn)
* FIXED: add attribute autocomplete="off" in usermanagement to some fields (Björn)
* FIXED: in use with mod_rewrite 2 anchors can't be set (Björn)
* FIXED: the download button for css files doesn't work (Björn)
* FIXED: no cursor in text fields near the rightpopup (Björn)
* FIXED: make new pages automatically protected if cat above ist protected (Björn)
* FIXED: standalone UTF-8 converter hides database textfield in IE (Björn)
* FIXED: in some cases PEAR image_transform throws an error if php gives image resources free. fixed in the pear package (Björn)
* FIXED: sql fixes in MySql5: Column 'idlang' in from clause is ambiguous (Björn)
* FIXED: page redirecta doesn't work (Björn)
* FIXED: wrong include_path in lang_plug_edit.php (Reto)
* FIXED: mass mod_rewrite url function does not work correctly in some cases (Björn)
* FIXED: in some cases mod_rewrite 2 accepts free parameters in the url (Björn)
* FIXED: strip out version number in frontend generator tag for security reasons (Björn)
* FIXED: check config constant CMS_CONFIGFILE_INCLUDED in langfiles (Björn)
* FIXED: updating dedi guestbook with sefrengo guestbook doesn't match (Björn)



Sefrengo v1.3.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.03.00<br/>
Release: 30.06.2006

* ADDED: new setupdesign and functionality (Reto, Axxxcel, Olaf)
* ADDED: add constant SF_SKIP_HEADER, if set no content-type header will sen in the inc.init_external.php (Björn)
* ADDED: new $sf_factory helper methods: sf_factoryCallMethod(), sf_factoryCallMethodCache() (Björn)
* ADDED: use new DbCache Class to generate  con_tree and con_side arrays - improves performance ~15% (Björn)
* ADDED: use ADO DB to generate con_tree and con_side arrays (Björn)
* ADDED: show ADO DB Querys in cms_debug object (Björn)
* ADDED: add new class DbCache in package UTILS (Björn)
* ADDED: new backendfeature: user can regenerate all spoken urls (if rewrite 2 support is activated) (Björn)
* ADDED: new class ArrayIterator in package UTILS (Björn)
* ADDED: add helper functions for sf_factory (Björn)
* ADDED: add package PAGE (Björn)
* ADDED: new classes Catinfos and Pageinfos in package PAGE - $con_side, $con_tree and $tlo_tree are produced in this classes. In a long wiew this classes will replace the global vars in the future. (Björn)
* ADDED: possibility to define "real" custom 404 errorpage by using mod_rewrite=2 in the projectsettings. In this mode the apache (.htaccess as example) errorpage won't work anymore. (Björn)
* ADDED: Packages ASSETS and UTILS (Björn)
* ADDED: Class DbFile provides simple access and manipulation methodes for files in the filemanager (Björn)
* ADDED: Class mail provides advanced mime Mail features with possibility to send emails to usergroups or users. (Björn)
* ADDED: add basehref config (Björn)
* ADDED: new  lang folders de, en and fr (Björn)
* ADDED: mod_rewrite spoken url support (automatic and manual generation) (Björn)
* ADDED: config option disable session by useragent (Björn)
* ADDED: config option disable session by ip (Björn)
* CHANGED: replace phplib::delete_cache() with DbCache::flushByGroup() or DbCacheflushAll() (Björn)
* CHANGED: replace all $HTTP_* vars with $_(GET|POST|COOKIE|SERVER) vars (Björn)
* CHANGED: change API loader, method getObjectForced is deprecated, new method getObjectCache will replace getObject, getObject replace getObjectForced (Björn)
* REMOVED: kick old lang folders (Björn)
* REMOVED: remove all phplib query cache calls (Björn)
* NOTE: phplib query cache is deprecated (Björn)
* FIXED: typo in class.SF_HTTP_WebRequest.php - doesn't match magic_quotes_gpc = on (Björn)
* FIXED: correct .htaccess error documents (syntax doesn't support full urls) (Björn)
* FIXED: updatescript: backendmenu entry in table cms_backendmenu, field entry_url: replace value dedi_plugin with value cms_plugin (Björn)
* FIXED: by saving content strip trailingslashes if they occur in internal links (Björn)
* FIXED: backend_lang choser for english and francais don't work correctly (Björn)
* FIXED: validate $lang and $client as int  in class.values_ct.de (Björn)
* FIXED: style select in fckeditor does not work (Björn)
* FIXED: "generate urls for all" action does not work. (Björn)
* FIXED: some minor fixes (Björn)



Sefrengo v1.2.2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.02<br/>
Release: 08.04.2006

* FIXED: tar bulk-upload was not possible in filemangager (Björn)
* FIXED: replace old <dedl:lay in some cms_values, like the layout template (Björn)
* FIXED: Fix  MySql 5 problems (Björn)
* ADDED: add fckeditor 2.2 (Björn)
  * FIXED: better support for nested html lists, but HTML code is still buggy/ not a valid nested list
  * FIXED: correct height and width of some dialogs
* ADDED: frontend con_side sql optimization (Björn)
* CHANGED: update projektvorlage (Björn)



Sefrengo v1.2.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.01<br/>
Release: 06.01.2006

* REMOVED: cms:tag dependency (Reto)
* FIXED: don't accept global include_pathes any longer. Only in sefrengo registered pathes will work (Björn)
* FIXED: update projektvorlage (Björn)
* FIXED: In some cases the UTF-8  check routine crashes (API/HTTP/Attic/class.SF_HTTP_WebRequest.php) (Björn)
* ADDED: cms:tag date: formDateFormat functionality (Reto)
* FIXED: Firerfox 1.5 doesn't show FCK Editor Toolbar in full width (640px) (Björn)
* FIXED: automatic module update from "dediflex" and "deitag Eingabefeld" doesn`t work (Björn)
* REMOVED:  cms:tag time (Reto)
* CHANGED: modify cms:tag date  time and date in one cms:tag (Reto)
* FIXED: cms:tag img some fix for XHTML (Reto)
* FIXED: all cms:tags fixe some span problems (Reto)
* FIXED: fix utf-8  umlaut error  in perminvalid.php (and yes: the egnlish word for "umlaut" is "umlaut" :)) (Björn)
* FIXED: fix wrong meta_fields in projectsettings (Björn)
* FIXED: fix cms://'* REMOVED: Link convert problem - some empty links would be displayed (Björn)
* FIXED: correct utf-8 convert problem in the mod default configs (Björn) 
* FIXED: fix wrong "path to backend " langstring (Björn)
* FIXED: Ehrenrettung: searched string  "dirigent" and replaced with "sefrengo" (Björn)
* FIXED: disable autocomplete for user forms - fierfox doesn't ask  for saveing formdata by creating new user/ edit user (Björn)
* FIXED: if no mysql connect possible -> script dies. Prevent logfile overflow (Björn)
* FIXED: if backenduser change client/ project in a pluginarea, redirect to area con (Björn)
* FIXED: if backebnduser change project-language and the current area is an inline (single) plugin (like terminkalender), the plugin area wasn't lost and teh user is after changing language in the correct plugin area (Björn)
* ADDED: setup routine: disable autocomplete for mysql data form - fierfox doesn't ask  for saveing formdata  (Björn)
* FIXED: setup routine tool utf9_convert: make utf8_converter mysql 4.1 compatible (Björn)
* FIXED: cast lang and client in class.values_ct.php for security issues (Björn)
* ADDED: add js method to deactivate lang-sync select in inc.con_frameheader.php (Björn)
* FIXED: correct lang codes in inc.clients.php (Björn)
* FIXED: remove some old dedi vars (Björn)
* FIXED: mod_rewrite urls on multiple language pages crashes (Björn)
* FIXED: page copy function: user without admin permisson have side effects (Björn)
* FIXED: mod_rewrite support: links of multi language pages crashes, if users current position is not the default language (Björn)
* FIXED: plugin system: add broken setup routine for client_install.meta, client_uninstall.meta, client_update.meta (Björn)
* CHANGED: plugin system: *.meta files handle php coder only, if the <?php starttag ist at the beginnin of one row. In the past, some sqls with contents like "foo<?php bar" crashes (Björn)
* FIXED: plugin system: convert modules from iso8859-1 to utf-8 before import (Björn)
* FIXED: setup: correct sql update for converting dedi to cms langstrings (Björn)



Sefrengo v1.2.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.00<br/>
Release: 29.11.2005

* FIXED: add charset to setup templates (Björn)
* REMOVED: remove system settings for gd-version and imagemagick (Björn)
* CHANGED: modify some imageconverter releated langstrings (Björn)
* CHANGED: update projectvorlage.tar (Björn)
* FIXED: update contentflex (Björn)
* FIXED: in some cases changes in the doctype select (area design->lay) wasn't save (Björn)
* FIXED: update PEAR - use standard PEAR PAckage and call the packages in the "standard way" without custom hacks (Björn)
* ADDED: add PEAR Quickforms (Björn)
* ADDED: if user upload files, replace troublechars in filenames like ' !ÄÖÜ' or SPACE with '-' (Björn)
* FIXED: fix copy/ paste bug in fck editor - occurs in mozilla based browsers (Björn)
* ADDED: favicon for frontend/ backend (Alexxx)
* FIXED: remove dedi comment from pluginvorlage.tar (Björn)
* FIXED: cast $idcat, $idcatside for Security issues (Björn)
* REMOVED: remove domain cookie var from setup sql dump (Björn)
* FIXED: correct path_seperator routine (Björn)
* FIXED: cast $idcat, $idcatside for Security issues (Björn)
* CHANGED: stop supporting the session challenge feature (Björn)
* FIXED: change cache routine sql INSERT INTO to REPLACE INTO (Björn)
* FIXED: values_ct editor slashes quotes in system ans project settings (Björn)
* FIXED: correct API path for frontend (Björn)
* FIXED: Setup update makes a turnaround from step5 to step 1 if user updates a sefrengo version > 1.0.3 (Björn)
* FIXED: securityfixes $cfg_client, $cfg_cms attack (Björn)



Sefrengo v1.2.0-beta
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.92<br/>
Release: 12.11.2005

* FIXED: fix repair_dependency (Roland)
* ADDED: new feature "content sync. from other projectlang, pagebased" (Björn)
* ADDED: new feature "copy page" (Björn)
* FIXED: change $session->url to $session->urlRaw() in module updates (Björn)
* CHANGED: change version number to 1.01.92 (Sefrengo 1.2 beta) (Björn)
* ADDED: add some standard mods (Björn)
* FIXED: sefrengo displays double pagecontent on the same page  if <DEDIPHP> and <CMSPHP>  booth used the same time (Björn)
* FIXED: converting dedi_perms to cms_perms in update routine (Björn)
* CHANGED: remove 1.0.3 updatefile for lang - needless (Björn)
* FIXED: correct dateformat in errorlog  - now shows the "numeric" day - new dateformat is YYYY-mm-dd (D) (Björn)
* FIXED: solve sql error in cms:image tag form. Occurs if a thumbimage was chosen and backenduser change to the form view (Björn)
* FIXED: custom hide folders for cms:tag image/ file doesn't work (Björn)
* CHANGED: autostart feature in frontend have now access to $output var (Björn)
* FIXED: drop autoadd {table_prefix} in sql statements - values like cms_perms crashes in use without the standardprefix "cms" (Björn)
* FIXED: send default utf8 header in inc.content_external.php (Björn)



Sefrengo v1.2.0-alpha2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.91<br/>
Release: 05.10.2005

* CHANGED:  update projektvorlage (Björn)
* ADDED: add fckeditor 2.1.1 (Björn)
* FIXED: correct updateroutine for insert the correct startlang (Björn)
* CHANGED: remove old java wysiwyg editor files (Björn)
* FIXED: fix http cookie showstopper (Björn)
* CHANGED: dedi backward compatibility: (Björn)
  $dedi_db is reference to $cms_db
  $dedi_cfg is reference to $cms_cfg
  $dedi_mod is reference to $cms_mod
* FIXED: updating an existing module does not work (Björn)  
* FIXED: fix: wrong session cookie (Roland)
* ADDED: cookie domain setup (Roland)
* ADDED: cookie domain configuration backend/client (Roland)
* ADDED: updates for cookie domain support (client update needs test* NOTE: - not implemented) (Roland)
* NOTE: TODO: cookie domain on update
* FIXED: fix import from [dediplugin] (Roland)
* FIXED: correct cms:tag radio, checkbox, select for UTF-8 content (Reto)
* FIXED: correct cms:tag checkbox default value behaviour (Reto)
* FIXED: strip quotes and php code from metatags (Björn)
* FIXED: make sourcepad utf-8 save (Björn)
* FIXED: make ü to entitie in mod.contentflex.php (Björn)
* FIXED: correct geramn spelling author to autor (Björn)
* FIXED: correct js alert "Wirklich löschen?" with js entitie (Björn)
* FIXED: a* REMOVED: tag bug around the username in header.tpl (right hand on the top of the backend) (Björn)
* ADDED: update script now handles container config.  configs will be encode to utf8, dedilinks will convert to cms://links (Björn)
* FIXED: many fixes for the update routine (Björn)
* ADDED: integrate utf-8 converter to setup routine (Björn)
* ADDED: begin to add seperate sql update scripts for client and lang updates and a possibility to eval php update files (Björn)
* ADDED: add standalone utf-8 converter / deconverter script for mysql data (Björn)
* FIXED: fix problems with trailing slashes in frontend meta tags in relation with HTML, XHTM (Björn)
* CHANGED: update projektvorlage (Björn)
* FIXED: take checkbox, radio, select modules to import area, remove parentid (Björn)
* ADDED: add fckeditor 2.0 (Björn)
* CHANGED: change sefrengo links to cms links (Björn)
* FIXED: now accepts https, http and ftp links in cms:link tag (Björn)
* ADDED: new perms for startlang and starttpl (Björn)
* FIXED: check perms for startlang and starttpl (Björn)
* FIXED: set startlang to first lang by creating a new client/ project and make utf-8 as default charset (Björn)
* FIXED: update js popup functions (scrollbars in firerfox) (Björn)
* FIXED: add entities for zurücksetzen langstring (Björn)
* CHANGED: change mod_rewrite names from {IDLANG}side{IDCATSIDE} to page{IDLANG}-{IDCATSIDE} -> page1-3.html - same to {IDLANG}cat{IDCATSIDE} (Björn)
* ADDED: predefine error commands in htaccess.txt (Björn)
* FIXED: if backendusers current position is area "lay_edit" and user switch to another project/ client, redirect to area lay (overview) (Björn)
* FIXED: default layout now with title tag (Björn)
* FIXED: overwrite choosen template with starttemplate by editing  folder (Editing -> pages) (Björn)
* FIXED: fckeditoor doesn't load by using *x Systems (Björn)



Sefrengo v1.2.0-alpha
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.90<br/>
Release: 07.09.2005

* CHANGED: update some standard mods / remove some - (Björn)
* ADDED: add mod content:flex as standard mod (Reto)
* FIXED: fix anchor hover bug in firerfox when user move mousepointer over inputvalues (Björn)
* FIXED: fix border="0" bug in image function (Björn)
* ADDED: make utf-8 as standard charset for backend and frontend (Björn)
* ADDED: frontend supports utf-8 and iso-8859-1 cahrsets (Björn)
* REMOVED: remove all old wysiwyg editors (htmlarea1, htmlarea2, kafenio) (Björn)
* ADDED: add doctype choser for html 4.0.1 trans and xhtml 1.0 trans (Björn)
* ADDED: sefrengo produces valid XHTML 1.0 trans code (Björn)
* ADDED: WebRequest class - handels $_REQUEST parameters (Björn)
* ADDED: add save/ apply /cancel buttons in all areas (Björn)
* ADDED: add new cms:tags checkbox, radio and select (Reto)
* ADDED: picture preview for cms:tag image in for mode (Björn)
* ADDED: pick cms:tag items file, link, image with the ressource browser (Björn)
* ADDED: add adoDB classes (Björn)
* ADDED: add classloader/ API (Björn)
* CHANGED: move metatag config from client to language settings (Björn)
* ADDED: new config area for language settings  (Björn)
* ADDED: browserspecific startlang (based on http header values from clientbrowser) (Björn)
* ADDED: choose startlang in projectsettings (Björn)
* ADDED: choose starttempalte in design-> templates (Björn)
* ADDED: "new lang action" copys all templates and content to new generated lang (Björn)
* ADDED:  Singel-Login kann erzwungen werden mit 'force_single_login' (Roland)
* ADDED: Challenge verfügbar für alle Requests (Roland)
* ADDED: Login mit Challenge (Roland)
* ADDED: Session kann 'verlängert werden' um die Laufzeit  der Session wenn die Letze Nutzung nach 2/3 der Laufzeit liegt (Roland)
* FIXED: FIX: Vulnerable 'doubble Login' -> siehe Feature, 'user Inject Session-Id' -> Session-Id min. 32 Zeichen * ADDED: Session-Id darf nicht neu* NOTE: sein,
  'Same Session-Id in Use' -> 2 User können sich keine Session-Id teilen (Roland)
* CHANGED: Update: Alle DeDi Verweise in CMS konvertiert, alte DeDi-Schnittstellen werden per Referenz erhalten (Roland)
* CHANGED: Fork from DeDi 01.00.02 to Sefrengo 01.00.02 (Roland)
* CHANGED: deactivate setup* REMOVED: path tests for windows (Björn)



Sefrengo v1.0.3
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.00.03<br/>
Release: 18.06.2005

* FIXED: * FIXED: JS* REMOVED: Popupbug in backend "standard.js" - same problems like the frontend standard.js (Björn)
* FIXED: Update Session - fix "accept any data as session" bug (STam)  
* FIXED: Layout Testscreen in Setup (Reto)
* FIXED: too many " in span (text, textarea, wysiwyg) (Reto)
* CHANGED: Lists in BBCode (Reto)
* ADDED: add FCK Editor (Björn)
* ADDED: add Ressource Browser (Björn)
* CHANGED: Make fck Editor to the dafault editor in a fresh installation in config.sql (Björn)
* REMOVED: remove htmlarea3 (Björn)
* ADDED: Add Setup* REMOVED: updatefile for Sefrengo 1.0.3 (Björn)
* CHANGED: update mod deditag eingabefeld from 1.1 to 1.2 (Björn)
* CHANGED: downgrade navigation from 1.1.1 to 1.1 (Björn)
* FIXED: Error while saving/ updating data in class.values_ct.php.  Given parameters with quotes become slashes  if magic_quotes_gpc=off (Björn)
* CHANGED: edit backandtitle from "01.00.01 p1" to "01.00.01 sf [bugfix_nr]" -> current is "01.00.01 sf3" (Björn)
* FIXED: JS* REMOVED: Popupbug in "standard.js" - mozilla based browsers calculates false popup sizes (Björn)
* CHANGED: Update "docs/update.pdf" . Please read when you would like to update your DeDi* REMOVED: installation (Björn)
* NOTE: new changelog syntax - the changeloglanguage is english !from now on* NOTE: (Björn)



Sefrengo v1.0.2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.00.02<br/>
Release: 19.03.2005

* FEATURE: loginmodul 1.3.3sf hinzugefügt (Björn)
* FIX:  modulfehler bei array_multisort in der Modulkonfiguration angepasst
* MISC: mod_rewrite_support für dedi erweiterung entfernt
* FIX: reset menuheight in function createMenu() - otherwise in IE a display error occurs, if you call [first] a large menu and at [second] a small menu. (Björn)
* FEATURE: Bildpopupfunktion von Aki und saschapi für wysiwyg2 eingebaut (Björn)
* FIX: CSS-Editor-Unterstützung der Eingabe bei neuen CSS-Regeln (Jürgen)
* FIX: Seiten werden nun richtig angelgt, bei Benutzern, die nicht über das Recht Seiten/ Ordnertemplate konfigurieren, aber über Seiten/ Ordnertemplate auswählen verfügen. Das führte bis hetzt immer zu einer unbrauchbaren Seite. (Björn)
* FIX: HTMLAREA3 - Popupfenster zu klein, buttons/ funktionalität wurde abgeschnitten (Björn)
* FIX: HTMLAREA3 - einige fehlende Sprachstrings übersetzt (Björn)
* FIX: Insideediting Mozilla - editieren bei leeren Contentfeld war nicht möglich (Björn)
* FIX: Bei "wysiwyg mode = 3" wurde opera, etc. nicht berücksichtigt - jetzt fallback auf java* REMOVED: wysiwyg editor kafenio (Björn)
* FEATURE: Htmlarea3 an DeDi Desgin anpassen (Björn)
* FIX: Htmlarea3: deditag* REMOVED: feature attribut "indent" falsch zugeordnet (Björn)
* FEATURE: Sprachstring für HA3 Projekteinstellung hinzugefügt: 3= kein IE* ADDED: Mozilla (Björn)
* FEATURE: Neue Webgrabversion von kfo eingebaut (Björn)
* FEATURE: Modulupdates/ einige Module ugedated, einige neu, alte entfernt (Björn)
  Neue Module:
    - Categorywalker 1.0.3
    - Gästebuch 1.0
  Modulupdates:
    - DeDiFlex 0.91
    - Dynamische Uhrzeit 1.0.1
    - Info 1.0
    - Login 1.2
    - Webgrab 1.4
    - Teaserbuilder 1.0.1
* MISC: Modulupdates in SQL eingespielt (Björn)
* MISC: Projektvorlage geupdated, wegen neuer Kafenio Dateien (Björn)
* FEATURE: in einige Verzeichnisse zur Sicherheit .htaccess Dateien mir "Deny from all" Regel hinzugefügt (Björn)
* FEATURE: Setup um Tests bei der Installation erweitert (Reto)
* FEATURE: mip_form Applikation für Dateien hinzugefügt "app_file" (Reto)
* FEATURE: textara-Tag -> transform=bbcode (Reto)
* FEATURE: file-Tag -> filesize, fmtitle, fmdesc, filetype,filename (Reto)
* FEATURE: image-Tag -> filesize, fmtitle, fmdesc, filetype (Reto)
* FIX: typegrop PopUp war fehlerhaft wenn nichts zum bearbeiten war (Reto)
* FIX: falsche Ausgabe beim Image-tag bei Kombination "editbutton" mit menuoptions="false" (Reto)
* FIX: original Bild ausgeben wenn kein Thumb vorhanden (Reto)
* FIX: blank-replace bug behoben (Karsten)
* FEATURE: Kafenio aktualisiert Version 0.8.5 (Reto)
* FIX: Abbruch beim installieren von Plugins (Roland)
* FIX: Thumbnail-Generierung für Option x-fest,y-skaliert bzw. y-fets,x-skaliert (Jürgen)
* FIX: CSS-Editor in französischer Version ohne Funktion wegen fehlender JS-Datei (Jürgen)
* FIX: Image-Anzeige im CSS-Regeleditor, Initalisierung des Editor angepasst (Jürgen)
* Update Projektvorlage (Björn)
* SECUIRTYFIX: Includeangriffe bei HTMLAREA3 (Björn)
* FRATURE: Inlineediting und WYSIWYG (HTMLAREA3) für Mozilla (Björn)
* FIX: Inlineediting Mozilla, Speichernbutton war nicht sichtbar (Björn) 
* FIX: Schreibfehler in der robots.txt (Björn)
* FEATURE: nervige index.html aus dem Projektverzeichnis gekickt (Björn)
* SECURITYFIX: Angriff auf includes mit Variable bei register_globals = on (Björn)
* FEATURE: pagedDoubletAudit (Björn)
* SECURITYFIX: $code konnte manipulliert werden (Björn)
* SECURITYFIX: Variablen in config.php konnte von get/ post/ cookie Parametern überschrieben werden (Björn)
* FIX: mip_form apps cache erzeugte manchmal identische id's (Björn)
* FIX: app groups zeigt nur geuppen an, welche eine id > 3 hatten, jetzt id > 2 (Björn)
* FIX: Tar Archive (extract to lower case) (Roland)
* FIX: Fehlermeldungen Modul/Plugin Upload (Roland)
* FIX: Falsche verzeichnisanzeige nach Bulk-Upload (Jürgen)
* FIX: Titel beim Kopieren von Dateien (Jürgen)
