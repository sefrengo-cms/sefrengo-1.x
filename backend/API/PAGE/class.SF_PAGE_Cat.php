<?php
class SF_PAGE_Cat extends SF_API_Object {
	var $data = array(
					'sql' => array(		
						'cat_lang' => array(
							'idcatlang' => '',
							'idcat' => '',
							'idlang' => '',
							'idtplconf' => '',
							'name' => '',
							'description' => '',
							'rewrite_use_automatic' => '',
							'rewrite_alias' => '',
							'visible' => '',
							'author' => '',
							'created' => '',
							'lastmodified' => ''),
						'cat' => array(
							'idcat' => '',
							'parent' => '',
							'rootparent' => '',
							'sortindex' => '',
							'idclient' => ''
						),
					),
					'advanced' => array('is_online' => '', 
										'is_protected' => '',
										'copy_to_idcat' => -1,
										));
	
	var $dirty = false;
	
	var $db;
	
	var $_set_langprefix_on_save_if_new = false;
	
	function SF_PAGE_Cat () {
		$this->db =& sf_factoryGetObjectCache('DATABASE', 'Ado');
	}
	
	function loadByIdcatIdlang($idcat, $idlang) {
		global $cms_db;
		
		$idcat = (int) $idcat;
		$idlang = (int) $idlang;
		
		if ($idcat < 1 || $idlang < 1) {
			return false;
		}
		
		$sql = "SELECT *
				FROM
					".$cms_db['cat_lang']." CL LEFT JOIN
					".$cms_db['cat']." C USING(idcat)
				WHERE 
					CL.idcat = '".$idcat."'
					AND CL.idlang   = '".$idlang."'";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
			
		$this->data['sql']['cat_lang'] = array(
											'idcatlang' => (int) $rs->fields['idcatlang'],
											'idcat' => (int) $rs->fields['idcat'],
											'idlang' => (int) $rs->fields['idlang'],
											'idtplconf' => (int) $rs->fields['idtplconf'],
											'name' => $rs->fields['name'],
											'description' => $rs->fields['description'],
											'rewrite_use_automatic' => (int) $rs->fields['rewrite_use_automatic'],
											'rewrite_alias' => $rs->fields['rewrite_alias'],
											'visible' => (int) $rs->fields['visible'],
											'author' => (int) $rs->fields['author'],
											'created' => $rs->fields['created'],
											'lastmodified' => $rs->fields['lastmodified']
										);
		
		$this->data['sql']['cat'] = array(
										'idcat' => (int) $rs->fields['idcat'],
										'parent' => (int) $rs->fields['parent'],
										'rootparent' => (int) $rs->fields['rootparent'],
										'sortindex' => (int) $rs->fields['sortindex'],
										'idclient' => (int) $rs->fields['idclient'],
									);
		
		$this->data['advanced']['is_online'] = ( ($this->data['sql']['cat_lang']['visible'] & 0x01 ) == 0x01) ? true:false;
		$this->data['advanced']['is_protected'] = ( ($this->data['sql']['cat_lang']['visible'] & 0x04 ) == 0x04) ? true:false;
		
		$this->dirty = false;
		return true;
	}

		
	function getIdcatlang() { return $this->data['sql']['cat_lang']['idcatlang']; } 
	function getIdcat() { return $this->data['sql']['cat_lang']['idcat']; } 
	function getIdlang() { return $this->data['sql']['cat_lang']['idlang']; } 
	function getIdtplconf() { return $this->data['sql']['cat_lang']['idtplconf']; } 
	function getTitle() { return $this->data['sql']['cat_lang']['name']; } 
	function getDescription() { return $this->data['sql']['cat_lang']['description']; } 
	function getRewriteUseAutomatic() { return $this->data['cat_lang']['rewrite_use_automatic']; } 
	function getRewriteAlias() { return $this->data['cat_lang']['rewrite_alias']; } 
	function getVisible() { return $this->data['sql']['cat_lang']['visible']; } 
	function getIduser() { return $$this->data['cat_lang']['author']; } 
	function getCreatedTimestamp() { return $$this->data['cat_lang']['created']; } 
	function getLastmodifiedTimestamp() { return $$this->data['cat_lang']['lastmodified']; } 
	
	function getParent() { return $this->data['sql']['cat']['parent']; } 
	function getRootparent() { return $this->data['sql']['cat']['rootparent']; } 
	function getSortindex() { return $this->data['sql']['cat']['sortindex']; } 
	function getIdclient() { return $this->data['sql']['cat']['idclient']; } 

	function getIsOnline() { return $this->data['advanced']['is_online']; } 
	function getIsProtected() { return $this->data['advanced']['is_protected']; }
	
	function setTitle($val) { return $this->_set('sql', 'cat_lang', 'name', $val);} 
	function setDescription($val) { return $this->_set('sql', 'cat_lang', 'description', $val);}  
	
	function save() {
		global $cms_db;
		
		if (! $this->dirty) {
			return false;
		}

		
		//TODO change code status
		
		//Tabelle: side
		//- wenn neuer datensatz, idside anlegen	
		//Tabelle: side_lang
		//- ändern oder neu anlegen
		//- wenn neu: für alle sprachen anlegen, in anderen sprachen offline anlegen
		$current_time = time();
		$catinfos =& sf_factoryGetObjectCache('PAGE', 'Catinfos');
		$catinfos->setIdlang($this->data['sql']['cat_lang']['idlang']);
		$catinfos->generate();
		//print_r($this->data);exit;
		if ($this->data['sql']['cat']['parent'] < 1) {
			$this->data['sql']['cat']['parent'] = 0;
			$this->data['sql']['cat']['rootparent'] = $this->data['sql']['cat']['idcat'];
		}
		
		//new cat
		if ($this->data['sql']['cat']['idcat'] < 1) {
			
			
		//table cat
			
			//first run
			$record = $this->data['sql']['cat'];
			$record['idcat'] = false;
			$record['created'] = $current_time;
			$record['lastmodified'] = $current_time;
			$this->db->AutoExecute($cms_db['cat'], $record, 'INSERT');
			$this->data['sql']['cat']['idcat'] = $this->db->Insert_ID();
			$this->data['sql']['cat_lang']['idcat'] = $this->db->Insert_ID();
			
			//echo $this->data['sql']['cat_lang']['idcat'];
			unset($record);
			//second run
			$record['idcat'] = $this->data['sql']['cat']['idcat'];
			$record['rootparent'] = $this->data['sql']['cat']['rootparent'] = $this->_getRootparent($this->data['sql']['cat']['parent']);
			
			$record['sortindex'] = $this->data['sql']['cat']['sortindex'] = $this->_getNextSortindex($this->data['sql']['cat']['parent']);
			$this->db->AutoExecute($cms_db['cat'], $record, 'UPDATE', "idcat = '".$this->data['sql']['cat']['idcat']."'");
			
			unset($record);

			
			// table cat_lang
			$record = $this->data['sql']['cat_lang'];
			
			//TODO title ist kopie von xy
			$arr = $this->_getLangInfoArray();
			foreach ($arr['order'] AS $current_lang) {
				$record['idcatlang'] = false;
				$record['idlang'] = $current_lang;
				
				if ($this->_set_langprefix_on_save_if_new) {
					if ($current_lang != $this->data['sql']['cat_lang']['idlang']) {
						$record['name'] .= ' ('. $arr[$current_lang]['name'] .')';
					}
				}
				
				$this->db->AutoExecute($cms_db['cat_lang'], $record, 'INSERT');
				if ($current_lang == $this->data['sql']['cat_lang']['idlang']) {
					$this->data['sql']['cat_lang']['idcatlang'] = $this->db->Insert_ID();
				}
			}
			unset($record);
			
			//TODO Event
			
		} else {
			//update page
			// table cat
			
			$record = $this->data['sql']['cat'];
			$this->db->AutoExecute($cms_db['cat'], $record, 'UPDATE', "idcat = '".$this->data['sql']['cat']['idcat']."'");
			unset($record);
			
			// table cat_lang
			$record = $this->data['sql']['cat_lang'];
			$record['lastmodified'] = $current_time;
			$this->db->AutoExecute($cms_db['cat_lang'], $record, 'UPDATE', "idcatlang = '".$this->data['sql']['cat_lang']['idcatlang']."'");
			unset($record);
		}
		// Content aus Cache löschen
	    sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
	    
	    $this->dirty = false;
	    return true;
		
	}
	
	function delete() {
		global $cms_db;
		
		//TODO DELETE
	}
	
	/**
	 * @return obj|boolen new cat object or false
	 */
	function &copy($target_idcat, $title = '', $options = array()) {
		global $cfg_cms, $perm;
		
		//option values are: default, yes, no
		//special: 'set_startflag': if_first 
		//'set_online' (default|yes|no) default is copy flag from source
		//'set_copy' (default|yes|no) default is copy flag from source
		//'set_startflag' (default|from_source) default set the startflag if page in category haven't a valid startpage, 
		//                                      from_source copys flag from source 
		//'perms' bool (true|false)
		$options_default = array( 'set_online'=> 'default', 
								  'set_protected'=> 'default',
								  'set_startflag' => 'from_source',
								  'perms' => true);
		$options = array_merge($options_default, $options);
		
		$target_idcat = (int) $target_idcat;
		$return = false;
		if ($target_idcat < 0) {
			return $return;
		}
		
		$catinfos =& sf_factoryGetObjectCache('PAGE', 'Catinfos');
		
		//copy rootcat
		$cat_copy =& sf_factoryGetObject('PAGE', 'Cat');
		$cat_copy->data = $this->data;
		$cat_copy->data['sql']['cat_lang']['idcatlang'] = false;
		$cat_copy->data['sql']['cat_lang']['idcat'] = false;
		$cat_copy->data['sql']['cat']['idcat'] = false;
		$cat_copy->data['sql']['cat']['parent'] = $target_idcat;
		$cat_copy->data['sql']['cat']['sortindex'] = false;
		
		//name
		if ($title != '') {
			$cat_copy->data['sql']['cat_lang']['name'] = $title;
		} else {
			$title = $cat_copy->data['sql']['cat_lang']['name'];
		}
		
		//online
		$visible = $cat_copy->data['sql']['cat_lang']['visible'];
		//print_r($options);
		//echo $visible.'<br>';
		if ($options['set_online'] == 'yes') {
			$visible = ($visible | 0x01);
		} else if ($options['set_online'] == 'no'){
			$visible = ($visible & 0xFE);
		}
		//echo $visible.'<br>';
		//protected
		if ($options['set_protected'] == 'yes') {
			$visible = ($visible | 0x04);
		} else if ($options['set_protected'] == 'no') {
			$visible = ($visible & 0xFB);
		}
		
		$cat_copy->data['sql']['cat_lang']['visible'] = $visible;
		
		//set langprefix on other langs then the default lang
		$cat_copy->_set_langprefix_on_save_if_new = true;
		
		$cat_copy->dirty = true;
		$cat_copy->save();
		
		//copy templates
		$copy_idtplconf = $this->_copyTemplateConfig($this->getIdcat(), $cat_copy->getIdcat());
		//echo "$target_idcat $title";exit;
		$cat_copy->data['sql']['cat_lang']['idtplconf'] = $copy_idtplconf;
		$cat_copy->dirty = true;
		
		//rewrite url
		include_once $cfg_cms['cms_path']."inc/fnc.mod_rewrite.php";
		rewriteGenerateMapping();
		$rewrite_url = rewriteGenerateUrlString($title);
		$rewrite_url = rewriteMakeUniqueStringForLang('idcat', $cat_copy->getIdcat(), $rewrite_url, '', $cat_copy->getParent());
		$cat_copy->data['sql']['cat_lang']['rewrite_use_automatic'] = 1;
		$cat_copy->data['sql']['cat_lang']['rewrite_alias'] = $rewrite_url;
		
		$cat_copy->save();
		
		//copy rootcat perms
		if ($options['perms']) {
			$arr_langs = $this->_getLangInfoArray();
			foreach ($arr_langs['order'] AS $current_lang) {
				$perm->xcopy_perm($this->getIdcat(), 'cat', $cat_copy->getIdcat(), 'cat', 0xFFFFFFFF, 0, $current_lang, false);
				$perm->xcopy_perm($this->getIdcat(), 'frontendcat', $cat_copy->getIdcat(), 'frontendcat', 0xFFFFFFFF, 0, $current_lang, false);
			}
		}
		
		//copy pages of rotcat
		include_once $cfg_cms['cms_path']."inc/fnc.con.php";
		$arr_idcatsides = $this->_getIdcatsides($this->getIdcat());
		foreach ($arr_idcatsides AS $v) {
			//echo '<br>---X'.$cat_copy->getIdcat();
			con_copy_page($this->_getIdclient(), $this->getIdlang(), $v, '', $cat_copy->getIdcat(), $options['perms'], $options);
		}
		
		//copy childcats
		$cattree =& sf_factoryGetObjectCache('PAGE', 'Cattree');
		$cattree->setIdclient($this->_getIdclient());
		$cattree->generate();
		
		$this_idcat = $this->getIdcat();
		$new_parent = $cat_copy->getIdcat();
		
		$arr_childs = $cattree->getChilds($this_idcat);
		//print_r($arr_childs);
		foreach ($arr_childs AS $v) {
			$cat_child =& sf_factoryGetObject('PAGE', 'Cat');
			if ($cat_child->loadByIdcatIdlang($v, $this->getIdlang()) ) {
				$cat_child->copy($new_parent, '', $options);
			}
		}
		
		return $cat_copy;
	}
	
	
	/**
	 * Copies page to another category
	 * 
	 * @param int idcat destination, param < 1 have no effect
	 * @return boolean true on success, otherwise false
	 */
	function setCopyToIdcat($idcat) {
		$idcat = (int) $idcat;
		
		if ($idcat < 1) {
			$idcat = -1;
		}
		
		$this->data['advanced']['copy_to'] = $idcat;
	}
	
	/*
	 * PRIVATE
	 */
	function _set($where, $where2 = '', $key, $value) {
		
		if ($where == '' || $key == '') {
			return false;
		}
		
		if ($where2 != '') {
			if ($this->data[$where][$where2][$key] != $value) {
				$this->data[$where][$where2][$key] = $value;
				$this->dirty = true;
				return true;
			}
		} else {
			if ($this->data[$where][$key] != $value) {
				$this->data[$where][$key] = $value;
				$this->dirty = true;
				return true;
			}
		}
		
		return false;
	}
	
	function _getNextSortindex($parent_idcat) {
		global $cms_db;
		
		$idcat = (int) $idcat;
		$sql = "SELECT 
					MAX(sortindex) AS max 
				FROM 
					".$cms_db['cat']." 
				WHERE 
					parent='$parent_idcat'";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			echo '$rs === false<br>';
			return 1;
		}
		
		$id = (int) $rs->fields['max'];
		if ($id > 0) {
			return (++$id);
		}
		
		return 1;
	}
	
	function _getParent($idcat) {
		global $cms_db;
		
		$idcat = (int) $idcat;
		
		$sql = "SELECT 
					parent 
				FROM 
					".$cms_db['cat']." 
				WHERE 
					idcat='$idcat'";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return 0;
		}
		
		$id = (int) $rs->fields['parent'];
		
		if ($id > 0) {
			return $id;
		}
		
		return 0;
	}
	
	function _getRootparent($idcat) {
		$idcat = (int) $idcat;
		
		$parent = $this->_getParent($idcat);
		
		if ($parent < 1) {
			return $idcat;
		} else {
			return $this->_getRootParent($parent);
		}
	}
	
	/* TODO - Make function multi- clientsave */
	function _getIdclient() {
		global $client;
		
		return (int) $client;
	}
	
	function _getLangInfoArray() {
		global $cfg_cms;
		include_once $cfg_cms['cms_path']."inc/fnc.clients.php";
		return clients_get_langs($this->_getIdclient(), true);	
	}
	
	function _getIdcatsides($idcat) {
		global $cms_db;
		
		$idcat = (int) $idcat;
		$arr =array();
		
		$sql = "SELECT 
					idcatside 
				FROM 
					".$cms_db['cat_side']." 
				WHERE 
					idcat='$idcat'
				ORDER BY
					sortindex";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return 0;
		}
		
		while (! $rs->EOF) {
			array_push($arr, $rs->fields['idcatside']);
			$rs->MoveNext();
		}
		
		return $arr;
	}
	/**
	 * @return idtplconf
	 */
	function _copyTemplateConfig($idcat_from, $idcat_to) {
		global $cms_db, $db;
		$db2 = new DB_cms;
		$ret_tpl_conf = 0;
		
		$arr_langs = $this->_getLangInfoArray();
		foreach ($arr_langs['order'] AS $current_lang) {
			//get tpl
			$sql = "SELECT
						CL.idcat, CL.idtplconf,
						TC.idtpl,
						CC.idcontainer, CC.config, CC.view, CC.edit
					FROM 
						".$cms_db['cat_lang']." CL
						LEFT JOIN ".$cms_db['tpl_conf']." TC USING(idtplconf)
						LEFT JOIN ".$cms_db['container_conf']." CC USING(idtplconf)
					WHERE
						CL.idlang='$current_lang'
						AND CL.idcat = '$idcat_from'
						AND CL.idtplconf != 0";
			$current_idcat = 0;
			$current_idtplconf = 0;
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
								idlang = '$current_lang'
							 	AND idcat = '$idcat_to'";
					//echo $sql2 .'<br />';
					$db2->query($sql2);
					if ($current_lang == $this->data['sql']['cat_lang']['idlang']) {
						$ret_tpl_conf =  $current_idtplconf;
					}
					
					
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
		
		return $ret_tpl_conf;
	}
	
}