<?php 
// File: $Id: fnc.type_forms.php 360 2011-05-19 14:10:48Z joern $
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
// + Autor: $Author: joern $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 360 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes:
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
if (! defined('CMS_CONFIGFILE_INCLUDED')) {
    die('NO CONFIGFILE FOUND');
} 

include_once($cms_path . 'inc/fnc.type_common.php');

/**
* Returns a HTML-hiddenfield
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
* @Return String HTML hiddenfield
* @Access private 
*/
function _type_get_element_hidden($formname, $content)
{
    return '<input type="hidden" name="' . $formname . '" value="' . $content . '">';
} 

/**
* Returns a HTML-textfield
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         width
*         maxlength
* @Return String HTML textfield
* @Access private 
*/
function _type_get_element_text($formname, $content, $width, $maxlength)
{
    $width = (empty($width)) ? '800px': $width;
    $maxlength = (empty($maxlength)) ? '': ' maxlength ="' . $maxlength . '" ';

    $out = '<input type="text" name="' . $formname . '" value="' . $content . '" size="90" style="width:' . $width . '" ' . $maxlength . '>' . "\n";
    return $out;
} 

/**
* Returns complete formatted HTML-textfield
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML textfield
* @Access public 
*/
function type_form_text($formname, $content, $type_config, $cms_side)
{
    $content = type_form_cmslinks_to_templinks($content);
    if (! _type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'])) return _type_get_element_hidden($formname, $content);
    else {
        $width = $type_config['width'];
        $maxlength = $type_config['maxlength'];
        $out = '<td>' . "\n" . _type_get_element_text($formname, $content, $width, $maxlength) . "\n</td>\n";
    } 
    return $out;
} 

/**
* Returns complete formatted HTML-textareafield
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML textareafield
* @Access public 
*/
function type_form_textarea($formname, $content, $type_config, $cms_side)
{
    global $cfg_cms, $sess;

    $content = type_form_cmslinks_to_templinks($content);
    if (!_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'])) return _type_get_element_hidden($formname, $content);
    $width = $type_config['width'];
    $width = (empty($width)) ? '800px': $width;
    $height = $type_config['height'];
    $height = (empty($height)) ? '200px': $height;

    if ($type_config['transform'] == 'bbcode') {
        // mit bbcode
        return '<td>
        <input style="font-weight: bold;" name="bold" value="fett" title="Text fett formatieren: [b]Text[/b]" onclick="bbcode(\'b\');" type="button">
        <input style="font-style: italic;" name="italic" value="kursiv" title="Text kursiv formatieren: [i]Text[/i]" onclick="bbcode(\'i\');" type="button">
        <input style="text-decoration: underline;" name="under" value="unterstr." title="Text unterstrichen formatieren: [u]Text[/u]" onclick="bbcode(\'u\');" type="button">
        <input style="text-decoration : line-through;" name="durch" value="durchgestr." title="Text durchgestrichen formatieren: [s]Text[/s]" onclick="bbcode(\'s\');" type="button">
        <input style="color: rgb(0, 0, 255); text-decoration: underline;" name="mail" value="E-Mail" title="E-Mail mit Text einfügen: [email=name@adresse.de]email an [/email]" onclick="insert_mail();" type="button">
        <input style="color: rgb(0, 0, 255); text-decoration: underline;" name="link2" value="Link" title="Link mit Linktext einfügen: [link=http://...]Linktext[/link]" onclick="insert_link();" type="button"><br>
        <textarea id="text" name="' . $formname . '" rows="14" cols="52" style="width:' . $width . ';height:' . $height . '">' . $content . '</textarea>
        <script type="text/javascript" src="' . $sess->url($cfg_cms['cms_html_path'] . 'tpl/standard/js/bbcode.js') . '"></script>
        </td>' . "\n";
    } else {
        // ohne bbcode
        return '<td>
        <textarea name="' . $formname . '" rows="14" cols="52" style="width:' . $width . ';height:' . $height . '">' . $content . '</textarea>
        </td>' . "\n";
    } 
} 

/**
* Returns complete WYSIWYG-field (wysiwyg2)
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML textareafield
* @Access public 
*/
function type_form_wysiwyg($formname, $content, $type_config)
{
    return type_form_wysiwyg2($formname, $content, $type_config);
} 

/**
* Returns complete WYSIWYG-field 
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML textareafield
* @Access public 
*/
function type_form_wysiwyg2($formname, $content, $type_config)
{
    global $dedi_lang, $client, $lang, $dedi, $cfg_dedi, $cfg_client, $sess;

    $content = type_form_cmslinks_to_templinks($content); 
    // echo "<pre>".$content."</pre><br><hr>";
    // make relative pathes to absolut pathes
    $upl_path_relative = str_replace($cfg_client['htmlpath'], '', $cfg_client['upl_htmlpath']); 
    // $preg = '!(src|href)([ ]*=[ ]*("|\'|&quot;))('.$upl_path_relative.'[\w/.]*("|\'|&quot;))!';
    // $preg_out = '\\1\\2'.$cfg_client['htmlpath'].'\\4';
    // $content = preg_replace($preg, $preg_out, $content);
    // echo "<pre>".$content."</pre><br><hr>";
    $content = str_replace('&quot;' . $upl_path_relative, '&quot;' . $cfg_client['htmlpath'] . $upl_path_relative, $content);

    $out = '<script language="Javascript1.2" src="' . $sess->url($cfg_client['htmlpath'] . 'cms/fckeditor/fckeditor.js') . '"></script>' . "\n"; 
    // Textfeld erstellen
    $out .= "    <td><textarea name=\"$formname\" id=\"$formname\" rows=\"30\" cols=\"52\" style=\"width:640px\">$content</textarea></td>\n";

    $out .= '<script type="text/javascript">' . "\n"; 
    // Init Editor
    global $idlay;
    $type_config['sess_name'] = $sess->name;
    $type_config['sess_id'] = $sess->id;
    $type_config['sf_idlay'] = $idlay;

    $toolbar_set = (trim($type_config['features']) == 'true' || trim($type_config['features']) == '')
    ? 'SefrengoDefault' : $formname;
    $out .= "
	var oFCKeditor_" . $formname . " = new FCKeditor( '" . $formname . "', '100%', 400, '" . $toolbar_set . "' ) ;
	var sf_BasePath = '" . $cfg_client['htmlpath'] . "cms/fckeditor/';	
	oFCKeditor_" . $formname . ".BasePath	= sf_BasePath;
	oFCKeditor_" . $formname . ".Config['CustomConfigurationsPath'] = sf_BasePath + 'editor/sefrengo/fckconfig.php"
     . "?" . $sess->name . "=" . $sess->id . "&fck_editorname=" . $formname . "&fck_ser=" . base64_encode(serialize($type_config)) . "';

	oFCKeditor_" . $formname . ".ReplaceTextarea() ;
	</script>
	";
    return $out;
} 


/**
* Returns complete formatted Image-field
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML field
* @Access public 
*/
function type_form_img($formname, $content, $type_config, $cms_side)
{
    global $db, $cms_db, $cms_lang, $client, $cfg_client; 
    // Standardfiletypes laden, wenn keine anderen angegeben, defaults laden
    $ft = strtolower(trim($type_config['filetypes']));
    $filetypes = (empty($ft) || $ft == 'true') ? 'jpg,jpeg,gif,png': $ft;

    $match = array();
    $pathway_string = '';
    if (preg_match_all('#^cms://(idfile|idfilethumb)=(\d+)$#', $content, $match)) {
        $content = type_form_cmslinks_to_templinks($content);

        $is_thumb = $match['1']['0'] == 'idfilethumb';
        $id = $match['2']['0'];

        $sql = "SELECT
				U.idupl, U.filename, U.pictheight, U.pictwidth, U.pictthumbwidth, U.pictthumbheight, F.filetype, D.dirname
			FROM
				" . $cms_db['upl'] . " U
				LEFT JOIN " . $cms_db['filetype'] . " F USING(idfiletype)
				LEFT JOIN " . $cms_db['directory'] . " D ON U.iddirectory=D.iddirectory
			WHERE
				U.idclient= '$client'
				AND D.dirname NOT LIKE('cms/%')
				AND U.idupl = '" . $id . "'";

        $db->query($sql);

        if ($db->next_record()) {
            $pic_filename = $db->f('filename');
            $pic_filetype = $db->f('filetype');
            $pic_height = $db->f('pictheight');
            $pic_width = $db->f('pictwidth');
            $pic_thumb_height = $db->f('pictthumbheight');
            $pic_thumb_width = $db->f('pictthumbwidth');
            $name_length = strlen($pic_filename);
            $extension_length = strlen($pic_filetype);
            $t_name = substr ($pic_filename, 0, ($name_length - $extension_length - 1));
            $t_name .= $cfg_client['thumbext'] . '.' . $pic_filetype;
            $thumb_url = $db->f('dirname') . $t_name;
            if ($is_thumb) {
                $pathway_string = $thumb_url;
            } else {
                $pathway_string = $db->f('dirname') . $db->f('filename');
            } 
        } 
    } 

    $rb = &$GLOBALS['sf_factory']->getObjectForced('GUI', 'RessourceBrowser');

    $res_file = &$GLOBALS['sf_factory']->getObjectForced('GUI/RESSOURCES', 'FileManager');
    $res_file->setFiletypes(type_config_string_to_array($filetypes));
    $res_file->setFolderIds(type_config_string_to_array($type_config['folders']));
    $res_file->setWithSubfoders(($type_config['subfolders'] != 'false'));
    $res_file->setReturnValueMode('sefrengolink');

    $rb->addRessource($res_file);
    $rb->setJSCallbackFunction('sf_getImage' . $formname, array('picked_name', 'picked_value'));
    $rb_url = $rb->exportConfigURL();

    if (is_file($cfg_client['upl_path'] . $thumb_url)) {
        $img_src = $cfg_client['upl_htmlpath'] . $thumb_url;
        $img_thumb_x = $pic_thumb_width;
        $img_thumb_y = $pic_thumb_height;
    } else if ($pic_height > 0 && $pic_width > 0) {
        $img_src = $cfg_client['upl_htmlpath'] . $pathway_string;
        $extracted_size = _type_calculate_new_image_size($pic_width, $pic_height, $cfg_client['thumb_size'], $cfg_client['thumb_size'], true);
        $img_thumb_x = $extracted_size['width'];
        $img_thumb_y = $extracted_size['height'];
    } else {
        $img_src = $cfg_client['space'];
        $img_thumb_x = $cfg_client['thumb_size'];
        $img_thumb_y = $cfg_client['thumb_size'];
    } 

    $out .= '<td width="640" nowrap> <table style="height:' . ($cfg_client['thumb_size'] + 20) . 'px"><tr>
<td style="background-color:#efefef;border:1px solid black;text-align:center;vertical-align:middle;width:' . ($cfg_client['thumb_size'] + 20) . 'px;">
<img id="' . $formname . '" src="' . $img_src . '"  border="0" width="' . $img_thumb_x . '" height="' . $img_thumb_y . '" />
</td><td valign="bottom">';

    $out .= "<input type=\"hidden\" name=\"$formname\" value=\"$content\"><input type=\"text\" name=\"" . $formname . "display\" readonly=\"readonly\" value=\"" . $pathway_string . "\" style=\"width:560px\" >\n";
    $out .= "<input type='button' value='DEL' onclick=\"sf_getImage" . $formname . "('', '');\" />";
    $out .= "&nbsp;<input type='button' value='...' onclick=\"new_window('$rb_url', 'rb', '', screen.width * 0.7, screen.height * 0.7, 'true')\" />";
    $out .= '</td></tr></table></td>' . "\n";
    $out .= '<script type="text/javascript">
	<!--
	function sf_getImage' . $formname . '(name, value) {
		editcontent.' . $formname . '.value= value;
		editcontent.' . $formname . 'display.value= name;
		sf_loadPreviewPic("' . $cfg_client['upl_htmlpath'] . '"+name, "' . $cfg_client['thumbext'] . '", "' . $formname . '");
		//changeImage("media/"+name, value, "' . $formname . '");
		//alert(name + " XXX " +value);
	}
	-->
	</script>';
    return $out;
} 

/**
* Returns complete formatted HTML-textfield for Imagedesr
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML field
* @Access public 
*/
function type_form_imgdescr($formname, $content, $type_config, $cms_side)
{
    global $db, $cms_db, $cms_lang, $client;

    $content = type_form_cmslinks_to_templinks($content);
    return "    <td><textarea name=\"$formname\" rows=\"2\" cols=\"52\" style=\"width:800\">$content</textarea></td>\n";
} 

/**
* Returns complete formatted Link-field
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML field
* @Access public 
*/
function type_form_link($formname, $content, $type_config, $cms_side)
{
    global $db, $cms_db, $cms_lang, $mod_lang, $client, $lang, $catlist;

    $match = array();
    if (preg_match_all('#^cms://(idcatside|idcat)=(\d+)$#', $content, $match)) {
        $content = type_form_cmslinks_to_templinks($content);
        $is_page = $match['1']['0'] == 'idcatside';
        $id = $match['2']['0'];
        $pathway_string = '';

        if ($is_page) {
            $sql = "SELECT 
					CS.idcat, SL.title
				FROM 
					" . $cms_db['cat_side'] . "  CS 
					LEFT JOIN " . $cms_db['side'] . "  S USING(idside) 
					LEFT JOIN " . $cms_db['side_lang'] . "  SL USING(idside)
				WHERE 
					CS.idcatside = '" . $id . "'
					AND SL.idlang='$lang'";
            $db->query($sql);
            if ($db->next_record()) {
                $pathway_string = $db->f('title');
                $id = $db->f('idcat');
            } 
        } 
        $control = 0;
        while ($id > 0 && ++$control < 50) {
            $sql = "SELECT CL.name, C.parent
					FROM 
						" . $cms_db['cat_lang'] . " CL
						LEFT JOIN " . $cms_db['cat'] . " C USING(idcat)
					WHERE 
						CL.idcat = '" . $id . "'
						AND CL.idlang = '" . $lang . "'
					LIMIT 1";
            $db->query($sql);
            if (! $db->next_record()) {
                break;
            } 
            $id = $db->f('parent');
            $pathway_string = $db->f('name') . '/' . $pathway_string;
        } 

        $sf_link_intern = htmlentities($pathway_string, ENT_COMPAT, 'UTF-8');
        $sf_link_extern = '';
    } else {
        $sf_link_intern = '';
        $sf_link_extern = $content;
    } 

    $rb = &$GLOBALS['sf_factory']->getObjectForced('GUI', 'RessourceBrowser');
    $res_links = &$GLOBALS['sf_factory']->getObjectForced('GUI/RESSOURCES', 'InternalLink');
    $rb->addRessource($res_links);
    $rb->setJSCallbackFunction('sf_getLink' . $formname, array('picked_name', 'picked_value'));
    $rb_url = $rb->exportConfigURL();

    $out = '<script type="text/javascript">
	<!--
	function sf_getLink' . $formname . '(name, value) {
		editcontent.' . $formname . '.value= value;
		editcontent.' . $formname . 'intern.value= name;
		editcontent.' . $formname . 'extern.value= "";
		//alert(name + " XXX " +value);
	}
	-->
	</script>';
    $out .= "    <input type=\"hidden\" name=\"$formname\" value=\"$content\">\n";
    $out .= "      <td width=\"640\"><table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" width='100%'>\n";
    $out .= "        <tr>\n";
    $out .= "          <td ><input type=\"text\" name=\"" . $formname . "extern\" value=\"" . $sf_link_extern . "\" style=\"width:800px\" onchange=\"editcontent." . $formname . ".value=this.value; editcontent." . $formname . "intern.value='';\">&nbsp;<small>(" . $mod_lang['link_extern'] . ")</small>&nbsp;</td>\n";
    $out .= "        </tr><tr>";
    $out .= "          <td><input type=\"text\" name=\"" . $formname . "intern\" readonly=\"readonly\" value=\"" . $sf_link_intern . "\" style=\"width:640px\" >\n";
    $out .= "          <input type='button' value='DEL' onclick=\"sf_getLink" . $formname . "('', '')\" />&nbsp;<input type='button' value='...' onclick=\"new_window('$rb_url', 'rb', '', screen.width * 0.7, screen.height * 0.7, 'true')\" />&nbsp;<small>(" . $mod_lang['link_intern'] . ")</small>
					

					&nbsp;</td>\n";
    $out .= "        </tr>\n";
    $out .= "      </table></td>\n";
    return $out;
} 

/**
* Returns complete formatted HTML-textfield for Imagedesr
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML field
* @Access public 
*/
function type_form_linkdescr($formname, $content, $type_config, $cms_side)
{
    return '<td><input type="text" name="' . $formname . '" style="width:800px" value="' . $content . '"></td>' . "\n";
} 

/**
* Returns complete formatted field for Linktarget
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML field
* @Access public 
*/
function type_form_linktarget($formname, $content, $type_config, $cms_side)
{
    global $mod_lang;

    $content = type_form_cmslinks_to_templinks($content);

    $out = "    <input type=\"hidden\" name=\"$formname\" value=\"$content\">\n";
    $out .= "      <td width=\"640\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
    $out .= "        <tr>\n";
    $out .= "          <td><input type=\"text\" name=\"" . $formname . "frame\" value=\"$content\" size=\"11\" style=\"width:100\" onchange=\"editcontent." . $formname . ".value=this.value; editcontent." . $formname . "selectframe.value='_self';\">&nbsp;</td>\n";
    $out .= "          <td><select name=\"" . $formname . "selectframe\" size=\"1\" onchange=\"editcontent." . $formname . ".value=this.value; editcontent." . $formname . "frame.value=this.value;\">\n";
    if ($content == '_self') $out .= "            <option value=\"_self\" selected=\"selected\">&nbsp;" . $mod_lang['link_self'] . "</option>\n";
    else $out .= "            <option value=\"_self\">&nbsp;" . $mod_lang['link_self'] . "</option>\n";
    if ($content == '_blank') $out .= "            <option value=\"_blank\" selected=\"selected\">&nbsp;" . $mod_lang['link_blank'] . "</option>\n";
    else $out .= "            <option value=\"_blank\">&nbsp;" . $mod_lang['link_blank'] . "</option>\n";
    if ($content == '_parent') $out .= "            <option value=\"_parent\" selected=\"selected\">&nbsp;" . $mod_lang['link_parent'] . "</option>\n";
    else $out .= "            <option value=\"_parent\">&nbsp;" . $mod_lang['link_parent'] . "</option>\n";
    if ($content == '_top') $out .= "            <option value=\"_top\" selected=\"selected\">&nbsp;" . $mod_lang['link_top'] . "</option>\n";
    else $out .= "            <option value=\"_top\">&nbsp;" . $mod_lang['link_top'] . "</option>\n";
    $out .= "          </select></td>\n";
    $out .= "        </tr>\n";
    $out .= "      </table></td>\n";
    return $out;
} 

/**
* Returns complete formatted HTML-textareafield for Sourcecode
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML textareafield
* @Access public 
*/
function type_form_sourcecode($formname, $content, $type_config, $cms_side)
{
    global $js_pad, $cfg_cms, $gb_conf;

    $content = type_form_cmslinks_to_templinks($content);

    include_once ($cfg_cms['cms_path'] . 'external/sourcepad/gb_source_pad.php');
    $js_pad = &new gb_source_pad('editcontent', $formname);

    $out = "    <td>\n";
    $js_pad->set('handle_http_path', $cfg_cms['cms_html_path'] . 'external/sourcepad/');
    $js_pad->set('language', 'german');
    $js_pad->set('editorheight_css', '350px');
    $js_pad->set('editorwidth_css', '800px');
    $js_pad->set('content', $content);
    $out .= $js_pad->make_pad();
    $out .= "    </td>\n";
    return $out;
} 

/**
* Returns complete formatted "select files from the filebrowser"- field
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML field
* @Access public 
*/
function type_form_file($formname, $content, $type_config, $cms_side)
{
    global $db, $cms_db, $cfg_client, $cms_lang, $client, $sefrengotag_config, $con_contype, $con_typenumber;

    $match = array();
    $pathway_string = '';
    if (preg_match_all('#^cms://(idfile|idfilethumb)=(\d+)$#', $content, $match)) {
        $content = type_form_cmslinks_to_templinks($content);

        $is_thumb = $match['1']['0'] == 'idfilethumb';
        $id = $match['2']['0'];

        $sql = "SELECT
				U.idupl, U.filename, F.filetype, D.dirname
			FROM
				" . $cms_db['upl'] . " U
				LEFT JOIN " . $cms_db['filetype'] . " F USING(idfiletype)
				LEFT JOIN " . $cms_db['directory'] . " D ON U.iddirectory=D.iddirectory
			WHERE
				U.idclient= '$client'
				AND D.dirname NOT LIKE('cms/%')
				AND U.idupl = '" . $id . "'";

        $db->query($sql);

        if ($db->next_record()) {
            if ($is_thumb) {
                $pic_filename = $db->f('filename');
                $pic_filetype = $db->f('filetype');
                $name_length = strlen($pic_filename);
                $extension_length = strlen($pic_filetype);
                $t_name = substr ($pic_filename, 0, ($name_length - $extension_length - 1));
                $t_name .= $cfg_client['thumbext'] . '.' . $pic_filetype;
                $pathway_string = $db->f('dirname') . $t_name;
            } else {
                $pathway_string = $db->f('dirname') . $db->f('filename');
            } 
        } 
    } 

    $rb = &$GLOBALS['sf_factory']->getObjectForced('GUI', 'RessourceBrowser');

    $res_file = &$GLOBALS['sf_factory']->getObjectForced('GUI/RESSOURCES', 'FileManager');
    $res_file->setFiletypes(type_config_string_to_array($type_config['filetypes']));
    $res_file->setFolderIds(type_config_string_to_array($type_config['folders']));
    $res_file->setWithSubfoders(($type_config['subfolders'] != 'false'));
    $res_file->setReturnValueMode('sefrengolink');

    $rb->addRessource($res_file);
    //global $sess;
    //$rb->setExtraUrlParmString($sess->name .'='. $sess->id .'&amp;x=y');
    $rb->setJSCallbackFunction('sf_getFile' . $formname, array('picked_name', 'picked_value'));
    $rb_url = $rb->exportConfigURL();

    $html_out = '<script type="text/javascript">
	<!--
	function sf_getFile' . $formname . '(name, value) {
		editcontent.' . $formname . '.value= value;
		editcontent.' . $formname . 'display.value= name;
		//alert(name + " XXX " +value);
	}
	-->
	</script>';

    $html_out .= '<td width="640">
	 <table cellspacing="0" cellpadding="0" border="0">'; 
    // zusätzliche beschreibung nur wenn ein title im tag angegeben wurde, sonst würde hier zwei mal das gleiche stehen
    if (! empty($sefrengotag_config[$con_type[$con_contype]['type']][$con_typenumber]['title'])) {
        $html_out .= '
		  <tr>
		   <td>&nbsp;' . $con_type[$con_contype]['descr'] . ':</td>
		  </tr>';
    } 

    $html_out .= "<tr><td>
					<input type=\"hidden\" name=\"$formname\" value=\"$content\">
					<input type=\"text\" name=\"" . $formname . "display\" readonly=\"readonly\" value=\"" . $pathway_string . "\" style=\"width:660px\" >\n";
    $html_out .= "<input type='button' value='DEL' onclick=\"sf_getFile" . $formname . "('', '')\" />&nbsp;<input type='button' value='...' onclick=\"new_window('$rb_url', 'rb', '', screen.width * 0.7, screen.height * 0.7, 'true')\" />";

    $html_out .= '	   </td>
	  </tr>
	 </table>
	</td>'; 
    // Hidetarget auslesen und variable ins filetarget rüberretten, da die Werte der cms:tags nur im Haupttag sichtbar sind
    global $filetarget_is_hidden;
    $filetarget_is_hidden = $type_config['hidetarget'];
    return $html_out;
} 

/**
* Returns complete formatted HTML-textfield for Filedesr
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML field
* @Access public 
*/
function type_form_filedescr($formname, $content, $type_config, $cms_side)
{
    $content = type_form_cmslinks_to_templinks($content);
    return '<td><input type="text" name="' . $formname . '" style="width:800px" value="' . $content . '"></td>' . "\n";
} 

/**
* Returns complete formatted field for Filetarget
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML field
* @Access public 
*/
function type_form_filetarget($formname, $content, $type_config, $cms_side)
{
    global $mod_lang, $filetarget_is_hidden;

    $content = type_form_cmslinks_to_templinks($content);

    if ($filetarget_is_hidden != 'true') {
        $out = "    <input type=\"hidden\" name=\"$formname\" value=\"$content\">\n";
        $out .= "      <td width=\"640\"><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
        $out .= "        <tr>\n";
        $out .= "          <td><input type=\"text\" name=\"" . $formname . "frame\" value=\"$content\" size=\"11\" style=\"width:100\" onchange=\"editcontent." . $formname . ".value=this.value; editcontent." . $formname . "selectframe.value='_self';\"></td>\n";
        $out .= "          <td><select name=\"" . $formname . "selectframe\" size=\"1\" onchange=\"editcontent." . $formname . ".value=this.value; editcontent." . $formname . "frame.value=this.value;\">\n";
        if ($content == '_self') $out .= "            <option value=\"_self\" selected=\"selected\">&nbsp;" . $mod_lang['link_self'] . "</option>\n";
        else $out .= "            <option value=\"_self\">&nbsp;" . $mod_lang['link_self'] . "</option>\n";
        if ($content == '_blank') $out .= "            <option value=\"_blank\" selected=\"selected\">&nbsp;" . $mod_lang['link_blank'] . "</option>\n";
        else $out .= "            <option value=\"_blank\">&nbsp;" . $mod_lang['link_blank'] . "</option>\n";
        if ($content == '_parent') $out .= "            <option value=\"_parent\" selected=\"selected\">&nbsp;" . $mod_lang['link_parent'] . "</option>\n";
        else $out .= "            <option value=\"_parent\">&nbsp;" . $mod_lang['link_parent'] . "</option>\n";
        if ($content == '_top') $out .= "            <option value=\"_top\" selected=\"selected\">&nbsp;" . $mod_lang['link_top'] . "</option>\n";
        else $out .= "            <option value=\"_top\">&nbsp;" . $mod_lang['link_top'] . "</option>\n";
        $out .= "          </select></td>\n";
        $out .= "        </tr>\n";
        $out .= "      </table></td>\n";
    } 
    unset($filetarget_is_hidden);
    return $out;
} 

/**
* Returns complete formatted select field
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML selectfield
* @Access public 
*/
function type_form_select($formname, $content, $type_config, $cms_side)
{
    global $cms_lang;

    if (!_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'])) return _type_get_element_hidden($formname, $content);
    $size = $type_config['size'];
    $size = (empty($size)) ? '1': $size;

    if (empty($type_config['elementseparator']) || $type_config['elementseparator'] == '') {
        $separator = ",";
    } else {
        $separator = $type_config['elementseparator'];
    } 

    $opt = explode($separator, htmlentities($type_config['elementname'], ENT_COMPAT, 'UTF-8'));
    if (empty($type_config['elementvalue'])) {
        $val = $opt;
    } else {
        $val = explode($separator, htmlentities($type_config['elementvalue'], ENT_COMPAT, 'UTF-8'));
    } 

    if (empty($content) && !empty($type_config['default'])) {
        $content = explode($separator, htmlentities($type_config['default'], ENT_COMPAT, 'UTF-8'));
    } else {
        $content = explode("\n", $content);
    } 

    if (!empty($type_config['multiple']) && ($type_config['multiple'] == "true")) {
        $multiple = 'multiple="multiple"';
    } else {
        $multiple = '';
    } 

    $optionfields = '<option value="">' . $cms_lang['form_nothing'] . '</option>' . "\n";
    while (list($key, $v) = each($opt)) {
        if (in_array($val[$key], $content)) $optionfields .= '<option value="' . $val[$key] . '" selected="selected">' . $v . '</option>' . "\n";
        else $optionfields .= '<option value="' . $val[$key] . '">' . $v . '</option>' . "\n";
    } 

    return '<td>
          <select name="' . $formname . '[]" size="' . $size . '" ' . $multiple . '>' . $optionfields . '</select>
        </td>' . "\n";
} 

/**
* Returns complete formatted hidden
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML textareafield
* @Access public 
*/
function type_form_hidden($formname, $content, $type_config, $cms_side)
{ 
    // return _type_get_element_hidden($formname, $content);
    if (empty($content) || $content != $type_config['elementvalue']) $content = $type_config['elementvalue'];
    $out = _type_get_element_hidden($formname, $content) . "\n";
    return $out;
} 

/**
* Returns complete formatted checkbox
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML checkboxfield
* @Access public 
*/
function type_form_checkbox($formname, $content, $type_config, $cms_side)
{
    global $cms_lang;

    if (!_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'])) return _type_get_element_hidden($formname, $content);

    $name = $type_config['elementname'];
    if (empty($type_config['elementvalue'])) {
        $val = $name;
    } else {
        $val = $type_config['elementvalue'];
    } 

    if (($type_config['saved'] != 'saved') && (!empty($type_config['checked'])) && ($type_config['checked'] == "true")) {
        $content = htmlentities($val, ENT_COMPAT, 'UTF-8');
    } 

    $optionfields = '';
    if (!empty($type_config['elementvalueunchecked'])) {
        $optionfields .= _type_get_element_hidden($formname, $type_config['elementvalueunchecked']);
    } 

    $val = htmlentities($val, ENT_COMPAT, 'UTF-8');

    if ($val == $content) {
        $optionfields .= '<label for="' . $val . '"><input type="checkbox" name="' . $formname . '" value="' . $val . '" id="' . $val . '" checked="checked"> ' . $name . '</label><br>' . "\n";
    } else {
        $optionfields .= '<label for="' . $val . '"><input type="checkbox" name="' . $formname . '" value="' . $val . '" id="' . $val . '" > ' . $name . '</label><br>' . "\n";
    } 

    return '<td>
          ' . $optionfields . '
        </td>' . "\n";
} 

/**
* Returns a HTML-hiddenfield for checkbox save state
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML hiddenfield
* @Access public 
*/
function type_form_checkboxsave($formname, $content, $type_config, $cms_side)
{ 
    // return _type_get_element_hidden($formname, $content);
    $out = _type_get_element_hidden($formname, "saved") . "\n";
    return $out;
} 

/**
* Returns complete formatted radiobutton
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML radiobuttonfield
* @Access public 
*/
function type_form_radio($formname, $content, $type_config, $cms_side)
{
    global $cms_lang;

    if (!_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'])) return _type_get_element_hidden($formname, $content);

    if (empty($type_config['elementseparator']) || $type_config['elementseparator'] == '') {
        $separator = ",";
    } else {
        $separator = $type_config['elementseparator'];
    } 

    $opt = explode($separator, $type_config['elementname']);
    if (empty($type_config['elementvalue'])) {
        $val = $opt;
    } else {
        $val = explode($separator, $type_config['elementvalue']);
    } 

    if (empty($content) && !empty($type_config['default'])) {
        $content = htmlentities($type_config['default'], ENT_COMPAT, 'UTF-8');
    } 

    $optionfields = '';
    while (list($key, $v) = each($opt)) {
        $value = htmlentities($val[$key], ENT_COMPAT, 'UTF-8');
        if ($value == $content) {
            $optionfields .= '<label for="' . $value . '"><input type="radio" name="' . $formname . '" value="' . $value . '" id="' . $value . '" checked="checked"> ' . $v . '</label><br>' . "\n";
        } else {
            $optionfields .= '<label for="' . $value . '"><input type="radio" name="' . $formname . '" value="' . $value . '" id="' . $value . '"> ' . $v . '</label><br>' . "\n";
        } 
    } 

    return '<td>
          ' . $optionfields . '
        </td>' . "\n";
} 


// PRIVATE HELPER FUNCTIONS

function type_form_cmslinks_to_templinks($content)
{ 
    // turn around inline- editing temp_links
    $in = array("!cms://idfile=(\d+)!",
        "!cms://idcat=(\d+)!",
        "!cms://idcatside=(\d+)!");
    $out = array("cms://temp_idfile=\\1",
        "cms://temp_idcat=\\1",
        "cms://temp_idcatside=\\1");
    return preg_replace($in, $out, $content);
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


// Not finished


/**
* Returns complete formatted date
* 
* @Args : formname -> name of the inputfield
*         content -> value of textfield
*         type_config -> array with values from the CMS:tag
*         cms_side['view'] -> the current view of the user, 'edit' or 'preview'
*                  ['edit'] -> 'true' if user set the mod in templateconfig as active
*                              if user set inactiv var is not set
* @Return String HTML fields
* @Access public 
*/
function type_form_date($formname, $content, $type_config, $cms_side)
{
    global $cfg_cms, $sess;

	include_once 'HTML/QuickForm.php';

    if (!_type_check_editable($cms_side['edit'], $type_config['editable'], $cms_side['view'])) return _type_get_element_hidden($formname, $content); 
    if (empty($content)) {
        $content = time();
    } 
    $optionfields = "";
    // Datumsangaben für Ausgabe formatieren
    if (!((empty($type_config['formdateformat'])) || ($type_config['formdateformat'] == ''))) {
        if ($type_config['formdateformat'] == "default-cms-date-format") {
            $type_config['formdateformat'] = $cfg_cms['FormatDate'];    
        }
        if ($type_config['formdateformat'] == "default-cms-time-format") {
            $type_config['formdateformat'] = $cfg_cms['FormatTime'];    
        }
        if ($type_config['formdateformat'] == "default-cms-format") {
            $type_config['formdateformat'] = $cfg_cms['FormatDate']." ".$cfg_cms['FormatTime'];    
        }
        
        $form = new HTML_QuickForm('');
        $form->setDefaults(array(
            $formname => $content
        ));

	$dateOptions = array('format'=>$type_config['formdateformat'],
			     'language'=>'de',
			     'optionIncrement' => array('i' => 5));
	
	$contentYear = date("Y",$content);

	if (!empty($type_config['minyear'])) {
	  $dateOptions['minYear'] = $type_config['minyear'];
	} else {
	  $dateOptions['minYear'] = date("Y") - 5;
	}
	
	if ($dateOptions['minYear'] > $contentYear) {
	  $dateOptions['minYear'] = $contentYear;
	}
	
	if (!empty($type_config['maxyear'])) {
	  $dateOptions['maxYear'] = $type_config['maxyear'];
	} else {
	  $dateOptions['maxYear'] = date("Y") + 5;
	}
	
	if ($dateOptions['maxYear'] < $contentYear) {
	  $dateOptions['maxYear'] = $contentYear;
	}
	
        $form->addElement('date', $formname, 'Date2:', $dateOptions);
        $temp_fileds = $form->toArray();
    
        $optionfields .= $temp_fileds[elements][0][html];
        $optionfields .= '&nbsp;&nbsp;' . date($type_config['formdateformat'],$content);


    } else {    
        $type_config['formdateformat'] = 'd.m.Y';
        $startdate = date($type_config['formdateformat'], $content); 
        $content = getdate($content); 
        // Datumsangaben für Ausgabe formatieren
        $optionfields .= _type_get_element_hidden($formname . '[0]', 'd.m.Y');
        $optionfields .= '<input type="text" id="' . $formname . '" name="' . $formname . '[1]" value="' . $startdate . '" size="10" maxlength="10" style="width: 85px;">';
        $optionfields .= '&nbsp;
    	<script language="JavaScript1.2">
        function callback_' . $formname . '(date, month, year)
        {
            if (String(month).length == 1) {
                month = "0" + month;
            }
        
            if (String(date).length == 1) {
                date = "0" + date;
            }    
            document.getElementById("' . $formname . '").value = date + "." + month + "." + year;
        }
    	  calendar' . $formname . ' = new dynCalendar("calendar' . $formname . '", "callback_' . $formname . '", "' . $cfg_cms['cms_html_path'] . '/tpl/standard/img/");
    	  calendar' . $formname . '.setMonthCombo(true);
    	  calendar' . $formname . '.setYearCombo(true);
    	</script>';
    
        $optionfields .= '&nbsp;&nbsp;dd.mm.yyyy';
        $optionfields .= '&nbsp;&nbsp;' . date($type_config['formdateformat']);

    }

    return '<td>
          ' . $optionfields . '
        </td>' . "\n";
} 


?>
