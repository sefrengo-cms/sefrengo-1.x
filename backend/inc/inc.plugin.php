<?PHP
// File: $Id: inc.plugin.php 28 2008-05-11 19:18:49Z mistral $
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


/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

//$plugin_var['dirname']   Verzeichnis
//           ['basename']  Dateiname
//           ['extension'] Dateiendung

$plugin_var = pathinfo($cfg_cms['cms_path'] .'plugins/'. $cms_plugin);
$plugin_var['base_url'] = $sess -> url('main.php?area=plugin&cms_plugin='. $cms_plugin);
$plugin_var['base_form_head'] = '<form action ="main.php" method ="post">';
$plugin_var['base_form_vars'] = '<input type="hidden" name="area" value="'.$area.'">
<input type="hidden" name="cms_plugin" value="'.$cms_plugin.'">
<input type="hidden" name="'. $sess-> name. '" value="'.$sess -> id.'">'."\n";

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

if (!$is_frame_plugin) {
	include($cfg_cms['cms_path'] . 'inc/inc.header.php');
}

if(is_file($cfg_cms['cms_path'] .'plugins/'. $cms_plugin)) {
	include $cfg_cms['cms_path'] .'plugins/'. $cms_plugin;
} else {
	header ('Location: '.$sess->urlRaw('main.php?area=con&lang='.$lang));
	exit;
} 


/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/
?>
