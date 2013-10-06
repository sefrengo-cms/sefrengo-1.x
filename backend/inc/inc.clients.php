<?PHP
// File: $Id: inc.clients.php 28 2008-05-11 19:18:49Z mistral $
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
include_once('inc/fnc.clients.php');
include_once('inc/fnc.lang.php');
include_once('inc/fnc.tpl.php');
include_once('inc/fnc.con.php');


/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/
$perm->check('area_clients');
switch($action) 
{
	case 'save_new_client':  //neues Projekt
		$perm->check(1, 'area_clients', 0);
		clients_new_client($cid, $project_name, $newdesc, $newpath, $newurl, $with_dir, $newlang, $newlangdesc, $charset);
		if(empty($errno)){
			$collapse = $cid;
		}
		else{
			$action = 'new_project';
		}
		break;
	case 'save_edited_client':  //Projekt umbenennen
		$perm->check(3, 'clients', $cid);
		$errno = clients_rename_client($cid, $newname, $newdesc);
		break;
	case 'delete_client':  //Projekt löschen
		$perm->check(5, 'clients', $cid);
		$errno = clients_delete_client($cid);
		break;
	case 'save_new_lang':  // neue Sprache anlegen
		//collapse ist idclient
		$perm->check(18, 'clients', $collapse);
		$errno = lang_new_language($collapse, $newname, $newdesc, $charset, $_REQUEST['rewrite_key'], $_REQUEST['rewrite_mapping']);
		break;
	case 'save_edited_lang':  // Sprache umbenennen
		$perm->check(19, 'clientlangs', $idlang);
		$errno = lang_rename_language($idlang, $newname, $newdesc, $charset, $_REQUEST['rewrite_key'], $_REQUEST['rewrite_mapping']);
		break;
	case 'delete_lang':  // Sprache löschen
		//collapse ist idclient
		$perm->check(21, 'clientlangs', $idlang);
		$errno = lang_delete_language($collapse, $lid);
		break;
	case 'makestartlang':
		$errno = lang_make_start_lang((int)$_REQUEST['cid'], (int)$_REQUEST['lid']);
		break;
}
/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/


/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/
//
// GENERAL VALUES
//
$c_conf['url'] = $sess->url('main.php?area='.$area);
$c_conf['url_extern'] = $sess->url('main.php?collapse='. $collapse);
$c_conf['image_path'] = 'tpl/'.$cfg_cms['skin'].'/img/';
$c_conf['style_project'] = '#f7fbff';
$c_conf['style_project_over'] = '#fff7ce';
$c_conf['style_lang'] = '#ffffff';
$c_conf['style_lang_over'] = '#fff7ce';
$c_conf['html_image'] = '<img src="'. $c_conf['image_path'] .'%s" alt="%s" width="%s" height="%s" %s />';
$c_conf['html_link'] = '<a href="'. $c_conf['url'] .'&collapse='. $collapse .'%s" title="%s">%s</a>';
$c_conf['html_link_extern'] = '<a href="'. $c_conf['url_extern'] .'%s" title="%s">%s</a>';
$c_conf['html_link_new_project'] = '<a href="'. $c_conf['url'] .'&action=new_project" class="action" title="'.$cms_lang['clients_new_client'].'">'.$cms_lang['clients_new_client'].'</a>';
$c_conf['html_link_delete'] = '<a href="'. $c_conf['url'] .'&collapse='. $collapse .'%s" onclick="return delete_confirm()" title="%s">%s</a>';
$c_conf['html_link_collapse'] = '<a href="'. $c_conf['url'] .'&collapse=%s" title="%s">%s</a>';
$c_conf['html_formname'] = 'getit';
$c_conf['html_form'] = '<form action="main.php" name ="'. $c_conf['html_formname'] .'" method ="post">
<input type="hidden" name="area" value="'.$area.'" />'."\n".'
<input type="hidden" name="'. $sess-> name. '" value="'.$sess -> id.'" />'."\n";
$c_conf['html_field_hidden'] = '<input type="hidden" name="%s" value="%s" />'."\n";
$c_conf['html_field_text'] = '<input type="text" name="%s" value="%s" style="width:%spx" maxlength="%s" />'."\n";
$c_conf['html_field_submit'] = '<input type="image" class="toolinfo" src="'.$c_conf['image_path'].'but_confirm.gif" title="'.$cms_lang['clients_submit'].'" />';
$c_conf['iso_3166_codes'] = array(
								'af-ZA' => 'Afrikaans - South Africa',
								'sq-AL' => 'Albanian - Albania',
								'ar' => 'Arabic',
								'ar-DZ' => 'Arabic - Algeria',
								'ar-BH' => 'Arabic - Bahrain',
								'ar-EG' => 'Arabic - Egypt',
								'ar-IQ' => 'Arabic - Iraq',
								'ar-JO' => 'Arabic - Jordan',
								'ar-KW' => 'Arabic - Kuwait',
								'ar-LB' => 'Arabic - Lebanon',
								'ar-LY' => 'Arabic - Libya',
								'ar-MA' => 'Arabic - Morocco',
								'ar-OM' => 'Arabic - Oman',
								'ar-QA' => 'Arabic - Qatar',
								'ar-SA' => 'Arabic - Saudi Arabia',
								'ar-SY' => 'Arabic - Syria',
								'ar-TN' => 'Arabic - Tunisia',
								'ar-AE' => 'Arabic - United Arab Emirates',
								'ar-YE' => 'Arabic - Yemen',
								'hy' => 'Armenian',
								'hy-AM' => 'Armenian - Armenia',
								'eu-ES' => 'Basque - Basque',
								'be-BY' => 'Belarusian - Belarus',
								'bg-BG' => 'Bulgarian - Bulgaria',
								'ca-ES' => 'Catalan - Catalan',
								'zh' => 'Chinese',
								'zh-CN' => 'Chinese - China',
								'zh-HK' => 'Chinese - Hong Kong',
								'zh-MO' => 'Chinese - Macau',
								'zh-SG' => 'Chinese - Singapore',
								'zh-TW' => 'Chinese - Taiwan',
								'zh-CHS' => 'Chinese (Simplified)',	 
								'zh-CHT' => 'Chinese (Traditional)',
								'hr-HR' => 'Croatian - Croatia',
								'cs-CZ' => 'Czech - Czech Republic',
								'da-DK' => 'Danish - Denmark',
								'div-MV' => 'Dhivehi - Maldives',
								'nl-BE' => 'Dutch - Belgium',
								'nl-NL' => 'Dutch - The Netherlands',
								'en' => 'English',
								'en-AU' => 'English - Australia',
								'en-BZ' => 'English - Belize',
								'en-CA' => 'English - Canada',
								'en-CB' => 'English - Caribbean',
								'en-IE' => 'English - Ireland',
								'en-JM' => 'English - Jamaica',
								'en-NZ' => 'English - New Zealand',
								'en-PH' => 'English - Philippines',
								'en-ZA' => 'English - South Africa',
								'en-TT' => 'English - Trinidad and Tobago',
								'en-GB' => 'English - United Kingdom',
								'en-US' => 'English - United States',
								'en-ZW' => 'English - Zimbabwe',
								'et-EE' => 'Estonian - Estonia',
								'fo-FO' => 'Faroese - Faroe Islands',
								'fa-IR' => 'Farsi - Iran',
								'fi-FI' => 'Finnish - Finland',
								'fr' => 'French',
								'fr-BE' => 'French - Belgium',
								'fr-CA' => 'French - Canada',
								'fr-FR' => 'French - France',
								'fr-LU' => 'French - Luxembourg',
								'fr-MC' => 'French - Monaco',
								'fr-CH' => 'French - Switzerland',
								'gl-ES' => 'Galician - Galician',
								'ka-GE' => 'Georgian - Georgia',
								'de' => 'German',
								'de-AT' => 'German - Austria',
								'de-DE' => 'German - Germany',
								'de-LI' => 'German - Liechtenstein',
								'de-LU' => 'German - Luxembourg',
								'de-CH' => 'German - Switzerland',
								'el-GR' => 'Greek - Greece',
								'gu-IN' => 'Gujarati - India',
								'he-IL' => 'Hebrew - Israel',
								'hi-IN' => 'Hindi - India',
								'hu-HU' => 'Hungarian - Hungary',
								'is-IS' => 'Icelandic - Iceland',
								'id-ID' => 'Indonesian - Indonesia',
								'it-IT' => 'Italian - Italy',
								'it-CH' => 'Italian - Switzerland',
								'ja-JP' => 'Japanese - Japan',
								'kn-IN' => 'Kannada - India',
								'kk-KZ' => 'Kazakh - Kazakhstan',
								'kok-IN' => 'Konkani - India',
								'ko-KR' => 'Korean - Korea',
								'ky-KZ' => 'Kyrgyz - Kazakhstan',
								'lv-LV' => 'Latvian - Latvia',
								'lt-LT' => 'Lithuanian - Lithuania',
								'mk-MK' => 'Macedonian (FYROM)',
								'ms' => 'Malay',
								'ms-BN' => 'Malay - Brunei',
								'ms-MY' => 'Malay - Malaysia',
								'mr-IN' => 'Marathi - India',
								'mn-MN' => 'Mongolian - Mongolia',
								'nb-NO' => 'Norwegian (Bokm.)',
								'nn-NO' => 'Norwegian (Nynorsk)',
								'pl-PL' => 'Polish - Poland',
								'pt' => 'Portuguese',
								'pt-BR' => 'Portuguese - Brazil',
								'pt-PT' => 'Portuguese - Portugal',
								'pa-IN' => 'Punjabi - India',
								'ro-RO' => 'Romanian - Romania',
								'ru-RU' => 'Russian - Russia',
								'sa-IN' => 'Sanskrit - India',
								'Cy-sr-SP' => 'Serbian (Cyrillic) - Serbia',
								'Lt-sr-SP' => 'Serbian (Latin) - Serbia',
								'sk' => 'Slovak - Slovakia',
								'sl' => 'Slovenian - Slovenia',
								'es' => 'Spanish',
								'es-AR' => 'Spanish - Argentina',
								'es-BO' => 'Spanish - Bolivia',
								'es-CL' => 'Spanish - Chile',
								'es-CO' => 'Spanish - Colombia',
								'es-CR' => 'Spanish - Costa Rica',
								'es-DO' => 'Spanish - Dominican Republic',
								'es-EC' => 'Spanish - Ecuador',
								'es-SV' => 'Spanish - El Salvador',
								'es-GT' => 'Spanish - Guatemala',
								'es-HN' => 'Spanish - Honduras',
								'es-MX' => 'Spanish - Mexico',
								'es-NI' => 'Spanish - Nicaragua',
								'es-PA' => 'Spanish - Panama',
								'es-PY' => 'Spanish - Paraguay',
								'es-PE' => 'Spanish - Peru',
								'es-PR' => 'Spanish - Puerto Rico',
								'es-ES' => 'Spanish - Spain',
								'es-UY' => 'Spanish - Uruguay',
								'es-VE' => 'Spanish - Venezuela',
								'sw-KE' => 'Swahili - Kenya',
								'sv' => 'Swedish',
								'sv-FI' => 'Swedish - Finland',
								'sv-SE' => 'Swedish - Sweden',
								'syr-SY' => 'Syriac - Syria',
								'ta-IN' => 'Tamil - India',
								'tt-RU' => 'Tatar - Russia',
								'te-IN' => 'Telugu - India',
								'th-TH' => 'Thai - Thailand',
								'tr-TR' => 'Turkish - Turkey',
								'uk-UA' => 'Ukrainian - Ukraine',
								'ur-PK' => 'Urdu - Pakistan',
								'Cy-uz-UZ' => 'Uzbek (Cyrillic) - Uzbekistan',
								'Lt-uz-UZ' => 'Uzbek (Latin) - Uzbekistan',
								'vi-VN' => 'Vietnamese - Vietnam'
								);

//Throw out cms-header
include('inc/inc.header.php');
//Load templatefile
$tpl->loadTemplatefile('clients.tpl');

//
// START GENERATE HEADER BLOCK
// 
if(! empty($errno)){
	$tpl_data['ERR_MSG'] = $cms_lang['err_' . $errno];
}
if(! empty($user_msg)){
	$tpl_data['USER_MSG'] = $cms_lang[$user_msg];
}
$tpl_data['AREA_TITLE'] = $cms_lang['clients_clients'];
$tpl_data['SUB_NAV_RIGHT'] = ( $perm->have_perm(2, 'area_clients', 1) ) ? $c_conf['html_link_new_project']: '';
$tpl_data['TABLE_PADDING'] = $cellpadding;
$tpl_data['TABLE_SPACING'] = $cellspacing;
$tpl_data['TABLE_BORDER'] = $border;
$tpl_data['TITLE'] = $cms_lang['clients_headline'];
$tpl_data['DESCRIPTION'] = $cms_lang['clients_desc'];
$tpl_data['ACTIONS'] = $cms_lang['clients_actions'];
$tpl_data['FOOTER_LICENSE'] = $cms_lang['login_licence'];
//Look for Errors 
if(! empty($errno)){
	$tpl_error['ERR_MSG'] = $cms_lang['err_' . $errno];
}

$tpl -> setCurrentBlock('HEADER');
$tpl->setVariable($tpl_data);
$tpl->parseCurrentBlock();
unset($tpl_data);
//
// END GENERATE HEADER BLOCK
//

function clients_get_langrow($idclient, $idlang, $client_array, $c_conf)
{
	global $cms_lang, $perm;
	
	$tpl_data['ENTRY_ICON'] = make_image('but_lang.gif', false, '16', '16', false, 'class="icon"');
	$tpl_data['ENTRY_NAME'] = $client_array[$idclient]['langs'][$idlang]['name'];
	$tpl_data['ENTRY_DESCRIPTION'] = $client_array[$idclient]['langs'][$idlang]['desc'];
	$tpl_data['ENTRY_BGCOLOR'] = $c_conf['style_lang'];
	$tpl_data['OVERENTRY_BGCOLOR'] = $c_conf['style_lang_over'];
	
	//starlang
	if($perm->have_perm( 28, 'clientlangs', $idlang) ){
		if ($client_array[$idclient]['langs'][$idlang]['is_start'] == 1) {
			$tpl_data['ENTRY_STARTLANG'] = clients_get_imagelink('but_start_yes.gif', 'action=makestartlang&cid='.$idclient.'&lid='.$idlang, 'globale Startsprache setzen', 16, 16);
		} else {
			$tpl_data['ENTRY_STARTLANG'] = clients_get_imagelink('but_start_no.gif', 'action=makestartlang&cid='.$idclient.'&lid='.$idlang, 'globale Startsprache setzen', 16, 16);
		}
	}
	
	if($perm->have_perm(19, 'clientlangs', $idlang) ){
		$tpl_data['ENTRY_EDIT'] = clients_get_imagelink('but_edit.gif', 'action=editlang&lid='.$idlang, $cms_lang['clients_lang_edit'], 16, 16);
	}
	
	$tpl_data['ENTRY_CONF_LANG'] = clients_get_imagelinkextern('but_config.gif', 'area=clients_config_lang&cid='.$idclient.'&lid='.$idlang, 'Sprache konfigurieren', 16, 16);
	
	//If there is more then one lang, you can delete them
	if($client_array['num_langs'] > 1){
		if($perm->have_perm(21, 'clientlangs', $idlang) ){
			$tpl_data['ENTRY_DELETE'] = clients_get_imagedeletelink('but_delete.gif', 'action=delete_lang&lid='.$idlang, $cms_lang['clients_lang_delete'], 16, 16);
		}
	}
	
	return $tpl_data;
}

function clients_get_langeditrow($idclient, $idlang, $client_array, $c_conf)
{	
	global $cms_lang,$perm;
	
	$tpl_data['FORM_START']  = $c_conf['html_form'] . sprintf($c_conf['html_field_hidden'], 'action', 'save_edited_lang');
	$tpl_data['FORM_START'] .= sprintf($c_conf['html_field_hidden'], 'idlang', $idlang);
	$tpl_data['FORM_START'] .= sprintf($c_conf['html_field_hidden'], 'collapse', $idclient);
	$tpl_data['ENTRY_TITLEFIELD'] = sprintf($c_conf['html_field_text'], 'newname', $client_array[$idclient]['langs'][$idlang]['name'], 150, 70);
	$tpl_data['LANG_DESCFIELD'] = 'Beschreibung: ';
	$tpl_data['ENTRY_DESCFIELD'] = sprintf($c_conf['html_field_text'], 'newdesc', $client_array[$idclient]['langs'][$idlang]['desc'], 300, 150);
	$tpl_data['LANG_CHARSET'] = 'Sprachcodierung: ';
	$tpl_data['ENTRY_CHARSET'] = clients_get_charset_selectbox($client_array[$idclient]['langs'][$idlang]['charset']);
	$tpl_data['LANG_ISO_3166'] = 'Automatische Standardsprache (Browserabh&auml;ngig): ';
	$tpl_data['LANG_REWRITE_KEY'] = 'Kurzzeichen f&uuml;r URL- Rewrite: ';
	$tpl_data['ENTRY_REWRITE_KEY'] = sprintf($c_conf['html_field_text'], 'rewrite_key', $client_array[$idclient]['langs'][$idlang]['rewrite_key'], 150, 70);
	$tpl_data['LANG_REWRITE_MAPPING'] = 'URL- Filter f&uuml;r URL- Rewrite: ';
	
	//make iso select
	$my_iso = array(''=>'--');
	foreach ($c_conf['iso_3166_codes'] AS $k=>$v) {
		$my_iso[$k] = $v .' ('.$k.')';
	}

	$tpl_data['ENTRY_ISO_3166'] = clients_get_select('iso_3166', $my_iso, $client_array[$idclient]['langs'][$idlang]['iso_3166_code']);
	
	//make mapping select
	$my_mapping = array('standard'=>'Standard');
	foreach ($c_conf['iso_3166_codes'] AS $k=>$v) {
		$my_mapping[$k] = $v .' ('.$k.')';
	}

	$tpl_data['ENTRY_REWRITE_MAPPING'] = clients_get_select('rewrite_mapping', $my_mapping, $client_array[$idclient]['langs'][$idlang]['rewrite_mapping']);

	if($perm->have_perm(22, 'clientlangs', $idlang) ){
		$panel = $perm->get_right_panel('clientlangs', $idlang, array( 'formname'=> $c_conf['html_formname'] ), 'img' );
		if (!empty($panel)) {
			$tpl_data['ENTRY_RIGHTS'] =  implode("", $panel);
		}
	}
	
	$tpl_data['ENTRY_SUBMIT'] = $c_conf['html_field_submit'];
	$tpl_data['ENTRY_CANCEL'] = clients_get_imagelink('but_cancel_delete.gif', '', $cms_lang['clients_abort'], 16, 16);
	
	$tpl_data['ENTRY_BGCOLOR'] = $c_conf['style_lang'];
	$tpl_data['OVERENTRY_BGCOLOR'] = $c_conf['style_lang'];

	return $tpl_data;
}

function clients_get_charset_selectbox($selected_charset = '')
{
	global $cms_lang;
	$my_charsets = array('utf-8' => 'utf-8', 'iso-8859-1' => 'iso-8859-1');
	
	return clients_get_select('charset', $my_charsets, $selected_charset);
}

function clients_get_select($fieldname, $element_array, $selected) {
	$out = '<select name="'.$fieldname.'" size="1">' ."\n";
	
	foreach($element_array AS $k=>$v)
	{
		$sel = ($k == $selected) ? 'selected': '';
		$out .= sprintf('  <option value="%s"  %s>%s</option>'. "\n", $k, $sel, $v);
	}
	
	$out .= '</select>' ."\n";
	
	return $out;
}


function clients_get_langnewrow($idclient, $client_array, $c_conf)
{	
	global $cms_lang;
	
	$tpl_data['FORM_START']  = $c_conf['html_form'] . sprintf($c_conf['html_field_hidden'], 'action', 'save_new_lang');
	$tpl_data['FORM_START'] .= sprintf($c_conf['html_field_hidden'], 'collapse', $idclient);
	$tpl_data['ENTRY_TITLEFIELD'] = sprintf($c_conf['html_field_text'], 'newname', $cms_lang['clients_lang_new'], 150, 70);
	$tpl_data['LANG_DESCFIELD'] = $cms_lang['clients_desc'] .': ';
	$tpl_data['ENTRY_DESCFIELD'] = sprintf($c_conf['html_field_text'], 'newdesc', '', 350, 150);
	$tpl_data['LANG_CHARSET'] = $cms_lang['clients_charset'] .': ';
	$tpl_data['ENTRY_CHARSET'] = clients_get_charset_selectbox();
	
	$tpl_data['ENTRY_SUBMIT'] = $c_conf['html_field_submit'];
	$tpl_data['ENTRY_CANCEL'] = clients_get_imagelink('but_cancel_delete.gif', '', $cms_lang['clients_abort'], 16, 16);
	
	$tpl_data['ENTRY_BGCOLOR'] = $c_conf['style_lang'];
	$tpl_data['OVERENTRY_BGCOLOR'] = $c_conf['style_lang'];

	return $tpl_data;
}


function clients_get_projectrow($idclient, $client_array, $collapse, $c_conf)
{
	global $cms_lang, $perm;
	
	//make collapse/ expand 
	if($client_array[$idclient]['have_childs']){
		if($collapse == $idclient) $lin = clients_get_collapselink('but_minus.gif', $cms_lang['clients_collapse'], 0);
		else $lin = clients_get_collapselink('but_plus.gif', $cms_lang['clients_expand'], $idclient);
	}
	else $lin = sprintf($c_conf['html_image'], 'space.gif', '', '', 16, 16, '');
	
	$tpl_data['PR_ENTRY_EXPANDER'] = $lin;
	$tpl_data['PR_ENTRY_NAME'] = htmlentities($client_array[$idclient]['name'], ENT_COMPAT, 'UTF-8');
	$tpl_data['PR_ENTRY_ICON'] = make_image('but_project.gif', '', '16', '16', false, ' class="icon" ');
	$tpl_data['PR_ENTRY_DESCRIPTION'] = htmlentities($client_array[$idclient]['desc'], ENT_COMPAT, 'UTF-8');
	$tpl_data['PR_ENTRY_BGCOLOR'] = $c_conf['style_project'];
	$tpl_data['PR_OVERENTRY_BGCOLOR'] = $c_conf['style_project_over'];
	if($perm->have_perm(18, 'clients', $idclient) ){
		$tpl_data['PR_LANG_NEW'] = clients_get_imagelink('but_newlang.gif', 'action=newlang&cid='.$idclient, $cms_lang['clients_make_new_lang'], 16, 16);
	}
	if($perm->have_perm(3, 'clients', $idclient) ){
		$tpl_data['PR_ENTRY_EDIT'] = clients_get_imagelink('but_edit.gif', 'action=editclient&cid='.$idclient, $cms_lang['clients_modify'], 16, 16);
	}
	if($perm->have_perm(4, 'clients', $idclient) ){
		$tpl_data['PR_ENTRY_CONF'] = clients_get_imagelinkextern('but_config.gif', 'area=clients_config&cid='.$idclient, $cms_lang['clients_config'], 16, 16);
	}
	if($client_array['num_clients'] > 1){
		if($perm->have_perm(5, 'clients', $idclient) ){
			$tpl_data['PR_ENTRY_DELETE'] = clients_get_imagedeletelink('but_delete.gif', 'action=delete_client&cid='.$idclient, $cms_lang['clients_delete'], 16, 16);
		}
	}
	else $tpl_data['PR_ENTRY_DELETE'] = '';
	
	return $tpl_data;
}


function clients_get_projecteditrow($idclient, $client_array, $collapse, $c_conf)
{
	global $cms_lang, $perm;
	
	//make collapse/ expand 
	if($client_array[$idclient]['have_childs']){
		if($collapse == $idclient) $lin = clients_get_collapselink('but_minus.gif', $cms_lang['clients_collapse'], 0);
		else $lin = clients_get_collapselink('but_plus.gif', $cms_lang['clients_expand'], $idclient);
	}
	else $lin = sprintf($c_conf['html_image'], 'space.gif', '', '', 16, 16, '');
	
	$tpl_data['PR_ENTRY_EXPANDER'] = $lin;
	$tpl_data['PR_ENTRY_BGCOLOR'] = $c_conf['style_project'];
	$tpl_data['PR_OVERENTRY_BGCOLOR'] = $c_conf['style_project_over'];

	$tpl_data['PR_FORM_START']  = $c_conf['html_form'] . sprintf($c_conf['html_field_hidden'], 'action', 'save_edited_client');
	$tpl_data['PR_FORM_START'] .= sprintf($c_conf['html_field_hidden'], 'cid', $idclient);
	$tpl_data['PR_FORM_START'] .= sprintf($c_conf['html_field_hidden'], 'collapse', $collapse);
	$tpl_data['PR_ENTRY_TITLEFIELD'] = sprintf($c_conf['html_field_text'], 'newname', htmlentities($client_array[$idclient]['name'], ENT_COMPAT, 'UTF-8'), 150, 70);
	$tpl_data['PR_ENTRY_DESCFIELD'] = sprintf($c_conf['html_field_text'], 'newdesc', htmlentities($client_array[$idclient]['desc'], ENT_COMPAT, 'UTF-8'), 350, 150);

	if($perm->have_perm(6, 'clients', $idclient) ){
		$panel = $perm->get_right_panel('clients', $idclient, array( 'formname'=> $c_conf['html_formname'] ), 'img' );
		if (!empty($panel)) {
			$tpl_data['PR_ENTRY_RIGHTS'] = implode("", $panel);
		}
	}
	
	$tpl_data['PR_ENTRY_SUBMIT'] = $c_conf['html_field_submit'];
	$tpl_data['PR_ENTRY_CANCEL'] = clients_get_imagelink('but_cancel_delete.gif', '', $cms_lang['clients_abort'], 16, 16);
		
	return $tpl_data;
}

function clients_get_projectnewrow($c_conf)
{	
	global $cms_lang;
	
	global $db, $cms_db, $cfg_client; 
	global $project_name, $newdesc, $newpath, $newurl, $with_dir, $newlang, $newlangdesc, $charset; 
	
	$sql = "SELECT MAX(idclient) AS max FROM ". $cms_db['clients'];
	$db->query($sql);
	$db->next_record();
	$nextclient = $db->f('max') + 1;
	$project_name = ($project_name == '') ? $nextclient . '. '. $cms_lang['clients_client']: $project_name;
	$newpath = ($newpath == '') ? strrev(strstr(strrev(substr($cfg_client['path'],0,-1)),'/'))."projekt0".$nextclient."/" : $newpath;
	$newurl = ($newurl == '') ? strrev(strstr(strrev(substr($cfg_client['htmlpath'],0,-1)),'/'))."projekt0".$nextclient."/" : $newurl;

	$tpl_data['PR_ENTRY_BGCOLOR'] = $c_conf['style_project'];
	$tpl_data['PR_OVERENTRY_BGCOLOR'] = $c_conf['style_project_over'];

	$tpl_data['PRN_FORM_START']  = $c_conf['html_form'] . sprintf($c_conf['html_field_hidden'], 'action', 'save_new_client');
	$tpl_data['PRN_FORM_START']  .= sprintf($c_conf['html_field_hidden'], 'cid', $nextclient);
	$tpl_data['PRN_ENTRY_TITLEFIELD'] = sprintf($c_conf['html_field_text'], 'project_name', htmlentities($project_name, ENT_COMPAT, 'UTF-8'), 150, 70);

	$tpl_data['PRN_DESC'] = $cms_lang['clients_client_desc'] .': ';
	$tpl_data['PRN_DESCFIELD'] = sprintf($c_conf['html_field_text'], 'newdesc', htmlentities($newdesc, ENT_COMPAT, 'UTF-8'), 350, 150);
	$tpl_data['PRN_PATH'] = $cms_lang['clients_client_path'] .': ';
	$tpl_data['PRN_PATHFIELD'] = sprintf($c_conf['html_field_text'], 'newpath', htmlentities($newpath, ENT_COMPAT, 'UTF-8'), 350, 150);
	$tpl_data['PRN_URL'] = $cms_lang['clients_client_url'] .': ';
	$tpl_data['PRN_URLFIELD'] = sprintf($c_conf['html_field_text'], 'newurl', htmlentities($newurl, ENT_COMPAT, 'UTF-8'), 350, 150);
	$tpl_data['PRN_WITH_DIR'] = $cms_lang['clients_client_directory'] .': ';
	$tpl_data['PRN_WITH_DIRFIELD'] = '<input class="withdir" type="checkbox" name="with_dir" value="1" checked="checked" />';
	$tpl_data['PRN_LANG'] = $cms_lang['clients_client_start_lang'] .': ';
	$tpl_data['PRN_LANGFIELD'] = sprintf($c_conf['html_field_text'], 'newlang', htmlentities($newlang, ENT_COMPAT, 'UTF-8'), 350, 150);
	$tpl_data['PRN_LANG_DESC'] = $cms_lang['clients_lang_desc'] .': ';
	$tpl_data['PRN_LANG_DESCFIELD'] = sprintf($c_conf['html_field_text'], 'newlangdesc', htmlentities($newlangdesc, ENT_COMPAT, 'UTF-8'), 350, 150);
	$tpl_data['PRN_LANG_CHARSET'] = $cms_lang['clients_lang_charset'] .': ';
	$tpl_data['PRN_CHARSETFIELD'] = clients_get_charset_selectbox($charset);
	
	$tpl_data['PRN_ENTRY_SUBMIT'] = $c_conf['html_field_submit'];
	$tpl_data['PRN_ENTRY_CANCEL'] = clients_get_imagelink('but_cancel_delete.gif', '', 'Abbrechen', 16, 16);
		
	return $tpl_data;
}




function clients_get_collapselink($button, $desc, $idclient)
{
	global $c_conf;

	$im = sprintf($c_conf['html_image'], $button, $desc, 16, 16, 'class="icon"');
	$lin = sprintf($c_conf['html_link_collapse'] , $idclient, $desc, $im);
	
	return $lin;
}

function clients_get_imagelink($button, $urlparms, $desc, $width = 16, $height= 16)
{
	global $c_conf;

	$im = sprintf($c_conf['html_image'], $button, $desc, $width, $height, '');
	$lin = sprintf($c_conf['html_link'] , '&'.$urlparms, $desc, $im);
	
	return $lin;
}

function clients_get_imagelinkextern($button, $urlparms, $desc, $width = 16, $height= 16)
{
	global $c_conf;

	$im = sprintf($c_conf['html_image'], $button, $desc, $width, $height, '');
	$lin = sprintf($c_conf['html_link_extern'] , '&'.$urlparms, $desc, $im);
	
	return $lin;
}


function clients_get_imagedeletelink($button, $urlparms, $desc, $width = 16, $height= 16)
{
	global $c_conf;

	$im = sprintf($c_conf['html_image'], $button, $desc, $width, $height, '');
	$lin = sprintf($c_conf['html_link_delete'] , '&'.$urlparms, $desc, $im);
	
	return $lin;
}


//get all necessary projectdata info
$client_array = clients_get_clients();


//new project
if($action == 'new_project'){
	$perm->check(2, 'area_clients', 0);
	$tpl_data = clients_get_projectnewrow($c_conf);
	$tpl -> setCurrentBlock('PROJECTNEW');
	$tpl->setVariable($tpl_data);
	$tpl->parseCurrentBlock();
	$tpl -> setCurrentBlock('ENTRY');
	$tpl->parseCurrentBlock();

	unset($tpl_data);		
}

if(is_array($client_array['order'])){
	foreach ($client_array['order'] AS $idclient)
	{
		
		if(! $perm->have_perm(1, 'clients', $idclient)) continue;
		//new language
		if($action == 'newlang' && $idclient == $cid && $is_checked != true){
			$perm->check(18, 'clients', $cid);
			$is_checked = true;
			$collapse = $idclient;
			$client_array[$idclient]['have_childs'] = true;
			$tpl_data = clients_get_langnewrow($idclient, $client_array, $c_conf);
			$tpl->setCurrentBlock('LANGEDIT');
			$tpl->setVariable($tpl_data);
			$tpl->setCurrentBlock('LANG');
			$tpl->parseCurrentBlock();
			unset($tpl_data);
		}
		
		//echo $client_array[$idclient]['name'];echo "<br><br>";
		if($collapse == $idclient && $client_array[$idclient]['have_childs'] && is_array($client_array[$idclient]['langs']['order']) ){
	
			foreach ($client_array[$idclient]['langs']['order'] AS $idlang)
			{
				if(! $perm->have_perm(17, 'clientlangs', $idlang)) continue;
				
				//edit language
				if($action == 'editlang' && $idlang == $lid){
					$perm->check(19, 'clientlangs', $lid);
					$tpl_data = clients_get_langeditrow($idclient, $idlang, $client_array, $c_conf);
					$tpl->setCurrentBlock('LANGEDIT');
				}
				//show langrow
				else{
					$tpl_data = clients_get_langrow($idclient, $idlang, $client_array, $c_conf);
					$tpl->setCurrentBlock('LANGSHOW');
				}
				$tpl->setVariable($tpl_data);
				$tpl->setCurrentBlock('LANG');
				$tpl->parseCurrentBlock();
				unset($tpl_data);
			}
		}
	
		if($action == 'editclient' && $idclient == $cid){
			$perm->check(3, 'clients', $cid);
			$tpl_data = clients_get_projecteditrow($idclient, $client_array, $collapse, $c_conf);
			$tpl -> setCurrentBlock('PROJECTEDIT');
			$tpl->setVariable($tpl_data);
			$tpl->parseCurrentBlock();
	
			unset($tpl_data);		
		}
		else{
			$tpl_data = clients_get_projectrow($idclient, $client_array, $collapse, $c_conf);
			$tpl -> setCurrentBlock('PROJECTSHOW');
			$tpl->setVariable($tpl_data);
			$tpl->parseCurrentBlock();
			unset($tpl_data);
		}
	
		$tpl -> setCurrentBlock('ENTRY');
		$tpl->parseCurrentBlock();
	
	}
}



?>
