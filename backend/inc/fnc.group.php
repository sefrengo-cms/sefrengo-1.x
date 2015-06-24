<?PHP
// File: $Id: fnc.group.php 28 2008-05-11 19:18:49Z mistral $
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

function group_set_active($is_active) {
	global $adb, $cms_db, $idgroup;
	$adb->AutoExecute($cms_db['groups'], array('is_active' => (bool)$is_active), 'UPDATE', "idgroup = ".(int)$idgroup);
}

function group_delete() {
	global $adb, $cms_db, $idgroup, $perm;

	// Systemadmin kann nicht gel�scht werden
	if ($idgroup >= 3) {
		$sql = "DELETE FROM ".$cms_db['groups']." WHERE idgroup=?";
		$adb->Execute($sql, array($idgroup));
		$perm->delete_perms_by_group($idgroup, '-1');
	}
}

function group_save() {
	global $cms_db, $adb, $idgroup, $name, $description, $oldname;

	// Kein Gruppenname
	if (trim($name) == '') return 'group_noname';

	// keine Sonderzeichen in Gruppenname
	if (!eregi("[0-9a-zA-Z]", $name)) return 'group_incorrectcharacter';

	// check existency
	if ($name != $oldname) {
		$sql = "SELECT idgroup FROM ".$cms_db['groups']." WHERE name=? LIMIT 0, 1";
		$rs = $adb->Execute($sql, array($name));
		while (!$rs->EOF) {
			if($rs->fields['idgroup'] != $idgroup)
				return 'group_existname';
			$rs->MoveNext();
		}
		$rs->Close();
	}
	$record = array('name' => $name, 'description' => $description);
	if ($idgroup != '') {
		$adb->AutoExecute($cms_db['groups'], $record, 'UPDATE', "idgroup = ".$idgroup);
	}
	else {
		$record['is_sys_admin'] = 0;
		$record['is_active'] = 1;
		$record['is_deletable'] = 1;
		$adb->AutoExecute($cms_db['groups'], $record, 'INSERT');
		$idgroup = $adb->Insert_ID();
	}
}

// jb_todo: workflow check, wegen l�schen der rechte
function group_visible_lang() {
	global $cms_db, $adb, $idgroup, $idlang, $perm;

	// group look up
	$sql = "SELECT idgroup FROM ".$cms_db['perms']." WHERE idgroup=? AND type=? AND id=? LIMIT 0, 1";
	$rs = $adb->Execute($sql, array($idgroup, 'lang', $idlang));

	// add or delete perms
	if ($rs === FALSE || $rs->EOF) {
		$perm->new_perm( $idgroup, 'lang', $idlang, 1, 0);
	} else {
		$perm->delete_perms_by_group($idgroup, $idlang);
		$perm->delete_perms($idlang, 'lang', $idgroup);
	}
	$rs->Close();
}

function reindex_sort() {
	global $ssort, $adb, $cms_db, $reindex;

	foreach (array_keys($reindex) as $kat) {
		ksort($ssort[$kat]);
		$index = 0;
		foreach ($ssort[$kat] as $idcatside) {
			$index++;
			$sql = "UPDATE ".$cms_db['cat_side']." SET sortindex=? WHERE idcatside=?;";
			$adb->Execute($sql, array($index, $idcatside));
		}
	}

	// Navigationstree aus Cache l�schen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'tree'));
}

//
// Funktionen zur Verwaltung von Gruppenrechten
//

function group_reset_existing_perms($idgroup, $idlang, $types_to_clean) {
	global $cms_db, $adb;
	
	$idgroup = (int) $idgroup;
	$idlang = (int) $idlang;
	if ($idgroup < 1 || $idlang < 1 || ! is_array($types_to_clean)) {
		return false;
	}
	foreach ($types_to_clean AS $k => $v) {
		$types_to_clean[$k] = addslashes($v);
	}
	$types = "'".implode("', '", $types_to_clean)."'";
	$sql = "DELETE FROM 
				".$cms_db['perms']."
			WHERE 
				idgroup = ?
				AND idlang = ?
				AND type IN ($types)
				AND id != '0'";
	$adb->Execute($sql, array($idgroup, $idlang));
	return true;
}

function group_save_perms() {
	global $cms_perm_val, $adb, $cms_db, $idgroup, $idlang, $val_ct;

	$array      = $cms_perm_val['cms_access'];
	
	//print_r($sf_overwrite_existing_perms);
	//rechte zur�cksetzen
	$overwrite_existing_perms = $_POST['sf_overwrite_existing_perms'];
	if (is_array($overwrite_existing_perms)) {
		$perm_meta = $val_ct->get_by_group('user_perms');
		//print_r($perm_meta);
		foreach ($overwrite_existing_perms AS $the_area=>$v) {
			$clean_up = array();
			array_push($clean_up, addslashes($the_area));
			if ($perm_meta['cms_access'][$the_area] != '') {
				$subareas = explode(',', $perm_meta['cms_access'][$the_area]);
				foreach ($subareas AS $singlesub) {
					array_push($clean_up, $singlesub);
				}
			}
			group_reset_existing_perms($idgroup, $idlang, $clean_up);
		}
		
	}

	// zuerst cms_access und bereiche speichern
	$sql_array = array();
	foreach ($array as $key => $value) {
		// f�r die �bermittlung durch den browser muss der Punkt in der Versionsnummer durch einen unterstrich ersetzt werden
		// dies ist bei der ermittlung des neuen perm-wertes zu ber�cksichtigen ... die eintragung selbst erfolgt mit dem Punkt
		// und wird bei der Abfrage von Rechte auch mit der Punktnotation durchgef�hrt
		$perm_area = str_replace('.', '_', $key);
		$perm_val = $GLOBALS['cms_access_'.$perm_area];
		$sql = "SELECT idperm FROM ". $cms_db['perms'] ." WHERE idgroup=? AND idlang=? AND type='cms_access' AND id=?";
		$rs = $adb->Execute($sql, array($idgroup, $idlang, $key));
		$idperm = ($rs == FALSE || $rs->EOF) ? '' : $rs->fields['idperm'];
		$rs->Close();
		if (!empty($idperm)) {
			array_push($sql_array, _update_or_delete_perm($perm_val, $idperm));
		} else if($perm_val > 0) {
			array_push($sql_array, _insert_perm($perm_val, $idgroup, $idlang, 'cms_access', $key));
		}
		// Perms der Bereiche speichern
		$perm_val = 0;
		if (isset($cms_perm_val[$key])) {
			foreach($cms_perm_val[$key] AS $key2 => $value2) {
				$perm_val |= $GLOBALS[$perm_area."_".$key2];
			}
		}
		$sql = "SELECT idperm FROM ". $cms_db['perms'] ." WHERE idgroup=? AND idlang=? AND type=? AND id='0'";
		$rs = $adb->Execute($sql, array($idgroup, $idlang, $key));
		$idperm = ($rs == FALSE || $rs->EOF) ? '' : $rs->fields['idperm'];
		$rs->Close();
		if (!empty($idperm)) {
			array_push($sql_array, _update_or_delete_perm($perm_val,  $idperm));
		} else if($perm_val > 0) {
			array_push($sql_array, _insert_perm($perm_val, $idgroup, $idlang, $key, '0'));
		}
	}
	// f�hre alle sqls gesammelt durch
	_do_sql_queries($sql_array);
}

// helper function
function _do_sql_queries(&$sql_array ) {
	global $adb;
	$max = count($sql_array);
	for($i = 0; $i < $max; $i++) {
		$adb->Execute($sql_array[$i]);
	}
}

function _insert_perm( $perm, $idgroup, $idlang, $type, $key) {
	global $cms_db;
	return "INSERT INTO ". $cms_db['perms'] ." VALUES('', '$idgroup', '$idlang',  '$type', '$key', '$perm')";
}

function _update_or_delete_perm( $perm, $idperm) {
	global $cms_db;
	// Rechte aktualisieren
	if ($perm > 0) {
		return "UPDATE ". $cms_db['perms'] ." SET perm = '$perm' WHERE idperm = " . $idperm;

	// Rechte l�schen
	} else {
		return 'DELETE FROM ' . $cms_db['perms'] . ' WHERE idperm = ' . $idperm;
	}
}


// function create_area_checkbox($area, $name, $lang_name) {
function create_area_checkbox($area, $name, $lang_name, $plugin) {
	global $cms_perm_val, $tmp, $cms_lang, $perm, $sim_perm, $tpl;

	unset($tmp);

	$checkbox_cell = "<td width=\"14\" title=\"%s\"><input type=\"checkbox\" name=\"%s_%s\" id=\"%s_%s\" value=\"%s\" %s /></td>";
	$checkbox_cell_overwrite_ex = "<td width=\"14\" bgcolor=\"#EBD5C7\" title=\"%s\"><input type=\"checkbox\" name=\"sf_overwrite_existing_perms[%s]\" id=\"sf_overwrite_existing_perms[%s]\"  value=\"yes\" /></td>";

	$box_off       = "<th class=\"content6\" width=\"12\" title=\"%s\"><input type=\"radio\" name=\"cms_access_%s\" value=\"%s\" %s onclick=\"cms_rm.set_area('%s', %s, %s)\" /></th>";
	$box_on        = "<th class=\"content6\" width=\"12\" title=\"%s\"><input type=\"radio\" name=\"cms_access_%s\" value=\"%s\" %s onclick=\"cms_rm.set_area('%s', %s, %s)\" /></th>";

	$i   = 0;
	$max = 0;
	// f�r die �bermittlung durch den browser muss der Punkt in der Versionsnummer durch einen unterstrich ersetzt werden
	// dies ist bei der ermittlung des neuen perm-wertes zu ber�cksichtigen ... die eintragung selbst erfolgt mit dem Punkt
	// und wird bei der Abfrage von Rechte auch mit der Punktnotation durchgef�hrt
	$area_name  = ($plugin) ? str_replace('.', '_', $area): $area;
	if (!empty($cms_perm_val[$area])) {
		$tpl->setCurrentBlock('MAIN_RIGHTS_ROW');
		$max = count($cms_perm_val[$area]);
		if (isset($max) && $max > 0) {
			foreach($cms_perm_val[$area] AS $key2 => $value) {
				$tooltip    = $cms_lang[$lang_name.'_'.$key2];
				$checkvalue = $cms_perm_val[$area][$key2];
				$checked    = $sim_perm->get_checkbox_status($area, '0', 9, $checkvalue);
				$tmp['PERM_LABEL'] = $tooltip;
				$tmp['PERM_LABEL_FOR'] = $area_name .'_'. $key2;
				$tmp['PERM_BUTTON'] = sprintf($checkbox_cell, $tooltip, $area_name, $key2, $area_name, $key2, $checkvalue, $checked);
				$tpl->setVariable($tmp);
				$tpl->parseCurrentBlock();
				unset($tmp);
			}
			//overwrite perms
			$tmp['PERM_LABEL'] = '<i>Vorhandene Rechte zur&uuml;cksetzen</i>';
			$tmp['PERM_LABEL_FOR'] = "sf_overwrite_existing_perms[$area_name]";
			$tmp['PERM_BUTTON'] = sprintf($checkbox_cell_overwrite_ex, $tooltip, $area_name, $area_name);
			$tpl->setVariable($tmp);
			$tpl->parseCurrentBlock();
			unset($tmp);
		}
	}

	$tpl->setCurrentBlock('MAIN_RIGHTS');

	$area_on_checked  = $sim_perm->get_checkbox_status('cms_access', $area, 8, '1');
	$area_off_checked = ($area_on_checked == '') ? ' checked ': '';
	$tooltip = $cms_lang['group_access_area_granted'];
	$tmp['PERM_AREA_ON']  = sprintf($box_on, $tooltip, $area_name,  '1', $area_on_checked, $area_name, $max, 'true');
	$tooltip = $cms_lang['group_access_area_denied'];
	$tmp['PERM_AREA_OFF'] = sprintf($box_off, $tooltip, $area_name, '-1', $area_off_checked, $area_name, $max, 'false');
	$tmp['PERM_AREA']      = $name;
	$tpl->setVariable($tmp);
	$tpl->parseCurrentBlock();
	unset($tmp);

}

?>
