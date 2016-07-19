<?PHP
// File: $Id: backend.php 52 2008-07-20 16:16:33Z bjoern $
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
// + Revision: $Revision: 52 $
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

page_open(array('sess' => 'cms_Backend_Session',
                'auth' => 'cms_Backend_Auth'));

include_once($cms_path.'inc/fnc.generate_code.php');
$code="";

// Sprache wechseln
$sess->register('sid_idcatside');
$sid_idcatside = $idcatside;
$perm = new cms_perms($client, $lang);

//idcat is needed for have_perm()- call
//wenn vom frontend aus eine Kategorie angelegt wird, ist noch keine $idcatside vorhanden
if( (int) $idcat < 1 && ! empty($idcatside)){
	$sql = "SELECT idcat FROM ". $cms_db['cat_side'] ." WHERE idcatside='". (int) $idcatside."'";
	$db->query($sql);
	if( $db->next_record() )
		$idcat = $db->f('idcat');
}
// Modus einstellen: editieren/Vorschau/normal
if ($view == 'edit' && ! $perm->have_perm(19, 'side', $idcatside, $idcat)) {
	$cms_side['next_view'] = 'edit';
	$cms_side['view'] = 'preview';
} else {
	$cms_side['next_view'] = $view;
	$cms_side['view'] = $view;
}

// Editmodus einstellen
if ($cms_side['view'] == 'edit') $cms_side['edit'] = 'true';

//Generate cat and page informations
$sf_use_idlang_in_link = ($lang == $startlang) ? false : true;
$sf_sessionstring = ($sess->mode == 'get') ? '&amp;'.$sess->name.'='.$sess->id:'';
$sf_viewurlparm = '&view=' . $cms_side['next_view'];

$deb -> collect('File:' .__FILE__.' Line:' .__LINE__, 'mem');

$SF_catinfos =& sf_factoryGetObjectCache('PAGE', 'Catinfos');
$SF_catinfos->setIdlang($lang);
$SF_catinfos->setCheckBackendperms(true);
$SF_catinfos->setLinkSessionstring($sf_sessionstring);
$SF_catinfos->setLinkUseIdlang($sf_use_idlang_in_link);
$SF_catinfos->setLinkExtraUrlstring($sf_viewurlparm);
$SF_catinfos->generate();
$con_tree =& $SF_catinfos->getCatinfoDataArrayByRef();
$tlo_tree =& $SF_catinfos->getParentDependanceDataArrayByRef();

$deb -> collect('File:' .__FILE__.' Line:' .__LINE__, 'mem');

tree_level_order('0', 'catlist');

$SF_pageinfos =& sf_factoryGetObjectCache('PAGE', 'Pageinfos');
$SF_pageinfos->setIdlang($lang);
$SF_pageinfos->setCheckBackendperms(true);
$SF_pageinfos->setLinkSessionstring($sf_sessionstring);
$SF_pageinfos->setLinkUseIdlang($sf_use_idlang_in_link);
$SF_pageinfos->setLinkExtraUrlstring($sf_viewurlparm);	
$SF_pageinfos->generate();
$con_side =& $SF_pageinfos->getPageinfoDataArrayByRef();


// idcatside prüfen, da der User auch in einer Kategorie sein kann, wo es noch keine seite
// und damit idcatside gibt.

if(! empty($idcatside) ){
	$sql = "SELECT
				meta_title,meta_other,meta_author, meta_description, meta_keywords, meta_robots, meta_redirect,
				metasocial_title,metasocial_image,metasocial_description,metasocial_author, 
				meta_redirect_url, summary, author, created, lastmodified
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
	
	$con_side[$idcatside]['metasocial_title'] = $db->f('metasocial_title');
	$con_side[$idcatside]['metasocial_image'] = $db->f('metasocial_image');
	$con_side[$idcatside]['metasocial_description'] = $db->f('metasocial_description');
	$con_side[$idcatside]['metasocial_author'] = $db->f('metasocial_author');
}


// $idcat und $idside ermitteln
if (!$idcat) $idcat = $con_side[$idcatside]['idcat'];
if (!$idside) $idside = $con_side[$idcatside]['idside'];


// Inhalt erstellen zum editieren der Seite
if(isset($cms_side['view'])) {
	// es existiert noch keine Seite in diesem Ordner
	if(!$idcatside) unset($cms_side['edit']);

	include($cms_path.'inc/fnc.tpl.php');
	include($cms_path.'inc/fnc.type.php');
	include($cms_path.'inc/fnc.con_edit.php');
	include($cms_path.'inc/inc.con_edit.php');
}

// Konfigurationslayer erstellen
$con_side[$idcatside]['config'] = generate_configlayer();
// Seite ausgeben
if ($code) {
	//redirect is active
	if($con_side[$idcatside]['meta_redirect'] == '1' && $con_side[$idcatside]['meta_redirect_url'] != '') {
		header ('HTTP/1.1 302 Moved Temporarily');
		if ( is_numeric($con_side[$idcatside]['meta_redirect_url']) ) {
			sf_header_redirect($cfg_client['htmlpath'] . $con_side[$con_side[$idcatside]['meta_redirect_url']]['link']);
		} else {
			sf_header_redirect($con_side[$idcatside]['meta_redirect_url']);
		}
	}
	// throw out side
	else{
//echo $code;
		eval('?>'.$code);

		$code = ob_get_contents ();
		ob_end_clean ();
		ob_start();
		// Links ersetzen
		// Dateilinks suchen:
		preg_match_all("!cms://(idfile|idfilethumb)=(\d+)!", $code, $internlinks);
		$sql_links = implode(',', $internlinks['2']);
		if ($sql_links != '') {
			$sql = "SELECT
						A.idupl id, A.filename filename,B.dirname dirname
					FROM
						".$cms_db['upl']." as A
						LEFT JOIN ". $cms_db['directory'] ." as B USING(iddirectory)
					WHERE
						A.idclient=$client
						AND A.idupl IN ($sql_links)";
			$db->query($sql, 1, 'frontend_content');
			while($db->next_record()){
				$cms_file[$db->f('id')] = $cfg_client['upl_htmlpath'].$db->f('dirname').$db->f('filename');
			}
		}
		//Links ersetzen
		$in = array("'cms://idfile=(\d+)'e",
			    "!cms://idcat=(\d+)!e",
			    "!cms://idcatside=(\d+)!e");
		$out = array('\$cms_file[\\1]',
			     '\$con_tree[\\1][\'link\']',
			     '\$con_side[\\1][\'link\']');
		$code = preg_replace($in, $out, $code);


		//turn around inline- editing temp_links
		$in = array("!cms://temp_idfile=(\d+)!",
			    "!cms://temp_idcat=(\d+)!",
			    "!cms://temp_idcatside=(\d+)!");
		$out = array("cms://idfile=\\1",
			         "cms://idcat=\\1",
			         "cms://idcatside=\\1");
		$code = preg_replace($in, $out, $code);
		echo $code;

	}
}
else{
	if($cfg_client['errorpage'] != '0' && $cfg_client['errorpage'] != $idcatside){
		sf_header_redirect($con_side[ $cfg_client['errorpage'] ]['link']);
	} else{
		header ('HTTP/1.1 404 Not Found');
		exit;
	}

}

unset($code);
?>
