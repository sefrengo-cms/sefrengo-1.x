<?php
// File: $Id: local.php 28 2008-05-11 19:18:49Z mistral $
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

class DB_cms extends DB_Sql {
    var $Host, $Database, $User, $Password, $Halt_On_Error = 'report'; 
    // konstruktor
    function DB_cms() {
        global $cfg_cms;

        $this->Host = $cfg_cms['db_host'];
        $this->Database = $cfg_cms['db_database'];
        $this->User = $cfg_cms['db_user'];
        $this->Password = $cfg_cms['db_password'];
        $this->PConnect = $cfg_cms['db_mysql_pconnect'];
    } 
    // trigger for haltmessages
    function haltmsg( $msg ) {
        global $deb;

        $msg = sprintf( "%s: error %s (%s) - %s\n",
            date( 'Y-m-d (D) H:i:s' ), $this->Errno, $this->Error, $msg );
        $deb->collect( 'MySql-Error:' . $msg, 'error', true );
    } 
		// extend phplib 2 adoDb functionality
    var $fetch_mode = 'DB_FETCH_ARRAY';
    var $EOF = false;
    var $cache = array();
    var $cache_test = array();
    var $cache_mode = '';
    var $cache_id = 0;
    var $cache_group = '';
    var $cache_groups = array();
    var $cache_item = '';
    var $cache_items = array();
    var $use_cache = false;
    var $cache_time = 60;
    var $force_overide_cache = false;
    var $cache_name = 'db_cache';
    var $cache_test_value = 50;
    var $cache_db = false;
    var $figure_db = false;
    var $cache_gc_probability = 5; 
    // go 1 step back
    function seek_last() {
        return $this->seek( -1 );
    } 
    // call next recordset
    function next_record() {
        $this->EOF = false;
        if ( !$this->Query_ID ) {
            $this->EOF = true; // set EOF trigger
            $this->halt( "next_record called with no query pending." );
            $this->cache_mode = '';
            return 0;
        } 
        // select fetchmode
        if ( $this->cache_mode == 'DB_READ_CACHE' ) {
            $this->Record = $this->cache[$this->Row];
        } elseif ( $this->fetch_mode == 'DB_FETCH_ARRAY' ) {
            $this->Record = @mysql_fetch_array( $this->Query_ID );
        } elseif ( $this->fetch_mode == 'DB_FETCH_ASSOC' ) {
            $this->Record = @mysql_fetch_assoc( $this->Query_ID );
        } 
        $this->Row += 1;
        $this->Errno = mysql_errno();
        $this->Error = mysql_error();
        $stat = is_array( $this->Record );
        if ( !$stat && $this->Auto_Free && !$this->EOF ) {
            $this->free();
        } elseif ( $this->cache_mode == 'DB_STORE_CACHE' || $this->cache_mode == 'DB_TEST_CACHE' ) {
            $this->store_cache();
        } 
        return $stat;
    } 
    function init_cache() {
        global $cfg_cms, $cfg_client;
		
		if(is_array($cfg_client) == FALSE)
			return;

		$this->use_cache = ( $cfg_client['db_cache_enabled'] != '0' && $cfg_cms['db_cache_enabled'] == '1' ) ? true : false;
		$this->cache_name = ( $cfg_client['db_cache_name'] != '' ) ? $cfg_client['db_cache_name'] : ( ( $cfg_cms['db_cache_name'] != '' ) ? $cfg_cms['db_cache_name'] : $this->cache_name );
		$this->cache_groups = ( is_array( $cfg_client['db_cache_groups'] ) ) ? array_merge( $cfg_cms['db_cache_groups'], $cfg_client['db_cache_groups'] ) : $cfg_cms['db_cache_groups'];
		$this->cache_items = ( is_array( $cfg_client['db_cache_items'] ) ) ? array_merge( $cfg_cms['db_cache_items'], $cfg_client['db_cache_items'] ) : $cfg_cms['db_cache_items'];
    } 
    // store a cache result
    function store_cache() {
        if ( $this->cache_id ) {
            if ( $this->cache_mode == 'DB_STORE_CACHE' ) {
                array_push( $this->cache, $this->Record );
            } else {
                array_push( $this->cache_test, $this->Record );
            } 
        } 
    } 
    // write the cache
    function write_cache() {
        global $cms_db, $cfg_cms;

        $action = 'insert';
        if ( $this->cache_mode == 'DB_TEST_CACHE' ) {
            if ( count( $this->cache_test ) >= $this->cache_test_value ) {
                $this->cache = $this->cache_test;
                $this->cache_test = array();
            } else $this->cache_id = 0;
        } 
        if ( $this->cache_id && $this->use_cache ) {
            if ( !$this->cache_db ) $this->cache_db = new DB_cms;
            if ( $this->cache_db->read_cache( $this->cache_id, true ) ) $action = $force_overide_cache == true ? 'update': 'ignore';
            $this->cache_mode = 'DB_WRITE_CACHE';
            $now = date( "YmdHis", time() ); 
            // bb hack for timemanagment start
            if( $this->cache_group == 'frontend' ) {
                $this->_figure_out_cachetime_for_frontend();
            } 
            // bb hack for timemanagment stop
            switch ( $action ) {
                case 'insert':
                    $sql = "REPLACE INTO
												 " . $cms_db['db_cache'] . " (sid, name, val, changed, releasetime, groups, item)
													VALUES (
													'" . addslashes( $this->cache_id ) . "',
													'" . addslashes( $this->cache_name ) . "',
													'" . addslashes( serialize( $this->cache ) ) . "',
													'" . $now . "',
													'" . $this->cache_time . "',
													'" . addslashes( $this->cache_group ) . "',
													'" . addslashes( $this->cache_item ) . "')";
                    $this->cache_db->query( $sql );
                    break;
                case 'update':
                    $sql = "UPDATE
												 " . $cms_db['db_cache'] . " SET 
													val = '" . addslashes( serialize( $this->cache ) ) . "', 
													groups = '" . addslashes( $this->cache_group ) . "',
													item = '" . addslashes( $this->cache_item ) . "',
													changed = '" . $now . "',
													releasetime = '" . $this->cache_time . "'
													WHERE
													name = '" . addslashes( $this->cache_name ) . "'
													AND
													sid = '" . addslashes( $this->cache_id ) . "'";
                    $this->cache_db->query( $sql );
                    break;
            } 
            $this->cache = array();
            $this->cache_id = 0;
            $this->cache_group = '';
            $this->cache_item = '';
            $this->cache_mode = '';
        } else $this->cache_mode = '';
    } 
    // delete old cache
    function delete_cache( $Cache_var = false ) {
        global $cms_db, $cache_gc, $cfg_cms;
        static $cache_gc;

        $this->init_cache();
        if ( ( !$cache_gc || $Cache_var ) && $this->use_cache ) {
            if ( $Cache_var !== true && $Cache_var != '' ) {
                list( $Cache_group, $Cache_item ) = explode( '_', $Cache_var );
            } 
            if ( !$this->garbage_cache( $Cache_var ) ) return;
            $now = date( "YmdHis", time() );
            if ( $Cache_group != '' && is_numeric( $this->cache_groups["$Cache_group"] ) ) {
                $delete = " AND groups = '" . addslashes( $Cache_group ) . "'";
                if ( $Cache_item != '' && is_numeric( $this->cache_items["$Cache_group"]["$Cache_item"] ) ) {
                    $delete .= " AND item = '" . addslashes( $Cache_item ) . "'";
                } 
            } elseif ( $Cache_var !== true ) {
                $delete = " AND changed + (releasetime * 60) < " . $now . " AND releasetime <> 0" ;
            } 
            $sql = "DELETE FROM
								 " . $cms_db['db_cache'] . " WHERE
									 name = '" . addslashes( $this->cache_name ) . "'
								 " . $delete . "";
            $this->query( $sql );
            $cache_gc = true;
        } 
    } 
    // real garbage collection
    function garbage_cache( $force = false ) {
        static $cache_gc = 0;
        srand( ( double ) microtime() * 1000000 ); 
        // time and probability based
        if ( ( $force ) || ( $cache_gc && $cache_gc < ( time() + $this->cache_time ) ) || ( rand( 1, 100 ) < $this->cache_gc_probability ) ) {
            $cache_gc = time();
            return true;
        } 
    } 
    // stats the cache
    function stat_cache( $Cache_var = '' ) {
        global $cms_db, $cfg_cms;

        $this->init_cache();
        if ( $this->use_cache ) {
            if ( !$this->cache_db ) $this->cache_db = new DB_cms;
            $return = array();
            $now = date( "YmdHis", time() );
            list( $Cache_group, $Cache_item ) = explode( '_', $Cache_var );
            if ( $Cache_group != '' && is_numeric( $this->cache_groups["$Cache_group"] ) ) {
                $where = " AND
											groups = '" . addslashes( $Cache_group ) . "'";
                if ( $Cache_item != '' && is_numeric( $this->cache_items["$Cache_group"]["$Cache_item"] ) ) {
                    $where .= " AND
													item = '" . addslashes( $Cache_item ) . "'";
                } 
            } 
            $sql = "SELECT SUM(length(val)) AS stat, COUNT(sid) AS count FROM
								 " . $cms_db['db_cache'] . " WHERE
									 name = '" . $this->cache_name . "'
								 " . $where . "";
            if ( !$this->cache_db->query( $sql ) ) return;
            $oldmode = $this->cache_db->get_fetch_mode();
            $this->cache_db->set_fetch_mode( 'DB_FETCH_ASSOC' );
            if( $this->cache_db->next_record() ) {
                $return = $this->cache_db->this_record();
            } 
            $this->cache_db->set_fetch_mode( $oldmode );
            return $return;
        } 
    } 
    // reads the cache back
    function read_cache( $cache_id, $check = false ) {
        global $cms_db;

        if ( $cache_id && $this->use_cache ) {
            if ( !$this->cache_db ) $this->cache_db = new DB_cms;
            $return = false;
            $sql = "SELECT val FROM
								 " . $cms_db['db_cache'] . " WHERE
									 name = '" . addslashes( $this->cache_name ) . "'
									 AND
									 sid =  '" . addslashes( $cache_id ) . "'";
            if ( !$this->cache_db->query( $sql ) ) return;
            $oldmode = $this->cache_db->get_fetch_mode();
            $this->cache_db->set_fetch_mode( 'DB_FETCH_ASSOC' );
            if( $this->cache_db->next_record() ) {
                if ( $check ) {
                    $return = true;
                } else {
                    $cache_pre = $this->cache_db->this_record();
                    $cache_val = $cache_pre['val'];
                    $cache = unserialize( stripslashes( $cache_val ) );
                    if ( is_array( $cache ) ) {
                        $this->cache = $cache;
                        $return = true;
                    } 
                } 
            } 
            $this->cache_db->set_fetch_mode( $oldmode );
            return $return;
        } else $this->cache_mode = '';
    } 
    // free result
    function free() {
        @mysql_free_result( $this->Query_ID );
        $this->Query_ID = 0;
        $this->delete_cache();
        $this->write_cache();
    } 
    // set fetchmode
    function set_fetch_mode( $mode = 'DB_FETCH_ARRAY' ) {
        if ( $mode != $this->fetch_mode AND ( $mode == 'DB_FETCH_ARRAY' OR $mode == 'DB_FETCH_ASSOC' ) ) {
            $this->fetch_mode = $mode;
        } 
    } 
    // return the complete array of result
    function this_record() {
        return $this->Record;
    } 
    // return the current fetchmode
    function get_fetch_mode() {
        return $this->fetch_mode;
    } 
    // return the EOF trigger
    function EOF() {
        return $this->EOF;
    } 
    // return the last insert id
    function insert_id() {
        // if (mysql_affected_rows($this->Link_ID)>0) return mysql_insert_id($this->Link_ID);
        return mysql_insert_id( $this->Link_ID );
    } 
    // fetch a query in a array
    function fetch_query( $Sql, $Mode = 'DB_FETCH_ASSOC' ) {
        $affect = $this->query( $Sql );
        if ( !$affect || $affect < 1 ) return 0;
        $oldmode = $this->get_fetch_mode();
        $this->set_fetch_mode( $Mode );
        $return = array();
        while ( $this->next_record() ) array_push( $return, $this->this_record() );
        $this->set_fetch_mode( $oldmode );
        return $return;
    } 
    // query by order
    function query( $Query_String, $Cache_it = 0, $Cache_var = '' ) {
        global $deb, $cfg_cms;

        $this->init_cache();
        // no empty queries, please, since PHP4 chokes on them.
        if ( $Query_String == "" )
				/* the empty query string is passed on from the constructor,
			   * when calling the class without a query, e.g. in situations
			   * like these: '$db = new DB_Sql_Subclass;'
			   */
            return 0;
        if ( !$this->connect() ) {
            return 0;
            // we already complained in connect() about that.
        } 
        // new query, discard previous result.
        if ( $this->Query_ID ) {
            $this->free();
        } 
        if( $Cache_it >= 1 && $this->use_cache ) {
            $cache = md5( trim( $Query_String ) );
            if ( $this->read_cache( $cache ) ) {
                $this->cache_mode = 'DB_READ_CACHE';
                $this->Row = 0;
                if ( $this->Debug ) {
                    $deb->collect( 'Cache: ' . $Query_String, 'cache' );
                } 
                return ( $this->Query_ID = 1 );
            } 
        } 
        if ( $this->Debug ) {
            $deb->collect( 'MySql: ' . $Query_String, 'sql' );
        } 
        $this->Query_ID = @mysql_query( $Query_String, $this->Link_ID );
        $this->Row = 0;
        $this->Errno = mysql_errno();
        $this->Error = mysql_error();
        if ( !$this->Query_ID ) {
            $this->halt( "Invalid SQL: " . $Query_String );
        } elseif( $Cache_it >= 1 && $this->use_cache ) {
            list( $Cache_group, $Cache_item ) = explode( '_', $Cache_var );
            $this->cache_mode = $Cache_it == 1 ? 'DB_STORE_CACHE' : 'DB_TEST_CACHE';
            $this->cache_id = md5( trim( $Query_String ) );
            if ( is_numeric( $this->cache_groups["$Cache_group"] ) ) {
                $this->cache_group = $Cache_group;
                if ( is_numeric( $this->cache_items["$Cache_group"]["$Cache_item"] ) ) {
                    $this->cache_item = $Cache_item;
                    $this->cache_time = $this->cache_items["$Cache_group"]["$Cache_item"];
                } else {
                    $this->cache_time = $this->cache_groups["$Cache_group"];
                } 
            } elseif ( is_numeric( $Cache_var ) ) {
                $this->cache_time = ( int ) $Cache_var;
            } elseif ( is_numeric( $this->cache_groups["default"] ) ) {
                $this->cache_time = $this->cache_groups["default"];
            } 
        } 
        // will return nada if it fails. that's fine.
        return $this->Query_ID;
    } 
    // private
    function _figure_out_cachetime_for_frontend() {
        global $cms_db, $cfg_cms;

        if ( !$this->figure_db ) $this->figure_db = new DB_cms;
        $sql = "SELECT SL.start, SL.end
    			FROM
    				" . $cms_db['cat_side'] . " CS,
    				" . $cms_db['side_lang'] . " SL
    			WHERE
    				CS.idside = SL.idside
    				AND SL.idlang = " . $GLOBALS['lang'] . "
    				AND (SL.online & 0x02) = 0x02
    				AND ( SL.start >= UNIX_TIMESTAMP(NOW())
						OR SL.end >= UNIX_TIMESTAMP(NOW()) )";
        $this->figure_db->query( $sql );
        if( ! $this->figure_db->num_rows() > 0 ) return;
        $now = time();
        $smallest_t = '';
        while( $this->figure_db->next_record() ) {
            $s = $this->figure_db->f( 'start' );
            $e = $this->figure_db->f( 'end' );
            $t = ( $s < $e && $s > $now ) ? $s:$e;
            if( empty( $smallest_t ) ) {
                $smallest_t = $t;
            } else {
                $smallest_t = ( $t < $smallest_t ) ? $t:$smallest_t;
            } 
        } 
        $minutes_to_next_timemanagmentrun = ceil( ( $smallest_t - $now ) / 60 ); 
        // DebugMe! echo $smallest_t." X ". $minutes_to_next_timemanagmentrun .'<br>';
        $this->cache_time = ( $minutes_to_next_timemanagmentrun < $this->cache_time && $minutes_to_next_timemanagmentrun > 0 ) ? $minutes_to_next_timemanagmentrun:$this->cache_time;
    } 
} 

class cms_CT_Sql extends CT_Sql {
    var $database_class = 'DB_cms'; // Which database to connect...
    
    function ac_checkme($id, $name) {
        global $cms_db;

        $ret = true;
        $cquery = sprintf("select count(*) from %s where sid='%s' and name='%s'",
            $cms_db['sessions'],
            addslashes($id),
            addslashes($name));
        $squery = sprintf("select sid from %s where sid  = '%s' and name = '%s'",
            $cms_db['sessions'],
            addslashes($id),
            addslashes($name));
        $this->db->query($squery);
        if ( $this->db->affected_rows() == 0
            && $this->db->query($cquery)
	        && $this->db->next_record() && $this->db->f(0) == 0 ) {
            // nothing found here
            $ret = false;
        }
        return $ret;
    }
    function ac_newid($str, $name) {
        if( !$this->ac_checkme($str, $name) ) return $str;
    }
    function ac_sigleme($str, $name, $id) {
        global $cms_db, $sess;

        $sess->gc( true );
	    if( $id >= 1 && $this->session_enabled ) {
            $this->db->query(sprintf("delete from %s where name = '%s' and sid != '%s' and user_id = '%s'",
                $cms_db[sessions],
                addslashes($name),
                addslashes($str),
                addslashes($id)));
        }
    }
    function ac_sigleid($name, $id) {
        global $cms_db, $sess;

        $sess->gc( true );
        $ret = true;
        if( $id >= 1 ) {
            $ret = false;
            $cquery = sprintf("select count(*) from %s where user_id='%s' and name='%s'",
                $cms_db['sessions'],
                addslashes($id),
                addslashes($name));
            $squery = sprintf("select sid from %s where user_id='%s' and name='%s'",
                $cms_db['sessions'],
                addslashes($id),
                addslashes($name));
            $this->db->query($squery);
            if ( $this->db->affected_rows() == 0
                && $this->db->query($cquery)
    	        && $this->db->next_record() && $this->db->f(0) == 0 ) {
                // nothing found here
                $ret = true;
            }
        }
        return $ret;
    }
} 

class cms_Backend_Session extends Session {
    var $classname = 'cms_Backend_Session';
    var $cookiename = 'sefrengo'; // Name des Cookies
    var $name = 'cms';
    var $magic = 'backend_Session'; // beliebiger Name zur Verschl?sselung
    var $mode = 'get'; // default Modus der Session-ID
    var $fallback_mode = 'cookie'; // falls default Modus abgelehnt wird
    var $lifetime = '0'; // 0 = do session cookies, else minutes
    var $refresh = '0'; // 0 = no refresh, else minutes
    var $that_class = 'cms_CT_Sql'; // name of data storage container
    var $gc_probability = '5'; // Wahrscheinlichkeit der Sessionlöschung
    var $session_enabled = true; // Session an, bzw. ausschalten
    var $block_alien_side = true;
    var $use_token_sid;
    
    function cms_Backend_Session() {
       global $cfg_cms;
       
       $this->use_token_sid = false; // this is BUGGY! Not for use!
       $this->cookie_domain = $cfg_cms['session_backend_domain'];
    }
} 

class cms_Frontend_Session extends Session {
    var $classname = 'cms_Frontend_Session';
    var $cookiename = 'sid'; // Name des Cookies
    var $name = 'sid';
    var $magic = 'frontend_Session'; // beliebiger Name zur Verschl?sselung
    var $mode = 'cookie'; // default Modus der Session-ID
    var $fallback_mode = 'get'; // falls default Modus abgelehnt wird
    var $lifetime = '0'; // 0 = do session cookies, else minutes
    var $refresh = '0'; // 0 = no refresh, else minutes
    var $that_class = 'cms_CT_Sql'; // name of data storage container
    var $gc_probability = '5'; // Wahrscheinlichkeit der Sessionlöschung
    var $session_enabled = true; // Session an, bzw. ausschalten
    var $block_alien_side = true;
    var $use_token_sid;
    
    function cms_Frontend_Session() {
        global $cfg_client;

        if( $cfg_client['session_enabled'] == '0' ) $this->session_enabled = false;
        $this->cookie_domain = $cfg_client['session_frontend_domain'];
    } 
} 

class cms_Backend_Auth extends Auth {
    var $classname = 'cms_Backend_Auth';
    var $database_class = 'DB_cms';
    var $lifetime;
    var $refresh;
    var $force_single_login;
    
    // konstruktor, set session lifetime & refresh
    function cms_Backend_Auth() {
        global $cfg_cms;

        $this->force_single_login = false;
        $this->refresh = ( 2* ceil($cfg_cms['session_backend_lifetime']/3) ); # 2/3
        $this->lifetime = $cfg_cms['session_backend_lifetime'];
    } 
    function auth_loginform() {
        global $cfg_cms, $cfg_client, $view, $lang, $sess;

        if ( $view ) {
            sf_header_redirect($sess->url($cfg_cms['cms_html_path'].'main.php'), false);
        } else {
        	include( 'tpl/' . $cfg_cms['skin'] . '/loginform.tpl' );
        }
    } 
    function auth_validatelogin() {
        global $challengefail, $challenge, $doublelogin, $username, $password, $cms_db, $sess;
		
		$sf_user =& sf_factoryGetObject('ADMINISTRATION', 'User'); 
		$sf_user->setUpdateLastmodifiedMeta(false);
        $sf_user->loadByUsernamePassword($username, $password, true);
        unset($sf_user);
		
        if ( isset( $username ) ) $this->auth['uname'] = trim( $username );
        elseif ( $this->nobody ) {
            $uid = $this->auth['uname'] = $this->auth['uid'] = 'nobody';
            return $uid;
        }
        if ( isset( $challenge ) ) {
            if( !$sess->challenge_me( $challenge ) ) {
                $challengefail = true;
                // Event
                fire_event( 'login_challenge_fail', array ( 'username' => $username, 'password' => $password, 'challenge' => $challenge ) );
                return false;
            }
        } 
        // User aus der Datenbank suchen
        set_magic_quotes_gpc( $username ); 
        $this->db->query( "
                    SELECT DISTINCT salutation,
					street,
					street_alt,
					zip,
					location,
					state,
					country,
					phone,
					fax,
					mobile,
					pager,
					homepage,
					birthday,
					firm,
					position,
					firm_street,
					firm_street_alt,
					firm_zip,
					firm_location,
					firm_state,
					firm_country,
					firm_email,
					firm_phone,
					firm_fax,
					firm_mobile,
					firm_pager,
					firm_homepage,
					comment, A.user_id, password, A.name, surname, email, C.name AS groupname, C.description 
					FROM 
						" . $cms_db['users'] . " A 
						LEFT JOIN " . $cms_db['users_groups'] . " B USING(user_id) 
						LEFT JOIN " . $cms_db['groups'] . " C USING(idgroup) 
						LEFT JOIN " . $cms_db['perms'] . " D USING(idgroup) 
					WHERE 
						A.username='$username' 
						AND A.password='" . md5( $password ) . "' 
						AND A.is_active='1' 
						AND C.is_active='1' 
						AND ((D.type='cms_access' AND D.id = 'area_backend' AND D.perm = 1) OR C.is_sys_admin='1') LIMIT 0, 1
				" ); 
        if ( $this->db->next_record() ) { 
            // Use Single Login
            if( $this->force_single_login ) {
              if( !$sess->single_id( $this->db->f( 'user_id' ) ) ) {
                $doublelogin = true;
                // Event
                fire_event( 'login_single_fail', array ( 'username' => $username, 'password' => $password ) );
                return false;
              }
            }
            // Event
            fire_event( 'login_success', array ( 'uid' => $this->db->f( 'user_id' ) ) );
            $this->auth['name'] = $this->db->f( 'name' );
            $this->auth['surname'] = $this->db->f( 'surname' );
            $this->auth['group_name'] = $this->db->f( 'groupname' );
            $this->auth['group_desc'] = $this->db->f( 'description' );
            $this->auth['email'] = $this->db->f( 'email' ); 
            $this->auth['salutation'] = $this->db->f( 'salutation' );
            $this->auth['street'] = $this->db->f( 'street' );
            $this->auth['street_alt'] = $this->db->f( 'street_alt' );
            $this->auth['zip'] = $this->db->f( 'zip' );
            $this->auth['location'] = $this->db->f( 'location' );
            $this->auth['state'] = $this->db->f( 'state' );
            $this->auth['country'] = $this->db->f( 'country' );
            $this->auth['phone'] = $this->db->f( 'phone' );
            $this->auth['fax'] = $this->db->f( 'fax' );
            $this->auth['mobile'] = $this->db->f( 'mobile' );
            $this->auth['pager'] = $this->db->f( 'pager' );
            $this->auth['homepage'] = $this->db->f( 'homepage' );
            $this->auth['birthday'] = $this->db->f( 'birthday' );
            $this->auth['firm'] = $this->db->f( 'firm' );
            $this->auth['position'] = $this->db->f( 'position' );
            $this->auth['firm_street'] = $this->db->f( 'firm_street' );
            $this->auth['firm_street_alt'] = $this->db->f( 'firm_street_alt' );
            $this->auth['firm_zip'] = $this->db->f( 'firm_zip' );
            $this->auth['firm_location'] = $this->db->f( 'firm_location' );
            $this->auth['firm_state'] = $this->db->f( 'firm_state' );
            $this->auth['firm_country'] = $this->db->f( 'firm_country' );
            $this->auth['firm_email'] = $this->db->f( 'firm_email' );
            $this->auth['firm_phone'] = $this->db->f( 'firm_phone' );
            $this->auth['firm_fax'] = $this->db->f( 'firm_fax' );
            $this->auth['firm_mobile'] = $this->db->f( 'firm_mobile' );
            $this->auth['firm_pager'] = $this->db->f( 'firm_pager' );
            $this->auth['firm_homepage'] = $this->db->f( 'firm_homepage' ); 
            $this->auth['comment'] = $this->db->f( 'comment' );
            // Use Single Login
            if( $this->force_single_login ) $sess->single_me( $this->db->f( 'user_id' ) );
            return $this->db->f( 'user_id' );
        } 
        // Event
        fire_event( 'login_fail', array ( 'username' => $username, 'password' => $password ) );
        return false;
    }
    function auth_refreshlogin() {
        return true;
    }
} 

class cms_Frontend_Auth extends Auth {
    var $classname = 'cms_Frontend_Auth';
    var $database_class = 'DB_cms';
    var $lifetime;
    var $refresh;
    var $force_single_login = false;
    var $nobody = true;

    // konstruktor, set session lifetime & refresh
    function cms_Frontend_Auth() {
        global $cfg_client;
				
        $this->refresh = ( 2* ceil($cfg_client['session_lifetime']/3) ); # 2/3
        $this->lifetime = $cfg_client['session_lifetime'];
    } 
    
    function auth_refreshlogin() 
    {
        return true;
    }    
} 

?>