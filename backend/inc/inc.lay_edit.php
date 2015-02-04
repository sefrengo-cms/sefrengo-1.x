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
?>
<script>
/* HTML5 Sortable (http://farhadi.ir/projects/html5sortable)
 * Released under the MIT license.
 */(function(a){var b,c=a();a.fn.sortable=function(d){var e=String(d);return d=a.extend({connectWith:!1},d),this.each(function(){if(/^enable|disable|destroy$/.test(e)){var f=a(this).children(a(this).data("items")).attr("draggable",e=="enable");e=="destroy"&&f.add(this).removeData("connectWith items").off("dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s");return}var g,h,f=a(this).children(d.items),i=a("<"+(/^ul|ol$/i.test(this.tagName)?"li":"div")+' class="sortable-placeholder">');f.find(d.handle).mousedown(function(){g=!0}).mouseup(function(){g=!1}),a(this).data("items",d.items),c=c.add(i),d.connectWith&&a(d.connectWith).add(this).data("connectWith",d.connectWith),f.attr("draggable","true").on("dragstart.h5s",function(c){if(d.handle&&!g)return!1;g=!1;var e=c.originalEvent.dataTransfer;e.effectAllowed="move",e.setData("Text","dummy"),h=(b=a(this)).addClass("sortable-dragging").index()}).on("dragend.h5s",function(){b.removeClass("sortable-dragging").show(),c.detach(),h!=b.index()&&f.parent().trigger("sortupdate",{item:b}),b=null}).not("a[href], img").on("selectstart.h5s",function(){return this.dragDrop&&this.dragDrop(),!1}).end().add([this,i]).on("dragover.h5s dragenter.h5s drop.h5s",function(e){return!f.is(b)&&d.connectWith!==a(b).parent().data("connectWith")?!0:e.type=="drop"?(e.stopPropagation(),c.filter(":visible").after(b),!1):(e.preventDefault(),e.originalEvent.dataTransfer.dropEffect="move",f.is(this)?(d.forcePlaceholderSize&&i.height(b.outerHeight()),b.hide(),a(this)[i.index()<a(this).index()?"after":"before"](i),c.not(i).detach()):!c.is(this)&&!a(this).children(d.items).length&&(c.detach(),a(this).append(i)),!1)})})}})(jQuery);
		function makeCssList(){
			$(".cssFormItems").remove();
			$(".csslist.usedlist li").each(function(i) {
		        $(".csslist.usedlist").append("<input class=\"cssFormItems\" type=\"hidden\" name=\"css[]\" value=\""+$(this).attr('data-value')+"\" />");
		    });
		}
		function makeJsList(){
			$(".jsFormItems").remove();
			$(".jslist.usedlist li").each(function(i) {
		        $(".jslist.usedlist").append("<input class=\"jsFormItems\" type=\"hidden\" name=\"js[]\" value=\""+$(this).attr('data-value')+"\" />");
		    });
		}
		$(function() {
			makeCssList();
			makeJsList();
			$('.csslist').sortable({
				connectWith: '.csslist'
			}).bind('sortupdate', function(e, ui) {
				makeCssList();	
			}).bind('mouseout', function(e, ui) {
				makeCssList();	
			});
			$('.jslist').sortable({
				connectWith: '.jslist'
			}).bind('sortupdate', function(e, ui) {
				makeJsList();	
			}).bind('mouseout', function(e, ui) {
				makeJsList();	
			});
		});
	</script>
	<style type="text/css">
	.connected li {
			list-style: none;
			border: 1px solid #D6D6D6;
			margin: 1px;
			padding: 2px;
			min-height:16px;
			cursor:move;
			color:#777;
		}
		.connected, .sortable, .exclude, .handles {
			margin: 0;
			padding: 0;
			width: 100%;
			min-height:50px;
			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}
		.list{
			border:1px solid #D6D6D6;
			overflow:hidden;
		}
		.list:hover{
			overflow:auto;
		}
		.usedlist li,.connected li:hover{
			background-color:#fff;
			color:#000;
		}
	</style>
<?php
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
$sql = "SELECT B.idupl FROM $cms_db[lay_upl] A LEFT JOIN $cms_db[upl] B USING(idupl) LEFT JOIN $cms_db[filetype] C ON B.idfiletype=C.idfiletype WHERE idlay='$idlay' AND C.filetype='css' AND B.status IN (4,5) ORDER BY A.idlayupl ASC";
$db->query($sql);
while ($db->next_record()) {
	$used_files['css'][] = $db->f('idupl');
}

// Benutzen JS-Dateien in Array schreiben
$sql = "SELECT B.idupl FROM $cms_db[lay_upl] A LEFT JOIN $cms_db[upl] B USING(idupl) LEFT JOIN $cms_db[filetype] C ON B.idfiletype=C.idfiletype WHERE idlay='$idlay' AND C.filetype='js' AND B.status IN (4,5) ORDER BY A.idlayupl ASC";
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
echo "        <td colspan=\"2\"><table border=0>\n";
echo "          <tr>\n";


$cssIcon = make_image('ressource_browser/icons/rb_typ_css.gif', '', '16', '16', false, 'class="icon"');
$jsIcon = make_image('ressource_browser/icons/rb_typ_js.gif', '', '16', '16', false, 'class="icon"');

// Stylesheet-Dateien suchen
$sql = "SELECT A.idupl, A.filename, A.description FROM $cms_db[upl] A left join $cms_db[filetype] B on A.idfiletype=B.idfiletype WHERE A.idclient='$client' AND B.filetype='css' AND A.status IN (4,5) ORDER BY A.filename";
$db->query($sql);
$used=array();
$notused="";
if ($db->affected_rows()) {
	while ($db->next_record()) {
		if (is_array($used_files['css'])) {
			if (in_array($db->f('idupl'),$used_files['css'])){
				$used[array_search($db->f('idupl'),$used_files['css'])]=sprintf ("<li data-value=\"".$db->f('idupl')."\" selected=\"selected\" title=\"%s\">".$cssIcon.$db->f('filename')."</li>\n", ($db->f('description')) ? $db->f('description') : '');
			}else{
				$notused.=sprintf ("<li data-value=\"".$db->f('idupl')."\" title=\"%s\">".$cssIcon.$db->f('filename')."</li>\n", ($db->f('description')) ? $db->f('description') : '');
			}
 		} else{
			$notused.=sprintf ("<li data-value=\"".$db->f('idupl')."\" title=\"%s\">".$cssIcon.$db->f('filename')."</li>\n", ($db->f('description')) ? $db->f('description') : '');
		}
	}
}
ksort($used);

echo "            <td width=\"200\"><b>".$cms_lang['lay_used']."</b><br />
<ul class=\"csslist connected usedlist list\" style=\"height: 150px; width: 180px;\">".implode("",$used)."</ul>
</td>
<td width=\"200\"><b>".$cms_lang['lay_available']."</b>\n
<ul class=\"csslist connected list no2\" style=\"height: 150px; width: 180px;\">".$notused."</ul>
<!-- select name=\"css[]\" multiple=\"multiple\" size=\"5\"></select -->\n";

echo "            </td>\n";

// Javascript-Dateien suchen
$sql = "SELECT A.idupl, A.filename, A.description FROM $cms_db[upl] A left join $cms_db[filetype] B on A.idfiletype=B.idfiletype WHERE A.idclient='$client' AND B.filetype='js' AND A.status IN (4,5) ORDER BY A.filename";
$db->query($sql);
$used=array();
$notused="";
if ($db->affected_rows()) {	
	while ($db->next_record()) {
		if (is_array($used_files['js'])) {
			if (in_array($db->f('idupl'),$used_files['js'])){ 
				$used[array_search($db->f('idupl'),$used_files['js'])]=sprintf ("<li data-value=\"".$db->f('idupl')."\" selected=\"selected\" title=\"%s\">".$jsIcon.$db->f('filename')."</li>\n", ($db->f('description')) ? $db->f('description') : '');
			}else{
				$notused.=sprintf ("<li data-value=\"".$db->f('idupl')."\" title=\"%s\">".$jsIcon.$db->f('filename')."</li>\n", ($db->f('description')) ? $db->f('description') : '');
			}
 		} else{
			$notused.=sprintf ("<li data-value=\"".$db->f('idupl')."\" title=\"%s\">".$jsIcon.$db->f('filename')."</li>\n", ($db->f('description')) ? $db->f('description') : '');
		}
	}
}
ksort($used);
echo "<td width=\"200\"><b>".$cms_lang['lay_used']."</b><br />
<ul class=\"jslist connected usedlist list\" style=\"height: 150px; width: 180px;\">".implode("",$used)."</ul>
</td>
<td width=\"200\"><b>".$cms_lang['lay_available']."</b>\n
<ul class=\"jslist connected list no2\" style=\"height: 150px; width: 180px;\">".$notused."</ul>\n";

echo "            </td>\n";
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