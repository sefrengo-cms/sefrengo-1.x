<?php
// File: $Id: class.SF_GUI_ContentStylerPlain.php 28 2008-05-11 19:18:49Z mistral $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name$
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
class SF_GUI_ContentStylerPlain extends SF_API_Object {
  //privat
    /**
    * Class Name
    *
    * The Common Class identifier.
    *
    * @param  string
    */
    var $_API_name = 'ContentStylerPlain';

    /**
    * Singleton
    *
    * This Flag enable the singleton feature.
    *
    * @param  boolean
    */
    var $_API_is_singleton = true;

    /**
    * Object version
    *
    * This string identify the SF_API_Object Version.
    *
    * @param  string
    */
    var $_API_object_version = '$Revision: 28 $';
    var $_API_object_internalversion = '00.02.00';

    var $_db;

    var $_content = array();

    var $_config = array( 'idlang' => 0, 
						  'idside' => 0, 
						  'container' => -1,    						
    					  'number' => -1, 	
    					);

  //public

    /**
    * Common Class Constructor
    *
    * The Class Constructor.
    *
    */
    function __construct() {
        // constructor

    }

    /*
     * GET METHODS
     */
    /**
    * Version Info
    * 
    * The Version fot this Class
    *
    * @access public 
    * @return array  
    */
    function getVersion() {
        $_version['class']  = $this->_API_name;
        $_version['version'] = $this->_API_object_version;
        $_version['internalversion']   = $this->_API_object_internalversion;
        $_version['debug'] = $this->_API_debug();
        $_pieces = explode('.', $_version['internalversion']);
        $_version['prior'] = $_pieces['0'];
        $_version['minor'] = $_pieces['1'];
        $_version['fix']   = $_pieces['2'];

        return $_version;
    }

    
    //****************************
    /**
    * Catch vars and arrays from the cmstag attributes. Returns a string wich must 
    * execute with eval() in the type-function, to get the dynamic values 
    * 
    *
    * @vars   array type_config - cms:tag attributes
    * @return string 
    */
    function _getDynamicValString($type_config)
    {
    	if ($type_config != "") {
    	    $to_eval = '';
    	    foreach($type_config AS $k=>$v){
    	    	if(preg_match("/^\\$/", $v)){
    	    		//globa delaration for array or single_var?
    	    		preg_match("/^\\$([^\\[]*)/", $v, $my_global);
    	    		$to_eval .= "global $".$my_global['1'].";\n";
    	    		$to_eval .= '$type_config["'.$k.'"] = '.$v.';'."\n";
    	    		//eval($to_eval);
    	    		//$type_config[$k] = $extracted_var;
    	    	}
    	    }
    	    return $to_eval;	      
    	}
    }
        
    
    /**
    * Styled text Output
    *
    * Frontendausgabe analog CMS:tag text
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getText($mod_content, $type_config = "") {
        return $mod_content;
    }
    
    
    
    /**
    * Styled textarea Output
    *
    * Frontendausgabe analog CMS:tag textarea
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getTextarea($mod_content, $type_config = "") {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled wysiwyg2 Output
    *
    * Frontendausgabe analog CMS:tag wysiwyg2
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getWysiwyg2($mod_content, $type_config = "")
    {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled wysiwyg Output
    *
    * Frontendausgabe analog CMS:tag wysiwyg
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getWysiwyg($mod_content, $type_config = "")
    {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled image Output
    *
    * Frontendausgabe analog CMS:tag image
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getImage($mod_content, $mod_descr, $type_config = "") {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled link Output
    *
    * Frontendausgabe analog CMS:tag link
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getLink($link_url, $link_desc, $link_target, $type_config = "")
    {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled file Output
    *
    * Frontendausgabe analog CMS:tag file
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getFile($file_id, $file_desc, $file_target, $type_config = "")
    {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled sourcecode Output
    *
    * Frontendausgabe analog CMS:tag sourcecode
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getSourcecode($mod_content, $type_config = "")
    {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled select Output
    *
    * Frontendausgabe analog CMS:tag select
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getSelect($mod_content, $type_config = "") {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled hidden Output
    *
    * Frontendausgabe analog CMS:tag hidden
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getHidden($mod_content, $type_config = "")
    {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled checkbox Output
    *
    * Frontendausgabe analog CMS:tag checkbox
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getCheckbox($mod_content, $type_config = "") {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled radio Output
    *
    * Frontendausgabe analog CMS:tag radio
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getRadio($mod_content, $type_config = "") {
        return $mod_content;    
    }
    
    
    
    /**
    * Styled date Output
    *
    * Frontendausgabe analog CMS:tag date
    *
    * @Args: string mod_content -> Inhalt wie er in der DB vorhanden ist
    *        array  $type_config -> Attribute und deren Werte analog des cms:tags
    * @Return String Content
    * @Access public
    */
    function getDate($mod_content, $type_config = "") {
        return $mod_content;    
    }

} 

?>