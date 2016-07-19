<?PHP
// File: $Id: fnc.tpl.php 52 2008-07-20 16:16:33Z bjoern $
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

function tpl_change($idlay) {
    global $db;
	$list = browse_layout_for_containers($idlay);
	if (is_array($list['id'])) {
		foreach ($list['id'] as $i) {
			global ${'C'.$i.'MOD_VAR'};
			if (${'C'.$i.'MOD_VAR'}) $cconfig[$i] = make_array_to_urlstring(${'C'.$i.'MOD_VAR'});
		}
	}
    // Content aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'content'));
	return $cconfig;
}

function tpl_make_start_tpl($idclient, $idtpl) {
	global $db, $cms_db;
	
	if(! is_int($idclient) || ! is_int($idtpl)) {
		return false;
	}
	
	//reset all
	$sql = "UPDATE ".$cms_db['tpl']." 
			SET	
				is_start='0'
			WHERE idclient='$idclient'";
	$db->query($sql);
	
	//update on
	$sql = "UPDATE ".$cms_db['tpl']." 
		SET	
			is_start='1'
		WHERE 
			idclient='$idclient'
			AND idtpl = '$idtpl'";
	$db->query($sql);
	
	return true;
}

function tpl_autoset_starttpl($idclient, $idtpl) {
	global $db, $cms_db;
	
	$sql = "SELECT 
				count(idtpl) AS c  FROM ".$cms_db['tpl']." 
			WHERE 
				idclient='$idclient'";
				
	$db->query($sql);
	$db->next_record();
	
	if($db->f('c') == 1) {
		tpl_make_start_tpl($idclient, $idtpl);
	}
	
	return true;
}


function tpl_save($idtpl, $idlay, $tplname, $description, $tpl_overwrite_all) {
	global $db, $auth, $client, $cms_db, $cms_lang, $cfg_client, $tpl, $perm;
	
	global $idtpl;

	// Eintrag in 'tpl' Tabelle
	if ($tplname == '') $tplname = $cms_lang['tpl_defaultname'];
	set_magic_quotes_gpc($tplname);
	set_magic_quotes_gpc($description);
	if (!$idtpl) {
		$sql = "INSERT INTO ".$cms_db['tpl']." (name, description, idlay, idclient, author, created
                , lastmodified) VALUES ('$tplname', '$description', '$idlay', '$client'
                , '".$auth->auth['uid']."', '".time()."', '".time()."')";
		$db->query($sql);
		$idtpl = mysql_insert_id();

		// Event
		fire_event('tpl_new', array('idtpl' => $idtpl, 'name' => $tplname));
	} else {
		$sql = "UPDATE ".$cms_db['tpl']." SET	name='$tplname', description='$description', idlay='$idlay',
                author='".$auth->auth['uid']."', lastmodified='".time()."' WHERE idtpl='$idtpl'";
		$db->query($sql);

		//rechte setzen
		if ($perm->have_perm('6', 'tpl', $idtpl)) {
			global $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben;
			$perm->set_group_rights( 'tpl', $idtpl, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben, '', 0x35);
		}

		// Event
		fire_event('tpl_edit', array('idtpl' => $idtpl, 'name' => $tplname));
	}

	// Array der alten Container erstellen
	$used_container = browse_template_for_module($idtpl, '0');
	if (!is_array($used_container['id'])) $used_container['id']['0'] = '0';

	// Array der neuen Container erstellen
	$list = browse_layout_for_containers($idlay);
	if (is_array($list['id'])) {
		foreach ($list['id'] as $i) {
			global ${'C'.$i.'MOD_VAR'}, ${'c'.$i}, ${'cview'.$i}, ${'cedit'.$i};

			if (${'c'.$i}) $container['id'][] = $i;
			if (${'C'.$i.'MOD_VAR'}) $cconfig[$i] = make_array_to_urlstring(${'C'.$i.'MOD_VAR'});
		}
	}
	if (!is_array($container['id'])) $container['id']['0'] = '0';
	foreach ($container['id'] as $value) {
		if ($value != '0') {
			// neue Container hinzufügen
			if (!in_array($value,$used_container['id'])) {
				$sql = "INSERT INTO $cms_db[container] (idtpl, container, idmod) VALUES ('$idtpl'
                        , '$value', '".${'c'.$value}."')";
				$db->query($sql);
				$idcontainer = mysql_insert_id();
				$sql = "INSERT INTO $cms_db[container_conf] (idcontainer, config, view, edit)
                        VALUES ('$idcontainer', '$cconfig[$value]', '".${'cview'.$value}."', '".${'cedit'.$value}."')";
				$db->query($sql);

				// Templatekopien suchen
				if (!$tpllist) {
					$sql = "SELECT idtplconf FROM ". $cms_db['tpl_conf'] ." WHERE idtpl='$idtpl'";
					$db->query($sql);
					while ($db->next_record()) $tpllist[] = $db->f('idtplconf');
				}
				if (is_array($tpllist)) {
					foreach ($tpllist as $idtplconf) {
						$sql = "INSERT INTO ".$cms_db['container_conf']." (idtplconf, idcontainer, config
                                , view, edit) VALUES ('$idtplconf', '$idcontainer', '$cconfig[$value]'
                                , '".${'cview'.$value}."', '".${'cedit'.$value}."')";
						$db->query($sql);
						$change = 'true';
					}
				}
			}

			// geänderte Container updaten
			if (${'c'.$value} == $used_container[$value]['idmod'] && ($cconfig[$value] != $used_container[$value]['config']
                || ${'cview'.$value} != $used_container[$value]['view']	|| ${'cedit'.$value} != $used_container[$value]['edit'])) {

				// Seiten / Ordnertemplates ändern
				if ($tpl_overwrite_all != '1') $tpl_overwrite = " AND idtplconf='0'";
				else {
					$tpl_overwrite = '';
					$change = 'true';
                }
				$sql = "UPDATE	".$cms_db['container_conf']." SET config='".$cconfig[$value]."', view='".${'cview'.$value}."'
                        , edit='".${'cedit'.$value}."' WHERE idcontainer='".$used_container[$value]['idcontainer']."'$tpl_overwrite";
				$db->query($sql);
			}

			// Modul wurde geändert
			if (${'c'.$value} != $used_container[$value]['idmod'] && $used_container[$value]['idmod']) {
				$sql = "UPDATE ".$cms_db['container']." SET idmod='".${'c'.$value}."'
                        WHERE idcontainer='".$used_container[$value]['idcontainer']."'";
				$db->query($sql);
				$sql = "UPDATE ".$cms_db['container_conf']." SET config='".$cconfig[$value]."', view='".${'cview'.$value}."'
                        , edit='".${'cedit'.$value}."' WHERE idcontainer='".$used_container[$value]['idcontainer']."'";
				$db->query($sql);
				$change = 'true';
				$empty_container[] = $value;
			}
		}
	}

  	// alte Container löschen
  	foreach ($used_container['id'] as $value) {
		if ($value != '0') {
  			if (!in_array($value,$container['id'])) {
  				$sql = "DELETE FROM $cms_db[container] WHERE idcontainer='".$used_container[$value]['idcontainer']."'";
  				$db->query($sql);
  				$sql = "DELETE FROM $cms_db[container_conf] WHERE idcontainer='".$used_container[$value]['idcontainer']."'";
  				$db->query($sql);
				$change = 'true';
				$empty_container[] = $value;
  			}
  		}
  	}

	if ($change) {
		$list = get_idtplconf_by_using_type($idtpl, 'tpl');

		// lösche alte 'content' Einträge
		if (is_array($empty_container)) {
			$list2 = get_idsidelang_by_idtplconf($list);
			$sql = "DELETE FROM $cms_db[content] WHERE idsidelang IN(".implode(',', $list2).")
                    AND container IN(".implode(',', $empty_container).")";
			$db->query($sql);
			$sql = "DELETE FROM $cms_db[content_external] WHERE idsidelang
                    IN(".implode(',', $list2).") AND container IN(".implode(',', $empty_container).")";
			$db->query($sql);
			unset($list2);
		}

		// Status der 'code' Tabelle ändern
		// jb_todo: change_code_status im dateimanager einführen
		$list = get_idcode_by_idtplconf($list);
		change_code_status($list, '1');
		unset($list);
	}
    // Content aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'content'));
}

function tpl_delete_template($idtpl) {
	global $db, $client, $cms_db, $perm;
	
	if(! (is_numeric($idtpl) || is_int($idtpl) ) ) return;

	// Wird Template noch verwendet?
	$template_is_in_use = false;
	
	$sql = "SELECT idtplconf FROM ".$cms_db['tpl_conf']." WHERE idtpl='$idtpl'";
	$db->query($sql);
	$used_ids = array();
	while ($db->next_record()) {
		$used_ids[$db->f('idtplconf')] = $db->f('idtplconf');
	}
	
	if (count($used_ids) > 0) {
		$ids_comma = implode(',', $used_ids);
		$sql = "SELECT idtplconf FROM ".$cms_db['side_lang']." WHERE idtplconf IN($ids_comma)";
		$db->query($sql);
		if ($db->next_record()) {
			$template_is_in_use = true;
		}
		$sql = "SELECT idtplconf FROM ".$cms_db['cat_lang']." WHERE idtplconf IN($ids_comma)";
		$db->query($sql);
		if ($db->next_record()) {
			$template_is_in_use = true;
		}
	}

	if ($template_is_in_use) {
		return '0501';
	} else {
		// Modulkonfiguration löschen
		$sql = "SELECT idcontainer FROM $cms_db[container] WHERE idtpl='$idtpl'";
		$db->query($sql);
		while ($db->next_record()) $containerlist[] = $db->f('idcontainer');
		if (is_array($containerlist)) {
			$sql = "DELETE FROM $cms_db[container_conf] WHERE idcontainer IN(".implode(',', $containerlist).")";
			$db->query($sql);
		}

		// Module aus dem Template löschen
		$sql = "DELETE FROM $cms_db[container] WHERE idtpl='$idtpl'";
		$db->query($sql);

		// Template löschen
		$sql = "DELETE FROM $cms_db[tpl] WHERE idtpl='$idtpl'";
		$db->query($sql);

		//delete perm
		$perm->delete_perms($idtpl, 'tpl');

		// Event
		fire_event('tpl_delete', array('idtpl' => $idtpl));
	}
    // Content aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'content'));
}

function con_config_tpl_save($idtpl, $idlay, $idcatlang, $idsidelang, $idtplconf, $have_perm_save_configdata = true) {
	global $db, $cms_db, $cfg_client, $configtpl;
	
	if(! (is_numeric($idtpl) || is_int($idtpl) ) ) return;
	if(! (is_numeric($idtplconf) || is_int($idtplconf) ) ) return;
	$is_new_tpl = false;
	
	
	// Array der alten Container erstellen
	$used_container = browse_template_for_module('0', $idtplconf);
	if (!is_array($used_container['id'])) $used_container['id']['0'] = '0';
	
	// Eintrag in 'tpl_conf' Tabelle
	if ($idtplconf == '0') {
		if ($idtpl != '0') {
			// Template erstellen
			$sql = "INSERT INTO $cms_db[tpl_conf] (idtpl) VALUES ('$idtpl')";
			mysql_query($sql);
			//print_r($db);
			$idtplconf = mysql_insert_id();
			//echo mysql_insert_id();
			$is_new_tpl = true;
			//echo "<br>new tpl idtpl: $idtpl idtplconf: $idtplconf<br>";
		} else {
			 return;
		}
	} else {
		if ($idtpl != '0' && $idtpl != $configtpl) {
			// Template ändern
	        $sql = "UPDATE $cms_db[tpl_conf] SET idtpl='$idtpl' WHERE idtplconf='$idtplconf'";
			$db->query($sql);
			$is_new_tpl = true;
		} elseif ($idtpl == '0') {
			// Template löschen
			$sql = "DELETE FROM $cms_db[container_conf] WHERE idtplconf='$idtplconf'";
			$db->query($sql);
			$sql = "DELETE FROM $cms_db[tpl_conf] WHERE idtplconf='$idtplconf'";
			$db->query($sql);

			// Status der 'code' Tabelle ändern
			$list = get_idcode_by_idtplconf($idtplconf);
			change_code_status($list, '1');
			unset($list);

			// Containerinhalt löschen
			$list = get_idsidelang_by_idtplconf($idtplconf);
			$sql = "DELETE FROM $cms_db[content] WHERE idsidelang IN(".implode(',', $list).")";
			$db->query($sql);
			$sql = "DELETE FROM $cms_db[content_external] WHERE idsidelang IN(".implode(',', $list).")";
			$db->query($sql);
			unset($list);
			$idtplconf = '0';
		}
	}

    // Content aus Cache löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'content'));
    
	// Template bei Seite oder Ordner eintragen
	if ($idcatlang) {
		$sql = "UPDATE $cms_db[cat_lang] SET idtplconf='$idtplconf' WHERE idcatlang='$idcatlang'";
	} else {
		$sql = "UPDATE $cms_db[side_lang] SET idtplconf='$idtplconf' WHERE idsidelang='$idsidelang'";
	}
	if ($idcatlang || $idsidelang) {
		//print_r($GLOBALS);
		//echo $lang."zzy"; exit;
		 $db->query($sql);
	}
	if ($idtplconf == '0') {
		return;
	}
	
	
	
	// Array der neuen Container erstellen
	// ein vorhandenes template wird neu konfiguriert und benutzer hat das recht dazu 
	if ($idlay && $have_perm_save_configdata) {
		$list = browse_layout_for_containers($idlay);
		if (is_array($list['id'])) {
			foreach ($list['id'] as $i) {
				global ${'C'.$i.'MOD_VAR'}, ${'c'.$i}, ${'cview'.$i}, ${'cedit'.$i};

				if (${'c'.$i}) $container['id'][] = $i;
				if (${'C'.$i.'MOD_VAR'}) $cconfig[$i] = make_array_to_urlstring(${'C'.$i.'MOD_VAR'});
			}
		}
	//Ein neues Template wird angelegt - recht des benutzers ist egal
	} else if(! $idlay) {
		//idlay ist bei zu wenig rechten nicht vorhanden, prüfen ob neues Template
		if(! $is_new_tpl && ! $have_perm_save_configdata){
			return;	
		}
		
		
		// Templatevorlage kopieren
		$sql = "SELECT container, config, view, edit FROM $cms_db[container] A LEFT JOIN $cms_db[container_conf] B
                USING(idcontainer) WHERE A.idtpl='$idtpl' AND B.idtplconf='0'";
		$db->query($sql);
		while ($db->next_record()) {
			$container['id'][] = $db->f('container');
			$cconfig[$db->f('container')]  = $db->f('config');
			${'cview'.$db->f('container')} = $db->f('view');
			${'cedit'.$db->f('container')} = $db->f('edit');
		}
	//es wurde kein neues template angelegt -> benutzer hat nicht das recht weiterzumachen
	} else{
		return;
	}
	
	
	
	if (!is_array($container['id'])) $container['id']['0'] = '0';
	foreach ($container['id'] as $value) {
		if ($value != '0') {
			// neue Container hinzufügen
			if (!in_array($value,$used_container['id'])) {
				$sql = "SELECT idcontainer FROM $cms_db[container] WHERE idtpl='$idtpl' AND container='$value'";
				$db->query($sql);
				$db->next_record();
				$sql = "INSERT INTO $cms_db[container_conf] (idtplconf, idcontainer, config, view, edit)
                        VALUES ('$idtplconf', '".$db->f('idcontainer')."', '$cconfig[$value]', '".${'cview'.$value}."', '".${'cedit'.$value}."')";
				$db->query($sql);
				$change = 'true';
			}

			// geänderte Container updaten
			if (in_array($value,$used_container['id'])) {
				// Modulkonfiguration hat sich geändert
				if ($used_container[$value]['config'] != $cconfig[$value] || $used_container[$value]['view'] != ${'cview'.$value}
                    || $used_container[$value]['edit'] != ${'cedit'.$value}) {
					$sql = "UPDATE $cms_db[container_conf] SET config='".$cconfig[$value]."', view='".${'cview'.$value}."', edit='".${'cedit'.$value}."' WHERE idcontainer='".$used_container[$value]['idcontainer']."' AND idtplconf='$idtplconf'";
					$db->query($sql);
					$change = 'true';
				}

				// Modul hat sich durch Templatewechsel geändert
				if ($idtpl != $configtpl) {
			        $sql = "SELECT idcontainer FROM $cms_db[container] WHERE idtpl='$idtpl' AND container='$value'";
					$db->query($sql);
					$db->next_record();
					$sql = "UPDATE $cms_db[container_conf] SET idcontainer='".$db->f('idcontainer')."'
                            WHERE idcontainer='".$used_container[$value]['idcontainer']."' AND idtplconf='$idtplconf'";
					$db->query($sql);
					if ($used_container[$value]['idmod'] != ${'c'.$value}) $empty_container[] = $value;
				}
			}
		}
	}

  	// alte Container löschen
  	foreach ($used_container['id'] as $value) {
		if ($value != '0') {
  			if (!in_array($value,$container['id'])) {
  				$sql = "DELETE FROM $cms_db[container_conf] WHERE idcontainer='".$used_container[$value]['idcontainer']."'
                        AND idtplconf='$idtplconf'";
  				$db->query($sql);
				$change = 'true';
				$empty_container[] = $value;
  			}
  		}
  	}

	// Status der 'code' Tabelle ändern
	if ($change) {
		$list = get_idcode_by_idtplconf($idtplconf);
		change_code_status($list, '1');
		unset($list);
	}

        	// Containerinhalt löschen
	if (is_array($empty_container)) {
		$list = get_idsidelang_by_idtplconf($idtplconf);
		if(is_array($list)){
			$sql = "DELETE FROM $cms_db[content] WHERE idsidelang IN(".implode(',', $list).")
                    AND container IN(".implode(',', $empty_container).")";
			$db->query($sql);
			$sql = "DELETE FROM $cms_db[content_external] WHERE idsidelang IN(".implode(',', $list).")
                    AND container IN(".implode(',', $empty_container).")";
			$db->query($sql);
			unset($list);
		}
	}
}

function con_config_folder_save($idcat, $idcatside, $idtpl, $view, $idtplconf, $description, $name,  $rewrite_use_automatic, $rewrite_alias, 
									$parent, $area, $idlay, $use_redirect = true, $inherit_visibility = true) {
	global $db, $client, $lang, $cms_db, $auth, $cms_lang, $perm, $sess, $cfg_client;
	

	
	if (empty($name)) $name = $cms_lang['con_newfolder'];
	$rewrite_use_automatic = ($rewrite_use_automatic > 0) ? 1 : 0;
	rewriteGenerateMapping();
	if($rewrite_use_automatic) {
		$rewrite_alias = rewriteGenerateUrlString($name);
		$rewrite_alias = rewriteMakeUniqueStringForLang('idcat', $idcat, $rewrite_alias);
	} else {
		$rewrite_alias = rewriteGenerateUrlString($rewrite_alias);
	}
	

	if(! (is_numeric($idtpl) || is_int($idtpl) ) ) return;

	if(! (is_numeric($idtplconf) || is_int($idtplconf) ) ) return;
	
	// Im Frontend muß ein Template angegeben werden
	// bb_todo: frontend??? config???
	if ($area == 'frontend_configcat' && $idtpl == '0') return;

	set_magic_quotes_gpc($name);
	set_magic_quotes_gpc($description);
	set_magic_quotes_gpc($rewrite_alias);

	// existiert dieser Ordner schon?
	if (empty($idcat)) {
		// Rootparent suchen
		if ($parent != '0') {
			$sql = "SELECT rootparent FROM $cms_db[cat] WHERE idcat='$parent' AND idclient='$client'";
			$db->query($sql);
			$db->next_record();
			$rootparent = $db->f('rootparent');
		}
		// Sortindex suchen
		$sql = "SELECT MAX(sortindex) AS sortindex FROM $cms_db[cat] WHERE parent='$parent' AND idclient='$client'";
		$db->query($sql);
		$db->next_record();
		$sortindex = $db->f('sortindex') + 1;

		// Neuen Ordner in 'cms_cat' schreiben
		$sql = "INSERT INTO ".$cms_db['cat']." (parent, sortindex, idclient) VALUES('$parent', '$sortindex', '$client')";
		$db->query($sql);

		// neue idcat suchen
		$idcat = mysql_insert_id();
		if ($parent == '0') $rootparent = $idcat;

		$sql   = "UPDATE ".$cms_db['cat']." SET rootparent='$rootparent' WHERE idcat='$idcat'";
		$db->query($sql);
		$languages = get_languages_by_client($client);
		
		
		//onlinestaus übergeordnete idcat holen
		$parent_visibility = 0;
		if ($parent > 0 && $inherit_visibility) {
			$sql = "SELECT visible FROM $cms_db[cat_lang] WHERE idcat='$parent' AND idlang='$lang'";
			//exit;
			$db->query($sql);
			$db->next_record();
			$parent_visibility = $db->f('visible');
			if ( ($parent_visibility & 0x04 ) == 0x04) {
				$parent_visibility = 4;
			}
		}
		
		//$cssssssssat_visible = conGetCatVisible($parent, $lang);
		
		foreach ($languages as $value) {
			$sql = "INSERT INTO ".$cms_db['cat_lang']."
                         	(idcat, idlang, name, description, rewrite_use_automatic, rewrite_alias, visible, author, created, lastmodified)
				VALUES
                                 ('$idcat', '$value', '$name', '$description', '$rewrite_use_automatic', '$rewrite_alias', '$parent_visibility', '".$auth->auth['uid']."', '".time()."', '".time()."')";
			$db->query($sql);

			// kein Hauptordner, kopiert Rechte des "Mutter- Ordners"
			//if ($parent != '0') {
				//$perm->xcopy_perm($parent, 'cat', $idcat, 'cat', 0xFFFFFFFF, 0, $value, false);
				//$perm->xcopy_perm($parent, 'frontendcat', $idcat, 'cat', 0xFFFFFFFF, 0, $value, false);
			//}
		}

		// Ordner öffnen
		$sql = "INSERT INTO ".$cms_db['cat_expand']." (idusers, idcat) VALUES('".$auth->auth['uid']."', '$idcat')";
		$db->query($sql);

		// idcatlang für die Templateerstellung raussuchen
 		$sql = "SELECT idcatlang FROM ".$cms_db['cat_lang']." WHERE idcat='$idcat'";
		$db->query($sql);
		while ($db->next_record()) $tmp_idcatlang[] = $db->f('idcatlang');

		// Template erstellen
		if ($db->affected_rows()){
			foreach ($tmp_idcatlang as $value) con_config_tpl_save($idtpl, $idlay, $value, '', $idtplconf);
		}

		// Event
		fire_event('con_cat_new', array('idcat' => $idcat, 'name' => $name, 'parent' => $parent, 'idtpl' => $idtpl));
        // Content aus Cache löschen
        sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'content'));
		// Danach ins Frontend?
		if ($view) $url_location = $sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcat='.$idcat.'&idcatside='.$idcatside.'&view='.$view);
		else       $url_location = $sess->url("main.php?area=con");

	// Ordner aktualisieren
	} else {
		// Ordner umbenennen
		$sql = "UPDATE ".$cms_db['cat_lang']." SET name='$name', description='$description', 
				rewrite_use_automatic = '$rewrite_use_automatic', rewrite_alias = '$rewrite_alias', author='".$auth->auth['uid']."', 
				lastmodified='".time()."' WHERE idcat='$idcat' AND idlang='$lang'";
		$db->query($sql);

		// Template konfigurieren
		$have_perm_save_configdata = $perm->have_perm(11, 'cat', $idcat);
		if ($idtplconf == '0' && $idtpl != '0') {
			// idcatlang suchen
	 		$sql = "SELECT idcatlang FROM ".$cms_db['cat_lang'] ." WHERE idcat='$idcat' AND idlang='$lang'";
			$db->query($sql);

			while ($db->next_record()){
				$tmp_idcatlang[] = $db->f('idcatlang');			
			}

			// Template erstellen
			if (count($tmp_idcatlang) >0){
				foreach ($tmp_idcatlang as $value) con_config_tpl_save($idtpl, $idlay, $value, '', $idtplconf, $have_perm_save_configdata);
			}
		} else {
			con_config_tpl_save($idtpl, $idlay, $idcatlang, '', $idtplconf, $have_perm_save_configdata);
		}

		// rechte setzen
		if ($perm->have_perm(6, 'cat', $idcat)) {
			global $backend_cms_gruppenids, $backend_cms_gruppenrechte, $backend_cms_gruppenrechtegeerbt, $backend_cms_gruppenrechteueberschreiben;
			$perm->set_group_rights( 'cat', $idcat, $backend_cms_gruppenids, $backend_cms_gruppenrechte, $backend_cms_gruppenrechtegeerbt, $backend_cms_gruppenrechteueberschreiben, '', 0xFFFFFFFF);
		}
		if ($perm->have_perm(14, 'cat', $idcat)) {
			global $frontend_cms_gruppenids, $frontend_cms_gruppenrechte, $frontend_cms_gruppenrechtegeerbt, $frontend_cms_gruppenrechteueberschreiben;
			$perm->set_group_rights( 'frontendcat', $idcat, $frontend_cms_gruppenids, $frontend_cms_gruppenrechte, $frontend_cms_gruppenrechtegeerbt, $frontend_cms_gruppenrechteueberschreiben, '', 0xFFFFFFFF);
		}

		// Event
		fire_event('con_cat_edit', array('idcat' => $idcat, 'name' => $name));
        // Content aus Cache löschen
        sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'content'));
		// Danach ins Frontend?
		if ($view) {
			$url_location = $sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcat='.$idcat.'&idcatside='.$idcatside.'&view='.$view);
		} else {
			$url_location = $sess->url("main.php?area=con");
		}
	}

	// Cache-Group Frontend löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
	if ($use_redirect) {
		redirect_page($url_location);
	} 
	
	return $idcat;
}


// jb_todo: alle aufrufe
function con_config_side_save($idcat, $idside, $idtpl, $idtplconf, $idsidelang, $idcatside, $idcatnew
                                       , $author, $title, $meta_keywords, $summary, $online, $user_protected
                                       , $view, $created, $lastmodified, $startdate, $starttime, $enddate, $endtime
                                       , $meta_other, $meta_title, $meta_author, $meta_description, $meta_robots, $meta_redirect_time
                                       , $metasocial_title,$metasocial_image,$metasocial_description,$metasocial_author
                                       , $meta_redirect, $meta_redirect_url, $rewrite_use_automatic, $rewrite_url
                                       , $idlay, $use_redirect = true) {
	global $db, $client, $sess, $perm, $lang, $cms_db, $cfg_client, $cms_lang, $val_ct,$idcatside, $idside;
  
	if(! (is_numeric($idtpl) || is_int($idtpl) ) ) return;
	if(! (is_numeric($idtplconf) || is_int($idtplconf) ) ) return;
	
	if (empty($title)) {
		$title = $cms_lang['con_defaulttitle'];
	}
	
	$rewrite_use_automatic = ($rewrite_use_automatic > 0) ? 1 : 0;
	rewriteGenerateMapping();
	if($rewrite_use_automatic) {
		$rewrite_url = rewriteGenerateUrlString($title);
		$rewrite_url = rewriteMakeUniqueStringForLang('idcatside', $idcatside, $rewrite_url);
	} else {
		$rewrite_url = rewriteGenerateUrlString($rewrite_url, true);
	}
	
	
	// idcatside für rechte
	$idcatside_for_rights = $idcatside;
	$idcat_for_rights = $idcat;


	if (!is_array($idcatnew)) {
		$idcatnew['0'] = $idcat;
	}
	$start             = createDate($startdate, $starttime);
	$end               = createDate($enddate, $endtime);
  	$meta_redirect     = ($meta_redirect == '1') ? '1' : '0';
	$meta_redirect_url = ($meta_redirect_url == 'http://' || $meta_redirect_url == '') ? '' : $meta_redirect_url;
	set_magic_quotes_gpc($title);
	set_magic_quotes_gpc($summary);
	set_magic_quotes_gpc($meta_other);
  set_magic_quotes_gpc($meta_title);
  set_magic_quotes_gpc($meta_author);
	set_magic_quotes_gpc($meta_description);
	set_magic_quotes_gpc($meta_keywords);
	set_magic_quotes_gpc($meta_robots);
	set_magic_quotes_gpc($meta_redirect_url);
	set_magic_quotes_gpc($metasocial_title);
	set_magic_quotes_gpc($metasocial_image);
	set_magic_quotes_gpc($metasocial_description);
	set_magic_quotes_gpc($metasocial_author);
	
	if (empty($idside)) {
		//echo "new page";exit;
		// Seite erstellen
		$sql = "INSERT INTO $cms_db[side] (idclient) VALUES ('$client')";
		$db->query($sql);

		// neue idside suchen
		$idside = mysql_insert_id();
		

		// Seite in alle Ordner einfügen
		foreach ($idcatnew as $value) {
			//sortindex suchen
			$sql = "SELECT MAX(sortindex) AS sortindex FROM ".$cms_db['cat_side']." WHERE idcat='$value'";
			$db->query($sql);
			if($db->next_record()) {
				$sortindex = ($db->f('sortindex')+1);
			} else {
				$sortindex = 1;
			}
		
			$sql = "SELECT * FROM $cms_db[cat_side] WHERE idcat='$value' AND is_start='1'";
			$db->query($sql);
			$is_start = ($db->next_record()) ? '0' : '1';
			$sql = "INSERT INTO $cms_db[cat_side] (idcat, idside, sortindex, is_start) VALUES ('$value', '$idside', '$sortindex', '$is_start')";
			$db->query($sql);
		}

		// idcatside suchen
		$sql = "SELECT idcatside FROM $cms_db[cat_side] WHERE idside='$idside'";
		$idcatside = array();
		getIdList($sql, $idcatside, '', 'idcatside');

		// für jede Sprache erstellen
		$a_languages = get_languages_by_client($client);
		foreach ($a_languages as $tmp_lang) {
			if ($tmp_lang == $lang)	{
				$side_online = $online;
				$side_start  = $start;
				$side_end    = $end;
			} else {
				$side_online = 0;
				$side_start  = time();
				$side_end    = time();
			}
			//TODO Problems to update template in multilang pages
			$catobject =& sf_factoryGetObject('PAGE', 'Cat');
			$catobject->loadByIdcatIdlang($idcatnew['0'], $tmp_lang);
			$cat_is_protected = $catobject->getIsProtected();
			if($cat_is_protected) {
				$side_online = ($side_online | 0x04);
			}

			if ($tmp_lang == $lang)	{
        	    $tmp_meta_title = $meta_title;
              $tmp_meta_title = $meta_other;
              $tmp_meta_description = $meta_description;
	            $tmp_meta_keywords = $meta_keywords;
            	$tmp_meta_robots = $meta_robots;
			} else {

    	        $cfg_lang = $val_ct -> get_by_group('cfg_lang', $client, $tmp_lang);
        	    $tmp_meta_title = htmlentities($cfg_lang['meta_title'], ENT_COMPAT, 'UTF-8');
              $tmp_meta_other = htmlentities($cfg_lang['meta_other'], ENT_COMPAT, 'UTF-8');
              $tmp_meta_description = htmlentities($cfg_lang['meta_description'], ENT_COMPAT, 'UTF-8');
	            $tmp_meta_keywords = htmlentities($cfg_lang['meta_keywords'], ENT_COMPAT, 'UTF-8');
            	$tmp_meta_robots = htmlentities($cfg_lang['meta_robots'], ENT_COMPAT, 'UTF-8');
            }

	
			$sql  = 'INSERT INTO ' . $cms_db['side_lang'];
			$sql .= ' (idside, idlang, title, meta_keywords, summary, created, lastmodified, author, meta_redirect, meta_redirect_url,';
			$sql .= ' user_protected, online, start, end, meta_title,meta_other,meta_author, meta_description, meta_robots, meta_redirect_time, rewrite_use_automatic, rewrite_url,metasocial_title,metasocial_image,metasocial_description,metasocial_author) ';
			$sql .= 'VALUES (';
			$sql .= " '$idside', '$tmp_lang', '$title', '$tmp_meta_keywords', '$summary', '$created', '$lastmodified', '$author', ";
			$sql .= " '$meta_redirect', '$meta_redirect_url', '$user_protected', '$side_online', '$side_start', '$side_end', ";
			$sql .= " '$meta_title','$meta_other','$meta_author', '$tmp_meta_description', '$tmp_meta_robots', '$meta_redirect_time', '$rewrite_use_automatic', '$rewrite_url','$metasocial_title','$metasocial_image','$metasocial_description','$metasocial_author')";

      
      $db->query($sql);
		}

		// idsidelang für die Templateerstellung raussuchen
 		$sql = "SELECT idsidelang FROM ".$cms_db['side_lang']." WHERE idside='$idside'";
		$tmp_idsidelang = array();
		$affectedrows = getIdList($sql, $tmp_idsidelang, '', 'idsidelang');
		//print_r($tmp_idsidelang);exit;
		// Template erstellen
		if ($affectedrows) {
			foreach ($tmp_idsidelang as $value) {
				con_config_tpl_save($idtpl, $idlay, '', $value, $idtplconf);
			}
			//exit;
		}

		// Seite für Frontend erzeugen
		if ($cfg_client['publish'] == '1') {
			foreach ($a_languages as $tmp_lang) {
				$sql = 'INSERT INTO ' . $cms_db['code'] . "(idlang, idcatside, changed) VALUES ('$tmp_lang', '".$idcatside['0']."', '1')";
				$db->query($sql);
			}
		}

		// Event
		fire_event('con_side_new', array('idside' => $idside, 'name' => $title));
        // Content aus Cache löschen
        sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'content'));
		// Danach ins Frontend?
		// ermittle redirect-url
		if ($view) $url_location = $sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcatside='.$idcatside['0'].'&view='.$view);
		else $url_location = $sess->url("main.php?area=con_editframe&idcatside=".$idcatside['0']);
		
		$idcatside = $idcatside['0'];
	} else {
		// handle $online-Angabe
		// 0 -> offline setzen
		// 1 -> online setzen
		// 2 -> zeitsteuerung setzen
		switch ((int)$online) {
			case   0:
				$change_online = 'online & 0xFC';
				break;
			case   1:
				$change_online = '((online & 0xFC) | 0x01)';
				break;
			case   2:
				$change_online = '((online & 0xFC) | 0x02)';
				break;
			default:
				$change_online = '0';
				break;
		}

		// update der 'side_lang' Tabelle
		$sql  = 'UPDATE ' . $cms_db['side_lang']. ' ';
		$sql .= 'SET';                                   
		$sql .= " title='$title', meta_keywords='$meta_keywords', summary='$summary', meta_redirect='$meta_redirect', ";
		$sql .= " meta_redirect_url='$meta_redirect_url', user_protected = '$user_protected', online = $change_online, start='$start', ";
		$sql .= " end='$end', meta_title='$meta_title',meta_other='$meta_other',meta_author='$meta_author', meta_description='$meta_description', meta_robots='$meta_robots', ";
		$sql .= " meta_redirect_time = '$meta_redirect_time', rewrite_use_automatic = '$rewrite_use_automatic', rewrite_url = '$rewrite_url',metasocial_title='$metasocial_title',metasocial_image='$metasocial_image',metasocial_description='$metasocial_description',metasocial_author='$metasocial_author' ";
		$sql .= 'WHERE idsidelang = ' . $idsidelang;
		$db->query($sql);
		
		// in welchem Ordner existiert die Seite?
		$sql = 'SELECT idcat FROM ' . $cms_db['cat_side'] . ' WHERE idside = ' . $idside;
		$tmp_idcat = array();
		getIdList($sql, $tmp_idcat, 'idcat');

		if (is_array($tmp_idcat)) {
			// Seite in neue Ordner einfügen
			foreach ($idcatnew as $value) {
				if (!in_array($value,$tmp_idcat)) {
					$sql = 'SELECT * FROM ' . $cms_db['cat_side'] . ' WHERE idcat = ' . $value . ' AND is_start = 1';
					$db->query($sql);
					$is_start = ($db->next_record()) ? '0' : '1';
					
					//sortindex suchen 
					$sql = "SELECT MAX(sortindex) AS sortindex FROM ".$cms_db['cat_side']." WHERE idcat='$value'";
					$db->query($sql);
					if($db->next_record()) {
						$sortindex = ($db->f('sortindex')+1);
					} else {
						$sortindex = 1;
					}
						
					if($value == $idcatnew['0'] && !in_array($idcat,$idcatnew)) {
		                $sql  = 'UPDATE ' . $cms_db['cat_side'] . ' ';
						$sql .= 'SET';
						$sql .= ' idcat    = ' . $value    . ',';
						$sql .= ' sortindex    = ' . $sortindex    . ',';
						$sql .= ' is_start = ' . $is_start . ' ';
						$sql .= 'WHERE  idcat = ' . $idcat;
						$sql .= ' AND  idside = ' . $idside;
						$db->query($sql);
						if (in_array($idcat,$idcatnew)) {
							unset($tmp_idcat[$idcat]);
						}
						//alte kategorie neu sortieren
						if (! function_exists('con_reindex_page_sort') ) {
						  include_once('inc/fnc.con.php');	
					    }
						con_reindex_page_sort($idcat);
					} else {
						//sortindex suchen 
						$sql = "SELECT MAX(sortindex) AS sortindex FROM ".$cms_db['cat_side']." WHERE idcat='$value'";
						$db->query($sql);
						if($db->next_record()) {
							$sortindex = ($db->f('sortindex')+1);
						} else {
							$sortindex = 1;
						}
						$sql  = 'INSERT INTO ' . $cms_db['cat_side'] . ' ';
						$sql .= ' (idcat , idside , is_start, sortindex) ';
						$sql .= 'VALUES';
						$sql .= " ($value, $idside, $is_start, $sortindex) ";
						$db->query($sql);
					}
				}
			}

			// Seite aus nicht benutzen Ordnern löschen
			// jb_todo: rechte löschen??
			foreach ($tmp_idcat as $value) {
				if (!in_array($value,$idcatnew)) {

				  	// suche alle idcatsides, die nicht mehr existieren
					$sql = 'SELECT idcatside FROM ' . $cms_db['cat_side'] . " WHERE idcat='$value' AND idside='$idside'";
					$db->query($sql);
					$db->next_record();

					// lösche alte 'code' Einträge
					$sql = 'DELETE FROM ' . $cms_db['code'] . " WHERE idcatside='".$db->f('idcatside')."'";
					$db->query($sql);

					// lösche alte 'cat_side' Einträge
					$sql = 'DELETE FROM ' . $cms_db['cat_side'] . " WHERE idside='$idside' AND idcat='$value'";
					$db->query($sql);

				  	// falls kein Startartikel mehr vorhanden neuen setzen
					$sql = 'SELECT * FROM ' . $cms_db['cat_side'] . " WHERE idcat='$value' AND is_start='1'";
					$db->query($sql);
					if (!$db->affected_rows()) {
						$sql = 'UPDATE ' . $cms_db['cat_side'] . " SET is_start = '1' WHERE idcat='$value' ORDER BY sortindex LIMIT 1";
						$db->query($sql);
					}
					
					//sortindex neu sortieren
					if (! function_exists('con_reindex_page_sort') ) {
						include_once('inc/fnc.con.php');	
					}
					con_reindex_page_sort($value);

					// jb_todo:
					// lösche alte 'tpl_conf' Einträge
					// muß noch eingetragen werden

					//Event
					fire_event('get_unused_idcatside_by_save_side', array ('idside' => $idside, 'idcat' => $value, 'idcatside' => $db->f('idcatside')));
				}
			}
		}

		// Template konfigurieren
		$have_perm_save_configdata = $perm->have_perm(27, 'side', $idcatside_for_rights, $idcat_for_rights);
//		if ($idtplconf == '0' && $idtpl != '0') {
//	 		$sql = 'SELECT idsidelang FROM ' . $cms_db['side_lang'] . " WHERE idside = $idside";
//			$tmp_idsidelang = array();
//			$affected_rows = getIdList($sql, $tmp_idsidelang, '', 'idsidelang');
//			//print_r($tmp_idsidelang);exit;
//	
//			// Template erstellen
//			if ($affected_rows) {
//				foreach ($tmp_idsidelang as $value) {
//					con_config_tpl_save($idtpl, $idlay, '', $value, $idtplconf, $have_perm_save_configdata);
//				}
//			}
//		} 
//		else {
			con_config_tpl_save($idtpl, $idlay, '', $idsidelang, $idtplconf, $have_perm_save_configdata);
//		}

		// Rechte setzen
		if ($perm->have_perm(22, 'side', $idcatside_for_rights, $idcat_for_rights)) {
			global $backend_cms_gruppenids, $backend_cms_gruppenrechte, $backend_cms_gruppenrechtegeerbt, $backend_cms_gruppenrechteueberschreiben;
			$perm->set_group_rights( 'side', $idcatside_for_rights, $backend_cms_gruppenids, $backend_cms_gruppenrechte, $backend_cms_gruppenrechtegeerbt, $backend_cms_gruppenrechteueberschreiben, '', 0x7FFD0000, $idcat_for_rights, 0x7FFD0000);
		}
		if ($perm->have_perm(14, 'cat', $idcat_for_rights)) {
			global $frontend_cms_gruppenids, $frontend_cms_gruppenrechte, $frontend_cms_gruppenrechtegeerbt, $frontend_cms_gruppenrechteueberschreiben;
			$perm->set_group_rights( 'frontendpage', $idcatside_for_rights, $frontend_cms_gruppenids, $frontend_cms_gruppenrechte, $frontend_cms_gruppenrechtegeerbt, $frontend_cms_gruppenrechteueberschreiben,'', 0xFFFFFFFFF, $idcat_for_rights, 0xFFFFFFFF);
		}

		// Codestatus ändern
		change_code_status($idcatside_for_rights, 1, 'idcatside');

		// Event
		fire_event('con_side_edit', array('idside' => $idside, 'name' => $title));
        // Content aus Cache löschen
        sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend', 'content'));
		// ermittle redirect-url
		if ($view) $url_location = $sess->url($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcatside='.$idcatside.'&view='.$view);
		else $url_location = $sess->url('main.php?area=con');
	}

	// Cache-Group Frontend löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
	if ($use_redirect) {
		redirect_page($url_location);
	}
}

function getIdList($sql, &$array_for_ids, $idfield, $valfield = '') {
	global $db;

	// ermittle werte-feld und verhindere einen ungültigen namen
	$value_field = (empty($valfield)) ? $idfield:	$valfield;
	if (empty($value_field)) return 0;

	// führe sql aus und lade die gewünschten werte ins array
	$db->query($sql);
	if (empty($idfield)) {
		// erstelle array durch anhängen der neuen werte
		while ($db->next_record()) $array_for_ids[] = $db->f($value_field);
	} else {
		// erstelle array mit benannten elementen
		while ($db->next_record()) $array_for_ids[$db->f($idfield)] = $db->f($value_field);
	}
	return ($db->affected_rows());
}

function createDate($dateval, $timeval) {
	// datumswert vorbesetzen
	$datum = time();

	// zerlege übergabetexte
	$arr_date = @explode('.', $dateval);
	$arr_time = @explode(':', $timeval);

	// prüfe ob felder vorhanden, dann erzeuge datum
	if ($arr_time[0] && $arr_time[1] && $arr_date[1] && $arr_date[0] && $arr_date[2]) {
		$datum = mktime($arr_time[0], $arr_time[1], 0, $arr_date[1], $arr_date[0], $arr_date[2] );
	}

	// datumswert zurückliefern
	return $datum;
}

function redirect_page($url_location) {
	$url_location = str_replace('&amp;', '&', $url_location);
	header ('HTTP/1.1 302 Moved Temporarily');
	header ('Location:' . $url_location );
	exit;
}
?>
