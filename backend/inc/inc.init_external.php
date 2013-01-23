<?PHP
// File: $Id: inc.init_external.php 314 2010-09-06 11:12:13Z andre $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name:  $                                          
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 - 2007 sefrengo.org <info@sefrengo.org>           |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License                 |
// |                                                                      |
// | This program is subject to the GPL license, that is bundled with     |
// | this package in the file LICENSE.TXT.                                |
// | If you did not receive a copy of the GNU General Public License      |
// | along with this program write to the Free Software Foundation, Inc., |
// | 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA               |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// +----------------------------------------------------------------------+
// + Autor: $Author: andre $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 314 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

if (function_exists('set_magic_quotes_runtime')) {
    @set_magic_quotes_runtime (0);
}

// zeige alle Fehlermeldungen, aber keine Warnhinweise und Deprecated-Meldungen
if (defined('E_DEPRECATED'))
{
	error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED);
}
else
{
	error_reporting (E_ALL & ~E_NOTICE);
}


//send header
if(! defined('SF_SKIP_HEADER') )
{
	header('Content-type: text/html; charset=UTF-8');
}

if(! defined('SKIP_COMMON_SETTINGS') ){
	// Output buffering starten
	ob_start();
	
	
	// alle GET, POST und COOKIE wegen Globals_off parsen
	// $types_to_register = array('GET','COOKIE','POST','SERVER','FILES','ENV','SESSION','REQUEST');
	$types_to_register = array('GET','POST','SERVER');
	foreach ($types_to_register as $global_type) {
	        $arr = @${'HTTP_'.$global_type.'_VARS'};
	        if (@count($arr) > 0) extract($arr, EXTR_OVERWRITE);
	        else {
	                 $arr = @${'_'.$global_type};
	                if (@count($arr) > 0) extract($arr, EXTR_OVERWRITE);
	        }
	}
	
	$sefrengo = ( empty($sefrengo) ) ? $_COOKIE['sefrengo']: $sefrengo;
}

// notwendige Dateien includen
$this_dir = str_replace ('\\', '/', dirname(__FILE__) . '/');
//go directory up
$strip_slash = substr ($this_dir, 0, strlen($folder)-1);
$to_replace = strrchr ($strip_slash, "/");
$pos = strrpos ($strip_slash, $to_replace);
$this_dir = substr($strip_slash, "0",$pos )."/";
chdir($this_dir);
if(! defined('SKIP_COMMON_SETTINGS') ){
	require_once ($this_dir.'inc/config.php');
	//Load API
	require_once ($this_dir.'API/inc.apiLoader.php');
	
	require_once ($this_dir.'inc/class.cms_debug.php');
	include_once ($this_dir.'external/phplib/prepend.php');
	include_once ($this_dir.'inc/class.user_perms.php');
	include_once ($this_dir.'inc/class.values_ct.php');
	require_once ($this_dir.'inc/fnc.general.php');
	include_once ($this_dir.'inc/class.querybuilder_factory.php');
	
	// Klassen initialisieren
	$deb    = &new cms_debug;
	$db     = &new DB_cms;
	$val_ct = &new values_ct();
	
	// Konfigurationsparameter einlesen
	$cfg_cms_temp = $val_ct -> get_cfg();
	$cfg_cms = array_merge($cfg_cms, $cfg_cms_temp);
	//print_r($cfg_cms);
	unset($cfg_cms_temp);
}

// Session starten
if(! defined('SF_USE_FRONTEND_SESSION') ) {
	page_open(array('sess' => 'cms_Backend_Session',
                'auth' => 'cms_Backend_Auth'));
} else {
	page_open(array('sess' => 'cms_Frontend_Session',
                	'auth' => 'cms_Frontend_Auth'));
}

// Sessionvariablen initialisieren
$sess->register('sid_client');
$sess->register('sid_lang');
$sess->register('sid_lang_charset');
$sess->register('sid_area');
$sess->register('sid_sniffer');
$client       = (empty($client))       ? $sid_client       : $client;
$lang         = (empty($lang))         ? $sid_lang         : $lang;
//hack
$lang_charset = (empty($lang_charset)) ? 'iso-8859-1' : $lang_charset;


$perm         = &new cms_perms($client, $lang);
$client       = $perm -> get_client();
$lang         = $perm -> get_lang();
$lang_charset = $perm -> get_lang_charset();

// Projekt initialisieren
$sid_client = $client;
$cfg_client = $val_ct -> get_by_group('cfg_client', $client);

// Sprache initialisieren
$sid_lang         = $lang;
$sid_lang_charset = $lang_charset;

// Sprachdatei einlesen
// change jb
$lang_dir = $this_dir.'tpl/'.$cfg_cms['skin'].'/lang/'.$cfg_cms['backend_lang'].'/';
if (file_exists ($lang_dir.'lang_general.php')) {
	require_once($lang_dir.'lang_general.php');
} else {
	require_once($this_dir.'tpl/standard/lang/de/lang_general.php');
}
// Sprachdatei speziell für externe Einbindungen einlesen
if (file_exists ($lang_dir.'lang_general_external.php')) {
	require_once($lang_dir.'lang_general_external.php');
} else {
	require_once($this_dir.'tpl/standard/lang/de/lang_general_external.php');
}
// change jb
?>