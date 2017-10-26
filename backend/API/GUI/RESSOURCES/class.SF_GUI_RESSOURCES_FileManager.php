<?php
// File: $Id: class.SF_GUI_RESSOURCES_FileManager.php 55 2008-07-28 15:30:47Z bjoern $
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
// + Autor: $Author: bjoern $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 55 $
// +----------------------------------------------------------------------+

$GLOBALS['sf_factory']->requireClass('GUI/RESSOURCES', 'Abstract');

/**
 * Generate data for filemanager content.
 */
class SF_GUI_RESSOURCES_FileManager extends SF_GUI_RESSOURCES_Abstract{
	
	var $custom_config = array(
							'folder_ids' => array(),
							'filetypes' => '',
							'subfolders' => true,
	
							);
	
	//disable format selectbox for files, used by folder jumpin with only one customdir
	var $disable_format_selectbox = FALSE;
	
	/**
	 * Konstructor
	 */
	function __construct()
	{
		global $cms_lang;
		$this->lang =& $cms_lang;
		$this->custom_config['folder_ids'] = '';
		$this->custom_config['filetypes'] = '';
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
		return 'files';
	}
	
	/**
	 * Returns ressource image path. The path is relative form the templatepath.
	 * 
	 * @return str
	 */
	function getRessourceChooserImage() 
	{
		return 'icons/rb_rsc_files.gif';
	}
	
	/**
	 * Returns the displayed sourcename
	 * 
	 * @return str
	 */
	function getRessourceChooserDisplayedName() 
	{
		return $this->lang['rb_res_file'];	
	}
	
	
	/**
	 * Returns the global startposition
	 * 
	 * @return int;
	 */
	function getStartPosition() 
	{
		//check custom config folder_ids position - if only one folder id is active
		//jump in folder
        if (is_array($ids = $this->_getCustomDirs()))
		{
		  if (count($ids) === 1)
		  {
		      return array_pop($ids);
		  }
		}
        
        return 0;
	}
	
	
	/**
	 * Returns an array with the pathway. 
	 * 
	 * @param int $current_position
	 * @return arr [name => str name, position => int iddirectory]
	 */
	function _getPathwayArray($current_position) 
	{
		global $db, $perm, $cms_db, $client, $cfg_client, $cfg_cms;
					
		$pos = $current_position;
		$arr_pway = array();
		
		//check custom config folder_ids position - if only one folder id is active
		//don't generate the pathway array
        if (is_array($folder_ids = $this->_getCustomDirs()))
		{
		  if (count($folder_ids) === 1 && $current_position == $folder_ids['0'])
		  {
		      $this->disable_format_selectbox = TRUE;
		      return $arr_pway;
		  }
		}
		
		$control = 0;
		 while($pos > 0 && ++$control < 50) 
		 {
			$sql = "SELECT *
			FROM ".$cms_db['directory']." C
			WHERE C.iddirectory = '".$pos."'
			LIMIT 1";
			$db->query($sql);
			$db->next_record();
			$item['name'] = $db->f('name');
			$item['position'] = $db->f('iddirectory');
			$pos = $db->f('parentid');
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
	function _getPathwayIddirctories($iddirectory) 
	{
		$pathway = $this->_getPathwayArray($iddirectory);
		$arr = array();
		
		foreach($pathway AS $v) 
		{
			array_push($arr, $v['position']);
		}
		
		return $arr;
	} 
	
	/**
	 * Returns folderids from $this->custom_config['folder_ids']. Doublets and empty values are stripped out, 
	 * also all values are casted as int. 
	 * 
	 * @return arr|bool array with catids or FLASE if no catids was found.
	 */
	function _getCustomDirs()
	{
		$ids = $this->custom_config['folder_ids'];
		
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
	
	
	
	
	/*
	 * SET METHODS START HERE
	 * ================================================================================
	 */
	 
	function setFolderIds($ids) 
	{
		if (! is_array($ids)) 
		{
			return false;	
		}
		
		$this->custom_config['folder_ids'] = $ids;
	}
	
	function setFiletypes($filetypes) 
	{
		if (! is_array($filetypes)) 
		{
			return false;	
		}
		
		$this->custom_config['filetypes'] = $filetypes;
	}
	
	function setWithSubfoders($bool) 
	{
		$this->custom_config['subfolders'] = $bool;
	}
	
	// [nothing is set] - relative url 
	// sefrengolink - returns values as cms://(idfile|idfilethumb)=\d
	function setReturnValueMode($str) 
	{
		$this->custom_config['return_value_mode'] = $str;
	}
	
	
	/*
	 * OTHER METHODS START HERE
	 * ================================================================================
	 */
	
	
	function generatePathwayReleatedData($current_position) 
	{
		global $db, $perm, $cms_db, $client, $cfg_client, $cfg_cms;
							
		$arr_rev = $this->_getPathwayArray($current_position);
		//check customcats
		$with_customdirs = FALSE;
		if (is_array($custom_dirs = $this->_getCustomDirs()))
		{
			$with_customdirs = TRUE;
			$arr_pwidirs = $this->_getPathwayIddirctories($current_position);
		}
		
		
		$level=0;
		$this->_addPathway($this->getRessourceChooserDisplayedName(), 0, $level);
		//if custom_config['folder_ids'] and custom_config['subfolders'] are activated this value
		//will match the first rootcat
		$first_catmatch = FALSE;
		foreach($arr_rev AS $v) 
		{
			
			//check customcats path
			if ($with_customdirs)
			{
				//match the first rootdir
				if (in_array($v['position'], $custom_dirs))
				{
					$first_catmatch = TRUE;
				}
				
				//this dirs are not defined to show in the userconfig. What should we do?
				if (! in_array($v['position'], $custom_dirs))
				{
					//subdirs are allowed
					if ($this->custom_config['subfolders'] == TRUE)
					{
						//subdirs are allowed but at this time there wasn't a matched rootdir, skip this
						if (! $first_catmatch)
						{
							continue;
						}
					}
					//subdirs are disallowed, skip all not defined pathways
					else
					{
						continue;
					}
				}
			}
			
			//name, position, level
			$this->_addPathway($v['name'], $v['position'], ++$level);
		}
		
		//check customdirs
		$customdir_is_root = FALSE;
		if (is_array($folderids = $this->_getCustomDirs()))
		{
			if (in_array($current_position, $folderids))
			{
				$customdir_is_root = TRUE;
			}
		}
		
		if($current_position != $this->getStartPosition()) 
		{
			$this->_setPathwayOneUpIsActive(TRUE);
			$this->_setPathwayOneUpPosition( $arr_rev[ (count($arr_rev) -2) ]['position'] );
			if ($customdir_is_root && $level < 2) 
            {
			   $this->_setPathwayOneUpPosition(0);
			}
		}  
		else
		{
			$this->_setPathwayOneUpIsActive(FALSE);
		}
		
		$this->_setDetailswitchIsActive(true);
	}
	
	function generateCatData($current_position) 
	{
			global $db, $perm, $cms_db, $client, $cfg_client, $cfg_cms;

			//limit by folderids?
			$sql_folderids = '';
			$sql_parents = '';
			if (is_array($folderids = $this->_getCustomDirs()))
			{
				//defaultsql, the quantitie of elements 
				$sql_folderids = 'AND C.iddirectory IN('.implode(',', $folderids).')';
				
				//current position is not root
				if ($current_position != 0)
				{
					//exception: if only one customfolder is possible and the customfolder is the 
					//current iddirectory --> skip. This is because when only one customfolder will shown the
		            //root- iddirectory is not the parent of the cat  but the cat is the parent
		            if (count($folderids) === 1 && $folderids['0'] == $current_position)
		            {
		                $sql_parents = "AND C.parentid = '$current_position'";
		                if ($this->custom_config['subfolders'] == TRUE)
						{
		                    $sql_folderids = '';
		                }
		            }
					
					
					//check if current position is in pathway array
					$arrpw = $this->_getPathwayIddirctories($current_position);
					if (in_array($current_position, $arrpw))
					{
						$sql_parents = "AND C.parentid = '$current_position'";
						
						if ($this->custom_config['subfolders'] == TRUE)
						{
							//dont limit the catids in a accurent_position != 0 if subcats are allowed
							$sql_folderids = '';
						}
					} 
				}
			}
			//normal catparents
			else
			{
				$sql_parents = "AND C.parentid = '$current_position'";
			}
			
			//--------------------------------------------------------------------
			
			//MAKE CATS
			$sql = "SELECT C.*, D.name AS firstname, D.surname, D.username
				FROM ".$cms_db['directory']." C, ".$cms_db['users']." D
				WHERE C.idclient= '$client'
					AND C.author = D.user_id
					AND C.dirname NOT LIKE('cms/%')
					$sql_folderids
					$sql_parents
				ORDER BY C.dirname";
			$db->query($sql);
		
			while( $db->next_record() )
			{
				if(! $perm->have_perm( 1, 'folder', $db->f('iddirectory') ) )
				{
					continue;
				}
				
				$author = $db->f('username') .
							($db->f('firstname') != '' || $db->f('surname') != '' 
								? ' ('.$db->f('firstname'). ' ' . $db->f('surname').')': '');
				//name, value, tpl_extra_parms, position, image, is_chooseable, is_editable_in_picker
				$this->_addCat($db->f('name'), $db->f('dirname'), 
									array('description' => $db->f('description'),
											'detail_description1' => '<strong>'.$this->lang['rb_description'].': </strong>'.$db->f('description').'<br />',
											'detail_description2' => '<strong>'.$this->lang['rb_author'].': </strong>'.$author.'<br />',
											'detail_description3' => '',
											'detail_description4' => '',
											'detail_description5' => '' ), 
									$db->f('iddirectory'), 'image', false, false);
			}
	}
	
	function generateItemData($current_position) 
	{
		$this->_generateItems('items', $current_position);
	}
	
	function generateItemVariousFormatsData($current_position, $item_position) 
	{
		$this->_generateItems('format', $current_position, $item_position);
	}
	 
	function _generateItems($type, $current_position, $item_position =null) 
	{
		global $db, $perm, $cms_db, $client, $cfg_client, $cfg_cms;
		//echo "$type, $current_position, $item_position <br>";
		//make filetype filter
		if ( is_array($this->custom_config['filetypes']) ) {
			if ( count($this->custom_config['filetypes']) >0) {
				$sql_filetypes = ' AND B.filetype IN('."'". implode("','", $this->custom_config['filetypes']) ."') ";
			}
		} else {
			$sql_filetypes = '';
		}
		
		//get only versions
		if ($type == 'format') {
			$sql_format = ' AND A.idupl ='.$item_position;
		} else {
			$sql_format = '';
		}
		
		//MAKE ITEMS
		$thumb_types = array('gif', 'jpg','jpeg','png');
		$thumb_stencil = '<img src="%s" width="%s" height="%s" style="float:left;padding:0px 3px 3px 0px" alt="" />';
		$date_format = $cfg_cms['FormatDate'] . ' ' . $cfg_cms['FormatTime'];
		$sql = "SELECT
				A.*, A.created AS fcreated, A.lastmodified AS flastmodified, B.filetype, C.dirname, C.iddirectory, D.*
			FROM
				".$cms_db['upl']." A
				LEFT JOIN ".$cms_db['filetype']." B USING(idfiletype)
				LEFT JOIN ".$cms_db['directory']." C ON A.iddirectory=C.iddirectory,
				".$cms_db['users']." D
			WHERE
				A.idclient= '$client'
				AND A.author = D.user_id
				AND C.dirname NOT LIKE('cms/%')
				$sql_filetypes
				$sql_format
				AND C.iddirectory = '".$current_position."'
			ORDER BY
				A.filename, A.titel";

		$db -> query($sql);
	
		while ( $db->next_record() )
		{
			//item has more then one choseable format - standard: flase
			$item_position = false;
			if (! $perm->have_perm( 17, 'file', $db->f('idupl'), $db->f('iddirectory') ) ) {
				continue;
			}
			
			$pic_filename = $db->f('filename');
			$pic_filetype = $db->f('filetype');
			$name_length = strlen($pic_filename);
			$extension_length = strlen($db->f('filetype'));
			$t_name = substr ($pic_filename, 0, ($name_length - $extension_length - 1) );
			$t_name .= $cfg_client['thumbext'].'.'. $pic_filetype;
			
			//create thumbnaill if possible
			if (in_array($pic_filetype, $thumb_types)) {
				
				if (! file_exists($cfg_client['upl_path'].$db->f('dirname').$t_name) ) {
					//echo $cfg_client['upl_path'].$db->f('dirname').$t_name.'<br>';
					if ($db->f('pictwidth') >0 && $db->f('pictheight')>0 ) { 
						$im_gif_size = $this->_calculateThumbSize($db->f('pictwidth'), $db->f('pictheight'), 
											$cfg_client['thumb_size'], $cfg_client['thumb_size']);
						$thumb = sprintf($thumb_stencil,  $cfg_client['upl_htmlpath'].$db->f('dirname').$db->f('filename'), 
											$im_gif_size['width'], $im_gif_size['height'] );
					} else {
						$thumb = '';	
					}
				} else {
					// item has more then one format
					$item_position = $db->f('idupl');
					// make thumb
					$thumb = sprintf($thumb_stencil,  $cfg_client['upl_htmlpath'].$db->f('dirname').$t_name, 
										$db->f('pictthumbwidth'), $db->f('pictthumbheight'));
				} 
				
			} else {
				$thumb = '';
			}
			
			$author = $db->f('username') .
						($db->f('name') != '' || $db->f('surname') != '' 
							? ' ('.$db->f('name'). ' ' . $db->f('surname').')': '');
		
			// pictwidth   	  pictheight
			$picsize = ($db->f('pictwidth') > 0) ? $db->f('pictwidth') .'x'.$db->f('pictheight') .' px': '';
			$filesize = ( $db->f('filesize') > 1024) ? sprintf( "%01.2f", $db->f('filesize')/1024) . '&nbsp;kByte': $db->f('filesize') . '&nbsp;Byte';
			$picthumbsize = $db->f('pictthumbwidth') .'x'.$db->f('pictthumbheight') .' px';
			$fileinfo = empty($picsize) ? $filesize: $filesize .' / '. $picsize;
			
			//name, value, tpl_extra_parms, image, is_chooseable, is_editable_in_picker
			if($type == 'format') 
			{
				//pathway up ist active
				$this->_setPathwayOneUpIsActive(TRUE);
				
				$picker_val = ($this->custom_config['return_value_mode'] == 'sefrengolink') 
								? 'cms://idfile='. $db->f('idupl'):
								$cfg_client['upl_htmlpath'].$db->f('dirname').$db->f('filename');
				$this->_addVariousFormatItem($db->f('filename'), $picker_val, 
								array('description' => $this->lang['rb_res_file_picformat'] .': '.$picsize,
										'detail_description1' => $thumb,
										'detail_description2' => '<strong>'.$this->lang['rb_res_file_filewidth'].': </strong>'.$db->f('pictwidth').' px<br />',
										'detail_description3' => '<strong>'.$this->lang['rb_res_file_fileheight'].': </strong>'.$db->f('pictheight').' px<br />',
										'detail_description4' => '',
										'detail_description5' => ''
										), 
								$db->f('filetype'), true, false, $db->f('dirname'));				
				
				$picker_val = ($this->custom_config['return_value_mode'] == 'sefrengolink') 
								? 'cms://idfilethumb='. $db->f('idupl'):
								$cfg_client['upl_htmlpath'].$db->f('dirname').$t_name;
				$this->_addVariousFormatItem($t_name, $picker_val, 
								array('description' => $this->lang['rb_res_file_picformat'] .': '.$picthumbsize,
										'detail_description1' => $thumb,
										'detail_description2' => '<strong>'.$this->lang['rb_res_file_filewidth'].': </strong>'.$db->f('pictthumbwidth').' px<br />',
										'detail_description3' => '<strong>'.$this->lang['rb_res_file_fileheight'].': </strong>'.$db->f('pictthumbheight').' px<br />',
										'detail_description4' => '',
										'detail_description5' => ''
										), 
								$db->f('filetype'), true, false, $db->f('dirname'));
												
			} else {
				if ($item_position) {
					$title_after = '<span style="font-size:9px;color:#5A7BAD;" title="'.$this->lang['rb_res_file_mutiple_picsizes'].'"> (2)</span>';
				} else {
					$title_after = '';
				}
				$picker_val = ($this->custom_config['return_value_mode'] == 'sefrengolink') 
								? 'cms://idfile='. $db->f('idupl'):
								$cfg_client['upl_htmlpath'].$db->f('dirname').$db->f('filename');
				$this->_addItem($db->f('filename'), $picker_val, 
									array( 'title_after' => $title_after,
											'description' => $db->f('titel'),
											'detail_description1' => $thumb,
											'detail_description2' => '<strong>'.$this->lang['rb_title'].': </strong>'.$db->f('titel').'<br />',
											'detail_description3' => '<strong>'.$this->lang['rb_description'].': </strong>'.$db->f('description').'<br />',
											'detail_description4' => '<strong>'.$this->lang['rb_res_file_infos'].': </strong>'.$fileinfo.'<br />',
											'detail_description5' => '<strong>'.$this->lang['rb_author'].': </strong>'.$author.'<br />'
											), 
									$db->f('filetype'), true, false, $item_position, $db->f('dirname'));
			}
			
		}
	}
	 
	function _calculateThumbSize($original_width, $original_height, 
						$new_width = '', $new_height = '', $aspectratio = true) 
	{
		
		if( empty($new_width) && empty($new_height)) return false;
		if( empty($original_width) ||  empty($original_height) ) return false;
		
		if ($original_width < $new_width && $original_height < $new_height) 
		{
			$out['width'] = $original_width;
			$out['height'] = $original_height;
		}
		//calculate without aspectratio
		else if($aspectratio != 'true')
		{
			$out['width'] = ( empty($new_width) ) ? $original_width: $new_width;
			$out['height'] = ( empty($new_height) ) ? $original_height: $new_height;
		}
		//calculate with aspectratio
		else
		{
			$calculate_height_by_width = round( ($new_width / $original_width) * $original_height, 0);
			$calculate_width_by_height = round( ($new_height / $original_height) * $original_width, 0);
			//both values, new height and width are set. We must figure out if we resize by
			//height or by width
			if(! empty($new_width) && ! empty($new_height))
			{
				//resize by height
				if($calculate_height_by_width > $new_height)
				{
					$out['width'] = $calculate_width_by_height;
					$out['height'] = $new_height;
				}
				//resize by width
				else
				{
					$out['width'] = $new_width;
					$out['height'] = $calculate_height_by_width;
				}
			}
			//by width
			else if( empty($new_height) )
			{
				$out['width'] = $new_width;
				$out['height'] = $calculate_height_by_width;
			}
			//by height
			else if( empty($new_width) )
			{
				$out['width'] = $calculate_width_by_height;
				$out['height'] = $new_height;
			}
			//how did you create this nonsens?
			else
			{
				return false;
			}
		}

		return $out;
	}

}


?>