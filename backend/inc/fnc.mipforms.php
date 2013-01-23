<?PHP
// File: $Id: fnc.mipforms.php 50 2008-07-09 21:56:04Z bjoern $
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
// + Revision: $Revision: 50 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

/**
 * MIP FORMS (Modul Input Forms)
 * Vorgefertigte Formulare zur Abstraktion der Eingaben
 * im Modulinput.
 *
 * Struktur:
 * fnc.mipforms.php --> Diese Datei - Hauptfunktion
 * fnc.mipforms_core.php --> Grundfunktionen
 * fnc.mipforms_extensions.php --> Erweiterungen
 * fnc.mipforms_apps.php --> Applikationen
 *
 *
 * @Author: (c) Björn Brockmann 2002 - 2003
 * @Email: bjoern@project-gooseberry.de
 * @Homepage: www.project-gooseberry.de
 * @License: GPL
 * @Version: 1.3
 */

//Start Fix
if (is_object($perm)){
//include mip_forms base functions
require_once $cfg_path_cms.'inc/fnc.mipforms_core.php';

//include mip_forms applications
require_once $cfg_path_cms.'inc/fnc.mipforms_apps.php';

//include mip_forms extensions
require_once $cfg_path_cms.'inc/fnc.mipforms_extensions.php';
} // End Fix

/**
* HAUPTFUNKTION
* Von hier aus werden alle mip_forms erzeugt.
*
*
* @Access public
* @Args array 	in['cat'] (Ordner)
*                ['type'] ('', 'chk', 'radio', 'long', 'chk_long', 'radio_long')
*                         (Ausnahme 'hidden')
*                ['desc'] (Beschreibung Ausnahme 'hidden')
*                ['cms_var'] (MOD_VAR[x])
*                ['cms_val'] (MOD_VALUE[x])
*                ['cms_val_default'] (optionaler defaultwert, falls ['cms_val'] leer)
*					  ['tab']  (Einrückung Ausnahme 'hidden')
*
*                NUR $in['type'] = 'chk', 'chk_long'
*                 ['chk_var'] (Checkboxvariable)
*                 ['chk_val'] (Checkboxwert  "1" für angeklickt)
*                 ['chk_val_default'] (optionaler defaultwert, falls ['chk_val'] leer)
*                NUR $in['type'] = 'radio', 'radio_long'
*                 ['radio_var'] (Radiovariable)
*                 ['radio_val'] (Wert der Radiovariable)
*                 ['radio_val_default'] (optionaler defaultwert, falls ['radio_val'] leer)
*                 ['radio_user_val'] (Radiouservariable, wenn gleich ['radio_val']
*                                    ist der Radiobutton aktiviert)
*                NUR $in['type'] = 'radio', 'option'
*                 ['option_desc']['x'] (Beschreibung einer Option)
*                 ['option_val']['x'] (MOD_VALUE[x] einer Option)
*                NUR $in['type'] = 'option'
*                 ['size'] (optional default ='1') (Größe des <select>- Tags)
*                 ['flag'] (optional) (Möglicher Wert = 'multiple', wenn ['size'] > 1)
*                NUR $in['cat'] = 'chk'
*                 ['option_var'][x] (cms_var[x] einer Checkbox)
*                 ['option_val']['x'] (cms_val[x] einer Checkbox)
*                 ['option_val_select']['x']
*                NUR $in['cat'] = 'txtarea'
*                 ['rows'] (optional, default = '5')(Textareahoehe in Textzeilen)
*                NUR $in['cat'] = 'app_css'
*                 ['output_cat'] ('option' oder 'radio')
*                 ['type'] ('', 'chk', 'radio', 'long' , 'chk_long', 'radio_long')
*                 ['file'] (optional)
*                 ['flag'] (optional)
*                NUR $in['cat'] = 'app_css_db'
*                 ['output_cat'] ('option' oder 'radio')
*                 ['type'] ('', 'chk', 'radio', 'long' , 'chk_long', 'radio_long')
*                 ['file'] id der Layout-Datei
*                 ['flag'] (optional)
* @Return mip-form
*/
function mip_forms($in, $_client = '', $_lang = '')
{
    global $mip_forms_to_array;
    //check for default values
	if($in['cms_val'] == ''){
		$in['cms_val'] = $in['cms_val_default'];
	}
	if($in['radio_val'] == ''){
		$in['radio_val'] = $in['radio_val_default'];
	}


	switch($in['cat'])
	{
		case 'desc':
			$to_return = mip_forms_desc($in);
			break;
		case 'txt':
			$to_return = mip_forms_txt($in);
			break;
		case 'txtarea':
			$to_return = mip_forms_txtarea($in);
			break;
		case 'option':
			$to_return = mip_forms_option($in);
			break;
		case 'chk':
			$to_return = mip_forms_chk($in);
			break;
		case 'radio':
			$to_return = mip_forms_radio($in);
			break;
		case 'hidden':
			$to_return = mip_forms_hidden($in);
			break;
		case 'app_css':
			$to_return = mip_forms_app_css($in);
			break;
		case 'app_cat':
			$to_return = mip_forms_app_cat($in, $_client, $_lang);
			break;
		case 'app_filetype':
			$to_return = mip_forms_app_filetype($in);
			break;
		case 'app_directory':
			$to_return = mip_forms_app_directory($in);
			break;
		case 'app_group':
			$to_return = mip_forms_app_group($in);
			break;
		case 'app_file':
			$to_return = mip_forms_app_file($in);
			break;
		default:
            if ($mip_forms_to_array === true) return false;
            else echo 'mip_forms - Fehler! $in[\'cat\'] = "'. $in['cat'] . '" existiert nicht!';exit;
	}

	return $to_return;

}

function mip_formsp($in)
{
    global $mip_forms_to_array;
    if ($mip_forms_to_array === true) return mip_forms($in);
    else echo mip_forms($in);
}

function mip_formta($in)
{
    mip_forms_ob_start();
	$to_return = mip_formsp($in);
	mip_forms_ob_end();
	return $to_return;
}

function mip_forms_get_array($form_var = 'to_array', $form_key = '')
{
    if (mip_forms_check_cache($form_var, $form_key)) return mip_forms_get_cache($form_var, $form_key);
}

function mip_forms_ob_start()
{
    global $mip_forms_to_array;
    ob_start();
    $mip_forms_to_array = true;
}

function mip_forms_ob_end()
{
    global $mip_forms_to_array;
    if($mip_forms_to_array === true) {
        ob_end_clean();
        $mip_forms_to_array = false;
    }
}

function mip_forms_ob_end_clean()
{
    global $mip_forms_to_array, $mip_forms_cache_container;
    if($mip_forms_to_array === true) {
        ob_end_clean();
        $mip_forms_to_array = false;
        $mip_forms_cache_container = false;
    }
}


function mip_forms_tabpane_beginp()
{
    global $mip_forms_layerid, $idmod, $idtpl, $value, $mip_forms_layerid_stack, $key, $area, $cms_mod;

    $ebene = '';
    if (isset($mip_forms_layerid) && ($mip_forms_layerid != '')) {
        if (is_array ($mip_forms_layerid_stack)) 
        {
            $ebene = array_push($mip_forms_layerid_stack,$mip_forms_layerid);
        } else {
            $mip_forms_layerid_stack[] = $mip_forms_layerid;
            $ebene = '0';
        }
        
    }
    if (($area=='con_configside') || ($area=='con_configcat')){
        $temp_mod = (int)$key ;   
    } else {
        $temp_mod = (int)$idmod;    
    }
    $mip_forms_layerid = (int)$idtpl.'_'.(int)$temp_mod.'_'.(int)$value.'_'.$area;
    $mip_forms_layerid = base64_encode ($mip_forms_layerid);
    $mip_forms_layerid = str_replace(array('+','/','='),array('-','_',''),$mip_forms_layerid);
    $mip_forms_layerid = 'tp_'.$mip_forms_layerid.'_'.$ebene;
    $mip_forms_pageid = 0;
    echo '<div class="tab-pane" id="'.$mip_forms_layerid.'">
    ';
    echo '<script type="text/javascript">
    ';
    echo 'var '.$mip_forms_layerid.' = new WebFXTabPane( document.getElementById( "'.$mip_forms_layerid.'" ), true )
    ';
    echo '</script>
    ';
}

function mip_forms_tabpane_endp()
{
    global $mip_forms_layerid,$mip_forms_layerid_stack;
    
    if (is_array ($mip_forms_layerid_stack)) 
    {
        $mip_forms_layerid = array_pop($mip_forms_layerid_stack);
        if (count($mip_forms_layerid_stack) == 0)
        {
             unset($mip_forms_layerid_stack);
        }
    }
    unset ($mip_forms_layerid);

    echo '</div>
    ';
    
    
}

function mip_forms_tabitem_beginp($name,$scroll = false)
{
    global $mip_forms_layerid,$mip_forms_pageid;
    
    $mip_forms_pageid += 1;
    $id = $mip_forms_layerid.'_'.$mip_forms_pageid;
    if ($scroll) 
    {
        $scroll_text = ' scrollpage';      
    } else {
        $scroll_text = '';      
    }
    echo '<div class="tab-page'.$scroll_text.'" id="'.$id.'">
    ';
    echo '<h2 class="tab" onfocus="this.blur()">'.$name.'</h2>
    ';
    echo '<script type="text/javascript">'.$mip_forms_layerid.'.addTabPage( document.getElementById( "'.$id.'" ) );</script>
    ';
}

function mip_forms_tabitem_endp()
{
    echo '</div>';
}

?>
