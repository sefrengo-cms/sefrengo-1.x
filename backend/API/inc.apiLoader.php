<?php
// File: $Id: inc.apiLoader.php 28 2008-05-11 19:18:49Z mistral $
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

//get api path
$_api_path = str_replace ('\\', '/', dirname(__FILE__) . '/');

//set include pathes
$ini_separator = strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? ';' : ':';

//$ini_original = ini_get('include_path');
//$ini_original = ( strlen($ini_original) > 0 ) ? $ini_original. $ini_separator: '';
ini_set('include_path', '.' 
			. $ini_separator . $_api_path
			. $ini_separator . preg_replace('!/API/$!', '/external/adodb/', $_api_path)
			. $ini_separator . preg_replace('!/API/$!', '/external/pear.php.net/', $_api_path)			
			);

//echo $ini_original . $cfg_cms['cms_path'].  'external/adodb/';

/** @define "$_api_path" "/var/www/sefrengodev/htdocs/backend/API/" */
require_once ($_api_path.'API/class.SF_API_Object.php');
require_once ($_api_path.'API/class.SF_API_ObjectStore.php');
require_once ($_api_path.'API/class.SF_API_ObjectFactory.php');
 
$sf_factory = new SF_API_ObjectFactory($_api_path, new SF_API_objectStore());

// helper functions

function &sf_factoryGetObject($package, $classname, $subclassname = null, $parms = null, $class_prefix = 'SF') {
	return $GLOBALS['sf_factory']->getObject($package, $classname, $subclassname, $parms, $class_prefix);
}

function &sf_factoryGetObjectCache($package, $classname, $subclassname = null, $parms = null, $cache_alias = 'default', $class_prefix = 'SF') {
	return $GLOBALS['sf_factory']->getObjectCache($package, $classname, $subclassname, $parms, $cache_alias, $class_prefix);
}

function &sf_factoryObjectExistsInCache($package, $classname, $subclassname = null, $cache_alias = 'default', $class_prefix = 'SF') {
	return $GLOBALS['sf_factory']->objectExistsInCache($package, $classname, $subclassname, $cache_alias, $class_prefix);
}

function &sf_factoryCallMethod($package, $classname, $subclassname = null, $parms = null, $method, 
										$methodparms = null, $class_prefix = 'SF') {
	
	return $GLOBALS['sf_factory']->callMethod($package, $classname, $subclassname, $parms, $method, $methodparms, $class_prefix);
}

function &sf_factoryCallMethodCache($package, $classname, $subclassname = null, $parms = null, $method, 
										$methodparms = null, $cache_alias = 'default', $class_prefix = 'SF') {
	
	return $GLOBALS['sf_factory']->callMethodCache($package, $classname, $subclassname, $parms, $method, 
													$methodparms, $cache_alias, $class_prefix);
}



//START TESTING STUFF
//$sf_db =& $sf_factory->getObject('database', 'Ado'); 
//print_r($sf_db);
?>