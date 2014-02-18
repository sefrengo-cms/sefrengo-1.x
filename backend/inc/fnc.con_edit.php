<?PHP
// File: $Id: fnc.con_edit.php 28 2008-05-11 19:18:49Z mistral $
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

function con_edit_save_content ($idsidelang, $container, $number, $idtype, $typenumber, $value) {
	global $db, $auth, $cms_db, $lang, $cfg_client, $cfg_client;

	$author = $auth->auth['uid'];
	
	$value = str_replace("http://cms://", "cms://", $value);
	// strip trailingslashes if they occur in internal links  
	$value = preg_replace('#cms://(idcatside|idcat)=(\d+)/#U', 'cms://\1=\2', $value);
	
	set_magic_quotes_gpc($value);

	// Eintrag in die 'con_content'-Tabelle
	$sql = "SELECT value FROM $cms_db[content] WHERE idsidelang='$idsidelang' AND container='$container' AND number='$number' AND idtype='$idtype' AND typenumber='$typenumber'";
	$db->query($sql);

	// Steht schon Content in der Datenbank?
	if ($db->next_record()) {

		// hat sich was geändert?
		if (addslashes($db->f('value')) != $value) {

			// interne Bildpfade relativ machen
			$in = array("!href=(\\\\)?[\"\']".$cfg_client['htmlpath']."([^\"\'\\\\]*)(\\\\)?[\"\']!i",
				    "!src=(\\\\)?[\"\']".$cfg_client['htmlpath']."([^\"\'\\\\]*)(\\\\)?[\"\']!i");
			$out = array("href=\\1\"\\2\\3\"",
			             "src=\\1\"\\2\\3\"");
			$value = preg_replace($in, $out, $value);

			// wurde überhaupt was eingegeben?
			if ($value != '') {
		         	$sql = "UPDATE $cms_db[content] SET value='$value', author='$author', lastmodified='".time()."' WHERE idsidelang='$idsidelang' AND container='$container' AND number='$number' AND idtype='$idtype' AND typenumber='$typenumber'";
				$db->query($sql);
				$change = 'true';
                         } else {
				$sql = "DELETE FROM ".$cms_db['content']." WHERE idsidelang='$idsidelang' AND container='$container' AND number='$number' AND idtype='$idtype' AND typenumber='$typenumber'";
				$db->query($sql);
				$change = 'true';
                         }
		}
	} else {
		if ($value != '') {
			// neuer Eintrag?
	         	$sql = "INSERT INTO $cms_db[content] (idsidelang, container, number, idtype, typenumber, value, author, created, lastmodified) VALUES('$idsidelang', '$container', '$number', '$idtype', '$typenumber', '$value', '$author', '".time()."', '".time()."')";
			$db->query($sql);
			$change = 'true';
                 }
         }

	if ($change) {
		// Änderungsdatum aktualisieren
		$sql = "UPDATE $cms_db[side_lang] SET lastmodified='".time()."', author='$author' WHERE idsidelang='$idsidelang'";
		$db->query($sql);

		// Seitenkopien suchen
		$sql = "SELECT idcatside FROM $cms_db[side_lang] A LEFT JOIN $cms_db[cat_side] B USING(idside) WHERE A.idsidelang='$idsidelang'";
		$db->query($sql);
		while ($db->next_record()) $list[] = $db->f('idcatside');

		// Status der Seite auf geändert stellen
		change_code_status($list, '1', 'idcatside');
		unset($change);
	}
}

function con_edit_unset_sidelock ($idsidelang, $idtype, $typeid) {
	global $db, $cms_db;

	$sql = "SELECT * FROM $cms_db[content] WHERE idsidelang='$idsidelang' AND idtype='$idtype' AND typeid='$typeid'";
	$db->query($sql);
	if ($db->next_record()) {
		$sql = "UPDATE $cms_db[content] SET edit_ttl = '0' WHERE idsidelang='$idsidelang' AND idtype='$idtype' AND typeid='$typeid'";
		$db->query($sql);
	}
}
?>
