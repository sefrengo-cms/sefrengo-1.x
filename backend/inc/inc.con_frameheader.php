<?PHP
// File: $Id: inc.con_frameheader.php 28 2008-05-11 19:18:49Z mistral $
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


include('inc/inc.header.php');
// wenn seite bearbeiten
if(empty($is_plugin)) {
	
	//make content copy select select
	$sql = "SELECT 
				A.idlang, A.name
			FROM 
				".$cms_db['lang']." A 
				LEFT JOIN ".$cms_db['clients_lang']." B USING(idlang) 
			WHERE 
				B.idclient='$client'
				AND A.idlang != '$lang' 
			ORDER BY idlang";

	$db->query($sql);
	if ($db->num_rows() > 0 ) {
		$options = '<option value="">Inhalte &uuml;bernehmen aus...</option>'."\n";
		while( $db->next_record() ) {
			$options .= '<option value="'.$db->f('idlang').'">'.$db->f('name').'</option>'."\n";

		}

		$select_disabled = $_REQUEST['unfreeze'] == 'true' ? '':'disabled="disabled"';
		$select_contentcopy = '<select id="sf_cc_select" '. $select_disabled .' name="sf_contentcopy_from_lang"  onchange="if(this.options[this.selectedIndex].value != \'\' && sf_confirmContentcopy(this.options[this.selectedIndex].text)){this.form.submit()}">'.$options .'</select>';
		$form_contentcopy_start = '<form action="'.$sess->url('main.php?area=con_editframe&action=contentcopy').'" method="post"  name="contentcopy" target="_top">
							<input type="hidden" id="sf_cc_idcatside" name="idcatside" value="'.$idcatside.'" />
							<input type="hidden" id="sf_cc_to_lang" name="to_lang" value="'.$lang.'" />';
		$form_contentcopy_stop = '</form>';
	} else {
		$select_contentcopy = '';
		$form_contentcopy_start = '';
		$form_contentcopy_stop = '';
	
	}
	
?>
<!-- Anfang inc.con_frameheader.php -->
<div id="main">
		<div class="forms">
		<?php echo $form_contentcopy_start ?>
		<?php echo $select_contentcopy ?>
		<?php echo $form_contentcopy_stop ?>
		<a target="_parent" class="action" href="<?php echo $sess->url("main.php?area=con") ?>"><?php echo $cms_lang['con_back_to_overview']; ?></a>
		</div>
		<h5><?PHP echo $cms_lang['area_con_edit']; ?></h5>
		<?PHP if ($errno) {echo "<p class=errormsg>".$cms_lang["err_$errno"]."</p>";} ?>

	<script type="text/javascript">
	function sf_setCurrentIdcatside(idcatside, is_disabled, idlang) {
		var sel;
		//alert(123);
		try {
			//set idcatside
			sel = document.getElementById('sf_cc_idcatside');
			sel.value = idcatside;
			
			//check idlang
			sel = document.getElementById('sf_cc_to_lang');
			if (idlang != sel.value) {
				is_disabled = true
				window.location = '<?php echo $sess->urlRaw($cfg_cms['cms_html_path'] . 'main.php?place=holder') ?>&idcatside='+idcatside+'&area=con_frameheader&unfreeze=true&lang=' + idlang
			}
	
			
			//set select enabled or disabled
			sel = document.getElementById('sf_cc_select');
	
			if (is_disabled) {
				if(! sel.disabled) {
					var attr = document.createAttribute('disabled');
					attr.value = 'disabled';
					sel.setAttributeNode(attr);
				}
			} else {
				if(sel.disabled) {
					sel.removeAttribute('disabled');
				}
			}
		} catch (e) {
			//sleeping
		}
	}

	function sf_disablaLangsync() {
		sel = document.getElementById('sf_cc_select');

		if(! sel.disabled) {
				var attr = document.createAttribute('disabled');
				attr.value = 'disabled';
				sel.setAttributeNode(attr);
		}
	}

	
	function sf_confirmContentcopy(lang) {
		return confirm("Sollen wirklich alle Inhalte aus der Sprache '"+ lang 
						+ "' in die aktuelle Seite \u00fcbernommen werden?"+"\n"
						+ "Alle derzeitigen Inhalte der Seite werden gel\u00F6scht!\n" 
						+ "Dieser Vorgang kann nicht r\u00FCckg\u00E4ngig gemacht werden!");
	}
	</script>

</div>
	</body>
	</html>
<?PHP
// wenn plugin
} else {
?>
	</body>
	</html>
<?PHP
}
