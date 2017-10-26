<?php
class SF_ADMINISTRATION_UserCollection extends SF_API_Object {
	
	var $items = array();
	var $count_all = 0;
	var $count = 0;
	var $db;
	var $conf = array('searchterm' => false,
						'limit_start' => false,
						'limit_max' => false,
						'order_by' => false,
						'order_direction' => false,
						'idgroup' => 0,
						'hide_admins' => false,
						'userfilter' => false,
						
						
							);
	
	
	function SF_ADMINISTRATION_UserCollection() {
		$this->db =& sf_factoryGetObjectCache('DATABASE', 'Ado');
	}
	
	function setSearchterm($term) {
		$this->conf['searchterm'] = $term;
	}
	function setLimitMax($max) {
		$this->conf['limit_max'] = (int) $max;
	}
	function setLimitStart($start) {
		$this->conf['limit_start'] = (int) $start;
	}
	function setOrder($order, $direction = 'ASC') {
		$this->conf['order_by'] = $order;
		$this->conf['order_direction'] = ($direction == 'DESC') ? 'DESC':'ASC';
	}
	function setIdgroup($idgroup) {
		$this->conf['idgroup'] = (int) $idgroup;
	}
	function setHideAdmins($boolean) {
		$this->conf['hide_admins'] = (boolean) $boolean;
	}
	function setUserfilterByIduser($mixed) {
		if (! is_array($mixed)) {
			$mixed = explode(',', $mixed);
		}
		
		if (is_array($mixed)) {
			foreach ($mixed AS $k=>$v) {
				$mixed[$k] = (int) $v;
			} 
			$mixed = trim(implode(',', $mixed));
		}
		
		if (trim($mixed) == '') {
			return false;
		}
		
		$this->conf['userfilter'] = $mixed;
	}
	

	function generate() {
		global $cms_db;
		
		//load user object to access meta data
		$usermeta =& sf_factoryGetObject('ADMINISTRATION', 'User');
		$usermeta_fields = $usermeta->data['sql']['users'];
		
		//generate searchterm
		$this->conf['searchterm'] = trim($this->conf['searchterm']);
		$sql_search = '';
		if ($this->conf['searchterm'] != '') {
			$term = mysqli_real_escape_string($this->db->_connectionID, $this->conf['searchterm']);
			$pieces = explode(' ', $term);			
			$sql_search_array = array();
			foreach ($pieces AS $word) {
				if (trim($word) == '') {
					continue;
				}
				$sql_search_array_single = array();
				foreach ($usermeta_fields AS $field => $default) {
					array_push($sql_search_array_single, 'U.'.$field." LIKE '%".$word."%'");
				}
				array_push($sql_search_array, ' ( ' .implode(' OR ', $sql_search_array_single) .' ) ');
			}
			$sql_search = ' AND '. implode(' AND ' ,$sql_search_array);
		}
		
		//generate idgroup
		$sql_group = '';
		if ($this->conf['idgroup'] > 0) {
			$sql_group = " AND UG.idgroup = '".$this->conf['idgroup']."' ";
		} else if ($this->conf['idgroup'] == -1) {
			$sql_group = " AND UG.idgroup IS NULL ";
		}
		
		//hide admins
		$sql_hide_admins = '';
		if ($this->conf['hide_admins']) {
			$adminids = implode(',', $this->_getAdminIds());
			if ($adminids != '') {
				$sql_hide_admins = " AND U.user_id NOT IN (".$adminids.") ";
			}
		}
		
		//userfilter
		$sql_userfilter = '';
		if ($this->conf['userfilter']) {
			$sql_userfilter = " AND U.user_id IN (".$this->conf['userfilter'].") ";
		}
		
		
		//generate order
		$sql_order = '';
		if ($this->conf['order_by'] != '') {
			if (array_key_exists($this->conf['order_by'], $usermeta_fields)) {
				$sql_order = ' ORDER BY U.'.$this->conf['order_by'] . ' '. $this->conf['order_direction'];
			}
		}

		// generate limit
		$sql_limit = '';
		if ($this->conf['limit_start'] || $this->conf['limit_max']) {
			if ($this->conf['limit_max']) {
				$sql_limit = ' LIMIT '. (int) $this->conf['limit_start'].', '. (int) $this->conf['limit_max'];
			} else if ($this->conf['limit_start']) {
				$sql_limit = ' LIMIT '. (int) $this->conf['limit_start'];
			}
		}
		
		//set sql
		$sql = "SELECT DISTINCT U.user_id 
				FROM
					".$cms_db['users']." U
					LEFT JOIN ".$cms_db['users_groups']." UG USING(user_id)
				WHERE
					U.user_id != '2'
					$sql_userfilter
					$sql_search
					$sql_group
					$sql_hide_admins
					$sql_order
					$sql_limit
				";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
		
		while (! $rs->EOF) {
			$item =& sf_factoryGetObject('ADMINISTRATION', 'User');
			if ($item->loadByIduser($rs->fields['user_id'])) {
				array_push($this->items, $item);
			}
			$rs->MoveNext();
		}
		
		$this->count = count($this->items);
		
		return true;
	
	
	}
	
	function &get() {
		$iter =& sf_factoryGetObject('UTILS', 'ArrayIterator');
		$iter->loadByRef($this->items);
		return $iter;
	}
	
	function getCountAll() { 
		if ($this->count_all < 1) {
			$this->_countAll();
		}
		
		return $this->count_all;
	}
	
	function getCount() { return $this->count;}
	function reset() { return false; /*TODO*/}
	
	function _countAll() {
		global $cms_db;
		
		//load user object to access meta data
		$usermeta =& sf_factoryGetObject('ADMINISTRATION', 'User');
		$usermeta_fields = $usermeta->data['sql']['users'];
		
		//generate searchterm
		$this->conf['searchterm'] = trim($this->conf['searchterm']);
		$sql_search = '';
		if ($this->conf['searchterm'] != '') {
			$term = mysqli_real_escape_string($this->db->_connectionID, $this->conf['searchterm']);
			$pieces = explode(' ', $term);			
			$sql_search_array = array();
			foreach ($pieces AS $word) {
				if (trim($word) == '') {
					continue;
				}
				$sql_search_array_single = array();
				foreach ($usermeta_fields AS $field => $default) {
					array_push($sql_search_array_single, 'U.'.$field." LIKE '%".$word."%'");
				}
				array_push($sql_search_array, ' ( ' .implode(' OR ', $sql_search_array_single) .' ) ');
			}
			$sql_search = ' AND '. implode(' AND ' ,$sql_search_array);
		}
		
		//userfilter
		$sql_userfilter = '';
		if ($this->conf['userfilter']) {
			$sql_userfilter = " AND U.user_id IN (".$this->conf['userfilter'].") ";
		}
		
		//hide admins
		$sql_hide_admins = '';
		if ($this->conf['hide_admins']) {
			$adminids = implode(',', $this->_getAdminIds());
			if ($adminids != '') {
				$sql_hide_admins = " AND U.user_id NOT IN (".$adminids.") ";
			}
		}
		
		
		//generate idgroup
		$sql_group = '';
		$sgl_group_left = '';
		if ($this->conf['idgroup'] > 0) {
			$sgl_group_left = "LEFT JOIN ".$cms_db['users_groups']." UG USING(user_id)";
			$sql_group = " AND UG.idgroup = '".$this->conf['idgroup']."' ";
		} else if ($this->conf['idgroup'] == -1) {
			$sgl_group_left = "LEFT JOIN ".$cms_db['users_groups']." UG USING(user_id)";
			$sql_group = " AND UG.idgroup IS NULL ";
		}
		
		$sql = "SELECT DISTINCT COUNT(U.user_id) AS countme
				FROM
					".$cms_db['users']." U
					$sgl_group_left
				WHERE
					U.user_id != '2'
					$sql_userfilter
					$sql_hide_admins
					$sql_group
					$sql_search";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
		
		$this->count_all = $rs->fields['countme'];
		return true;
	}
	
	/*
	 * PRIVATE
	 */
	
	function _getAdminIds() {
		global $cms_db;
		
		$ids = array();
		
		$sql = "SELECT DISTINCT user_id
				FROM
					".$cms_db['users_groups']." UG
				WHERE
					idgroup = 2";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return $ids;
		}
		
		if ($rs->EOF ) {
			return $ids;
		}
		while(! $rs->EOF) {
			array_push($ids, $rs->fields['user_id']);
			$rs->MoveNext();
		}
		return $ids;
	}	
		
}

?>