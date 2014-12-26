<?PHP
// File: $Id: inc.lay_edit.php 107 2008-12-17 15:08:25Z bjoern $
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
// + Revision: $Revision: 107 $
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

include('inc/fnc.lay.php');

if(is_numeric($idlay)) $perm->check(3, 'lay', $idlay);
else $perm->check(3, 'area_lay', 0);

// user change client - redirect to layout list
if (isset($changeclient)) {
	sf_header_redirect( $sess->urlRaw("main.php?area=lay&idclient=$client") );
}

include('inc/inc.header.php');


echo "<!-- Anfang inc.lay_edit.php -->\n";
echo "<div id=\"main\">\n";
echo "    <h5>".$cms_lang['area_lay_edit']."</h5>";
if ($errno) echo "<p class=\"errormsg\">".$cms_lang["err_$errno"]."</p>";


// Layout aus der Datenbank suchen
if ($idlay) {
	$sql = "SELECT * FROM ".$cms_db['lay']." WHERE idlay='$idlay'";
	$db->query($sql);
	$db->next_record();
	$layname = htmlspecialchars($db->f('name'), ENT_COMPAT, 'UTF-8');
	$description = htmlspecialchars($db->f('description'), ENT_COMPAT, 'UTF-8');
	$code = htmlspecialchars($db->f('code'), ENT_COMPAT, 'UTF-8');
	$sf_doctype = $db->f('doctype');
	$sf_doctype_autoinsert = $db->f('doctype_autoinsert');
}
else{
	$code = $cfg_client['default_layout'];
}

// Layout dublizieren
if($action == 'duplicate'){
	$idlay_for_form = '';
	$layname = $cms_lang['lay_copy_of'] . $layname;
}
else{
	$idlay_for_form = $idlay;
}

// Benutzen CSS-Dateien in Array schreiben
$sql = "SELECT B.idupl FROM $cms_db[lay_upl] A LEFT JOIN $cms_db[upl] B USING(idupl) LEFT JOIN $cms_db[filetype] C ON B.idfiletype=C.idfiletype WHERE idlay='$idlay' AND C.filetype='css' AND B.status IN (4,5)";
$db->query($sql);
while ($db->next_record()) {
	$used_files['css'][] = $db->f('idupl');
}

// Benutzen JS-Dateien in Array schreiben
$sql = "SELECT B.idupl FROM $cms_db[lay_upl] A LEFT JOIN $cms_db[upl] B USING(idupl) LEFT JOIN $cms_db[filetype] C ON B.idfiletype=C.idfiletype WHERE idlay='$idlay' AND C.filetype='js' AND B.status IN (4,5)";
$db->query($sql);
while ($db->next_record()) {
	$used_files['js'][] = $db->f('idupl');
}

// Formular zum bearbeiten des Layouts
echo "    <form name=\"newlayout\" method=\"post\" action=\"".$sess->url("main.php?area=lay&action=save&idlay=$idlay_for_form&idclient=$idclient")."\">\n";
echo "    <table class=\"config\" cellspacing=\"1\">\n";
echo "      <tr valign=\"top\">\n";
echo "        <td class=\"head\"><p>".$cms_lang['lay_layoutname']."</p></td>\n";
echo "        <td>\n<input class=\"w800\" type=\"text\" name=\"layname\" value=\"$layname\" size=\"90\" /></td>\n";
echo "      </tr>\n";
//
// rechte management
// change JB: Kein Panel ohne Gruppen
if (!empty($idlay) && !empty($idclient) && $action != 'duplicate' && $perm->have_perm(6, 'lay', $idlay)) {
	$panel = $perm->get_right_panel('lay', $idlay, array( 'formname'=>'newlayout' ), 'text' );
	if (!empty($panel)) {
	echo "      <tr>\n";
	echo "        <td class=\"head\">&nbsp;</td>\n";
	echo "        <td>";
		echo implode("", $panel);
	echo "      </tr>\n";
	}
}


echo "      <tr valign=\"top\">\n";
echo "        <td class=\"head\">".$cms_lang['lay_description']."</td>\n";
echo "        <td>\n<textarea class=\"w800\" name=\"description\" rows=\"3\" cols=\"52\">$description</textarea></td>\n";
echo "      </tr>\n";


$doctype_array = array('0' => $cms_lang['lay_doctype_none'],
                        'xhtml-1.0-trans' => 'XHTML 1.0 transitional',
                        'html-4.0.1-trans' => 'HTML 4.0.1 transitional',
                        'html-5' => 'HTML5');

$doctype_select = '';
foreach ($doctype_array AS $k=>$v) {
	$doctype_selected = $k == $sf_doctype ? 'selected="selected"' : '';
	$doctype_select .= sprintf('<option value="%s" %s>%s</option>'."\n", $k, $doctype_selected, $v);
}
$doctype_select = sprintf('<select name="sf_doctype">%s</select>',$doctype_select);
$doctype_auto_is_checked = $sf_doctype_autoinsert == 1 ? 'checked="checked"': '';
$doctype_auto_check = sprintf('<input type="checkbox" name="sf_doctype_autoinsert" id="sf_doctype_autoinsert" %s  value="1" /> <label for="sf_doctype_autoinsert">%s</label>', 
								$doctype_auto_is_checked,
								$cms_lang['lay_doctype_autoinsert']	);

echo "      <tr valign=\"top\">\n";
echo "        <td class=\"head\">".$cms_lang['lay_doctype']."</td>\n";
echo "        <td>$doctype_select $doctype_auto_check</td>\n";
echo "      </tr>\n";




echo "      <tr>\n";
echo "        <td class=\"head\" rowspan=\"2\">".$cms_lang['lay_cmshead']."</td>\n";
echo "        <td style=\"padding:0;\" colspan=\"2\" class=\"headre \"><table>\n";
echo "          <tr>\n";
echo "            <td width=\"400\" class=\"headre\">".$cms_lang['lay_css']."</td>\n";
echo "            <td width=\"400\" class=\"headre\">".$cms_lang['lay_js']."</td>\n";
echo "          </tr>\n";
echo "        </table></td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "        <td colspan=\"2\"><table>\n";
echo "          <tr>\n";

// Stylesheet-Dateien suchen
$sql = "SELECT A.idupl, A.filename, A.description FROM $cms_db[upl] A left join $cms_db[filetype] B on A.idfiletype=B.idfiletype WHERE A.idclient='$client' AND B.filetype='css' AND A.status IN (4,5) ORDER BY A.filename";
$db->query($sql);
echo "            <td width=\"400\">\n<select style=\"height: 150px; width: 380px;\" name=\"css[]\" multiple=\"multiple\" size=\"5\">\n";
if ($db->affected_rows()) {
	if (is_array($used_files['css'])) echo "              <option value=\"0\">".$cms_lang['lay_nofile']."</option>\n";
	else echo "              <option value=\"0\" selected=\"selected\">".$cms_lang['lay_nofile']."</option>\n";
	while ($db->next_record()) {
		if (is_array($used_files['css'])) {
			if (in_array($db->f('idupl'),$used_files['css'])) printf ("              <option value=\"".$db->f('idupl')."\" selected=\"selected\">".$db->f('filename')."%s</option>\n", ($db->f('description')) ? ' - '.$db->f('description') : '');
			else printf ("              <option value=\"".$db->f('idupl')."\">".$db->f('filename')."%s</option>\n", ($db->f('description')) ? ' - '.$db->f('description') : '');
 } else printf ("              <option value=\"".$db->f('idupl')."\">".$db->f('filename')."%s</option>\n", ($db->f('description')) ? ' - '.$db->f('description') : '');
	}
} else echo "              <option value=\"0\" selected=\"selected\">".$cms_lang['lay_nofile']."</option>\n";
echo "            </select></td>\n";

// Javascript-Dateien suchen
$sql = "SELECT A.idupl, A.filename, A.description FROM $cms_db[upl] A left join $cms_db[filetype] B on A.idfiletype=B.idfiletype WHERE A.idclient='$client' AND B.filetype='js' AND A.status IN (4,5) ORDER BY A.filename";
$db->query($sql);
echo "            <td width=\"400\">\n<select style=\"height: 150px; width: 380px;\" name=\"js[]\" multiple=\"multiple\" size=\"5\">\n";
if ($db->affected_rows()) {
	if (is_array($used_files['js'])) echo "              <option value=\"0\">".$cms_lang['lay_nofile']."</option>\n";
	else echo "              <option value=\"0\" selected=\"selected\">".$cms_lang['lay_nofile']."</option>\n";
	while ($db->next_record()) {
		if (is_array($used_files['js'])) {
			if (in_array($db->f('idupl'),$used_files['js'])) printf ("              <option value=\"".$db->f('idupl')."\" selected=\"selected\">".$db->f('filename')."%s</option>\n", ($db->f('description')) ? ' - '.$db->f('description') : '');
			else printf ("              <option value=\"".$db->f('idupl')."\">".$db->f('filename')."%s</option>\n", ($db->f('description')) ? ' - '.$db->f('description') : '');
                 } else printf ("              <option value=\"".$db->f('idupl')."\">".$db->f('filename')."%s</option>\n", ($db->f('description')) ? ' - '.$db->f('description') : '');
	}
} else echo "              <option value=\"0\" selected=\"selected\">".$cms_lang['lay_nofile']."</option>\n";
echo "            </select></td>\n";
echo "          </tr>\n";
echo "        </table></td>\n";
echo "      </tr>\n";
// Quellcode
echo "      <tr valign=\"top\">\n";
echo "        <td class=\"head\">".$cms_lang['lay_code']."</td>\n";
echo "        <td colspan=\"2\"><textarea class=\"w800\" name=\"code\" rows=\"26\" cols=\"52\" wrap=\"off\">$code</textarea></td>\n";
echo "      </tr>\n";
// Buttons

echo "      <tr>\n";
echo "        <td class='content7' colspan='3' style='text-align:right'>\n";
echo "        <input type='submit' name='sf_save' title='".$cms_lang['gen_save_titletext']."' value='".$cms_lang['gen_save']."' class=\"sf_buttonAction\"/>\n";
echo "        <input type='submit' name='sf_apply' title='".$cms_lang['gen_apply_titletext']."' value='".$cms_lang['gen_apply']."' class=\"sf_buttonAction\" onclick=\"document.newlayout.action='".$sess->url("main.php?area=lay&action=saveedit&idlay=$idlay_for_form&idclient=$idclient")."'\"/>\n";
echo "        <input type='button' name='sf_cancel' title='".$cms_lang['gen_cancel_titletext']."' value='".$cms_lang['gen_cancel']."' class=\"sf_buttonActionCancel\" onclick=\"window.location='".$sess->url("main.php?area=lay&idclient=$idclient")."'\" />\n";
echo "        </td>\n";
echo "      </tr>\n";

echo "    </table>\n";
echo "    </form>\n";
echo "    </div>\n";
echo '<div class="footer">'. $cms_lang['login_licence'] .'</div>'."\n";
echo "</body>\n";
echo "</html>\n";
?>