<?php
// File: $Id: class.SF_GUI_RESSOURCES_Abstract.php 43 2008-06-23 15:23:05Z bjoern $
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
// + Revision: $Revision: 43 $
// +----------------------------------------------------------------------+


/**
 * 
 */
class SF_GUI_RESSOURCES_Abstract extends SF_API_Object{
	var $ressource_name = string;
	var $_item_stack = array();
	var $_cat_stack = array();
	var $_various_formats_stack = array();
	var $_pathway_stack = array();
	var $_position = null;

	var $_pathway_one_up_is_active = string;
	var $_pathway_one_up_position = string;
	var $_detailswitch_is_active = string;	
	
	var $tpls = array( 'cat_list_start' => '<table width="100%" border="0" cellpadding="0" cellspacing="0">
											<tr id="rbInnerTableHead">
											<td width="10" class="rbInnerTablespacer"></td>
											<td colspan="2" width="250">{lang_catname}</td>
											<td width="300">{lang_catdesc}</td></tr>
											<tr><td colspan="4" class="rbInnerTableVerticalSpacer"></td></tr>',
	                   
						'cat_detail_start' => '',
											
						'cat_list_row' =>  '<tr class="rbInnerCatRow" onclick="window.parent.rb.sendValsToPicker(\'{js_name}\', \'{js_value}\', \'{is_chooseable}\', \'{is_editable_in_picker}\');window.parent.rb.performBrowserWindowURL(\'{exporturl}\');">
											<td width="10" class="rbInnerTableHorizontalSpacer"></td>
											<td width="16">{icon}</td>
											<td width="284" class="rbInnerCat">{name}</td> 
											<td class="rbInnerCatDesc">{description}</td></tr>',
											
					   'cat_detail_row' => '<div class="rbInnerDetailCat" onclick="window.parent.rb.sendValsToPicker(\'{js_name}\', \'{js_value}\', \'{is_chooseable}\', \'{is_editable_in_picker}\');window.parent.rb.performBrowserWindowURL(\'{exporturl}\');">
										    <div class="rbInnerDetailCatHead"><table border="0"><tr><td width="16">{icon}</td><td class="rbInnerDetailCatHeadTitel">{name}</td></tr></table></div> 
											<div class="rbInnerDetailCatBody">{detail_description1}{detail_description2}{detail_description3}{detail_description4}{detail_description5}</div></div>',
					   'cat_list_end' => '',
	                   'cat_detail_end' => '',
	                   'item_list_start' => '',
	                   'item_detail_start' => '',
					   'item_list_row' =>  '<tr class="rbInnerItemRow" onclick="window.parent.rb.sendValsToPicker(\'{js_name}\', \'{js_value}\', \'{is_chooseable}\', \'{is_editable_in_picker}\');window.parent.rb.performBrowserWindowURL(\'{exporturl}\');">
								           <td width="10" class="rbInnerTablespacer"></td>
								           <td width="16">{icon}</td>
								           <td width="284" class="rbInnerItem">{name}{title_after}</td> 
								           <td class="rbInnerItemDesc">{description}</td></tr>',
								           
					   'item_detail_row' => '<div class="rbInnerDetailItem" onclick="window.parent.rb.sendValsToPicker(\'{js_name}\', \'{js_value}\', \'{is_chooseable}\', \'{is_editable_in_picker}\');window.parent.rb.performBrowserWindowURL(\'{exporturl}\');">
										    <div class="rbInnerDetailItemHead"><table border="0"><tr><td width="16">{icon}</td><td class="rbInnerDetailItemHeadTitel">{name}{title_after}</td></tr></table></div> 
											<div class="rbInnerDetailItemBody">{detail_description1}{detail_description2}{detail_description3}{detail_description4}{detail_description5}</div></div>',
					   'item_list_end' => '</table>',
	                   'item_detail_end' => '<div style="clear:both;height:10px"></div>',
						);
	
	/**
	 * Konstructor
	 */
	function __construct()
	{
		
	}
	
	/*
	 * GET METHODS STARTS HERE
	 */
	function _getPathwayStack() 
	{
		return $this->_pathway_stack;
	}
	
	function _getPathwayOneUpIsActive() 
	{
		return $this->_pathway_one_up_is_active;
	}
	
	function _getPathwayOneUpPosition() 
	{
		return $this->_pathway_one_up_position;
	}
	
	function _getDetailswitchIsActive() 
	{
		return $this->_detailswitch_is_active;
	}
	
	function _getCurrentPosition() 
	{
		return $this->_position;
	}
	
	function _getRawTpl($what)
	{
		if (array_key_exists($what, $this->tpls)) 
		{
			return $this->tpls[$what];
		}
		
		return 'Template "'.$what.'" does not exist';
	}
	
	/*
	 * SET METHODS
	 */
	
	
	function _getCatStack() 
	{
		return $this->_cat_stack;
	}
	function _getItemStack() 
	{
		return $this->_item_stack;
	}
	function _getVariousFormatsStack() 
	{
		return $this->_various_formats_stack;
	}
	
	
	/**
	 * Add an item to the stack.
	 * 
	 * @param str $item_name The displayed item value
	 * @param str $item_value The returnvalue for the js callback function.
	 * @param arr $tpl_extra_parms
	 * @param str [$icon_type]
	 * @param bool [$item_is_chooseable] - Item is chosable in picker, defaultvalue is TUE
	 * @param bool [$item_is_editable_in_picker] - Item is editable in picker, defaultvalue is TURE
	 * @param bool [$format_position] Defaultvalue is FALSE
	 * @param str [$picked_name_prefix] will shown in picker as prefixstring before the item value. Defaultvalue is ''
	 */
	function _addItem($item_name, $item_value, $tpl_extra_parms, $icon_type = 'item_default', 
						$item_is_chooseable = TRUE, $item_is_editable_in_picker = TRUE, $format_position = FALSE,
						$picked_name_prefix ='') 
	{
							
		$item = array('name' => $item_name,
						'picked_name_prefix' => $picked_name_prefix,
						'value' => $item_value,
						'tpl_extra_parms' => $tpl_extra_parms,
						'icon_type' => $icon_type,
						'is_chooseable' => $item_is_chooseable,
						'is_editable_in_picker' => $item_is_editable_in_picker,
						'format_position' => $format_position
						);
		
		array_push($this->_item_stack, $item);
	}

	function _addVariousFormatItem($item_name, $item_value, $tpl_extra_parms, $icon_type = 'item_default', 
						$item_is_chooseable = true, $item_is_editable_in_picker = true, $picked_name_prefix ='') 
	{
							
		$item = array('name' => $item_name,
						'picked_name_prefix' => $picked_name_prefix,
						'value' => $item_value,
						'tpl_extra_parms' => $tpl_extra_parms,
						'icon_type' => $icon_type,
						'is_chooseable' => $item_is_chooseable,
						'is_editable_in_picker' => $item_is_editable_in_picker
						);
		
		array_push($this->_various_formats_stack, $item);
	}
	
	function _addCat($cat_name, $cat_value, $tpl_extra_parms = null, $cat_position, $icon_type = 'cat_default',
						$cat_is_chooseable = false, $cat_is_editable_in_picker = true, $picked_name_prefix ='') {
							
		$cat = array('name' => $cat_name,
						'picked_name_prefix' => $picked_name_prefix,
						'value' => $cat_value,
						'tpl_extra_parms' => $tpl_extra_parms,
						'position' => $cat_position,
						'icon_type' => $icon_type,
						'is_chooseable' => $cat_is_chooseable,
						'is_editable_in_picker' => $cat_is_editable_in_picker
						);
		
		array_push($this->_cat_stack, $cat);
	}
	
	
	function _addPathway($pathway_name, $cat_position, $level = 0) 
	{
							
		$pathway = array('name' => $pathway_name,
							'position' => $cat_position,
							'level' => $level
							);
		
		array_push($this->_pathway_stack, $pathway);
	}
	
	function _setPathwayOneUpIsActive($bool) 
	{
		$this->_pathway_one_up_is_active = $bool;
	}
	
	
	function _setPathwayOneUpPosition($position) 
	{
		$this->_pathway_one_up_position = $position;
	}
	
	function _setDetailswitchIsActive($bool)
	{
		$this->_detailswitch_is_active = $bool;
	}
	
	function exportConfig() 
	{
		$export = array('custom_config' => $this->custom_config,
							'position' => $this->_position,
							'class_name' => get_class($this)
							);
		return $export;
	}
	
	function exportConfigWithCustomPosition($pos, $format_pos = false) 
	{
		$e = $this->exportConfig();
		$e['position'] = $pos;
		$e['format_position'] = $format_pos;
		return $e;
	}
	
	function importConfig($in) 
	{
		//print_r($in);
		$this->custom_config = $in['custom_config'];
		$this->_position = $in['position'];
	}
	
	
	
	function _getPosition() 
	{
		return $this->_position;	
	}
	
	function _setPosition($pos) 
	{
		$this->_position = $pos;	
	}		
}


?>