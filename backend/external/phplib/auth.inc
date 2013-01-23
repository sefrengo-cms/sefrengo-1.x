<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 * Copyright (c) 1999-2000 Internet Images srl
 *                    Massimiliano Masserelli
 *
 * $Id: auth.inc 28 2008-05-11 19:18:49Z mistral $
 *
 */

class Auth {
	var $classname = 'Auth';
	var $persistent_slots = array('auth');
	var $lifetime = 0;
	var $refresh = 0;
	var $mode = 'log';	// "log" for login only systems,
				        // "reg" for user self registration
	var $magic = '';	// Used in uniqid() generation
	var $nobody = false;	// If true, a default auth is created...
	var $cancel_login = 'cancel_login';	   // The name of a button that can be
						                  // used to cancel a login form
    var $force_single_login = false;     // purge all other sesion with this user_id
	// End of user qualifiable settings.
	var $auth = array();
	var $in = false;
	var $db;

	// initialisieren
	function start() {
		$cl = $this->cancel_login;
		global $sess, $$cl;

		if(! $sess -> session_enabled){
		$this->auth['uid'] = 2;
		return false;
		}
	// This is for performance, I guess but I'm not sure if it could
	// be safely removed -- negro
	if (!$this->in) {
		$sess->register('auth');
		$this->in = true;
	}

	// back compatibility: if d_c is set, create db object
	if(isset($this->database_class)) {
		$class = $this->database_class;
		$this->db = new $class;
	}

	// Check current auth state. Should be one of
	//  1) Not logged in (no valid auth info or auth expired)
	//  2) Logged in (valid auth info)
	//  3) Login in progress (if $$cl, revert to state 1)
	if ($this->is_authenticated()) {
		$uid = $this->auth['uid'];
		switch ($uid) {
		case 'form':

			// Login in progress
			if ($$cl) {
				// If $$cl is set, delete all auth info
				// and set state to "Not logged in", so eventually
				// default or automatic authentication may take place
				$this->unauth();
				$state = 1;
			} else {
				// Set state to "Login in progress"
				$state = 3;
			}
			break;
		default:
			// User is authenticated and auth not expired
			$state = 2;
			break;
		}
	} else {
		// User is not (yet) authenticated
		$this->unauth();
		$state = 1;
	}

	switch ($state) {
		case 1:

			// No valid auth info or auth is expired
			// Check for user supplied automatic login procedure
			if ($uid = $this->auth_preauth()) {
				$this->auth['uid'] = $uid;
				$this->auth['exp'] = time() + (60 * $this->lifetime);
				$this->auth['refresh'] = time() + (60 * $this->refresh);
				return true;
			}

			// Check for "log" vs. "reg" mode
			switch ($this->mode) {
				case 'yes':
				case 'log':
					if ($this->nobody) {

						// Authenticate as nobody
						$this->auth['uid'] = 'nobody';
						$this->auth['exp'] = 0x7fffffff;
						$this->auth['refresh'] = 0x7fffffff;
						return true;
					} else {

						// Show the login form
						$this->auth_loginform();
						$this->auth['uid'] = 'form';
						$this->auth['exp'] = 0x7fffffff;
						$this->auth['refresh'] = 0x7fffffff;
						$sess->freeze();
						exit;
					}
					break;
				case 'reg':
					if ($this->nobody) {

						// Authenticate as nobody
						$this->auth['uid'] = 'nobody';
						$this->auth['exp'] = 0x7fffffff;
						$this->auth['refresh'] = 0x7fffffff;
						return true;
					} else {

						// Show the registration form
						$this->auth_registerform();
						$this->auth['uid'] = 'form';
						$this->auth['exp'] = 0x7fffffff;
						$this->auth['refresh'] = 0x7fffffff;
						$sess->freeze();
						exit;
					}
					break;
			}
			break;
		case 2:

			// Valid auth info
			// Refresh expire info
			// DEFAUTH handling: do not update exp for nobody.
			if ($uid != 'nobody') $this->auth['exp'] = time() + (60 * $this->lifetime);
			break;
		case 3:

			// Login in progress, check results and act accordingly
			switch ($this->mode) {
				case 'yes':
				case 'log':
					if ($uid = $this->auth_validatelogin()) {
						$this->auth['uid'] = $uid;
						$this->auth['exp'] = time() + (60 * $this->lifetime);
						$this->auth['refresh'] = time() + (60 * $this->refresh);
						return true;
					} else {
						$this->auth_loginform();
						$this->auth['uid'] = 'form';
						$this->auth['exp'] = 0x7fffffff;
						$this->auth['refresh'] = 0x7fffffff;
						$sess->freeze();
						exit;
					}
					break;
				case 'reg':
					if ($uid = $this->auth_doregister()) {
						$this->auth['uid'] = $uid;
						$this->auth['exp'] = time() + (60 * $this->lifetime);
						$this->auth['refresh'] = time() + (60 * $this->refresh);
						return true;
					} else {
						$this->auth_registerform();
						$this->auth['uid'] = 'form';
						$this->auth['exp'] = 0x7fffffff;
						$this->auth['refresh'] = 0x7fffffff;
						$sess->freeze();
						exit;
					}
					break;
			}
			break;
	}
}

	function login_if( $t ) {
		if ($t) {
			$this->unauth();  # We have to relogin, so clear current auth info
			$this->nobody = false; # We are forcing login, so default auth is disabled
			$this->start(); # Call authentication code
		}
	}

	function unauth($nobody = false) {
		$this->auth['uid']   = '';
		$this->auth['perm']  = '';
		$this->auth['exp']   = 0;

		// Back compatibility: passing $nobody to this method is deprecated
		if ($nobody) {
			$this->auth['uid']   = 'nobody';
			$this->auth['perm']  = '';
			$this->auth['exp']   = 0x7fffffff;
		}
	}


	function logout($nobody = '') {
		global $sess;

		$sess->unregister('auth');
		$sess->delete();
		unset($this->auth['uname']);
		$this->unauth($nobody == '' ? $this->nobody : $nobody);
	}

	function is_authenticated() {
		if (isset($this->auth['uid']) && $this->auth['uid'] && (($this->lifetime <= 0) || (time() < $this->auth['exp']))) {

			// If more than $this->refresh minutes are passed since last check,
			// perform auth data refreshing. Refresh is only done when current
			// session is valid (registered, not expired).
			if (($this->refresh > 0) && ($this->auth['refresh']) && ($this->auth['refresh'] < time())) {
				if ($this->auth_refreshlogin()) $this->auth['refresh'] = time() + (60 * $this->refresh);
				else return false;
			}
			return $this->auth['uid'];
		} else return false;
	}

	function url() {
		return $GLOBALS['sess']->self_url();
	}

	function purl() {
		echo $GLOBALS['sess']->self_url();
	}

	// This method can authenticate a user before the loginform
	// is being displayed. If it does, it must set a valid uid
	// (i.e. nobody IS NOT a valid uid) just like auth_validatelogin,
	// else it shall return false.

	function auth_preauth() {
         	return false;
         }

	// Authentication dummies. Must be overridden by user.
	function auth_refreshlogin() {
         }

	function auth_registerform() {
         }

	function auth_doregister() {
         }
}



?>