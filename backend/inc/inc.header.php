<?PHP
// File: $Id: inc.header.php 28 2008-05-11 19:18:49Z mistral $
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
// + Autor: $Author: mistral $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 28 $
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


header('Content-type: text/html; charset=UTF-8');
// Browsern das cachen von Backendseiten verbieten
if ($cfg_cms['backend_cache'] == '1') {
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Datum aus Vergangenheit
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // immer geändert
	header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache');
}
$tpl->loadTemplatefile('header.tpl');
if ($area == 'con_frameheader') {
    $area='con_editframe';
}

// Selectbox für die Projekte
$sql = "SELECT DISTINCT * FROM ". $cms_db['clients'] ." A LEFT JOIN ". $cms_db['clients_lang']." B USING(idclient) ORDER BY A.name";
$db->query($sql);
$con_more_than_one_client = false;
$prev_client = '';
while($db->next_record()) {
	// darf User Projekt sehen?
	if ($perm -> have_perm('1', 'lang', $db->f('idlang')) && $prev_client != $db->f('idclient')) {
		if ($client == $db->f('idclient')) {
			$con_client_options .= "<option value=\"".$db->f('idclient')."\" selected=\"selected\">".$db->f('name')."</option>\n";
			$con_act_client = $db->f('name');
		} else {
			$con_client_options .= "<option value=\"".$db->f('idclient')."\">".$db->f('name')."</option>\n";
			$con_more_than_one_client = true;
		}
		// wenn mehrere sprachen in einem client sind, verhindern, das der client öfters als ein mal angezeigt wird
		$prev_client = $db->f('idclient');
	}
}


$tpl_frm['CLIENT_FORM'] = '';
$tpl_frm['LANG_FORM'] = '';

if ($con_more_than_one_client) {
	$tpl_frm['CLIENT_FORM'] = "<form name=\"clientform\" id=\"clientform\" method=\"post\" action=\"".$sess->url('main.php')."\" target=\"_top\">";
	if ($is_plugin || $area == 'con_editframe') $tpl_frm['CLIENT_FORM'] .= "<input type=\"hidden\" name=\"area\" value=\"con\" />";
	if (!empty($idcatside)) $tpl_frm['CLIENT_FORM'] .= "<input type=\"hidden\" name=\"idcatside\" value=\"$idcatside\" />";
	if (!empty($idside)) $tpl_frm['CLIENT_FORM'] .= "<input type=\"hidden\" name=\"idside\" value=\"$idside\" />";
	if (!empty($idcat)) $tpl_frm['CLIENT_FORM'] .= "<input type=\"hidden\" name=\"idcat\" value=\"$idcat\" />";
	if (!empty($idlay)) $tpl_frm['CLIENT_FORM'] .= "<input type=\"hidden\" name=\"idlay\" value=\"$idlay\" />";
	if (!empty($idmod)) $tpl_frm['CLIENT_FORM'] .= "<input type=\"hidden\" name=\"idmod\" value=\"$idmod\" />";
	if (!empty($idtpl)) $tpl_frm['CLIENT_FORM'] .= "<input type=\"hidden\" name=\"idtpl\" value=\"$idtpl\" />";
	if (!empty($idclient)) $tpl_frm['CLIENT_FORM'] .= "<input type=\"hidden\" style='' name=\"idclient\" value=\"$idclient\" />";
	if (!empty($idcatsidetpl)) $tpl_frm['CLIENT_FORM'] .= "<input type=\"hidden\" name=\"idcatsidetpl\" value=\"$idcatsidetpl\" />";
	$tpl_frm['CLIENT_FORM'] .= "<select name=\"client\" size=\"1\" onchange=\"clientform.submit()\">".$con_client_options."</select><input type=\"hidden\" name=\"changeclient\" value=\"1\" /><input type=\"hidden\" name=\"change_show_tree\" value=\"0\" /></form>";
}

// Selectbox für die Sprachen
$sql = "SELECT A.idlang, A.name FROM ".$cms_db['lang']." A LEFT JOIN ".$cms_db['clients_lang']." B USING(idlang) WHERE B.idclient='$client' ORDER BY idlang";
$db->query($sql);
$con_more_than_one_lang = false;
while($db->next_record()) {
	// darf User Sprache sehen?
	if($perm -> have_perm('1', 'lang', $db->f('idlang'))) {
		if ($lang == $db->f('idlang')) {
			$con_lang_options .= "<option value=\"".$db->f('idlang')."\" selected=\"selected\">".$db->f('name')."</option>\n";
			$con_act_lang = $db->f('name');
		} else {
			$con_lang_options .= "<option value=\"".$db->f('idlang')."\">".$db->f('name')."</option>\n";
			$con_more_than_one_lang = true;
		}
	}
}
if($con_more_than_one_lang) {
	$tpl_frm['LANG_FORM'] ="<form name=\"languageform\" id=\"languageform\" method=\"post\" action=\"".$sess->url('main.php?area='.$area)."\" target=\"_top\">\n";
	if ($area == 'plugin') {
		$sf_forbiddenvars = array('action', 'area', 'client', 'lang', 'idcatsidetpl', 'idclient', 'idtpl', 'idmod', 'idlay', 'idcat', 'idside');
		foreach ($_REQUEST AS $k=>$v) {
			if (! is_array($v))
			{
				if (! array_key_exists($k, $sf_forbiddenvars) ) {
					$tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"$k\" value=\"$v\" />\n";
				}
			}
		}
	}
	
	if (!empty($idside)) $tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"idside\" value=\"$idside\" />";
	if (!empty($idcat)) $tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"idcat\" value=\"$idcat\" />";
	if (!empty($idlay)) $tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"idlay\" value=\"$idlay\" />";
	if (!empty($idmod)) $tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"idmod\" value=\"$idmod\" />";
	if (!empty($idtpl)) $tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"idtpl\" value=\"$idtpl\" />";
	if (!empty($idclient)) $tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"idclient\" value=\"$idclient\" />";
	if (!empty($idcatsidetpl)) $tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"idcatsidetpl\" value=\"$idcatsidetpl\" />";
	if (isset($change_show_tree)) $tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"change_show_tree\" value=\"$change_show_tree\" />";
	else $tpl_frm['LANG_FORM'] .= "<input type=\"hidden\" name=\"change_show_tree\" value=\"$show_tree\" />";
	$tpl_frm['LANG_FORM'] .= "\n\n<select name=\"lang\" size=\"1\" onchange=\"languageform.submit()\">";
	$tpl_frm['LANG_FORM'] .= $con_lang_options."</select></form>";
}
$sql = "SELECT idbackendmenu, parent, sortindex, entry_langstring, entry_url, url_target, entry_validate FROM ". $cms_db['backendmenu'] ." WHERE idclient IN(0, $client) AND entry_langstring NOT IN('empty_dummy') ORDER BY parent, sortindex";
$db->query($sql);
for($i =0; $db->next_record(); $i++) {
	$parent_old = $parent_new;
	$parent_new = $db->f('parent');
	if($parent_new != $parent_old){$k=0;}
	$unsort_array[$parent_new][$k]['id'] = $db->f('idbackendmenu');
	$unsort_array[$parent_new][$k]['parent'] = $db->f('parent');
	$unsort_array[$parent_new][$k]['sort'] = $db->f('sortindex');
	$unsort_array[$parent_new][$k]['langstring'] = $db->f('entry_langstring');
	$unsort_array[$parent_new][$k]['url'] = $db->f('entry_url');
	$unsort_array[$parent_new][$k]['url_target'] = $db->f('url_target');
	$unsort_array[$parent_new][$k]['validate'] = $db->f('entry_validate');
	$k++;
}

/**
* rekursives Auslesen vom $unsort_array
* die Daten werden danach mit richtiger Reihenfolge in ein Array eingeordnet
*/
/**
* Menupunkte rekursiv in das Array tree einlesen
*
* @args startposition im baum
*
*/
$count =0;
$maxlevel =0;
menu_level_order(0);
function menu_level_order($node_id, $level = 0) {
	global $unsort_array, $tree, $count, $maxlevel;

	for($i =0; ! empty($unsort_array[$node_id][$i]['id']) ; $i++) {
		$tree[$count]['level'] = $level;
		$tree[$count]['id'] = $unsort_array[$node_id][$i]['id'];
		$tree[$count]['sort'] = $unsort_array[$node_id][$i]['sort'];
		$tree[$count]['langstring'] = $unsort_array[$node_id][$i]['langstring'];
		$tree[$count]['url'] = $unsort_array[$node_id][$i]['url'];
		$tree[$count]['url_target'] = $unsort_array[$node_id][$i]['url_target'];
		$tree[$count]['validate'] = $unsort_array[$node_id][$i]['validate'];
		$tree[$count]['parent'] = $unsort_array[$node_id][$i]['parent'];
		if ($tree[$count][0] > $maxlevel) $maxlevel = $tree[$count]['id'];
		$count++;
		/* Haengt ein Leaf am Leaf? einfach mal rekursiv runtergehen und nachkucken ... */
		menu_level_order($tree[$count-1]['id'], ($level + 1));
	}
	/* Fallback aus der Rekursion */
	return;
}
$main_index = -1;
$sub_index = -1;

// needed to figure out active sublaye
$pos = strpos($area, '_');
$layer_cutter = (!$pos) ? $area: substr( $area, 0, $pos );
for($i = 0; $i < $count; $i++) {
	// Hauptmenü bauen
	if($tree[$i]['level'] == '0'){
		$main_index++;
		$sub_index = -1;
        //CHANGE STAM
        if ($tree[$i]['url'] == 'root') {
            $surl = 'javascript:con_layer('. ($main_index+1) .')';
        } else {
            $dynamic = '$surl = "'.$tree[$i]['url']. '";';
        	// parse url, this is nessesary if the var includes dynamic content like arrays, vars, etc...
            eval($dynamic);
        }
        //CHANGE STAM
		$mouseover = '';
		$mouseout = '';
		$mainmenu[$main_index]['url'] = '<a href = "'.$surl. '" '. $mouseover .' '. $mouseout .' onfocus="this.blur()" {class}>'. $cms_lang[$tree[$i]['langstring']] .'</a>';
		$mainmenu[$main_index]['validate'] = $tree[$i]['validate'];
		$out = $mainmenu[$main_index]['url'];

	// Untermenü aufbauen
	} else {
		$sub_index++;
		$dynamic = '$surl = "'.$tree[$i]['url']. '";';

		// parse url, this is nessesary if the var includes dynamic content like arrays, vars, etc...
		eval($dynamic);

		// target
		if($tree[$i]['url_target'] == 'frame') {
			$surl = $sess->url('main.php?area=con_editframe&idplugin='. $tree[$i]['id']);
		} else {
			$surl = $sess->url($surl);
		}
		$mouseover = '';
		$mouseout = '';

		
		// fucking mouseover slashing
		$submenu[$sub_index] = str_replace( '"', '\"', '<a href = "'.$surl. '" '. $mouseover .' '. $mouseout .'  onfocus="this.blur()" target="_top" {class}>'. $cms_lang[$tree[$i]['langstring']] .'</a>');
		
		
		// check for active menu layer
		//CHANGE STAM BB
		if ( (preg_match('/area='.$layer_cutter.'\b/', $tree[$i]['url']) && empty($cms_plugin) && empty($idplugin) ) 
			|| $idplugin == $tree[$i]['id'] 
			|| strstr($tree[$i]['url'], 'cms_plugin='.$cms_plugin) 
			&& ! empty($cms_plugin)){
			$submenu[$sub_index] = str_replace('{class}', 'class=\"active\"', $submenu[$sub_index]);
			$active_submenu_layer = $main_index +1;
		}
		//CHANGE STAM BB
		
		$mainmenu[$main_index]['permstring'] .= '( '. $tree[$i]['validate'] .')*';
		$dynamic = 'if('.$tree[$i]['validate'].')$out="'. $submenu[$sub_index] .'";else $out="";';
		// check perms
		eval($dynamic);

		// fucking mouseover slashing2
		$sub_final[$main_index] .= str_replace('`', "'", $out);
		$cat_is_nit_empty[$main_index] = true;
	}
	$out = '';
}

//set active mainnav class
$mainmenu[($active_submenu_layer-1)]['url'] = str_replace('{class}', 'class="active"', $mainmenu[($active_submenu_layer-1)]['url']);

$tpl->setCurrentBlock('SUBMENU');

// throw out mainmenu
$maincount = count($mainmenu);
//print_r($mainmenu);
for($i=0; $i < $maincount; $i++) {
	$permstring = str_replace(')*(', ') || (', $mainmenu[$i]['permstring']);
	$permstring = str_replace(')*', ')', $permstring);
	if ($mainmenu[$i]['validate'] != 'root' && $permstring) {
		$permstring = '('.$permstring.') && '.$mainmenu[$i]['validate'];
	}

	// check perms for displaying maincat
	if(trim($permstring) != ''){
		$permurl = $mainmenu[$i]['url'];
		$dynamic = 'if('.$permstring.') $out.="$permurl";else $out.="";';
		eval($dynamic);

	// only output, no perms to check
	} else if($cat_is_nit_empty[$i]) {
			$out .= $mainmenu[$i]['url'];
	}
	
	
	$sub_tmp['COUNT'] = $i+1;
	$sub_tmp['SUB_MENU_ENTRYS'] = $sub_final[$i];
	$sub_tmp['IMGPATH'] = 'tpl/'.$cfg_cms['skin'].'/img/';
	$tpl->setVariable($sub_tmp);
	$tpl->parseCurrentBlock('SUBMENU');
}

if ($tpl_frm['CLIENT_FORM'] != ''|| $tpl_frm['LANG_FORM'] != '') {
	$tpl->setVariable($tpl_frm);
	$tpl->parseCurrentBlock('CLIENT_LANG_SELECT');
}
$tpl_frm = null;

$tpl_in['VERSION'] = $cfg_cms['version'];
$tpl_in['IMGPATH'] = 'tpl/'.$cfg_cms['skin'].'/img/';
$tpl_in['MAIN_MENU_ENTRYS'] = $out;
$tpl_in['MAX_SUBMENUS'] = $maincount;;
$tpl_in['ACTIVE_SUBMENU_LAYER'] = $active_submenu_layer;
$tpl_in['LOGOUT_URL'] = $sess->url('main.php?area=logout');
$tpl_in['PATH_HELP'] = 'help/index_'.$cfg_cms['backend_lang'] .'.php#'. $area ;
$tpl_in['LANG_TOOLTIP'] = addslashes($cms_lang['gen_logout']);
$tpl_in['DELETE_MSG'] = $cms_lang['gen_deletealert'];
$tpl_in['HELP_TOOLTIP'] = addslashes($cms_lang['cms_help']);
$tpl_in['LOGOUT_WIDTH'] = $cms_lang['gen_logout_wide'];
if(!empty($auth->auth['name']) && !empty($auth->auth['surname'])) {
    $tpl_in['LOGGED_USER'] = $cms_lang['gen_welcome'] . ', ' . $auth->auth['name'] . ' ' .$auth->auth['surname'];
} else if(!empty($auth->auth['name'])) {
    $tpl_in['LOGGED_USER'] = $cms_lang['gen_welcome'] . ', ' .$auth->auth['name'];
} else if(!empty($auth->auth['surname'])) {
    $tpl_in['LOGGED_USER'] = $cms_lang['gen_welcome'] . ', '.$auth->auth['surname'];
} else {
    $tpl_in['LOGGED_USER'] = $cms_lang['gen_welcome'] . ', ' . $auth->auth['uname'];
}

$tpl->setVariable($tpl_in);
$tpl->show();
unset($sub_tmp, $tpl_in);
unset($unsort_array, $tree, $count, $maxlevel);
unset($tpl);
$tpl = new HTML_Template_IT($this_dir.'tpl/'.$cfg_cms['skin'].'/');
?>