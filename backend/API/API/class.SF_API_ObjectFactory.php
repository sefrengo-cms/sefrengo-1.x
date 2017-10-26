<?php 
// File: $Id: class.SF_API_ObjectFactory.php 363 2011-05-19 14:53:25Z joern $
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
// + Autor: $Author: joern $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 363 $
// +----------------------------------------------------------------------+
// + Description: The SF_API_ObjectFactory
// +----------------------------------------------------------------------+
// + Changes:
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
/**
* The SF_API_ObjectFactory
*
* This Class provide the general SF_API_ObjectFactory.
* Any API-Object can load with this Class.
*
* @package API , the Common API Package.
* @name SF_API_ObjectFactory
*/
class SF_API_ObjectFactory {
    /**
    * API Path
    *
    * The API Path identifier.
    *
    * @param  string
    */
    var $api_path = '';
    
    /**
    * Object Store
    *
    * The Common Class Store.
    *
    * @param  object
    */
    var $object_store;

    /**
    * Common Class Constructor
    *
    * The Class Constructor.
    *
    * @access protected 
    * @param string $api_path
    * @param (object) $object_store
    */
    function __construct($api_path, &$object_store) {
        $this->api_path = $api_path;
        $this->object_store = $object_store;
    } 
    
    
    function addIncludePath($path, $high_prio = false)
    {
		//set include pathes
		$ini_separator = strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? ';' : ':';
		
		$ini_original = ini_get('include_path');
		$ini_original = ( strlen($ini_original) > 0 ) ? $ini_original. $ini_separator: '';
		
		if ($high_prio)
		{
			$ini_original = preg_replace('#^\.'.$ini_separator.'#', '', $ini_original);
			ini_set('include_path', '.'. $ini_separator . $path . $ini_separator .$ini_original);
		}
		else
		{
			ini_set('include_path', $ini_original . $path . $ini_separator);
		}
		
		return true;
    }

    /**
    * Require Class
    *
    * Include an required File if exists in a holy black hole.
    *
    * @access protected
    * @param string $package
    * @param string $classname
    * @param string $class_prefix , optional, standart: 'SF'
    * @return (boolean)
    */
    function requireClass($package, $classname, $class_prefix = 'SF') {
       $file = strtoupper($package) . '/class.' 
              . $class_prefix . '_' . str_replace('/', '_', strtoupper($package)) 
              . '_' . $classname . '.php';

       include_once $file;

        return true;
    }
    
    function classExists($package, $classname, $class_prefix = 'SF')
    {
		$include_paths = explode(PATH_SEPARATOR, ini_get('include_path'));
		
		$file = strtoupper($package) . '/class.' 
              . $class_prefix . '_' . str_replace('/', '_', strtoupper($package)) 
              . '_' . $classname . '.php';

		foreach ($include_paths as $path) 
		{
			$include = $path.DIRECTORY_SEPARATOR.$file;
       		
       		if (is_file($include) && is_readable($include)) 
       		{
       			return TRUE;
			}
		}

		return FALSE;
    }
    

    /**
    * Get Object Forced
    *
    * Returns an Object, forced new Object. Deprecated, replaced with getObject
    *
    * @access public
    * @deprecated
    * @see getObject
    */   
    function &getObjectForced($package, $classname, $subclassname = null,
                              $parms = null, $class_prefix = 'SF') {
    	
    	return $this->getObject($package, $classname, $subclassname, $parms, $class_prefix);                         	
	}
	/**
    * Get Object
    *
    * Returns an Object, forced new Object.
    *
    * @access public
    * @deprecated
    * @param string $package
    * @param string $classname
    * @param string $subclassname , optional; standart: null
    * @param array  $parms , optional; standart: null
    * @param string $class_prefix , optional; standart: 'SF'
    * @return object
    */
        
    function &getObject($package, $classname, $subclassname = null,
                              $parms = null, $class_prefix = 'SF') {
        $cla = ($subclassname == null) ? $classname : $subclassname;
        $cla = $class_prefix . '_' . str_replace('/', '_', strtoupper($package))
             . '_' . $cla; 
        // handle singleton
        if ($this->object_store->isStored($cla, 'default')) {
            $obj = $this->object_store->get($cla, 'default');
            if ($obj->_API_objectIsSingleton()) {
                if ($obj->_API_isObjectBridge()) {
                    // handle bridge object
                    $bridge = $obj->_API_getBridgeObject();
                    return $bridge;
                } else {
                    // singleton object
                    return $obj;
                } 
            }
            unset($obj); 
        } 
        if ($this->requireClass($package, $classname)) {
            if (false === ($obj = $this->_getNewObject($cla, $parms))) return false; 
            
            if ($obj->_API_isObjectBridge()) {
                // handle bridge object
                $bridge = $obj->_API_getBridgeObject();
                return $bridge;
            } else {
                // standard object
                $obj->_API_instance();
                return $obj;
            } 
        } else {
            return false;
        } 
    } 

    /**
    * Get Object Cache
    *
    * Returns an Object, check for stored Object.
    *
    * @access public
    * @param string $package
    * @param string $classname
    * @param string $subclassname , optional; standart: null
    * @param array  $parms , optional; standart: null
    * @param string $cache_alias , optional; standart: 'default'
    * @param string $class_prefix , optional; standart: 'SF'
    * @return object
    */
    function &getObjectCache($package, $classname, $subclassname = null, $parms = null, 
                        $cache_alias = 'default', $class_prefix = 'SF') {
        $cla = ($subclassname == null) ? $classname : $subclassname;
        $cla = $class_prefix . '_' . str_replace('/', '_', strtoupper($package)) 
             . '_' . $cla;

        if ($this->object_store->isStored($cla, $cache_alias)) {
            $obj = $this->object_store->get($cla, $cache_alias);
        } else {
            if ($this->requireClass($package, $classname)) {
                if (false === ($obj = $this->_getNewObject($cla, $parms))) return false; 
                // force singleton
                if ($obj->_API_objectIsSingleton()) {
                    if ($this->object_store->isStored($cla, 'default')) {
                        $obj = $this->object_store->get($cla, 'default');
                    } else {
                        $this->object_store->add($cla, 'default', $obj);
                    } 
                    // normal cache
                } else {
                    $this->object_store->add($cla, $cache_alias, $obj);
                } 
            } else {
                // object does not exist
                return false;
            } 
        } 

        if ($obj->_API_isObjectBridge()) {
            // handle bridge object
            $bridge = $obj->_API_getBridgeObject();
            return $bridge;
        } else {
            // standard object
            $obj->_API_instance();
            return $obj;
        } 
    } 
    
    function objectExistsInCache($package, $classname, $subclassname = null, $cache_alias = 'default', $class_prefix = 'SF') {
    	$cla = ($subclassname == null) ? $classname : $subclassname;
        $cla = $class_prefix . '_' . str_replace('/', '_', strtoupper($package)) 
             . '_' . $cla;

        return $this->object_store->isStored($cla, $cache_alias);
    }
    
    function callMethod($package, $classname, $subclassname = null, $parms = null, $method, $methodparms = null,
                              $class_prefix = 'SF') {
			$obj =& $this->getObjectCache($package, $classname, $subclassname, $parms, $class_prefix);
			return $this->_callMethod($obj, $method, $methodparms);								
											
	}
    
    function callMethodCache($package, $classname, $subclassname = null, $parms = null, $method, 
										$methodparms = null, $cache_alias = 'default', $class_prefix = 'SF') {
			$obj =& $this->getObjectCache($package, $classname, $subclassname, $parms, $cache_alias, $class_prefix);
			return $this->_callMethod($obj, $method, $methodparms);											
											
	}

    /**
    * Unload Object
    *
    * Destroy an Object, check for stored Object.
    *
    * @access public
    * @param string $package
    * @param string $classname
    * @param string $subclassname , optional; standart: null
    * @param string $cache_alias , optional; standart: 'default'
    * @param string $class_prefix , optional; standart: 'SF'
    * @return (boolean) 
    */
    function unloadObject($package, $classname, $subclassname = null, 
                          $cache_alias = 'default', $class_prefix = 'SF') {
        $cla = ($subclassname == null) ? $classname : $subclassname;
        $cla = $class_prefix . '_' . str_replace('/', '_', strtoupper($package))
             . '_' . $cla;

        return $this->object_store->unload($cla, $cache_alias);
    } 

    /**
    * Unload Object Store
    *
    * Destroy any Object from Store.
    *
    * @access public
    * @return (boolean) 
    */
    function unloadAll($cache_alias = 'all') {
        return $this->object_store->unloadAll($cache_alias);
    } 

    /**
    * PRIVATE METHODS START HERE
    */
    
    /**
    * &Get new Object
    *
    * Returns an Object, by given name and params.
    *
    * @access private
    * @param string $full_classname
    * @param array  $parms
    * @return object 
    */
    function &_getNewObject($full_classname, &$parms) {
        $obj = '';
        if (is_array($parms)) {
            $keys = array_keys($parms);
            $c = count($keys);
            $pstring = '($parms["' . $keys['0'] . '"]';
            for ($i = 1; $i < $c;++$i) {
                $pstring .= ',$parms["' . $keys[$i] . '"]';
            } 
            $pstring .= ');';
        } else {
            $pstring = '();';
        } 

        $to_eval = '$obj = new ' . $full_classname . $pstring;
        eval($to_eval);
        if ($obj->_API_checkObject()) return $obj;
        
        return false;
    }
    
    function _callMethod(&$obj, &$method, &$parms) {
    	if (is_array($parms)) {
            $keys = array_keys($parms);
            $c = count($keys);
            $pstring = '($parms["' . $keys['0'] . '"]';
            for ($i = 1; $i < $c;++$i) {
                $pstring .= ',$parms["' . $keys[$i] . '"]';
            } 
            $pstring .= ');';
        } else if($parms != null) {
            $pstring = '('.$parms.');';
        } else {
        	$pstring = '();';
    	}
		$ret = false;
        $to_eval = '$ret = $obj->'.$method.$pstring;
        eval($to_eval);
        return $ret;
    }
    
} 
?>
