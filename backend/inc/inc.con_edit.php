<?PHP
// File: $Id: inc.con_edit.php 57 2008-08-11 16:26:04Z bjoern $
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
// + Revision: $Revision: 57 $
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



//mehrere Elementa auf einmal speichern
if ($action == 'savex' && $data != '') {
	// Event
	fire_event('con_edit', array('path' => $cms_path,'idcatside' => $idcatside));
    // Delete Content Cache
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));

	$sf_wr =& $GLOBALS['sf_factory']->getObject('HTTP', 'WebRequest');
	$data = $sf_wr->getVal('data'); 

	$array1 = explode('*x||*',substr($data,0,-5));
	foreach($array1 as $value)
	{
		$array2 = explode('*x|*',$value);
		con_edit_save_content($con_side[$idcatside]['idsidelang'], $array2[0], $array2[1], $array2[2], $array2[3], $array2[4]);
		$sql = "SELECT
					*
				FROM
					$cms_db[content]
				WHERE
				idsidelang='".$con_side[$idcatside]['idsidelang']."'
				AND container='".$array2[0]."'
				AND number='".$array2[1]."'";
		$db->query($sql);
		if (!$db->affected_rows()) {
			$sql = "UPDATE
						$cms_db[content]
					SET number=number-1
					WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."'
					AND container='".$array2[0]."'
					AND number>'".$array2[1]."'";
			$db->query($sql);
		}
	}
}

// Content speichern
if ($action == 'save' || $action == 'saveedit') {
	// Event
	fire_event('con_edit', array('path' => $cms_path, 'idcatside' => $idcatside, 'content' => $content));
    // Delete Content Cache
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
	// Content-Array aufbauen
	$con_content = explode (';', $content);
	if ($action == 'saveedit') {
	    $oldContent = $content;
	}
	unset($content);
	
	$sf_wr =& $GLOBALS['sf_factory']->getObject('HTTP', 'WebRequest');
	
	foreach ($con_content as $value) {
		$con_config = explode ('.', $value);
		$con_container = $con_config['0'];

		// Modul verschieben
		if (is_numeric($entry)) {
			$sql = "UPDATE $cms_db[content] SET number=number+1 WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number>'$entry'";
			$db->query($sql);
		}
		$con_contnbr = explode (',', $con_config[1]);
		$con_content_type = explode (',', $con_config[2]);
		foreach ($con_contnbr as $con_containernumber) {
			foreach ($con_content_type as $value3) {
				$value3 = explode ('-', $value3);
				$con_contype = $value3['0'];
				$con_typenumber = $value3['1'];
				$content = $sf_wr->getVal('content_'.$con_container.'_'.$con_containernumber.'_'.$con_contype.'_'.$con_typenumber);
				if (is_array($content)) {
					if ($con_contype == '14') {
						// content type select
						$content = implode("\n",$content);
                    } elseif ($con_contype == '18') {
                        // content type datetime
                        if (!array_key_exists(0,$content)) {
                            // Hour
                            if (array_key_exists('h',$content)) {
                                $content['H'] = $content['h'];
                                if (array_key_exists('a',$content)) {
                                    if ($content['H'] == "12") $content['H'] = "0"; 
                                    if ($content['a']=="pm") $content['H'] = $content['H'] + 12;
                                } else {
                                    if (array_key_exists('A',$content)) {
                                        if ($content['h'] == "12") $content['H'] = "0"; 
                                        if ($content['A']=="PM") $content['H'] = $content['H'] + 12;
                                    } 
                                }
                            }
                            if (!array_key_exists('H',$content)) {
                                $content['H'] = 12;
                            }
                            // Day
                            if (!array_key_exists('d',$content)) {
                                $content['d'] = 1;
                            }
                            // Months
                            if (!array_key_exists('m',$content)) {
                                $content['m'] = 1;
                            }
                            if (array_key_exists('M',$content)) {
                                $content['m'] = $content['M'];
                            }
                            if (array_key_exists('F',$content)) {
                                $content['m'] = $content['F'];
                            }
                            // Year
                            if (!array_key_exists('y',$content)) {
                                $content['y'] = 2000;
                            }
                            if (array_key_exists('Y',$content)) {
                                $content['y'] = $content['Y'];
                            }
                            $content = mktime($content['H'],$content['i'],0,$content['m'], $content['d'], $content['y']);
                        } else {
                            // zerlege übergabetexte
                            $arr_date = @explode('.', $content[1]);
                            // prüfe ob felder vorhanden, dann erzeuge datum
                            if (!($arr_date[1] && $arr_date[0] && $arr_date[2])) {
                                $arr_date[0] = 1;
                                $arr_date[1] = 1;
                                $arr_date[2] = 2000;
                            }
                            $content = mktime(12,0,0,$arr_date[1], $arr_date[0], $arr_date[2]);
                        }
                    }
                }

				// neue Modul-ID vergeben
				if (is_numeric($entry)) $new_containernumber = $entry+1;
                                 else $new_containernumber = $con_containernumber;
				con_edit_save_content($con_side[$idcatside]['idsidelang'], $con_container, $new_containernumber, $con_contype, $con_typenumber, $content);
				unset($content);
			}

			// Modulverdopplung minimieren, wenn Content leer ist.
			$sql = "SELECT * FROM $cms_db[content] WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number='$new_containernumber'";
			$db->query($sql);
			if (!$db->affected_rows()) {
                                	$sql = "UPDATE $cms_db[content] SET number=number-1 WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number>'$new_containernumber'";
				$db->query($sql);
			}
		}

		// Falls nichts eingetragen wurde Eintrag zurückverschieben
		if (is_numeric($entry)) {
			$sql = "SELECT * FROM $cms_db[content] WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number='".($entry+1)."'";
			$db->query($sql);
			if (!$db->affected_rows()) {
				$sql = "UPDATE $cms_db[content] SET number=number-1 WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number>'$entry'";
				$db->query($sql);
			}
                 }
	}
	if ($action == 'saveedit') {
	    $content = $oldContent;
	    unset($oldContent);
	}
}

// Content löschen
if ($action == 'delete') {
	$con_content = explode (';', $content);
	unset($content);
	// Delete Content Cache
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
	foreach ($con_content as $value) {
		$con_config = explode ('.', $value);
		$con_container = $con_config['0'];
		$con_contnbr = $con_config['1'];

		// Einträge löschen
		$sql = "DELETE FROM $cms_db[content] WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number='$con_contnbr'";
		$db->query($sql);

		// Modul verschieben
		$sql = "UPDATE $cms_db[content] SET number=number-1 WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number>'$con_contnbr'";
		$db->query($sql);

		// Änderungsdatum aktualisieren
		$sql = "UPDATE $cms_db[side_lang] SET lastmodified='".time()."', author='".$auth->auth['uid']."' WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."'";
		$db->query($sql);

		// Seitenkopien suchen
		$sql = "SELECT idcatside FROM $cms_db[side_lang] A LEFT JOIN $cms_db[cat_side] B USING(idside) WHERE A.idsidelang='".$con_side[$idcatside]['idsidelang']."'";
		$db->query($sql);
		while ($db->next_record()) $list[] = $db->f('idcatside');

		// Status der Seite auf geändert stellen
		change_code_status($list, '1', 'idcatside');
		sf_header_redirect($con_side[$idcatside]['link'], true);
	}
}

// Modul nach oben verschieben
if ($action == 'move_up') {
	$con_content = explode (';', $content);
	// Delete Content Cache
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
	unset($content);
	foreach ($con_content as $value) {
		$con_config = explode ('.', $value);
		$con_container = $con_config['0'];
		$entry = $con_config['1'];
		$sql = "UPDATE $cms_db[content] SET number='-1' WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number='$entry'";
		$db->query($sql);
		$sql = "UPDATE $cms_db[content] SET number=number+1 WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number='".($entry-1)."'";
		$db->query($sql);
		$sql = "UPDATE $cms_db[content] SET number='".($entry-1)."' WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number='-1'";
		$db->query($sql);
	}
	// Änderungsdatum aktualisieren
	$sql = "UPDATE $cms_db[side_lang] SET lastmodified='".time()."', author='".$auth->auth['uid']."' WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."'";
	$db->query($sql);
	// Seitenkopien suchen
	$sql = "SELECT idcatside FROM $cms_db[side_lang] A LEFT JOIN $cms_db[cat_side] B USING(idside) WHERE A.idsidelang='".$con_side[$idcatside]['idsidelang']."'";
	$db->query($sql);
	while ($db->next_record()) $list[] = $db->f('idcatside');

	// Status der Seite auf geändert stellen
	change_code_status($list, '1', 'idcatside');
	sf_header_redirect($con_side[$idcatside]['link'], true);
}

// Modul nach unten verschieben
if ($action == 'move_down') {
	$con_content = explode (';', $content);
	// Delete Content Cache
	sf_factoryCallMethod('UTILS', 'DbCache', null, null, 'flushByGroup', array('frontend'));
	unset($content);
	foreach ($con_content as $value) {
		$con_config = explode ('.', $value);
		$con_container = $con_config['0'];
		$entry = $con_config['1'];
		$sql = "UPDATE $cms_db[content] SET number='-1' WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number='$entry'";
		$db->query($sql);
		$sql = "UPDATE $cms_db[content] SET number=number-1 WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number='".($entry+1)."'";
		$db->query($sql);
		$sql = "UPDATE $cms_db[content] SET number='".($entry+1)."' WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='$con_container' AND number='-1'";
		$db->query($sql);
	}
	// Änderungsdatum aktualisieren
	$sql = "UPDATE $cms_db[side_lang] SET lastmodified='".time()."', author='".$auth->auth['uid']."' WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."'";
	$db->query($sql);
	// Seitenkopien suchen
	$sql = "SELECT idcatside FROM $cms_db[side_lang] A LEFT JOIN $cms_db[cat_side] B USING(idside) WHERE A.idsidelang='".$con_side[$idcatside]['idsidelang']."'";
	$db->query($sql);
	while ($db->next_record()) $list[] = $db->f('idcatside');

	// Status der Seite auf geändert stellen
	change_code_status($list, '1', 'idcatside');
	sf_header_redirect($con_side[$idcatside]['link'], true);
}

//Content bearbeiten
if ($action == 'edit' || $action == 'saveedit' || $action == 'new') {

	// Formularelemente includieren
	include_once($cms_path.'inc/fnc.type_forms.php');
	$code .= '<!doctype html>
	<!--[if lt IE 7]> <html class="no-js ie6 oldie" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
	<!--[if IE 7]>    <html class="no-js ie7 oldie" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
	<!--[if IE 8]>    <html class="no-js ie8 oldie" xmlns="http://www.w3.org/1999/xhtml"> <![endif]-->
	<!--[if gt IE 8]><!--> <html  class="no-js" xmlns="http://www.w3.org/1999/xhtml"> <!--<![endif]-->
	<head>
	<meta http-equiv="content-type" content="text/html; charset='.$lang_charset.'" />
	<meta http-equiv="X-UA-Compatible" content="chrome=1" />
	<meta name="robots" content="noindex, nofollow" />
	<title>Sefrengo | Edit-Mode</title>
	<link rel="shortcut icon" href="favicon.ico" />'."\n";
	$code .= '<link rel="stylesheet" type="text/css" href="'.$cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/css/styles.css" />'."\n";
	$code .= '<link rel="stylesheet" type="text/css" href="'.$cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/css/dynCalendar.css" />'."\n";
	$code .= '<script type="text/javascript" src="'.$cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/js/dynCalendarBrowserSniffer.js"></script>'."\n";
	$code .= '<script type="text/javascript" src="'.$cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/js/dynCalendar.js"></script>'."\n";
	$code .= '<script type="text/javascript" src="'.$cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/js/standard.js"></script>'."\n";
	//disable selector content sync
	$copycontent_disabled = true;
	$code .= '<script type="text/javascript">
		try {	
			window.parent.con_nav.sf_setCurrentIdcatside('.$idcatside.', '.$copycontent_disabled.', '.$lang.')
		} catch (e) {

		}
		</script>';
	$code .= '</head>'."\n";
	$code .= '<body id="con-edit2">'."\n";
	$code .= '<!-- inc.con_edit.php -->'."\n";
	$code .= '<div id="main">'."\n";
	$code .= "    <form name=\"editcontent\" method=\"post\" action=\"".$sess->url($cfg_client['contentfile'])."\">\n";
	$code .= "    <input type=\"hidden\" name=\"view\" value=\"edit\" />\n";
	$code .= "    <input type=\"hidden\" name=\"lang\" value=\"$lang\" />\n";
	$code .= "    <input type=\"hidden\" name=\"action\" value=\"save\" />\n";
	$code .= "    <input type=\"hidden\" name=\"entry\" value=\"$entry\" />\n";
	$code .= "    <input type=\"hidden\" name=\"idcatside\" value=\"$idcatside\" />\n";
	$code .= "    <input type=\"hidden\" name=\"content\" value=\"$content\" />\n";
	$code .= "    <table class=\"config\" cellspacing=\"1\">\n";

    $con_type[1] =array('type'=>'text'         ,'descr'=>'Text'                 ,'input'=>'$code .= type_form_text($formname, $content, $type_config, $cms_side);');
    $con_type[2] =array('type'=>'wysiwyg'      ,'descr'=>'Text/HTML'            ,'input'=>'$code .= type_form_wysiwyg($formname, $content, $type_config, $cms_side);');
    $con_type[3] =array('type'=>'textarea'     ,'descr'=>'Textarea'             ,'input'=>'$code .= type_form_textarea($formname, $content, $type_config, $cms_side);');
    $con_type[4] =array('type'=>'image'        ,'descr'=>'Bild'                 ,'input'=>'$code .= type_form_img($formname, $content, $type_config, $cms_side);');
    $con_type[5] =array('type'=>'imgdescr'     ,'descr'=>'Bildbeschreibung'     ,'input'=>'$code .= type_form_imgdescr($formname, $content, $type_config, $cms_side);');
    $con_type[6] =array('type'=>'link'         ,'descr'=>'Linkadresse (URL)'    ,'input'=>'$code .= type_form_link($formname, $content, $type_config, $cms_side);');
    $con_type[7] =array('type'=>'linkdescr'    ,'descr'=>'Linkname'             ,'input'=>'$code .= type_form_linkdescr($formname, $content, $type_config, $cms_side);');
    $con_type[8] =array('type'=>'linktarget'   ,'descr'=>'Zielfenster'          ,'input'=>'$code .= type_form_linktarget($formname, $content, $type_config, $cms_side);');
    $con_type[9] =array('type'=>'sourcecode'   ,'descr'=>'Sourcecode'           ,'input'=>'$code .= type_form_sourcecode($formname, $content, $type_config, $cms_side);');
    $con_type[10]=array('type'=>'file'         ,'descr'=>'Dateiauswahl'         ,'input'=>'$code .= type_form_file($formname, $content, $type_config, $cms_side);');
    $con_type[11]=array('type'=>'filedescr'    ,'descr'=>'Beschreibung'         ,'input'=>'$code .= type_form_filedescr($formname, $content, $type_config, $cms_side);');
    $con_type[12]=array('type'=>'filetarget'   ,'descr'=>'Zielfenster'          ,'input'=>'$code .= type_form_filetarget($formname, $content, $type_config, $cms_side);');
    $con_type[13]=array('type'=>'wysiwyg2'     ,'descr'=>'Text/HTML'            ,'input'=>'$code .= type_form_wysiwyg2($formname, $content, $type_config, $cms_side);');
    $con_type[14]=array('type'=>'select'       ,'descr'=>'Select'               ,'input'=>'$code .= type_form_select($formname, $content, $type_config, $cms_side);');
    $con_type[15]=array('type'=>'hidden'       ,'descr'=>'Hidden'               ,'input'=>'$code .= type_form_hidden($formname, $content, $type_config, $cms_side);');
    $con_type[16]=array('type'=>'checkbox'     ,'descr'=>'Checkbox'             ,'input'=>'$code .= type_form_checkbox($formname, $content, $type_config, $cms_side);');
    $con_type[17]=array('type'=>'radio'        ,'descr'=>'Radio'                ,'input'=>'$code .= type_form_radio($formname, $content, $type_config, $cms_side);');
    $con_type[18]=array('type'=>'date'         ,'descr'=>'Datum/Zeit'           ,'input'=>'$code .= type_form_date($formname, $content, $type_config, $cms_side);');
//    $con_type[19]=array('type'=>'time'         ,'descr'=>'Zeit'                 ,'input'=>'$code .= type_form_time($formname, $content, $type_config, $cms_side);');
    $con_type[20]=array('type'=>'checkboxsave' ,'descr'=>'Checkbox'             ,'input'=>'$code .= type_form_checkboxsave($formname, $content, $type_config, $cms_side);');

	// Content-Array erstellen
	$sql = "SELECT
				A.idcontent, container, number, idtype, typenumber, value
			FROM
				$cms_db[content] A
				LEFT JOIN $cms_db[side_lang] B USING(idsidelang)
			WHERE
				B.idside='$idside'
				AND B.idlang='$lang'";
	$db->query($sql);
	while ($db->next_record())
	{
	 	$content_array[$db->f('container')][$db->f('number')][$db->f('idtype')][$db->f('typenumber')] = array($db->f('idcontent'), htmlentities($db->f('value'), ENT_COMPAT, 'UTF-8'));
	}

	// Module finden
	if ($con_side[$idcatside]['idtplconf'] == '0') {
		$idtplconf = $con_tree[$con_side[$idcatside]['idcat']]['idtplconf'];
	} else {
		$idtplconf = $con_side[$idcatside]['idtplconf'];
	}

	$modlist = browse_template_for_module('0', $idtplconf);

	// Containernamen suchen
	$sql = "SELECT idlay FROM $cms_db[tpl_conf] A LEFT JOIN $cms_db[tpl] B USING(idtpl) WHERE A.idtplconf='$idtplconf'";
	$db->query($sql);
	$db->next_record();
	$idlay = $db->f('idlay');
	$list = browse_layout_for_containers($idlay);

	// Browserinformationen in Array schreiben
	$tmp_sniffer = explode(',', $sid_sniffer);

	// Bearbeitungsarray erstellen
	$con_content = explode (';', $content);
	unset($content);

	// Einzelne Container auflisten
	foreach ($con_content as $value) {

		// Konfiguration einlesen
		$con_config = explode ('.', $value);
		$con_container = $con_config['0'];
		$con_contnbr = explode (',', $con_config[1]);
		$con_content_type = explode (',', $con_config[2]);

		// Konfigurationsparameter mod_values extahieren und aufbereiten
		$sql = "SELECT container_conf.config FROM ".$cms_db['container_conf']." container_conf LEFT JOIN ".$cms_db['tpl_conf']." tpl_conf USING(idtplconf) LEFT JOIN ".$cms_db['container']." container USING(idtpl) WHERE container_conf.idtplconf = $idtplconf AND container = $con_container AND container_conf.idcontainer = container.idcontainer";
		$db->query($sql);
		$db->next_record();
		$tpl_config_vars = $db->f('config');

		// mod_values aus Container ersetzen
		$container = $modlist[$con_container]['output'];
		$config = preg_split('/&/', $tpl_config_vars );
		foreach ($config as $key1 => $value1) {
			$tmp2 = explode('=', $value1);
			if ($tmp2['1'] != '') {
				// $mod_value Array schreiben
				$cms_mod['value'][$tmp2['0']] = cms_stripslashes(urldecode($tmp2['1']));
				// MOD_VALUE[x] ersetzen
				$container = str_replace('MOD_VALUE['.$tmp2['0'].']', str_replace("\'","'", urldecode($tmp2['1'])), $container);//'
			}
			unset($tmp2);
		}

		// nicht benutzte Variablen strippen
		$container = preg_replace('/MOD_VALUE\[\d*\]/', '', $container);
		if( stristr ($container, '<cms:mod constant="tagmode" />') ){
			$container = str_replace('<cms:mod constant="tagmode" />', '', $container);
			$container = cms_stripslashes($container);
		//todo: 2remove
		} elseif( stristr ($container, '<dedi:mod constant="tagmode" />') ){
			$container = str_replace('<dedi:mod constant="tagmode" />', '', $container);
			$container = cms_stripslashes($container);
		}


		// Moduloutput simulieren, zum generieren der CMS-Tag Informationen
		$sefrengotag_config = extract_cms_tags($container, 'type');

		// Rowspan für Containertabelle berechnen
		$rowspan = 1;
		foreach ($con_contnbr as $con_containernumber) {
			$rowspan++;
			foreach ($con_content_type as $value3) {
				$rowspan++;
				$rowspan++;
			}
		}

		$code .= "  <tr>\n";

		// Containername
		$code .= "    <td class=\"head\" width=\"110\" rowspan=\"$rowspan\"><p>";
		if (!empty($list[$con_container]['title'])) $code .= $list[$con_container]['title'];
		else $code .= "$con_container. ".$cms_lang['tpl_container'];
		$code .= "</p></td>\n";
		unset($rowspan);
		foreach ($con_contnbr as $con_containernumber) {

			// neues Modul erstellen?
			if ($con_containernumber == '-1') $print_containernumber = '';
			else $print_containernumber = $con_containernumber.'. ';

			// Modulname
			$modname = (($modlist[$con_container]['verbose']) != '' ? $modlist[$con_container]['verbose'] : $modlist[$con_container]['modname']) . ((empty($modlist[$con_container]['version'])) ? '' : ' (' . $modlist[$con_container]['version'] . ')');
			$code .= "    <td class=\"headre\"><!-- $print_containernumber -->".$modname."</td>\n";
			$code .= "  </tr>\n";
			foreach ($con_content_type as $value3) {
				$value3 = explode ('-', $value3);
				$con_contype = $value3['0'];
				$con_typenumber = $value3['1'];

				// Name für Eingabefeld
				// Nicht anzeigen bei Dateilink, wenn hidetarget auf true gesetzt ist
				if ($filetarget_is_hidden == 'true' && $con_contype == 12) {
					$code .= "    <td></td>\n";
					$code .= "  </tr>\n";
					$code .= "  <tr>\n";

//				} elseif ($con_contype == 15) { 
//				    $code .="";
                } elseif ($con_contype == 20) { 
					$code .= "    <td height=\"0\">";
					$code .= "    </td>\n";
					$code .= "  </tr>\n";
					$code .= "  <tr>\n";
                } else {
                    if (in_array($con_contype,array(1,2,3,4,6,9,10,13,14,15,16,17,18))) {
					    $code .= "  <tr class=\"fomrstitle\">\n";
					} else {
					    $code .= "  <tr>\n";
					}
					$code .= "    <td>";
					if (!empty($sefrengotag_config[$con_type[$con_contype]['type']][$con_typenumber]['title'])) $code .= $sefrengotag_config[$con_type[$con_contype]['type']][$con_typenumber]['title'];
					else $code .= $con_type[$con_contype]['descr'];
					$code .= ":</td>\n";
					$code .= "  </tr>\n";
					$code .= "  <tr>\n";
				}

				// Name des Formularfeldes
				$formname = 'content_'.$con_container.'_'.$con_containernumber.'_'.$con_contype.'_'.$con_typenumber;

				// benutzte WYSIWYG-Applets in Array schreiben
				if ( ( ($tmp_sniffer['18'] != 'true' && $cfg_client['wysiwyg_applet'] == '1') 
					|| $cfg_client['wysiwyg_applet'] == '2'
					|| ($tmp_sniffer['18'] != 'true' || $tmp_sniffer['9'] != 'true') && $cfg_client['wysiwyg_applet'] == '3')  
					&& $con_contype == '2' || $con_contype == '13'){
						
						 $used_applet[] = $formname;
				}

				// Variable für den Content
				$content = $content_array[$con_container][$con_containernumber][$con_contype][$con_typenumber]['1'];
				$type_config = $sefrengotag_config[$con_type[$con_contype]['type']][$con_typenumber];
                if ($con_contype == '16') {
                    $type_config['saved'] = $content_array[$con_container][$con_containernumber]['20'][$con_typenumber]['1'];
                }
				eval ($con_type[$con_contype]['input']);
				unset($content);
				unset($formname);
				$code .= "  </tr>\n";

			}
		}
	}
	$code .= "      <tr>\n";
	$code .= "        <td class='content7' colspan='2' style='text-align:right'>\n";
	$code .= "        <input type='submit' value='".$cms_lang['gen_save']."' class=\"sf_buttonAction\"/>\n";
	$code .= "        <input type='submit' value='".$cms_lang['gen_apply']."' class=\"sf_buttonAction\" onclick=\"document.editcontent.action.value='saveedit'\"/>\n";
	$code .= "        <input type='button' value='".$cms_lang['gen_cancel']."' class=\"sf_buttonActionCancel\" onclick=\"window.location='".$sess->url("".$cfg_client['contentfile']."?lang=$lang&action=abort&view=edit&idcatside=$idcatside")."'\"/>\n";
		
	$code .= "      </tr>\n";
	$code .= "    </table>\n";
	$code .= "    </form>\n";
	unset ($con_type);
	unset ($con_content);
	$code .= '</div>'."\n";
	$code .= '</body>'."\n";
	$code .= '</html>'."\n";

// normale Anzeige
} else {
	//sniffer init
	$tmp_sniffer = explode(',', $sid_sniffer);
	
	// Template suchen
	$sql = "SELECT A.idtplconf, B.idtpl 
			FROM 
				".$cms_db['side_lang'] ." A 
				LEFT JOIN ". $cms_db['tpl_conf']." B USING(idtplconf) 
			WHERE 
				A.idside='$idside' 
				AND A.idlang='$lang' 
				AND A.idtplconf!='0'";
	$db->query($sql);
	if (!$db->affected_rows()) {
		$sql = "SELECT 
					A.idtplconf, B.idtpl 
				FROM 
					$cms_db[cat_lang] A 
					LEFT JOIN $cms_db[tpl_conf] B USING(idtplconf) 
				WHERE 
					A.idcat='$idcat' 
					AND A.idlang='$lang' 
					AND A.idtplconf!='0'";
		$db->query($sql);
	}
	if ($db->next_record()) {
		// Templatekonfiguration suchen
		$idtpl = $db->f('idtpl');
		$sql = "SELECT 
					A.config, A.view, A.edit, B.container, C.name, C.output, C.idmod, C.verbose
				FROM 
					".$cms_db['container_conf']." A 
					LEFT JOIN ".$cms_db['container']." B USING(idcontainer) 
					LEFT JOIN ".$cms_db['mod']." C USING(idmod) 
				WHERE 
					A.idtplconf='".$db->f('idtplconf')."'";
		$db->query($sql);
		while ($db->next_record()) {
			$container[$db->f('container')] = array ($db->f('config'), $db->f('view'), $db->f('edit'), 
														$db->f('name'), $db->f('output'), $db->f('idmod'), 
														$db->f('verbose'));
		}

		// Content-Array erstellen
		$sql = "SELECT 
					A.idcontent, container, number, idtype, typenumber, value 
				FROM 
					".$cms_db['content']." A 
					LEFT JOIN ".$cms_db['side_lang']." B USING(idsidelang) 
				WHERE 
					B.idside='$idside' 
					AND B.idlang='$lang' 
				ORDER BY 
					number";
		$db->query($sql);
		while ($db->next_record()) {
			$content[$db->f('container')][$db->f('number')][$db->f('idtype')][$db->f('typenumber')] = array($db->f('idcontent'), $db->f('value'));
		}

		// Layout suchen
		$sql = "SELECT 
					A.idlay, B.doctype, B.doctype_autoinsert, B.code 
				FROM 
					".$cms_db['tpl']." A 
					LEFT JOIN ".$cms_db['lay']." B USING(idlay) 
				WHERE 
					A.idtpl='$idtpl'";
		$db->query($sql);
		$db->next_record();
		$layout = $db->f('code');
		$idlay = $db->f('idlay');
		$sf_doctype = $db->f('doctype');
		$sf_doctype_autoinsert = $db->f('doctype_autoinsert');
		$layout = str_replace('<CMSPHP:CACHE>', '<?PHP ', $layout);
		$layout = str_replace('</CMSPHP:CACHE>', ' ?>', $layout);
		//todo: 2remove
		$layout = str_replace('<DEDIPHP:CACHE>', '<?PHP ', $layout);
		$layout = str_replace('</DEDIPHP:CACHE>', ' ?>', $layout);

        // insert doctype, if choosen
        if ($sf_doctype_autoinsert == 1) {
            $doctype = '';
            switch ($sf_doctype) {
                case 'xhtml-1.0-trans':
                    $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
                    break;
                case 'html-4.0.1-trans':
                    $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'."\n";
                    break;
                case 'html-5':
                    $doctype = '<!doctype html>'."\n";
                    break;
            }
        }
        

        $sf_slash_closing_tag = '';
        switch ($sf_doctype) {
            case 'html-4.0.1-trans':
                $sf_slash_closing_tag = '';
                break;
            case 'html-5':
                $sf_slash_closing_tag = '';
                break;    
            case 'xhtml-1.0-trans':
            default:
                $sf_slash_closing_tag = ' /';
                break;
        }
			
		$layout = $doctype . $layout;
		
		// Container generieren
		$list = extract_cms_tags($layout);
		if (is_array($list)) {
			foreach ($list as $cms_mod['container']) {
				// Head-Container?
				if ($cms_mod['container']['type'] == 'head') {
					$code = '';					
					//head
					$code .= "<!--START head//-->\n";
          $code .= '<title>'.htmlspecialchars($con_side[$idcatside]['meta_title'], ENT_COMPAT, 'utf-8').'</title>';
					$code .= "<meta name=\"generator\" content=\"Sefrengo / www.sefrengo.org\" ".$sf_slash_closing_tag.">\n";
          $code .= '<?PHP if ($con_side[$idcatside][\'meta_author\'] != \'\') echo \'<meta name="author" content="\'.htmlspecialchars($con_side[$idcatside][\'meta_author\'], ENT_COMPAT, \'utf-8\').\'"'.$sf_slash_closing_tag.'>\'."\n"; ?>';
					$code .= '<?PHP if ($con_side[$idcatside][\'meta_description\'] != \'\') echo \'<meta name="description" content="\'.htmlspecialchars($con_side[$idcatside][\'meta_description\'], ENT_COMPAT, \'utf-8\').\'"'.$sf_slash_closing_tag.'>\'."\n"; ?>';
					$code .= '<?PHP if ($con_side[$idcatside][\'meta_keywords\'] != \'\') echo \'<meta name="keywords" content="\'.htmlspecialchars($con_side[$idcatside][\'meta_keywords\'], ENT_COMPAT, \'utf-8\').\'"'.$sf_slash_closing_tag.'>\'."\n"; ?>';
					$code .= '<?PHP if ($con_side[$idcatside][\'meta_robots\'] != \'\') echo \'<meta name="robots" content="\'.htmlspecialchars($con_side[$idcatside][\'meta_robots\'], ENT_COMPAT, \'utf-8\').\'"'.$sf_slash_closing_tag.'>\'."\n"; ?>';
					
					$code .= '<?PHP if ($con_side[$idcatside][\'metasocial_title\'] != \'\') echo \'<meta property="og:title" content="\'.htmlspecialchars($con_side[$idcatside][\'metasocial_title\'], ENT_COMPAT, \'utf-8\').\'"'.$sf_slash_closing_tag.'>\'."\n"; ?>';
					$code .= '<?PHP if ($con_side[$idcatside][\'metasocial_image\'] != \'\') echo \'<meta property="og:image" content="\'.htmlspecialchars($con_side[$idcatside][\'metasocial_image\'], ENT_COMPAT, \'utf-8\').\'"'.$sf_slash_closing_tag.'>\'."\n"; ?>';
					$code .= '<?PHP if ($con_side[$idcatside][\'metasocial_description\'] != \'\') echo \'<meta property="og:description" content="\'.htmlspecialchars($con_side[$idcatside][\'metasocial_description\'], ENT_COMPAT, \'utf-8\').\'"'.$sf_slash_closing_tag.'>\'."\n"; ?>';
					$code .= '<?PHP if ($con_side[$idcatside][\'metasocial_author\'] != \'\') echo \'<meta property="article:author" content="\'.htmlspecialchars($con_side[$idcatside][\'metasocial_author\'], ENT_COMPAT, \'utf-8\').\'"'.$sf_slash_closing_tag.'>\'."\n"; ?>';
          $code .= '<?PHP if ($con_side[$idcatside][\'meta_other\'] != \'\') echo $con_side[$idcatside][\'meta_other\']."\n"; ?>';					
					$code .= '<meta property="og:type" content="website" />
					<meta http-equiv="content-type" content="text/html; charset='.$lang_charset.'"'.$sf_slash_closing_tag.'>'."\n";
					$code .= "<meta http-equiv=\"expires\" content=\"0\"".$sf_slash_closing_tag.">\n";
					$sql = "SELECT C.filetype, D.dirname, B.filename FROM $cms_db[lay_upl] A LEFT JOIN $cms_db[upl] B USING(idupl) LEFT JOIN $cms_db[filetype] C USING(idfiletype) LEFT JOIN $cms_db[directory] D ON B.iddirectory=D.iddirectory WHERE idlay='$idlay' ORDER BY A.idlayupl";
					$db->query($sql);
					while ($db->next_record()) {
						if ($db->f('filetype') == 'js') $code .= "<script src=\"".$db->f('dirname').$db->f('filename')."\" type=\"text/javascript\"></script>\n";
						else if ($db->f('filetype') == 'css') $code .= "<link rel=\"stylesheet\" href=\"".$db->f('dirname').$db->f('filename')."\" type=\"text/css\" ".$sf_slash_closing_tag.">\n";
					}
					$code .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"". $cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/css/sf_hovermenu.css' ."\" />\n";
					
					$code .= "<style type=\"text/css\">\n";
					$code .= "  div.actEditFrame {border:1px solid #AF0F0F; -moz-user-focus: normal;min-height:20px;}\n";
					$code .= "  div.editFrame {border:1px dashed #BFBFBF; -moz-user-focus: normal;min-height:20px;}\n";
					$code .= "</style>\n";
					
					$code .= "<script src=\"". $cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/js/jquery/init.sefrengo.js' ."\" type=\"text/javascript\"></script>\n";
					$code .= "<script src=\"". $cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/js/jquery/lib/jquery.min.js' ."\" type=\"text/javascript\"></script>\n";
					
					$code .= "<script type=\"text/javascript\">/* <![CDATA[ */
						SF.Config.debug = false;
						SF.Config.backend_dir = '".$cfg_cms['cms_html_path']."'; // e.g. /backend/
						SF.Config.js_dir = SF.Config.backend_dir + 'tpl/".$cfg_cms['skin']."/js/jquery/';
						SF.Config.css_dir = SF.Config.backend_dir + 'tpl/".$cfg_cms['skin']."/css/';
						SF.Config.img_dir = SF.Config.backend_dir + 'tpl/".$cfg_cms['skin']."/img/';
					/* ]]> */</script>";
					
					$code .= "<script src=\"". $cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/js/jquery/jquery.frontend.js' ."\" type=\"text/javascript\"></script>\n";
					
          			//TODO NUR BEI GECKO EINBINDEN
          			if($tmp_sniffer['9'] == 'true'){
          				$code .= "<script src=\"".$cfg_cms['cms_html_path']."external/mozile/mozileLoader.js\" type=\"text/javascript\"></script>\n";
          			}
					$code .= "<script type=\"text/javascript\">\n";
					$code .= "<!--\n";
					$code .= "function con_setcontent(container,number,type,typenumber) {\n";
					$code .= "  var thisID = 'content_'+container+'_'+number+'_'+type+'_'+typenumber;\n";
					$code .= "  eval(\"var a = document.getElementById('\" + thisID + \"');\");\n";
					$code .= "  var str = '';\n";
					$code .= "  var aContent = a.innerHTML;\n";
					$code .= "  var datas=a.id.split(\"_\");\n";
					$code .= "  str += datas[1] +'*x|*'+datas[2]+'*x|*'+datas[3]+'*x|*'+datas[4]+'*x|*' + aContent + '*x||*';\n";

					$code .= "  eval('document.editform_'+container+'_'+number+'_'+type+'_'+typenumber+'.data.value=str');\n";
					$code .= "  eval('document.editform_'+container+'_'+number+'_'+type+'_'+typenumber+'.submit()');\n";
					$code .= "}\n";
					
					//edit page perm
					$copycontent_disabled = ($perm->have_perm(19, 'side', $idcatside, $idcat) && $view !='preview') ? 'false': 'true';
					$code .= '
						try {	
							window.parent.con_nav.sf_setCurrentIdcatside('.$idcatside.', '.$copycontent_disabled.', '.$lang.')
						} catch (e) {

						}';
						
					$code .= "//-->\n";
					$code .= "</script>\n";
					$code .= "<!--END head//-->\n";
					$search[] = $cms_mod['container']['full_tag'];
					$replace[] = $code;

				// Seitenkonfigurationslayer?
				} elseif ($cms_mod['container']['type'] == 'config') {
					$search[] = $cms_mod['container']['full_tag'];
					$replace[] = '<?PHP if ($cms_side[\'view\']) echo $con_side[$idcatside][\'config\']; ?>';
				} else {
					unset($code);
					unset($config);
					unset($output);

					// darf Modul bearbeitet werden? Seitencontent darf bearbeitet werden
					if ($container[$cms_mod['container']['id']]['2'] == '0' && isset($idcatside) && $cms_side['view'] == 'edit') $cms_side['edit'] = 'true';
					else unset($cms_side['edit']);

					// darf Modul gesehen werden? Modul ist aktiviert
					if ($container[$cms_mod['container']['id']]['1'] == '0') {
						// Container konfigurieren
						$code = $container[$cms_mod['container']['id']]['4'];
						$config = preg_split('/&/', $container[$cms_mod['container']['id']]['0']);
						foreach ($config as $key1 => $value1) {
							$tmp2 = explode('=', $value1);
							if ($tmp2['1'] != '') {
								// $mod_value Array schreiben
								$cms_mod['value'][$tmp2['0']] = cms_stripslashes(urldecode($tmp2['1']));
								// MOD_VALUE[x] ersetzen
								$code = str_replace('MOD_VALUE['.$tmp2['0'].']', str_replace("\'","'", urldecode($tmp2['1'])), $code);
							}
							unset($tmp2);
						}
						
						//TODO: 2REMOVE - DEDI BACKWARD COMPATIBILITY
						$dedi_mod =& $cms_mod;

						// nicht benutzte Variablen strippen
						$code = preg_replace('/MOD_VALUE\[\d*\]/', '', $code);//'

						$code = str_replace('<CMSPHP:CACHE>', '<?PHP ', $code);
						$code = str_replace('</CMSPHP:CACHE>', ' ?>', $code);
						//todo: 2remove
						$code = str_replace('<DEDIPHP:CACHE>', '<?PHP ', $code);
						$code = str_replace('</DEDIPHP:CACHE>', ' ?>', $code);
						
						//Im tagmode stripslashes im modul ausführen
						if( stristr ($code, '<cms:mod constant="tagmode" />') ){
							$code = str_replace('<cms:mod constant="tagmode" />', '', $code);
							$code = cms_stripslashes($code);
						//todo: 2remove
						} elseif( stristr ($code, '<dedi:mod constant="tagmode" />') ){
							$code = str_replace('<dedi:mod constant="tagmode" />', '', $code);
							$code = cms_stripslashes($code);
						}

						// Das Modul existiert noch nicht in der Datenbank
						if (!is_array($content[$cms_mod['container']['id']])) $content[$cms_mod['container']['id']]['1'] = 'neu';

						// Alle MOD_TAGS[] im Container ersetzen
						$used_type = extract_cms_tags($code);

						// alle Module in einem Container generieren
						foreach ($content[$cms_mod['container']['id']] as $key3 => $value3) {
							// letztes Modul in diesem Container?
							if (!$content[$cms_mod['container']['id']][$key3+1]) {
								$cms_mod['modul']['lastentry'] = 'true';
								$pre_container_code = '<?php $cms_mod[\'modul\'][\'lastentry\']=\'true\'; ?>';
							} else {
								unset($cms_mod['modul']['lastentry']);
								$pre_container_code = '<?php unset($cms_mod[\'modul\'][\'lastentry\']); ?>';
							}

							// erstes Modul generieren?
							if ($key3 == '1') {
								$container_code = $code;
								if (is_array($used_type)) {
									// CMS-TAG in Funktionsaufruf umwandeln
									foreach ($used_type as $value4) {
										// CMS-TAG Konfiguration auslesen
										// Darf Modul erstellt werden?
										$cms_type_config = '\'';

										// letztes Modul in diesem Container?
										foreach ($value4 as $key5=>$value5) if ($key5 != 'type' && $key5 != 'id' && $key5 != 'full_tag') $cms_type_config .= '\''.$key5.'\'=>\''.str_replace('\"','"', cms_addslashes($value5)).'\',';

										// letztes Komma entfernen
										$cms_type_config = substr ($cms_type_config,  1, -1);

										// Filemanagerklasse für Bildformatierung laden
										$to_eval ='$check_type_config = array('.$cms_type_config.');';
										eval($to_eval);
										if($check_type_config['autoresize'] == 'true' && ! is_object($fm)){
											$this_dir = $cms_path;
											include_once ($cms_path.'inc/class.querybuilder_factory.php');
											include_once ($cms_path.'inc/class.filemanager.php');
											include_once ($cms_path.'inc/class.fileaccess.php');
											include_once ($cms_path.'inc/class.fileaddon.php');
											$db_query = new querybuilder_factory();
											$db_query = $db_query -> get_db($db, 'cms_db', $this_dir.'inc/');
											$fm = new filemanager();
										}
										if (!$value4['id']) $value4['id'] = '0';
										if (!is_integer($value4['id'])) {
											if (function_exists('type_output_'.strtolower($value4['type']))) {
												if ($value4['addslashes'] == 'true'){
													//bb testing please dont delete
													//$container_code = 'if(! function_exists(\'type_output_'.strtolower($value4['type']).'\'){'."\n";;
													//$container_code .=      'include_once(\''.$cms_path.'inc/fnc.type.php\');'."\n";;
													//$container_code .= '}'."\n";
													$container_code = str_replace($value4['full_tag'], 'type_output_'.strtolower($value4['type']).'('.$cms_mod['container']['id'].', $cms_mod[\'modul\'][\'id\'], '.$value4['id'].', array('.$cms_type_config.'))', $container_code);
												}
												else{
													$container_code = str_replace($value4['full_tag'], '<?PHP echo type_output_'.strtolower($value4['type']).'('.$cms_mod['container']['id'].', $cms_mod[\'modul\'][\'id\'], '.$value4['id'].', array('.$cms_type_config.')); ?>', $container_code);
												}
											} else $container_code = $mod_lang['err_type'].$container_code;
										} else $container_code = $mod_lang['err_id'].$container_code;
									}
								}

								// Modul cachen
								eval('$cms_mod[\'modul\'][\'id\'] = $key3;$cms_mod[\'key\'] = \'mod\'.$cms_mod[\'container\'][\'id\'].\'_\'.$key3.\'_\'; ?>'.$pre_container_code.$container_code);
								$container_code_final = ob_get_contents ();
								ob_end_clean ();
								ob_start();
								$output = '<?php $cms_mod[\'modul\'][\'id\']=\''.$key3.'\';$cms_mod[\'key\'] = \'mod\'.$cms_mod[\'container\'][\'id\'].\'_\'.$key3.\'_\'; ?>'.$pre_container_code.$container_code_final;

							// alle weiteren Module dranhängen
							} else {
								// Modul cachen
								eval('$cms_mod[\'modul\'][\'id\'] = $key3;$cms_mod[\'key\'] = \'mod\'.$cms_mod[\'container\'][\'id\'].\'_\'.$key3.\'_\'; ?>'.$pre_container_code.$container_code);
								$container_code_final = ob_get_contents ();
								ob_end_clean ();
								ob_start();
	                                                       	$output .= '<?php $cms_mod[\'modul\'][\'id\']=\''.$key3.'\';$cms_mod[\'key\'] = \'mod\'.$cms_mod[\'container\'][\'id\'].\'_\'.$key3.\'_\'; ?>'.$pre_container_code.$container_code_final;
							}
						}

						// Container in Array schreiben
						$search[] = $cms_mod['container']['full_tag'];
						if (!$cms_side['edit']) $replace[] = '<!--START '.$cms_mod['container']['id'].'//--><?php unset($cms_side[\'edit\']); $cms_mod[\'container\'][\'id\']=\''.$cms_mod['container']['id'].'\'; ?>'.$output.'<!--END '.$cms_mod['container']['id'].'//-->';
						else $replace[] = '<!--START '.$cms_mod['container']['id'].'//--><?php $cms_side[\'edit\']=\'true\'; $cms_mod[\'container\'][\'id\']=\''.$cms_mod['container']['id'].'\'; ?>'.$output.'<!--END '.$cms_mod['container']['id'].'//-->';
						unset($output);
						unset($used_type, $cms_mod['value']);
					} else {
						// Modul ist nicht sichtbar
						$search[] = $cms_mod['container']['full_tag'];
						$replace[] = '';
					}
				}
			}

			// Seite erstellen
			$code = $layout;
			foreach ($search as $key=>$value) $code = str_replace($value, $replace[$key], $code);
		} else $code = $layout;
	} else $code = $cms_lang['con_notemplate'];

	// Dynamisches PHP beibehalten
	$code = str_replace('<CMSPHP>', '<?PHP ', $code);
	$code = str_replace('</CMSPHP>', ' ?>', $code);
	//todo: 2remove
	$code = str_replace('<DEDIPHP>', '<?PHP ', $code);
	$code = str_replace('</DEDIPHP>', ' ?>', $code);
}
?>
