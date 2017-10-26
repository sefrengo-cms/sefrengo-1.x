<?php 
// File: $Id: class.SF_API_Object.php 28 2008-05-11 19:18:49Z mistral $
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
// + Description: The Common SF_API_Object
// +----------------------------------------------------------------------+
// + Changes:
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
/**
* The Common SF_API_Object
*
* This Class provide the general SF_API_Object.
* Any API-Object must inherit this Class.
*
* @package API , the Common API Package.
* @name SF_API_Object
*/
class SF_API_Object {
    /**
    * Class Name
    *
    * The Common Class identifier.
    *
    * @param  string
    */
    var $_API_name = '';

    /**
    * Debug Object
    *
    * This enable the debug feature.
    *
    * @param  object
    */
    var $_API_debug_object;
    
    /**
    * Singleton Flag
    *
    * This Flag enable the singleton feature.
    *
    * @param  boolean
    */
    var $_API_is_singleton = false;

    /**
    * Bridge Flag
    *
    * This Flag enable the bridging feature.
    *
    * @param  boolean
    */
    var $_API_is_object_bridge = false;
    
    /**
    * Error Message
    *
    * This string identify the Object-Errormessage.
    *
    * @param  string
    */
    var $_API_object_error_message = '';

    /**
    * Object version
    *
    * This string identify the SF_API_Object Version.
    *
    * @param  string
    */
    var $_API_object_version = '$Revision: 28 $';// need to overide in any API-Object
    
    /**
    * Common Class Constructor
    *
    * The Class Constructor.
    *
    */
    function __construct() { ;
    } 

    /**
    * Set Object is singleton
    *
    * If this set (boolean) true the Classloader will handle the Object as an
    * singleton.
    *
    * @access protected
    * @input (boolean) $bool
    */
    function _API_setObjectIsSingleton($bool) {
        $this->_API_is_singleton = (boolean) $bool;
    } 

    /**
    * Is Object singleton
    * 
    * Returns true if the object should be handle as singleton,
    * otherwise false.
    * 
    * @access protected 
    * @return (boolean)
    */
    function _API_objectIsSingleton() {
        return $this->_API_is_singleton;
    } 

    /**
    * Set Object use bridge
    *
    * If this set (boolean) true the classloader will take the object
    * from the method _API_getBridgeObject().
    * 
    * @access protected 
    * @input (boolean) $bool
    */
    function _API_setObjectBridge($bool) {
        $this->_API_is_object_bridge = (boolean) $bool;
    } 

    /**
    * Trigger Error
    *
    * If this set (string) to an Errormessage it will push the message
    * in the Error Stack.
    * 
    * @access protected
    * @input (string) $string 
    */
    function _API_triggerObjectError($string) {
        ;// overwrite me
    } 

    /**
    * Use Object bridge
    *
    * Returns true if the classloader debit the object from the method
    * _API_getBridgeObject()
    * 
    * @access protected
    * @return (boolean) 
    */
    function _API_isObjectBridge() {
        return $this->_API_is_object_bridge;
    } 

    /**
    * Get the bridge object
    * 
    * Return a new Object from bridged Object.
    *
    * @access public 
    * @return (object)
    */
    function &_API_getBridgeObject() {
        return new object;// overwrite me
    } 

    /**
    * Destroy Object
    * 
    * The API classloader call this method before the object will be
    * destroyed from the object Store.
    * 
    * @access public 
    * @uses Call this to safe close the object.      
    */
    function _API_unload() {
        ;// overwrite me
    } 

    /**
    * Instant Object
    *
    * The API classloader call this method before the object will 
    * be reanimate from the object Store.
    * 
    * @access public 
    * @uses Call this for reanimate arrays or objects.      
    */
    function _API_instant() {
        ;// overwrite me
    } 

    /**
    * Object Error 
    * 
    * The API classloader call this method when the object will crashed.
    * 
    * @access public
    * @uses Call this for API errorhandling.          
    */
    function _API_error() {
        ;// overwrite me
    } 

    /**
    * Check Instance
    *
    * The API classloader call this method after instance the object.
    * 
    * @access private
    * @return (boolean) 
    * @uses Call this for instance the object.
    */
    function _API_instance() { 
        return SF_API_Object::__construct();// overwrite me
    }
    
    /**
    * Provide Debug-Info
    *
    * The API classloader call this method to debug the object.
    * 
    * @access private
    * @input (object) 
    * @return (object)
    * @uses Call this for debug the object.
    */
    function _API_debug() {
        preg_match('/\$revision: ([^\$]+)* \$/si', $this->_API_object_version, $match);
        if ($match[1]) $_revision = $this->_API_name . ': v' . $match[1];
        else $_revision = $this->_API_object_version;
        if (is_object($this->_API_debug_object)) {
            $this->_API_debug_object->collect($_revision);
        } else return $_revision . "\n";
    } 

    /**
    * Check Object
    *
    * The API classloader call this method to check the object.
    * 
    * @access public 
    * @return (boolean)
    * @uses Call this for check the object.
    */
    function _API_checkObject() {
        if (is_a($this, 'SF_API_Object')) {
            $this->_API_name = get_class($this);
            return true;
        }
        return false;
    } 
    
} 
?>