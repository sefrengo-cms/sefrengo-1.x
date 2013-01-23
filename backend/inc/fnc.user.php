<?PHP
// File: $Id: fnc.user.php 56 2008-08-06 20:42:22Z bjoern $
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
// + Revision: $Revision: 56 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes: 
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

function user_set_active($iduser, $is_active) {
	global $db, $cms_db;

	$sql = "UPDATE ".$cms_db['users']." SET is_active='$is_active' WHERE user_id='$iduser'";
	$db->query($sql);
}

function user_save() {
	global $auth, $db, $cms_db, $username, $password, $password_validate, $name, $surname, $email, $group, $iduser, $idgroup, $order, $ascdesc, $oldusername, $comment;
	global $salutation, $street, $street_alt, $zip, $location, $state, $country, $phone, $fax, $mobile, $pager, $homepage, $birthday, $firm, $position, $firm_street, $firm_street_alt, $firm_zip, $firm_location, $firm_state, $firm_country, $firm_email, $firm_phone, $firm_fax, $firm_mobile, $firm_pager, $firm_homepage, $title;

	global $iduser;
	
	// Passwort vergleich
	$password = trim($password);
    $password_validate = trim($password_validate);
    $username = trim($username);
	if ((!empty($password) || (empty($password) && empty($iduser))) 
			&& ($password != $password_validate || strlen($password) < 3)) return 'incorrect';

	// keine Passwort
	if (empty($iduser) && empty($password)) return 'incorrect';

	// Kein Loginname
	if (empty($username)) return 'nologinname';

	// Username auf Existenz prüfen
	if ($username != $oldusername && ! isset($_REQUEST['sf_apply'])) {
		$sql = "SELECT username FROM ".$cms_db['users']." WHERE username='$username' LIMIT 0, 1";
		$db->query($sql);
		if ($db->affected_rows() && $db->f('username') == $username) return 'existusername';
	}
	if (!is_array($group)) {
		$group['0'] = $idgroup;
	}

	// Wenn Sysadmin gewählt wurde, alle anderen kicken
	if (in_array ('2', $group)) {
			unset($group);
			$group['0'] = '2';
	}
    
    $current_time = time();
	set_magic_quotes_gpc($username);
	set_magic_quotes_gpc($name);
	set_magic_quotes_gpc($surname);
	set_magic_quotes_gpc($email);
	set_magic_quotes_gpc($password);
	set_magic_quotes_gpc($salutation);
	set_magic_quotes_gpc($title);
	set_magic_quotes_gpc($street);
	set_magic_quotes_gpc($street_alt);
	set_magic_quotes_gpc($zip);
	set_magic_quotes_gpc($location);
	set_magic_quotes_gpc($state);
	set_magic_quotes_gpc($country);
	set_magic_quotes_gpc($phone);
	set_magic_quotes_gpc($fax);
	set_magic_quotes_gpc($mobile);
	set_magic_quotes_gpc($pager);
	set_magic_quotes_gpc($homepage);
	set_magic_quotes_gpc($birthday);
	set_magic_quotes_gpc($firm);
	set_magic_quotes_gpc($position);
	set_magic_quotes_gpc($firm_street);
	set_magic_quotes_gpc($firm_street_alt);
	set_magic_quotes_gpc($firm_zip);
	set_magic_quotes_gpc($firm_location);
	set_magic_quotes_gpc($firm_state);
	set_magic_quotes_gpc($firm_country);
	set_magic_quotes_gpc($firm_email);
	set_magic_quotes_gpc($firm_phone);
	set_magic_quotes_gpc($firm_fax);
	set_magic_quotes_gpc($firm_mobile);
	set_magic_quotes_gpc($firm_pager);
	set_magic_quotes_gpc($firm_homepage);
	set_magic_quotes_gpc($comment);

	// Besteht User bereits?
	if (!empty($iduser)) {
		if ($iduser > 1) {
			$sql = "DELETE FROM ".$cms_db['users_groups']." WHERE user_id='$iduser'";
		}
		$db->query($sql);
		$password_sql = (!empty($password)) ? ", password='".md5($password).'\'' : '';
		$sql = "UPDATE ".$cms_db['users']." SET
				username='$username',
				lastmodified='$current_time',
				lastmodified_author = '".$auth->auth['uid']."',
				name='$name',
				surname='$surname',
				email='$email',
				salutation='$salutation',
				title='$title',
				street='$street',
				street_alt='$street_alt',
				zip='$zip',
				location='$location',
				state='$state',
				country='$country',
				phone='$phone',
				fax='$fax',
				mobile='$mobile',
				pager='$pager',
				homepage='$homepage',
				birthday='$birthday',
				firm='$firm',
				position='$position',
				firm_street='$firm_street',
				firm_street_alt='$firm_street_alt',
				firm_zip='$firm_zip',
				firm_location='$firm_location',
				firm_state='$firm_state',
				firm_country='$firm_country',
				firm_email='$firm_email',
				firm_phone='$firm_phone',
				firm_fax='$firm_fax',
				firm_mobile='$firm_mobile',
				firm_pager='$firm_pager',
				firm_homepage='$firm_homepage',
				comment='$comment'$password_sql
			WHERE user_id ='$iduser'";
		$db->query($sql);
		
		$sf_user =& sf_factoryGetObject('ADMINISTRATION', 'User');
        $sf_user->loadByIduser($iduser);
        fire_event( 'user_update', $sf_user->data );
        unset($sf_user);
	} else {
         	$sql = "INSERT INTO ".$cms_db['users']."
				(username, password, name, created, author, lastmodified, lastmodified_author, surname, email, is_active, is_deletable, salutation, title, street,
				street_alt, zip, location, state, country, phone, fax, mobile, pager, homepage, birthday,
				firm, position, firm_street, firm_street_alt, firm_zip, firm_location, firm_state, firm_country,
				firm_email, firm_phone, firm_fax, firm_mobile, firm_pager, firm_homepage, comment)
               		VALUES
         	                ('$username', '".md5($password)."', '$name', '$current_time', '".$auth->auth['uid']."', '$current_time', '".$auth->auth['uid']."', '$surname', '$email','1', '1', '$salutation','$title',
				'$street', '$street_alt', '$zip', '$location', '$state', '$country', '$phone', '$fax',
				'$mobile', '$pager', '$homepage', '$birthday', '$firm', '$position', '$firm_street',
				'$firm_street_alt', '$firm_zip', '$firm_location', '$firm_state', '$firm_country',
				'$firm_email', '$firm_phone', '$firm_fax', '$firm_mobile', '$firm_pager', '$firm_homepage',
				'$comment')";
		$db->query($sql);
		$sql = "SELECT user_id FROM ".$cms_db['users']." WHERE username='$username'";
		$db->query($sql);
		$db->next_record();
		$iduser = $db->f('user_id');
		
		$sf_user =& sf_factoryGetObject('ADMINISTRATION', 'User'); 
        $sf_user->loadByIduser($iduser);
        fire_event( 'user_create', $sf_user->data );
        unset($sf_user);
	}
	if ($iduser > 1) {
		foreach ($group as $value) {
			$value = (int) $value;
			//hide group --kein-- 
			if ($value < 2) {
				continue;
			}
			$sql = "INSERT INTO ".$cms_db['users_groups']." VALUES ('', '$iduser', '$value')";
			$db->query($sql);
		}
	}
}

function user_delete() {
	global $db, $cms_db, $idgroup, $iduser;
	$iduser = (int) $iduser;

	$sql = "SELECT user_id FROM ".$cms_db['users']." WHERE user_id='$iduser'";
	$db->query($sql);
	$db->next_record();
	if ($db->f('user_id') == $iduser && $iduser > 2) {
		$sf_user =& sf_factoryGetObject('ADMINISTRATION', 'User'); 
        $sf_user->loadByIduser($iduser);
        fire_event( 'user_delete', $sf_user->data );
        unset($sf_user);
		
		
		$sql = "DELETE FROM ".$cms_db['users_groups']." WHERE user_id='$iduser'";
		$db->query($sql);

		if (!$db->affected_rows()) {
			$sql = "DELETE FROM ".$cms_db['users']." WHERE user_id='$iduser'";
			$db->query($sql);
			
			$update_data = array( $cms_db['cat_lang'], $cms_db['clients'], $cms_db['clients_lang'],
							   $cms_db['content'], $cms_db['css'], $cms_db['directory'],
							   $cms_db['filetype'], $cms_db['js'], $cms_db['lang'],
							   $cms_db['mod'], $cms_db['side_lang'], $cms_db['tpl'], $cms_db['upl']
							 );
			foreach($update_data AS $v)
			{
				$sql = "UPDATE ". $v . "
						SET author = 1
						WHERE author=$iduser";
				$db-> query($sql);
			}
			
        }
	}
}
?>