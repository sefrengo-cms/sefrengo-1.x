<?php
class SF_PAGE_Catinfos extends SF_API_Object {

    var $data = array( 'data' => array(),
					   'parent_dependace' => array()
    				 );
    				    					
    var $config = array( 'idlang' => 0, 
						 'check_frontend_prems' => false, 
						 'check_backend_prems' => false,    						
    					 'link_sessionstring' => '', 	
    					 'link_use_idlang' => true,
    					 'link_extra_urlstring' => '',
    					 'is_generated' => false
    					);
    var $cache;
    var $db;
    
    function SF_PAGE_Catinfos() {
    	// constructor
    	$this->cache =& sf_factoryGetObjectCache('UTILS', 'DbCache');
    	$this->db =& sf_factoryGetObjectCache('DATABASE', 'Ado');
    }
    
    function setIdlang($idlang) {
    	$this->config['idlang'] = (int) $idlang;
    }
    
    function setCheckFrontendperms($boolean) {
    	$this->config['check_frontend_prems'] = (boolean) $boolean;
    }
    
    function setCheckBackendperms($boolean) {
    	$this->config['check_backend_prems'] = (boolean) $boolean;
    }
    
    function setLinkSessionstring($sessionstring) {
    	$this->config['link_sessionstring'] = $sessionstring;
    }
    
    function setLinkUseIdlang($boolean) {
    	$this->config['link_use_idlang'] = (boolean) $boolean;
    }
    
    function setLinkExtraUrlstring($urlstring) {
    	$this->config['link_extra_urlstring'] = $urlstring;
    }

    function generate() {
    	global $cfg_client, $perm, $db, $cms_db, $client, $auth, $sess;
    	
    	//check dependencies 
    	if ( $this->config['idlang'] < 1 || $this->config['is_generated']) {
    		return false;
    	}
    	
    	
    	//url parameter with lang
		$querylang = ($this->config['link_use_idlang']) ? 'lang='.$this->config['idlang'].'&amp;' : '';
		
		
		//check perm: user have perm to see pages with the protected flag
		$sql_hide_protected_cats = '';
        if (( $auth->auth['uid'] == 'nobody')) {
		  $sql_hide_protected_cats = 'AND (C.visible & 0x04) = 0x00';
		}
		
		//check perms for user with advanced frontend perms
		$check_frontendperms_in_cat = ($auth->auth['uid'] != 'nobody' && $this->config['check_frontend_prems']);
		
		$check_backendperms_in_cat = $this->config['check_backend_prems'];
		
		$sess_string = ($this->config['link_sessionstring'] != '') ? '_SF_SESSION_STRING' : '';
		
		$sql= "SELECT 
					B.idcat, B.parent, B.sortindex,
					C.rewrite_use_automatic, C.rewrite_alias, C.idcatlang, C.author, C.created, C.lastmodified, C.description,
					IF ( ((C.visible & 0x03) = 0x01) ,'1' ,'0') AS visible, 
					IF ( ((C.visible & 0x04) = 0x04) ,'1' ,'0') AS protected, 
					C.idtplconf, C.name
				FROM
					".$cms_db['cat']." B LEFT JOIN
					".$cms_db['cat_lang']." C USING(idcat) LEFT JOIN
					".$cms_db['tpl_conf']." D USING(idtplconf) LEFT JOIN
					".$cms_db['tpl']." E USING(idtpl)
				WHERE 
					C.idlang = '".$this->config['idlang']."'  
					$sql_hide_protected_cats
					ORDER BY parent, sortindex";
					
		//try cache - on success jump out with return true
		$cache_key = $sql 
						. '|'.$this->config['link_extra_urlstring'] 
						. '|'.$sess_string
						. '|'.implode(',', $perm->get_group())
                        . '|'.$this->config['check_frontend_prems']
                        . '|'.$this->config['check_backend_prems'];
						
		if ($data = $this->cache->getCacheEntry($cache_key)) {
			$this->data =& $data;
			
			//insert session if get mode
			if ($this->config['link_sessionstring'] != '') {
				foreach($this->data['data'] AS $k=>$v) {
					$this->data['data'][$k]['link'] = str_replace('_SF_SESSION_STRING', $this->config['link_sessionstring'], $this->data['data'][$k]['link']);
				}
			}
			
			return true;
		}					
		
		$rs = $this->db->Execute($sql);
		
        if ($rs === false) {
	       return true;
	    }
		 while(! $rs->EOF ) {
			$idcat_loop = $rs->fields['idcat'];
		
			if ($check_frontendperms_in_cat) {
				if ($rs->fields['protected'] == 1 && ! $perm->have_perm(2, 'frontendcat', $idcat_loop) ) {
				    $rs->MoveNext();
					continue;
				}
			} 
			
			if ($check_backendperms_in_cat) {
				if (! $perm->have_perm(1, 'cat', $idcat_loop) ) {
				    $rs->MoveNext();
					continue;
				}
			}
			
			$link_loop = $cfg_client['contentfile'] . '?'. $querylang 
							.'idcat=' . $idcat_loop . $sess_string . $this->config['link_extra_urlstring'];
			
			$this->data['data'][$idcat_loop] = array(	'idcat' =>$idcat_loop,
														'link' =>$link_loop,
														'idcatlang' =>$rs->fields['idcatlang'],
														'rewrite_use_automatic' =>$rs->fields['rewrite_use_automatic'],
														'rewrite_alias' =>$rs->fields['rewrite_alias'],
														'author' =>$rs->fields['author'],
														'created' =>$rs->fields['created'],
														'lastmodified' =>$rs->fields['lastmodified'],
														'parent' =>$rs->fields['parent'],
														'visible' =>$rs->fields['visible'],
														'idtplconf' =>$rs->fields['idtplconf'],
														'name' =>$rs->fields['name'],
														'description' =>$rs->fields['description']);
			
			$this->data['parent_dependace'][$rs->fields['parent']][$rs->fields['sortindex']] = $idcat_loop;
			
			$rs->MoveNext();
		}
		
		//insert cache
		$this->cache->insertCacheEntry($cache_key, $this->data, 'frontend', 'tree');
		
		//replace placeholder '_SF_SESSION_STRING' with real session
		if ($this->config['link_sessionstring'] != '') {
			foreach ($this->data['data'] AS $k=>$v) {
				$this->data['data'][$k]['link'] = str_replace('_SF_SESSION_STRING', $this->config['link_sessionstring'], $this->data['data'][$k]['link']); 
			}
		}
		
		$this->config['is_generated'] = true;
		
		return true;
    }
    
    function &getCatinfoDataArrayByRef() {
    	return $this->data['data'];
    }
    
    function &getParentDependanceDataArrayByRef() {
    	return $this->data['parent_dependace'];
    } 
    
    function getLink($idcat) { return $this->data['data'][$idcat]['link']; }
    function getIdcatlang($idcat) { return $this->data['data'][$idcat]['idcatlang']; }
    function getRewriteUseAutomaticRaw($idcat) { return $this->data['data'][$idcat]['rewrite_use_automatic']; }
    function getRewriteAliasRaw($idcat) { return $this->data['data'][$idcat]['rewrite_alias']; }
    function getIduser($idcat) { return $this->data['data'][$idcat]['author']; }
    function getCreatedTimestamp($idcat) { return $this->data['data'][$idcat]['created']; }
    function getLastmodifiedTimestamp($idcat) { return $this->data['data'][$idcat]['lastmodified']; }
    function getParent($idcat) { return $this->data['data'][$idcat]['parent']; }
    function getIsOnline($idcat) { return $this->data['data'][$idcat]['visible']; }
    function getIsProtected($idcat) { return $this->data['data'][$idcat]['protected']; }
    function getIdtplconf($idcat) { return $this->data['data'][$idcat]['idtplconf']; }
    function getTitle($idcat) { return $this->data['data'][$idcat]['name']; }
    function getDescription($idcat) { return $this->data['data'][$idcat]['description']; }
    
    function getRootParent($idcat) {
    	$idcat = (int) $idcat;
    	$parent = $this->data['data'][$idcat]['parent'];
    	if ($parent < 1 ) {
    		return $idcat;
    	} else {
    		return $this->getRootParent($parent);
    	}
    	
    }
    
    function getIdcatsideStartpage($idcat) {
    	global $cfg_client, $perm, $db, $cms_db, $client, $auth, $sess;
    	
    	$sql = "SELECT CS.idcatside
				FROM
					".$cms_db['cat_side']." CS 
				WHERE 
					CS.idcat = '".$idcat."'
					AND CS.is_start = 1";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}

		return $rs->fields['idcatside'];
    }
    
    /**
     * Returns all child idcats of one cat as an array in a given order. If no idcat was found
     * returns empty array
     * 
     * Possible Options:
     * $options['order'] - Set the order. Possible values are sortindex, 
     * name, created, lastmodified. Default is sortindex.
     * $options['order'] - Orderdir is ASC or DESC. Default is ASC.
     * $options['hide_online'] - (bool) true or false. Default is false
     * $options['hide_offline'] - (bool) true or false. Default is true
     * 
     * @param int $idcat
     * @param arr $options 
     * @return arr 
     */
	function getChilds($idcat, $options = array())
    {
    	global $cms_db;
    	
    	//cast
    	$idcat = (int) $idcat;
    	$ret = array();
    	
    	//handle options
    	$options['order'] = (isset($options['order'])) ? $options['order'] : 'sortindex';
    	$options['order'] = (in_array($options['order'], array('sortindex', 'name', 'created', 'lastmodified'))) ? $options['order'] : 'sortindex';
    	$options['order_dir'] = (isset($options['order_dir'])) ?  strtoupper($options['order_dir']) : 'ASC';
    	$options['order_dir'] = ($options['order_dir'] == 'DESC') ?  'DESC' : 'ASC';
    	$options['hide_online'] = (isset($options['hide_online'])) ?  (bool) $options['hide_online'] : false;
    	$options['hide_offline'] = (isset($options['hide_offline'])) ?  (bool) $options['hide_offline'] : true;
    	
    	
    	$sql_order = 'C.sortindex';
    	switch($options['order'])
    	{
    		case 'sortindex':
    			$sql_order = 'C.'.$options['order'];
    			break;
    		case 'name':
    		case 'created':
    		case 'lastmodified':
    			$sql_order = 'CL.'.$options['order'];
    			break;
    	}
    	
    	$sql_order_dir = ($options['order_dir'] == 'ASC') ? 'ASC' : 'DESC';
    	
    	//get sql
    	$sql = "SELECT
					CL.idcat
				FROM
					".$cms_db['cat']." C LEFT JOIN
					".$cms_db['cat_lang']." CL USING(idcat)
				WHERE 
					C.parent = $idcat
					AND  CL.idlang   = '".$this->config['idlang']."'
				ORDER BY 
					$sql_order $sql_order_dir";				

	    $rs = $this->db->Execute($sql);
	    
	    if ($rs === false) 
	    {
	       return $ret;
	    }
		
		 while(! $rs->EOF ) 
		 {
			
			if ($options['hide_online'] && $this->getIsOnline($rs->fields['idcat']) == 1)
			{
				$rs->MoveNext();
				continue;
			}
			
			if ($options['hide_offline'] && $this->getIsOnline($rs->fields['idcat']) == 0)
			{
				$rs->MoveNext();
				continue;
			}
			
			if ( isset($this->data['data'][$rs->fields['idcat']]))
			{
				array_push($ret, $rs->fields['idcat']);
			}
							
			$rs->MoveNext();
		}
    	
    	
    	return $ret;
    }
    
    /**
	 * Checks recursive if a idcat is a child of another idcat.
	 *
	 * @param Int $idcat_child Child folder-Id
	 * @param Int $idcat_parent Needed parent folder-Id
	 *
	 * @return bool
	 */
	function isChildOf($idcat_child, $idcat_parent){
		
		if( $idcat_child == $idcat_parent ||
			$this->getParent($idcat_child) == $idcat_parent ||
			$this->getRootparent($idcat_child) == $idcat_parent )
		{
			return TRUE;
		}
		else if( $idcat_child == $this->getRootparent($idcat_child) || $this->getRootparent($idcat_child) != 0 )
		{
			return FALSE;
		}

		return $this->isChildOf($idcat_child, $idcat_parent);
	}
    
    function getIsGenerated() {
    	return $this->config['is_generated'];
    }
    
    function flushAll() {
    	$this->data = array( 'data' => array(),
					   'parent_dependace' => array()
    				 );
    				    					
    	$this->config = array( 'idlang' => 0, 
						 'check_frontend_prems' => false,    						
    					 'link_sessionstring' => '', 	
    					 'link_use_idlang' => true,
    					 'link_extra_urlstring' => '',
    					 'is_generated' => false
    					);
    }
} 

?>
