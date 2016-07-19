<?PHP
// File: $Id: fnc.lang.php 52 2008-07-20 16:16:33Z bjoern $
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

function lang_new_language($idclient, $name, $desc, $charset, $rewrite_key, $rewrite_mapping, $copy_content = true) {
	global $db, $sess, $auth, $cms_db, $lang, $user_msg;

	set_magic_quotes_gpc($name);
	set_magic_quotes_gpc($desc);
	set_magic_quotes_gpc($rewrite_key);
	set_magic_quotes_gpc($rewrite_mapping);
	$iso_3166_code = strlen($_REQUEST['iso_3166']) >8 ? '': $_REQUEST['iso_3166'] ;
	$is_start = '0';

	// Eintrag in 'lang' Tabelle
	$from_lang = $lang;
	$sql = "INSERT INTO ". $cms_db['lang'] ."
				(name, description, author, charset, iso_3166_code, rewrite_key, rewrite_mapping, is_start, created, lastmodified)
			VALUES
				('$name', '$desc', '".$auth->auth['uid']."', '".$charset."',
					 '".$iso_3166_code."', '".$rewrite_key."', '".$rewrite_mapping."', '".$is_start."', '".time()."', '".time()."')";
	$db->query($sql);
	$lang = mysql_insert_id();

	// Eintrag in 'clients_lang' Tabelle
	$sql = "INSERT INTO 
				".$cms_db['clients_lang']." 
				(idclient, idlang, author, created, lastmodified) 
			VALUES 
				('$idclient','$lang', '".$auth->auth['uid']."', '".time()."', '".time()."')";
	$db->query($sql);

	// Webseitencontent duplizieren
	if ($from_lang != '' && $copy_content) {
		$db2 =& new DB_cms;
		$db3 =& new DB_cms;
		
		// Zeitinterval vergrößern
		@set_time_limit(0);

		// Ordner kopieren
		$sql = "SELECT 
					idcat, name, description, author,  visible
				FROM 
					". $cms_db['cat_lang'] ." 
				WHERE 
					idlang='$from_lang'";
		$db->query($sql);
		while ($db->next_record()) {
			$name = make_string_dump($db->f('name'));
			$description = make_string_dump($db->f('description'));
			// setze kopierte Seite auf Offline, keine Übernahme der Zeitsteuerung
			// übernehme aber das Bit für Schutzrechte incl. der zukünftigen Level
			$online = ((int) $db->f('visible') & 0xFE);
			$sql2 = "INSERT INTO 
						".$cms_db['cat_lang']." 
						(idcat, idlang, idtplconf, name, description, 
							visible, author, created, lastmodified) 
					VALUES 
						('".$db->f('idcat')."', '$lang', '0', '$name', '$description', '$online', 
							'".$db->f('author')."', '".time()."', '".time()."')";
			$db2->query($sql2);
		}

		// Seiten kopieren
		$sql = "SELECT * FROM 
					".$cms_db['side_lang']." 
				WHERE 
					idlang='$from_lang'";
		$db->query($sql);
		while ($db->next_record()) {
			$title = make_string_dump($db->f('title'));
			$summary = make_string_dump($db->f('summary'));
			$meta_other = make_string_dump($db->f('meta_other'));
      $meta_title = make_string_dump($db->f('meta_title'));
      $meta_author = make_string_dump($db->f('meta_author'));
			$meta_description = make_string_dump($db->f('meta_description'));
			$meta_keywords = make_string_dump($db->f('meta_keywords'));
			$meta_robots = make_string_dump($db->f('meta_robots'));
			$meta_redirect_url = make_string_dump($db->f('meta_redirect_url'));
			// change JB
			// setze kopierte Seite auf Offline, keine Übernahme der Zeitsteuerung
			// übernehme aber das Bit für Schutzrechte incl. der zukünftigen Level
			$online = ((int) $db->f('online') & 0xFC);
			$sql2 = "INSERT INTO 
						".$cms_db['side_lang']." 
						(idside, idlang, idtplconf, title, meta_title,meta_other,meta_keywords, summary, online, 
							meta_redirect, meta_redirect_url, author, created, 
							lastmodified, user_protected, visited, edit_ttl, meta_author, 
							meta_description, meta_robots, meta_redirect_time,metasocial_title,metasocial_image,metasocial_description,metasocial_author) 
					VALUES 
						('".$db->f('idside')."', '$lang', '0', '$title', '$meta_title','$meta_other','$meta_keywords', '$summary', '$online', 
							'".$db->f('meta_redirect')."', '$meta_redirect_url', '".$db->f('author')."', '".time()."', 
							'".time()."', '".$db->f('user_protected')."', '".$db->f('visited')."', '".$db->f('edit_ttl')."', '$meta_author', 
							'$meta_description', '$meta_robots', '".$db->f('meta_redirect_time')."'
							, '".$db->f('metasocial_title')."', '".$db->f('metasocial_image')."', '".$db->f('metasocial_description')."', '".$db->f('metasocial_author')."')";
			// change JB
			$db2->query($sql2);
		}
		
		//
		// template config für kategorien kopieren
		//
		$sql = "SELECT
					CL.idcat, CL.idtplconf,
					TC.idtpl,
					CC.idcontainer, CC.config, CC.view, CC.edit
				FROM 
					".$cms_db['cat_lang']." CL
					LEFT JOIN ".$cms_db['tpl_conf']." TC USING(idtplconf)
					LEFT JOIN ".$cms_db['container_conf']." CC USING(idtplconf)
				WHERE
					CL.idlang='$from_lang'";
		$current_idcat = 0;
		//echo $sql .'<br />';
		$db->query($sql);
		while ($db->next_record() ) {
			// create new idtplconf
			// update new idtplconf to table cat_lang
			if ($current_idcat != $db->f('idcat')) {
				$current_idcat = $db->f('idcat');
				//insert idtplconf in config template
				$sql2 = "INSERT INTO 
							".$cms_db['tpl_conf']." (idtpl) VALUES('".$db->f('idtpl')."')";
				
				//echo $sql2 .'<br />';
				$db2->query($sql2);
				$current_idtplconf = mysql_insert_id(); 
				
				$sql2 = "UPDATE 
							".$cms_db['cat_lang']."
						SET
							idtplconf = '$current_idtplconf'
						WHERE 
							idlang = '$lang'
						 	AND idcat = '$current_idcat'";
				//echo $sql2 .'<br />';
				$db2->query($sql2);
			}
			
			$sql2 = "INSERT INTO 
						".$cms_db['container_conf']." 
							(idtplconf, idcontainer, config, view, edit)
						VALUES('$current_idtplconf', '".$db->f('idcontainer')."', '".make_string_dump($db->f('config'))."',
								'".$db->f('view')."', '".$db->f('edit')."')";
			//echo $sql2 .'<br /><br /><br />';
			$db2->query($sql2);
		}
		
		//
		// template config für seiten kopieren
		//
		$sql = "SELECT
					SL.idside, SL.idtplconf,
					TC.idtpl,
					CC.idcontainer, CC.config, CC.view, CC.edit
				FROM 
					".$cms_db['side_lang']." SL
					LEFT JOIN ".$cms_db['tpl_conf']." TC USING(idtplconf)
					LEFT JOIN ".$cms_db['container_conf']." CC USING(idtplconf)
				WHERE
					SL.idlang='$from_lang'
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
							idlang = '$lang'
						 	AND idside = '$current_idside'";
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
		
		//
		// content kopieren
		//
		
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
						SL.idlang='$from_lang'";
			$db->query($sql);
			
			while ($db->next_record() ) {
				$sql2 = "SELECT 
							SL.idsidelang 
						FROM 
							".$cms_db['side_lang']." SL 
						WHERE
							SL.idlang='$lang'
							AND SL.idside = '".$db->f('idside')."'";
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
		
		//generate rewrite urls
		include_once('inc/fnc.mod_rewrite.php');
		rewriteAutoForAll($lang);
		
		
	}

	//Metaangaben für Sprache
	$sql = "INSERT INTO ". $cms_db['values'] ." (idclient, idlang, group_name, key1, 
			conf_sortindex, conf_desc_langstring, conf_head_langstring, conf_input_type, 
			conf_input_type_val, conf_input_type_langstring, conf_visible) 
			VALUES ($idclient, $lang, 'cfg_lang', 'meta_title', 
			601, 'set_meta_title', '', 'txt', NULL, NULL, 1)";
	$db->query($sql);
  
  $sql = "INSERT INTO ". $cms_db['values'] ." (idclient, idlang, group_name, key1, 
			conf_sortindex, conf_desc_langstring, conf_head_langstring, conf_input_type, 
			conf_input_type_val, conf_input_type_langstring, conf_visible) 
			VALUES ($idclient, $lang, 'cfg_lang', 'meta_other', 
			601, 'set_meta_other', '', 'txt', NULL, NULL, 1)";
	$db->query($sql);
  
  $sql = "INSERT INTO ". $cms_db['values'] ." (idclient, idlang, group_name, key1, 
				conf_sortindex, conf_desc_langstring, conf_head_langstring, conf_input_type, 
				conf_input_type_val, conf_input_type_langstring, conf_visible) 
				VALUES ($idclient, $lang, 'cfg_lang', 'meta_description', 
				600, 'set_meta_description', 'set_meta', 'txt', NULL, NULL, 1)";
	$db->query($sql);
	
	$sql = "INSERT INTO ". $cms_db['values'] ." (idclient, idlang, group_name, key1, 
			conf_sortindex, conf_desc_langstring, conf_head_langstring, conf_input_type, 
			conf_input_type_val, conf_input_type_langstring, conf_visible) 
			VALUES ($idclient, $lang, 'cfg_lang', 'meta_keywords', 
			601, 'set_meta_keywords', '', 'txt', NULL, NULL, 1)";
	$db->query($sql);
	
	$sql = "INSERT INTO ". $cms_db['values'] ." (idclient, idlang, group_name, key1, value,
			conf_sortindex, conf_desc_langstring, conf_head_langstring, conf_input_type, 
			conf_input_type_val, conf_input_type_langstring, conf_visible) 
			VALUES ($idclient, $lang, 'cfg_lang', 'meta_robots', 'index, follow', 
			602, 'set_meta_robots', '', 'txt', NULL, NULL, 1)";
	$db->query($sql);
	
	//langstring for new client success userinfo
	$lang = $from_lang;
	$user_msg = 'success_new_lang';
}

function lang_make_start_lang($idclient, $idlang) {
	global $db, $cms_db;
	
	$langs = clients_get_langs($idclient);
	if (! is_array($langs['order'])) {
		return;
	} else {
		$in_op = implode(',', $langs['order']);
	}
	
	//reset all
	$sql = "UPDATE
				".$cms_db['lang'] ."
			SET
				is_start='0',
				author='".$auth->auth['uid']."',
				lastmodified='".time()."'
			WHERE
				idlang IN ($in_op)";

	$db->query($sql);
	
	//make start
	$sql = "UPDATE
				".$cms_db['lang'] ."
			SET
				is_start='1',
				author='".$auth->auth['uid']."',
				lastmodified='".time()."'
			WHERE
				idlang='$idlang'";

	$db->query($sql);
}

function lang_rename_language($idlang, $name, $desc, $charset, $rewrite_key, $rewrite_mapping)
{
	global $db, $auth, $cms_db, $perm;

	set_magic_quotes_gpc($name);
	set_magic_quotes_gpc($desc);
	set_magic_quotes_gpc($rewrite_key);
	set_magic_quotes_gpc($rewrite_mapping);
	$iso_3166_code = strlen($_REQUEST['iso_3166']) > 8 ? '': $_REQUEST['iso_3166'] ;

	$sql = "UPDATE
				".$cms_db['lang'] ."
			SET
				name='$name',
				description='$desc',
				charset='$charset',
				iso_3166_code='$iso_3166_code',
				rewrite_key='$rewrite_key',
				rewrite_mapping='$rewrite_mapping',
				author='".$auth->auth['uid']."',
				lastmodified='".time()."'
			WHERE
				idlang='$idlang'";

	$db->query($sql);

	//Rechte setzen
	if ($perm->have_perm(22, 'clientlangs', $idlang)) {
		global $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben;
		$perm->set_group_rights( 'clientlangs', $idlang, $cms_gruppenids, $cms_gruppenrechte, $cms_gruppenrechtegeerbt, $cms_gruppenrechteueberschreiben );
	}
}

function lang_delete_language($idclient, $idlang) {
	global $db, $sess, $cms_db;

	//************ check if there are still sides online
	// change JB: Berücksichtigt auch die zeitgesteuerten Seiten
	$sql = "SELECT * FROM $cms_db[side_lang] A LEFT JOIN $cms_db[side] B USING(idside) WHERE B.idclient='$idclient' AND idlang!='0' AND idlang='$idlang' AND (online & 0x03) > 0x00";
	$db->query($sql);
	if ($db->next_record()) {
		 return "0802";	//Deleting not possible because of online sides or visible categories
	}

	//************ check if there are visible categories
	// change JB: Berücksichtigt auch die zeitgesteuerten Seiten
	$sql = "SELECT * FROM $cms_db[cat_lang] A LEFT JOIN $cms_db[cat] B USING(idcat) WHERE B.idclient='$idclient' AND idlang!='0' AND idlang='$idlang' AND (visible & 0x03) > 0x00";
	$db->query($sql);
	if ($db->next_record()) return "0802"; //Deleting not possible because of online sides or visible categories


	// keine seiten oder kategorien online ... löschen
	// Event feuern
	fire_event('delete_lang', array ('idclient'    => $idclient,
					 'idlang'      => $idlang));

	//********* check if this is the clients last language to be deleted, if yes delete from side, cat, and cat_side as well *******
	$last_language = 0;
	$sql = "SELECT COUNT(*) FROM $cms_db[clients_lang] WHERE idclient='$idclient'";
	$db->query($sql);
	$db->next_record();
	if ($db->f(0) == 1) {
		$lastlanguage = 1;
	}

	//********** delete from 'side_lang'-table *************
	$sql = "SELECT idsidelang, A.idside FROM $cms_db[side_lang] A LEFT JOIN $cms_db[side] B USING(idside) WHERE B.idclient='$idclient' AND idlang!='0' AND idlang='$idlang'";
	$db->query($sql);
	while ($db->next_record()) {
		$a_idsidelang[] = $db->f('idsidelang');
		$a_idside[] = $db->f('idside');
	}
	if (is_array($a_idsidelang)) {
		foreach ($a_idsidelang as $value) {
			$sql = "DELETE FROM $cms_db[side_lang] WHERE idsidelang='$value'";
			$db->query($sql);

			$sql = "DELETE FROM $cms_db[content] WHERE idsidelang='$value'";
			$db->query($sql);
		}
	}
	if ($lastlanguage == 1) {
		if (is_array($a_idside)) {
			foreach ($a_idside as $value) {
				$sql = "DELETE FROM $cms_db[side] WHERE idside='$value'";
				$db->query($sql);
				$sql = "DELETE FROM $cms_db[cat_side] WHERE idside='$value'";
				$db->query($sql);
			}
		}
	}

	//********** delete from 'cat_lang'-table *************
	$sql = "SELECT idcatlang, A.idcat FROM $cms_db[cat_lang] A LEFT JOIN $cms_db[cat] B USING(idcat) WHERE B.idclient='$idclient' AND idlang!='0' AND idlang='$idlang'";
	$db->query($sql);
	while ($db->next_record()) {
		$a_idcatlang[] = $db->f("idcatlang");
		$a_idcat[] = $db->f("idcat");
	}
	if (is_array($a_idcatlang)) {
		foreach ($a_idcatlang as $value) {
			$sql = "DELETE FROM $cms_db[cat_lang] WHERE idcatlang='$value'";
			$db->query($sql);
		}
	}
	if ($lastlanguage == 1) {
		if (is_array($a_idcat)) {
			foreach ($a_idcat as $value) {
				$sql = "DELETE FROM $cms_db[cat] WHERE idcat='$value'";
				$db->query($sql);
			}
		}
	}

	// Cache-Group Frontend löschen
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));

	// delete cfg_lang entries from 'cms_values'-table
	$sql = "DELETE FROM ".$cms_db['values']." WHERE idlang='$idlang' AND  group_name = 'cfg_lang'";
	$db->query($sql);
	
	// delete from 'code'-table
	$sql = "DELETE FROM $cms_db[code] WHERE idlang='$idlang'";
	$db->query($sql);


	// delete from 'clients_lang'-table
	$sql = "DELETE FROM $cms_db[clients_lang] WHERE idclient='$idclient' AND idlang='$idlang'";
	$db->query($sql);

	// delete from 'lang'-table
	$sql = "DELETE FROM $cms_db[lang] WHERE idlang='$idlang'";
	$db->query($sql);

	// delete from 'perm'-table
	$sql = "DELETE FROM $cms_db[perms] WHERE idlang='$idlang'";
	$db->query($sql);
}
?>
