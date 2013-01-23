<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998-2000 NetUSE AG
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: prepend.php 28 2008-05-11 19:18:49Z mistral $
 *
 */

$_PHPLIB = array();
$_PHPLIB['libdir'] = str_replace ('\\', '/', dirname(__FILE__) . '/');

require($_PHPLIB['libdir'].'db_mysql.inc');  /* Change this to match your database. */
require($_PHPLIB['libdir'].'ct_sql.inc');    /* Change this to match your data storage container */
require($_PHPLIB['libdir'].'session.inc');   /* Required for everything below.      */
require($_PHPLIB['libdir'].'auth.inc');      /* Disable this, if you are not using authentication. */
require($_PHPLIB['libdir'].'local.php');     /* Required, contains your local configuration. */
require($_PHPLIB['libdir'].'page.inc');      /* Required, contains the page management functions. */

?>