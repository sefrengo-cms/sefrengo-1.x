<?PHP
// File: $Id: inc.group_edit.php 52 2008-07-20 16:16:33Z bjoern $
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
// + Revision: $Revision: 52 $
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

include('inc/fnc.group.php');

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

$perm->check('area_group');
switch($action) {
	case 'save':
		$errno = group_save();
		if (!$errno && ! isset($_REQUEST['sf_apply']) ) {
			header ('HTTP/1.1 302 Moved Temporarily');
			header ('Location:'.$sess->urlRaw("main.php?area=group&order=$order&ascdesc=$ascdesc"));
			exit;
		}
		break;
}

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

// Templatedatei laden
$tpl->loadTemplatefile('group_edit.tpl');
$tmp['AREA'] = $cms_lang['area_group_edit'];
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];
if (!empty($errno)) {
	$tpl -> setCurrentBlock('ERROR');
	$tpl_error['ERRORMESSAGE'] = $cms_lang['err_'.$errno];
	$tpl->setVariable($tpl_error);
	$tpl->parseCurrentBlock();
}

// Tabellenformatierung
$tmp['CELLPADDING'] = $cellpadding;
$tmp['CELLSPACING'] = $cellspacing;
$tmp['BORDER'] = $border;
$tmp['LANG_NAME'] = $cms_lang['group_name'];
$tmp['LANG_DESCRIPTION'] = $cms_lang['group_description'];

// Formulareinstellungen
$tmp['FORM_URL'] = $sess->url('main.php');
if (!isset($name) && !empty($idgroup)) {
	$sql = "SELECT name, description FROM ".$cms_db['groups']." WHERE idgroup='$idgroup' LIMIT 0, 1";
	$db->query($sql);
	$db->next_record();
	$tmp['FORM_NAME'] = htmlspecialchars($db->f('name'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_OLDNAME'] = htmlspecialchars($db->f('name'), ENT_COMPAT, 'UTF-8');
	$tmp['FORM_DESCRIPTION'] = htmlspecialchars($db->f('description'), ENT_COMPAT, 'UTF-8');
} else {
	remove_magic_quotes_gpc($name);
	remove_magic_quotes_gpc($description);
	$tmp['FORM_NAME'] = htmlspecialchars($name, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_DESCRIPTION'] = htmlspecialchars($description, ENT_COMPAT, 'UTF-8');
	$tmp['FORM_OLDNAME'] = htmlspecialchars($oldname, ENT_COMPAT, 'UTF-8');
}
if (!is_array($group)) $group['0'] = $idgroup;

$tmp['BUTTON_SUBMIT_VALUE'] = $cms_lang['gen_save'];
$tmp['BUTTON_SUBMIT_TEXT'] = $cms_lang['gen_save_titletext'];

$tmp['BUTTON_APPLY_VALUE'] = $cms_lang['gen_apply'];
$tmp['BUTTON_APPLY_TEXT'] = $cms_lang['gen_apply_titletext'];

$tmp['BUTTON_CANCEL_URL'] = $sess->url("main.php?area=group&order=$order&ascdesc=$ascdesc");
$tmp['BUTTON_CANCEL_VALUE'] = $cms_lang['gen_cancel'];
$tmp['BUTTON_CANCEL_TEXT'] = $cms_lang['gen_cancel_titletext'];

$tmp['IDGROUP'] = $idgroup;
$tmp['ORDER'] = $order;
$tmp['ASCDESC'] = $ascdesc;
$tpl->setVariable($tmp);
unset($tmp);
?>