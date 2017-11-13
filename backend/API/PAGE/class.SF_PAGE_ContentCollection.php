<?php
// File: $Id: class.SF_PAGE_ContentCollection.php 28 2008-05-11 19:18:49Z mistral $
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
class SF_PAGE_ContentCollection extends SF_API_Object {
  //privat
    /**
    * Class Name
    *
    * The Common Class identifier.
    *
    * @param  string
    */
    var $_API_name = 'ContentCollection';

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

    var $_SF_styler;

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
 		global $lang, $cfg_cms;
     
        //include_once($cfg_cms['cms_path'].'inc/fnc.type_common.php');

        $this->_db =& sf_factoryGetObjectCache('DATABASE', 'Ado');

        $this->_SF_styler =& sf_factoryGetObjectCache('GUI', 'ContentStylerPlain');

        $this->_config['idlang'] = (int) $lang;

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


    function getNumberCount($container = -1) {
        if ($container == -1) {
            $container = $this->_config['container'];
        }
        if (is_array($this->_content[$container])) {
            end($this->_content[$container]);
            return key($this->_content[$container]);        
        } else {
            return 0;    
        }
    }


    /**
    * is Container available
    *
    * @Args: int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function isContainerAvailable( $idcontainer = -1) {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	$retrun = (is_array($this->_content[$idcontainer])) ? true : false;
        return $retrun;
    }


    /**
    * is Content available
    *
    * @Args: int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function isContentAvailable($idrepeat = -1, $idcontainer = -1) {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$retrun = (is_array($this->_content[$idcontainer][$idrepeat])) ? true : false;
        return $retrun;
    }


    /**
    * is text Content available
    *
    * @Args: int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function isTextAvailable($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['1'][$idcmstag]['1'] : '';
        if ( $content == '') {
            return false;    
        } else {
            return true;    
        }
    }

    
    //**************
    function _isParamContainerValid($idcontainer)
    {
    	if ($idcontainer < 1) {
    	    if ($this->_config['container'] < 1) {
    	        return false;    
    	    } else {
    	        $idcontainer = $this->_config['container'];
    	    }
    	}
    	return $idcontainer;
    }


    function _isParamNumberValid($idrepeat)
    {
    	if ($idrepeat < 1) {
    	    if ($this->_config['number'] < 1) {
    	        return 1;    //per default erste Wiederholung ausgeben
    	    } else {
    	        $idrepeat = $this->_config['number'];
    	    }
    	}
    	return $idrepeat;
    }

    
    
    /**
    * Content Output CMS:tag text
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getText($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['1'][$idcmstag]['1'] : '';
    	return $this->_SF_styler->getText($content, $config);
    }
    
    
    /**
    * Content Output CMS:tag textarea
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getTextarea($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['3'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getTextarea($content, $config);
    }
    
    
    /**
    * Content Output CMS:tag wysiwyg
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getWysiwyg($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['2'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getWysiwyg($content, $config);
    }
    
    
    /**
    * Content Output CMS:tag wysiwyg2
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getWysiwyg2($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['13'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getWysiwyg($content, $config);
    }
    
    
    /**
    * Content Output CMS:tag image
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getImage($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['4'][$idcmstag]['1'] : '';
        $mod_descr   = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['5'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getImage($content, $mod_descr, $config);
    }
    
    
    /**
    * Content Output CMS:tag link
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getLink($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$link_url       = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['6'][$idcmstag]['1'] : '';
    	$link_desc      = (is_array($this->_content[$idcontainer][$idrepeat])) ? htmlspecialchars($this->_content[$idcontainer][$idrepeat]['7'][$idcmstag]['1'], ENT_COMPAT, 'UTF-8') : '';
        $link_target    = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['8'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getLink($link_url, $link_desc, $link_target, $config);
    }
    
    
    /**
    * Content Output CMS:tag file
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getFile($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$file_id        = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['10'][$idcmstag]['1'] : '';
    	$file_desc      = (is_array($this->_content[$idcontainer][$idrepeat])) ? htmlspecialchars($this->_content[$idcontainer][$idrepeat]['11'][$idcmstag]['1'], ENT_COMPAT, 'UTF-8') : '';
    	$file_target    = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['12'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getFile($file_id, $file_desc, $file_target, $config);
    }
    
    
    /**
    * Content Output CMS:tag sourcecode
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getSourcecode($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['9'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getSourcecode($content, $config);
    }
    
    
    /**
    * Content Output CMS:tag textarea
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getSelect($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['14'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getSelect($content, $config);
    }
    
    
    /**
    * Content Output CMS:tag hidden
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getHidden($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['15'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getHidden($content, $config);
    }
    
    
    /**
    * Content Output CMS:tag chechbox
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getCheckbox($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['16'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getCheckbox($content, $config);
    }
    
    
    /**
    * Content Output CMS:tag radio
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getRadio($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['17'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getRadio($content, $config);
    }
    
    
    /**
    * Content Output CMS:tag date
    *
    * @Args: array  $config -> Attribute und deren Werte analog des cms:tags
    *        int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getDate($config, $idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// Content aus Array beziehen
    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['18'][$idcmstag]['1'] : '';
    
    	return $this->_SF_styler->getDate($content, $config);
    }


    /**
    * Content Object text
    *
    * @Args: int    $idcmstag -> ID des CMS:tag
    *        int    $idrepeat -> Wiederholung des Container/Mouduls
    *        int    $idcontainer -> ID des Container
    * @Return String Content
    * @Access public
    */
    function getTextObject($idcmstag, $idrepeat = -1, $idcontainer = -1)
    {
    	if (!($idcontainer = $this->_isParamContainerValid($idcontainer))) {
    	    return false;    
    	}
    	if (!($idrepeat = $this->_isParamNumberValid($idrepeat))) {
    	    return false;    
    	}
    	$idcmstag = (int) $idcmstag;

    	// ContentObject generieren
        $o =& sf_factoryGetObjectCache('PAGE', 'ContentFactory', 'SF_PAGE_ContentWysiwyg2');

        $o->setDefaults($this->_config['idside'], $idcontainer, $idcmstag, $idrepeat, $this->_config['idlang']);

    	$content = (is_array($this->_content[$idcontainer][$idrepeat])) ? $this->_content[$idcontainer][$idrepeat]['18'][$idcmstag]['1'] : '';
        $o->_setData( array('idcontent' => false, 
							'idsidelang' => $this->_config['idside'], 
							'container' => $idcontainer, 
							'number' => $idrepeat, 
							'idtype' => 1, 
							'typenumber' => false, 
							'value' => $content, 
							'online' => false, 
							'version' => false, 
							'author' => false, 
							'created' => false, 
							'lastmodified' => false), 
					 array('idside'=>$this->_config['idside'],
					       'idcat'=> false)); 
         	
    	return $o;
    }

    
    /*
     * SET METHODS
     */
    function setIdlang($idlang) {
    	$this->_config['idlang'] = (int) $idlang;
    	if ($this->_config['idlang'] < 1) {
            global $lang;
            $this->_config['idlang'] = (int) $lang;
    	}
    }


    /**
    * Set styler for the content
    *
    * @Args: string $styler -> Definiert den Styler fuer die Ausgabe
    * @Access public
    */    
    function setStyler($styler) {
        $this->_SF_styler =& sf_factoryGetObjectCache('GUI', $styler);
    }


    /**
    * Set idside of content
    *
    * @Args: int    $container -> ID des Container
    *        int    $number -> Wiederholung des Container/Mouduls
    * @Access public
    */    
    function setIdside($idside)
    {
    	$this->_config['idside'] = (int) $idside;
    }


    /**
    * Set container of content
    *
    * @Args: int    $container -> ID des Container
    *        int    $number -> Wiederholung des Container/Mouduls
    * @Access public
    */
    function setContainer($container, $number = -1)
    {
    	$this->_config['container'] = (int) $container;
        if ($number != -1) {
        	$this->_config['number'] = (int) $number;
        }
    }


    /**
    * Set number of content
    *
    * @Args: int    $number -> Wiederholung des Container/Mouduls
    * @Access public
    */
    function setNumber($number = -1)
    {
        $this->_config['number'] = (int) $number;
    }


    /*
     * METHODS
     */
    function _getSideContent()
    {
		global $db, $perm, $cms_db, $client, $cfg_client, $cfg_cms;
    
        // Content-Array erstellen
        $sql = 'SELECT A.idcontent, container, number, idtype, typenumber, value FROM '.$cms_db[content].' A LEFT JOIN '.$cms_db[side_lang].' B USING(idsidelang) WHERE B.idside=\''.$this->_config['idside'].'\' AND B.idlang=\''.$this->_config['idlang'].'\' ORDER BY number';

        $db->query($sql);
        while ($db->next_record()) {
            $this->_content[$db->f('container')][$db->f('number')][$db->f('idtype')][$db->f('typenumber')] = array($db->f('idcontent'), $db->f('value'));
        }
    
    	return true;
    }
    
    
    function _getContainerSideContent()
    {
        global $db,$cms_db;

		if ($this->_config['container'] < 1) {
			return false;
		}
       
        // Content-Array erstellen
        if ($this->_config['number'] == -1) {
            $sql = 'SELECT A.idcontent, container, number, idtype, typenumber, value FROM '.$cms_db[content].' A LEFT JOIN '.$cms_db[side_lang].' B USING(idsidelang) WHERE B.idside=\''.$this->_config['idside'].'\' AND B.idlang=\''.$this->_config['idlang'].'\' AND A.container=\''.$this->_config['container'].'\' ORDER BY number';
        } else {
            $sql = 'SELECT A.idcontent, container, number, idtype, typenumber, value FROM '.$cms_db[content].' A LEFT JOIN '.$cms_db[side_lang].' B USING(idsidelang) WHERE B.idside=\''.$this->_config['idside'].'\' AND B.idlang=\''.$this->_config['idlang'].'\' AND A.container=\''.$this->_config['container'].'\' AND A.number=\''.$this->_config['number'].'\' ORDER BY number';
        }
        $db->query($sql);
        while ($db->next_record()) {
            $this->_content[$db->f('container')][$db->f('number')][$db->f('idtype')][$db->f('typenumber')] = array($db->f('idcontent'), $db->f('value'));
        }

    	return true;
    
    }

    function generate() {

        $this->_content = array();

		if ($this->_config['idlang'] < 1 || $this->_config['idside'] < 1) {
			return false;
		}

        if ($this->_config['container'] == -1) {
            // get all content from this side 
            return $this->_getSideContent();
            
        } else {
            // get conten form onliy one container
            return $this->_getContainerSideContent();
            
        }
    }

} 

?>