<?php
//idtracker
//idclient
//idlang
//iduser 
//created 
//ip
//group
//action
//value1
//value2
//value3
//value4
//value5
class SF_UTILS_Tracker extends SF_API_Object{
	var $db;
	var $dirty = false;
	var $data = array(
					'sql' => array(		
						'tracker' => array(
							'idtracker' => false,
							'idclient' => 0,
							'idlang' => 0,
							'iduser' => 2,
							'created' => 0,
							'ip' => '',
							'groupname' => 'default',
							'action' => '',
							'value1' => '',
							'value2' => '',
							'value3' => '',
							'value4' => '',
							'value5' => ''
						),
					),
					'advanced' => array()
				);
	
	
 	function SF_UTILS_Tracker() {
 		$this->db =& sf_factoryGetObjectCache('DATABASE', 'Ado');
 	}
 	
 	function getIdtracker() {return $this->data['sql']['tracker']['idtracker'];}
 	function getIdclient() {return $this->data['sql']['tracker']['idclient'];}
 	function getIdlang() {return $this->data['sql']['tracker']['idlang'];}
 	function getIduser() {return $this->data['sql']['tracker']['iduser'];}
 	function getCreatedTimestamp() {return $this->data['sql']['tracker']['created'];}
 	function getIp() {return $this->data['sql']['tracker']['ip'];}
 	function getGroupname() {return $this->data['sql']['tracker']['groupname'];}
 	function getAction() {return $this->data['sql']['tracker']['action'];}
 	function getValue1() {return $this->data['sql']['tracker']['value1'];}
 	function getValue2() {return $this->data['sql']['tracker']['value2'];}
 	function getValue3() {return $this->data['sql']['tracker']['value3'];}
 	function getValue4() {return $this->data['sql']['tracker']['value4'];}
 	function getValue5() {return $this->data['sql']['tracker']['value5'];}
 	
 	function setIdclient($value) {return $this->_set('sql', 'tracker', 'idclient', $value, 'int');}
 	function setIdlang($value) {return $this->_set('sql', 'tracker', 'idlang', $value, 'int');}
 	function setIduser($value) {return $this->_set('sql', 'tracker', 'iduser', $value, 'int');}
 	function setGroupname($value)  {return $this->_set('sql', 'tracker', 'groupname', $value);}
 	function setAction($value) {return $this->_set('sql', 'tracker', 'action', $value);}
 	function setValue1($value) {return $this->_set('sql', 'tracker', 'value1', $value);}
 	function setValue2($value) {return $this->_set('sql', 'tracker', 'value2', $value);}
 	function setValue3($value) {return $this->_set('sql', 'tracker', 'value3', $value);}
 	function setValue4($value) {return $this->_set('sql', 'tracker', 'value4', $value);}
 	function setValue5($value) {return $this->_set('sql', 'tracker', 'value5', $value);}
 	
 	function loadByIdtracker($id) {
 		
 		$id = (int) $id;
 		if ($id < 1) {
 			return false;
 		}
 		
 		$sql = "SELECT *
				FROM
					cms_tracker
				WHERE 
					idtracker = '$id'";
		
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
				
		$this->data['sql']['tracker'] = array(
											'idtracker' => $rs->fields['idtracker'],
											'idclient' => $rs->fields['idclient'],
											'idlang' => $rs->fields['idlang'],
											'iduser' => $rs->fields['iduser'],
											'created' => $rs->fields['created'],
											'ip' => $rs->fields['ip'],
											'groupname' => $rs->fields['groupname'],
											'action' => $rs->fields['action'],
											'value1' => $rs->fields['value1'],
											'value2' => $rs->fields['value2'],
											'value3' => $rs->fields['value3'],
											'value4' => $rs->fields['value4'],
											'value5' => $rs->fields['value5']
										);
		$this->dirty = false;
		return true;
 	}
 	
 	function loadByRawdata($data) {
 		$this->data = $data;
 	}
 	
 	function save() {
 		
 		if (! $this->dirty) {
 			return false;
 		}
 		
 		if ($this->getIdtracker() > 0) {
 			$record = $this->data['sql']['tracker'];
			$this->db->AutoExecute('cms_tracker', $record, 'UPDATE', "idtracker = '".$this->data['sql']['tracker']['idtracker']."'");
			$record = null;
 		} else {
 			$record = $this->data['sql']['tracker'];
 			$record['idclient'] = ($record['idclient'] != '') ? $record['idclient'] : (int) $GLOBALS['client'];
 			$record['idlang'] = ($record['idlang'] != '') ? $record['idlang'] : (int) $GLOBALS['lang'];
 			$record['created'] = time();
 			$record['ip'] = getenv('REMOTE_ADDR');
 			unset($record['idtracker']);
			$this->db->AutoExecute('cms_tracker', $record, 'INSERT');
			echo $this->data['sql']['tracker']['idtracker'] = $this->db->Insert_ID();
			$record = null;
 		}
 		
 		return true;
 	}
 	
 	function delete() {
 		if ($this->data['sql']['tracker']['idtracker'] < 1) {
 			return false;
 		}
 		
 		$sql = "DELETE FROM
						cms_tracker
					WHERE
						idtracker = '".$this->data['sql']['tracker']['idtracker']."'";
		$this->db->Execute($sql);
		
		return true;
 	}
 	
 	function copy() {
 		return false;
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
}
?>