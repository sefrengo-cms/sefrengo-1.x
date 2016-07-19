<?PHP
// File: $Id: frontend.php 64 2008-11-19 19:00:33Z bjoern $
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
// + Autor: $Author: bjoern $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 64 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}


if(strlen($cfg_client['session_disabled_useragents'])>3) {
	$preg_spiders = preg_replace("#(\r\n)|(\r)#m", "\n", trim($cfg_client['session_disabled_useragents']));
	$preg_spiders = preg_replace('#\n#m', '|', $preg_spiders);
	if(preg_match('#('.$preg_spiders.')#i', $_SERVER['HTTP_USER_AGENT']) ) {
	  $cfg_client['session_enabled'] = '0';
	}
}
if(strlen($cfg_client['session_disabled_ips'])>5) {
	$preg_ips = preg_replace("#(\r\n)|(\r)#m", "\n", trim($cfg_client['session_disabled_ips']));
	$preg_ips = preg_replace('#\n#m', '|', $preg_ips);
	if(preg_match('#('.$preg_ips.')#i', $_SERVER['REMOTE_ADDR']) ) {
	  $cfg_client['session_enabled'] = '0';
	}
}
page_open(array('sess' => 'cms_Frontend_Session',
                'auth' => 'cms_Frontend_Auth'));

if ($_sf_rewrite_session) {
	if ($sess->mode == 'get') {
		$sess->mode = 'getrewrite';
	}
}


// idcatside für Sprachwechsel in Session schreiben
$sess->register('sid_idcatside');
$sid_idcatside = $idcatside;
$code='';

// Rechte initialisieren
$perm = new cms_perms($client, $lang);

//Generate cat and page informations
$sf_use_idlang_in_link = ($lang == $startlang && $cfg_client['url_langid_in_defaultlang'] == '0') ? false : true;
$sf_sessionstring = ($sess->mode == 'get') ? '&amp;'.$sess->name.'='.$sess->id:'';

$deb -> collect('File:' .__FILE__.' Line:' .__LINE__, 'mem');

$SF_catinfos =& sf_factoryGetObjectCache('PAGE', 'Catinfos');
$SF_catinfos->setIdlang($lang);
$SF_catinfos->setCheckFrontendperms(true);
$SF_catinfos->setLinkSessionstring($sf_sessionstring);
$SF_catinfos->setLinkUseIdlang($sf_use_idlang_in_link);
$SF_catinfos->setLinkExtraUrlstring('');
$SF_catinfos->generate();
$con_tree =& $SF_catinfos->getCatinfoDataArrayByRef();
 #var_dump($con_tree);
$tlo_tree =& $SF_catinfos->getParentDependanceDataArrayByRef();

$deb -> collect('File:' .__FILE__.' Line:' .__LINE__, 'mem');

tree_level_order('0', 'catlist');

if(is_array($con_tree)) {
	$SF_pageinfos =& sf_factoryGetObjectCache('PAGE', 'Pageinfos');
	$SF_pageinfos->setIdlang($lang);
	$SF_pageinfos->setCheckFrontendperms(true);
	$SF_pageinfos->setLinkSessionstring($sf_sessionstring);
	$SF_pageinfos->setLinkUseIdlang($sf_use_idlang_in_link);
	$SF_pageinfos->setLinkExtraUrlstring('');	
	$SF_pageinfos->generate();
	$con_side =& $SF_pageinfos->getPageinfoDataArrayByRef();
}

//init rewrite for mod_rewrite mode = 1
$cfg_client['url_rewrite_in'] = array("'(?<!/)".$cfg_client['contentfile']."\?idcat=([1-9][0-9]*)'",
			             "'(?<!/)".$cfg_client['contentfile']."\?idcatside=([1-9][0-9]*)'",
				     "'(?<!/)".$cfg_client['contentfile']."\?lang=([1-9][0-9]?)(&|&amp;)idcat=([1-9][0-9]*)'",
				     "'(?<!/)".$cfg_client['contentfile']."\?lang=([1-9][0-9]?)(&|&amp;)idcatside=([1-9][0-9]*)'");
$cfg_client['url_rewrite_out'] = array("cat\\1.html",
				      "page\\1.html",
				      "cat\\3-\\1.html",
				      "page\\3-\\1.html");


// $idcat und $idside ermitteln
if (empty($idcat))  $idcat  = $con_side[$idcatside]['idcat'];
if (empty($idside)) $idside = $con_side[$idcatside]['idside'];
// Ausgabe beenden, wenn Kategorie oder Seite nicht online
if($con_side[$idcatside]['online'] != 1 || $con_tree[$idcat]['visible'] != 1){
	//send correct 404 header
	if ($cfg_client['url_rewrite'] == '2') {
		$url = str_replace(array('{%http_host}', '{%request_uri}'), array($_SERVER['SERVER_NAME'], base64_encode($_SERVER['REQUEST_URI'])), $cfg_client['url_rewrite_404']);
		if ((int) $url > 0) {
			$idcatside = (int) $url;
			$cfg_client['send_header_404'] = true;
			//idside und idcatside ummappen
			$idcat  = $con_side[$idcatside]['idcat'];
			$idside = $con_side[$idcatside]['idside'];
		} else {
			sf_header_redirect($url);
		}
	} else if ($cfg_client['errorpage'] != '0' && $cfg_client['errorpage'] != $idcatside) {
		$cfg_client['errorpage'] = $con_side[$cfg_client['errorpage']]['link'];
		if ($cfg_client['url_rewrite'] == '1') {
			$cfg_client['errorpage'] = preg_replace($cfg_client['url_rewrite_in'], $cfg_client['url_rewrite_out'], $cfg_client['errorpage']);
		}

		sf_header_redirect($cfg_client['errorpage']);
	} else {
		header("HTTP/1.1 404 Not Found"); 
		exit;
	}
//404 header ausgeben, wenn Seite Fehlerseite
} else if ((int) $cfg_client['url_rewrite_404'] == $idcatside) {
	$cfg_client['send_header_404'] = true;
}



// get advanced sideinfos for this side
// idcatside prüfen, da der User auch in einer Kategorie sein kann, wo es noch keine seite
// und damit idcatside gibt.
// es gibt keine idsidelang, wenn der user nicht das recht hat, die seite zu sehen
if(! empty($idcatside) && ! empty($con_side[$idcatside]['idsidelang'])){
	$sql = "SELECT
				meta_title,meta_other,meta_author, meta_description, meta_keywords, meta_robots, meta_redirect,
				metasocial_title,metasocial_image,metasocial_description,metasocial_author,
				meta_redirect_url, summary, author, created, lastmodified,
				IF ( ((online & 0x04) = 0x01) ,'1' ,'0') AS protected
			FROM
				".$cms_db['side_lang']."
			WHERE
				idsidelang= ". $con_side[$idcatside]['idsidelang'];
	$db->query($sql);
	$db->next_record();
 	$con_side[$idcatside]['meta_title'] = $db->f('meta_title');
  $con_side[$idcatside]['meta_other'] = $db->f('meta_other');
  $con_side[$idcatside]['meta_author'] = $db->f('meta_author');
	$con_side[$idcatside]['meta_description'] = $db->f('meta_description');
	$con_side[$idcatside]['meta_keywords'] = $db->f('meta_keywords');
	$con_side[$idcatside]['meta_robots'] = $db->f('meta_robots');
	$con_side[$idcatside]['meta_redirect'] = $db->f('meta_redirect');
	$con_side[$idcatside]['meta_redirect_url'] = $db->f('meta_redirect_url');
	$con_side[$idcatside]['summary'] = $db->f('summary');
	$con_side[$idcatside]['author'] = $db->f('author');
	$con_side[$idcatside]['created'] = $db->f('created');
	$con_side[$idcatside]['lastmodified'] = $db->f('lastmodified');
	$con_side[$idcatside]['protected'] = $db->f('protected');
	
	$con_side[$idcatside]['metasocial_title'] = $db->f('metasocial_title');
	$con_side[$idcatside]['metasocial_image'] = $db->f('metasocial_image');
	$con_side[$idcatside]['metasocial_description'] = $db->f('metasocial_description');
	$con_side[$idcatside]['metasocial_author'] = $db->f('metasocial_author');
}



if ($cfg_client['url_rewrite'] == '2') {
	include_once($cfg_cms['cms_path'].'inc/fnc.mod_rewrite.php');
	rewriteGenerateMapping();
}


// Inhalt aus der Datenbank suchen
if ($auth->auth['uid'] != 'nobody' |
		($con_tree[$idcat]['visible'] == '1' && !empty($con_side[$idcatside]['online']) && $con_side[$idcatside]['online'] != '0') ) {

	if ( !empty($con_tree[$con_side[$idcatside]['idcat']]['idcat']) ) {
		$sql  = 'SELECT code, changed ';
		$sql .= 'FROM ' . $cms_db['code'] . ' ';
		$sql .= 'WHERE idcatside = ' . $idcatside;
		$sql .= ' AND  idlang    = ' . $lang;
		$db->query($sql);
		$db->next_record();
		$code         = $db->f('code');
		$code_changed = $db->f('changed');
		if ($code_changed == '' || $code_changed == '1') {
			// Seite generieren weil keine Daten gefunden oder Daten verändert
			include( $cms_path . 'inc/inc.generate_code.php' );
            // Delete Content Cache
            sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'content'));
			//schauen, ob sich cmstags im dynamischen php (<CMSPHP>) befinden
			//wenn ja, nicht cachen
			$dynamic = strpos ($code, 'echo type_output_');
			if(is_int($dynamic)){
				$cfg_client['cache'] = '0';
			}

			// Seite in die 'code'-Tabelle schreiben
			if ($cfg_client['cache'] == '1') {
				$sql  = 'SELECT * ';
				$sql .= 'FROM ' . $cms_db['code'] . ' ';
				$sql .= 'WHERE idcatside = ' . $idcatside;
				$sql .= ' AND  idlang    = ' . $lang;
				$db->query($sql);
				if ($db->next_record()) {
					// Update vorhandener Seite
					$sql  = 'UPDATE ' . $cms_db['code'] . ' ';
					$sql .= 'SET';
					$sql .= " code = '" . cms_addslashes($code) . "',";
					$sql .= ' changed = 0 ';
					$sql .= 'WHERE idcatside = ' . $idcatside;
					$sql .= ' AND  idlang    = ' . $lang;
				} else {
					// Insert neue Seite
					$sql  = 'INSERT INTO ' . $cms_db['code'];
					$sql .= ' (idlang, idcatside, code, changed) ';
					$sql .= 'VALUES';
					$sql .= ' (' . $lang . ', ' . $idcatside . ", '" . cms_addslashes($code) . "', 0)";
				}
				$db->query($sql);
			}
		}
	}
}

$deb -> collect('File:' .__FILE__.' Line:' .__LINE__, 'mem');
// Seite ausgeben
if ($code) {
	// Seite weiterleiten?
	if ($con_side[$idcatside]['meta_redirect'] == '1' && $con_side[$idcatside]['meta_redirect_url'] != '') {
		header ('HTTP/1.1 302 Moved Temporarily');
		$redirect = '';
        if (is_numeric($con_side[$idcatside]['meta_redirect_url'])) {
            $con_side[$idcatside]['meta_redirect_url'] = $con_side[$con_side[$idcatside]['meta_redirect_url']]['link'];
            if ($cfg_client['url_rewrite'] == '1') {
                $con_side[$idcatside]['meta_redirect_url'] = preg_replace($cfg_client['url_rewrite_in'], $cfg_client['url_rewrite_out'], $con_side[$idcatside]['meta_redirect_url']);
            } else if ($cfg_client['url_rewrite'] == '2') {
                $con_side[$idcatside]['meta_redirect_url'] = preg_replace_callback($cfg_client['url_rewrite_in'], rewriteHandle, $con_side[$idcatside]['meta_redirect_url']);
            }
            $redirect = $cfg_client['htmlpath'] . $con_side[$idcatside]['meta_redirect_url'];
            //$redirect = 'http://'. $_SERVER['HTTP_HOST']. '/'. $con_side[$idcatside]['meta_redirect_url'];
        } else {
            $redirect = $con_side[$idcatside]['meta_redirect_url'];
        }
		sf_header_redirect($redirect);
	} else {
		eval('?>'.$code);
        $deb -> collect('File:' .__FILE__.' Line:' .__LINE__, 'mem');

		$code = ob_get_contents ();
		ob_end_clean ();
		ob_start();
		if ($cfg_client['send_header_404']) {
			header("HTTP/1.1 404 Not Found"); 
			header ('Status: 404 Not Found');
		}
		
		// Sefrengolinks ersetzen
		// Anonymous functions become available with PHP 5.3.0 and preg_replace with /e modifier is deprecated since PHP 5.5.0
		// @see: http://www.php.net/manual/en/reference.pcre.pattern.modifiers.php#reference.pcre.pattern.modifiers.eval
		if (version_compare(PHP_VERSION, '5.3.0') >= 0)
		{
			function match_tree(array $match) {
				global $con_tree;
				return $con_tree[$match[1]]['link'];
			}
			
			$code = preg_replace_callback('!cms://idcat=(\d+)!', 'match_tree', $code);
			
			function match_side(array $match) {
				global $con_side;
				return $con_side[$match[1]]['link'];
			}
			
			$code = preg_replace_callback('!cms://idcatside=(\d+)!', 'match_side', $code);
				
			if ($cfg_client['url_rewrite'] == '2')
			{
				function match_amp(array $match) {
					return $match[1].str_replace('&', '&amp;', $_SERVER['REQUEST_URI']).'#'.$match[2].$match[3];
				}
				
				$code = preg_replace_callback('!(<a[\s]+[^>]*?href[\s]?=[\s\"\']+)#(.*?)([\"\'])!', 'match_amp', $code);
			}
		}
		else
		{
			$in = array("!cms://idcat=(\d+)!e",
					"!cms://idcatside=(\d+)!e");
			$out = array('\$con_tree[\\1][\'link\']',
					 '\$con_side[\\1][\'link\']');
			if ($cfg_client['url_rewrite'] == '2') {
				array_push($in, "!(<a[\s]+[^>]*?href[\s]?=[\s\"\']+)#(.*?)([\"\'])!i");
				array_push($out, '\\1'.str_replace('&', '&amp;', $_SERVER['REQUEST_URI']).'#\\2\\3');
			}
			$code = preg_replace($in, $out, $code);
		}

	
		if ($cfg_client['url_rewrite'] == '1') {
			$code = preg_replace($cfg_client['url_rewrite_in'], $cfg_client['url_rewrite_out'], $code);
		} else if ($cfg_client['url_rewrite'] == '2') {
			$code = preg_replace_callback($cfg_client['url_rewrite_in'], rewriteHandle, $code);
		}
		echo $code;
	}
} else {
	if ($cfg_client['errorpage'] != '0' && $cfg_client['errorpage'] != $idcatside) {
		$cfg_client['errorpage'] = $con_side[$cfg_client['errorpage']]['link'];
		if ($cfg_client['url_rewrite'] == '1') {
			$cfg_client['errorpage'] = preg_replace($cfg_client['url_rewrite_in'], $cfg_client['url_rewrite_out'], $cfg_client['errorpage']);
		} else if ($cfg_client['url_rewrite'] == '2') {
			$cfg_client['errorpage'] = preg_replace_callback($cfg_client['url_rewrite_in'], rewriteHandle, $cfg_client['errorpage']);
		}
		header("HTTP/1.1 404 Not Found"); 
		sf_header_redirect($cfg_client['errorpage']);
	} else{
		header("HTTP/1.1 404 Not Found"); 
		exit;
	}
}
?>
