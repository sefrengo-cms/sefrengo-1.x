<?php
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 The sefrengo-group                                |
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
// | Authors: Roland Braband <stam@sefrengo.de>                           |
// +----------------------------------------------------------------------+
//
// $Id: CASE.php 28 2008-05-11 19:18:49Z mistral $


/**
 * Replace CASE_LOWER/CASE_UPPER constant
 *
 * @category    PHP
 * @package     PHP_Compat
 * @author      Roland Braband <stam@sefrengo.de>
 * @version     $Revision: 28 $
 * @since       not in PHP
 */
if (!defined('CASE_LOWER')) {
    define('CASE_LOWER', 0);
}

if (!defined('CASE_UPPER')) {
    define('CASE_UPPER', 1);
}

?>