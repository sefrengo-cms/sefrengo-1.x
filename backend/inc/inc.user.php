<?PHP
// File: $Id: inc.user.php 64 2008-11-19 19:00:33Z bjoern $
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
// + Revision: $Revision: 64 $
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

include('inc/fnc.user.php');

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/

$perm->check('area_user');
switch($action) {
	case 'activate':
		user_set_active($iduser, '1');
		break;
	case 'deactivate':
		user_set_active($iduser, '0');
		break;
	case 'delete':
		user_delete();
		break;
}

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

include('inc/inc.header.php');

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

// Allgemeine Einstellungen initialisieren
if (empty($ascdesc)) {
	$order = 'username';
	$ascdesc = 'ASC';
}


$idgroup = (int) $_REQUEST['idgroup'];
if (! $perm->is_admin() && $idgroup == 1) {
		$idgroup = 0;
}
$searchterm = $_REQUEST['searchterm'];
$page = (int) $_REQUEST['page'];
$changepage1 = (int) $_REQUEST['changepage1'];
$changepage2 = (int) $_REQUEST['changepage2'];
if ($changepage1 > 0 && $changepage1 != $page) {
	$page = $changepage1;
} else if ($changepage2 > 0 && $changepage2 != $page) {
	$page = $changepage2;
} else if ($page < 1) {
	$page = 1;
}

$base_url = $sess->url('main.php?area=user&idgroup='.$idgroup.'&order=%s&ascdesc=%s');


// Templatedatei laden
$tpl->loadTemplatefile('user.tpl');
$tmp['AREA'] = $cms_lang['area_user'];
$tmp['FOOTER_LICENSE'] = $cms_lang['login_licence'];
if (!empty($errno)) {
	$tpl -> setCurrentBlock('ERROR');
	$tpl_error['ERRORMESSAGE'] = $cms_lang['err_'.$errno];
	$tpl->setVariable($tpl_error);
	$tpl->parseCurrentBlock();
}

// Formulareinstellungen
$tmp['FORM_URL'] = $sess->url('main.php');
$tmp['ORDER'] = $order;
$tmp['ASCDESC'] = $ascdesc;
$tmp['PAGE'] = $page;
$tmp['SESS_NAME'] = $sess->name;
$tmp['SESS_ID'] = $sess->id;

// Tabellenüberschrift
$tmp['LANG_LOGINNAME'] = '<a href ="'.sprintf($base_url, 'username', ($order == 'username' && ($ascdesc == 'ASC' || $ascdesc == '')) ? 'DESC' : 'ASC').'&amp;searchterm='.urldecode($searchterm).'&amp;page='.$page.'">'.$cms_lang['user_loginname'].'</a>';
$tmp['LANG_NAME'] = '<a href ="'.sprintf($base_url, 'name', ($order == 'name' && ($ascdesc == 'ASC' || $ascdesc == '')) ? 'DESC' : 'ASC').'&amp;searchterm='.urldecode($searchterm).'&amp;page='.$page.'">'.$cms_lang['user_name'].'</a>';
$tmp['LANG_SURNAME'] = '<a href ="'.sprintf($base_url, 'surname', ($order == 'surname' && ($ascdesc == 'ASC' || $ascdesc == '')) ? 'DESC' : 'ASC').'&amp;searchterm='.urldecode($searchterm).'&amp;page='.$page.'">'.$cms_lang['user_surname'].'</a>';
$tmp['LANG_EMAIL'] = '<a href ="'.sprintf($base_url, 'email', ($order == 'email' && ($ascdesc == 'ASC' || $ascdesc == '')) ? 'DESC' : 'ASC').'&amp;searchterm='.urldecode($searchterm).'&amp;page='.$page.'">Email</a>';
$tmp['LANG_FIRM'] = '<a href ="'.sprintf($base_url, 'firm', ($order == 'firm' && ($ascdesc == 'ASC' || $ascdesc == '')) ? 'DESC' : 'ASC').'&amp;searchterm='.urldecode($searchterm).'&amp;page='.$page.'">Firma</a>';
$tmp['LANG_ACTIONS'] = $cms_lang['user_action'];

//search
if (strlen($searchterm) > 0) {
	$tmp['SEARCHRESET'] = 'Ansicht gefiltert nach <strong>"'.htmlentities($searchterm, ENT_COMPAT, 'utf-8').'"</strong> |
          <a class="action" href="'.sprintf($base_url, $order, $ascdesc).'&amp;page=1'.'">zur&uuml;cksetzen</a>';
    $tmp['SEARCHTERM'] = $searchterm;
} else {
	$tmp['SEARCHRESET'] = '';
	$tmp['SEARCHTERM']  = '';
}


// User und Pager generieren
$items_per_page = ((int) $cfg_cms['paging_items_per_page'] > 0) ? $cfg_cms['paging_items_per_page']:10;
$delta = 2;
$uc =& sf_factoryGetObject('ADMINISTRATION', 'UserCollection');
$uc->setSearchterm($searchterm);
$uc->setLimitMax($items_per_page);
$uc->setLimitStart( ( ($page-1)*$items_per_page) );
$uc->setHideAdmins(! $perm->is_admin());
$uc->setOrder($order, $ascdesc);
$uc->setIdgroup($idgroup);
$uc->generate();
// fallback page does not exsist
if ($uc->getCount() < 1 && $page > 1) {
	sf_header_redirect(sprintf($base_url, $order, $ascdesc).'&searchterm='.urldecode($searchterm).'&page=1');
}


$pager =& sf_factoryGetObject('GUI', 'Pager');
$pager->setTotalItems($uc->getCountAll());
$pager->setItemsPerPage($items_per_page);
$pager->setDelta($delta);
$pager->setCurrentPage($page);
$pager->setExecludeVars(array('changepage1', 'changepage2'));
$pager->generate();
$tmp['PAGER_LINKS'] = $pager->getLinks();
$tmp['CHANGEPAGE_CURRENT'] = $page;
$tmp['CHANGEPAGE_MAX'] = $pager->getCountPages();

$tpl->setVariable($tmp);
unset($tmp);

// Aktionen
$tpl->setCurrentBlock('SELECT_ACTIONLIST');
$tmp['ACTIONLIST_VALUE'] = 'user';
$tmp['ACTIONLIST_ENTRY'] = $cms_lang['user_action'];
$tmp['ACTIONLIST_SELECTED'] = '';
$tpl->setVariable($tmp);
$tpl->parseCurrentBlock();
unset($tmp);

// User anlegen
$tmp['ACTIONLIST_VALUE'] = 'user_edit';
$tmp['ACTIONLIST_ENTRY'] = $cms_lang['user_new'];
$tmp['ACTIONLIST_SELECTED'] = '';


//$tpl->setVariable($tmp);
//$tpl->parseCurrentBlock();
//unset($tmp);

// Usergruppen auflisten
$tpl->setCurrentBlock('SELECT_SHOWLIST');
$tmp['SHOWLIST_VALUE'] = '0';
$tmp['SHOWLIST_ENTRY'] = 'Alle Gruppen';
$tmp['SHOWLIST_SELECTED'] = ($idgroup == 0) ? ' selected':'';
$tmp['SHOWLIST_STYLE'] = '';
$tpl->setVariable($tmp);
$tpl->parseCurrentBlock();
unset($tmp);

$tmp['SHOWLIST_VALUE'] = '-1';
$tmp['SHOWLIST_ENTRY'] = 'Ohne Gruppenzuordnung';
$tmp['SHOWLIST_SELECTED'] = ($idgroup == -1) ? ' selected':'';
$tmp['SHOWLIST_STYLE'] = '';
$tpl->setVariable($tmp);
$tpl->parseCurrentBlock();
unset($tmp);

$sql = "SELECT idgroup, name, is_active, is_sys_admin FROM ".$cms_db['groups']." ORDER BY name";
$db->query($sql);
while ($db->next_record()) {
	// Standardusergruppe ausblenden
	if ($db->f('idgroup') != '1') {
		if (! $perm->is_admin() && $db->f('is_sys_admin')) {
			continue;
		}
		$tmp['SHOWLIST_VALUE'] = $db->f('idgroup');
		$tmp['SHOWLIST_ENTRY'] = $db->f('name');
		if ($idgroup == $db->f('idgroup')) $tmp['SHOWLIST_SELECTED'] = ' selected';
		$tmp['SHOWLIST_STYLE'] = ($db->f('is_sys_admin') == '1') ? ' style="color:darkgreen;"' : '';
		if (empty($tmp['SHOWLIST_STYLE']) && $db->f('is_active') == '0') $tmp['SHOWLIST_STYLE'] = ' style="color:red;"';
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
	}
}

$tpl->setCurrentBlock('ENTRY');
if ($uc->getCount() > 0) {
	for($iter =& $uc->get();$iter->valid();$iter->next()) {
		$item =& $iter->current();
		
		if ($item->getIsAdmin()) {
			$usericon = 'but_user_red.gif';
			$usericon_desc = 'Benutzer hat Administratorrechte';
		} else if ($item->getHaveBackendAccess()) {
			$usericon = 'but_user_green.gif';
			$usericon_desc = 'Benutzer hat Backendrechte';
		} else {
			$usericon = 'but_user_blue.gif';
			$usericon_desc = 'Frontendbenutzer';
		}
		$tmp['USERICON'] = '<img src="tpl/'.$cfg_cms['skin'].'/img/'.$usericon.'" alt="'.$usericon_desc.'" title="'.$usericon_desc.'" width="16" height="16" alt="" class="icon" />';
		$tmp['LOGINNAME'] = htmlentities($item->getUsername(), ENT_COMPAT, 'UTF-8');
		$tmp['NAME'] = htmlentities($item->getName(), ENT_COMPAT, 'UTF-8').'&nbsp;';
		$tmp['SURNAME'] = htmlentities($item->getSurname(), ENT_COMPAT, 'UTF-8');
		$tmp['FIRM'] = htmlentities($item->getFirm(), ENT_COMPAT, 'UTF-8');
		$tmp['EMAIL'] = htmlentities($item->getEmail(), ENT_COMPAT, 'UTF-8');
		$tmp['BUTTON_SENDMAIL'] = ($item->getEmail() != '') ? '<a href="mailto:'.htmlentities($item->getEmail(), ENT_COMPAT, 'UTF-8').'"><img src="tpl/'.$cfg_cms['skin'].'/img/but_email.gif" alt="'.$cms_lang['user_sendmail'].'" title="'.$cms_lang['user_sendmail'].'" width="16" height="16" alt="" /></a>' : '<img src="tpl/'.$cfg_cms['skin'].'/img/space.gif" width="24" height="11" alt="" />';
		$tmp['BUTTON_EDIT'] = '<a href="'.$sess->url("main.php?area=user_edit&idgroup=$idgroup&order=$order&ascdesc=$ascdesc&iduser=".$item->getIduser().'&amp;searchterm='.urldecode($searchterm).'&amp;page='.$page).'"><img src="tpl/'.$cfg_cms['skin'].'/img/but_edit.gif" alt="'.$cms_lang['user_edit'].'" title="'.$cms_lang['user_edit'].'" width="16" height="16" alt="" /></a>';
		if ($item->getIsDeletable() == '1') {
	                	$tmp['BUTTON_AKTIVE'] = ($item->getIsOnline() == '0') ? '<a href="'.sprintf($base_url, $order, $ascdesc).'&action=activate&iduser='.$item->getIduser().'&amp;searchterm='.urldecode($searchterm).'&amp;page='.$page.'"><img src="tpl/'.$cfg_cms['skin'].'/img/but_offline.gif" alt="'.$cms_lang['user_on'].'" title="'.$cms_lang['user_on'].'" width="16" height="16" alt="" /></a>' : '<a href="'.sprintf($base_url, $order, $ascdesc).'&action=deactivate&iduser='.$item->getIduser().'&amp;searchterm='.urldecode($searchterm).'&amp;page='.$page.'"><img src="tpl/'.$cfg_cms['skin'].'/img/but_online.gif" alt="'.$cms_lang['user_off'].'" title="'.$cms_lang['user_off'].'" width="16" height="16"></a>';
			$tmp['BUTTON_DELETE'] = '<a href="'.sprintf($base_url, $order, $ascdesc).'&action=delete&iduser='.$item->getIduser().'&amp;searchterm='.urldecode($searchterm).'&amp;page='.$page.'" onclick="return delete_confirm()"><img src="tpl/'.$cfg_cms['skin'].'/img/but_delete.gif" alt="'.$cms_lang['user_delete'].'" title="'.$cms_lang['user_delete'].'" width="16" height="16" alt="" /></a>';
		} else {
	                	$tmp['BUTTON_AKTIVE'] = '<img src="tpl/'.$cfg_cms['skin'].'/img/space.gif" width="16" height="16" alt="" />';
			$tmp['BUTTON_DELETE'] = '<img src="tpl/'.$cfg_cms['skin'].'/img/space.gif" width="16" height="16" alt="" />';
		}
		$tpl->setVariable($tmp);
		$tpl->parseCurrentBlock();
		unset($tmp);
	}
} else {
	$tpl -> setCurrentBlock('EMPTY');
	$tmp['LANG_NOUSERS'] = $cms_lang['user_nousers'];
	$tpl->setVariable($tmp);
	$tpl->parse('EMPTY');
	unset($tmp);
}
?>
