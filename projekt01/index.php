<?PHP
// File: $Id: index.php 308 2010-08-12 15:44:41Z andre $
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
// + Revision: $Revision: 308 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

// Output buffering starten
ob_start();


if (function_exists('set_magic_quotes_runtime')) {
    @set_magic_quotes_runtime (0);
}

// zeige alle Fehlermeldungen, aber keine Warnhinweise und Deprecated-Meldungen
$error_reporting = E_ALL & ~E_NOTICE;
if (defined('E_DEPRECATED'))
{
	$error_reporting &= ~E_DEPRECATED;
}
if (defined('E_STRICT'))
{
	$error_reporting &= ~E_STRICT;
}
error_reporting ($error_reporting);

// alle GET, POST und COOKIE wegen Globals_off parsen
$types_to_register = array('GET','POST','SERVER');
foreach ($types_to_register as $global_type) {
	$arr = @${'HTTP_'.$global_type.'_VARS'};
	if (@count($arr) > 0)
		extract($arr, EXTR_OVERWRITE);
	else {
		$arr = @${'_'.$global_type};
		if (@count($arr) > 0) extract($arr, EXTR_OVERWRITE);
	}
}
$cfg_cms = '';$cfg_client ='';

// Projektkonfiguration laden
include('cms/inc/config.php');

// Session auslesen
if (!empty($view)) {
	$sefrengo = (empty($sefrengo)) ? $_COOKIE['sefrengo'] : $sefrengo;
} else {
	$sefrengo = (empty($sid))  ? $_COOKIE_VARS['sid'] : $sid;
}
//Load API
require_once ($cms_path.'API/inc.apiLoader.php');
// notwendige Dateien includen
if (! is_file($cms_path.'inc/config.php')) {
	die('NO CONFIGFILE FOUND');
}
require_once ($cms_path.'inc/config.php');
require_once ($cms_path.'inc/class.cms_debug.php');
include_once ($cms_path.'external/phplib/prepend.php');
include_once ($cms_path.'inc/class.values_ct.php');
require_once ($cms_path.'inc/fnc.general.php');
include($cms_path.'inc/class.user_perms.php');

// Klassen initialisieren
$deb = new cms_debug;
$db = new DB_cms;
$val_ct = new values_ct();

// Konfigurationsparameter einlesen
$cfg_cms_temp = $val_ct -> get_cfg();
$cfg_cms = array_merge($cfg_cms, $cfg_cms_temp);
unset($cfg_cms_temp);

// Projekt initialisieren
if (!is_numeric($client)) $client=$load_client;
$idcatside = (int) $idcatside;
$idcat = (int) $idcat;


// Projekt-Konfigurationsparameter einlesen
$cfg_client = $val_ct -> get_by_group('cfg_client', $client);
$cfg_client['send_header_404'] = false;

// db_cache initialisieren
// change Roland
// weil hier erst die $cfg_client und $cfg_cms initialisiert sind
$db->init_cache(/*init db_cache with $cfg_client and $cfg_cms*/);
// change Roland

// Sprache aushandeln
$sql  = 'SELECT 
			L.idlang, L.charset, L.name, L.iso_3166_code, L.is_start, L.rewrite_key, L.rewrite_mapping
		FROM 
			' . $cms_db['lang'] . ' L 
			LEFT JOIN '. $cms_db['clients_lang'] . ' CL USING(idlang)
		WHERE 
			CL.idclient = ' . $client;
$db->query($sql);

$langarray = array();
while ($db->next_record()) {
	$tmp_idlang = $db->f('idlang'); 
	$sf_lang_stack[$tmp_idlang]['idlang'] = $tmp_idlang;
	$sf_lang_stack[$tmp_idlang]['charset'] = $db->f('charset'); 
	$sf_lang_stack[$tmp_idlang]['name'] = $db->f('name'); 
	$sf_lang_stack[$tmp_idlang]['iso_3166_code'] = $db->f('iso_3166_code'); 
	$sf_lang_stack[$tmp_idlang]['rewrite_key'] = $db->f('rewrite_key'); 
	$sf_lang_stack[$tmp_idlang]['rewrite_mapping'] = $db->f('rewrite_mapping'); 
		
	if ($db->f('is_start') == 1) {
		$lang_global_startlang = $tmp_idlang;
	}
	
	if (strlen($sf_lang_stack[$tmp_idlang]['iso_3166_code']) >= 2 ) {
		$langarray[ $sf_lang_stack[$tmp_idlang]['iso_3166_code'] ] = $tmp_idlang;
	} 

}

$neg = negotiateLanguage($langarray, 'xx');
if ($neg != 'xx') {
	$startlang = $langarray[$neg];
} else {
	$startlang = $lang_global_startlang;
}


//
// REWRITE
//
if ($_REQUEST['sf_rewrite'] && $cfg_client['url_rewrite'] == '2' && ! isset($view)) {
	include_once($cfg_cms['cms_path'].'inc/fnc.mod_rewrite.php');
	$sf_rewrite_raw = mysql_escape_string($_REQUEST['sf_rewrite']);
	$sf_rw_pieces = explode('/', $sf_rewrite_raw);
	
	$_sf_rewrite_session = true;
	if(preg_match('/^[0-9abcdef]{32}$/', $sf_rw_pieces['0'])) {
		$_GET['sid'] = $_GET['sid'] = $_REQUEST['sid'] = $sf_rw_pieces['0'];
	}
	
	
	//echo " AA ".$lang;
	//test of unique side
	$sql = "SELECT 
				CS.idcatside, CS.idcat, SL.idlang
			FROM
				".$cms_db['cat_side']." CS
				LEFT JOIN ".$cms_db['side_lang']." SL USING(idside)
				LEFT JOIN ".$cms_db['clients_lang']." CL USING(idlang)
			WHERE 
				CL.idclient = '$client'
				AND SL.rewrite_url = '".preg_replace('#^[0-9abcdef]{32}/#', '', $sf_rewrite_raw)."'
				AND SL.rewrite_use_automatic= '0'";
	$db->query($sql);
	if ($db->next_record()) {
		//remember exception langswitch
		if (! is_numeric($_REQUEST['lang'])) { 
			$lang = $db->f('idlang');
		} else { 
			$lang = $_REQUEST['lang'];	
		}
		
		$idcatside = $db->f('idcatside');
	} else {
		//sessionlookup and lang
		$with_short_startlang = ($cfg_client['url_langid_in_defaultlang'] != '1') ? true: false;
		if (preg_match('/^[0-9abcdef]{32}$/', $sf_rw_pieces['0']) ) {
			$sf_rw_session = $sf_rw_pieces['0'];
			$sf_rw_lang = mysql_escape_string($sf_rw_pieces['1']);
			$sf_rw_pieces = array_slice($sf_rw_pieces, 2);
		} else {
			$sf_rw_session = '';
			$sf_rw_lang = mysql_escape_string($sf_rw_pieces['0']);
			$sf_rw_pieces = array_slice($sf_rw_pieces, 1);
		}
		
		//check lang
		$lang_exists_in_url = false;
		foreach ($sf_lang_stack AS $v) {
			//echo "{$v['rewrite_key']} == $sf_rw_lang <br>";
			if($v['rewrite_key'] == $sf_rw_lang ) {
				$lang_exists_in_url = true;
				break;
			}
		}
		if (! $lang_exists_in_url) {
			//echo "IN";
			array_unshift($sf_rw_pieces, $sf_rw_lang);
			$sf_rw_lang = $sf_lang_stack[$startlang]['rewrite_key'];
		}
		
		// print_r($sf_rw_pieces);

		
		//page or cat
		$sf_rw_count = count($sf_rw_pieces);	
		$sf_rw_is_page = ($sf_rw_pieces[$sf_rw_count-1] != '') ? true : false;	
		if (! $sf_rw_is_page) {
			array_pop($sf_rw_pieces);
		}
		
		$sf_rw_pieces = array_reverse($sf_rw_pieces);
		
		
		//figure out lang - not jump in, if user change language
		$sql  = 'SELECT 
					L.idlang
				FROM 
					' . $cms_db['lang'] . ' L 
					LEFT JOIN '. $cms_db['clients_lang'] . ' CL USING(idlang)
				WHERE 
					CL.idclient = ' . $client .'
					AND  L.rewrite_key="'.$sf_rw_lang.'"';
		$db->query($sql);
		$db->next_record();
		$sf_rw_lang_id = $db->f('idlang');

		if (! is_numeric($_REQUEST['lang'])) { 
			$lang = $sf_rw_lang_id;
		} else { 
			$lang = $_REQUEST['lang'];	
		}
		//echo " AA ".$lang;
		
		//get idcatside or idcat
		if ($sf_rw_is_page) {
			//echo "IN". $lang;
			//page
			$sf_rw_suffix = str_replace('.', '\.', $cfg_client['url_rewrite_suffix']);
			
			$v = preg_replace('#'.$sf_rw_suffix.'$#', '', $sf_rw_pieces['0']);
			
			$sql = "SELECT DISTINCT 
						CS.idcatside, CS.idcat
					FROM
						".$cms_db['cat_side']." CS
						LEFT JOIN ".$cms_db['side_lang']." CL USING(idside)
					WHERE 
						CL.idlang= '$sf_rw_lang_id'
						AND CL.rewrite_url = '".$v."'";
			
			$db->query($sql);
			$db->num_rows() ;
			//simple rewrite - allows shadow urls
			//if ($db->num_rows() == 1) {
			//	$db->next_record();
			//	$idcatside = $db->f('idcatside');
			//} else 
			if ($db->num_rows() > 0) {
				while ($db->next_record()) {
					$sf_rw_possibleidcats[$db->f('idcatside')] = $db->f('idcat');
				}
				
				array_shift($sf_rw_pieces);		
				//print_r($sf_rw_pieces);echo '<br>';
				foreach($sf_rw_possibleidcats AS $k=>$v) {
					if(rewriteIdcatIsUniqueToPath($v, $sf_rw_lang_id, $sf_rw_pieces)) {
						//echo "IN";
						$idcatside = $k;
						//$idcat = $v;
						break;	
					}
				} 
			}
		} else {
			//cat
			$v = preg_replace('#/$#', '', mysql_escape_string($v));
			
			$sql = "SELECT DISTINCT
						C.idcat, C.parent 
					FROM
						".$cms_db['cat']." C
						LEFT JOIN ".$cms_db['cat_lang']." CL USING(idcat)
					WHERE 
						CL.idlang = '$sf_rw_lang_id'
						AND rewrite_alias = '".$sf_rw_pieces['0']."'";
			$db->query($sql);
			//if ($db->num_rows() == 1) {
			//	$db->next_record();
			//	$idcat = $db->f('idcat');
			//} else 
			if ($db->num_rows() > 0) {
				$sf_rw_possibleidcats = array();
				while ($db->next_record()) {
					array_push($sf_rw_possibleidcats, $db->f('idcat') );
				}
				
				foreach ($sf_rw_possibleidcats AS $v) {
					if(rewriteIdcatIsUniqueToPath($v, $lang, $sf_rw_pieces)) {
						$idcat = $v;
						break;	
					}
				}
				//echo $idcat;
				//print_r($sf_rw_possibleidcats);exit;
			}
	
		}
	}
} else {
	//var for manipulate session later
	$_sf_rewrite_session = false;
}
// no page found
if (! $idcat && ! $idcatside && $_REQUEST['sf_rewrite'] && $cfg_client['url_rewrite'] == '2') {
	//echo "! $idcat && ! $idcatside";exit;
	// print_r($cfg_client);
	//echo 'Location: '. $cfg_client['htmlpath'].'/error404.php';
	$url = '';
	//echo "XX".$cfg_client['url_rewrite_404'] . $idcatside;
	if ($cfg_client['url_rewrite_404'] != '0' && $cfg_client['url_rewrite_404'] != (string) $idcatside) { 
		$url = str_replace(array('{%http_host}', '{%request_uri}'), array($_SERVER['SERVER_NAME'], base64_encode($_SERVER['REQUEST_URI'])), $cfg_client['url_rewrite_404']);
		if ((int) $url > 0) {
			//$querylang = ((int) $_REQUEST['lang'] > 0) ? 'lang='.$_REQUEST['lang'].'&': '';
			//$url = $cfg_client['htmlpath']. $cfg_client['contentfile'] . '?'. $querylang .'idcatside=' . $url;
			$idcatside = $url;
			$cfg_client['send_header_404'] = true;
		} else {
			//redirect
			sf_header_redirect($url);
		}
	} else {
		header("HTTP/1.1 404 Not Found"); 
		exit;	
	}

}
// END REWRITE


if ($lang < 1) {
	$lang = $startlang;
}

$lang_charset = $sf_lang_stack[$lang]['charset'];
$lang_dir = $cms_path.'tpl/'.$cfg_cms['skin'].'/lang/'.$cfg_cms['backend_lang'] . '/';


if (file_exists ($lang_dir.'lang_general.php')) {
	require_once($lang_dir.'lang_general.php');
} else {
	require_once($cms_path.'tpl/standard/lang/de/lang_general.php');
}

// idcatside suchen
if ($idcatside < 1) {
	if ($idcat > 0) {
		$sql  = 'SELECT idcatside ';
		$sql .= 'FROM ' . $cms_db['cat_side'] . ' ';
		$sql .= 'WHERE idcat    = ' . $idcat;
		$sql .= ' AND  is_start = 1 ';
		$sql .= 'LIMIT 0,1';
	} else {
		$sql  = 'SELECT idcatside ';
		$sql .= 'FROM ' . $cms_db['cat_side'] . ' AS A LEFT JOIN ';
		$sql .=   $cms_db['cat'] . ' AS B USING(idcat) ';
		$sql .= 'WHERE is_start = 1';
		$sql .= ' AND idclient = ' . $client . ' ';
		$sql .= 'ORDER BY parent, B.sortindex LIMIT 0,1';
	}
	$db->query($sql);
	if ($db->next_record()) {
		$idcatside = $db->f('idcatside');
	} else {
		header("HTTP/1.1 404 Not Found"); 
	}
}
$deb -> collect('File:' .__FILE__.' Line:' .__LINE__, 'mem');

// Backend, oder Frontend?
if (isset($sefrengo) && (isset($view))){
	$is_backend  = true;
	$is_frontend = false;
	if(isset($view)){
		include('cms/inc/backend.php');
	}
} else {
	$is_backend  = false;
	$is_frontend = true;
	include ('cms/inc/frontend.php');
}
$deb -> collect('File:' .__FILE__.' Line:' .__LINE__, 'mem');

// Output buffering beenden
$output = ob_get_contents().$deb -> show();

// eventuelle autostarts ausführen:
$a_location = ($is_frontend) ? 'frontend': 'backend';
if (is_array($cfg_cms['autostart'][$a_location])) {
	foreach($cfg_cms['autostart'][$a_location] as $value) {
		include_once $cfg_cms['cms_path'] .'plugins/'. $value;
	}
}
if (is_array($cfg_client['autostart'][$a_location])) {
	foreach($cfg_client['autostart'][$a_location] as $value) {
		include_once $cfg_cms['cms_path'] .'plugins/'. $value;
	}
}

//handle charset - default is UTF-8
if ( $sf_lang_stack[$lang]['charset'] == 'iso-8859-1') {
	$output = utf8_decode($output);
}

//set Content-type header
header('Content-type: text/html; charset='.$sf_lang_stack[$lang]['charset']);

ob_end_clean ();

// Seite komprimieren und ausgeben
if ($cfg_cms['gzip'] == '1') {
	@ob_start('ob_gzhandler');
	eval($cfg_client['manipulate_output']);
	@ob_end_flush();
} else {
	eval($cfg_client['manipulate_output']);
}

$sf_factory->unloadAll();
unset($con_tree, $con_side, $code, $sf_lang_stack);
if ($action != 'logout') page_close();
?>
