<?php
class SF_UTILS_DbCache extends SF_API_Object {
	var $db;
	var $is_active = true;
	var $now = 0;
	var $cache_name = 'SF_UTILS_DbCache';
	var $cache_gc_probability = 1;
   
    function SF_UTILS_DbCache() {
    	global $cfg_cms, $cfg_client;

		$this->_API_objectIsSingleton(true);
		$this->db =& sf_factoryGetObjectCache('DATABASE', 'Ado');
		
        $this->is_active = ( $cfg_client['db_cache_enabled'] != '0' && $cfg_cms['db_cache_enabled'] == '1' ) ? true : false;
        $this->now = time();
        $this->_garbage();

        //$this->cache_groups = ( is_array( $cfg_client['db_cache_groups'] ) ) ? array_merge( $cfg_cms['db_cache_groups'], $cfg_client['db_cache_groups'] ) : $cfg_cms['db_cache_groups'];
        //$this->cache_items = ( is_array( $cfg_client['db_cache_items'] ) ) ? array_merge( $cfg_cms['db_cache_items'], $cfg_client['db_cache_items'] ) : $cfg_cms['db_cache_items'];
        
    }
    
    function getCacheIsActive() {
    	return $this->is_active;
    }
    
    
    function setCacheIsActive($boolean) {
    	$this->is_active = (boolean) $boolean;
    }
    
    
    function keyExists($key) {
    	global $cms_db;
    	
    	// cache is disabled
		if (! $this->is_active) {
			return false;
		}
    	
    	$key = $this->_generateMd5CacheKey($key);
		$sql = "SELECT 
					val 
				FROM
					" . $cms_db['db_cache'] . " 
				WHERE
					sid = '".addslashes($key) ."'
					AND name = '".addslashes($this->cache_name)."'";
		
		$this->db->Execute($sql);
		
		return ($this->db->Affected_Rows() > 0);					
    }
    
    /**
     * Fetch cache item
     * 
     * @return mixed array on success or (boolean) false
     */
    function getCacheEntry($key) {
		global $cms_db;
		
		// cache is disabled
		if (! $this->is_active) {
			return false;
		}
		
		$key = $this->_generateMd5CacheKey($key);
		$sql = "SELECT
					val 
				FROM
					" . $cms_db['db_cache'] . " 
				WHERE
					sid = '".addslashes($key)."'
					AND name = '".addslashes($this->cache_name)."'
					AND (changed + releasetime) > '".$this->now ."'";
		$rs = $this->db->Execute($sql);
		
		if ($rs->EOF || $rs === false) {
			return false;
		}
		//echo "get cache";
		$val = $rs->fields['val'];
		
		$val = unserialize(stripslashes($val));
		
		return $val;
    }
    
    /**
     *  @return boolen 
     */
    function insertCacheEntry($key, $value, $group, $subgroup = '', $lifetime = 1440) {
    	global $cms_db;
    	
    	// cache is disabled
		if (! $this->is_active) {
			return false;
		}
    	
    	if ($group == 'frontend') {
    		$this->_figureOutCachetimeForFrontend($lifetime);
    	}
    	//echo "insert cache";
		$sql = "REPLACE INTO
					 " . $cms_db['db_cache'] . " 
					(sid, name, val, changed, releasetime, groups, item)
				VALUES (
					'" . $this->_generateMd5CacheKey($key) . "',
					'" . addslashes( $this->cache_name ) . "',
					'" . addslashes( serialize( $value ) ) . "',
					'" . $this->now . "',
					'" . ($lifetime * 60) . "',
					'" . addslashes( $group ) . "',
					'" . addslashes( $subgroup ) . "')";
		
		return ($this->db->Execute($sql) !== false);
    }
    
    /**
     * @return int affected rows
     */
    function flushByGroup($group, $subgroup = '') {
    	global $cms_db;
    	
    	$group = addslashes($group);
    	$subgroup = addslashes($subgroup);
    	
    	$sql_groups = "groups = '$group' "; 
    	if ($subgroup != '') {
    		$sql_groups .= "AND item = '$subgroup' "; 
    	}
    	
    	$sql = "DELETE FROM
					 " . $cms_db['db_cache'] . " 
				WHERE 
					$sql_groups
					AND name = '".$this->cache_name."'";
		$this->db->Execute($sql);
		
		//delete phplib cache DEPRECATED
		global $db;
		if (is_object($db)) {
			$str = $group;
			if ($subgroup != '') {
				$str .= '_'.$subgroup;
			}
			
			$db->delete_cache($str);
		} 
		
		return $this->db->Affected_Rows();
    }
    
    /**
     * @return int affected rows
     */
    function flushByLifetime() {
    	global $cms_db;
    	
    	$sql = "DELETE FROM
					 " . $cms_db['db_cache'] . " 
				WHERE 
					(changed + releasetime) < '".$this->now ."'";
		$this->db->Execute($sql);
		
		return $this->db->Affected_Rows();
    }
    
    /**
     * @return int affected rows
     */
    function flushAll() {
    	global $cms_db;
    	
    	$sql = "DELETE FROM
					 " . $cms_db['db_cache'];
		$this->db->Execute($sql);
		
		//delete phplib cache DEPRECATED
		global $db;
		if (is_object($db)) {
			$db->delete_cache(true);
		} 
		
		return $this->db->Affected_Rows();
    }
    
    /*
     * PRIVATE METHODS START HERE
     */
     
    function _generateMd5CacheKey($key) {
    	return md5(trim($key));
    } 
    
    function _garbage($force = false) {
    	srand( ( double ) microtime() * 1000000 ); 
        if ( ( $force ) || ( rand( 1, 100 ) <= $this->cache_gc_probability ) ) {
            return $this->flushByLifetime();
        }
        
        return false;
    }
    
	function _figureOutCachetimeForFrontend($default = 1440) {
        global $cms_db, $cfg_cms, $lang;
        
        $default *= 60;

        $sql = "SELECT SL.start, SL.end
    			FROM
    				" . $cms_db['cat_side'] . " CS,
    				" . $cms_db['side_lang'] . " SL
    			WHERE
    				CS.idside = SL.idside
    				AND SL.idlang = " . $lang . "
    				AND (SL.online & 0x02) = 0x02
    				AND ( SL.start >= UNIX_TIMESTAMP(NOW())
						OR SL.end >= UNIX_TIMESTAMP(NOW()) )";
        
        $rs = $this->db->Execute($sql);
        
        if ($this->db->Affected_Rows() < 1 ) {
        	return false;
        }
        
        
        $now = $this->now;
        $smallest_t = 0;
        while(! $rs->EOF ) {
            $s = $rs->fields['start'];
            $e = $rs->fields['end'];
            $t = ( $s < $e && $s > $now ) ? $s:$e;
            if( empty( $smallest_t ) ) {
                $smallest_t = $t;
            } else {
                $smallest_t = ( $t < $smallest_t ) ? $t:$smallest_t;
            }
            $rs->MoveNext();
        } 
        
        $minutes_to_next_timemanagmentrun = ceil( ( $smallest_t - $now ) / 60 ); 

        $t = ( $minutes_to_next_timemanagmentrun < $default && $minutes_to_next_timemanagmentrun > 0 ) 
        		? $minutes_to_next_timemanagmentrun: $default;
		return $t;        		
    } 

   
} 

?>