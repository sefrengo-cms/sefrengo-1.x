<?PHP
// File: $Id: class.querybuilder_mysql.php 28 2008-05-11 19:18:49Z mistral $
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

/*
** Querybuilder for mysql
** 
** Description : querybuilder for mysql-database
**
** Copyright   : Jürgen Brändle, 2002-2003
** Author      : Jürgen Brändle, braendle@web.de
** Urls        : www.Sefrengo.de
** Create date : 2003-02-09
** Last update : 2003-05-17
**
** IMPORTANT:
** do not use for production yet ... classes are experimental
*/
class querybuilder_mysql extends querybuilder
{
	// constructor
	function __construct() {
	}

	//
	// insert( $table, $check, $parameter )
	//
	// insert the given values into the database
	// creates the sql-query and checks if there are at least a name of the table and an array of parameters
	// if $check is given, then the insert will be check with a MAX($check)-SELECT with the given where-clauses
	// or returns the value of mysql_insert_id() if where-clause is missing
	//
	// remark: alias and order parts are ignored for an insert-statement
	// 
	// return
	//   0  insert failed or the requirements ($table, $parameter needed) were not met
	//  -1  insert has been successful, but no check was made
	//		insert has been successful, but check has failed because of missing where-clauses
	// any  any other number returned will be the maximum value for the checked field $check
	//
	// error
	//   error will be set to 1310 if any of the values was not set: $table, $parameter
	//
	//
	// parameter details
	// $table	string		name of the table where the insert should happen
	//						uses a reference to $cms_db[]
	// $check	string		name of the db-field which value will be returned if the insert 
	//						has to be checked afterwards
	// $parameter	array	parameter array
	//						every element has the following structure:
	//						fieldname => array ( 0 => 'value', 1 => 'type', 2 => 'komperator', 3 => 'whereconnector', 4 => 'alias', 5 => 'order' )
	//						fieldname -> name of the field for field list in insert
	//								  -> name of a where-clause field (only if 3rd element is set)
	//						fieldname[0]	-> value for insert
	//										-> value for where-clause (only if 3rd element is set)
	//						fieldname[1]	-> type of data, used to prepare the sql-value for the query
	//						fieldname[2]	-> operator for the where-clause
	//										   if this is set, the field will be used in the verify query as where-clause
	//						fieldname[3]	-> defines the connection of where-clause with the following where-clause
	//										   if there are more than one where-clauses, then this field has to be set for
	//										   any where-clause except the last one
	//						fieldname[4]	-> ignored in insert-statement
	//						fieldname[5]	-> ignored in insert-statement
	//
	// remark
	// the function will return the value of an autoincrement field in the table
	// if $check is set and no values for a verify query are set, i.e. all fieldname[2] 
	// and fieldname[3] are unset
	// it is recommended to leave the where clause fields empty to get an autoincrement
	// value for the last insert-statement
	//
	function insert( $table, $check, $parameter ) {
		if (!isset($parameter) || !$table) {
			$this->errno[] = '1310';
			return 0;
		}
		// create update query
		$tmp_data = $this->create_parameter_array( $parameter, 'insert' );
		// do insert
		$this->sql = 'INSERT INTO ' . $this->cms_db[$table] . ' (' . $tmp_data['fields'] . ') VALUES (' . $tmp_data['values'] . ')';
		$this->exec_query();
		$this->lastInsert = mysqli_insert_id($GLOBALS['db']->Link_ID);
		// check result
		if ($check) {
			if (!$tmp_data['where']) {
				return $this->lastInsert;
			} else {
				$this->sql = 'SELECT MAX(' . $check . ') id FROM ' . $this->cms_db[$table] . ' WHERE' . $tmp_data['where'];
				$this->exec_query();
				// if insert failed, return 0, else return the id of the inserted record
				return (!($this->db->next_record())) ? 0 : $this->db->f('id'); // return result
			}
		}
		return -1;
	}

	//
	// update( $table, $parameter )
	//
	// updates the given values in the database
	// creates the sql-query and checks if there are at least a name of the table and an array of parameters
	// if there is no valid where-clause the update will not be executed
	//
	// remark: the update is not verified
	// 
	// return
	//   true  update has been executed
	//  false  missing parameter or no valid where-clause created
	//
	// error
	//   error will be set to 1320 if any of the values were not set: $table, $parameter
	//   error will be set to 1321 if the parameter will not compute to a valid where-clause
	//
	//
	// parameter details
	// $table	string		name of the table where the update should happen
	//						uses a reference to $cms_db[]
	// $parameter	array	parameter array
	//						every element has the following structure:
	//						fieldname => array ( 0 => 'value', 1 => 'type', 2 => 'komperator', 3 => 'whereconnector', 4 => 'alias', 5 => 'order' )
	//						fieldname -> name of the field to be updated
	//								  -> name of a where-clause field (only if 3rd element is set)
	//						fieldname[0]	-> value for update
	//										-> value for where-clause (only if 3rd element is set)
	//						fieldname[1]	-> type of data, used to prepare the sql-value for the query
	//						fieldname[2]	-> operator for the where-clause
	//										   if this is set, the field will be used as where-clause
	//						fieldname[3]	-> defines the connection of where-clause with the following where-clause
	//										   if there are more than one where-clauses, then this field has to be set for
	//										   any where-clause except the last one
	//						fieldname[4]	-> ignored in update-statement
	//						fieldname[5]	-> ignored in update-statement
	//
	function update( $table, $parameter ) {
		if (!isset($parameter) || !$table) {
			$this->errno[]  = '1320';
			return false;
		}
		// create update query
		$tmp_data = $this->create_parameter_array( $parameter, 'update' );
		// do update and leave
		if ($tmp_data['where']) {
			$this->sql = 'UPDATE ' . $this->cms_db[$table] . ' SET ' . $tmp_data['sql'] . ' WHERE' . $tmp_data['where'];
			$this->exec_query();
			return true;
		}
		// error: no where-clause
		$this->errno[]  = '1321';
		return false;
	}

	//
	// delete_row( $table, $parameter )
	//
	// deletes the given values in the database
	// creates the sql-query and checks if there are at least a name of the table and an array of parameters
	// if there is no valid where-clause the delete will not be executed
	//
	// remark: the delete is not verified
	// 
	// return
	//   true  delete has been executed
	//  false  missing parameter or no valid where-clause created
	//
	// error
	//   error will be set to 1330 if any of the values were not set: $table, $parameter
	//   error will be set to 1331 if the parameter will not compute to a valid where-clause
	//
	//
	// parameter details
	// $table	string		name of the table where the delete should happen
	//						uses a reference to $cms_db[]
	// $parameter	array	parameter array
	//						every element has the following structure:
	//						fieldname => array ( 0 => 'value', 1 => 'type', 2 => 'komperator', 3 => 'whereconnector', 4 => 'alias', 5 => 'order' )
	//						fieldname -> name of a where-clause field (only if 3rd element is set)
	//						fieldname[0]	-> value for where-clause (only if 3rd element is set)
	//						fieldname[1]	-> type of data, used to prepare the sql-value for the query
	//						fieldname[2]	-> operator for the where-clause
	//										   if this is set, the field will be used as where-clause
	//						fieldname[3]	-> defines the connection of where-clause with the following where-clause
	//										   if there are more than one where-clauses, then this field has to be set for
	//										   any where-clause except the last one
	//						fieldname[4]	-> ignored in update-statement
	//						fieldname[5]	-> ignored in update-statement
	//
	function delete_row( $table, $parameter ) {
		if (!isset($parameter) || !$table) {
			$this->errno[]  = '1330';
			return false;
		}

		$tmp_data = $this->create_parameter_array( $parameter, 'delete' );
		// do delete and leave
		if ($tmp_data['where']) {
			$this->sql = 'DELETE FROM ' . $this->cms_db[$table] . ' WHERE ' . $tmp_data['where'];
			$this->exec_query();
			return true;
		}
		// error: no where-clause
		$this->errno[]  = '1331';
		return false;
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
	//		$neeedReturn	true	$db			database-object from phplib
	//                     false	no return value
	//   in case of any error, the function return false
	//
	// error
	//   error will be set to 1340 if any of the values were not set: $table, $parameter
	//
	//
	// parameter details
	// $table		string	name of the view, which should be used
	// $parameter	array	parameter array
	//						every element has the following structure:
	//						fieldname => array ( 0 => 'value', 1 => 'type', 2 => 'komperator', 3 => 'whereconnector', 4 => 'alias', 5 => 'order' )
	//						fieldname -> name of a where-clause field (only if 2nd element is set)
	//						fieldname[0]	-> name of the placeholder in the sql-view
	//						fieldname[1]	-> type of data, used to prepare the sql-value for the placeholder
	//						fieldname[2]	-> ignored in this type of the select-statement
	//						fieldname[3]	-> ignored in this type of the select-statement
	//						fieldname[4]	-> ignored in this type of the select-statement
	//						fieldname[5]	-> ignored in this type of the select-statement
	// $limit		array	limit array - predefined: '' - no limit, return all records found
	//						limit[0]	lower limit, start value
	//						limit[1]	upper limit, end value
	// $needReturn	boolean	flag to control the return value of this method - predefined: true
	//						true	return the database-object from phplib
	//						false	no return value
	//
	// or
	//
	// $table		array	table array
	//						every element has the following structure:
	//						tablename => array ( 0 => 'alias', 1 => 'jointype', 2 => 'joinfield', 3 => 'joinoperator' )
	//						tablename		-> name of the table
	//						tablename[0]	-> alias for the table
	//						tablename[1]	-> name of a join to make a relationship for two tables
	//						tablename[2]	-> name of the filed for the join
	//						tablename[3]	-> operator for the join
	//						remark: only two tables may be join in a select-statement
	//								if there is a need to join more tables make a sql-view and use this function with the 
	// $parameter	array	parameter array
	//						every element has the following structure:
	//						fieldname => array ( 0 => 'value', 1 => 'type', 2 => 'komperator', 3 => 'whereconnector', 4 => 'alias', 5 => 'order' )
	//						fieldname -> name of a where-clause field (only if 2nd element is set)
	//						fieldname[0]	-> value for the fieldname for the where-clause (only if 3rd element is set)
	//						fieldname[1]	-> type of data, used to prepare the sql-value for the query (only if 3rd element is set)
	//						fieldname[2]	-> operator for the where-clause
	//										   if this is set, the field will be used as where-clause
	//						fieldname[3]	-> defines the connection of where-clause with the following where-clause
	//										   if there are more than one where-clauses, then this field has to be set for
	//										   any where-clause except the last one
	//						fieldname[4]	-> alias for a fieldname
	//										-> needed if the field should be part of the fields return from the database
	//										-> to use the unchanged fieldname set this element to '_key'
	//						fieldname[5]	-> ordering sequence for this field
	//										-> if set the fieldname or the alias will be added to the order-clause
	//										-> there are two possible value: 'ASC' or 'DESC'
	//										-> the sorting order will be added to the fieldname
	// $limit		array	limit array - predefined: '' - no limit, return all records found
	//						limit[0]	lower limit, start value
	//						limit[1]	upper limit, end value
	// $needReturn	boolean	flag to control the return value of this method - predefined: true
	//						true	return the database-object from phplib
	//						false	no return value
	//
	function select( $table, $parameter, $limit = '', $needReturn = true ) {
		if (!isset($parameter) || !$table) {
			$this->errno[]  = '1340';
			return false;
		}
		// create update query
		if (is_array($table)) {
			// complex query
			$tmp_table = $this->create_parameter_array( $table, 'table' );
			$tmp_data  = $this->create_parameter_array( $parameter, 'fields' );
			$sql = 'SELECT ' . $tmp_data['result'] . ' FROM ' . $tmp_table['table'];
			if ($tmp_data['where']) $sql .= ' WHERE ' . $tmp_data['where'];
			if ($tmp_data['order']) $sql .= ' ORDER BY ' . $tmp_data['order'];
			if ($limit) $sql .= ' LIMIT ' . $limit[0] . ', ' . $limit[1];
		} else {
			// view query
			$tmp_data = $this->create_parameter_array( $parameter, 'view' );
			eval( '$sql = "'. $this->views[$table] . '";' );
		}
		// do select and return result
		if ($sql) {
// echo $sql."<br>";
			$this->sql = $sql;
			$this->exec_query();
		}
		if ($needReturn) return $this->db;
	}

	//
	// get_sqldata()
	//
	//
	function get_sqldata( $parameter ) {
		
		$result = '';
		if (!isset($parameter)) {
			$this->errno[]  = '1350';
		}
		// create a string
		switch( $parameter[1] ) {
			case 'bool':
			case '1':
				$result = $parameter[0];
				break;
			case 'date':
			case '2':
				$result = '\'' . $parameter[0] . '\'';
				break;
			case 'func':
			case '3':
				// function
				$result = $parameter[0];
				break;
			case 'std':
			case '4':
				// standard values: userid, username, current date and time, client, language
				switch($parameter[0]) {
					case 'uid':
						$result = $GLOBALS['auth']->auth[$parameter[0]];
						break;
					case 'uname':
						$result = '\'' . $GLOBALS['auth']->auth[$parameter[0]] . '\'';
						break;
					case 'client':
						$result = $GLOBALS['client'];
						break;
					case 'datetime':
						$result = '\'' . date("Y-m-d H:i:s") . '\'';
						break;
					case 'lang':
						$result = '\'' . $GLOBALS['cfg_cms']['backend_lang'] . '\'';
						break;
				}
				break;
			case 'client':
			case '5':
				// cfgClient-Wert
				$param = $GLOBALS['cfg_client'][$parameter[0]];
				$this->set_magic_quotes_gpc($param);
				$result = '\'' . $param . '\'';
				break;
			case 'str':
			case '6':
				// string, escape delimiter and encapsulate the string in delimiter
				$this->set_magic_quotes_gpc($parameter[0]);
				$result = '\'' . $parameter[0] . '\'';
				break;
			case 'timestamp':
			case '7':
				// encapsulate the string a special function
				$result = 'UNIX_TIMESTAMP(' . $parameter[0] . ')';
				break;
			case 'timestamp1':
			case '8':
				// encapsulate the string a special function
				$result = 'FROM_UNIXTIME(' . $parameter[0] . ')';
				break;
			default:
				$result = $parameter[0];
				break;
		}
		return $result;
	}
	
}
?>