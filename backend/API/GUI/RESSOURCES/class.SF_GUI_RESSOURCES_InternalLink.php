<?php
// File: $Id: class.SF_GUI_RESSOURCES_InternalLink.php 51 2008-07-16 18:40:17Z bjoern $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name:  $
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 - 2008 sefrengo.org <info@sefrengo.org>           |
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
// + Autor: $Author: bjoern $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 51 $
// +----------------------------------------------------------------------+

$GLOBALS['sf_factory']->requireClass('GUI/RESSOURCES', 'Abstract');
//require_once ($_api_path.'GUI/RESSOURCES/class.SF_GUI_RESSOURCES_Abstract.php');


/**
 * Generate data for internal cms links.
 */
class SF_GUI_RESSOURCES_InternalLink extends SF_GUI_RESSOURCES_Abstract
{
	
	var $custom_config = array(
								'cat_ids' => array(),
								'cat_with_subcats' => FALSE,
								'page_show_startpage' => TRUE,
								'page_show_pages' => TRUE,
								'page_is_chosable' => TRUE,
								'cat_is_chosable' => TRUE
							);
	
	/**
	 * Konstruktor
	 */
	function SF_GUI_RESSOURCES_InternalLink() 
	{
		global $cms_lang;
		$this->lang =& $cms_lang;
	}
	
	/*
	 * GET METHODS START HERE
	 * ================================================================================
	 */
	
	/**
	 * Returns the internal source name.
	 * 
	 * @return str
	 */	
	function getRessourceName() 
	{
		return 'internalLink';
	}
	
	/**
	 * Returns ressource image path. The path is relative form the templatedit.
	 * 
	 * @return str
	 */
	function getRessourceChooserImage() 
	{
		return 'icons/rb_rsc_links.gif';
	}
	
	/**
	 * Returns the displayed sourcename
	 * 
	 * @return str
	 */
	function getRessourceChooserDisplayedName() 
	{
		return $this->lang['rb_res_internal_links'];	
	}
	
	/**
	 * Returns the global startposition
	 * 
	 * @return int;
	 */
	function getStartPosition() 
	{
		//check customcats position - if only one customcat is active
		//jump in folder
        if (is_array($catids = $this->_getCustomCats()))
		{
		  if (count($catids) === 1)
		  {
		      return array_pop($catids);
		  }
		}
        
        return 0;
	}
	
	/**
	 * Returns catids from $this->custom_config['cat_ids']. Doublets and empty values are stripped out, 
	 * also all values are casted as int. 
	 * 
	 * @return arr|bool array with catids or FLASE if no catids was found.
	 */
	function _getCustomCats()
	{
		$ids = $this->custom_config['cat_ids'];
		
		//check array length
		if (count($ids) < 1)
		{
			return FALSE;
		}
		
		//cast for int
		foreach ($ids AS $k=>$v)
		{
			$ids[$k] = (int) $v;
		}
		
		//filter doublets and empty values
		$ids = array_filter(array_unique($ids));
		
		//ceck length again
		if (count($ids) < 1)
		{
			return FALSE;
		}
		
		return $ids;
	}
	
	/**
	 * Returns an array with the pathway. 
	 * 
	 * @param int $idcat
	 * @return arr [name => str catname, position => int idcat]
	 */
	function _getPathwayArray($idcat) 
	{
		global $db, $perm, $cms_db, $client, $lang, $cfg_client, $cfg_cms;
		
		$pos = $idcat;
		$arr_pway = array();
		
		//check customcats position - if only one customcat is active
		//don't generate the pathway array
        if (is_array($catids = $this->_getCustomCats()))
		{
		  if (count($catids) === 1 && $idcat == $catids['0'])
		  {
		      return $arr_pway;
		  }
		}
		
		$control = 0;
		 while($pos > 0 && ++$control < 50) 
		 {
			$sql = "SELECT *
			FROM ".$cms_db['cat_lang']." C
			LEFT JOIN ".$cms_db['cat']." D USING(idcat)
			WHERE C.idcat = '".$pos."'
			AND C.idlang = '".$lang."'
			LIMIT 1";
			$db->query($sql);
			$db->next_record();
			$item['name'] = $db->f('name');
			$item['position'] = $db->f('idcat');
			$pos = $db->f('parent');
			array_push($arr_pway, $item);		
		}
		
		$arr_rev = array_reverse($arr_pway);
		
		return $arr_rev;
	}
	
	/**
	 * Returns pathway idcats.
	 * 
	 * @param int $idcat
	 * @return arr
	 */
	function _getPathwayIdcats($idcat) 
	{
		$pathway = $this->_getPathwayArray($idcat);
		$arr = array();
		foreach($pathway AS $v) 
		{
			array_push($arr, $v['position']);
		}
		return $arr;
	} 
	
	/**
	 * Returns current pathway as named string like cat1/cat2/cat3/
	 * 
	 * @param int $idcat
	 * @return str
	 */
	function _getPathwayAsString($idcat) 
	{
		$pathway = $this->_getPathwayArray($idcat);
		$title_pre = '';
		foreach($pathway AS $v) 
		{
			$title_pre .= $v['name'] .'/';
		}
		return $title_pre;
	}
	
	
	/*
	 * SET METHODS START HERE
	 * ================================================================================
	 */
	
	/**
	 * Show only pages in this idcats.
	 * 
	 * @param arr $ids 
	 * @return bool
	 */
	function setCatIds($ids) 
	{
		//check array input
		if (! is_array($ids)) 
		{
			return false;	
		}
		
		//check array length
		if (count($ids) < 1)
		{
			return FALSE;
		}
		
		//cast for int
		foreach ($ids AS $k=>$v)
		{
			$ids[$k] = (int) $v;
		}
		
		//filter doublets and empty values
		$ids = array_filter(array_unique($ids));
		
		//ceck length again
		if (count($ids) < 1)
		{
			return FALSE;
		}
		
		$this->custom_config['cat_ids'] = $ids;
		
		return TRUE;
	}
	
	/**
	 * Show the subfolders of selected cats. This setting takes only in
	 * effect if the method setCatIds() is called too. Defaultvalue
	 * is FALSE.
	 * 
	 * @param bool $bool 
	 * @return bool
	 */
	function setWithSubcats($bool)
	{
		if (! is_bool($bool)) 
		{
			return false;	
		}
		
		$this->custom_config['cat_with_subcats'] = $bool;
		
		return true;
	}
	
	/**
	 * Show startpages in the cats. Defalutvalue is TRUE.
	 * 
	 * @param bool $bool 
	 * @return bool
	 */
	function setShowStartpages($bool)
	{
		if (! is_bool($bool)) 
		{
			return false;	
		}
		
		$this->custom_config['page_show_startpage'] = $bool;
		
		return true;
	}
	
	/**
	 * Show Pages. Defalutvalue is TRUE.
	 * 
	 * @param bool $bool 
	 * @return bool
	 */
	function setShowPages($bool)
	{
		if (! is_bool($bool)) 
		{
			return false;	
		}
		
		$this->custom_config['page_show_pages'] = $bool;
		
		return true;
	}
	
	/**
	 * Pages are chosable as returnvalue by the user. Defalutvalue is TRUE.
	 * 
	 * @param bool $bool 
	 * @return bool
	 */
	function setPagesAreChosable($bool)
	{
		if (! is_bool($bool)) 
		{
			return false;	
		}
		
		$this->custom_config['page_is_chosable'] = $bool;
		
		return true;
	}
	
	/**
	 * Cats are chosable as returnvalue by the user. Defalutvalue is TRUE.
	 * 
	 * @param bool $bool 
	 * @return bool
	 */
	function setCatsAreChosable($bool)
	{
		if (! is_bool($bool)) 
		{
			return false;	
		}
		
		$this->custom_config['cat_is_chosable'] = $bool;
		
		return true;
	}	
	
	
	/*
	 * OTHER METHODS START HERE
	 * ================================================================================
	 */
	
	
	/**
	 * Generates data for the pathway selectbox
	 * 
	 * @param $current_position Parent Idcat or 0 for rootcat.
	 */
	function generatePathwayReleatedData($current_position) 
	{
		
		$arr_rev = $this->_getPathwayArray($current_position);
		
		//check customcats
		$with_customcats = FALSE;
		if (is_array($catids = $this->_getCustomCats()))
		{
			$with_customcats = TRUE;
			$arr_pwidcats = $this->_getPathwayIdcats($current_position);
		}
		
		$level=0;
		$this->_addPathway($this->getRessourceChooserDisplayedName(), 0, $level);
		//if custom_config['cat_ids'] and custom_config['cat_with_subcats'] are activated this value
		//will match the first rootcat
		$first_catmatch = FALSE;
		foreach($arr_rev AS $v) 
		{
			//check customcats path
			if ($with_customcats)
			{
				//match the first rootcat
				if (in_array($v['position'], $catids))
				{
					$first_catmatch = TRUE;
				}
				
				//this cats are not defined to show in the userconfig. What should we do?
				if (! in_array($v['position'], $catids))
				{
					//subcats are allowed
					if ($this->custom_config['cat_with_subcats'] == TRUE)
					{
						//subcats are allowed but at this time there wasn't a matched rootcat, skip this
						if (! $first_catmatch)
						{
							continue;
						}
					}
					//subcats are disallowed, skip all not defined pathways
					else
					{
						continue;
					}
				}
			}
			
			//name, position, level
			$this->_addPathway($v['name'], $v['position'], ++$level);
		}
		
		//check customcats
		$customcat_is_root = FALSE;
		if (is_array($catids = $this->_getCustomCats()))
		{
			if (in_array($current_position, $catids))
			{
				$customcat_is_root = TRUE;
			}
		}
		
		if($current_position != $this->getStartPosition()) 
		{
			$this->_setPathwayOneUpIsActive(TRUE);
			$this->_setPathwayOneUpPosition( $arr_rev[ (count($arr_rev) -2) ]['position'] );
			if ($customcat_is_root && $level < 2) 
            {
			   $this->_setPathwayOneUpPosition(0);
			}
		} 
		else 
		{
			$this->_setPathwayOneUpIsActive(FALSE);
		}
		
		$this->_setDetailswitchIsActive(TRUE);

	}

	
	/**
	 * Generates cat data items for the listview
	 * 
	 * @param int $current_position current parent iddirectory from SQL table cms_directory.
	 */
	function generateCatData($current_position) 
	{
		global $db, $perm, $cms_db, $client, $lang, $cfg_client, $cfg_cms;
		
		//get pathway for picker_name
		$title_pre = $this-> _getPathwayAsString($current_position);
		
		//limit by catids?
		$sql_catids = '';
		$sql_parents = '';
		if (is_array($catids = $this->_getCustomCats()))
		{
			//defaultsql, the quantitie of elements 
    		$sql_catids = 'AND B.idcat IN('.implode(',', $catids).')';
            
            //exception: if only one customcat is possible and the customcat is the 
			//current idcat --> skip. This is because when only one customcat will shown the
            //rootidcat is not the parent of the cat  but the cat is the parent
            if (count($catids) === 1 && $catids['0'] == $current_position)
            {
                $sql_parents = "AND B.parent = '$current_position'";
                if ($this->custom_config['cat_with_subcats'] == TRUE)
				{
                    $sql_catids = '';
                }
            }
			
			//current position is not root
			if ($current_position != $this->getStartPosition() )
			{
				//check if current position is in pathway array
				$arrpw = $this->_getPathwayIdcats($current_position);
				if (in_array($current_position, $arrpw))
				{
					$sql_parents = "AND B.parent = '$current_position'";
					
					if ($this->custom_config['cat_with_subcats'] == TRUE)
					{
						//dont limit the catids in a accurent_position != 0 if subcats are allowed
						$sql_catids = '';
					}
				} 
			}
		}
		//normal catparents
		else
		{
			$sql_parents = "AND B.parent = '$current_position'";
		}
		
		$sql = "SELECT 
					A.*, B.parent, D.name AS firstname, D.surname, D.username
				FROM 
					".$cms_db['cat_lang']."  A 
					LEFT JOIN ".$cms_db['cat']."  B USING(idcat) ,
					".$cms_db['users']." D
				WHERE 
					A.idlang = '$lang'
					AND A.author = D.user_id
					$sql_catids
					$sql_parents
				ORDER BY B.sortindex";
		$db->query($sql);
		
		while ($db->next_record()) 
		{
			if (! $perm->have_perm(1, 'cat', $db->f('idcat')) ) 
			{
					continue;
			}
			
			$author = $db->f('username') .
						($db->f('firstname') != '' || $db->f('surname') != '' 
							? ' ('.$db->f('firstname'). ' ' . $db->f('surname').')': '');
			$this->_addCat($db->f('name'), 'cms://idcat='.$db->f('idcat'), 
								array('description' => $db->f('description'), 
										'detail_description1' => '<strong>'.$this->lang['rb_description'].': </strong>'.$db->f('description').'<br />',
										'detail_description2' => '<strong>'.$this->lang['rb_author'].': </strong>'.$author.'<br />',
										'detail_description3' => '',
										'detail_description4' => '',
										'detail_description5' => ''), 
								$db->f('idcat'), 'cat_link', $this->custom_config['cat_is_chosable'], FALSE, $title_pre);
		}
	}
	
	/**
	 * Generates page data items for the listview.
	 * 
	 * @param int $current_position current parent iddirectory from SQL table cms_directory.
	 */
	function generateItemData($current_position) 
	{
		global $db, $perm, $cms_db, $client, $lang, $cfg_client, $cfg_cms;
		
		//check if pageinfos should be generate
		if ($this->custom_config['page_show_pages'] === FALSE)
		{
			return;
		}
		
		//get pathway for picker_name
		$title_pre = $this-> _getPathwayAsString($current_position);
		
		//with startpage?
		$sql_startpage = '';
		if ($this->custom_config['page_show_startpage'] === FALSE)
		{
			$sql_startpage = 'AND D.is_start = 0';
		}
		
		$sql = "SELECT 
					D.idcatside, D.idcat, D.sortindex, D.is_start, F.title, F.summary, 
					U.name AS firstname, U.surname, U.username
				FROM 
					".$cms_db['cat_side']."  D 
					LEFT JOIN ".$cms_db['side']."  E USING(idside) 
					LEFT JOIN ".$cms_db['side_lang']."  F USING(idside)
					LEFT JOIN ".$cms_db['users']." U ON (F.author = U.user_id)
				WHERE 
					E.idclient='$client'
					AND D.idcat = '".$current_position."'
					AND F.idlang='$lang'
					$sql_startpage
				ORDER BY D.sortindex";
				
		$db->query($sql);
		
		while ($db->next_record()) 
		{
			if (! $perm->have_perm(17, 'side', $db->f('idcatside'), $db->f('idcat')) ) 
			{
					continue;
			}
			
			
			$author = $db->f('username') .
						($db->f('firstname') != '' || $db->f('surname') != '' 
							? ' ('.$db->f('firstname'). ' ' . $db->f('surname').')': '');
			$image = $db->f('is_start') == 1 ? 'item_link_startpage':'item_link';
			$this->_addItem($db->f('title'),  'cms://idcatside='.$db->f('idcatside'), 
						array('title' =>  $db->f('title'),
								'description' => $db->f('summary'),
								'detail_description1' => '<strong>'.$this->lang['rb_description'].': </strong>'.$db->f('summary').'<br />',
								'detail_description2' => '<strong>'.$this->lang['rb_author'].': </strong>'.$author.'<br />',
								'detail_description3' => '',
								'detail_description4' => '',
								'detail_description5' => ''								
								) , 
								
								$image, $this->custom_config['page_is_chosable'], FALSE, FALSE, $title_pre);
		}		
	}
}


?>