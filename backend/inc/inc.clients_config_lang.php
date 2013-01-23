<?PHP

if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

include('inc/class.values_ct_edit.php');
$perm->check(4, 'clients', $cid);
include('inc/inc.header.php');

echo "<!-- Anfang inc.clients_config_lang.php -->\n";
echo "<div id=\"main\">\n";
echo "<div class=\"forms\">\n";
echo "<a class=\"action\" href=\"".$sess->url('main.php?area=clients&collapse='.$cid)."\">".$cms_lang['gen_back']."</a>\n";
echo "</div>\n";
echo "    <h5>Projekte - Sprache konfigurieren</h5>\n";
if ($errno) echo "<p class=\"errormsg\">".$cms_lang["err_$errno"]."</p>";


$output = new values_ct_edit(array(
				   'sqlgroup'  		=> 'cfg_lang',
				   'client'  		=> $cid,
				   'extra_url_args' => '&cid='.$cid .'&lid='.$lid .'&collapse='.$cid,
				   'lang'   		=> $lid,
				   'perm_edit'		=> 'area_settings_edit',
				   'tpl_file'  		=> 'settings.tpl',
				   'table_cellpadding'	=> $cellpadding,
				   'table_cellspacing'	=> $cellspacing,
				   'table_border'  	=> $border,
				   'area'   		=> 'clients_config_lang',
				   'action'   		=> $action,
				   'view'      		=> 'use',
				   'prefix'    		=> '$cfg_lang'));
$output -> start();
?>