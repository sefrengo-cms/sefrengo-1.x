<?php
// File: $Id: fnc.con.php 52 2008-07-20 16:16:33Z bjoern $
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

function con_delete_cache($idlang) {
	global $cms_db, $db, $perm;
    
	$sql = "DELETE FROM ".$cms_db['code']." WHERE idlang='$idlang'";
	$db->query($sql);
    // Cache löschen
    sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushAll'); 
}

function con_deeper_categories($idcat_start, $check_perm) {
	global $tlo_tree, $deeper_list, $client, $cms_db, $db, $perm;

	if (!$tlo_tree) {
		$sql = "SELECT idcat, parent, sortindex FROM $cms_db[cat] WHERE idclient='$client' ORDER BY parent, sortindex";
		$db->query($sql);
		while ($db->next_record()) {
			$tlo_tree[$db->f('parent')][$db->f('sortindex')] = $db->f('idcat');
        }
	}

	for ($i=1; !empty($tlo_tree[$idcat_start][$i]); $i++) {
		if ($perm -> have_perm($check_perm, 'cat', $tlo_tree[$idcat_start][$i])) {
			$deeper_list[] = $tlo_tree[$idcat_start][$i];
		}
		con_deeper_categories($tlo_tree[$idcat_start][$i], $check_perm);
	}
	return $deeper_list;
}

function con_visible_cat ($idcat, $lang = '', $visible) {
	global $db, $cms_db;

	$a_catstring = array();
   	$a_catstring = con_deeper_categories($idcat, '7');
	$a_catstring[] = $idcat;
	$sql  = "UPDATE $cms_db[cat_lang] SET visible='$visible' WHERE idcat IN (". implode(',', $a_catstring) . ")";
	$sql .= (!empty($lang)) ? " AND idlang='$lang'": '';
	$db->query($sql);

	// Navigationstree aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
}


function con_lock ($type, $id, $visible) {
	global $db, $cms_db, $client, $perm;

	switch ($type) {
		// Ordner sperren
		case 'cat':
			$a_catstring = array();
			$a_catstring = con_deeper_categories($id, '13');
			$a_catstring[] = $id;

			// entsperren / sperren aller Ordner unabhängig von der Sprache
		    	if ($visible == '1') $sql  = "UPDATE $cms_db[cat_lang] SET visible = (visible | 0x04) WHERE idcat IN (" . implode(',', $a_catstring) . ")";
			else $sql  = "UPDATE $cms_db[cat_lang] SET visible = (visible & 0xFB) WHERE idcat IN (" . implode(',', $a_catstring) . ")";
			$db->query($sql);

			$sql = "SELECT S.idside
				FROM ". $cms_db['side'] ." S
				inner join ". $cms_db['cat_side'] ." CS USING(idside)
				WHERE idcat IN (" . implode(',', $a_catstring) . ")";
			$db->query($sql);
			while ($db->next_record()) $sides[] = $db->f("idside");

			//prüfen ob sides vorhanden, bei leerer Kategorie nicht der fall
			if (is_array($sides)) con_lock('side', $sides, $visible);
			break;

		// Seite sperren
		case 'side':
            $sides_sql = (is_array($id)) ? ' IN (' . implode(',', $id) . ')': ' = ' . $id;

			// entsperren / sperren aller Seiten unabhängig von der Sprache
            if ($visible == '1') $sql  = "UPDATE $cms_db[side_lang] SET online = (online | 0x04) WHERE idside " . $sides_sql;
			else $sql  = "UPDATE $cms_db[side_lang] SET online = (online & 0xFB) WHERE idside " . $sides_sql;
			$db->query($sql);
            break;
	}

	// Navigationstree aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
}

function con_delete_cat ($idcat) {
	global $db, $cms_db, $auth, $client, $perm;

	// gibt es noch Unterordner?
	$sql = "SELECT * FROM $cms_db[cat] WHERE parent='$idcat'";
	$db->query($sql);
	if ($db->next_record()) return '0201';
	else {
		// gibt es noch Seiten in diesem Ordner?
		$sql = "SELECT * FROM $cms_db[cat_side] WHERE idcat='$idcat'";
		$db->query($sql);
		if ($db->next_record()) return '0202';
		else {
			// Parentid raussuchen zum neusortieren der Ordner
			$sql = "SELECT parent, sortindex FROM $cms_db[cat] WHERE idcat='$idcat'";
			$db->query($sql);
			$db->next_record();
			$parent = $db->f('parent');
			$sortindex = $db->f('sortindex');

            // Ordner löschen
            $sql = "DELETE FROM $cms_db[cat] WHERE idcat='$idcat'";
			$db->query($sql);

            // expand löschen
			$sql = "DELETE FROM $cms_db[cat_expand] WHERE idusers = '".$auth->auth['uid']."' AND idcat = '$idcat'";
            $db->query($sql);

            // die anderen Ordner neu sortieren
			$sql = "UPDATE $cms_db[cat] SET sortindex=sortindex-1 WHERE parent='$parent' AND sortindex>'$sortindex' AND idclient='$client'";
			$db->query($sql);

			// Liste der benutzten Templates erstellen
			$sql = "SELECT idtplconf FROM $cms_db[cat_lang] WHERE idcat='$idcat'";
			$db->query($sql);
			while ($db->next_record()) if ($db->f('idtplconf') != '0') $idtplconf[] = $db->f('idtplconf');

			// Einträge aus der cat_lang Tabelle löschen
            $sql = "DELETE FROM $cms_db[cat_lang] WHERE idcat='$idcat'";
			$db->query($sql);

			// Rechte löschen
			$perm->delete_perms($idcat, 'cat', 0, 0, 0, true);

			// benutzte Templates löschen
			if (is_array($idtplconf)) {
				foreach ($idtplconf as $value) {
					if ($value != '0') {
						// Templatekonfiguration löschen
                        $sql = "DELETE FROM $cms_db[container_conf] WHERE idtplconf='$value'";
						$db->query($sql);

						// Templatekopie löschen
                        $sql = "DELETE FROM $cms_db[tpl_conf] WHERE idtplconf='$value'";
						$db->query($sql);
                    }
                }
            }
			
			//reindex cat
			reindex_cat_sort($parent, (int) $client, 'parent');
			
			// Cache-Group Frontend löschen
			sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
		}
	}
}

// change JB - sprache ist nicht mehr Pflicht ... für sperren von Seiten notwendig
function con_visible_side ($idside, $lang, $online) {
	global $db, $cms_db;

	$sql  = "UPDATE $cms_db[side_lang] SET online = $online WHERE idside='$idside'";
	$sql .= ($lang >= 0) ? " AND idlang='$lang'": '';
	$db->query($sql);

	// Cache-Group Frontend löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
}

function con_delete_side ($idcat, $idside) {
	global $db, $cms_db, $perm;

	$sql = "SELECT idcatside FROM $cms_db[cat_side] WHERE idside='$idside'";
	$db->query($sql);
	while ($db->next_record()){
		 $temp_idcatsides = $db->f('idcatside');
		 $idcatsides[] = $temp_idcatsides;
		 $perm-> check(21, 'side', $temp_idcatsides, $idcat);
	}

	// Event
	fire_event('delete_side', array ('idside' => $idside, 'idcat' => $idcat, 'idcatside' => $idcatsides));

	// aus 'code'-Tabelle löschen
	if (is_array($idcatsides)) {
		$sql = "DELETE FROM ". $cms_db['code'] ." WHERE idcatside IN (". implode(',', $idcatsides).")";
		$db->query($sql);

		// Rechte löschen
		$perm->delete_perms($idcatsides, 'side', 0, 0, 0, true);
	}
	$sql = "SELECT idsidelang, idtplconf FROM $cms_db[side_lang] WHERE idside='$idside'";
	$db->query($sql);
	while ($db->next_record()) $idsidelang[$db->f('idsidelang')] = $db->f('idtplconf');
	if (is_array($idsidelang)) {
		foreach ($idsidelang AS $key => $value) {
			// Inhalt aus 'content'-Tabelle löschen
			$sql = "DELETE FROM $cms_db[content] WHERE idsidelang='$key'";
			$db->query($sql);

			// besitzt die Seite ein eigenes Template?
			if ($value != '0') {
				// Templatekonfiguation löschen
				$sql = "DELETE FROM $cms_db[container_conf] WHERE idtplconf='$value'";
				$db->query($sql);

				// Templatekopie löschen
				$sql = "DELETE FROM $cms_db[tpl_conf] WHERE idtplconf='$value'";
				$db->query($sql);
            }

			// Links, die auf diese Seite zeigen löschen
			// Seitenstatus dieser Seiten auf geändert stellen
            // muß noch gemacht werden
		}
	}

	// Seite aus den Ordnern löschen
	$sql = "DELETE FROM $cms_db[cat_side] WHERE idside='$idside'";
	$db->query($sql);

	// Seite löschen
	$sql = "DELETE FROM $cms_db[side] WHERE idside='$idside'";
	$db->query($sql);

	// Seite aus den verschiedenen Sprachen löschen
	$sql = "DELETE FROM $cms_db[side_lang] WHERE idside='$idside'";
	$db->query($sql);

	// restliche Seiten neu sortieren
	con_reindex_page_sort($idcat);

	// neue Startseite festlegen
	$sql = "SELECT * FROM $cms_db[cat_side] WHERE idcat='$idcat' AND is_start='1'";
	$db->query($sql);
	if (!$db->affected_rows()) {
		$sql = "UPDATE $cms_db[cat_side] SET is_start='1' WHERE idcat='$idcat' AND sortindex='1'";
		$db->query($sql);
    }

	// Cache-Group Frontend löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
}

function con_expand ($idcat, $expand_action) {
	global $db, $perm, $client, $DB_cms, $cms_db, $auth;

	$user_id = $auth->auth['uid'];
	switch($expand_action) {
		// Ordner öffnen
		case '0':
			$sql = "INSERT INTO $cms_db[cat_expand] (idusers, idcat) VALUES ('$user_id', '$idcat')";
            $db->query($sql);
            break;

	 	// Ordner schließen
	 	case '1':
			$sql = "DELETE FROM $cms_db[cat_expand] WHERE idusers = '$user_id' AND idcat = '$idcat'";
            $db->query($sql);
            break;

		// gesamten Ordner schließen
		case '2':
			$list = con_deeper_categories($idcat, '0');
			if (is_array($list)) array_push ($list, $idcat);
            else $list[] = $idcat;
			$sql = "DELETE FROM $cms_db[cat_expand] WHERE idusers = '$user_id' AND idcat IN(".implode(',', $list).")";
            $db->query($sql);
            break;

		// gesamten Ordner öffnen
		case '3':
			$list = con_deeper_categories($idcat, '0');
			if (is_array($list)) array_push ($list, $idcat);
            else $list[] = $idcat;

			// alles löschen, damit doppelte Datensätze vermieden werden
			$sql = "DELETE FROM $cms_db[cat_expand] WHERE idusers = '$user_id' AND idcat IN(".implode(',', $list).")";
            $db->query($sql);

			foreach($list as $value) {
				if ($value != '0' && $perm->have_perm('0', 'cat', $value)) {
					$sql = "INSERT INTO $cms_db[cat_expand] (idusers, idcat) VALUES ('$user_id', '$value')";
                    $db->query($sql);
                }
			}
            break;
	}
}

function con_publish ($id, $type='all') {
	global $db, $cms_db, $con_side, $con_tree, $perm, $idcat, $lang, $view, $sess, $idcatside, $cfg_client;

	switch($type) {
		// diese Ordner publizieren
		case 'all':
	        	$tmp_list = con_deeper_categories($id, '7');
			if (is_array($tmp_list)) array_push ($tmp_list, $id);
			else $tmp_list[] = $id;
			foreach ($tmp_list as $id) {
				if (is_array($con_side[$id])) {
					foreach($con_side[$id] as $side) {
						if($perm->have_perm(23, 'side', $side['idcatside'], $idcat)) {
                                                 	$list[] = $side['idcatside'];
							unset($con_tree[$id]['status']);
							unset($con_side[$id][$side['idcatside']]['status']);
                                                 }
                                         }
                                 }
			}
			break;

		// Seite publizieren
		case 'side':
			if($perm->have_perm(23, 'side', $id, $idcat)) {
				$list[] = $id;
				unset($con_tree[$idcat]['status']);
				unset($con_side[$idcat][$id]['status']);
				$new_search = 'true';
			}
			break;

		// alle Seiten dieses Ordners publizieren
		case 'cat':
			if (is_array($con_side[$id])) {
				foreach($con_side[$id] as $side) {
					if ($perm->have_perm(23, 'side', $side['idcatside'], $idcat)) {
                        $list[] = $side['idcatside'];
						unset($con_tree[$id]['status']);
						unset($con_side[$id][$side['idcatside']]['status']);
                    }
				}
			}
			break;
	}
	change_code_status($list, '1', 'publish');

	// Status neu einlesen
	if ($new_search) {
		$sql = "SELECT A.idcatside, idcat, changed FROM $cms_db[cat_side] A LEFT JOIN $cms_db[code] B USING(idcatside) WHERE B.idlang='$lang' AND changed='2' AND A.idcat='$idcat'";
		$db->query($sql);
		while($db->next_record()) {
			if ($perm->have_perm(23, 'side', $db->f('idcatside'), $db->f('idcat'))) {
	            $con_side[$db->f('idcat')][$db->f('idcatside')]['status'] = 'true';
				$con_tree[$db->f('idcat')]['status'] = 'true';
			}
		}
	}
    
    // Cache-Group Frontend löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
	
	// Danach ins Frontend?
	if ($view) {
		header ('HTTP/1.1 302 Moved Temporarily');
		header ('Location:'.$sess->urlRaw($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcatside='.$idcatside.'&view='.$view));
		exit;
	}
}

function con_make_start ($idcatside, $is_start) {
	global $db, $cms_db;

	$sql = "SELECT idcat FROM $cms_db[cat_side] WHERE idcatside='$idcatside'";
	$db->query($sql);
	$db->next_record();

	$sql = "UPDATE $cms_db[cat_side] SET is_start='0' WHERE idcat='".$db->f('idcat')."'";
	$db->query($sql);

	$sql = "UPDATE $cms_db[cat_side] SET is_start='$is_start' WHERE idcatside='$idcatside'";
	$db->query($sql);

	// Cache-Group Frontend löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
}

function con_move_side($dir,$idcat,$idside,$sortindex) {
	global $db, $cms_db;

	if($dir == 'up'){
		if($sortindex > 1){
			$sql = "UPDATE $cms_db[cat_side] SET sortindex='$sortindex' WHERE idcat='$idcat' AND sortindex='".($sortindex - 1)."'";
			$db->query($sql);
			$sql = "UPDATE $cms_db[cat_side] SET sortindex='".($sortindex - 1)."' WHERE idcat='$idcat' AND idside='$idside'";
			$db->query($sql);
		}
	} else {
		$sql = "UPDATE $cms_db[cat_side] SET sortindex='$sortindex' WHERE idcat='$idcat' AND sortindex='".($sortindex + 1)."'";
		if($db->query($sql)){
			$sql = "UPDATE $cms_db[cat_side] SET sortindex='".($sortindex + 1)."' WHERE idcat='$idcat' AND idside='$idside'";
			$db->query($sql);
		}
	}

	// Cache-Group Frontend löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
}

function con_quick_sort($quicksort,$idcat) {
	global $db, $lang, $cms_db;

	list($sort,$dir) = explode(':',$quicksort);
	$sql  = "SELECT A.idcatside, A.sortindex, B.title, B.created, B.lastmodified, B.author FROM $cms_db[cat_side] A LEFT JOIN $cms_db[side_lang] B USING(idside) WHERE A.idcat = '$idcat' AND B.idlang = '$lang'";
	$db->query($sql);
	while($db->next_record()){
		if($sort == 'created' || $sort == 'lastmodified') 
			$sortarray[$db->f('idcatside')] = strtotime($db->f($sort));
		else 
			$sortarray[$db->f('idcatside')] = $db->f($sort);
	}
	foreach ($sortarray AS $k=>$v){
		$sortarray[$k] = str_replace(array('Ä','ä','Ü','ü','Ö','ö'),
										array('Ae','ae','Ue','ue','Oe','oe'),
										$v); 
	}
	
	natsort($sortarray);
	if($dir == 'DESC'){$sortarray = array_reverse($sortarray, TRUE); }
	$counter = 1;
	foreach (array_keys($sortarray) as $idcatside){
		$sql = "UPDATE $cms_db[cat_side] SET sortindex='$counter' WHERE idcatside = '$idcatside'";
		$db->query($sql);
		$counter++;
	}

	// Navigationstree aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'tree'));
}

function con_move_top_bottom($dir,$idcat,$idcatside,$sortindex) {
	global $db, $cms_db;
	if($dir == 'top'){
		$sql = "UPDATE $cms_db[cat_side] SET sortindex=sortindex+1 WHERE idcat=$idcat AND sortindex BETWEEN 1 AND $sortindex";
		$db->query($sql);
		$sql = "UPDATE $cms_db[cat_side] SET sortindex=1 WHERE idcatside=$idcatside";
		$db->query($sql);
	} else {
		// Endwert holen
		$db->query("SELECT MAX(sortindex) AS max FROM $cms_db[cat_side] WHERE idcat=$idcat");
		$db->next_record(); $lastindex=$db->f('max');
		// Alles eins höher legen
		$sql = "UPDATE $cms_db[cat_side] SET sortindex=sortindex-1 WHERE idcat=$idcat AND sortindex > $sortindex";
		$db->query($sql);
		// Aktuelle Seite nach ganz unten
		$sql = "UPDATE $cms_db[cat_side] SET sortindex=$lastindex WHERE idcatside=$idcatside";
		$db->query($sql);
	}

	// Navigationstree aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'tree'));
}

function reindex_sort(&$sidelist) {
	global $db, $cms_db, $reindex;

	foreach (array_keys($reindex) as $kat) {
		ksort($sidelist[$kat]);
		$index = 0;
		foreach ($sidelist[$kat] as $idcatside) {
			$index++;
			$sql = "UPDATE $cms_db[cat_side] SET sortindex=$index WHERE idcatside = $idcatside";
			$db->query($sql);
		}
	}

	// Navigationstree aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'tree'));
}

function con_reindex_page_sort($idcat) {
	global $db, $cms_db;
	
	if (! function_exists('getIdList') ) {
		include_once('inc/fnc.tpl.php');	
	}
	
	$idcat = (int) $idcat;
		$sql = 'SELECT idcatside FROM ' . $cms_db['cat_side'] . ' WHERE idcat = ' . $idcat .' ORDER BY sortindex';
		$list_idcatsides = array();
		getIdList($sql, $list_idcatsides, 'idcatside');
		$index = 0;
		foreach ($list_idcatsides as $idcatside) {
			$index++;
			$sql = "UPDATE ".$cms_db['cat_side']." SET sortindex=$index WHERE idcatside = $idcatside";
	
			$db->query($sql);
		}

}

//mode: idcat or parent
function reindex_cat_sort($idcat, $idclient, $mode = 'idcat') {
	global $db, $cms_db;
	
	if($idcat == 0 || ! (is_int($idcat)||is_numeric($idcat) ) ) 
		return;
	if($idclient < 1 || ! (is_int($idclient)||is_numeric($idclient) ) ) 
		return;
	
	//echo "XXXX $idcat";
	//parent finden
	if ($mode == 'idcat') {
	$db->query("SELECT 
					parent FROM ".$cms_db['cat']." 
				WHERE 
					idcat=". $idcat ."
					AND idclient=".$idclient);
	$db->next_record();
    $p=$db->f('parent');
	} else {
		$p = $idcat;
	}

	//alle idcats finden
	$db->query("SELECT 
					idcat FROM ".$cms_db['cat']." 
				WHERE 
					parent=".$p." 
					AND idclient=". $idclient ." 
				ORDER BY sortindex");	
	$idcats = array();
	while($db->next_record()){
		array_push($idcats, $db->f('idcat'));
	}
	
	//neu sortieren
	$new_index = 1;
	if(is_array($idcats)){
		foreach($idcats AS $v){
			$sql = "UPDATE ".$cms_db['cat']." SET sortindex=$new_index WHERE idcat=$v";
			$db->query($sql);
			$new_index++;
		}
	}
    
    // Navigationstree aus Cache löschen
	//sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'tree'));
}

function con_move_cat($idcat,$target, $idclient) {
	global $db, $cms_db, $sort;
	$sort = true;

	if($idcat == 0 || ! (is_int($idcat)||is_numeric($idcat)) || ! (is_int($target)||is_numeric($target)) ) 
		return;
	if($idclient < 1 || ! (is_int($idclient)||is_numeric($idclient) ) ) 
		return;
	
	
	// Parent und Sortindex feststellen
	$db->query("SELECT parent, sortindex FROM $cms_db[cat] WHERE idcat=$idcat AND idclient=". $idclient);
	$db->next_record();
         $parent=$db->f('parent');
         $sortindex=$db->f('sortindex');

	// neuen Sortindex suchen
	$db->query("SELECT MAX(sortindex) AS max FROM $cms_db[cat] WHERE parent=$target AND idclient=". $idclient);
	$db->next_record();
         $lastindex=$db->f('max')+1;

	// rootparent holen
	$db->query("SELECT rootparent FROM $cms_db[cat] WHERE idcat=$target AND idclient=". $idclient);
	$db->next_record();
         if( $db->f('rootparent') > 0){ $rootparent = $db->f('rootparent'); } 
         else { $rootparent = $idcat; }

	// verschieben
	$sql = "UPDATE 
				$cms_db[cat] 
			SET 
				parent='$target',
				sortindex='$lastindex', 
				rootparent='$rootparent' 
			WHERE idcat=$idcat
			AND idclient=". $idclient;
	$db->query($sql);

	// umsortieren in der alten Kategorie BUGGY?
	// $sql = "UPDATE $cms_db[cat] SET sortindex=sortindex-1 WHERE parent=$parent AND sortindex > $sortindex";
	// $db->query($sql);
	
	//update old cats
	reindex_cat_sort($parent, $idclient);
	//update new cats
	reindex_cat_sort($target, $idclient);
	
    // Navigationstree aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'tree'));
}

function con_sort_cat ($dir,$idcat,$sortindex,$parent, $idclient) {
	global $db, $cms_db, $sort;

	$sort = true;
	switch($dir) {
		case 'up':
			if($sortindex > 1){
				$sql = "UPDATE $cms_db[cat] SET sortindex='$sortindex' WHERE parent='$parent' AND sortindex='".($sortindex - 1)."' AND idclient=".$idclient;
				$db->query($sql);
				$sql = "UPDATE $cms_db[cat] SET sortindex='".($sortindex - 1)."' WHERE parent='$parent' AND idcat='$idcat' AND idclient=".$idclient;
				$db->query($sql);
			}
			break;
		case 'down':
			$sql = "UPDATE $cms_db[cat] SET sortindex='$sortindex' WHERE parent='$parent' AND sortindex='".($sortindex + 1)."' AND idclient=".$idclient;
			if($db->query($sql)){
				$sql = "UPDATE $cms_db[cat] SET sortindex='".($sortindex + 1)."' WHERE parent='$parent' AND idcat='$idcat' AND idclient=".$idclient;
				$db->query($sql);
			}
			break;
		case 'top':
			$sql = "UPDATE $cms_db[cat] SET sortindex=sortindex+1 WHERE parent=$parent AND sortindex BETWEEN 1 AND $sortindex AND idclient=".$idclient;
			$db->query($sql);
			$sql = "UPDATE $cms_db[cat] SET sortindex=1 WHERE idcat=$idcat AND idclient=".$idclient;
			$db->query($sql);
			break;
		case 'bottom':
			// Endwert holen
			$db->query("SELECT MAX(sortindex) AS max FROM $cms_db[cat] WHERE parent=$parent AND idclient=".$idclient);
			$db->next_record(); $lastindex=$db->f('max');
			// Alles eins höher legen
			$sql = "UPDATE $cms_db[cat] SET sortindex=sortindex-1 WHERE parent=$parent AND sortindex > $sortindex AND idclient=". $idclient;
			$db->query($sql);
			// Aktuelle Seite nach ganz unten
			$sql = "UPDATE $cms_db[cat] SET sortindex=$lastindex WHERE idcat=$idcat AND idclient=".$idclient;
			$db->query($sql);
			break;
	}

	// Navigationstree aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'tree'));
}

function con_copy_page($idclient, $idlang, $idcatside_from, $name='', $target_idcat=-1, $copy_perms = true, 
						$options = array()) {
	
	//option values are: default, yes, no
	//special: 'set_startflag': if_first 
	//'set_online' (default|yes|no) default is offline
	//'set_copy' (default|yes|no) default is copy flag from source
	//'set_startflag' (default|from_source) default set the startflag if category haven't a valid startpage, 
	//                                      from_source copys flag from source  
	$options_default = array( 'set_online'=> 'default', 
							  'set_protected'=> 'default',
							  'set_startflag' => 'default');
	$options = array_merge($options_default, $options);
	
	//cast
	$idclient = (int) $idclient;
	$idlang = (int) $idlang;
	$idcatside_from = (int) $idcatside_from;
	$target_idcat = (int) $target_idcat;
	if ($idcatside_from < 1 || $idclient < 1 || $idlang < 1) {
		return false;
	}
	
	$idcatside_to = con_create_site_meta_from_idcatside($idclient, $idlang, $idcatside_from, $name, true, $target_idcat, $options);
	con_copy_tpl_from_idcatside($idclient, $idcatside_from, $idcatside_to);
	con_copy_content_from_idcatside($idclient, $idcatside_from, $idcatside_to);
	if ($copy_perms) {
		con_copy_perms_from_idcatside($idclient, $idcatside_from, $idcatside_to);
	}
	
	return $idcatside_to;
	
	
}

function con_create_site_meta_from_idcatside($idclient, $idlang_current, $idcatside_from, $name='', $lang_postfix= true, $target_idcat=-1, $options =array()) {
	global $db, $cms_db, $cfg_cms;
	//cast
	$idclient = (int) $idclient;
	$idlang_current = (int) $idlang_current;
	$idcatside_from = (int) $idcatside_from;
	if ($idclient < 1 || $idlang_current < 1 || $idcatside_from < 1) {
		return false;
	}
	
	set_magic_quotes_gpc($name);
	
	//init
	$db2 = new DB_cms;
	
	//get necessary values from source idcatside
	$sql = "SELECT * FROM ".$cms_db['cat_side']." WHERE idcatside='$idcatside_from'";
	$db->query($sql);
	if ($db->next_record() ) {
		$idcat_from = $db->f('idcat');
		$idside_from = $db->f('idside');
		$sortindex_from = $db->f('sortindex');
		$is_start_from = $db->f('is_start');
	} else {
		return false;
	}
	
	//copy to same idcat or to an other category
	$idcat_to = ($target_idcat<1) ? $idcat_from : $target_idcat;
	
	
	//create idside
	$sql = "INSERT INTO ".$cms_db['side']." (idclient) VALUES ('$idclient')";
	$db->query($sql);
	$idside_to = mysql_insert_id();
	
	//create idcatside
	$sql = "INSERT INTO 
				".$cms_db['cat_side']." (idcat, idside, is_start) 
			VALUES
				('$idcat_to', '$idside_to', '0')";
	$db->query($sql);
	$idcatside_to = mysql_insert_id();
	
	//sortindex
	$db->query("SELECT MAX(sortindex) AS max FROM ".$cms_db['cat_side']." WHERE idcat='$idcat_to'");
	$db->next_record(); 
	$lastindex = (int) $db->f('max');
	if ($lastindex < 1) {
		$lastindex = 1;
	} else {
		++$lastindex;	
	}
	$sql = "UPDATE ".$cms_db['cat_side']." SET sortindex='$lastindex' WHERE idcatside='$idcatside_to'";
	$db->query($sql);
	
	
	
	//check and set startpage
	if ($options['set_startflag'] == 'from_source') {
		$sql = "UPDATE ".$cms_db['cat_side']." SET is_start='$is_start_from' WHERE idcatside='$idcatside_to'";
		$db->query($sql);
	} else {
		$sql = "SELECT * FROM ".$cms_db['cat_side']." WHERE idcat='$idcat_to' AND is_start='1'";
		$db->query($sql);
		if (!$db->affected_rows()) {
			$sql = "UPDATE ".$cms_db['cat_side']." SET is_start='1' WHERE idcat='$idcat_to' AND sortindex='1'";
			$db->query($sql);
	    }	
	}
	
	//get lang infos
	include_once $cfg_cms['cms_path']."inc/fnc.clients.php";
	$arr_langs = clients_get_langs($idclient, true);
	
	//insert metadata foreach lang
	$db3 = new DB_cms;
	$sql = "SELECT * FROM ".$cms_db['side_lang']." WHERE idside='$idside_from'";
	$db3->query($sql);
	while ( $db3->next_record() ) {
		$idlang = $db3->f('idlang');
		
		if ($name == '') {
			$name = make_string_dump($db3->f('title'));
		}
		
		if($lang_postfix && $idlang_current != $idlang) {
			$title = $name . ' ('. $arr_langs[$idlang]['name'] .')';
		} else {
			$title = $name;
		}
		
		$summary = make_string_dump($db3->f('summary'));
    $meta_title = make_string_dump($db3->f('meta_title'));
    $meta_other = make_string_dump($db3->f('meta_other'));
		$meta_author = make_string_dump($db3->f('meta_author'));
		$meta_description = make_string_dump($db3->f('meta_description'));
		$meta_keywords = make_string_dump($db3->f('meta_keywords'));
		$meta_robots = make_string_dump($db3->f('meta_robots'));
		$meta_redirect_url = make_string_dump($db3->f('meta_redirect_url'));
		$rewrite_url = make_string_dump($db3->f('rewrite_url'));
		
		$metasocial_title = make_string_dump($db3->f('metasocial_title'));
		$metasocial_image = make_string_dump($db3->f('metasocial_image'));
		$metasocial_description = make_string_dump($db3->f('metasocial_description'));
		$metasocial_author = make_string_dump($db3->f('metasocial_author'));
		
		//get the stat
		$online = ((int) $db3->f('online') & 0xFF);


		//handle online/ offline, protection options
		//online
		if ($options['set_online'] == 'yes') {
			$online = ($online | 0x01);
		} else if ($options['set_online'] == 'no'){
			$online = ($online & 0xFE);
		}
		
		//protected
		if ($options['set_protected'] == 'yes') {
			$online = ($online | 0x04);
		} else if ($options['set_protected'] == 'no') {
			$online = ($online & 0xFB);
		}
		
		//make rewrite url
		if (! function_exists('rewriteGenerateUrlString'))
		{
			include_once $cfg_cms['cms_path'].'inc/fnc.mod_rewrite.php';
		}
		$rewrite_url = rewriteGenerateUrlString($title);
		$rewrite_url = rewriteMakeUniqueStringForLang('idcatside', $idcatside_to, $rewrite_url);
		
		
		//echo $online;exit;
		
		$sql2 = "INSERT INTO ".$cms_db['side_lang']." 
					(idside, idlang, idtplconf, title, meta_title, meta_other, meta_keywords, summary, online, 
						meta_redirect, meta_redirect_url, author, 
						created, lastmodified, user_protected, visited, edit_ttl, 
						meta_author, meta_description, meta_robots, meta_redirect_time,
						,metasocial_title,metasocial_image,metasocial_description,metasocial_author
						rewrite_use_automatic, rewrite_url, start, end) 
				VALUES ('".$idside_to."', '$idlang', '0', '$title', '$meta_title','$meta_other','$meta_keywords', '$summary', '$online',
					 '".$db3->f('meta_redirect')."', '$meta_redirect_url', '".$db3->f('author')."', 
					'".time()."', '".time()."', '".$db3->f('user_protected')."', '".$db3->f('visited')."', '".$db3->f('edit_ttl')."', 
					'$meta_author', '$meta_description', '$meta_robots', '".$db3->f('meta_redirect_time')."',
					 '".$db->f('metasocial_title')."', '".$db->f('metasocial_image')."', '".$db->f('metasocial_description')."', '".$db->f('metasocial_author')."',
					'1', '$rewrite_url', ".time().", ".time().")";
		$db2->query($sql2);
	}

	return $idcatside_to;
}

function con_copy_perms_from_idcatside($idclient, $idcatside_from, $idcatside_to) {
	global $perm, $cfg_cms;
	
	include_once $cfg_cms['cms_path']."inc/fnc.clients.php";
	$arr_langs = clients_get_langs($idclient, true);
	foreach ($arr_langs['order'] AS $current_lang) {
		$perm->xcopy_perm($idcatside_from, 'side', $idcatside_to, 'side', 0x7FFD0000, 0, $current_lang, false);
		$perm->xcopy_perm($idcatside_from, 'frontendpage', $idcatside_to, 'frontendpage', 0xFFFF0000, 0, $current_lang, false);
	}
}

function con_copy_tpl_from_idcatside($idclient, $idcatside_from, $idcatside_to) {
	//init
	global $db, $cms_db, $cfg_cms;
	$db2 = new DB_cms;
	
	//get necessary values from source idcatside
	$sql = "SELECT * FROM ".$cms_db['cat_side']." WHERE idcatside='$idcatside_from'";
	$db->query($sql);
	if ($db->next_record() ) {
		$idcat_from = $db->f('idcat');
		$idside_from = $db->f('idside');
		$sortindex_from = $db->f('sortindex');
	} else {
		return false;
	}

	//get necessary values from target idcatside
	$sql = "SELECT * FROM ".$cms_db['cat_side']." WHERE idcatside='$idcatside_to'";
	$db->query($sql);
	if ($db->next_record() ) {
		$idcat_to = $db->f('idcat');
		$idside_to = $db->f('idside');
		$sortindex_to = $db->f('sortindex');
	} else {
		return false;
	}

	//get langs
	include_once $cfg_cms['cms_path']."inc/fnc.clients.php";
	$arr_langs = clients_get_langs($idclient, true);
	foreach ($arr_langs['order'] AS $current_lang) {

		//get tpl
		$sql = "SELECT
					SL.idside, SL.idtplconf,
					TC.idtpl,
					CC.idcontainer, CC.config, CC.view, CC.edit
				FROM 
					".$cms_db['side_lang']." SL
					LEFT JOIN ".$cms_db['tpl_conf']." TC USING(idtplconf)
					LEFT JOIN ".$cms_db['container_conf']." CC USING(idtplconf)
				WHERE
					SL.idlang='$current_lang'
					AND SL.idside = '$idside_from'
					AND SL.idtplconf != 0";
		$current_idside = 0;
		$current_idtplconf = 0;
		//echo $sql .'<br />';
		$db->query($sql);
		while ($db->next_record() ) {
			// create new idtplconf
			// update new idtplconf to table cat_lang
			if ($current_idside != $db->f('idside')) {
				$current_idside = $db->f('idside');
				//insert idtplconf in config template
				$sql2 = "INSERT INTO 
							".$cms_db['tpl_conf']." (idtpl) VALUES('".$db->f('idtpl')."')";
				
				//echo $sql2 .'<br />';
				$db2->query($sql2);
				$current_idtplconf = mysql_insert_id(); 
				
				$sql2 = "UPDATE 
							".$cms_db['side_lang']."
						SET
							idtplconf = '$current_idtplconf'
						WHERE 
							idlang = '$current_lang'
						 	AND idside = '$idside_to'";
				//echo $sql2 .'<br />';
				$db2->query($sql2);
			}
			
			$sql2 = "INSERT INTO 
						".$cms_db['container_conf']." 
							(idtplconf, idcontainer, config, view, edit)
						VALUES('$current_idtplconf', '".$db->f('idcontainer')."', '".make_string_dump($db->f('config'))."',
								'".$db->f('view')."', '".$db->f('edit')."')";
			//echo $sql2 .'<br />';
			$db2->query($sql2);
		}
	}
}

function con_copy_content_from_idcatside($idclient, $idcatside_from, $idcatside_to) {
	//init
	global $db, $cms_db, $cfg_cms;
	$db2 = new DB_cms;
	$db3 = new DB_cms;
	
	//get necessary values from source idcatside
	$sql = "SELECT * FROM ".$cms_db['cat_side']." WHERE idcatside='$idcatside_from'";
	$db->query($sql);
	if ($db->next_record() ) {
		$idcat_from = $db->f('idcat');
		$idside_from = $db->f('idside');
		$sortindex_from = $db->f('sortindex');
	} else {
		return false;
	}

	//get necessary values from target idcatside
	$sql = "SELECT * FROM ".$cms_db['cat_side']." WHERE idcatside='$idcatside_to'";
	$db->query($sql);
	if ($db->next_record() ) {
		$idcat_to = $db->f('idcat');
		$idside_to = $db->f('idside');
		$sortindex_to = $db->f('sortindex');
	} else {
		return false;
	}

	//get langs
	include_once $cfg_cms['cms_path']."inc/fnc.clients.php";
	$arr_langs = clients_get_langs($idclient, true);
	// print_r($arr_langs);
	foreach ($arr_langs['order'] AS $current_lang) {
		//copy content
		$table_list = array($cms_db['content'], $cms_db['content_external']);
		
		foreach ($table_list AS $current_content_table ) {
			$sql = "SELECT 
						C.idcontent, C.idsidelang, C.container, C.number, C.idtype, C.typenumber, C.value, C.online, 
							C.version, C.author, C.created, C.lastmodified,
							SL.idside
					FROM
						".$current_content_table." C
						LEFT JOIN ".$cms_db['side_lang']." SL USING(idsidelang)
					WHERE
						SL.idlang='$current_lang'
						AND SL.idside='$idside_from'";
			$db->query($sql);
			
			while ($db->next_record() ) {
				$sql2 = "SELECT 
							SL.idsidelang 
						FROM 
							".$cms_db['side_lang']." SL 
						WHERE
							SL.idlang='$current_lang'
							AND SL.idside = '$idside_to'";
				 $db2->query($sql2);
				 if ($db2->next_record() ) {
				 	$sql3 = "INSERT INTO 
							".$current_content_table." 
								(idsidelang, container, number, idtype, typenumber, value, online, 
									version, author, created, lastmodified)
							VALUES
								('".$db2->f('idsidelang')."', 
									'".$db->f('container')."', '".$db->f('number')."', '".$db->f('idtype')."', 
									'".$db->f('typenumber')."', '".make_string_dump($db->f('value'))."',
									'".$db->f('online')."', '".$db->f('version')."', '".$db->f('author')."',
									'".$db->f('created')."', '".$db->f('lastmodified')."')";
					$db3->query($sql3);
				 }
			}
		}
	}
}


?>