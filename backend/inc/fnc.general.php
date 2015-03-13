<?PHP
// File: $Id: fnc.general.php 52 2008-07-20 16:16:33Z bjoern $
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

function fire_event($event, $args) {
	global $cms_event, $val_ct, $db, $cms_db, $client, $lang, $perm, $sess, $cfg_client, $cfg_cms;

	if (!is_object($cms_event)) {
		include_once $cfg_cms['cms_path']. 'inc/class.cms_event.php';
		$cms_events = new cms_event($val_ct);
	}
	$cms_events -> fire($event, $args);
	
	return $cms_events->getReturnval();
}

function html_entity_decode_compatible($string) {
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	return strtr ($string, $trans_tbl);
}

function make_string_dump($tmp = '') {
	$tmp = str_replace('\\', '\\\\', $tmp);
	$tmp = str_replace('\'', '\\\'', $tmp);
	$tmp = str_replace(array('\x00', '\x0a', '\x0d', '\x1a'), array('\0', '\n', '\r', '\Z'), $tmp);
	return $tmp;
}

function extract_cms_tags($in, $sort='') {
	//alle CMS Tags extrahieren
	//Hinterher befindet sich in
	//$matches[0][x] -> der gesamte Tag
	//$matches[3][x] -> alle Attribute
	//$matches[4][x] -> Content zwischen <cms></cms>
	//todo: 2remove
	preg_match_all ('/<(dedi|cms):(mod|lay) ([^>]*)\/?>([^<\/]*<\/$1>)?/i',$in, $matches);
	$match_count = count($matches[0]);

	//Attribute filtern
	if($match_count) {
		for ($i=0; $i< $match_count; $i++) {
			$attributes = $matches[3][$i];
			$preg = '/(([A-Za-z_:]|[^\\x00-\\x7F])([A-Za-z0-9_:.-]|[^\\x00-\\x7F])*)'."([ \\n\\t\\r]+)?(=([ \\n\\t\\r]+)?(\"[^\"]*\"|'[^']*'|[^ \\n\\t\\r]*))?/";
			if (preg_match_all($preg, $attributes, $regs)) {
				$valCounter = 0;
				for ($counter=0; $counter<count($regs[1]); $counter++) {
					$name = $regs[1][$counter];
					$check = $regs[0][$counter];
					$value = $regs[7][$valCounter];
					if (trim($name) == trim($check)) $arrAttr[] = strtoupper(trim($name));
					else {
						if (substr($value, 0, 1) == '"' || substr($value, 0, 1) == "'") $value = substr($value, 1, -1);
						$arrAttr[strtolower(trim($name))] = trim($value);
						$valCounter++;
					}
				}

				// Alle Attribute für returnwert aufbereiten
//				$arrAttr['id'] = sprintf("%01d",$arrAttr['id']);
				if ($sort == 'type') {
					//$out[type][id][attributekey] = attributevalue
					$out[$arrAttr['type']][$arrAttr['id']] = $arrAttr;
					$out[$arrAttr['type']][$arrAttr['id']]['in_tag'] = $matches[4][$i];
				} else {
					//$out[$i][attributekey] = attributevalue
					$out[$i] = $arrAttr;
					$out[$i]['full_tag'] = $matches[0][$i];
					$out[$i]['in_tag'] = $matches[4][$i];
				}
				unset($arrAttr);
			}
		}
		return $out;
	} else return false;
}


function check_cache_is_expired($timestamp) {
	global $db, $cms_db, $idcatside;

	// Wenn timestamp <= aktuelle Zeit, Cache ist abgelaufen
	if ($timestamp <= time()) {
		$sql = "UPDATE ".$cms_db['code']." SET changed='1' WHERE idcatside = $idcatside";
		$db->query($sql);
	    // Delete Content Cache
        $db->delete_cache('frontend_content');
    }
}


function change_code_status($list, $status, $type='idcode') {
	global $db, $cms_db, $lang, $cfg_client;

	if (!is_array($list)) $list = explode(',',$list);
	if ($list['0'] != '') {
		switch($type) {
			case 'idcode':
				if ($cfg_client['publish'] == '1') $status = '2';
				$sql = "UPDATE $cms_db[code] SET changed='$status' WHERE idcode IN(".implode(',', $list).")";
				break;

			case 'idcatside':
				if ($cfg_client['publish'] == '1') $status = '2';
				$sql = "UPDATE $cms_db[code] SET changed='$status' WHERE idlang='$lang' AND idcatside IN(".implode(',', $list).")";
				break;

			case 'publish':
				$sql = "UPDATE $cms_db[code] SET changed='$status' WHERE idlang='$lang' AND changed='2' AND idcatside IN(".implode(',', $list).")";
				break;
		}
		$db->query($sql);
        // Delete Content Cache
        $db->delete_cache('frontend_content');	
	}
}

function get_languages_by_client($client) {
	global $db, $cms_db;

	$sql = "SELECT idlang FROM $cms_db[clients_lang] WHERE idclient='$client'";
	$db->query($sql);
	while($db->next_record()) $list[]=$db->f('idlang');
	return $list;
}

function browse_layout_for_containers($idlay) {
        global $db, $cms_db;

        $sql = "SELECT code FROM $cms_db[lay] WHERE idlay='$idlay'";
        $db->query($sql);
        $db->next_record();
        $list_tmp = extract_cms_tags($db->f('code'));
        if (is_array($list_tmp)) {
                // Container sortieren
                foreach($list_tmp as $container) {
                        if ($container['id']) {
                                $list['id'][] = $container['id'];
                                if ($container['title']) $list[$container['id']]['title'] = $container['title'];
                        }
                }
        }
        return $list;
}

function browse_template_for_module($idtpl, $idtplconf) {
	global $db, $cms_db;

	if ($idtpl == '0') {
		$sql = "SELECT idtpl FROM $cms_db[tpl_conf] WHERE idtplconf='$idtplconf'";
		$db->query($sql);
		$db->next_record();
		$idtpl = $db->f('idtpl');
	}
	$sql = "SELECT A.idcontainer, A.container, A.idmod, B.name, B.input, B.output, C.config, C.view, C.edit FROM $cms_db[container] A LEFT JOIN $cms_db[mod] B USING(idmod) LEFT JOIN $cms_db[container_conf] C ON A.idcontainer=C.idcontainer WHERE A.idtpl='$idtpl' AND C.idtplconf='$idtplconf'";
	$db->query($sql);
	while ($db->next_record()) {
		$cont_loop = $db->f('container');
		$list['id'][] = $cont_loop;
		$list[$cont_loop] = array('idcontainer'=>$db->f('idcontainer'),
								  'idmod'      =>$db->f('idmod'),
								  'modname'    =>$db->f('name'),
								  'input'      =>$db->f('input'),
								  'output'     =>$db->f('output'),
								  'config'     =>$db->f('config'),
								  'view'       =>$db->f('view'),
								  'edit'       =>$db->f('edit'));
	}
	return $list;
}

function get_idtplconf_by_using_type($list, $type) {
	global $db, $cms_db, $cfg_client;

	if (!is_array($list) && $list != '') $list = explode(',',$list);
	if (is_array($list)) {
		switch($type) {
			case 'lay':
				$sql = "SELECT idtplconf FROM $cms_db[tpl] A, $cms_db[tpl_conf] B WHERE (A.idlay=''";
				foreach ($list as $value) $sql .=" OR A.idlay='$value'";
				$sql .= ") AND A.idtpl=B.idtpl";
				break;
			case 'mod':
				$sql = "SELECT DISTINCT B.idtplconf FROM $cms_db[tpl] A LEFT JOIN $cms_db[tpl_conf] B USING(idtpl) LEFT JOIN $cms_db[container_conf] C USING(idtplconf) LEFT JOIN $cms_db[container] D USING(idcontainer) WHERE (D.idmod=''";
				foreach ($list as $value) $sql .=" OR D.idmod='$value'";
				$sql .= ")";
				break;
			case 'tpl':
				$sql = "SELECT idtplconf FROM $cms_db[tpl_conf] WHERE (idtpl=''";
				foreach ($list as $value) $sql .=" OR idtpl='$value'";
				$sql .= ")";
				break;
                 }
		$db->query($sql);
		while ($db->next_record()) $tmp[] = $db->f('idtplconf');
		return $tmp;
	}
}

function get_idcode_by_idtplconf($list) {
	global $db, $cms_db, $cfg_client;

	if (!is_array($list) && $list != '') $list = explode(',',$list);
	if (is_array($list)) {
		if ($cfg_client['publish'] == '1') $changed = "(C.changed='0' OR C.changed='1')";
		else $changed = "(C.changed='0' OR C.changed='2')";
		foreach ($list as $value) {
			$sql = "SELECT 
						idcode 
					FROM 
						$cms_db[side_lang] A 
						LEFT JOIN $cms_db[cat_side] B USING(idside) 
						LEFT JOIN $cms_db[code] C USING(idcatside) 
					WHERE 
						A.idtplconf='$value' AND $changed";
			$db->query($sql);
			while ($db->next_record()) $tmp[] = $db->f('idcode');

			// das Template gehört einem Ordner
			if (!$db->affected_rows()) {
				$sql = "SELECT 
							idcode 
						FROM 
							$cms_db[cat_lang] A 
							LEFT JOIN $cms_db[cat_side] B USING(idcat) 
							LEFT JOIN $cms_db[side_lang] C USING(idside) 
							LEFT JOIN $cms_db[code] D ON C.idlang = D.idlang 
						WHERE 
							A.idtplconf='$value' 
							AND A.idlang=C.idlang 
							AND C.idtplconf='0' 
							AND B.idcatside=D.idcatside";
				$db->query($sql);
				while ($db->next_record()) $tmp[] = $db->f('idcode');
			}
		}
	}
	return $tmp;
}

function get_idsidelang_by_idtplconf($list) {
	global $db, $cms_db, $cfg_client;

	if (!is_array($list) && $list != '') $list = explode(',',$list);
	if (is_array($list)) {
		foreach ($list as $value) {
			$sql = "SELECT idsidelang FROM $cms_db[side_lang] WHERE idtplconf='$value'";
			$db->query($sql);
			while ($db->next_record()) $tmp[] = $db->f('idsidelang');

			// das Template gehört einem Ordner
			if (!$db->affected_rows()) {
				$sql = "SELECT idsidelang FROM $cms_db[cat_lang] A LEFT JOIN $cms_db[cat_side] B USING(idcat) LEFT JOIN $cms_db[side_lang] C USING(idside) WHERE A.idtplconf='$value' AND A.idlang=C.idlang AND C.idtplconf='0'";
				$db->query($sql);
				while ($db->next_record()) $tmp[] = $db->f('idsidelang');
			}
		}
	}
	return $tmp;
}

function set_magic_quotes_gpc(&$code) {
	if (get_magic_quotes_gpc() == 0) $code = addslashes($code);
	if (ini_get(magic_quotes_sybase) != 0) {
		$code = str_replace("''", "'", $code);
		$code = str_replace("\\", "\\\\", $code);
		$code = str_replace("'", "\'", $code);
		$code = str_replace('"', '\"', $code);
	}
}

//todo: 2remove
function dedi_addslashes($code) {
	return cms_addslashes($code);
}

function cms_addslashes($code) {
	$code = addslashes($code);
	if (ini_get(magic_quotes_sybase) != 0) {
		$code = str_replace("\\", "\\\\", $code);
		$code = str_replace('"', '\"', $code);
         	$code = str_replace("''", "\'", $code);
	}
	return $code;
}

//todo: 2remove
function dedi_stripslashes($code) {
	return cms_stripslashes($code);
}

function cms_stripslashes($code) {
	if (ini_get(magic_quotes_sybase) != 0) {
		$code = str_replace("\\\\", "\\", $code);
         	$code = str_replace("\'", "'", $code);
		$code = str_replace('\"', '"', $code);
         } else $code = stripslashes($code);
	return $code;
}

function remove_magic_quotes_gpc(&$code) {
	if (get_magic_quotes_gpc() != 0) {
		if (ini_get(magic_quotes_sybase) != 0) {
			$code = str_replace("\\", "\\\\", $code);
			$code = str_replace('"', '\"', $code);
	         	$code = str_replace("''", "\'", $code);
		}
         	$code = cms_stripslashes($code);
         }
}

function make_array_to_urlstring($in) {
	if (!is_array($in)) return;
	
	
    ksort($in);
    $tmp[] = '';
    // $tmp[] = 0;
    $in = array_diff($in, $tmp);
	foreach($in as $key => $value) {
		if (is_array($value)) {
			// leere Arrayelemente löschen und in String wandeln
			$value = array_diff($value, $tmp);
			
			if (is_array($value)) {
				$value = implode(',',$value);
			}
        }
		set_magic_quotes_gpc($value);
		$value = urlencode($value);
		$out .= $key.'='.$value.'&';
	}
	$out = preg_replace('/&$/', '', $out);
	return $out;
}

function tree_level_order($node_id, $array, $expanded = 'false', $level = '0') {
	global $tlo_tree, ${$array}, ${$array.'_level'}, $perm, $con_tree, $perm;

	$arr = (is_array($tlo_tree[$node_id])) ? $tlo_tree[$node_id]: array();

	while(list($i) = each($arr))
	{
		$id_loop = $tlo_tree[$node_id][$i];

		//Warum wird hier der perm gecheckt?
		//if ($perm->is_admin() || $perm->test_perm('cat', $id_loop)) {
			// $perm->test_perm('cat', $id_loop, 7, $con_tree[$id_loop]['visible'])

		${$array}[] = $id_loop;
		//}

		${$array.'_level'}[$id_loop] = $level;

		if ($tlo_tree['expanded'] && $expanded == 'false') {

			if ($tlo_tree['expanded'][$id_loop] == $id_loop || !$perm -> have_perm('0', 'cat', $id_loop)) {
				tree_level_order($id_loop, $array, $expanded, ($level + 1));
			}
		}
		else {
			tree_level_order($id_loop, $array, $expanded, ($level + 1));
		}
	}
	return;
}

// Wird vom cms:tag Link benutzt bei Auswahl eines Links
// im BearbeitenDialog
function tree_level_order_light($node_id = '0', $level = '0')
{
	global $tlo_tree, $catlist, $catlist_level;

	for ($i=1; !empty($tlo_tree[$node_id][$i]); $i++)
	{
		$catlist[] = $tlo_tree[$node_id][$i];
		$catlist_level[$tlo_tree[$node_id][$i]] = $level;
		tree_level_order_light($tlo_tree[$node_id][$i], ($level + 1));
	}
	return;
}


function make_image ($image = 'space.gif', $description = '', $width = '', $height = '', $popup = '', $attr = '') {
	global $cfg_cms, $cms_lang;

	$image_width = (is_numeric($width)) ? ' width="'. $width .'" ': '';
	$image_height = (is_numeric($height)) ? ' height="'. $height .'" ': '';
	$image_popup = ($popup != '') ? ' onmouseover="sf_overlib('.$popup.');" onmouseout="sf_nd();" ' : '';
	$image_final = '<img src="tpl/'.$cfg_cms['skin'].'/img/'.$image.'" '. $image_width . $image_height . $image_popup .' alt="" '.$attr.' />';
	return $image_final;
}

function make_image_link ($url = '#', $image = 'space.gif', $description = '', $width = '', $height = '', $target = '', $popup = '', $anchor ='', $class='') {
	global $sess, $cfg_cms, $cms_lang;

	return sprintf("\n<a style=\"text-decoration:none;\" href=\"".$sess->url($url). $anchor ."\" %s %s %s>\n<img src=\"tpl/".$cfg_cms['skin']."/img/".$image."\"%s%s %s />\n</a>\n%s",
				($target != '') ? "target=\"$target\"" : '', 
				($class != '') ? "class=\"$class\"" : '', 
				($description != '') ? "title=\"$description\"" : '', 
				($width != '') ? " width=\"$width\"" : '', 
				($height != '') ? " height=\"$height\"" : '', 
				($popup == '') ? " alt=\"$description\"" : 'alt=""',
				($popup != '') ? $popup : '');
}

function make_image_link2 ($url = '#', $image = 'space.gif', $description = '', $width = '', $height = '', $target = '', $popup = '', $popupheader = '', $class = '', $hash = '', $name = '', $linktext = '', $imgclass = '' ) {
	global $sess, $cfg_cms, $cms_lang;

	$link = sprintf("\n<a style=\"text-decoration:none;\" %s%shref=\"".$sess->url($url)."%s\" onmouseover=\"%sreturn true;\" onmouseout=\"%sreturn true;\"%s>\n{content}\n</a>\n", 
						($name != '') ? 'name="'.$name.'" ' : '', 
						($class != '') ? 'class="'.$class.'" ' : '', 
						($hash != '') ? '#'.$hash: '', 
						($popup != '') ? 'overlib('.$popup.');' : '', 
						($popup != '') ? 'nd();' : '', 
						($target != '') ? " target=\"$target\"" : '');
	
	$image = sprintf("\n<img src=\"tpl/".$cfg_cms['skin']."/img/".$image."\"%s%s %s%s%s />", 
						($width != '') ? " width=\"$width\"" : '', 
						($height != '') ? " height=\"$height\"" : '', 
						($popup == '') ? " alt=\"$description\"" : '', 
						($popup == '') ? " title=\"$description\"" : '',
						($imgclass != '') ? ' class="'.$imgclass.'" ' : '');
	
	$out = ' '. str_replace('{content}', $image, $link);
	if ($linktext != '') {
		$out .= ' '. str_replace('{content}', $linktext, $link);
	}
	
//	$out = sprintf("<a %s%shref=\"".$sess->url($url)."%s\" onmouseover=\"%sreturn true;\" onmouseout=\"%sreturn true;\"%s>\n<img src=\"tpl/".$cfg_cms['skin']."/img/".$image."\"%s%s %s%s%s />$linktext</a>", 
//						($name != '') ? 'name="'.$name.'" ' : '', 
//						($class != '') ? 'class="'.$class.'" ' : '', 
//						($hash != '') ? '#'.$hash: '', 
//						($popup != '') ? 'overlib('.$popup.');' : '', 
//						($popup != '') ? 'nd();' : '', 
//						($target != '') ? " target=\"$target\"" : '', 
//						($width != '') ? " width=\"$width\"" : '', 
//						($height != '') ? " height=\"$height\"" : '', 
//						($popup == '') ? " alt=\"$description\"" : '', 
//						($popup == '') ? " title=\"$description\"" : '', ($imgclass != '') ? ' class="'.$imgclass.'" ' : '');
	
	return $out;
}

function make_image_link3 ($url = '#', $image = 'space.gif', $image1 = 'space.gif', $description = '', $description1 = '', $class = '', $hash = '', $name = '' ) {
	global $sess, $cfg_cms, $cms_lang;

	return sprintf("\n<a style=\"text-decoration:none;\" %s%shref=\"".$sess->url($url)."%s\">\n<img src=\"tpl/".$cfg_cms['skin']."/img/".$image."\"  alt=\"$description\" %s />\n</a>\n<a style=\"text-decoration:none;\" href=\"javascript:history.back()\">\n<img src=\"tpl/".$cfg_cms['skin']."/img/".$image1."\" alt=\"$description1\" %s />\n</a>\n", ($name != '') ? 'name="'.$name.'" ' : '', ($class != '') ? 'class="'.$class.'" ' : '', ($hash != '') ? '#'.$hash: '', ($class != '') ? 'class="'.$class.'" ' : '', ($class != '') ? 'class="'.$class.'" ' : '');
}

function make_image_link4 ($image = 'space.gif', $image1 = 'space.gif', $description = '', $description1 = '', $class = '', $class1 = '', $backurl = '#', $width = '16', $height = '16' ) {
	global $sess, $cfg_cms, $cms_lang;

	return sprintf("\n<input type=\"image\" %s  class=\"w16\" src=\"tpl/".$cfg_cms['skin']."/img/".$image."\"  alt=\"$description\" width=\"$width\" height=\"$height\" />\n<a style=\"text-decoration:none;\" href=\"".$sess->url($backurl)."\">\n<img %s src=\"tpl/".$cfg_cms['skin']."/img/".$image1."\" alt=\"$description1\" width=\"$width\" height=\"$height\" />\n</a>\n", ($class != '') ? 'class="'.$class.'"' : '', ($class1 != '') ? 'class="'.$class1.'"' : '');
}

function make_image_link5 ($url = '#', $image = 'space.gif', $description = '', $width = '', $height = '', $target = '', $targetpara = '', $popup = '', $popupheader = '', $class = '', $hash = '', $name = '', $linktext = '', $imgclass = '' ) {
	global $sess, $cfg_cms, $cms_lang;

	return sprintf("\n<a style=\"text-decoration:none;\" %s%shref=\"".$sess->url($url)."%s\" onClick=\"window.open('about:blank','".$target."','".$targetpara."')\"  onmouseover=\"%sreturn true;\" onmouseout=\"%sreturn true;\"%s>\n<img src=\"tpl/".$cfg_cms['skin']."/img/".$image."\"%s%s %s%s%s />$linktext</a>\n",
	($name != '')     ? 'name="'.$name.'" ' : '',
	($class != '')    ? 'class="'.$class.'" ' : '', 
	($hash != '')     ? '#'.$hash: '',
	($popup != '')    ? 'sf_overlib('.$popup.');' : '',
	($popup != '')    ? 'sf_nd();' : '',
	($target != '')   ? " target=\"$target\"" : '',
	($width != '')    ? " width=\"$width\""       : '',
	($height != '')   ? " height=\"$height\""     : '',
	($popup == '')    ? " alt=\"$description\""   : '',
	($popup == '')    ? " title=\"$description\"" : '',
	($imgclass != '') ? ' class="'.$imgclass.'" ' : '');
}

function make_image_link6 ($image = 'space.gif', $image1 = 'space.gif', $description = '', $description1 = '', $class = '', $class1 = '', $backurl = '#', $width = '16', $height = '16' ) {
	global $sess, $cfg_cms, $cms_lang;

	return sprintf("\n<input type=\"image\" %s  class=\"w16\" src=\"tpl/".$cfg_cms['skin']."/img/".$image."\"  alt=\"$description\" />\n<a style=\"text-decoration:none;\" href=\"".$sess->url($backurl)."\">\n<img %s src=\"tpl/".$cfg_cms['skin']."/img/".$image1."\" alt=\"$description1\" width=\"$width\" height=\"$height\" />\n</a>\n", ($class != '') ? 'class="'.$class.'"' : '', ($class1 != '') ? 'class="'.$class1.'"' : '');
}

function make_nav_link($class='', $url='#', $mousetext='', $linktext='', $end='&nbsp;' ) {
	global $sess;

	return sprintf("\n<a %s href=\"%s\">%s</a>%s", ($class != '') ? "class=\"$class\"": '' , $sess->url($url), $linktext, $end);
}

// Lädt eine Validator-Klasse für Überprüfungen beim CSS-Editor oder File-Uploads
function get_validator($name) {
	global $cfg_cms;

	// include validator class and create object
	if (!$cfg_cms[$name]) $cfg_cms[$name] = 'class.validator.php';
	include_once($cfg_cms['cms_path'].'inc/'.$cfg_cms[$name]);
	$validator = new validator();
	return $validator;
}

function sf_header_redirect($url_location, $shutdown = true) {
	global $area;
	
	$url_location = str_replace('&amp;', '&', $url_location);
	header ('HTTP/1.1 302 Moved Temporarily');
	header ('Location:' . $url_location );
	
	if ($shutdown) {
		if ($area != 'logout') {
			page_close();
		}
		
		exit;
	}
}

/**
 * Negotiate Language
 * 
 * Negotiate language with the user's browser through the Accept-Language 
 * HTTP header or the user's host address.  Language codes are generally in 
 * the form "ll" for a language spoken in only one country, or "ll-CC" for a 
 * language spoken in a particular country.  For example, U.S. English is 
 * "en-US", while British English is "en-UK".  Portugese as spoken in
 * Portugal is "pt-PT", while Brazilian Portugese is "pt-BR".
 * 
 * Quality factors in the Accept-Language: header are supported, e.g.:
 *      Accept-Language: en-UK;q=0.7, en-US;q=0.6, no, dk;q=0.8
 * 
 * <code>
 *  require_once 'HTTP.php';
 *  $langs = array(
 *      'en'   => 'locales/en',
 *      'en-US'=> 'locales/en',
 *      'en-UK'=> 'locales/en',
 *      'de'   => 'locales/de',
 *      'de-DE'=> 'locales/de',
 *      'de-AT'=> 'locales/de',
 *  );
 *  $neg = HTTP::negotiateLanguage($langs);
 *  $dir = $langs[$neg];
 * </code>
 * 
 * @static 
 * @access  public 
 * @return  string  The negotiated language result or the supplied default.
 * @param   array   $supported An associative array of supported languages,
 *                  whose values must evaluate to true.
 * @param   string  $default The default language to use if none is found.
 */
function negotiateLanguage($supported, $default = 'en-US')
{
    $supp = array();
    foreach ($supported as $lang => $isSupported) {
        if ($isSupported) {
            $supp[strToLower($lang)] = $lang;
        }
    }
    
    if (!count($supp)) {
        return $default;
    }

    $matches = array();
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $lang) {
            $lang = array_map('trim', explode(';', $lang));
            if (isset($lang[1])) {
                $l = strtolower($lang[0]);
                $q = (float) str_replace('q=', '', $lang[1]);
            } else {
                $l = strtolower($lang[0]);
                $q = null;
            }
            if (isset($supp[$l])) {
                $matches[$l] = isset($q) ? $q : 1000 - count($matches);
            }
        }
    }

    if (count($matches)) {
        asort($matches, SORT_NUMERIC);
        return $supp[array_pop(array_keys($matches))];
    }
    
    if (isset($_SERVER['REMOTE_HOST'])) {
        $lang = strtolower(array_pop(explode('.', $_SERVER['REMOTE_HOST'])));
        if (isset($supp[$lang])) {
            return $supp[$lang];
        }
    }

    return $default;
}
/**
   * Is String an Url?
   */     
function isUrl($url){
  	

    if(filter_var($url, FILTER_VALIDATE_URL)){ 
        return true; 
    }else{
	   	return false;
	}
}
?>
