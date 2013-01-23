<?php 
// File: $Id: class.SF_API_ObjectStore.php 28 2008-05-11 19:18:49Z mistral $
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
// + Description: The SF_API_ObjectStore
// +----------------------------------------------------------------------+
// + Changes:
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
/**
* The SF_API_ObjectStore
*
* This Class provide the general SF_API_ObjectStore.
* Any API-Object can stored within this Class.
*
* @package API , the Common API Package.
* @name SF_API_ObjectStore 
*/
class SF_API_ObjectStore {
    /**
    * Store
    *
    * The Store identifier.
    * @param  array
    */
    var $_store = array();

    /**
    * Common Class Constructor
    *
    * The Class Constructor.
    *
    */
    function SF_API_ObjectStore() { ;
    }
    
    /**
    * Is Object stored
    * 
    * Returns true if the object stored in the Store,
    * determinate with $identifier and $alias.
    * 
    * @access protected 
    * @input (string) $identifier
    * @input (string) $alias
    * @return (boolean)
    */
    function isStored($identifier, $alias) {
        return is_object($this->_store[$identifier][strtolower($alias)]);
    } 

    /**
    * Add Object to Store
    *
    * Store an given object. Returns true if the object is stored in the Store,
    * determinate with $identifier and $alias.
    *
    * @access protected 
    * @input (string) $identifier
    * @input (string) $alias
    * @input (object) $object
    * @return (boolean) 
    */
    function add($identifier, $alias, &$object) {
        $this->_store[$identifier][strtolower($alias)] = &$object;
        return $this->isStored($identifier, $alias);
    } 

    /**
    * &GetObject from Store
    *
    * Returns the object from the Store,
    * determinate with $identifier and $alias.
    *
    * @access public 
    * @input (string) $identifier
    * @input (string) $alias
    * @return (mixed) 
    */
    function &get($identifier, $alias) {
        if ($this->isStored($identifier, $alias)) {
            $this->_store[$identifier][strtolower($alias)]->_API_instant();
            return $this->_store[$identifier][strtolower($alias)];
        } 
        
        return false;
    } 

    /**
    * Unload Object from Store
    *
    * Returns (boolean) true if the object unloaded from the Store,
    * determinate with $identifier and $alias.
    *
    * @access public 
    * @input (string) $identifier
    * @input (string) $alias , optional ; default: 'all'
    * @return (boolean) 
    */
    function unload($identifier, $alias = 'all') {
        if ($this->isStored($identifier, strtolower($alias))) {
            $this->_store[$identifier][strtolower($alias)]->_API_unload();
            $this->_store[$identifier][strtolower($alias)] = null;
            return true;
        } elseif (strtolower($alias) == 'all' && is_array($this->_store[$identifier])) {
            foreach ($this->_store[$identifier] as $_alias) {
                $this->_store[$identifier][$_alias]->_API_unload();
                $this->_store[$identifier][$_alias] = null;
            }
            return true; 
        } 

        return false;
    } 

    /**
    * Unload the Store
    *
    * Returns (boolean) true,
    * destroy all objects in Store by given $alias.
    *
    * @access public 
    * @input (string) $alias , optional ; default: 'all'
    * @return (boolean) 
    */
    function unloadAll($alias = 'all') {
        if (! is_array($this->_store)) {
        	return true;
        }
        
        $ikeys = array_keys($this->_store);
  
        foreach ($ikeys AS $iv) {
            $akeys = array_keys($this->_store[$iv]);
            foreach ($akeys as $av) {
                if ($alias != 'all' && $av != $alias) continue;
                $this->_store[$iv][$av]->_API_unload();
                $this->_store[$iv][$av] = null;
            } 
        } 

        return true;
    } 
} 

?>