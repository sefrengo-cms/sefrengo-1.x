<?PHP
// File: $Id: inc.con.php 52 2008-07-20 16:16:33Z bjoern $
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

/******************************************************************************
 1. Benötigte Funktionen und Klassen includieren
******************************************************************************/

include('inc/fnc.con.php');
/**
 * Class to check if a doublet/ clone Page of an idcatside exists
 *
 *  (c) 2004 by Björn Brockmann
 */
class cmsPageDoubletAudit{

	var $idsideStore = array();

	/**
	 * Construtor, generates data for idatsidecheking
	 */
	function  cmsPageDoubletAudit(){
		global $db, $cms_db, $lang;

		$sql ="SELECT
					CS.idcatside, SL.idside
					FROM ". $cms_db['cat_side'] . " CS
					LEFT JOIN " . $cms_db['side_lang']. " SL USING(idside)
					WHERE SL.idlang = $lang
					ORDER BY CS.idside";
		$db->query($sql);

		while($db->next_record()){
			if(! is_array($this->idsideStore[$db->f('idside')]))
				$this->idsideStore[$db->f('idside')] = array();

			array_push($this->idsideStore[$db->f('idside')], $db->f('idcatside'));
		}
		//print_r($this->idsideStore);
	}

	/**
	 * Checks if a doublet/ clone of one idcatside exists
	 *
	 * @parms int idside
	 * @return bool true/ false
	 */
	function pageDoubletExists($idside){

		if(! is_int($idside))
			return false;

		return (count($this->idsideStore[$idside]) > 1);
	}
}

$sefrengoPDA = new cmsPageDoubletAudit;

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

if ($change_show_tree == 'delete_cache') {
	 $action = 'delete_cache';
} else if ($change_show_tree == 'regenerate_rewrite_urls') {
	$action = 'regenerate_rewrite_urls';
}

if ($action && $view && $perm->have_perm(3, 'area_frontend', 3) ) {
	switch($action) {
		case 'side_delete':  // Seite löschen
			con_delete_side ($idcat, $idside);
			fire_event('con_side_delete', array('idcat' => $idcat, 'idside' => $idside));
			header ('HTTP/1.1 302 Moved Temporarily');
			header ('Location:'.$sess->urlRaw($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcat='.$idcat.'&view='.$view));
			exit;
			break;
		case 'cat_delete':  // Ordner löschen
			// Event
			$errno = con_delete_cat ($idcat);
			fire_event('con_cat_delete', array('idcat' => $idcat,'errno' => $errno));
			header ('HTTP/1.1 302 Moved Temporarily');
			header ('Location:'.$sess->urlRaw($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&view='.$view));
			exit;
			break;
		case 'side_visible':  // Seite online/offline schalten
			// change JB
			$bitmask = (($online & 0x02) == 0x02) ? 0xFC: 0xFC;
			$bit_to_clear = (($online & 0x01) == 0x01 || ($online & 0x05) == 0x05);
			$change = ($bit_to_clear) ? ($online & $bitmask): (($online & $bitmask) | 0x01);
			con_visible_side ($idside, $lang, $change);
			if ($bit_to_clear) fire_event('con_side_offline', array('idside' => $idside));
			else fire_event('con_side_online', array('idside' => $idside));
			header ('HTTP/1.1 302 Moved Temporarily');
			header ('Location:'.$sess->urlRaw($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcatside='.$idcatside.'&view='.$view));
			exit;
			break;

	}
}
$perm->check('area_con');
switch($action) {
	case 'side_start':  // Seite als Startseite festlegen
		con_make_start ($idcatside, !$is_start);
		fire_event('con_side_start', array('idcatside' => $idcatside));
		break;
	case 'side_delete':  // Seite löschen
		con_delete_side ($idcat, $idside);
		fire_event('con_side_delete', array('idcat' => $idcat, 'idside' => $idside));
		break;
	case 'side_visible':  // Seite online/offline schalten
		// change JB
		$bitmask = 0xFC;
		$bit_to_clear = (($online & 0x01) == 0x01 || ($online & 0x05) == 0x05);
		$change = ($bit_to_clear) ? ($online & $bitmask): (($online & $bitmask) | 0x01);
		con_visible_side ($idside, $lang, $change);
		if ($bit_to_clear) fire_event('con_side_offline', array('idside' => $idside));
		else fire_event('con_side_online', array('idside' => $idside));
		break;
	case 'delete_cache':  // Cache löschen
		con_delete_cache($lang);
		fire_event('con_cache_delete', array());
		break;
	case 'regenerate_rewrite_urls':
		con_delete_cache($lang);
		include_once($cfg_cms['cms_path'].'inc/fnc.mod_rewrite.php');
		rewriteAutoForAll($lang);
		break;
	case 'cat_delete':  // Ordner löschen
		// Event
		$errno = con_delete_cat ($idcat);
		fire_event('con_cat_delete', array('idcat' => $idcat,'errno' => $errno));
		break;
	case 'cat_visible':  // Ordner online schalten
		//test if offline
		$bit_to_clear = (($visible & 0x01) == 0x01);
		//make offline or online
		$change = ($bit_to_clear) ? ($visible & 0xFE): ($visible | 0x01);
		con_visible_cat ($idcat, $lang, $change);
		if ($bit_to_clear) fire_event('con_cat_offline', array('idcat' => $idcat));
		else fire_event('con_cat_online', array('idcat' => $idcat));
		break;
	case 'cat_lock':  // Ordner sperren
		con_lock ('cat', $idcat, $lock);
		if ($lock == '1') fire_event('con_cat_unlock', array('idcat' => $idcat));
		else fire_event('con_cat_lock', array('idcat' => $idcat));
		break;
	case 'side_lock':  // Seite sperren
//		con_lock ('side', $idcatside, $lock);
		con_lock ('side', $idside, $lock);
		if ($lock == '1') fire_event('con_side_unlock', array('idside' => $idside));
		else fire_event('con_side_lock', array('idside' => $idside));
		break;
	case 'expand':  // Ordner aufklappen
		con_expand ($idcat, $expanded);
		break;
	case 'sideup': // Seite eins nach oben schieben
		con_move_side ('up',$idcat,$idside,$sortindex);
		break;
	case 'sidedown': // Seite eins nach unten schieben
		con_move_side ('down',$idcat,$idside,$sortindex);
		break;
	case 'sidetop': // Seite ganz nach oben schieben
		con_move_top_bottom ('top',$idcat,$idcatside,$sortindex);
		break;
	case 'sidebottom': // Seite ganz nach unten schieben
		con_move_top_bottom ('bottom',$idcat,$idcatside,$sortindex);
		break;
	case 'quicksort': // schnelles Umsortieren
		con_quick_sort ($quicksort,$idcat);
		break;
	case 'movecat': // verschieben des Ordners
		con_move_cat ($idcat, $target, $client);
		break;
	case 'catupdown': // umsortieren der Ordner
		con_sort_cat ($dir,$idcat,$sortindex,$parent,$client);
		break;
}

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');


/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/
// Aufbau des Arrays $con_tree
// jb_todo: sperren category
$sql = "SELECT
         B.idcat, B.parent, B.sortindex, C.idcatlang, C.author, C.created, C.lastmodified,
		 C.visible, C.idtplconf, C.name, E.name AS tplname, F.idcat AS expanded
		FROM "       . $cms_db['cat']        . " B
		 LEFT JOIN " . $cms_db['cat_lang']   . " C USING(idcat)
		 LEFT JOIN " . $cms_db['tpl_conf']   . " D USING(idtplconf)
		 LEFT JOIN " . $cms_db['tpl']        . " E USING(idtpl)
		 LEFT JOIN " . $cms_db['cat_expand'] . " F ON B.idcat = F.idcat AND F.idusers = '" . $auth->auth['uid'] . "'
		WHERE B.idclient = $client
		 AND  C.idlang   = $lang
		ORDER BY B.parent, B.sortindex";
$db->query($sql);
while($db->next_record()) {
	$idcat_loop = $db->f('idcat');

	$con_tree[$idcat_loop]['link']         = $sess->url($cfg_client['htmlpath'].$cfg_client['contentfile']."?lang=$lang&idcat=".$idcat_loop."&view=preview");
	$con_tree[$idcat_loop]['idcat']        = $idcat_loop;
	$con_tree[$idcat_loop]['idcatlang']    = $db->f('idcatlang');
	$con_tree[$idcat_loop]['author']       = $db->f('author');
	$con_tree[$idcat_loop]['created']      = $db->f('created');
	$con_tree[$idcat_loop]['lastmodified'] = $db->f('lastmodified');
	$con_tree[$idcat_loop]['parent']       = $db->f('parent');
	$con_tree[$idcat_loop]['visible']      = $db->f('visible');
	$con_tree[$idcat_loop]['idtplconf']    = $db->f('idtplconf');
	$con_tree[$idcat_loop]['name']         = htmlentities($db->f('name'), ENT_COMPAT, 'UTF-8');
	$con_tree[$idcat_loop]['tplname']      = $db->f('tplname');
	$con_tree[$idcat_loop]['sortindex']    = $db->f('sortindex');
	$con_tree[$idcat_loop]['offline']      = (($db->f('visible') & 0x04) == 0x04);  // change JB: ($db->f('perm') == '0');

	$tlo_tree['expanded'][$idcat_loop]               = $db->f('expanded');
	$tlo_tree[$db->f('parent')][$db->f('sortindex')] = $idcat_loop;
}

// neuen Hauptordner anlegen
if ($change_show_tree == 'new_folder') {
	header ('HTTP/1.1 302 Moved Temporarily');
	header ('Location: '.$sess->urlRaw('main.php?area=con_configcat&parent=0&idtplconf=0'));
	exit;
} else if ($change_show_tree == 'publish') {
	$action = 'all_publish';
	unset($change_show_tree);
}

// Ordner, der angezeigt werden soll, bestimmen
if (!isset($show_tree)) {
	$sess->register('show_tree');
	$show_tree = '0';
}
if (isset($change_show_tree))                  $show_tree = $change_show_tree;
//if (!$perm->have_perm(1, 'area_con', 0)) $show_tree = '0';
if (!$con_tree[$show_tree])                    $show_tree = '0';

// Ordner sortieren
if ($show_tree == '0') {
	tree_level_order('0', 'catlist');
} else {
	if ($perm -> have_perm(1, 'cat', $show_tree)) {
		$catlist[] = $show_tree;
	}
	if ($tlo_tree['expanded'][$show_tree] == $show_tree || !$perm -> have_perm(1, 'cat', $show_tree)) {
		tree_level_order($show_tree, 'catlist', 'false', '1');
	}
}

$tpl->loadTemplatefile('con_main.tpl');

$tpl_data['AREA'] = $cms_lang['area_con'];
$tpl_data['FOOTER_LICENSE'] = $cms_lang['login_licence'];

if(! empty($errno)){
	$tpl -> setCurrentBlock('ERRORMESSAGE');
	$tpl_error['ERRORMESSAGE'] = $cms_lang['err_' . $errno];
	$tpl->setVariable($tpl_error);
	$tpl->parseCurrentBlock();
}

//Generate selectbox ACTIONS...
$tpl -> setCurrentBlock('SELECT_ACTIONS');

$show_action_select = false;
// neuen Ordner erstellen
if ($perm->have_perm(2, 'area_con', $show_tree)){
	$tpl_folderlist['ACTIONS_VALUE'] = 'new_folder';
	$tpl_folderlist['ACTIONS_ENTRY'] = $cms_lang['con_folder_new'];
	$tpl_folderlist['ACTIONS_SELECTED'] = '';
	$tpl->setVariable($tpl_folderlist);
	$tpl->parseCurrentBlock();
	unset($tpl_folderlist);
	$show_action_select = true;
}


// Webseite publizieren
if ($cfg_client['publish'] == '1' && $perm->have_perm('7', 'area_con', 0) && $perm->have_perm('23', 'area_con', 0)){
	$tpl_folderlist['ACTIONS_VALUE'] = 'publish';
	$tpl_folderlist['ACTIONS_ENTRY'] = $cms_lang['con_folder_publish'];
	$tpl_folderlist['ACTIONS_SELECTED'] = '';
	$tpl->setVariable($tpl_folderlist);
	$tpl->parseCurrentBlock();
	unset($tpl_folderlist);
	$show_action_select = true;
}

// Cache löschen
if ( $perm ->is_admin() ){
	$tpl_folderlist['ACTIONS_VALUE'] = 'delete_cache';
	$tpl_folderlist['ACTIONS_ENTRY'] = $cms_lang['con_delete_cache'];
	$tpl_folderlist['ACTIONS_SELECTED'] = '';
	$tpl->setVariable($tpl_folderlist);
	$tpl->parseCurrentBlock();
	unset($tpl_folderlist);
	$show_action_select = true;
}

// regenerate rewrite urls
if ($cfg_client['url_rewrite'] == '2'){
	$tpl_folderlist['ACTIONS_VALUE'] = 'regenerate_rewrite_urls';
	$tpl_folderlist['ACTIONS_ENTRY'] = $cms_lang['con_regenerate_rewrite_urls'];
	$tpl_folderlist['ACTIONS_SELECTED'] = '';
	$tpl->setVariable($tpl_folderlist);
	$tpl->parseCurrentBlock();
	unset($tpl_folderlist);
	$show_action_select = true;
}

if($show_action_select){
	$tpl -> setCurrentBlock('FORM_SELECT_ACTIONS');
	$entry['FORM_URL_ACTIONS'] = $sess->url('main.php');
	$entry['LANG_SELECT_ACTIONS'] = $cms_lang['gen_select_actions'];
	$tpl->setVariable($entry);
	$tpl->parseCurrentBlock();
	unset($entry, $show_action_select);
}



//Generate Selectbox VIEW...
$tpl -> setCurrentBlock('SELECT_FOLDERLIST');

// alle Ordner anzeigen
$tpl_folderlist['FOLDERLIST_VALUE'] = '0';
$tpl_folderlist['FOLDERLIST_ENTRY'] = $cms_lang['con_folder_view'];
$tpl_folderlist['FOLDERLIST_SELECTED'] = '';
$tpl->setVariable($tpl_folderlist);
$tpl->parseCurrentBlock();
unset($tpl_folderlist);

// Leerzeile
$tpl_folderlist['FOLDERLIST_VALUE'] = '';
$tpl_folderlist['FOLDERLIST_ENTRY'] = '---------------------------------------';
$tpl_folderlist['FOLDERLIST_SELECTED'] = '';
$tpl->setVariable($tpl_folderlist);
$tpl->parseCurrentBlock();
unset($tpl_folderlist);

// Ordner auflisten
tree_level_order('0', 'folderlist', 'true');
if (is_array($folderlist)) {
	foreach ($folderlist as $a) {
		$spaces = '';
		if (! $perm -> have_perm(1, 'cat', $a)) continue;

		for ($i=0; $i<$folderlist_level[$a]; $i++) $spaces = $spaces.'&nbsp;&nbsp;';
		$tpl_folderlist['FOLDERLIST_VALUE'] = $a;
		$tpl_folderlist['FOLDERLIST_ENTRY'] = $spaces.'&nbsp;-&nbsp;'.$con_tree[$a]['name'];

		$catmove_list[$a]['level'] = $folderlist_level[$a];
		$catmove_list[$a]['name'] = $tpl_folderlist['FOLDERLIST_ENTRY'];
		$catmove_list[$a]['idcat'] = $a;


		if ($show_tree == $a) $tpl_folderlist['FOLDERLIST_SELECTED'] = 'selected';
		else $tpl_folderlist['FOLDERLIST_SELECTED'] = '';
		$tpl->setVariable($tpl_folderlist);
		$tpl->parseCurrentBlock();
	}
	unset($tpl_folderlist);
}

//Generate Selectbox VIEW...
$tpl -> setCurrentBlock('FORM_SELECT_VIEW');
$entry['LANG_SELECT_VIEW'] = $cms_lang['gen_select_view'];
$entry['FORM_URL_VIEW'] = $sess->url('main.php');
$tpl->setVariable($entry);
$tpl->parseCurrentBlock();
unset($entry);



//Generate Selectbox CHANGE TO...
//print_r( $perm->get_group() );
if($perm->have_perm(9, 'area_con',0) || $perm->have_perm(25, 'area_con',0)
   || $perm->is_any_perm_set('cat', 9, $perm->get_group(), $lang)
   || $perm->is_any_perm_set('cat', 25, $perm->get_group(), $lang)
   || $perm->is_any_perm_set('side', 25, $perm->get_group(), $lang)) {
	$tpl -> setCurrentBlock('SELECT_CHANGE_TO');
	// Webseite publizieren
	if ($sort){
		$tpl_folderlist['CHANGE_TO_VALUE'] = '';
		$tpl_folderlist['CHANGE_TO_ENTRY'] = $cms_lang['con_view_normal'];
		$tpl_folderlist['CHANGE_TO_SELECTED'] = '';
		$tpl->setVariable($tpl_folderlist);
		$tpl->parseCurrentBlock();
		unset($tpl_folderlist);
	} else{
		$tpl_folderlist['CHANGE_TO_VALUE'] = 'true';
		$tpl_folderlist['CHANGE_TO_ENTRY'] = $cms_lang['con_sort'];
		$tpl_folderlist['CHANGE_TO_SELECTED'] = '';
		$tpl->setVariable($tpl_folderlist);
		$tpl->parseCurrentBlock();
		unset($tpl_folderlist);
	}
	$tpl -> setCurrentBlock('FORM_CHANGE_TO');
	$entry['FORM_URL_CHANGE_TO'] = $sess->url('main.php');
	$entry['LANG_CHANGE_TO'] = $cms_lang['gen_select_change_to'];
	$tpl->setVariable($entry);
	$tpl->parseCurrentBlock();
	unset($entry);
}



$tpl -> setCurrentBlock('__GLOBAL__');
$tpl_data['LANG_STRUCTURE_AND_SIDE'] = $cms_lang['con_structureandsides'];
$tpl_data['BUTTON_EXPAND'] = make_image_link('main.php?area=con&action=expand&expanded=3&idcat='.$show_tree, 'but_plus_small.gif', $cms_lang['con_allexpanded'],  '16', '11');
$tpl_data['BUTTON_MINIMIZE'] = make_image_link('main.php?area=con&action=expand&expanded=2&idcat='.$show_tree, 'but_minus_small.gif', $cms_lang['con_nooneexpanded'],  '16', '11');
$tpl_data['LANG_ACTIONS'] = $cms_lang['con_action'];
$tpl->setVariable($tpl_data);
unset($tpl_data);

// Ordner
if (is_array($catlist)) {
	// Aufbau des Arrays $con_side
	$sql = "SELECT A.idcatside, A.idcat, A.sortindex, changed
         	FROM "       . $cms_db['cat_side']. " A
             LEFT JOIN " . $cms_db['cat']     . " B USING(idcat)
             LEFT JOIN " . $cms_db['code']    . " C ON A.idcatside = C.idcatside AND idlang = $lang
            WHERE B.idclient = $client
            ORDER BY idcatside";
	$db->query($sql);
	while($db->next_record()) {
		$con_side[$db->f('idcat')][$db->f('idcatside')]['idcatside'] = $db->f('idcatside');
		$con_side[$db->f('idcat')][$db->f('idcatside')]['idcat'] = $db->f('idcat');
		if ($db->f('changed') == '2' && $perm->have_perm('19', 'side', $db->f('idcatside'), $db->f('idcat'))) {
			$con_side[$db->f('idcat')][$db->f('idcatside')]['status'] = 'true';
			$con_tree[$db->f('idcat')]['status'] = 'true';
		}

		// Sortindex einfügen
		if($db->f('sortindex') > 0) {
			if(!empty($sidelist[$db->f('idcat')][$db->f('sortindex')])) {
				array_push($sidelist[$db->f('idcat')], $db->f('idcatside'));
				$reindex[$db->f('idcat')] = 1;
			} else $sidelist[$db->f('idcat')][$db->f('sortindex')] = $db->f('idcatside');
		} elseif (is_array($sidelist[$db->f('idcat')])) {
			array_push($sidelist[$db->f('idcat')], $db->f('idcatside'));
			$reindex[$db->f('idcat')] = 1;
		} else {
			$sidelist[$db->f('idcat')] = array($db->f('idcatside'));
			$reindex[$db->f('idcat')] = 1;
		}
	}

//	// neue Seiten einsortieren
//	if($reindex) reindex_sort($sidelist);

	// Seiten publizieren
	if ($action == 'side_publish' && $perm->have_perm('23', 'side', $idcatside, $idcat)) con_publish($idcatside, 'side');
	if ($action == 'cat_publish'  && $perm->have_perm('7', 'cat', $idcat)) con_publish($idcat, 'cat');
	if ($action == 'all_publish'  && $perm->have_perm('7', 'area_con', 0) && $perm->have_perm('23', 'area_con', 0)) con_publish($show_tree);

	// Ordner anzeigen
	foreach ($catlist as $a) {
		//darf Ordner sehen?
		if(! $perm->have_perm(1, 'cat', $con_tree[$a]['idcat']) ) continue;

		$spaces = '';
		for ($i='0'; $i<$catlist_level[$a]; $i++) $spaces = $spaces.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		// Platzhalter zwischen Hauptordnern
		if ($catlist_level[$a] == '0' && $catlist['0'] != $a) $tpl_cat_values['EMPTY_ROW'] = "<tr><td colspan=\"4\" class=\"tdspace\"></td></tr>";

		// Ordner auf-/zuklappen
		if ($con_tree[$a]['idcat'] == $idcat) $tpl_cat_values['EXPAND_ANCHOR'] = "\n<a id=\"catanchor\" name=\"catanchor\"></a>\n";
		$tpl_cat_values['SPACES_BEFORE'] = $spaces;

		// Link Expand öffnen
		if ($tlo_tree['expanded'][$a] != '') $tpl_cat_values['BUTTON_CAT_EXPAND'] = make_image_link('main.php?area=con&action=expand&idcat='.$con_tree[$a]['idcat'].'&expanded=1', 'but_minus.gif', $cms_lang['con_noexpanded'],  '16', '16','','','#catanchor');

		// Link Expand schliessen
		else $tpl_cat_values['BUTTON_CAT_EXPAND'] = make_image_link('main.php?area=con&action=expand&idcat='.$con_tree[$a]['idcat'].'&expanded=0', 'but_plus.gif', $cms_lang['con_expanded'],  '16', '16','','','#catanchor');

		// Ordner konfigurieren Infotextpopup/ Link
		// konfiguriert
		if ($con_tree[$a]['tplname']) {
			//$con_catinfo = '<b>'.$cms_lang['con_template'].":</b> ".$con_tree[$a]['tplname'];
			//$folder_popup = "'$con_catinfo','".$cms_lang['con_category_information']."', 'Id: $a', 'folderinfo'";
			$folder_popup = '<span class="toolinfo"><!-- Ordner konfigurieren<br/> -->
				<table class="toolinfotablefolder" cellspacing="0" cellpadding="0" border="0">
					<tr class="headline">
						<th>'.$cms_lang['con_category_information'].'</th>
						<th align="right"><small>idcat</small> '.$a.'</th>
					</tr>
					<tr>
						<td class="first"><strong>'.$cms_lang['con_template'].'</strong></td>
						<td class="first" align="right">'.$con_tree[$a]['tplname'].'</td>
					</tr>
					<!-- <tr>
						<td><strong>Redakteur</strong></td>
						<td align="right">1</td>
					</tr> -->
				</table>
			</span>';

			// Konfigurationslink
			if($perm->have_perm(3, 'cat', $con_tree[$a]['idcat'])){
				$tpl_cat_values['BUTTON_CAT_CONFIG'] = make_image_link('main.php?area=con_configcat&idcat='.$con_tree[$a]['idcat'].'&idtplconf='.$con_tree[$a]['idtplconf'], 'but_folder_info.gif', $cms_lang['con_cat_config'], '16', '16', '', $folder_popup, '', 'toolinfo');
			}
			else{
				$tpl_cat_values['BUTTON_CAT_CONFIG'] = make_image_link('#sideanchor', 'but_folder_info.gif', $cms_lang['con_cat_config'], '16', '16', '', $folder_popup, '', 'toolinfo');
			}

		// unkonfiguriert
		} else {
			//$con_catinfo = "<b>".$cms_lang['con_template'].":</b><font color=#AF0F0F> ".$cms_lang['con_unconfigured']."</font>";
			//$folder_popup = "'$con_catinfo','".$cms_lang['con_category_information']."', 'Id: $a', 'folderinfo'";
			$folder_popup = '<span class="toolinfo"><!-- Ordner konfigurieren<br/> -->
				<table class="toolinfotablefolder" cellspacing="0" cellpadding="0" border="0">
					<tr class="headline">
						<th>'.$cms_lang['con_category_information'].'</th>
						<th align="right"><small>idcat</small> '.$a.'</th>
					</tr>
					<tr>
						<td class="first"><strong>'.$cms_lang['con_template'].'</strong></td>
						<td class="first" align="right"><font color=#AF0F0F>'.$cms_lang['con_unconfigured'].'</font></td>
					</tr>
					<!-- <tr>
						<td><strong>Redakteur</strong></td>
						<td align="right">1</td>
					</tr> -->
				</table>
			</span>';

			// Konfigurationslink
			if($perm->have_perm(3, 'cat', $con_tree[$a]['idcat'])){
				$tpl_cat_values['BUTTON_CAT_CONFIG'] = make_image_link('main.php?area=con_configcat&idcat='.$con_tree[$a]['idcat'].'&idtplconf='.$con_tree[$a]['idtplconf'], 'but_folder_off.gif', $cms_lang['con_cat_config'], '16', '16', '', $folder_popup, '', 'toolinfo');
			}
			else{
				$tpl_cat_values['BUTTON_CAT_CONFIG'] = make_image_link('#sideanchor', 'but_folder_off.gif', $cms_lang['con_cat_config'], '16', '16', '', $folder_popup, '', 'toolinfo');
			}
		}

		// Ordnername
		$tpl_cat_values['CAT_NAME'] = $con_tree[$a]['name'];
		//event folder
		$args = fire_event('con_manipulate_foldername', $con_tree[$a]);

		if (count($args) > 0) {
			$tpl_cat_values['CAT_NAME'] = $args['0']['catname'];
		}

		// Ordner: Seiten ordnen
		if ($sort) {
			if($perm->have_perm(9, 'cat', $con_tree[$a]['idcat'])){
				$qs_url = "main.php?area=con&amp;sort=true&amp;action=quicksort&amp;idcat=$a";
				$mv_url = "main.php?area=con&amp;action=movecat&amp;idcat=$a";
				$cat_actions = '<select onchange="if(this.options[this.selectedIndex].value != \'\'){window.location.href = this.options[this.selectedIndex].value}" size="1">'."\n";
				if(count($con_side[$a]) > 1) {
					$cat_actions .= '          <option value="">Quicksort...</option>'."\n";
					$cat_actions .= '          <option value="">---------------</option>'."\n";
					$cat_actions .= '          <option value="'.$sess->urlRaw($qs_url."&amp;quicksort=title:ASC").'#catanchor">'.$cms_lang['con_sidename_up'].'</option>'."\n";
					$cat_actions .= '          <option value="'.$sess->urlRaw($qs_url."&amp;quicksort=title:DESC").'#catanchor">'.$cms_lang['con_sidename_down'].'</option>'."\n";
					$cat_actions .= '          <option value="'.$sess->urlRaw($qs_url."&amp;quicksort=created:ASC").'#catanchor">'.$cms_lang['con_created_up'].'</option>'."\n";
					$cat_actions .= '          <option value="'.$sess->urlRaw($qs_url."&amp;quicksort=created:DESC").'#catanchor">'.$cms_lang['con_created_down'].'</option>'."\n";
					$cat_actions .= '          <option value="'.$sess->urlRaw($qs_url."&amp;quicksort=lastmodified:ASC").'#catanchor">'.$cms_lang['con_changed_up'].'</option>'."\n";
					$cat_actions .= '          <option value="'.$sess->urlRaw($qs_url."&amp;quicksort=lastmodified:DESC").'">'.$cms_lang['con_changed_down'].'</option>'."\n";
					$cat_actions .= '          <option value="">---------------</option>'."\n";
				}
				$cat_actions .= '                <option value="">'.$cms_lang['con_move_cat'].'</option>'."\n";
				$cat_actions .= '                <option value="">---------------</option>'."\n";
				if($catlist_level[$a] != '0' && $perm->have_perm(9, 'area_con', 0) ){
					$cat_actions .= '<option value="'.$sess->urlRaw($mv_url."&amp;target=0").'">'.$cms_lang['con_rootfolder'].'</option>'."\n";
				}
				foreach ($catmove_list as $b)
				{
					$optspaces = '';
					if($b['idcat'] == $a) {
						$hideit = 1;
						$showit = $b['level'];
					}
				    else if ($b['level'] <= $showit){
						$hideit = 0; $showit=0;
				    }
						//echo $b['name']. " reallevel: ".$b['level'] ."showit: $showit <br>";
					if($hideit != 1 && $perm->have_perm(9, 'cat', $b['idcat'])  && $b['idcat']!=$con_tree[$a]['parent'])
						$cat_actions .= '<option value="'.$sess->urlRaw($mv_url."&amp;target=".$b['idcat']). '#catanchor">'.$b['name'].'</option>'."\n";
				}
				$hideit=0; $showit=0;
				$cat_actions .= '        </select>'."\n";

				// Ordner: nach oben verschieben
				if ($con_tree[$a]['sortindex'] > 1) $cat_actions .= make_image_link('main.php?area=con&action=catupdown&dir=up&amp;sort=true&sortindex='.$con_tree[$a]['sortindex'].'&idcat='.$a.'&parent='.$con_tree[$a]['parent'], 'but_sideup.gif', $cms_lang['con_sideup'], '16', '16','','','#catanchor')." \n";
//				else $cat_actions .= "\n<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" width=\"16\" height=\"16\" alt=\"\" /> \n";

				// Ordner: nach unten verschieben
				if (end($tlo_tree[$con_tree[$a]['parent']]) != $a) $cat_actions .= make_image_link('main.php?area=con&action=catupdown&dir=down&amp;sort=true&sortindex='.$con_tree[$a]['sortindex'].'&idcat='.$a.'&parent='.$con_tree[$a]['parent'], 'but_sidedown.gif', $cms_lang['con_sidedown'], '16', '16','','','#catanchor')." \n";
//				else $cat_actions .= "\n<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" width=\"16\" height=\"16\" alt=\"\" />\n";

				// Ordner: nach ganz oben verschieben
				if ($con_tree[$a]['sortindex'] > 1) $cat_actions .= make_image_link("main.php?area=con&action=catupdown&dir=top&amp;sort=true&idcat=$a&sortindex=".$con_tree[$a]['sortindex'].'&parent='.$con_tree[$a]['parent'], 'but_sidetop.gif', $cms_lang['con_sideup'], '16', '16','','','#catanchor')." \n";
//				else $cat_actions .= "\n<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" width=\"16\" height=\"16\" alt=\"\" />\n";

				// Ordner: nach ganz unten verschieben
				if (end($tlo_tree[$con_tree[$a]['parent']]) != $a) $cat_actions .= make_image_link("main.php?area=con&action=catupdown&dir=bottom&amp;sort=true&idcat=$a&sortindex=".$con_tree[$a]['sortindex'].'&parent='.$con_tree[$a]['parent'], 'but_sidebottom.gif', $cms_lang['con_sidedown'], '16', '16','','','#catanchor')." \n";
//				else $cat_actions .= "\n<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" width=\"16\" height=\"16\" alt=\"\" />\n";
			}
		// Normale Ansicht
		} else{
			$cat_actions = '';

			// Ordner: neue Seite erstellen
			if ($con_tree[$a]['idtplconf'] != '0' && $perm -> have_perm(18, 'cat', $con_tree[$a]['idcat']) ) $tpl_cat_values['BUTTON_NEWSIDE'] = make_image_link('main.php?area=con_configside&idcat='.$con_tree[$a]['idcat'].'&idtplconf=0', 'but_newside.gif', $cms_lang['con_actions']['20'],  '16', '16');
			else $tpl_cat_values['BUTTON_NEWSIDE'] = make_image('space.gif', '',  '16', '16');

			// Ordner: anlegen/ kopieren
			if ($perm -> have_perm(2, 'cat', $con_tree[$a]['idcat']) ){
				$tpl_cat_values['BUTTON_NEWCAT'] = make_image_link('main.php?area=con_configcat&parent='.$con_tree[$a]['idcat'].'&idtplconf=0', 'but_newcat.gif', $cms_lang['con_folder_new'],  '16', '16');
				$tpl_cat_values['BUTTON_COPYCAT'] = make_image_link('main.php?area=con_copycat&idcat='.$con_tree[$a]['idcat'], 'but_copy_cat.gif', 'Ordner kopieren',  '16', '16');
			}
			else{
				$tpl_cat_values['BUTTON_COPYCAT'] = $tpl_cat_values['BUTTON_NEWCAT'] = make_image('space.gif', '',  '16', '16');
			}
			

			// Ordner: online/offline/publish schalten
			if ($perm->have_perm( 7, 'cat', $con_tree[$a]['idcat'])) {
				$tmp_link = 'main.php?area=con&action=cat_visible&idcat='.$con_tree[$a]['idcat'].'&visible='.$con_tree[$a]['visible'];
				$tmp_link2 = 'main.php?area=con&action=cat_publish&idcat='.$a;
				$tmp_descr = $cms_lang['con_folder_visible'][$con_tree[$a]['visible']];
				$tmp_descr2 = $cms_lang['con_publish'];

				if (((int)$con_tree[$a]['visible'] & 0x03) == 0x00) {
					if ($cfg_client['publish'] == '1' && $con_tree[$a]['status'] == 'true') {
						$tmp_pic = 'but_offpublish.gif';
						$tmp_descr = $tmp_descr2;
						$tmp_link = $tmp_link2;
					} else $tmp_pic = 'but_offline.gif';
				} else {
					if ($cfg_client['publish'] == '1' && $con_tree[$a]['status'] == 'true') {
						$tmp_pic = 'but_onpublish.gif';
						$tmp_descr = $tmp_descr2;
						$tmp_link = $tmp_link2;
					} else $tmp_pic = 'but_online.gif';
				}
				$tpl_cat_values['BUTTON_PUBLISH'] = make_image_link($tmp_link, $tmp_pic, $tmp_descr, '16', '16','','','#sideanchor');
				unset($tmp_link);
				unset($tmp_pic);
				unset($tmp_descr);
			} else $tpl_cat_values['BUTTON_PUBLISH'] = make_image('space.gif', '', '16', '16');

			// Ordner: löschen
			if ($perm -> have_perm(5, 'cat', $con_tree[$a]['idcat'])) $tpl_cat_values['BUTTON_DELETE'] = "<a href=\"".$sess->url('main.php?area=con&action=cat_delete&idcat='.$con_tree[$a]['idcat']). "\" onclick=\"return delete_confirm()\"><img src=\"tpl/".$cfg_cms['skin']."/img/but_deleteside.gif\" width=\"16\" height=\"16\" alt=\"".$cms_lang['con_folder_delete']."\" title=\"".$cms_lang['con_folder_delete']."\"  /></a>";
			else $tpl_cat_values['BUTTON_DELETE'] = make_image('space.gif', '', '16', '16');

			// Ordner: sperren
			if ($perm -> have_perm(8, 'cat', $con_tree[$a]['idcat'])) {
				if ($con_tree[$a]['offline']) {
					$lock_val = 0;
					$lock_icon = 'but_lock.gif';
					$lock_text = $cms_lang['con_unlock'];
				} else {
					$lock_val  = 1;
					$lock_icon = 'but_unlock.gif';
					$lock_text = $cms_lang['con_lock'];
				}
				$tpl_cat_values['BUTTON_LOCK'] = make_image_link('main.php?area=con&action=cat_lock&idcat='.$con_tree[$a]['idcat'].'&lock='.$lock_val, $lock_icon, $lock_text,  '16', '16','','','#sideanchor');
			} else $tpl_cat_values['BUTTON_LOCK'] = make_image('space.gif', '',  '16', '16');
			// Ordner: Vorschau
			$tpl_cat_values['BUTTON_PREVIEW'] = make_image_link($con_tree[$a]['link'], 'but_preview.gif', $cms_lang['con_preview'], '16', '16', '_blank');
		}

		// Tabellenfarbwerte
		if ($con_tree[$a]['offline']) {
        	$tpl_cat_values['TABLE_COLOR']     = '#FFE1CE';
            $tpl_cat_values['TABLE_OVERCOLOR'] = '#FFF5CE';
		} else {
            $tpl_cat_values['TABLE_COLOR']     = '#F0F8FF';
            $tpl_cat_values['TABLE_OVERCOLOR'] = '#FFF5CE';
		}
		$tpl_cat_values['CAT_ACTIONS'] = $cat_actions;
		unset($cat_actions);
		$spaces = $spaces . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$tpl -> setCurrentBlock('SIDES');

		// Seiten anzeigen
		if ($tlo_tree['expanded'][$a] != '' && $con_side[$a]) {
			ksort($sidelist[$a]);
			$tmp_count = count($con_side[$a]);
			$sql = "SELECT
					 D.idcatside, D.idcat, E.idside, D.is_start, D.sortindex, J.username,
					 F.created, F.lastmodified, F.title, F.summary, F.online, F.meta_redirect, F.meta_redirect_url,
					 F.idsidelang, F.idtplconf, H.name, I.changed
					FROM "       . $cms_db['cat_side'] . " D
					 LEFT JOIN " . $cms_db['side']     . " E USING(idside)
                     LEFT JOIN " . $cms_db['side_lang']. " F USING(idside)
					 LEFT JOIN " . $cms_db['tpl_conf'] . " G using(idtplconf)
					 LEFT JOIN " . $cms_db['tpl']      . " H using(idtpl)
					 LEFT JOIN " . $cms_db['code']     . " I ON D.idcatside = I.idcatside AND F.idlang=I.idlang
                     LEFT JOIN " . $cms_db['users']    . " J ON F.author=J.user_id
                    WHERE D.idcat        = $a
					 AND  E.idclient     = $client
					 AND  F.idlang       = $lang
					ORDER BY D.sortindex";
			$db->query($sql);
			$format_datetime = $cfg_cms['FormatDate'].' '.$cfg_cms['FormatTime'];
			while($db->next_record()) {
				if ( $perm->have_perm(17, 'side', $db->f('idcatside'), $db->f('idcat'))) {
						$tmp_side['idcatside']    = $db->f('idcatside');
						$tmp_side['link']         = $sess->url($cfg_client['htmlpath'].$cfg_client['contentfile']."?lang=$lang&idcatside=".$db->f('idcatside')."&view=preview");
						$tmp_side['idcat']        = $db->f('idcat');
						$tmp_side['idside']       = $db->f('idside');
						$tmp_side['is_start']     = $db->f('is_start');
						$tmp_side['author']       = $db->f('username');
						$tmp_side['created']      = date($format_datetime, $db->f('created'));
						$tmp_side['lastmodified'] = date($format_datetime, $db->f('lastmodified'));
						$tmp_side['name']         = htmlentities($db->f('title'), ENT_COMPAT, 'UTF-8');
						$tmp_side['online']       = (int) $db->f('online');
						$tmp_side['meta_redirect']= $db->f('meta_redirect');
						$tmp_side['meta_redirect_url'] = $db->f('meta_redirect_url');
						$tmp_side['summary']      = htmlentities($db->f('summary'), ENT_COMPAT, 'UTF-8');
						$tmp_side['idsidelang']   = $db->f('idsidelang');
						$tmp_side['idtplconf']    = $db->f('idtplconf');
						$tmp_side['tplname']      = htmlentities($db->f('name'), ENT_COMPAT, 'UTF-8');
						// offline ist "Seite gesperrt"
						$tmp_side['offline']      = (($db->f('online') & 0x04) == 0x04);  // change JB: ($db->f('perm') == '0');
						$tmp_side['sortindex']    = $db->f('sortindex');

						// Seite: konfigurieren
						$tplname = ($tmp_side['idtplconf'] != '0') ? $tmp_side['tplname'] : $cms_lang['gen_default'];
						$tpl_side_values['SPACES_BEFORE_SIDENAME'] = $spaces;

						// Popupinformationen Seite
						/*$con_sideinfo = '<b>'.$cms_lang['con_created'].":</b> ".$tmp_side['created']."<br><b>".$cms_lang['con_lastmodified'].":</b> ".$tmp_side['lastmodified']."<br>";

						if ($tmp_side['tplname'])
							$con_sideinfo .= "<b>".$cms_lang['con_template'].":</b> ".$tmp_side['tplname']."<br>";
						else
							$con_sideinfo .= "<b>".$cms_lang['con_template'].":</b><font color=#A8A8A8> ".$con_tree[$a]['tplname']."</font><br>";

						$con_sideinfo = $con_sideinfo."<b>".$cms_lang['con_author'].":</b> ".$tmp_side['author'];
						$con_summary = str_replace("\n", "<br>", $tmp_side['summary']);
						$con_summary = str_replace("\r", '', $con_summary);
						$con_summary = str_replace("'", "\'", $con_summary);//'
						if ($tmp_side['summary'] != '') $con_sideinfo = $con_sideinfo."<br><b>".$cms_lang['con_summary'].":</b><br>".$con_summary;
						if ($tmp_side['meta_redirect'] == '1') $con_sideinfo = $con_sideinfo."<br><b>".$cms_lang['con_metaredirect_url'].":</b><br>".$tmp_side['meta_redirect_url'];
						$side_popup = "'$con_sideinfo','".$cms_lang['con_side_information']."', 'Id: ".$tmp_side['idcatside']."', 'sideinfo'";*/
						$con_sideinfo = array();
						$con_sideinfo[0]['key'] = $cms_lang['con_created'];
						$con_sideinfo[0]['value'] = $tmp_side['created'];
						$con_sideinfo[1]['key'] = $cms_lang['con_lastmodified'];
						$con_sideinfo[1]['value'] = $tmp_side['lastmodified'];
						$con_sideinfo[2]['key'] = $cms_lang['con_template'];
						$con_sideinfo[2]['value'] = ($tmp_side['tplname']) ? $tmp_side['tplname'] : "<font color=#A8A8A8> ".$con_tree[$a]['tplname']."</font>";
						if ($tmp_side['summary'] != '')
						{
							$con_summary = str_replace("\n", "<br>", $tmp_side['summary']);
							$con_summary = str_replace("\r", '', $con_summary);
							$con_summary = str_replace("'", "\'", $con_summary);//'
							$con_sideinfo[3]['key'] = $cms_lang['con_summary'];
							$con_sideinfo[3]['value'] = $con_summary;
						}
						if ($tmp_side['meta_redirect'] == '1')
						{
							$con_sideinfo[4]['key'] = $cms_lang['con_metaredirect_url'];
							$con_sideinfo[4]['value'] = $tmp_side['meta_redirect_url'];
						}
						
						$side_popup = '<span class="toolinfo"><!-- Seite konfigurieren<br/> -->
							<table class="toolinfotableside" cellspacing="0" cellpadding="0" border="0">
								<tr class="headline">
									<th>'.$cms_lang['con_side_information'].'</th>
									<th align="right"><small>idcatside</small> '.$tmp_side['idcatside'].'</th>
								</tr>';
						foreach($con_sideinfo as $i => $tmp_sideinfo)
						{
							$class = ($i == 0) ? ' class="first"' : '';
							$side_popup .= '<tr>
									<td'.$class.'><strong>'.$tmp_sideinfo['key'].'</strong></td>
									<td'.$class.' align="right">'.$tmp_sideinfo['value'].'</td>
								</tr>';
						
						}
						$side_popup .= '</table>
						</span>';

						// Seite: konfigurieren
						if($perm->have_perm(20, 'side', $tmp_side['idcatside'], $tmp_side['idcat'])) {
							$tmp_side_idtplconf = ($tmp_side['tplname']) ? $tmp_side['idtplconf']: '0';
							if($sefrengoPDA->pageDoubletExists( (int) $tmp_side['idside']))
								$tpl_side_values['SIDECONFIG'] =  make_image_link('main.php?area=con_configside&idside='.$tmp_side['idside'].'&idcat='.$con_tree[$a]['idcat'].'&idcatside='.$tmp_side['idcatside'].'&idtplconf='.$tmp_side_idtplconf, 'but_sideinfo_doublet.gif', $cms_lang['con_actions']['30'],  '16', '16', '', $side_popup, '', 'toolinfo').' ';
							else
								$tpl_side_values['SIDECONFIG'] =  make_image_link('main.php?area=con_configside&idside='.$tmp_side['idside'].'&idcat='.$con_tree[$a]['idcat'].'&idcatside='.$tmp_side['idcatside'].'&idtplconf='.$tmp_side_idtplconf, 'but_sideinfo.gif', $cms_lang['con_actions']['30'],  '16', '16', '', $side_popup, '', 'toolinfo').' ';
						} else {
							if($sefrengoPDA->pageDoubletExists( (int) $tmp_side['idside']))
								$tpl_side_values['SIDECONFIG'] = make_image_link('#sideanchor', 'but_sideinfo_doublet.gif', $cms_lang['con_actions']['30'], '16', '16', '', $side_popup, '', 'toolinfo').'&nbsp;';
							else
								$tpl_side_values['SIDECONFIG'] = make_image_link('#sideanchor', 'but_sideinfo.gif', $cms_lang['con_actions']['30'], '16', '16', '', $side_popup, '', 'toolinfo').'&nbsp;';
						}

						// Seite: bearbeiten
						$tpl_side_values['SIDENAME'] =   "<a href=\"".$sess->url('main.php?area=con_editframe&idcatside='.$tmp_side['idcatside'])."\" class=\"action\"  title=\"".$cms_lang['con_editside']."\">".$tmp_side['name']."</a>";

						// Anker
						if ($tmp_side['idcatside'] == $idcatside || $tmp_side['idside'] == $idside) $tpl_side_values['SIDEANCHOR'] =   "\n<a name=\"sideanchor\" id=\"sideanchor\"></a>\n";

						// Seite: Aktionen
						// Sortieransicht
						if ($sort) {
							if($perm->have_perm(25, 'side', $tmp_side['idcatside'], $tmp_side['idcat'])){
								$sort_actions = '';

								// Seite: nach oben verschieben
								if ($sidelist[$a]['1'] != $tmp_side['idcatside']) $sort_actions .= make_image_link('main.php?area=con&action=sideup&amp;sort=true&sortindex='.$tmp_side['sortindex'].'&idcat='.$tmp_side['idcat'].'&idside='.$tmp_side['idside'].'&idside='.$tmp_side['idside'], 'but_sideup.gif', $cms_lang['con_sideup'], '16', '16','','','#catanchor')."\n";
								elseif ($tmp_count > 1) $sort_actions .= "<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" width=\"16\" height=\"16\" alt=\"\" /> \n";

								// Seite: nach unten verschieben
								if (end($sidelist[$a]) != $tmp_side['idcatside']) $sort_actions .= make_image_link('main.php?area=con&action=sidedown&amp;sort=true&sortindex='.$tmp_side['sortindex'].'&idcat='.$tmp_side['idcat'].'&idside='.$tmp_side['idside'], 'but_sidedown.gif', $cms_lang['con_sidedown'], '16', '16','','','#catanchor')."\n";
								elseif ($tmp_count > 1) $sort_actions .= "<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" width=\"16\" height=\"16\" alt=\"\" /> \n";

								// Seite: nach ganz oben verschieben
								if ($sidelist[$a]['1'] != $tmp_side['idcatside']) $sort_actions .= make_image_link("main.php?area=con&action=sidetop&amp;sort=true&idcat=$a&idcatside=".$tmp_side['idcatside']."&sortindex=".$tmp_side['sortindex'], 'but_sidetop.gif', $cms_lang['con_sideup'], '16', '16','','','#catanchor')."\n";
								elseif ($tmp_count > 1) $sort_actions .= "<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" width=\"16\" height=\"16\" alt=\"\" />\n";

								// Seite: nach ganz unten verschieben
								if (end($sidelist[$a]) != $tmp_side['idcatside']) $sort_actions .= make_image_link("main.php?area=con&action=sidebottom&amp;sort=true&idcat=$a&idcatside=".$tmp_side['idcatside']."&sortindex=".$tmp_side['sortindex'], 'but_sidebottom.gif', $cms_lang['con_sidedown'], '16', '16','','','#catanchor')." \n";
								elseif ($tmp_count > 1) $sort_actions .= "<img src=\"tpl/".$cfg_cms['skin']."/img/space.gif\" width=\"16\" height=\"16\" alt=\"\" />\n";

								$tpl_side_values['SIDE_ACTIONS'] = $sort_actions;
								unset($sort_actions);
							}
						// Standardansicht
						} else {
							$side_actions = '';

							// Seite: Startseite festlegen
							if($perm->have_perm(28, 'side', $tmp_side['idcatside'], $tmp_side['idcat'])) {
								if ($tmp_side['is_start'] == '1') $tpl_side_values['BUTTON_STARTPAGE'] = "<img src=\"tpl/".$cfg_cms['skin']."/img/but_start_yes.gif\" alt=\"".$cms_lang['con_actions']['10'][$tmp_side['is_start']]."\" title=\"".$cms_lang['con_actions']['10'][$tmp_side['is_start']]."\" width=\"16\" height=\"16\" />";
								else $tpl_side_values['BUTTON_STARTPAGE'] = make_image_link('main.php?area=con&action=side_start&idcatside='.$tmp_side['idcatside'].'&is_start='.$tmp_side['is_start'], 'but_start_no.gif', $cms_lang['con_actions']['10'][$tmp_side['is_start']],  '16', '16','','','#sideanchor');
							}

							// Seite: online/offline/publish schalten
							if ($perm->have_perm(23, 'side', $tmp_side['idcatside'], $tmp_side['idcat'])) {
								$tmp_link   = 'main.php?area=con&action=side_visible&idside=' . $tmp_side['idside'] . '&idcat=' . $tmp_side['idcat'] . '&online=' . $tmp_side['online'];
								$tmp_link2  = 'main.php?area=con&action=side_publish&idcatside='.$tmp_side['idcatside'].'&idcat='.$a;
								$tmp_descr  = $cms_lang['con_side_visible'][$tmp_side['online']];
								$tmp_descr2 = $cms_lang['con_publish'];

								if (($tmp_side['online'] & 0x03) == 0x00) {
									// ist geschützt,
									if ($cfg_client['publish'] == '1' && $con_side[$a][$tmp_side['idcatside']]['status'] == 'true') {
										$tmp_pic = 'but_offpublish.gif';
										$tmp_descr = $tmp_descr2;
										$tmp_link = $tmp_link2;
									} else $tmp_pic = 'but_offline.gif';
								} else {
									// online oder zeitgesteuert
									if (((int)$con_tree[$a]['visible'] & 0x03) == 0x00) {
										if ($cfg_client['publish'] == '1' && $con_side[$a][$tmp_side['idcatside']]['status'] == 'true') {
											$tmp_pic = 'but_offpublish.gif';
											$tmp_descr = $tmp_descr2;
											$tmp_link = $tmp_link2;
										} else $tmp_pic = 'but_onoffline.gif';
									} else {
										if ($cfg_client['publish'] == '1' && $con_side[$a][$tmp_side['idcatside']]['status'] == 'true') {
											$tmp_pic = 'but_onpublish.gif';
											$tmp_descr = $tmp_descr2;
											$tmp_link = $tmp_link2;
										} else {
					                 		$tmp_pic = (((int)$tmp_side['online'] & 0x02) == 0x02) ? 'but_time.gif': 'but_online.gif';
										}
									}
								}
								$tpl_side_values['BUTTON_PUBLISH'] = make_image_link($tmp_link, $tmp_pic, $tmp_descr, '16', '16','','','#sideanchor');
								unset($tmp_link);
								unset($tmp_pic);
								unset($tmp_description);
							} else $tpl_side_values['BUTTON_PUBLISH'] = make_image('space.gif', '', '16', '16');

							// Seite: bearbeiten
							if($perm->have_perm(19, 'side', $tmp_side['idcatside'], $tmp_side['idcat'])) {
								$tpl_side_values['BUTTON_EDIT'] = make_image_link('main.php?area=con_editframe&idcatside='.$tmp_side['idcatside'], 'but_edit.gif', $cms_lang['con_editside'],  '16', '16','','','#sideanchor');
							} else {
								$tpl_side_values['BUTTON_EDIT'] = make_image('space.gif', '',  '16', '16');
							}

							//Seite kopieren
							if( $perm->have_perm(18, 'cat', $tmp_side['idcat']) ) {
								$tpl_side_values['BUTTON_COPY'] = make_image_link('main.php?area=con_copyside&idcatside='.$tmp_side['idcatside'].'&idcat='.$tmp_side['idcat'], 'but_duplicate.gif', 'Seite kopieren',  '16', '16','','','#sideanchor');
							} else {
								$tpl_side_values['BUTTON_COPY'] = make_image('space.gif', '',  '16', '16');
							}


							// Seite: löschen
							if($perm -> have_perm(21, 'side', $tmp_side['idcatside'], $tmp_side['idcat']))
								if($sefrengoPDA->pageDoubletExists( (int) $tmp_side['idside']))
									$tpl_side_values['BUTTON_DELETE'] = "<a href=\"".$sess->url('main.php?action=side_delete&idcat='.$tmp_side['idcat'].'&idside='.$tmp_side['idside']). "#catanchor\" onclick='return confirm(\"Achtung! Diese Seite existiert als Kopie/ Klone in mehreren Kategorien! Wirklich löschen?\")'><img src=\"tpl/".$cfg_cms['skin']."/img/but_deleteside.gif\" width=\"16\" height=\"16\" alt=\"".$cms_lang['con_actions']['40']."\" title=\"".$cms_lang['con_actions']['40']."\" /></a>";
								else
									$tpl_side_values['BUTTON_DELETE'] = "<a href=\"".$sess->url('main.php?action=side_delete&idcat='.$tmp_side['idcat'].'&idside='.$tmp_side['idside']). "#catanchor\" onclick=\"return delete_confirm()\"><img src=\"tpl/".$cfg_cms['skin']."/img/but_deleteside.gif\" width=\"16\" height=\"16\" alt=\"".$cms_lang['con_actions']['40']."\" title=\"".$cms_lang['con_actions']['40']."\" /></a>";
							else $tpl_side_values['BUTTON_DELETE'] = make_image('space.gif', '', '16', '16');

							// Seite: schützen
							if ($perm -> have_perm(24, 'side', $tmp_side['idcatside'], $tmp_side['idcat'])) {
				                if ($tmp_side['offline']) {
									$lock_val = 0;
									$lock_icon = 'but_lock.gif';
									$lock_text = $cms_lang['con_unlock_side'];
								} else {
									$lock_val  = 1;
									$lock_icon = 'but_unlock.gif';
									$lock_text = $cms_lang['con_lock_side'];
								}
								$tpl_side_values['BUTTON_LOCK'] = make_image_link('main.php?action=side_lock&idcatside='.$tmp_side['idcatside'].'&idside='.$tmp_side['idside'].'&lock='.$lock_val, $lock_icon, $lock_text,  '16', '16','','','#sideanchor');
							} else {
								$tpl_side_values['BUTTON_LOCK'] = make_image('space.gif', '',  '16', '16');
							}

							// Seite: Vorschau
							$tpl_side_values['BUTTON_PREVIEW'] = make_image_link($tmp_side['link'], 'but_preview.gif', $cms_lang['con_preview'], '16', '16', '_blank');
							$tpl_side_values['SIDE_ACTIONS'] = '';
							unset($side_actions);
						}

						// Tabellenfarbwerte
						if ($con_tree[$a]['offline'] || $tmp_side['offline']) {
							$tpl_side_values['TABLE_COLOR'] = '#FFEEDF';
							$tpl_side_values['TABLE_OVERCOLOR'] = '#FFF5CE';
						} else {
							$tpl_side_values['TABLE_COLOR'] = '#FFFFFF';
							$tpl_side_values['TABLE_OVERCOLOR'] = '#FFF5CE';
						}
						// Seitentemplate parsen
						$tpl->setVariable($tpl_side_values);
						$tpl->parse('SIDES');
						unset($tpl_side_values, $tmp_side);
					}
				}
			}

		// Ordnertemplate parsen
		$tpl -> setCurrentBlock('TREE');
		$tpl->setVariable($tpl_cat_values);
		$tpl->parse('TREE');
		unset($tpl_cat_values);
	}

// Es gibt keine Ordner
} else {
	$tpl -> setCurrentBlock('EMPTY');
	$tpl_empty_values['LANG_NOCATS'] = $cms_lang['con_nofolder'];
	$tpl->setVariable($tpl_empty_values);
	$tpl->parse('EMPTY');
	unset($tpl_empty_values);
}
?>
