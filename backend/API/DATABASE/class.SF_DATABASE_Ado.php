<?php
// File: $Id: class.SF_DATABASE_Ado.php 28 2008-05-11 19:18:49Z mistral $
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

class SF_DATABASE_Ado extends SF_API_Object{
	var $conn_ado;
	
	function __construct() {
		global $cfg_cms;
		
		$this->_API_setObjectBridge(true);
		$this->_API_objectIsSingleton(true);
		
		include_once('adodb.inc.php');
		$this->conn_ado =& ADONewConnection('mysqli');
		
		if ($cfg_cms['db_mysql_pconnect'] === true) {
			$this->conn_ado->PConnect($GLOBALS['cfg_cms']['db_host'], $GLOBALS['cfg_cms']['db_user'], 
									$GLOBALS['cfg_cms']['db_password'], $GLOBALS['cfg_cms']['db_database']);
		} else {
			$this->conn_ado->Connect($GLOBALS['cfg_cms']['db_host'], $GLOBALS['cfg_cms']['db_user'], 
									$GLOBALS['cfg_cms']['db_password'], $GLOBALS['cfg_cms']['db_database']);
		}
		
		if ($cfg_cms['debug_active'] ) {
			$this->conn_ado->LogSQL();
		}
		if ($cfg_cms['db_utf8'] === true) {
      		$this->conn_ado->Execute("SET NAMES 'utf8'");
      	}
		
		
	}
	
	function &_API_getBridgeObject() {
		return $this->conn_ado;	
	}
		
}
?>
