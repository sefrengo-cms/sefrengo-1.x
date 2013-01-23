<?php
class SF_PAGE_Cattree extends SF_API_Object {

    var $data = array(  'items_levelorder' => array(), // [index] => idcat
    					'items_level' => array(),// idcat => level
    					'items_sortindex' => array(),// idcat => sortindex
    					'count_items_in_level' => array(), // [level] = count
    					'last_items' => array(),
    					'level_max' => 0,
    					'rawdata' => array(), // [parent][sortindex] = idcat
    					'parents' => array(), // [idcat] = parent
    					);
    				    					
    var $config = array( 'idclient' => 0, 
    					 'is_generated' => false
    					);
    
    function SF_PAGE_Cattree() {
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
    
    function getSortindex($idcat) {
    	return $this->data['items_sortindex'][$idcat];
    }
    
    function getLevel($idcat) {
    	return $this->data['items_level'][$idcat];
    }
    
    function getParent($idcat) {
    	//echo "IN $idcat";
    	return $this->data['parents'][$idcat];
    }
    
    function getChilds($parent) {
    	$out = array();
    	
    	if (is_array($this->data['rawdata'][$parent])) {
    		foreach ($this->data['rawdata'][$parent] AS $idcat) {
    			array_push($out, $idcat);
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
    		foreach ($a AS $idcat) {
    			$this->getChildsRecursive($idcat, false);
    		}
    	}
    	
    	if ($is_first) {  		
    		foreach ($this->tempchilds AS $arr) {
    			foreach($arr AS $idcat) {
    				$out[] = $idcat;
    			}
    		}
    		return $out;
    	} 
    }
    
    function getIsFirstItemInLevel($idcat) {
    	return (1 == $this->data['items_sortindex'][$idcat]);
    }
    
    function getIsLastItemInLevel($idcat) {
    	return in_array($idcat, $this->data['last_items']);
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
    	$i =& sf_factoryGetObject('UTILS', 'ArrayIterator');
		$i->loadByRef($this->data['items_levelorder']); 
		return $i;
    }
    
    
    
    
    
    function getIsGenerated() {
    	return $this->config['is_generated'];
    }
    
    function flushAll() {
    	$this->data = array();
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
			idcat, parent, sortindex
		FROM
			".$cms_db['cat']." 
		WHERE 
			idclient = '".$this->config['idclient']."'  
		ORDER BY 
			parent, sortindex";
					
		//try cache or generate data
		$cache =& sf_factoryGetObjectCache('UTILS', 'DbCache');		
		if ($data = $cache->getCacheEntry($sql)) {
			$this->data =& $data;	
			return true;
		} else {
			$db =& sf_factoryGetObjectCache('DATABASE', 'Ado');	
			$rs = $db->Execute($sql);
			while(! $rs->EOF ) {	
				$this->data['rawdata'][ $rs->fields['parent'] ][ $rs->fields['sortindex'] ] = $rs->fields['idcat'];
				$this->data['parents'][ $rs->fields['idcat'] ] = $rs->fields['parent'];
				$rs->MoveNext();
			}
			$rs->Close();
			$this->_treeOrder(0);
			$cache->insertCacheEntry($sql, $this->data, 'frontend', 'tree');
		}
		
		return true;
    }
    
    function _treeOrder($parent, $level=0) {
		if ($level > $this->level_max) {
			$this->data['level_max'] = $level;
		}
				
		if (is_array($this->data['rawdata'][$parent])) {
			foreach ($this->data['rawdata'][$parent] AS $sortindex => $idcat) {
				//items_levelorder
				$this->data['items_levelorder'][] = $idcat;
				$this->_incrementLevelCount($level);
				$this->data['items_level'][$idcat] = $level;
				$this->data['items_sortindex'][$idcat] = $sortindex;
				if ( is_array($this->data['rawdata'][$idcat])) {
					$this->_treeOrder($idcat, $level+1);
				}
			}
			array_push($this->data['last_items'], $idcat);
		}
		
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
