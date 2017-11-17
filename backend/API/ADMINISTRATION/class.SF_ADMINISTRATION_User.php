<?php
class SF_ADMINISTRATION_User extends SF_API_Object {
	
	var $data = array('sql' => 
						array('users' => 
								array ('user_id' => false,
										'username' => false,
										'password' => false,
										'title' => '',
										'name' => '',
										'surname' => '',
										'email' => '',
										'is_active' => 0,
										'is_deletable' => 1,
										'position' => '',
										'salutation' => '',
										'street' => '',
										'zip' => '',
										'location' => '',
										'phone' => '',
										'fax' => '',
										'comment' => '',
										'street_alt' => '',
										'state' => '',
										'country' => '',
										'mobile' => '',
										'pager' => '',
										'homepage' => '',
										'birthday' => '',
										'firm' => '',
										'firm_street' => '',
										'firm_street_alt' => '',
										'firm_zip' => '',
										'firm_location' => '',
										'firm_state' => '',
										'firm_country' => '',
										'firm_email' => '',
										'firm_phone' => '',
										'firm_fax' => '',
										'firm_mobile' => '',
										'firm_pager' => '',
										'firm_homepage' => '',
										'author' => 0,
										'created' => 0,
										'lastmodified' => 0,
										'lastmodified_author' => 0,
										'currentlogin' => 0,
										'lastlogin' => 0,
										'lastlogin_failed' => 0,
										'failed_count' => 0,
										'password_recover_hash' => '',
										'registration_hash' => '',
										'accept_agreement' => 0,
										'accept_agreement_timestamp' => 0,
										'registers_timestamp' => 0,
										'registration_valid' => 0
										)
						),
						'advanced' => array('idgroups' => array()
						),
						'special' => array()
				);
	
	var $dirty = false;
	var $dirty_groups = false;
	var $update_lastmodified_meta = true;
	
	var $db;
	
	function __construct() {
		$this->db =& sf_factoryGetObjectCache('DATABASE', 'Ado');
	}
	
	function loadByIduser($iduser) {
		$iduser = (int) $iduser;

		if ($iduser < 1) {
			return false;
		}
		
		return $this->_load('iduser', array($iduser));
	}
	
	function loadByPasswordRecoverHash($hash) {
		if (trim($hash) == '' || strlen($hash)<10) {
			return false;
		}
		$hash = addslashes($hash);
		return $this->_load('hash', array($hash));
	}
	
	function loadByRegistrationHash($hash) {
		if (trim($hash) == '' || strlen($hash)<10) {
			return false;
		}
		$hash = addslashes($hash);
		return $this->_load('registration_hash', array($hash));
	}
	
	function loadByUsername($username)
	{
		if (trim($username) == '') {
			return false;
		}
		
		$username = addslashes($username);

		return $this->_load('username', array($username));
	}
	
	function loadByUsernamePassword($username, $password, $log = false) {
		
		if (trim($username) == '' || trim($password) == '') {
			return false;
		}
		
		
		//cast - no need to cast password, cause it will later converted to md5
		$username = addslashes($username);

		$is_loaded = $this->_load('username_password', array($username, $password));
		
		if ($log) {
			if ($iduser = $this->usernameExists($username)) {
				
				$current_time = time();
				if ($is_loaded) {
					$this->_set('sql', 'users', 'lastlogin', $this->getCurrentLoginTimestamp());
					$this->_set('sql', 'users', 'currentlogin', $current_time);
					$this->_set('sql', 'users', 'failed_count', 0);
					//print_r($this);exit;
					$this->save();
					$this->_handleEvent('login_success');
				} else {
					$shadow =& sf_factoryGetObject('ADMINISTRATION', 'User');
					if ($shadow->loadByIduser($iduser)) {
						$shadow->_set('sql', 'users', 'lastlogin_failed', $current_time);
						$shadow->_set('sql', 'users', 'failed_count', ($shadow->getFailedCount()+1) );
						$shadow->save();
						fire_event('login_fail', $shadow->data);
					}
				}
			}
		}
		
		return $is_loaded;
	}
	
	function getIduser() {return $this->data['sql']['users']['user_id'];}
	function getTitle() {return $this->data['sql']['users']['title'];}
	function getUsername() {return $this->data['sql']['users']['username'];}
	function getPassword() {return $this->data['sql']['users']['password'];}
	function getName() {return $this->data['sql']['users']['name'];}
	function getSurname() {return $this->data['sql']['users']['surname'];}
	function getEmail() {return $this->data['sql']['users']['email'];}
	function getIsOnline() {return $this->data['sql']['users']['is_active'];}
	function getIsDeletable() {return $this->data['sql']['users']['is_deletable'];}
	function getPosition() {return $this->data['sql']['users']['position'];}
	function getSalutation() {return $this->data['sql']['users']['salutation'];}
	function getStreet() {return $this->data['sql']['users']['street'];}
	function getZip() {return $this->data['sql']['users']['zip'];}
	function getLocation() {return $this->data['sql']['users']['location'];}
	function getPhone() {return $this->data['sql']['users']['phone'];}
	function getFax() {return $this->data['sql']['users']['fax'];}
	function getComment() {return $this->data['sql']['users']['comment'];}
	function getStreetAlt() {return $this->data['sql']['users']['street_alt'];}
	function getState() {return $this->data['sql']['users']['state'];}
	function getCountry() {return $this->data['sql']['users']['country'];}
	function getMobile() {return $this->data['sql']['users']['mobile'];}
	function getPager() {return $this->data['sql']['users']['pager'];}
	function getHomepage() {return $this->data['sql']['users']['homepage'];}
	function getBirthday() {return $this->data['sql']['users']['birthday'];}
	function getFirm() {return $this->data['sql']['users']['firm'];}
	function getFirmStreet() {return $this->data['sql']['users']['firm_street'];}
	function getFirmStreetAlt() {return $this->data['sql']['users']['firm_street_alt'];}
	function getFirmZip() {return $this->data['sql']['users']['firm_zip'];}
	function getFirmLocation() {return $this->data['sql']['users']['firm_location'];}
	function getFirmState() {return $this->data['sql']['users']['firm_state'];}
	function getFirmCountry() {return $this->data['sql']['users']['firm_country'];}
	function getFirmEmail() {return $this->data['sql']['users']['firm_email'];}
	function getFirmPhone() {return $this->data['sql']['users']['firm_phone'];}
	function getFirmFax() {return $this->data['sql']['users']['firm_fax'];}
	function getFirmMobile() {return $this->data['sql']['users']['firm_mobile'];}
	function getFirmPager() {return $this->data['sql']['users']['firm_pager'];}
	function getFirmHomepage() {return $this->data['sql']['users']['firm_homepage'];}
	function getAuthor() {return $this->data['sql']['users']['author'];}
	function getCreatedTimestamp() {return $this->data['sql']['users']['created'];}
	function getLastmodifiedTimestamp() {return $this->data['sql']['users']['lastmodified'];}
	function getLastmodifiedAuthor() {return $this->data['sql']['users']['lastmodified_author'];}
	function getCurrentLoginTimestamp() {return $this->data['sql']['users']['currentlogin'];}
	function getLastLoginTimestamp() {return $this->data['sql']['users']['lastlogin'];}
	function getLastLoginFailedTimestamp() {return $this->data['sql']['users']['lastlogin_failed'];}
	function getFailedCount() {return $this->data['sql']['users']['failed_count'];}
	function getPasswordRecoverHash() {return $this->data['sql']['users']['password_recover_hash'];}
	function getRegistrationHash() {return $this->data['sql']['users']['registration_hash'];}
	function getAcceptAgreement() {return $this->data['sql']['users']['accept_agreement'];}
	function getAcceptAgreementTimestamp() {return $this->data['sql']['users']['accept_agreement_timestamp'];}
	function getRegistersTimestamp() {return $this->data['sql']['users']['registers_timestamp'];}
	function getRegistrationValid() {return $this->data['sql']['users']['registration_valid'];}

	function getIdgroups() {return $this->data['advanced']['idgroups'];}
	
	function getSpecialByKey($key) { 
		return $this->data['special'][$key]; 
	}
	
	function getIsAdmin() {
		return in_array(2, $this->data['advanced']['idgroups']);
	}
	
	function getHaveBackendAccess() {
		global $cms_db;
		
		if ($this->getIsAdmin()) {
			return true;
		}
		
		
		$groups = implode(',', $this->data['advanced']['idgroups']);
		
		if ($groups == '') {
			return false;
		}
		
		$sql = "SELECT 
					count(idgroup) AS countme
				FROM 
					".$cms_db['perms']."
				WHERE
					type='cms_access'
					AND id = 'area_backend'
					AND perm = '1'
					AND idgroup IN ($groups)";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
		
		return ($rs->fields['countme'] > 0);
		
	}
		
	function setUsername($value) {return $this->_set('sql', 'users', 'username', $value);}
	function setPassword($value) {return $this->_set('sql', 'users', 'password', md5($value));}
	function setTitle($value) {return $this->_set('sql', 'users', 'title', $value);}
	function setName($value) {return $this->_set('sql', 'users', 'name', $value);}
	function setSurname($value) {return $this->_set('sql', 'users', 'surname', $value);}
	function setEmail($value) {return $this->_set('sql', 'users', 'email', $value);}
	function setIsOnline($value) {return $this->_set('sql', 'users', 'is_active', $value);}
	function setIsDeletable($value) {return $this->_set('sql', 'users', 'is_deletable', $value);}
	function setPosition($value) {return $this->_set('sql', 'users', 'position', $value);}
	function setSalutation($value) {return $this->_set('sql', 'users', 'salutation', $value);}
	function setStreet($value) {return $this->_set('sql', 'users', 'street', $value);}
	function setZip($value) {return $this->_set('sql', 'users', 'zip', $value);}
	function setLocation($value) {return $this->_set('sql', 'users', 'location', $value);}
	function setPhone($value) {return $this->_set('sql', 'users', 'phone', $value);}
	function setFax($value) {return $this->_set('sql', 'users', 'fax', $value);}
	function setComment($value) {return $this->_set('sql', 'users', 'comment', $value);}
	function setStreetAlt($value) {return $this->_set('sql', 'users', 'street_alt', $value);}
	function setState($value) {return $this->_set('sql', 'users', 'state', $value);}
	function setCountry($value) {return $this->_set('sql', 'users', 'country', $value);}
	function setMobile($value) {return $this->_set('sql', 'users', 'mobile', $value);}
	function setPager($value) {return $this->_set('sql', 'users', 'pager', $value);}
	function setHomepage($value) {return $this->_set('sql', 'users', 'homepage', $value);}
	function setBirthday($value) {return $this->_set('sql', 'users', 'birthday', $value);}
	function setFirm($value) {return $this->_set('sql', 'users', 'firm', $value);}
	function setFirmStreet($value) {return $this->_set('sql', 'users', 'firm_street', $value);}
	function setFirmStreetAlt($value) {return $this->_set('sql', 'users', 'firm_street_alt', $value);}
	function setFirmZip($value) {return $this->_set('sql', 'users', 'firm_zip', $value);}
	function setFirmLocation($value) {return $this->_set('sql', 'users', 'firm_location', $value);}
	function setFirmState($value) {return $this->_set('sql', 'users', 'firm_state', $value);}
	function setFirmCountry($value) {return $this->_set('sql', 'users', 'firm_country', $value);}
	function setFirmEmail($value) {return $this->_set('sql', 'users', 'firm_email', $value);}
	function setFirmPhone($value) {return $this->_set('sql', 'users', 'firm_phone', $value);}
	function setFirmFax($value) {return $this->_set('sql', 'users', 'firm_fax', $value);}
	function setFirmMobile($value) {return $this->_set('sql', 'users', 'firm_mobile', $value);}
	function setFirmPager($value) {return $this->_set('sql', 'users', 'firm_pager', $value);}
	function setFirmHomepage($value) {return $this->_set('sql', 'users', 'firm_homepage', $value);}
	function setPasswordRecoverHash($hash) { return $this->_set('sql', 'users', 'password_recover_hash', $hash); }
	function setUpdateLastmodifiedMeta($b) { $this->update_lastmodified_meta = (boolean) $b; return true; }
	function setRegistrationHash($hash) { return $this->_set('sql', 'users', 'registration_hash', $hash); }
	function setAcceptAgreement($accept) { return $this->_set('sql', 'users', 'accept_agreement', $accept);}
	function setAcceptAgreementTimestamp($timestamp) { return $this->_set('sql', 'users', 'accept_agreement_timestamp', $timestamp);}
	function setRegistersTimestamp($timestamp) { return $this->_set('sql', 'users', 'registers_timestamp', $timestamp);}
	function setRegistrationValid($valid) { return $this->_set('sql', 'users', 'registration_valid', $valid);}
	
	function setIdgroups($mixed) {

			if (! is_array($mixed)) {
				$mixed = array($mixed);
			}
			
			foreach ($mixed AS $k=>$v) {
				$mixed[$k] = (int) $v;
			}
			$mixed = array_unique($mixed);
			$this->data['advanced']['idgroups'] = $mixed;
			$this->dirty_groups = true;
			return true;
	}
	
	function setSpecialByKey($key, $value) {
		return $this->_set('special', false, $key, $value);
	}
	
	
	function save() {
		global $cms_db, $auth;
		
		if (!$this->dirty && !$this->dirty_groups) {
			return false;
		}
		
		$current_time = time();
		$current_author = (int) $auth->auth['uid']; 
		
		if ($this->dirty) {
			if ($this->data['sql']['users']['user_id'] > 0) {
				$this->data['sql']['users']['lastmodified'] = $current_time;
				$this->data['sql']['users']['lastmodified_author'] = $current_author;
				$record = $this->data['sql']['users'];
				
				if (! $this->update_lastmodified_meta) {
					unset($record['lastmodified'], $record['lastmodified_author']);
				}
				
				$this->db->AutoExecute($cms_db['users'], $record, 'UPDATE', "user_id = '".$this->data['sql']['users']['user_id']."'");
		
				
				$this->_handleEvent('user_update');
			} else {
				$this->data['sql']['users']['created'] = $current_time;
				$this->data['sql']['users']['lastmodified'] = $current_time;
				$this->data['sql']['users']['author'] = $current_author;
				$this->data['sql']['users']['lastmodified_author'] = $current_author;
				
				$record = $this->data['sql']['users'];
				
				//iduser not needed
				unset($record['user_id']);
				
				$this->db->AutoExecute($cms_db['users'], $record, 'INSERT');
				$this->data['sql']['users']['user_id'] = $this->db->Insert_ID();
				
				//selfregistered user
				if ($record['author'] < 1) {
					$record['author'] = $this->data['sql']['users']['user_id'];
					$record['lastmodified_author'] = $this->data['sql']['users']['user_id'];
					$this->db->AutoExecute($cms_db['users'], $record, 'UPDATE', "user_id = '".$this->data['sql']['users']['user_id']."'");
				}
				
				$this->_handleEvent('user_create');
			}
			unset($record);
		}
		
		if ($this->dirty_groups && $this->data['sql']['users']['user_id'] > 1) {
			$sql = "DELETE FROM
						".$cms_db['users_groups']."
					WHERE
						user_id = '".$this->data['sql']['users']['user_id']."'";
			$this->db->Execute($sql);
			
			foreach($this->data['advanced']['idgroups'] AS $v) {
				if ($v > 0) {
					$record = array('user_id' => $this->data['sql']['users']['user_id'], 'idgroup' => $v);
					$this->db->AutoExecute($cms_db['users_groups'], $record, 'INSERT');
				}
			}
			$record = null;
		}
				
		$this->dirty = false;
		$this->dirty_groups = false;
		
		return true;		
	}
	
	function delete() {
		global $cms_db;
		
		if ($this->data['sql']['users']['user_id'] < 2) {
			return false;
		}
		$sql = "DELETE FROM
						".$cms_db['users_groups']."
					WHERE
						user_id = '".$this->data['sql']['users']['user_id']."'";
		$this->db->Execute($sql);
		
		$sql = "DELETE FROM
						".$cms_db['users']."
					WHERE
						user_id = '".$this->data['sql']['users']['user_id']."'";
		$this->db->Execute($sql);
		$this->_handleEvent('user_delete');
		
		if ($this->db->Affected_Rows() > 0) {
			$this->data['sql']['users']['user_id'] = false;
			return true;
		}
		
		return false;
	}
	
	/**
	 * Shows if an username already exists.
	 * 
	 * @param string username 
	 * @return int|boolean if user was found userid, otherwise false
	 */
	function usernameExists($username) {
		global $cms_db;
		
		if (trim($username) == '') {
			return false;
		}
		
		$username = addslashes($username);
		
		$sql = "SELECT user_id
				FROM
					".$cms_db['users']."
				WHERE 
					username='$username'";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
		
		return $rs->fields['user_id'];
		
	}
	
	function &copy($username, $password) {
		if ($username == '' || $password == '') {
			return false;
		}
		
		$copy =& sf_factoryGetObject('ADMINISTRATION', 'User');
		$copy->data = $this->data;
		$copy->data['sql']['users']['user_id'] = false;
		$copy->setUsername($username);
		$copy->setPassword($password);
		$copy->dirty = true;
		$copy->dirty_groups = true;
		$copy->save();
		return $copy;
	}
	
	/*
	 * PRIVATE
	 */
	 
	function _load($what, $args) {
		global $cms_db;
		
		$sql_where = '';
		switch ($what) {
			case 'iduser':
				$sql_where = "U.user_id = '".$args['0']."'";
				break;
			case 'username_password':
				$sql_where = "U.username = '".$args['0']."' AND U.password = '".md5($args['1'])."'";
				break;
			case 'username':
				$sql_where = "U.username = '".$args['0']."'";
				break;
			case 'hash':
				$sql_where = "U.password_recover_hash = '".$args['0']."'";
				break;
			case 'registration_hash':
				$sql_where = "U.registration_hash = '".$args['0']."'";
				break;
			default:
				return false;
		}
		
		$sql = "SELECT U.*, UG.idgroup 
				FROM
					".$cms_db['users']." U LEFT JOIN
					".$cms_db['users_groups']." UG USING(user_id)
				WHERE 
					$sql_where";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
		
	
				
		$this->data['sql']['users'] = array ('user_id' => $rs->fields['user_id'],
										'username' => $rs->fields['username'],
										'password' => $rs->fields['password'],
										'title' => $rs->fields['title'],
										'name' => $rs->fields['name'],
										'surname' => $rs->fields['surname'],
										'email' => $rs->fields['email'],
										'is_active' => $rs->fields['is_active'],
										'is_deletable' => $rs->fields['is_deletable'],
										'position' => $rs->fields['position'],
										'salutation' => $rs->fields['salutation'],
										'street' => $rs->fields['street'],
										'zip' => $rs->fields['zip'],
										'location' => $rs->fields['location'],
										'phone' => $rs->fields['phone'],
										'fax' => $rs->fields['fax'],
										'comment' => $rs->fields['comment'],
										'street_alt' => $rs->fields['street_alt'],
										'state' => $rs->fields['state'],
										'country' => $rs->fields['country'],
										'mobile' => $rs->fields['mobile'],
										'pager' => $rs->fields['pager'],
										'homepage' => $rs->fields['homepage'],
										'birthday' => $rs->fields['birthday'],
										'firm' => $rs->fields['firm'],
										'firm_street' => $rs->fields['firm_street'],
										'firm_street_alt' => $rs->fields['firm_street_alt'],
										'firm_zip' => $rs->fields['firm_zip'],
										'firm_location' => $rs->fields['firm_location'],
										'firm_state' => $rs->fields['firm_state'],
										'firm_country' => $rs->fields['firm_country'],
										'firm_email' => $rs->fields['firm_email'],
										'firm_phone' => $rs->fields['firm_phone'],
										'firm_fax' => $rs->fields['firm_fax'],
										'firm_mobile' => $rs->fields['firm_mobile'],
										'firm_pager' => $rs->fields['firm_pager'],
										'firm_homepage' => $rs->fields['firm_homepage'],
										'author' => $rs->fields['author'],
										'created' => $rs->fields['created'],
										'lastmodified' => $rs->fields['lastmodified'],
										'lastmodified_author' => $rs->fields['lastmodified_author'],
										'currentlogin' => $rs->fields['currentlogin'],
										'lastlogin' => $rs->fields['lastlogin'],
										'lastlogin_failed' => $rs->fields['lastlogin_failed'],
										'failed_count' => $rs->fields['failed_count'],
										'password_recover_hash' => $rs->fields['password_recover_hash'],
										'registration_hash' => $rs->fields['registration_hash'],
										'accept_agreement' => $rs->fields['accept_agreement'],
										'accept_agreement_timestamp' => $rs->fields['accept_agreement_timestamp'],
										'registers_timestamp' => $rs->fields['registers_timestamp'],
										'registration_valid' => $rs->fields['registration_valid']
										);
		
		do {	
			array_push($this->data['advanced']['idgroups'], $rs->fields['idgroup']);
			$rs->MoveNext();
		} while (! $rs->EOF );
		
		if ($this->test) {
		  //print_r($this->data['sql']['users']);
		}
		
		$this->_handleEvent('user_load');
		if ($this->test) {
		  //print_r($this->data['sql']['users']); 
		}
		$this->dirty = false;
		$this->dirty_groups = false;
		return true;
	}
	
	function _set($where, $where2 = '', $key, $value, $cast = '') {
		
		
		if ($where == '' || $key == '') {
			return false;
		}
		
		switch ($cast) {
			case 'int':
				$value = (int) $value;
				break;
			case 'float':
				$value = (float) $value;
				break;
			case 'boolean':
				$value = (boolean) $value;
				break;
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
	
	function _handleEvent($event) {
		
		$args = fire_event($event, $this->data);
		if (is_array($args)) {
			foreach ($args AS $v) {
				if (is_array($v)) {
					foreach ($v AS $key=>$val) {
						$this->data['special'][$key] = $val;
					}
				}
			}
		}
	}
}

?>
