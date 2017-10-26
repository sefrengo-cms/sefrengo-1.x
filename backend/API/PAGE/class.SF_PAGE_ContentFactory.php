<?php
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
// + Autor: Björn Brockmann
// +----------------------------------------------------------------------+
// + Revision: $Revision: 359 $
// +----------------------------------------------------------------------+
// + Description:
// + Simple Access to content objects.
// +----------------------------------------------------------------------+


/** 
 * Contnent Factory Class
 */ 
class SF_PAGE_ContentFactory extends SF_API_Object 
{
	function &getByTypenameAndIds($type, $idcatside, $idcontainer, $idcmstag = 1, $idrepeat = 1, $idlang = 0) 
	{
		//init
		$o = false;
		
		//cast
		$idcatside = (int) $idcatside;
		$idcontainer = (int) $idcontainer;
		$idcmstag = (int) $idcmstag;
		$idrepeat = (int) $idrepeat;
		$idlang = (int) $idlang;
		$type = strtolower($type);
		
		if ($idlang == 0)
		{
			$idlang = (int) $GLOBALS['lang'];
		}
		
		if ($idcatside < 1 || $idcontainer < 1 || $idcmstag < 1 || $idrepeat < 1 || $idlang < 1 )
		{
			return $o;
		}
		
		
		switch($type) 
		{
			case 'text':
			case 'textarea':
			case 'image':
			case 'link':
			case 'sourcecode':
			case 'file':
			case 'select':
			case 'hidden':
			case 'checkbox':
			case 'radio':
			case 'date':
			case 'wysiwyg':
			case 'wysiwyg2':
				$o =& sf_factoryGetObject('PAGE', 'ContentFactory', 'Content'.ucfirst($type));
				$o->loadByIds($idcatside, $idcontainer, $idcmstag, $idrepeat, $idlang);
				break;
		}
		
		return $o; 
	}	
}

class SF_PAGE_Content extends SF_API_Object 
{
	var $defaults = array( 
						'idcatside' => false,
						'idcontainer' => false,
						'idcmstag' => false,
						'idrepeat' => false,
						'idlang' => false,
						'idtype' => false,
						'typename' => 'undefined'
					);

	var $data = array(
					'content' => array(
									'idcontent' => false,
									'idsidelang' => false,
									'container' => false,
									'number' => false,
									'idtype' => false,
									'typenumber' => false,
									'value' => false,
									'online' => false,
									'version' => false,
									'author' => false,
									'created' => false,
									'lastmodified' => false,
								),
					'extra' => array(
								'idside' => false,
								'idcat' => false, 
								
								),
	);

	var $db;
	var $styler = array();
	
	function __construct($idtype)
	{
		$this->defaults['idtype'] = $idtype;
		$this->db =& sf_factoryGetObject('DATABASE', 'Ado');
	}
	
	function loadByIds($idcatside, $idcontainer, $idcmstag = 1, $idrepeat = 1, $idlang = 0)
	{
		return $this->_loadByIds($idcatside, $idcontainer, $idcmstag, $idrepeat, $idlang);
	}
	
	function getValue()
	{
		return $this->data['content']['value'];
	}
	
	function getValueStyled($config = array(), $styler = 'html', $_args = array())
	{
		$out = '';
		$styler = strtolower($styler);
		
		if (! array_key_exists($styler, $this->styler))
		{
			switch($styler)
			{
				case 'html':
					$GLOBALS['sf_factory']->requireClass('GUI', 'ContentStylerPlain');
					$this->styler[$styler] =& sf_factoryGetObjectCache('GUI', 'ContentStylerHTML');
					break;
				case 'plain':
					$this->styler[$styler] =& sf_factoryGetObjectCache('GUI', 'ContentStylerPlain');
				default:
					return $out;
			}
		}
		
		$methodname = 'get'.ucfirst($this->defaults['typename']);
		
		if (! method_exists ( $this->styler[$styler], $methodname))
		{
			return $out;
		}
		
		if (array_key_exists('2', $_args))
		{
			$out = $this->styler[$styler]->$methodname($this->getValue(), $_args['1'], $_args['2'], $config);
		}
		else if (array_key_exists('1', $_args))
		{
			$out = $this->styler[$styler]->$methodname($this->getValue(), $_args['1'], $config);
		}
		else
		{
			$out = $this->styler[$styler]->$methodname($this->getValue(), $config);	
		}
		
		return $out;
	}
	
	function save()
	{
		return false;
	}
	
	function delete()
	{
		return false;
	}
	
	function moveUp()
	{
		return false;
	}
	
	function moveDown()
	{
		return false;
	}
	
	function getIdtype()
	{
		
	}
	
	/**
	 * Set default ids. Must be set to get or save content.
	 * 
	 * @param int idcatside
	 * @param int idcontainer
	 * @param int idcmstag - optional, default value is 1
	 * @param int idrepeat - optional, default value is 1
	 * @param int idlang - optional, default value is current lang
	 * 
	 * @return bool - return true one success, otherwise false
	 */
	function setDefaults($idcatside, $idcontainer, $idcmstag = 1, $idrepeat = 1, $idlang = 0)
	{
		$idcatside = (int) $idcatside;
		$idcontainer = (int) $idcontainer;
		$idcmstag = (int) $idcmstag;
		$idrepeat = (int) $idrepeat;
		$idlang = (int) $idlang;
		
		if ($idlang == 0)
		{
			$idlang = (int) $GLOBALS['lang'];
		}
		
		if ($idcatside < 1 || $idcontainer < 1 || $idcmstag < 1 || $idrepeat < 1 || $idlang < 1)
		{
			return false;
		}
		
		$this->defaults['idcatside'] = $idcatside;
		$this->defaults['idcontainer'] = $idcontainer;
		$this->defaults['idcmstag'] = $idcmstag;
		$this->defaults['idrepeat'] = $idrepeat;
		$this->defaults['idlang'] = $idlang;
		
		return true;
	}

	/**
	 * Load a contenttype by given ids.
	 * 
	 * @param int idcatside
	 * @param int idcontainer
	 * @param int idcmstag - optional, default value is 1
	 * @param int idrepeat - optional, default value is 1
	 * @param int idlang - optional, default value is current lang
	 * 
	 * @return bool - return true one success, otherwise false
	 */	
	function _loadByIds($idcatside, $idcontainer, $idcmstag = 1, $idrepeat = 1, $idlang = 0)
	{
		$accept = $this->setDefaults($idcatside, $idcontainer, $idcmstag, $idrepeat, $idlang);
		
		if (! $accept)
		{
			return false;
		}
		
		$sql_idtype = '';
		if ( is_array($this->defaults['idtype']) )
		{
			$sql_idtype = " IN(".implode(',', $this->defaults['idtype']).") ";
		}
		else
		{
			$sql_idtype = " = ".$this->defaults['idtype'];
		}
		
		$sql = "SELECT 
					CS.idside,  CS.idcat, 
					C.idcontent, C.idsidelang, C.container, C.number, C.idtype, C.typenumber, 
					C.value, C.online, C.version, C.author, C.created, C.lastmodified
				FROM
					".$GLOBALS['cms_db']['cat_side']." CS
					LEFT JOIN ".$GLOBALS['cms_db']['side_lang']." SL USING(idside)
					LEFT JOIN ".$GLOBALS['cms_db']['content']." C ON (SL.idsidelang = C.idsidelang)
				WHERE
					C.idtype ".$sql_idtype."
					AND CS.idcatside = ".$this->defaults['idcatside']."
					AND C.container = ".$this->defaults['idcontainer']."
					AND C.typenumber = ".$this->defaults['idcmstag']."
					AND C.number = ".$this->defaults['idrepeat']."
					AND SL.idlang = ".$this->defaults['idlang'];
 
		$rs = $this->db->Execute($sql);
		
		if ($rs === false) 
		{
			return false;
		}	
				
		while (! $rs->EOF) 
		{
			$this->_setData(array ('idcontent' =>$rs->fields['idcontent'], 
									'idsidelang' =>$rs->fields['idsidelang'], 
									'container' =>$rs->fields['container'], 
									'number' =>$rs->fields['number'], 
									'idtype' =>$rs->fields['idtype'], 
									'typenumber' =>$rs->fields['typenumber'], 
									'value' =>$rs->fields['value'], 
									'online' =>$rs->fields['online'], 
									'version' =>$rs->fields['version'], 
									'author' =>$rs->fields['author'], 
									'created' =>$rs->fields['created'], 
									'lastmodified' =>$rs->fields['lastmodified']
								), 
							array('idside' =>$rs->fields['idside'], 
									'idcat' =>$rs->fields['idcat']
								)
							);
			
			$rs->MoveNext();
		}
		
		return $accept;
	}
	
	
	/**
	 * Map DB record(s) to array.
	 * @param arr arr_content (idcontent, idsidelang, container, number, idtype, typenumber, value, 
	 * 							online, version, author, created, lastmodified)
	 * @param arr arr_extra (idside, idcat)
	 */
	function _setData($arr_content, $arr_extra)
	{
		if (is_array($this->defaults['idtype']))
		{
			$this->data['content'][ $arr_content['idtype'] ] = $arr_content;
			$this->data['extra'][ $arr_content['idtype'] ] = $arr_extra;
		}
		else
		{
			$this->data['content'] = $arr_content;
			$this->data['extra'] = $arr_extra;
		}
	}
}


class SF_PAGE_ContentText extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'text'; parent::__construct(1); }
}

class SF_PAGE_ContentWysiwyg extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'wysiwyg'; parent::__construct(2); }
}

class SF_PAGE_ContentTextarea extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'textarea'; parent::__construct(3); }
}

class SF_PAGE_ContentImage extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'image'; parent::__construct(array('url'=> 4, 'desc'=> 5)); }
	function getValue() { return $this->getUrl(); }
	function getUrl() { return $this->data['content']['4']['value']; }
	function getDesc() { return $this->data['content']['5']['value']; }
	function getValueStyled($config = array(), $styler = 'html')
	{
		$_args['1'] = $this->getDesc();
		return parent::getValueStyled($config , $styler, $_args);
	}
}


class SF_PAGE_ContentLink extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'link'; parent::__construct(array('url'=> 6, 'name'=> 7, 'target'=> 8)); }
	function getValue() { return $this->getUrl(); }
	function getUrl() { return $this->data['content']['6']['value']; }
	function getName() { return $this->data['content']['7']['value']; }
	function getTarget() { return $this->data['content']['8']['value']; }
	function getValueStyled($config = array(), $styler = 'html')
	{
		$_args['1'] = $this->getName();
		$_args['2'] = $this->getTarget();
		return parent::getValueStyled($config , $styler, $_args);
	}
}

class SF_PAGE_ContentSourcecode extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'sourcecode'; parent::__construct(9); }
}

class SF_PAGE_ContentFile extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'file'; parent::__construct(array('url'=> 10, 'name'=> 11, 'target'=> 12)); }
	function getValue() { return $this->getUrl(); }
	function getUrl() { return $this->data['content']['10']['value']; }
	function getName() { return $this->data['content']['11']['value']; }
	function getTarget() { return $this->data['content']['12']['value']; }
	function getValueStyled($config = array(), $styler = 'html')
	{
		$_args['1'] = $this->getName();
		$_args['2'] = $this->getTarget();
		return parent::getValueStyled($config , $styler, $_args);
	}
}


class SF_PAGE_ContentWysiwyg2 extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'wysiwyg2'; parent::__construct(13); }
}

class SF_PAGE_ContentSelect extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'select'; parent::__construct(14); }
}

class SF_PAGE_ContentHidden extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'hidden'; parent::__construct(15); }
}

class SF_PAGE_ContentCheckbox extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'checkbox'; parent::__construct(16); }
}

class SF_PAGE_ContentRadio extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'radio'; parent::__construct(17); }
}

class SF_PAGE_ContentDate extends SF_PAGE_Content 
{
	function __construct() { $this->defaults['typename'] = 'date'; parent::__construct(18); }
}

?>
