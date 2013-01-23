<?PHP
// File: $Id: inc.con_editframe.php 28 2008-05-11 19:18:49Z mistral $
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


// wenn plugin
if(is_numeric($idplugin)){
	$sql = "SELECT idbackendmenu, entry_url, entry_validate FROM ". $cms_db['backendmenu'] ." WHERE idbackendmenu = $idplugin";
	$db->query($sql);
	$db->next_record();
	$idplugin = $db->f('idbackendmenu');
	$plug_url = $db->f('entry_url');
	$plug_validate = $db->f('entry_validate');
	$dynamic = '$surl = "'.$plug_url. '";';
	eval($dynamic);
	?>
	<html>
	<head>
	  <title>Sefrengo <?PHP echo $cfg_cms['version']; ?></title>
	</head>
	<frameset rows="76,*" border="0" frameborder="0" framespacing="0">
	  <frame name="con_nav" src="<?PHP $sess->purl("main.php?is_plugin=true&area=con_frameheader&idplugin=$idplugin") ?>" scrolling="no" noresize marginwidth="0" marginheight="0" frameborder="0">
	  <frame name="con_content" SRC="<?PHP $sess->purl($surl, 'plugin'.$idplugin, true); ?>" scrolling="auto" noresize frameborder="0">
	</frameset>
	</html>
	<?PHP
// wenn seitenbearbeitung
} else {
	// aktuelle Seite suchen
	if ($idcatside == '') $idcatside = $sid_idcatside;
	
	if ($action == 'contentcopy') {
		function con_contentcopy($from_lang, $to_lang, $idcatside) {
			global $db, $cms_db;
			
			//cast
			$from_lang = (int) $from_lang; $to_lang = (int) $to_lang; $idcatside = (int) $idcatside;
			
			if($from_lang == $to_lang || $from_lang < 1 || $to_lang < 1 || $idcatside < 1) {
				return false;
			}
			
			$db2 = new DB_cms;
			$db3 = new DB_cms;
			
			//echo "$from_lang, $to_lang, $idcatside";
			//find idside
			$sql = "SELECT 
						CS.idside 
					FROM 
						".$cms_db['cat_side']." CS 
					WHERE
						CS.idcatside='$idcatside'";
			 $db->query($sql);
			 if ($db->next_record()) {
			 	$idside = $db->f('idside');
			 } else {
			 	return false;
			 }
			
			//grab the contents
			$table_list = array($cms_db['content'], $cms_db['content_external']);	
			foreach ($table_list AS $current_content_table ) {
				$sql = "SELECT 
							C.idcontent, C.idsidelang, C.container, C.number, C.idtype, C.typenumber, C.value, C.online, 
								C.version, C.author, C.created, C.lastmodified,
								SL.idside
						FROM
							".$current_content_table." C
							LEFT JOIN ".$cms_db['side_lang']." SL USING(idsidelang)
						WHERE
							SL.idlang='$from_lang'
							AND SL.idside = '$idside'";
				$db->query($sql);
				
				 $sql2 = "SELECT 
							SL.idsidelang 
						FROM 
							".$cms_db['side_lang']." SL 
						WHERE
							SL.idlang='$to_lang'
							AND SL.idside = '$idside'";
				 $db2->query($sql2);
				 
				 if ($db2->next_record() ) {
				 	$idsidelang = $db2->f('idsidelang');
				 	
				 	$sql2 ="DELETE FROM ".$current_content_table." 
							WHERE idsidelang='$idsidelang'";
					$db2->query($sql2);
				 	
				 	while ($db->next_record() ) {
					 	$sql3 = "INSERT INTO 
								".$current_content_table." 
									(idsidelang, container, number, idtype, typenumber, value, online, 
										version, author, created, lastmodified)
								VALUES
									('".$db2->f('idsidelang')."', 
										'".$db->f('container')."', '".$db->f('number')."', '".$db->f('idtype')."', 
										'".$db->f('typenumber')."', '".make_string_dump($db->f('value'))."',
										'".$db->f('online')."', '".$db->f('version')."', '".$db->f('author')."',
										'".$db->f('created')."', '".$db->f('lastmodified')."')";
						$db3->query($sql3);
				 	} // end while
				 }// end if $db2
			}// end foreach			
		}// end function
		if ($perm->have_perm(19, 'side', $idcatside, $idcat)) {
			con_contentcopy($sf_contentcopy_from_lang, $lang, $idcatside);
			header ("Location:".$sess->urlRaw("main.php?area=con_editframe&idcatside=$idcatside"));
			exit;
		}
	}

	?>
	<html>
	<head>
	  <title>Sefrengo <?PHP echo $cfg_cms['version']; ?></title>
	</head>
	<frameset rows="120,*" border="0" frameborder="0" framespacing="0">
	  <frame name="con_nav" src="<?PHP $sess->purl("main.php?idcatside=$idcatside&area=con_frameheader") ?>" scrolling="no" noresize marginwidth="0" marginheight="0" frameborder="0">
	  <frame name="con_content" SRC="<?PHP $sess->purl($cfg_client['htmlpath'].$cfg_client['contentfile'].'?lang='.$lang.'&idcatside='.$idcatside.'&view=edit', 'frontendwork', true); ?>" scrolling="auto" noresize frameborder="0">
	</frameset>
	</html>

	<?PHP } ?>