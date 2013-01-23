<?PHP
// File: $Id: class.querybuilder_factory.php 28 2008-05-11 19:18:49Z mistral $
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
// + Autor: $Author: mistral $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 28 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

class querybuilder_factory
{
	var $_db = array();

	// constructor
	function querybuilder_factory() {
	}

	//
	// get_db($db_interface, $db_tables, $inc_path)
	//
	// returns the querybuilder object for the desired db-type
	// the function creates a new querybuilder object if there is no existing one
	// and stores it in the array $_db
	//
	function get_db($db_interface, $db_tables, $inc_path) {
		$dbtype = $db_interface->type;
		// check if a querybuilder for the desired db exits
		if (!isset($this->_db["'$dbtype'"])) {
			// no ... create new query builder for the db-type
			if (!file_exists($inc_path.'class.querybuilder_'.$dbtype.'.php')) {
				die('Querybuilder für Datenbank <b>'.$db_interface->Database.'</b> vom Typ <b>'. $dbtype .'</b> nicht gefunden.');
			} else {
				include_once($inc_path.'class.querybuilder_'.$dbtype.'.php');
				eval('$this->_db["'.$dbtype.'"] = &new querybuilder_'.$dbtype.'();');
				$this->_db[$dbtype]->db = &$db_interface;
				$this->_db[$dbtype]->cms_db = &$GLOBALS[$db_tables];
			}
		}
		// return querybuilder
		return $this->_db[$dbtype];
	}

	//
	// get_adodb($db_interface, $db_tables, $inc_path)
	//
	// returns a php-adodb-object for the desired db-type
	// the function creates a new adodb-object if there is no existing one
	// and stores it in the array $_db
	//
/*
	function get_adodb($db_type, $inc_path) {
    global $cfg_cms;
		// check if a querybuilder for the desired db exits
		if (!isset($this->_db["'ado_$db_type'"])) {
			// no ... create new adodb-object
			if (!file_exists($inc_path.'adodb/adodb.inc.php')) {
				die("phpADODB für Datenbank vom Typ <b>$db_type</b> nicht gefunden.");
			} else {
        include_once($inc_path."adodb/adodb.inc.php");
        $db = NewADOConnection('mysql');
				$this->_db["'ado_$db_type'"] = NewADOConnection($db_type);
        $db->Connect($cfg_cms['db_host'], $cfg_cms['db_user'], $cfg_cms['db_password'], $cfg_cms['db_database']);
			}
		}
		// return querybuilder
		return $this->_db["'ado_$db_type'"];
	}
*/
}


/*
** Querybuilder
**
** Description : abstract sql-query-builder
**
** Copyright   : Jürgen Brändle, 2002-2003
** Author      : Jürgen Brändle, braendle@web.de
** Urls        : www.Sefrengo.de
** Create date : 2003-02-09
** Last update : 2003-02-26
*/
class querybuilder
{

	// variables for public use
	var $errno   = array();
	var $sql     = '';
	var $db		 = '';
	var $cms_db = '';
	var $lastInsert  = 0;
	var $minWhereLen = 5;
 	var $needReturn  = false;

 	// variables for diagnostic and querybuilder
	var $debugmode       = false;
	var $sql_debuglist   = array();
	var $buildermode     = false;
	var $sql_builderlist = array();
	var $forceexecute    = true;

	// predefined sql-queries - for all queries that could not be build with the methods select,insert,update,delete
	var $views = array();

	// constructor
	function querybuilder() {
	}

	//
	// empty methods to be implemented by the inherited class
	//
	// insert( $table, $check, $parameter )
	// update( $table, $parameter )
	// delete_row( $table, $parameter )
	// select( $table, $parameter, $limit, $needReturn )
	// get_sqldata( $parameter )

	//
	// insert( $table, $check, $parameter )
	//
	// insert the given values into the database
	// creates the sql-query and checks if there are at least a name of the table and an array of parameters
	// if $check is given, then the insert will be check with a MAX($check)-SELECT with the given where-clauses
	// or returns the value of mysql_insert_id() if where-clause is missing
	//
	// implementation details see class.querybuilder_mysql.php
	//
	function insert( $table, $check, $parameter ) {
	}

	//
	// update( $table, $parameter )
	//
	// updates the given values in the database
	// creates the sql-query and checks if there are at least a name of the table and an array of parameters
	// if there is no valid where-clause the update will not be executed
	//
	// return
	//   true  update has been executed
	//  false  missing parameter or no valid where-clause created
	//
	// implementation details see class.querybuilder_mysql.php
	//
	function update( $table, $parameter ) {
	}

	//
	// delete_row( $table, $parameter )
	//
	// deletes the given values in the database
	// creates the sql-query and checks if there are at least a name of the table and an array of parameters
	// if there is no valid where-clause the delete will not be executed
	//
	// return
	//   true  delete has been executed
	//  false  missing parameter or no valid where-clause created
	//
	// implementation details see class.querybuilder_mysql.php
	//
	function delete_row( $table, $parameter ) {
	}

	//
	// select( $table, $parameter, $limit, $needReturn )
	//
	// retrieves data from the database
	// creates the sql-query and checks if there are at least a name of the table
	// and an array of parameters
	// if $table is a string, the function will seek for an predefined sql-query
	// in the array $views with the given name. The parameters are the values
	// that will replace the placeholders in this "sql-view".
	// if $table is an array, the function will create with the given parameters
	// an select-query from scratch.
	//
	//
	// return
	//   based on $needReturn (default: true) the return values varies:
	//	 $neeedReturn	true	$db			database-object from phplib
	//                  false	no return value
	//   in case of any error, the function returns no value
	//
	// implementation details see class.querybuilder_mysql.php
	//
	function select( $table, $parameter, $limit = '', $needReturn = true ) {
	}

	//
	// get_sqldata()
	//
	// implementation details see class.querybuilder_mysql.php
	//
	function get_sqldata( $parameter ) {
	}

	//
	// method of the class
	//
	// exec_query()
	// create_parameter_array( $parameter, $caller )
	// make_string_dump($tmp = '')
	// set_magic_quotes_gpc(&$code)
	//

	//
	// exec_query()
	//
	// executes any SQL-Statement and returns the $db-Objekt with the result
	// if $needReturn is set to true
	//
	function exec_query() {
		if ($this->debugmode) $this->sql_debuglist[] = $this->sql;
		if ($this->buildermode) $this->sql_builderlist[] = $this->sql;
		if ($this->forceexecute || (!$this->buildermode && !$this->debugmode)) {
			$this->db->query($this->sql);
			if ($this->needReturn) return $this->db;
		}
	}

	//
	// create_parameter_array( $parameter, $caller )
	//
	// constructs the parameter-strings for the different query-methods
	// uses get_sqldata to create a valid SQL-Value
	//
	function create_parameter_array( $parameter, $caller ) {
		// construct the parameter values
		$sql = $tables = $fields = $values = $where = $join = $fieldlist = $order = '';
		
		foreach($parameter as $field => $content) {
			$value = $this->get_sqldata($content);
			if ($value || $value == 0) {
				switch($caller) {
					case 'view':
						$tmp_data[$field] = $value;
						break;
					case 'insert':
						$fields .= $field . ', ';
						$values .= $value . ', ';
					case 'update':
						if (!$content[2]) $sql   .= $field . '=' . $value . ', ';
					case 'delete':
						if ( $content[2]) $where .= ' ' . $field . $content[2] . $value . ' ' . $content[3];
						break;
					default:
						break;
				}
			}
			// table and fieldlist for select statements, $value isn't a good check for this parameter
			switch($caller) {
				case 'table':
					$tables .= $this->cms_db[$field] . (($content[0])? ' '.$content[0]: '');
					if (!empty($content[1])) {
						$tables .= (($join) ? $join . $content[2]:'') . ' ' . $content[1] . ' ';
						$join    = ' ON ' . (($join) ? $content[4]:$content[2]) . $content[3];
					} else {
						$tables .= ', ';
					}
					if (empty($content[1]) && !empty($content[2])) $join .= $content[2];
					break;
				case 'fields':
					switch ($content[1]) {
						case 'timestamp':
							if (!empty($content[4])) $fieldlist .= $value . (($content[4] != '_key') ? ' ' . $content[4]: '')  . ', ';
							if (!empty($content[5])) $order     .= ((!empty($content[4]) && $content[4] != '_key') ? $content[4]: $value) . ' ' . $content[5] . ', ';
							break;
						default:
							if (!empty($content[2])) $where     .= ' ' . $field . $content[2] . $value . ' ' . $content[3];
							if (!empty($content[4])) $fieldlist .= $field . (($content[4] != '_key') ? ' ' . $content[4]: '')  . ', ';
							if (!empty($content[5])) $order     .= ((!empty($content[4]) && $content[4] != '_key') ? $content[4]: $field) . ' ' . $content[5] . ', ';
							break;
					}
					break;
				default:
					break;
			}
		}
		// construct return array ...
		// caller = 'view' -> nothing to do, else create array items
		switch($caller) {
			case 'view':
				break;
			case 'table':
				if (substr($tables, -2, 2) == ', ') $tables = substr($tables, 0, strlen($tables)-2);
				$tmp_data['table'] = $tables . $join;
				break;
			case 'fields':
				$tmp_data['result'] = substr($fieldlist, 0, strlen($fieldlist)-2);
				$tmp_data['order']  = substr($order,     0, strlen($order)-2    );
				if (strlen($where) >= $this->minWhereLen) $tmp_data['where'] = $where;
				break;
			default:
				$tmp_data['fields'] = substr($fields, 0, strlen($fields)-2);
				$tmp_data['values'] = substr($values, 0, strlen($values)-2);
				$tmp_data['sql']    = substr($sql,    0, strlen($sql)-2   );
				if (strlen($where) >= $this->minWhereLen) $tmp_data['where'] = $where;
				break;
		}
		return ($tmp_data);
	}


	//
	// helper functions
	//
	function make_string_dump($tmp = '') {
		$tmp = str_replace('\\', '\\\\', $tmp);
		$tmp = str_replace('\'', '\\\'', $tmp);
		$tmp = str_replace(array('\x00', '\x0a', '\x0d', '\x1a'), array('\0', '\n', '\r', '\Z'), $tmp);
		return $tmp;
	}

	function set_magic_quotes_gpc(&$code) {
		if (get_magic_quotes_gpc() == 0) $code = addslashes($code);
	}

	//
	// shortcut functions
	//
	// delete_by_id_and_client( $table, $id, $value, $client = '' )
	// select_record( $table, $parameter, $type = 1, $limit = '' )
	//

	function delete_by_id_and_client( $table, $id, $value, $client = '' ) {
		if ($client == '') $this->delete_row( $table, array($id=>array($value, 'num', '=')) );
		else $this->delete_row( $table, array($id=>array($value, 'num', '=', 'AND'), 'idclient'=>array($client, 'num', '=')) );
	}

	function select_record( $table, $parameter, $type = 1, $limit = '' ) {
		$this->select( $table, $parameter, $limit, false );
		if ($this->db->next_record()) {
			$tmp_data = $this->db->Record;
			if (!$type) {
				foreach($tmp_data as $key => $value) $tmp_data[$key] = $this->make_string_dump($value);
			}
			return $tmp_data;
		}
	}
}
?>