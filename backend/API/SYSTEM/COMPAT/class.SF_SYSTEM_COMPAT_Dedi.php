<?php
// File: $Id: class.SF_SYSTEM_COMPAT_Dedi.php 28 2008-05-11 19:18:49Z mistral $
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
// + Description: The DeDi Compat Class
// +----------------------------------------------------------------------+
// + Changes:
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+
/**
* The DeDi Compat Class
*
* This Class provide the general SF_SYSTEM_COMPAT_Dedi.
* This Class is emulate the Compability to DeDi 1.01
*
* @package SYSTEM , the Common SYSTEM Package.
* @name SF_SYSTEM_COMPAT_DeDi
*/
class SF_SYSTEM_COMPAT_DeDi extends SF_API_Object {
    /**
    * Object version
    *
    * This string identify the SF_API_Object Version.
    *
    * @param  string
    */
    var $_API_object_version = '$Revision: 28 $';
    
    /**
    * Common Class Constructor
    *
    * The Class Constructor.
    *
    */
    function SF_SYSTEM_COMPAT_DeDi() {
    }
}
?>