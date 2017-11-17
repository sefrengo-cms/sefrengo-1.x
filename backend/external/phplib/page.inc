<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: page.inc 28 2008-05-11 19:18:49Z mistral $
 *
 */

function page_open($feature) {
	global $_PHPLIB;

	// enable sess and all dependent features.
	if (isset($feature['sess'])) {
		global $sess;
		$sess = new $feature['sess'];
		$sess->start();

		// the auth feature depends on sess
		if (isset($feature['auth'])) {
			global $auth;

			if (!isset($auth)) $auth = new $feature['auth'];
			/** @var cms_Backend_Auth $auth */
			$auth->start();
			if(! $sess -> session_enabled){ return false ;}

			// the perm feature depends on auth and sess
			if (isset($feature['perm'])) {
				global $perm;

				if (!isset($perm)) $perm = new $feature['perm'];
			}

			// the user feature depends on auth and sess
			if (isset($feature['user'])) {
				global $user;

				if (!isset($user)) $user = new $feature['user'];
				$user->start($auth->auth['uid']);
			}
		}

		// Load the auto_init-File, if one is specified.
		if (($sess->auto_init != '') && !$sess->in) {
			$sess->in = 1;
			include($_PHPLIB['libdir'].$sess->auto_init);
			if ($sess->secure_auto_init != '') $sess->freeze();
		}
	}
}

function page_close() {
	global $sess, $user;

	if(! $sess -> session_enabled){ return false; }

	if (isset($sess)) {
		$sess->freeze();
	}
}

function sess_load($session) {
	reset($session);
    if (!empty($session)) {
        foreach ($session as $k => $v) {
            $GLOBALS[$k] = new $v;
            $GLOBALS[$k]->start();
        }
    }
}

function sess_save($session) {
	reset($session);
    if (!empty($session)) {
        foreach ($session as $k => $v) {
            $GLOBALS[$v]->freeze();
        }
    }
}
?>