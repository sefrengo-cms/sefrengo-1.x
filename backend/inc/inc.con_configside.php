<?PHP
// File: $Id: inc.con_configside.php 34 2008-05-12 13:23:11Z mistral $
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
// + Revision: $Revision: 34 $
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

/**
 * 1. Benötigte Funktionen und Klassen includieren
 */

include('inc/fnc.tpl.php');
include('inc/fnc.mipforms.php');
include('inc/fnc.mod_rewrite.php');
/**
 * 2. Eventuelle Actions/ Funktionen abarbeiten
 */

// idcatside vorhanden, prüfen, ob Recht zum konfigurieren gegeben ist
if(is_numeric($idcatside))$perm->check(20, 'side', $idcatside, $idcat);
//Neue Seite, prüfen, ob recht auf neue Seite anlegen vorhanden ist
else $perm->check(18, 'cat', $idcat);


// rewrite check
$sf_is_rewrite_error = false;
if ($action == 'save') {
	$have_rewrite_perm = ( is_numeric($idcatside) ) ? $perm->have_perm(31, 'side', $idcatside, $idcat): $perm -> have_perm(31, 'cat', $idcat) ;
	if ($cfg_client['url_rewrite'] == '2' && $have_rewrite_perm) {
		 if($_REQUEST['rewrite_use_automatic'] != '1') {
			if (! rewriteUrlIsAllowed($_REQUEST['rewrite_url'], true)) {
				$sf_is_rewrite_error = true;
				$sf_rewrite_error_message = 'Diese URL enth&auml;lt keine oder nicht erlaubte Zeichen! Erlaubte Zeichen sind: "a-z0-9/_-.,". Ein f&uuml;hrender "/", sowie zwei oder mehr aufeinander folgende "/" sind ebenfalls nicht erlaubt.';
				$action = 'change';
			} else if (! rewriteUrlIsUnique('idcatside', $idcatside, $_REQUEST['rewrite_url'])) {
				$sf_is_rewrite_error = true;
				$sf_rewrite_error_message = 'Dieser URL- Alias wurde schon f&uuml;r eine anderen Seite vergeben!';
				$action = 'change';				
			} else if (rewriteManualUrlMatchAutoUrl($_REQUEST['rewrite_url'])) {
				$sf_is_rewrite_error = true;
				$sf_rewrite_error_message = 'Dieser URL- Alias entspricht der URL einer anderen Seite oder eines anderern Ordners';
				$action = 'change';	
			}
		 } 
	}
}



// Seitenkonfiguration speichern
switch($action) {
	case 'save':  // Template bearbeiten
		$use_redirect = isset($_REQUEST['sf_apply']) ? false: true;
		con_config_side_save($idcat, $idside, $idtpl, $idtplconf, $idsidelang, $idcatside, $idcatnew
                                       , $author, $title, $meta_keywords, $summary, $online, $user_protected
                                       , $view, $created, $lastmodified, $startdate, $starttime, $enddate, $endtime
                                       , $meta_other, $meta_title, $meta_author, $meta_description, $meta_robots, $meta_redirect_time
                                       , $metasocial_title,$metasocial_image,$metasocial_description,$metasocial_author
                                       , $meta_redirect, $meta_redirect_url, $rewrite_use_automatic, $rewrite_url
                                       , $idlay, $use_redirect);
		if ( isset($_REQUEST['sf_apply']) ) {
			$sql = "SELECT idtplconf FROM " . $cms_db['side_lang'] ." WHERE idside = $idside AND idlang=$lang";
			$db->query($sql);
			$db->next_record();
			$idtplconf = $db->f('idtplconf');
		}
		break;
	case 'change':  // Layout oder Modul wechseln
		$cconfig = tpl_change($idlay);
		break;
}
function type_config_string_to_array($string)
{
    if (! empty($string) && $string != 'true' && $string != 'false') {
        if (substr($string, 0, 1) == ',') {
            $string = substr($string, 1);
        } 
    } else {
        return array();
    } 

    $string = str_replace(' ', '', $string);
    $arr = explode(',', $string);
    foreach($arr AS $k => $v) {
        if ($v == '') {
            unset($arr[$k]);
        } 
    } 

    return $arr;
}
function showRB($content){
	global $db, $cms_db, $cms_lang, $client, $cfg_client; 
    // Standardfiletypes laden, wenn keine anderen angegeben, defaults laden
    $ft = strtolower(trim($type_config['filetypes']));
    $filetypes = (empty($ft) || $ft == 'true') ? 'jpg,jpeg,gif,png': $ft;
    $formname="editform";
    $match = array();
    $pathway_string = '';
	$rb = $GLOBALS['sf_factory']->getObjectForced('GUI', 'RessourceBrowser');

    $res_file = $GLOBALS['sf_factory']->getObjectForced('GUI/RESSOURCES', 'FileManager');
    $res_file->setFiletypes(type_config_string_to_array($filetypes));
    $res_file->setFolderIds(type_config_string_to_array($type_config['folders']));
    $res_file->setWithSubfoders(($type_config['subfolders'] != 'false'));
    #$res_file->setReturnValueMode('sefrengolink');

    $rb->addRessource($res_file);
    $rb->setJSCallbackFunction('sf_getImage' . $formname, array('picked_name', 'picked_value'));
    $rb_url = $rb->exportConfigURL();
 
 	if($content==""){
		$preview=$cfg_client['space']; 
	}else{
	    $preview=$content;
	}

    $out .= '<table style="height:' . ($cfg_client['thumb_size'] + 20) . 'px"><tr>
<td style="background-color:#efefef;border:1px solid black;text-align:center;vertical-align:middle;width:' . ($cfg_client['thumb_size'] + 20) . 'px;">
<img id="' . $formname . '" src="' . $preview . '"  border="0" width="100" />
</td><td valign="bottom">';

    $out .= "<input type=\"hidden\" name=\"metasocial_image\" value=\"$content\"><input type=\"text\" name=\"metasocial_imagedisplay\" readonly=\"readonly\" value=\"" . $content . "\" style=\"width:180px\" >\n";
    $out .= "<input type='button' value='DEL' onclick=\"sf_getImage" . $formname . "('', '');\" />";
    $out .= "&nbsp;<input type='button' value='...' onclick=\"new_window('$rb_url', 'rb', '', screen.width * 0.7, screen.height * 0.7, 'true')\" />";
    $out .= '</td></tr></table>' . "\n";
    $out .= '<script type="text/javascript">
	<!--
	function sf_getImage' . $formname . '(name, value) {
		editcontent.metasocial_image.value= value;
		editcontent.metasocial_imagedisplay.value= name;
		sf_loadPreviewPic("' . $cfg_client['upl_htmlpath'] . '"+name, "' . $cfg_client['thumbext'] . '", "' . $formname . '");

	}
	-->
	</script>';
    return $out;
}


/**
 * 3. Eventuelle Dateien zur Darstellung includieren
 */

// getrennte Header für Backend und Frontendbearbeitung
if (empty($view)) include('inc/inc.header.php');
else {
	echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n";
	echo "    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"de\" lang=\"de\">\n";
	echo "<head>\n";
	echo "  <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\n";
	echo "  <title>Sefrengo ".$cfg_cms['version']."</title>\n";
	echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"tpl/".$cfg_cms['skin']."/css/styles.css\" />\n";
	echo "  <link rel=\"stylesheet\" href=\"tpl/".$cfg_cms['skin']."/css/dynCalendar.css\" type=\"text/css\" />\n";
	echo "  <script src=\"tpl/".$cfg_cms['skin']."/js/standard.js\" type=\"text/javascript\"></script>\n";
	echo "  <script src=\"tpl/".$cfg_cms['skin']."/js/tabpane.js\" type=\"text/javascript\"></script>\n";
	echo "</head>\n";
	echo "<body id=\"con-edit2\">\n";
}



if ((!$action && $idside) || isset($_REQUEST['sf_apply']) && ! $sf_is_rewrite_error) {
	// Ordner der Seite suchen
	$sql = "SELECT idcat FROM ".$cms_db['cat_side']." WHERE idside='$idside'";
	$db->query($sql);
	while ($db->next_record()) $idcatnew[] = $db->f('idcat');
//echo "x";
	// Konfiguration suchen
    $sql = "SELECT * FROM ".$cms_db['side_lang']." A WHERE idside='$idside' AND idlang='$lang'";
	$db->query($sql);
	$db->next_record();
	$idsidelang    = $db->f('idsidelang');
	$title         = htmlentities($db->f('title'), ENT_COMPAT, 'UTF-8');
	$author        = $db->f('author');
	$created       = $db->f('created');
	$summary       = htmlentities($db->f('summary'), ENT_COMPAT, 'UTF-8');
	$lastmodified  = $db->f('lastmodified');
	$startdate     = date('d.m.Y', $db->f('start'));
	$starttime     = date('H:i', $db->f('start'));
	$enddate       = date('d.m.Y', $db->f('end'));
	$endtime       = date('H:i', $db->f('end'));
	$online        = $db->f('online');
	$userprotected = ($db->f('user_protected') == '1') ? 'selected' : '';
	$meta_title = htmlentities($db->f('meta_title'), ENT_COMPAT, 'UTF-8');
  $meta_other = htmlentities($db->f('meta_other'), ENT_COMPAT, 'UTF-8');
  $meta_author = htmlentities($db->f('meta_author'), ENT_COMPAT, 'UTF-8');
	$meta_description = htmlentities($db->f('meta_description'), ENT_COMPAT, 'UTF-8');
	$meta_keywords = htmlentities($db->f('meta_keywords'), ENT_COMPAT, 'UTF-8');
	$meta_robots = $db->f('meta_robots');
	/*
	*   Start Social-Addon by screengarden.de
	*/
	$metasocial_title= htmlentities($db->f('metasocial_title'), ENT_COMPAT, 'UTF-8');
	$metasocial_image= htmlentities($db->f('metasocial_image'), ENT_COMPAT, 'UTF-8');
	$metasocial_description= htmlentities($db->f('metasocial_description'), ENT_COMPAT, 'UTF-8');
	$metasocial_author= htmlentities($db->f('metasocial_author'), ENT_COMPAT, 'UTF-8'); 
	/*
	*   End Social-Addon by screengarden.de
	*/
	
	$meta_redirect = ($db->f('meta_redirect') == '1') ? ' checked' : '';
	$meta_robots_time = $db->f('meta_robots_time');
	$meta_redirect_url = ($db->f('meta_redirect_url') != '') ? $db->f('meta_redirect_url') : 'http://';
	$rewrite_use_automatic = $db->f('rewrite_use_automatic');
	$rewrite_url    = $db->f('rewrite_url');	
} elseif (!$action && !$idside) {
	$idcatnew['0'] = $idcat;
	$author = $auth->auth['uid'];
	$created = time();
	$online = '0';
	$startdate = date('d.m.Y', time());
	$starttime = '00:00';
	$enddate = date('d.m.Y', time());
	$endtime = '00:00';
	$lastmodified = $created;
	$sql = "SELECT * FROM ". $cms_db['users'] ." WHERE user_id='".$auth->auth['uid']."'";
	$db->query($sql);
	$db->next_record();
	$meta_author = htmlentities($db->f('name').' '.$db->f('surname'), ENT_COMPAT, 'UTF-8');
	$cfg_lang = $val_ct -> get_by_group('cfg_lang', $client, $lang);
	$meta_title = htmlentities($cfg_lang['meta_title'], ENT_COMPAT, 'UTF-8');
  $meta_other = htmlentities($cfg_lang['meta_other'], ENT_COMPAT, 'UTF-8');
  $meta_description = htmlentities($cfg_lang['meta_description'], ENT_COMPAT, 'UTF-8');
	$meta_keywords = htmlentities($cfg_lang['meta_keywords'], ENT_COMPAT, 'UTF-8');
	$meta_robots = htmlentities($cfg_lang['meta_robots'], ENT_COMPAT, 'UTF-8');
	/*
	*   Start Social-Addon by screengarden.de
	*/
	$metasocial_title= "";
	$metasocial_image= "";
	$metasocial_description=$meta_description;
	$metasocial_author= $meta_author; 
	/*
	*   End Social-Addon by screengarden.de
	*/
	$meta_redirect_url = 'http://';
	$rewrite_use_automatic = 1;
	$rewrite_url    = '';
} else {
	if (!is_array($idcatnew)) $idcatnew['0'] = $idcat;
	$meta_redirect = ($meta_redirect == '1') ? ' checked' : '';
	$meta_redirect_url = ($meta_redirect_url != '') ? $meta_redirect_url : 'http://';
	$userprotected = ($user_protected == '1') ? 'selected' : '';
	$rewrite_use_automatic = $_REQUEST['rewrite_use_automatic'];
	$rewrite_url    = (string) $_REQUEST['rewrite_url'];
}


// Selectbox darf Seite sperren erzeugen
// DEPRECATED
//$have_sidelock_perm = (is_numeric($idcatside)) ? $perm->have_perm(31, 'side', $idcatside, $idcat): $perm -> have_perm(31, 'cat', $idcat) ;
//if($have_sidelock_perm) {
//	$select_lock_side = "<select name=\"user_protected\" size=\"1\">\n
//	<option value=\"\">".$cms_lang['con_side_not_locked_for_other_editors']."</option>\n
//	<option value=\"1\" $userprotected>".$cms_lang['con_side_locked_for_other_editors']."</option>\n
//	</select>\n";
//} else 

$select_lock_side = '';

// Selectbox für Seiten verschieben, clonen
$select_sidemove = '<select name="idcatnew[]" multiple="multiple" style="height:150px;width:380px">';
$sql = "SELECT A.idcat, parent, sortindex, name, idtplconf
	FROM ".$cms_db['cat']." A
	LEFT JOIN ".$cms_db['cat_lang']." B USING(idcat)
	WHERE B.idlang='$lang'
	AND A.idclient='$client'
	ORDER BY parent, sortindex";
$db->query($sql);
while ($db->next_record()) {
	$con_tree[$db->f('idcat')]['name'] = $db->f('name');
	$con_tree[$db->f('idcat')]['idtplconf'] = $db->f('idtplconf');
	$tlo_tree[$db->f('parent')][$db->f('sortindex')] = $db->f('idcat');
}
tree_level_order('0', 'catlist');
$select_sidemove_hidden = '';
if (is_array($catlist)) {
	foreach ($catlist as $a) {
		if ($con_tree[$a]['idtplconf'] != '0' && $perm -> have_perm(1, 'cat', $a) || $a == $idcat) {
			$spaces = '&nbsp;';
			for ($i=0; $i<$catlist_level[$a]; $i++) $spaces = $spaces.'&nbsp;&nbsp;';
			if (!in_array($a,$idcatnew)) $select_sidemove .= "<option value=\"$a\">$spaces ".$con_tree[$a]['name']."</option>";
			else {
				$select_sidemove .= "<option value=\"$a\" selected>$spaces ".$con_tree[$a]['name']."</option>";
				$select_sidemove_hidden .= '<input type="hidden" name="idcatnew[]" value="'. $a .'" />';
			}
		}
	}
}
$select_sidemove .= "</select>";

// radiobox online, offline, zeitgesteuert
$radio_visibllity  =  sprintf('<input type="radio" name="online" value="0" id="a0" %s /> <label for="a0">'.$cms_lang['con_side_offline'].'</label> ', (((int)$online & 0x03) == 0x00) ? 'checked' : '');
$radio_visibllity .=  sprintf('<input type="radio" name="online" value="1" id="a1" %s /> <label for="a1">'.$cms_lang['con_side_online'].'</label> ', (((int)$online & 0x03) == 0x01) ? 'checked' : '');
$radio_visibllity .=  sprintf('<input type="radio" name="online" value="2" id="a2" %s /> <label for="a2">'.$cms_lang['con_side_time'].'</label> ', (((int)$online & 0x03) == 0x02) ? 'checked' : '');
$radio_visibllity_hidden =  sprintf('<input type="hidden" name="online" value="%s" />', $online);

// Datumsangaben für Ausgabe formatieren
$print_created = date($cfg_cms['FormatDate'].' '.$cfg_cms['FormatTime'],$created);
$print_lastmodified = date($cfg_cms['FormatDate'].' '.$cfg_cms['FormatTime'],$lastmodified);

// Zeitsteuerung Startdatum
$html_startdate = '<input type="text" name="startdate" onchange="document.editform.online[2].checked=true" value="'. $startdate .'" size="10" maxlength="10" style="width: 65px;" />
	' . $cms_lang['con_timemanagement_starttime'] . '
	<input type="text" name="starttime" value="'. $starttime .'" size="5" maxlength="5"  onchange="document.editform.online[2].checked=true;" style="width:35px;" />
	<script type="text/javascript">
	  calendar1 = new dynCalendar("calendar1", "callback_startdate");
	  calendar1.setMonthCombo(true);
	  calendar1.setYearCombo(true);
	</script>';

$html_startdate_hidden = '<input type="hidden" name="startdate" value="'. $startdate .'" />
<input type="hidden" name="starttime" value="'. $starttime .'" />';

// Zeitsteuerung Enddatum
$html_enddate = '<input type="text" name="enddate" value="'. $enddate .'" size="10" maxlength="10"  onchange="document.editform.online[2].checked=true;" style="width: 65px;" />
	' . $cms_lang['con_timemanagement_endtime'] . '
	<input type="text" name="endtime" value="'. $endtime .'" size="5" maxlength="5"  onchange="document.editform.online[2].checked=true;" style="width:35px;" />
	<script type="text/javascript">
	  calendar2 = new dynCalendar("calendar2", "callback_enddate");
	  calendar2.setMonthCombo(true);
	  calendar2.setYearCombo(true);
	</script>';
$html_enddate_hidden = '<input type="hidden" name="enddate" value="'. $enddate .'" />
						  <input type="hidden" name="endtime" value="'. $endtime .'" />';


// Auswahl robtos
$html_robots = '<select name="meta_robots" size="1" style="width:318px">'."\n";
$html_robots .= sprintf('          <option value="index, follow"%s>'.$cms_lang['con_metarobotsif'].'</option>'."\n", ($meta_robots == 'index, follow') ? ' selected="selected"' : '');
$html_robots .= sprintf('          <option value="index, nofollow"%s>'.$cms_lang['con_metarobotsin'].'</option>'."\n", ($meta_robots == 'index, nofollow') ? ' selected="selected"' : '');
$html_robots .= sprintf('          <option value="noindex, follow"%s>'.$cms_lang['con_metarobotsnf'].'</option>'."\n", ($meta_robots == 'noindex, follow') ? ' selected="selected"' : '');
$html_robots .= sprintf('          <option value="noindex, nofollow"%s>'.$cms_lang['con_metarobotsnn'].'</option>'."\n", ($meta_robots == 'noindex, nofollow') ? ' selected="selected"' : '');
$html_robots .= '        </select>';

$html_robots_hidden = '<input type="hidden" name="meta_robots" value="'. $meta_robots .'" />';

/**
 * 4. Bildschirmausgabe aufbereiten und ausgeben
 */

$tpl->loadTemplatefile('side_config.tpl', false);

// URL REWRITE
$have_rewrite_perm = ( is_numeric($idcatside) ) ? $perm->have_perm(31, 'side', $idcatside, $idcat, true): $perm -> have_perm(31, 'cat', $idcat) ;
if ($cfg_client['url_rewrite'] == '2' && $have_rewrite_perm) {
	$tpl->setCurrentBlock('URL_REWRITE');
	$tpl_data['REWRITE_USE_AUTOMATIC_CHECKED'] = ($rewrite_use_automatic == 1) ? 'checked="checked" ':'';
	$tpl_data['REWRITE_URL'] = $rewrite_url;
	$tpl_data['REWRITE_URL_BACKGROUNDCOLOR'] = ($rewrite_use_automatic == 1) ? '#cccccc':'#ffffff';
	$tpl_data['REWRITE_URL_DISABLED'] = ($rewrite_use_automatic == 1) ? 'disabled="disabled" ':'';
	$tpl_data['REWRITE_ERROR'] = $rewrite_error = ($sf_is_rewrite_error) ? '<p class="errormsg">'.$sf_rewrite_error_message.'</p>':'';
	
	if ($rewrite_use_automatic == 1) {
		$tpl_data['REWRITE_CURRENT_URL'] = ($rewrite_url == '') ? rewriteGetPath($idcat, $lang). '<em>{Diese Seite}</em>'. $cfg_client['url_rewrite_suffix']: rewriteGetPath($idcat, $lang). '<strong>'.$rewrite_url.'</strong>'. $cfg_client['url_rewrite_suffix'];
	} else {
		$tpl_data['REWRITE_CURRENT_URL'] = ($rewrite_url == '') ?  '<em>{Diese Seite}</em>': '<strong>'.$rewrite_url.'<strong>';
	}
	$tpl_data['REWRITE_CURRENT_URL'] = 'http://<em>{domain.xyz}</em>/'. $tpl_data['REWRITE_CURRENT_URL'];
	
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
} else {
	$tpl->setCurrentBlock('HIDDEN_FIELDS');
	$tpl_data['HIDDEN_FIELDS'] = '<input type="hidden" name="rewrite_use_automatic" value="'. $rewrite_use_automatic .'" />
	  <input type="hidden" name="rewrite_url" value="'. $rewrite_url .'" />';
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);	
}

// Zeitsteuerung
$have_online_offline_perm = ( is_numeric($idcatside) ) ? $perm->have_perm(23, 'side', $idcatside, $idcat): $perm -> have_perm(23, 'cat', $idcat) ;
if($have_online_offline_perm) {
	$tpl->setCurrentBlock('TIMER_BLOCK');
	$tpl_data['VISBILITY_DESC'] = $cms_lang['con_visibility'];
	$tpl_data['LANG_SIDE_IS'] = $cms_lang['con_side_is'];
	$tpl_data['VISIBILITY'] = $radio_visibllity;
	$tpl_data['LANG_ONLINE'] = $cms_lang['con_side_is_online_at'];
	$tpl_data['STARTDATE'] = $html_startdate;
	$tpl_data['LANG_OFFLINE'] = $cms_lang['con_side_is_offline_at'];
	$tpl_data['ENDDATE'] = $html_enddate;
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
} else {
	$tpl->setCurrentBlock('HIDDEN_FIELDS');
	$tpl_data['HIDDEN_FIELDS'] = $radio_visibllity_hidden . $html_startdate_hidden . $html_enddate_hidden;
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
}

// Darf Seiten verschieben
$have_move_perm = (is_numeric($idcatside)) ? $perm->have_perm(30, 'side', $idcatside, $idcat): $perm -> have_perm(30, 'cat', $idcat);
if ($have_move_perm) {
	$tpl->setCurrentBlock('CLONE_AND_NOTICE');
	$tpl_data['LANG_MOVE_SIDE'] = $cms_lang['con_move_side'];
	$tpl_data['SELECT_SIDEMOVE'] = $select_sidemove;
	$tpl_data['LANG_NOTICES'] = $cms_lang['con_notices'];
	$tpl_data['SUMMARY'] = empty($summary) ? '' : $summary;
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
} else {
	$tpl->setCurrentBlock('NOTICE');
	$tpl_data['LANG_NOTICES'] = $cms_lang['con_notices'];
	$tpl_data['SUMMARY'] = empty($summary) ? '' : $summary;
	$tpl_data['HIDDEN_CLONES'] = $select_sidemove_hidden;
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
}

// Metaangaben bearbeiten
$have_meta_perm = (is_numeric($idcatside)) ? $perm->have_perm(29, 'side', $idcatside, $idcat): $perm -> have_perm(29, 'cat', $idcat);
if ($have_meta_perm) {
	$tpl->setCurrentBlock('META');
  $tpl_data['LANG_META_OTHER'] = $cms_lang['con_metaother'];
  $tpl_data['META_OTHER'] = $meta_other;
  $tpl_data['LANG_META_TITLE'] = $cms_lang['con_metatitle'];
  $tpl_data['META_TITLE'] = $meta_title;
	$tpl_data['LANG_CON_METACONFIG'] = $cms_lang['con_metaconfig'];
	$tpl_data['LANG_META_DESC'] = $cms_lang['con_metadescription'];
	$tpl_data['META_DESC'] = $meta_description;
	$tpl_data['LANG_META_KEYWORDS'] = $cms_lang['con_metakeywords'];
	$tpl_data['META_KEYWORDS'] = $meta_keywords;
	$tpl_data['LANG_META_AUTHOR'] = $cms_lang['con_metaauthor'];
	$tpl_data['LANG_META_ROBOTS'] = $cms_lang['con_metarobots'];
	$tpl_data['META_AUTHOR'] = $meta_author;
	$tpl_data['META_ROBOTS'] = $html_robots;
	$tpl_data['LANG_META_REDIRECT'] = $cms_lang['con_metaredirect'];
	/*
	*   Start Social-Addon by screengarden.de
	*/	
	$tpl_data['LANG_META_SOCIAL'] = $cms_lang['con_metasocial'];
	$tpl_data['LANG_META_SOCIAL_TITLE'] = $cms_lang['con_metasocial_title'];
	$tpl_data['LANG_META_SOCIAL_IMAGE'] = $cms_lang['con_metasocial_image'];
	$tpl_data['LANG_META_SOCIAL_DESCRIPTION'] = $cms_lang['con_metasocial_description'];
	$tpl_data['LANG_META_SOCIAL_AUTHOR'] = $cms_lang['con_metaauthor']; 
	
	$tpl_data['META_SOCIAL_TITLE'] = $metasocial_title;
	$tpl_data['META_SOCIAL_IMAGE'] = showRB($metasocial_image);
	$tpl_data['META_SOCIAL_DESCRIPTION'] = $metasocial_description;
	$tpl_data['META_SOCIAL_AUTHOR'] = $metasocial_author; 
	/*
	*   End Social-Addon by screengarden.de
	*/
	$tpl_data['META_REDIRECT'] = $meta_redirect;
	$tpl_data['META_REDIRECT_URL'] = $meta_redirect_url;
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
} else {
	$tpl->setCurrentBlock('HIDDEN_FIELDS');
	$tpl_data['HIDDEN_FIELDS'] = '<input type="hidden" name="meta_title" value="'. $meta_title .'" />
  <input type="hidden" name="meta_other" value="'. $meta_other .'" />
  <input type="hidden" name="meta_description" value="'. $meta_description .'" />
	  <input type="hidden" name="meta_keywords" value="'. $meta_keywords .'" />
	  <input type="hidden" name="meta_author" value="'. $meta_author .'" />
	  <input type="hidden" name="meta_redirect_url" value="'. $meta_redirect_url .'" />'
      . $html_robots_hidden;
     if($meta_redirect != '')
     	$tpl_data['HIDDEN_FIELDS'] .= '<input type="hidden" name="meta_redirect" value="1" />';
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
}

// Rechtemanagement
if (!empty($idcatside) 
		&& ( $perm->have_perm(22, 'side', $idcatside, $idcat)  
			|| $perm->have_perm(31, 'side', $idcatside, $idcat) ) ) {
	
	$tpl->setCurrentBlock('USER_RIGHTS');
			
	//backendperms
	if ($perm->have_perm(22, 'side', $idcatside, $idcat)) {
		$panel1 = $perm->get_right_panel('side', $idcatside, array( 'formname'=>'editform' ), 'Backendrechte bearbeiten', false, false, $idcat, 'backend_' );
		if (!empty($panel1)) {
			$tpl_data['BACKENDRIGHTS'] = implode("", $panel1);
		}
	} else {
		$tpl_data['BACKENDRIGHTS'] = '';
	}
	
	//frontendperms area_frontend
	if ($perm->have_perm(14, 'cat', $idcat)) {
		$panel2 = $perm->get_right_panel('frontendpage', $idcatside, array( 'formname'=>'editform' ), 'Frontendrechte bearbeiten', false, false, $idcat, 'frontend_' );
		if (!empty($panel2)) {
			$tpl_data['FRONTENDRIGHTS'] = implode("", $panel2);
		}
	} else {
		$tpl_data['FRONTENDRIGHTS'] = '';
	}
	
	$tpl_data['LANG_RIGHTS'] = '';
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
}

// Outputbuffering für das Backend temporär beenden, um Content aus den Modulen in Variablen speichern zu können
$temp_output_for_backend = ob_get_contents();
ob_end_clean();
ob_start();

// Darf Templatekonfiguration betreten
$have_enter_tpl_perm = (is_numeric($idcatside)) ? $perm->have_perm(26, 'side', $idcatside, $idcat): $perm -> have_perm(26, 'cat', $idcat);
if ($have_enter_tpl_perm) {
	echo "<tr>\n";
	echo "<td class=\"head nowrap\">".$cms_lang['con_template']."</td>\n";

	// Darf Templates konfigurieren?
	$have_config_tpl_perm = ( is_numeric($idcatside) ) ? $perm->have_perm(27, 'side', $idcatside, $idcat): $perm -> have_perm(27, 'cat', $idcat) ;
	if ($have_config_tpl_perm) {
		echo "<td colspan=\"3\">\n<select name=\"idtpl\" size=\"1\" onchange=\"document.editform.action.value='changetpl';document.editform.submit();\">\n";
	} else echo "<td colspan=\"3\">\n<select name=\"idtpl\" size=\"1\">\n";

	// konfiguriertes Template und Layout suchen
	if ($idtplconf != '0' && !$idtpl && !$configtpl) {
		$sql = "SELECT B.idlay, B.idtpl
			FROM $cms_db[tpl_conf] A
                         LEFT JOIN $cms_db[tpl] B USING(idtpl)
			WHERE idtplconf='$idtplconf'";
		$db->query($sql);
		$db->next_record();
		$idlay = $db->f('idlay');
		$idtpl = $db->f('idtpl');
		$configtpl = $idtpl;
	} else {
		$sql = "SELECT idlay, idtpl FROM $cms_db[tpl] WHERE idtpl='$idtpl'";
		$db->query($sql);
		$db->next_record();
		$idlay = $db->f('idlay');
	}
	echo "        <option value=\"0\" selected=\"selected\">Ordnertemplate</option>";  //fehlender Textbaustein

	// Templates Auflisten
	$sql = "SELECT idtpl, name FROM $cms_db[tpl] WHERE idclient='$client' ORDER BY name";
	$db->query($sql);
	while ($db->next_record()) {
		if ($db->f('idtpl') == $idtpl){
			 echo "<option value=\"".$db->f('idtpl')."\" selected=\"selected\">".$db->f('name')."</option>";
		}
		else if ($perm -> have_perm(1, 'tpl', $db->f('idtpl'))) {
			echo "<option value=\"".$db->f('idtpl')."\">".$db->f('name')."</option>";
		}
	}
	echo "      </select>\n</td>\n";
	echo "    </tr>\n\n\n</table>";
	echo "    <input type=hidden name=\"configtpl\" VALUE=\"$configtpl\" />";


// Darf Templateauswahl nicht betreten
} else {
	$sql = "SELECT B.idtpl
		FROM $cms_db[tpl_conf] A
        LEFT JOIN $cms_db[tpl] B USING(idtpl)
		WHERE idtplconf='$idtplconf'";
	$db->query($sql);
	if ($db->next_record())
		$idtpl = $db->f('idtpl');
	else $idtpl = 0;
	
	if($idtplconf != '0')
		$configtpl = $idtpl;

	echo "    </table>\n";
	echo '<input type="hidden" name="idtpl" value="'. $idtpl .'" />
	      <input type="hidden" name="configtpl" value="'. $configtpl .'" />';
}

// Template konfigurieren
if ($have_config_tpl_perm) {
	echo "    <input type=hidden name=\"idlay\" VALUE=\"$idlay\" />\n";

	// Module auflisten
	$list = browse_layout_for_containers($idlay);

	// Einstellungen suchen
	if ($configtpl == $idtpl){
		$sql = "SELECT A.config, A.view, A.edit, B.container, C.name, C.input, C.idmod, C.version, C.verbose, C.cat, C.source_id, C.idmod
			FROM $cms_db[container_conf] A
			LEFT JOIN $cms_db[container] B USING(idcontainer)
			LEFT JOIN $cms_db[mod] C USING(idmod)
			WHERE A.idtplconf='$idtplconf'";
	} else {
		$sql = "SELECT A.config, A.view, A.edit, B.container, C.name, C.input, C.idmod, C.version, C.verbose, C.cat, C.source_id, C.idmod
			FROM $cms_db[container_conf] A
			LEFT JOIN $cms_db[container] B USING(idcontainer)
			LEFT JOIN $cms_db[mod] C USING(idmod)
			WHERE A.idtplconf='0' AND B.idtpl='$idtpl'";
	}
	$db->query($sql);
	while ($db->next_record()) {
		$container[$db->f('container')] = array ( $db->f('config'),      // value 0
		                                          $db->f('view'),        // value 1
		                                          $db->f('edit'),        // value 2
		                                          htmlentities($db->f('name'), ENT_COMPAT, 'UTF-8'),        // value 3
		                                          $db->f('input'),       // value 4
		                                          htmlentities($db->f('idmod'), ENT_COMPAT, 'UTF-8'),       // value 5
		                                          htmlentities($db->f('version'), ENT_COMPAT, 'UTF-8'),     // value 6 
                                                          htmlentities($db->f('verbose'), ENT_COMPAT, 'UTF-8'),     // value 7
                                                          htmlentities($db->f('cat'), ENT_COMPAT, 'UTF-8'),         // value 8
                                                          htmlentities($db->f('source_id'), ENT_COMPAT, 'UTF-8'),   // value 9
                                                          htmlentities($db->f('idmod'), ENT_COMPAT, 'UTF-8'));      // value 10
	}
	if (is_array($container)) {
		ksort($container);
		foreach ($container as $key => $value) {
			if (is_array($list['id'])) {
				if (in_array($key, $list['id'])) {
					$input = $value['4'];
					// Containername
					$modname = ( (($value['7'] != '') ? $value['7'] : $value['3']) . ((empty($value['6'])) ? '' : ' (' . $value['6'] . ')') );
					$modtitel = ( ' ++ ' .$cms_lang['gen_description'] . ' ++ &#10;' . (($value['8'] != '') ? $cms_lang['gen_cat'] . ': ' . $value['8'] . ' &#10;' : '') .
                                                    (($value['7'] != '') ? $cms_lang['gen_verbosename'] . ': ' . $value['7'] . ' &#10;' : '') .
                                                    (empty($value['9']) ? $cms_lang['gen_name'] : $cms_lang['gen_original']) . ': ' . $value['3'] . ' &#10;' .
                                                    (($value['6'] != '') ? $cms_lang['gen_version'] . ': ' . $value['6'] . ' &#10;' : '') . 'IdMod: ' . $value['10'] );
					$modcursor = 'pointer';
     echo "    ";
					echo "    <table class=\"config\" cellspacing=\"1\">\n<tr>\n";
               printf ("        <td class=\"head nowrap\" rowspan=\"2\"><p>%s</p></td>\n", (!empty($list[$key]['title'])) ? $list[$key]['title']:"$key. ".$cms_lang['tpl_container']."");
					echo "      <td class=\"headre\">\n<input type=\"hidden\" name=\"c$key\" value=\"".$value['5']."\" />\n";
					echo "          <div class=\"forms\">";
					echo "        <select name=\"cview$key\" size=\"1\">\n";
					printf ("          <option value=\"0\"%s>". $cms_lang['gen_mod_active'] ."</option>\n", ($value['1'] == '0' || !$value['1']) ? ' selected':'');
					printf ("          <option value=\"-1\"%s>". $cms_lang['gen_mod_deactive'] ."</option>\n", ($value['1'] == '-1') ? ' selected':'');
					echo "        </select>";
					echo "        <select name=\"cedit$key\" size=\"1\">\n";
					printf ("          <option value=\"0\"%s>". $cms_lang['gen_mod_edit_allow'] ."</option>\n", ($value['2'] == '0' || !$value['2']) ? ' selected':'');
					printf ("          <option value=\"-1\"%s>". $cms_lang['gen_mod_edit_disallow'] ."</option>\n", ($value['2'] == '-1') ? ' selected':'');
				   	echo "        </select>\n";
					echo "        </div>";
     
					echo "        <img style=\"cursor:$modcursor\" src=\"tpl/" . $cfg_cms['skin'] . "/img/about.gif\" alt=\"" . $modtitel .
                                                "\" title=\"" . $modtitel . "\" width=\"16\" height=\"16\" /> ".$modname;

					echo "      </td>\n";
					echo "    </tr>\n";
					echo "    <tr>\n";
					echo "      <td class=\"content nopadd\">";

					// Developer-Modul
					if (strpos($value['6'], 'dev') != false && $value['6'] != '') {
						$input = '<p class="errormsg">'.$cms_lang['tpl_devmessage']."</p>\n".$input;
					}

					// Modulkonfiguration einlesen
					if ($cconfig) $tmp1 = preg_split("/&/", $cconfig[$key]);
					else $tmp1 = preg_split("/&/", $value['0']);
					$varstring = array();
					foreach ($tmp1 as $key1=>$value1) {
						$tmp2 = explode('=', $value1);
						foreach ($tmp2 as $key2=>$value2) $varstring["$tmp2[0]"]=$tmp2[1];
					}
					foreach ($varstring as $key3=>$value3) {
						$cms_mod['value'][$key3] = cms_stripslashes(urldecode($value3));
					}
					//TODO - remove dedi backward compatibility
					$dedi_mod =& $cms_mod;
					
					foreach ($value as $key4=>$value4) $cms_mod['info'][$key4] = cms_stripslashes(urldecode($value4));
					$input = str_replace("MOD_VAR", "C".$key."MOD_VAR" , $input);
					eval(' ?>'.$input);
					unset($cms_mod['value'], $dedi_mod['value']);
					echo "</td>\n";
					echo "    </tr></table>\n";
				}
			}
		}
	}
}

// Outputbuffering wieder aufnehmen
$temp_tpl_conf = ob_get_contents();
ob_end_clean();
ob_start();
echo $temp_output_for_backend;
unset($temp_output_for_backend);
$tpl->setCurrentBlock('__global__');
$tpl_data['SKIN'] = $cfg_cms['skin'];
if (empty($view)) {
	$tpl_data['FORM_ACTION'] = $sess->url("main.php?idside=$idside&idcat=$idcat&idsidelang=$idsidelang");
	$tpl_data['ABORT'] = $sess->url("main.php?area=con");
	$tpl_data['AREA_TITLE'] = $cms_lang['area_con_configside'];
} else {
	$tpl_data['FORM_ACTION'] = $sess->url("main.php?area=con_configside&idside=$idside&idcat=$idcat&idsidelang=$idsidelang&view=$view");
	$tpl_data['ABORT'] = $sess->url($cfg_client['htmlpath'].$cfg_client['contentfile']."?lang=$lang&idcatside=$idcatside&idcat=$idcat&view=$view");
	$tpl_data['AREA_TITLE'] = '';
}
//buttons    
$buttons  = "      <tr>\n";
$buttons .= "        <td class='content7' style='text-align:right' colspan='2'>\n";
$buttons .= "        <input type='submit' name='sf_save' title='".$cms_lang['gen_save_titletext']."' value='".$cms_lang['gen_save']."' class=\"sf_buttonAction\"/>\n";
$buttons .= "        <input type='submit' name='sf_apply' title='".$cms_lang['gen_apply_titletext']."' value='".$cms_lang['gen_apply']."' class=\"sf_buttonAction\"/>\n";
$buttons .= "        <input type='button' name='sf_cancel' title='".$cms_lang['gen_cancel_titletext']."' value='".$cms_lang['gen_cancel']."' class=\"sf_buttonActionCancel\" onclick=\"window.location='". $tpl_data['ABORT'] ."'\"/>\n";
$buttons .= "        </td>\n";
$buttons .= "      </tr>\n";

$tpl_data['BUTTONS_TOP'] = $tpl_data['BUTTONS_BOTTOM'] = $buttons;
if (! $have_config_tpl_perm && !$have_enter_tpl_perm && ! $have_meta_perm ) {
	$tpl_data['BUTTONS_BOTTOM'] = '';
}

$tpl_data['IDTPLCONF'] = $idtplconf;
$tpl_data['LASTMODIFIED'] = $lastmodified;
$tpl_data['AUTHOR'] = $author;
$tpl_data['CREATED'] = $created;
$tpl_data['IDCATSIDE'] = ($idcatside === NULL) ? '' : $idcatside;
$tpl_data['CON_SIDECONFIG'] = $cms_lang['con_sideconfig'];
$tpl_data['SIDE_TITLE_DESC'] = $cms_lang['con_title'];
$tpl_data['SIDE_TITLE'] = empty($title) ? '' : $title;
$tpl_data['SELECT_LOCK_SIDE'] = $select_lock_side;
$tpl_data['TPL_CONF'] = $temp_tpl_conf;
$tpl_data['FOOTER_LICENSE'] = $cms_lang['login_licence'];
$tpl->setVariable($tpl_data);
unset($tpl_data);
// Look for Errors
if (!empty($errno) || $sf_is_rewrite_error) {
	$tpl->setCurrentBlock('ERROR_BLOCK');
	if ($sf_is_rewrite_error) {
		$tpl_error['ERR_MSG'] = 'Bitte pr&uuml;fen Sie Ihre Formulareingaben';
	} else {
		$tpl_error['ERR_MSG'] = $cms_lang['err_' . $errno];
	}
	$tpl->setVariable($tpl_error);
	$tpl->parseCurrentBlock();
	unset($tpl_data);
}
?>
