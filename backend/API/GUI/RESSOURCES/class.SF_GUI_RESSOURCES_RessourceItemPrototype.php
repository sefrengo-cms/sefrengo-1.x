<?php
// File: $Id: class.SF_GUI_RESSOURCES_RessourceItemPrototype.php 28 2008-05-11 19:18:49Z mistral $
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


$GLOBALS['sf_factory']->requireClass('GUI/RESSOURCES', 'Abstract');

class SF_GUI_RESSOURCES_RessourceItemPrototype extends SF_GUI_RESSOURCES_Abstract{
	
	var $custom_config = array();
	
	function SF_GUI_RESSOURCES_FileManager() {
		
	}
			
	function getRessourceName() {
		return 'prototype';
	}
	
	function getRessourceChooserImage() {
		return 'ResourceBrowser/images/icons/rb_rsc_prototype.gif';
	}
	
	function getRessourceChooserDisplayedName() {
		return 'Prototype';	
	}
	
	function getStartPosition() {
		return 'rootCat';
	}

	function generatePathwayReleatedData($current_position) {

		//CatName, position, level
		if ($current_position == 'rootCat') {		
			$this->_addPathway('Prototype', 'rootCat', 0);
			$this->_addPathway('Kategorie 2', 'cat2', 1);
		} else if ($current_position == 'cat1') {
			$this->_addPathway('Prototype', 'rootCat', 0);
			$this->_addPathway('Kategorie 1', 'cat1', 1);
		} else if ($current_position == 'cat1.1') {
			$this->_addPathway('Prototype', 'rootCat', 0);
			$this->_addPathway('Kategorie 1', 'cat1', 1);
			$this->_addPathway('Kategorie 1.1', 'cat1.1', 2);
		} else if ($current_position == 'cat2') {
			$this->_addPathway('Prototype', 'rootCat', 0);
			$this->_addPathway('Kategorie 2', 'cat2', 1);
		}
		
		if($current_position != 'rootCat') {
			$this->_setPathwayOneUpIsActive(true);
		} else {
			$this->_setPathwayOneUpIsActive(false);
		}
		
		$this->_setDetailswitchIsActive(true);

	}
	
	function generateCatData($current_position) {
		
		switch ($current_position) {
			case 'rootCat':
				$this->_addCat('Kategorie 1', 'Kategorie1Wert', 
								array('description' => 'Beschreibungstext', 'author' => 'testauthor' ), 
								'cat1', 'default_image', false, false);
				$this->_addCat('Kategorie 2', 'Kategorie2Wert', 
								array('description' => 'Beschreibungstext 2', 'author' => 'testauthor' ), 
								'cat2', 'default_image', false, false);
				break;
			case 'cat1':
				$this->_addCat('Kategorie 1.1', 'Kategorie1.1Wert', 
								array('description' => 'Beschreibungstext 1.1', 'author' => 'testauthor' ), 
								'cat1.1', 'default_image', false, false);
			case 'cat2':
			case 'cat1.1':
			default:
				break;
		}
	}
	
	function generateItemData($current_position) {

		switch ($current_position) {
			case 'cat1':
				$this->_addItem('Item 1', 'Item1Wert', 
								array('title' => 'Item 1 Titel',
										'description' => 'Item 1 Desc',
										'thumbnail' => 'Item 1 Thumb',
										'author' => 'Item 1 Author' ,
										'fileinfo' => 'Item 1 Info'. $this->custom_config['custom_parameter']
										) , 
										'default_image', true, false);	
				$this->_addItem('Item 2', 'Item2Wert', 
								array('title' => 'Item 2 Titel',
										'description' => 'Item 2 Desc',
										'thumbnail' => 'Item 2 Thumb',
										'author' => 'Item 2Author' ,
										'fileinfo' => 'Item 2 Info'. $this->custom_config['custom_parameter']
										) , 
										'default_image', true, false);
				break;										
			case 'cat1.1':
				$this->_addItem('Item 1.1', 'Item1Wert', 
								array('title' => 'Item 1.1 Titel',
										'description' => 'Item 1.1 Desc',
										'thumbnail' => 'Item 1.1 Thumb',
										'author' => 'Item 1.1 Author' ,
										'fileinfo' => 'Item 1.1 Info'. $this->custom_config['custom_parameter']
										) , 
										'default_image', true, false);	
			case 'rootCat':
			default:
				break;
		}		
		
	}	
	
	/*
	 *  CUSTOM METHODS STARTS HERE
	 */
	
	function setCustomParameter($parm) {
		$this->custom_config['custom_parameter'] = $parm;
	}
	
	
}


?>