<?php
require_once('fnc.fck.php');
require_once('../../../inc/config.php');
require_once('../../../../'.$cms_path.'inc/inc.init_external.php');

//echo $o1->get() . '<br />';//Gibt "Moin, I am a simple Test" aus
//require_once('ResourceBrowser/API/GUI/class.SF_GUI_RessourceBrowser.php');
//require_once('ResourceBrowser/API/GUI/RESSOURCES/class.SF_GUI_RESSOURCES_Abstract.php');
//require_once('ResourceBrowser/API/GUI/RESSOURCES/class.SF_GUI_RESSOURCES_FileManager.php');
//require_once('ResourceBrowser/API/GUI/RESSOURCES/class.SF_GUI_RESSOURCES_InternalLink.php');
//require_once('ResourceBrowser/API/GUI/RESSOURCES/class.SF_GUI_RESSOURCES_RessourceItemPrototype.php');

$fck_editorname = $_GET['fck_editorname'];
$fck_conf = unserialize( base64_decode($_GET['fck_ser']) );
$fck_menu_items = fckBuildMenu($fck_conf['features'], $fck_conf['selectablestyles']);
$fck_session_string = $fck_conf['sess_name'].'='. $fck_conf['sess_id'] ;
//print_r($fck_conf);
//linkbrowser
$rb =& $sf_factory->getObjectForced('GUI', 'RessourceBrowser');
$rb->setExtraUrlParmString($fck_session_string);

$res_file =& $sf_factory->getObjectForced('GUI/RESSOURCES', 'FileManager');

//print_r(get_class_methods($res_file));

$res_file->setFiletypes( fckConfigStringToArray($fck_conf['filefiletypes']) );
$res_file->setFolderIds( fckConfigStringToArray($fck_conf['filefolders']) );
$with_subfolders = ($fck_conf['filesubfolders'] != 'false') ? true:false;
$res_file->setWithSubfoders($with_subfolders);
$rb->addRessource($res_file);

$res_links =& $sf_factory->getObjectForced('GUI/RESSOURCES', 'InternalLink');
$rb->addRessource($res_links);

//$res_prototype = new SF_GUI_RESSOURCES_RessourceItemPrototype();
//$rb->addRessource($res_prototype);

$rb->setJSCallbackFunction('SetUrl', array('picked_value') );

//imagebrowser
$rb_image =& $sf_factory->getObjectForced('GUI', 'RessourceBrowser');
$rb_image->setExtraUrlParmString($fck_session_string);
$res_file_im =  $sf_factory->getObjectForced('GUI/RESSOURCES', 'FileManager');
$res_file_im->setFiletypes( fckConfigStringToArray(
								$fck_conf['imagefiletypes'] == 'true' || empty($fck_conf['imagefiletypes'])
									? 'jpg,jpeg,gif,png' : $fck_conf['imagefiletypes']) );
$res_file_im->setFolderIds( fckConfigStringToArray($fck_conf['imagefolders']) );
$with_subfolders = ($fck_conf['imagesubfolders'] != 'false') ? true:false;
$res_file_im->setWithSubfoders($with_subfolders);
$rb_image->addRessource($res_file_im);
$rb_image->setJSCallbackFunction('SetUrl', array('picked_value') );


//print_r($fck_conf);
?>

FCKConfig.DisableEnterKeyHandler = false ;

FCKConfig.CustomConfigurationsPath = '' ;

//FCKConfig.DocType = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' ;

FCKConfig.EditorAreaCSS = FCKConfig.BasePath + 'sefrengo/inc.fck_style_collector.php?<?php echo $fck_session_string .'&sf_idlay='. $fck_conf['sf_idlay'] ?>';
FCKConfig.ToolbarComboPreviewCSS = '' ;

FCKConfig.StylesXmlPath	= 'sefrengo/fckstyles.php?<?php echo $fck_session_string .'&selectablestyles='.$fck_conf['selectablestyles'] ?>';

FCKConfig.TemplatesXmlPath	= FCKConfig.EditorPath + 'fcktemplates.xml' ;

FCKConfig.BaseHref = '' ;

FCKConfig.FullPage = false ;

FCKConfig.Debug = false ;
FCKConfig.AllowQueryStringDebug = true ;

FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/office2003/' ;
FCKConfig.PreloadImages = [ FCKConfig.SkinPath + 'images/toolbar.start.gif', FCKConfig.SkinPath + 'images/toolbar.buttonarrow.gif' ] ;

FCKConfig.PluginsPath = FCKConfig.BasePath + 'sefrengo/plugins/' ;

//FCKConfig.Plugins.Add( 'placeholder', 'en,it' ) ;
FCKConfig.Plugins.Add( 'tablecommands') ;

FCKConfig.Plugins.Add( 'autogrow' ) ;
FCKConfig.AutoGrowMax = 400 ;

FCKConfig.ProtectedSource.Add( /<script[\s\S]*?\/script>/gi ) ;	// <SCRIPT> tags.

FCKConfig.AutoDetectLanguage	= true ;
FCKConfig.DefaultLanguage		= 'en' ;
FCKConfig.ContentLangDirection	= 'ltr' ;

FCKConfig.EnableXHTML		= true ;	// Unsupported: Do not change.
FCKConfig.EnableSourceXHTML	= true ;	// Unsupported: Do not change.

FCKConfig.ProcessHTMLEntities	= true ;
FCKConfig.IncludeLatinEntities	= false ;
FCKConfig.IncludeGreekEntities	= true ;

FCKConfig.ProcessNumericEntities = false ;

FCKConfig.AdditionalNumericEntities = ''  ;		// Single Quote: "'"

FCKConfig.FillEmptyBlocks	= true ;

FCKConfig.FormatSource		= true ;
FCKConfig.FormatOutput		= true ;
FCKConfig.FormatIndentator	= '    ' ;

FCKConfig.ForceStrongEm = true ;
FCKConfig.GeckoUseSPAN	= false ;
FCKConfig.StartupFocus	= false ;
FCKConfig.ForcePasteAsPlainText	= false ;
FCKConfig.AutoDetectPasteFromWord = true ;	// IE only.
FCKConfig.ForceSimpleAmpersand	= false ;
FCKConfig.TabSpaces		= 0 ;
FCKConfig.ShowBorders	= true ;
FCKConfig.SourcePopup	= false ;
//FCKConfig.UseBROnCarriageReturn	= true ; // new config-options - see some lines below
FCKConfig.ToolbarStartExpanded	= true ;
FCKConfig.ToolbarCanCollapse	= false ;
FCKConfig.IEForceVScroll = false ;
FCKConfig.IgnoreEmptyParagraphValue = true ;
FCKConfig.PreserveSessionOnFileBrowser = false ;
FCKConfig.FloatingPanelsZIndex = 10000 ;

FCKConfig.TemplateReplaceAll = true ;
FCKConfig.TemplateReplaceCheckbox = true ;

FCKConfig.ToolbarSets['SefrengoDefault'] = [
	['NewPage','Preview','Print'],
	['Cut','Copy','Paste','PasteText','PasteWord','RemoveFormat','Undo','Redo','Find','Replace'],
	['Table', '-','TableInsertRowAfter','TableDeleteRows','TableInsertColumnAfter','TableDeleteColumns','TableInsertCellAfter','TableDeleteCells','TableMergeCells','TableHorizontalSplitCell','TableCellProp'], 
  '/',
	['Bold','Italic','Underline','StrikeThrough','Subscript','Superscript'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['TextColor','BGColor'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['Link','Unlink','Anchor','Image','Rule','SpecialChar'],
	'/',
	[<?php echo (! empty($fck_conf['selectablestyles']) ) ? "'Style',":"" ?>'FontFormat','FontName','FontSize'],
	['Source']
] ;

FCKConfig.ToolbarSets['<?php echo $fck_editorname ?>'] = <?php echo $fck_menu_items ?>;

FCKConfig.EnterMode = 'br' ;			// p | div | br
FCKConfig.ShiftEnterMode = 'p' ;	// p | div | br

FCKConfig.Keystrokes = [
	[ CTRL + 65 /*A*/, true ],
	[ CTRL + 67 /*C*/, true ],
	[ CTRL + 88 /*X*/, true ],
	[ CTRL + 86 /*V*/, 'Paste' ],
	[ SHIFT + 45 /*INS*/, 'Paste' ],
	[ CTRL + 90 /*Z*/, 'Undo' ],
	[ CTRL + 89 /*Y*/, 'Redo' ],
	[ CTRL + SHIFT + 90 /*Z*/, 'Redo' ],
	[ CTRL + 76 /*L*/, 'Link' ],
	[ CTRL + 66 /*B*/, 'Bold' ],
	[ CTRL + 73 /*I*/, 'Italic' ],
	[ CTRL + 85 /*U*/, 'Underline' ],
	[ CTRL + ALT + 83 /*S*/, 'Save' ],
	[ CTRL + ALT + 13 /*ENTER*/, 'FitWindow' ],
	[ CTRL + 9 /*TAB*/, 'Source' ]
] ;

FCKConfig.ContextMenu = ['Generic','Link','Anchor','Image','Select','Textarea','Checkbox','Radio','TextField','HiddenField','ImageButton','Button','BulletedList','NumberedList','TableCell','Table','Form'] ;

FCKConfig.FontColors = '000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,808080,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF' ;

FCKConfig.FontNames		= 'Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana' ;
FCKConfig.FontSizes		= 'xx-small;x-small;small;medium;large;x-large;xx-large' ;
FCKConfig.FontFormats	= 'p;div;pre;address;h1;h2;h3;h4;h5;h6' ;

FCKConfig.SpellChecker			= 'ieSpell' ;	// 'ieSpell' | 'SpellerPages'
FCKConfig.IeSpellDownloadUrl	= 'http://wcarchive.cdrom.com/pub/simtelnet/handheld/webbrow1/ieSpellSetup240428.exe' ;

FCKConfig.MaxUndoLevels = 15 ;

FCKConfig.DisableImageHandles = false ;
FCKConfig.DisableFFTableHandles = true ;

FCKConfig.LinkDlgHideTarget		= false ;
FCKConfig.LinkDlgHideAdvanced	= false ;

FCKConfig.ImageDlgHideLink		= false ;
FCKConfig.ImageDlgHideAdvanced	= false ;

FCKConfig.FlashDlgHideAdvanced	= true ;

FCKConfig.LinkBrowser = true ;
FCKConfig.LinkBrowserURL = '<?php echo $rb->exportConfigURL().'&'.$fck_session_string ?>' ;
FCKConfig.LinkBrowserWindowWidth	= screen.width * 0.7 ;	// 70%
FCKConfig.LinkBrowserWindowHeight	= screen.height * 0.7 ;	// 70%


FCKConfig.ImageBrowser = true ;
FCKConfig.ImageBrowserURL = '<?php echo $rb_image->exportConfigURL() .'&'. $fck_session_string ?>' ;
FCKConfig.ImageBrowserWindowWidth  = screen.width * 0.7 ;	// 70% ;
FCKConfig.ImageBrowserWindowHeight = screen.height * 0.7 ;	// 70% ;

FCKConfig.FlashBrowser = false ;
FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Flash&Connector=connectors/asp/connector.asp' ;
// ASP.Net		// FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Flash&Connector=connectors/aspx/connector.aspx' ;
// ColdFusion	// FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Flash&Connector=connectors/cfm/connector.cfm' ;
// Perl			// FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Flash&Connector=connectors/perl/connector.cgi' ;
// PHP			// FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Flash&Connector=connectors/php/connector.php' ;
// PHP - mcpuk	// FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/mcpuk/browser.html?Type=Flash&Connector=connectors/php/connector.php' ;
FCKConfig.FlashBrowserWindowWidth  = screen.width * 0.7 ;	//70% ;
FCKConfig.FlashBrowserWindowHeight = screen.height * 0.7 ;	//70% ;

FCKConfig.LinkUpload = false ;
FCKConfig.LinkUploadURL = FCKConfig.BasePath + 'filemanager/upload/asp/upload.asp' ;
// PHP // FCKConfig.LinkUploadURL = FCKConfig.BasePath + 'filemanager/upload/php/upload.php' ;
FCKConfig.LinkUploadAllowedExtensions	= "" ;			// empty for all
FCKConfig.LinkUploadDeniedExtensions	= ".(php|php3|php5|phtml|asp|aspx|ascx|jsp|cfm|cfc|pl|bat|exe|dll|reg|cgi)$" ;	// empty for no one

FCKConfig.ImageUpload = false ;
FCKConfig.ImageUploadURL = FCKConfig.BasePath + 'filemanager/upload/asp/upload.asp?Type=Image' ;
// PHP // FCKConfig.ImageUploadURL = FCKConfig.BasePath + 'filemanager/upload/php/upload.php?Type=Image' ;
FCKConfig.ImageUploadAllowedExtensions	= ".(jpg|gif|jpeg|png)$" ;		// empty for all
FCKConfig.ImageUploadDeniedExtensions	= "" ;							// empty for no one

FCKConfig.FlashUpload = false ;
FCKConfig.FlashUploadURL = FCKConfig.BasePath + 'filemanager/upload/asp/upload.asp?Type=Flash' ;
// PHP // FCKConfig.FlashUploadURL = FCKConfig.BasePath + 'filemanager/upload/php/upload.php?Type=Flash' ;
FCKConfig.FlashUploadAllowedExtensions	= ".(swf|fla)$" ;		// empty for all
FCKConfig.FlashUploadDeniedExtensions	= "" ;					// empty for no one

FCKConfig.SmileyPath	= FCKConfig.BasePath + 'images/smiley/msn/' ;
FCKConfig.SmileyImages	= ['regular_smile.gif','sad_smile.gif','wink_smile.gif','teeth_smile.gif','confused_smile.gif','tounge_smile.gif','embaressed_smile.gif','omg_smile.gif','whatchutalkingabout_smile.gif','angry_smile.gif','angel_smile.gif','shades_smile.gif','devil_smile.gif','cry_smile.gif','lightbulb.gif','thumbs_down.gif','thumbs_up.gif','heart.gif','broken_heart.gif','kiss.gif','envelope.gif'] ;
FCKConfig.SmileyColumns = 8 ;
FCKConfig.SmileyWindowWidth		= 320 ;
FCKConfig.SmileyWindowHeight	= 240 ;

if( window.console ) window.console.log( 'Config is loaded!' ) ;	// @Packager.Compactor.RemoveLine
