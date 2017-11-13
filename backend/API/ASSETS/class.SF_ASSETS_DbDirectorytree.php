<?php
class SF_ASSETS_DbDirectorytree extends SF_API_Object {

    var $data = array(  'items_levelorder' => array(), // [index] => iddirectory
    					'items_level' => array(),// iddirectory => level
    					'count_items_in_level' => array(), // [level] = count
    					'level_max' => 0,
    					'rawdata' => array(), // [parent] = iddirectory
    					'parents' => array(), // [iddirectory] = parent
    					);
    				    					
    var $config = array( 'idclient' => 0, 
    					 'is_generated' => false
    					);
 
    
    function __construct() {
    	// constructor
    }
    
    function setIdclient($idclient) {
    	$this->config['idclient'] = (int) $idclient;
    }

    function generate() {
    	global $cms_db;
    	
    	//check dependencies 
    	if ( $this->config['idclient'] < 1 || $this->config['is_generated']) {
    		return false;
    	}
    	
    	$this->_buildTree();	
		$this->config['is_generated'] = true;
		
		return true;
    }
    
    
    function getLevel($iddirectory) {
    	return $this->data['items_level'][$iddirectory];
    }
    
    function getParent($iddirectory) {
    	return $this->data['parents'][$iddirectory];
    }
    
    function getChilds($parent) {
    	$out = array();

    	if (is_array($this->data['rawdata'][$parent])) {
    		foreach ($this->data['rawdata'][$parent] AS $iddirectory) {
    			array_push($out, $iddirectory);
    		}
    	}
    	
    	return $out;
    }
    
    function getChildsRecursive($parent, $is_first = true) {
    	if ($is_first) {
    		$this->tempchilds = array();
    	}
    	
    	$a = $this->getChilds($parent);
    	if (count($a) > 0) {
    		array_push($this->tempchilds, $a);
    		foreach ($a AS $iddirectory) {
    			$this->getChildsRecursive($iddirectory, false);
    		}
    	}
    	
    	if ($is_first) {  		
    		foreach ($this->tempchilds AS $arr) {
    			foreach($arr AS $iddirectory) {
    				$out[] = $iddirectory;
    			}
    		}
    		return $out;
    	} 
    }
       
    function getMaxLevel() {
    	return $this->data['level_max'];
    }
    
    function countEntriesInLevel($level) {
    	return count($this->data['count_items_in_level'][$level]);
    }
    
    function countAll() {
    	return count($this->data['parents']);
    }
    
    function &getLevelorderIter() {
    	$i =& $GLOBALS['sb_factory']->getObjectForced('UTILS', 'ArrayIterator');
		$i->loadByRef($this->data['items_levelorder']); 
		return $i;
    }
    
    
    
    
    
    function getIsGenerated() {
    	return $this->config['is_generated'];
    }
    
    function flushAll() {
    	$this->data = array(  'items_levelorder' => array(),
    					'items_level' => array(),
    					'count_items_in_level' => array(),
    					'level_max' => 0,
    					'rawdata' => array(),
    					'parents' => array()
    					);
    	$this->config = array( 'idclient' => 0, 
    					 	'is_generated' => false
    					);
    }
    
    /*
     * PRIVATE METHODS
     */
     
    function _buildTree() {
    	global $cms_db;
    	
		$sql= "SELECT 
			iddirectory, parentid, dirname
		FROM
			".$cms_db['directory']." 
		WHERE 
			idclient = '".$this->config['idclient']."'  
		ORDER BY 
			dirname";
					
	
		$db =& sf_factoryGetObjectCache('DATABASE', 'Ado');	
		$rs = $db->Execute($sql);
		while(! $rs->EOF ) {	
			array_push($this->data['items_levelorder'], $rs->fields['iddirectory']);
			$this->data['rawdata'][ $rs->fields['parentid'] ][] = $rs->fields['iddirectory'];
			$this->data['parents'][ $rs->fields['iddirectory'] ] = $rs->fields['parentid'];
			$level = (substr_count( $rs->fields['dirname'], '/') -1);
			$this->data['items_level'][ $rs->fields['iddirectory'] ] = $level;
			$this->_incrementLevelCount($level);
			if ($level > $this->data['level_max']) {
				$this->data['level_max'] = $level;
			}
			
			$rs->MoveNext();
		}
		$rs->Close();

		
		return true;
    }
	
	function _incrementLevelCount($level) {
		if(isset($this->data['count_items_in_level'][$level])){
			++$this->data['count_items_in_level'][$level];
		} else {
			$this->data['count_items_in_level'][$level] = 1;
		}		
	} 
	
} 

?>
