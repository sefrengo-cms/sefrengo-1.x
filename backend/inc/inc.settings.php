<?PHP
// File: $Id: inc.settings.php 28 2008-05-11 19:18:49Z mistral $
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

if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

/******************************************************************************
 1. Benötigte Funktionen und Klassen includieren
******************************************************************************/

include('inc/class.values_ct_edit.php');

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

$perm->check(1, 'area_settings', 0);

echo "<!-- Anfang inc.settings.php -->\n";
echo "<div id=\"main\">\n";
echo "    <h5>".$cms_lang["area_$area"]."</h5>\n";
if ($errno) echo "<p class=\"errormsg\">".$cms_lang["err_$errno"]."</p>\n";

$output = new values_ct_edit(
				array(
	               'sqlgroup'  		=> 'cfg',
				   'client'		=> '0',
				   'lang' 		=> '0',
				   'perm_edit' 		=> 'area_settings_edit',
				   'tpl_file'  		=> 'settings.tpl',
				   'table_cellpadding'	=> $cellpadding,
				   'table_cellspacing'	=> $cellspacing,
				   'table_border' 	=> $border,
				   'area'  		=> 'settings',
				   'action' 		=> $action,
				   'view'      		=> 'use',
				   'prefix'    		=> '$cfg_cms'));
$output -> start();
?>