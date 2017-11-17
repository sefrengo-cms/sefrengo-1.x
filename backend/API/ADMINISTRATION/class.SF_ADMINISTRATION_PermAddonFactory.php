<?php

class SF_ADMINISTRATION_PermAddonFactory extends SF_API_Object {
	
	function &getAddonObject($type) {
		switch($type) {
			case 'frontendcat':
			case 'cat': 
				$o =& sf_factoryGetObjectCache('ADMINISTRATION', 'PermAddonFactory', 'PermAddonCat');
				break;
			case 'folder':
				$o =& sf_factoryGetObjectCache('ADMINISTRATION', 'PermAddonFactory', 'PermAddonDirectory');
				break;
			default:
				$o =& sf_factoryGetObjectCache('ADMINISTRATION', 'PermAddonFactory', 'PermAddon');
		}
		
		return $o; 
	}
	
}

class SF_ADMINISTRATION_PermAddon extends SF_API_Object {
	
	function getParent($current) {
		return false;
	}
	
	function deleteChilds($type, $idlang, $idgroup, $id) {
		return false;
	}	
	
	function showDeleteChildsCheckbox($type) {
		return false;
	}
}

class SF_ADMINISTRATION_PermAddonCat extends SF_ADMINISTRATION_PermAddon {
	var $ctree;
	
	function __construct() {
		global $client;
		
		$this->ctree =& sf_factoryGetObjectCache('Page', 'Cattree');
		$this->ctree->setIdclient($client);
		$this->ctree->generate();
		$this->_is_special_object = true;
	}
	
	function getParent($current) {
		$parent = (int) $this->ctree->getParent($current);
		
		if ($parent > 0 ) {
			return $parent;
		}
		
		return false;
	}
	
	function deleteChilds($type, $idlang, $idgroup, $idroot) {
		global $cms_db;
		
		$type = addslashes($type);
		$idlang = (int) $idlang;
		$idgroup = (int) $idgroup;
		$idroot = (int) $idroot;

		if (! in_array($type, array('cat', 'frontendcat')) || $idgroup < 1 || $idlang < 1 || $idroot < 1) {
			return false;
		}
		
		$arr_childcats = $this->ctree->getChildsRecursive($idroot);
		if (count($arr_childcats) < 1) {
			return false;
		}

		$childcats = implode(',', $arr_childcats);
		
		//delete cat perms
		$db =& sf_factoryGetObject('DATABASE', 'Ado');
		$sql = "DELETE FROM 
					".$cms_db['perms']."
				WHERE 
					idgroup = '$idgroup'
					AND idlang = '$idlang'
					AND type = '$type'
					AND id != '0'
					AND id IN ($childcats)";
		$db->Execute($sql);
		
		//find pages
		
		//fetch slavetype
		$slavetype = ($type == 'cat') ? 'side' : 'frontendpage';
		
		//search in rootcat too
		$childcats .= ','. $idroot; 
		$sql = "SELECT 
					p.id
				FROM
					".$cms_db['perms']." p
					LEFT JOIN ".$cms_db['cat_side']." cs ON p.id = cs.idcatside
				WHERE
					cs.idcat IN($childcats)
					AND p.idgroup = '$idgroup'
					AND p.idlang = '$idlang'
					AND p.type = '$slavetype'
					AND p.id != '0'";
		$rs = $db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}			
		$arr_pages = array();
		while (! $rs->EOF) {
			array_push($arr_pages, $rs->fields['id']);
			$rs->MoveNext();
		}
		
		if (count($arr_pages) < 1) {
			return true;
		}
		
		$sql = "DELETE FROM 
					".$cms_db['perms']."
				WHERE 
					idgroup = '$idgroup'
					AND idlang = '$idlang'
					AND type = '$slavetype'
					AND id != '0'
					AND id IN (".implode(',',$arr_pages).")";
		$db->Execute($sql);
		
		return true;	
	}
	
	function showDeleteChildsCheckbox($type) {
		return (in_array($type, array('cat', 'frontendcat')));
	}	
	
}

class SF_ADMINISTRATION_PermAddonDirectory extends SF_ADMINISTRATION_PermAddon {
	var $dtree;
	
	function __construct() {
		global $client;
		
		$this->dtree =& sf_factoryGetObjectCache('ASSETS', 'DbDirectorytree');
		$this->dtree->setIdclient($client);
		$this->dtree->generate();
	}
	
	function getParent($current) {
		$parent = (int) $this->dtree->getParent($current);
		
		if ($parent > 0 ) {
			return $parent;
		}
		
		return false;
	}
	
	function deleteChilds($type, $idlang, $idgroup, $idroot) {
		global $cms_db;
		
		$type = addslashes($type);
		$idlang = (int) $idlang;
		$idgroup = (int) $idgroup;
		$idroot = (int) $idroot;

		if (! in_array($type, array('folder')) || $idgroup < 1 || $idlang < 1 || $idroot < 1) {
			return false;
		}
		
		$arr_childdirs = $this->dtree->getChildsRecursive($idroot);
		if (count($arr_childdirs) < 1) {
			return false;
		}

		$childdirs = implode(',', $arr_childdirs);
		
		//delete directory perms
		$db =& sf_factoryGetObject('DATABASE', 'Ado');
		$sql = "DELETE FROM 
					".$cms_db['perms']."
				WHERE 
					idgroup = '$idgroup'
					AND idlang = '$idlang'
					AND type = '$type'
					AND id != '0'
					AND id IN ($childdirs)";
		$db->Execute($sql);
		
		//find files
		
		//fetch slavetype
		$slavetype = 'file';
		
		//search in rootcat too
		$childdirs .= ','. $idroot; 
		$sql = "SELECT 
					p.id
				FROM
					".$cms_db['perms']." p
					LEFT JOIN ".$cms_db['upl']." u ON p.id = u.idupl
				WHERE
					u.iddirectory IN($childdirs)
					AND p.idgroup = '$idgroup'
					AND p.idlang = '$idlang'
					AND p.type = '$slavetype'
					AND p.id != '0'";
		$rs = $db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}			
		$arr_files = array();
		while (! $rs->EOF) {
			array_push($arr_files, $rs->fields['id']);
			$rs->MoveNext();
		}
		
		if (count($arr_files) < 1) {
			return true;
		}
		
		$sql = "DELETE FROM 
					".$cms_db['perms']."
				WHERE 
					idgroup = '$idgroup'
					AND idlang = '$idlang'
					AND type = '$slavetype'
					AND id != '0'
					AND id IN (".implode(',',$arr_files).")";
		$db->Execute($sql);
		
		return true;	
	}
	
	function showDeleteChildsCheckbox($type) {
		return (in_array($type, array('folder')));
	}	
	
}


?>