<?PHP
// File: $Id: inc.header.php 28 2008-05-11 19:18:49Z mistral $
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


header('Content-type: text/html; charset=UTF-8');
// Browsern das cachen von Backendseiten verbieten
if ($cfg_cms['backend_cache'] == '1') {
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Datum aus Vergangenheit
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // immer geändert
	header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache');
}
if ($area == 'con_frameheader') {
    $area='con_editframe';
}

include_once("class.header.php");

$sf_header = new SF_Header();

if(isset($body_onload_func))
{
	$sf_header->setBodyOnLoadFunction($body_onload_func);
}

$sf_header->generate();

// show template and clear $tpl variable
$tpl->show();
unset($tpl);
$tpl = new HTML_Template_IT($this_dir.'tpl/'.$cfg_cms['skin'].'/');

?>