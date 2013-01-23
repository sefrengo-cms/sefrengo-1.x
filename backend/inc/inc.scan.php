<?PHP
// File: $Id: inc.scan.php 28 2008-05-11 19:18:49Z mistral $
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

/******************************************************************************
 1. Benötigte Funktionen und Klassen includieren
******************************************************************************/

include('inc/fnc.scan.php');
include('inc/class.filemanager.php');
$fm = &new filemanager();
$scanned_dirs_complete   = false;
$scanned_files_complete  = false;
$scanned_thumbs_complete = false;
$scan_active             = 0;
$scanned_completed       = 0;
$found_dirs              = 0;
$scanned_dirs            = 0;
$found_files             = 0;
$scanned_files           = 0;
$found_thumbs            = 0;
$scanned_thumbs          = 0;
$max_dirs                = $cfg_client['max_count_scandir'];
$max_files               = $cfg_client['max_count_scanfile'];
$max_thumbs              = $cfg_client['max_count_scanthumb'];
$extend_time             = $cfg_client['extend_time_scandir'];

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/
$perm->check('area_upl');
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;

// if $action is named prepare a function call
if (!empty($action) && preg_match('/^\d/', $action) == 0) {
 eval( '$errno = upl_'.$action.'();' );

 // Event
 $errlog  = ($errno) ? 'Fehler:' . $errno: '';
 fire_event('upl'.$action, array('idupl' => $idupl, 'errlog' => $errlog));
}

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

// Kopfbereich
$title  = $cms_lang['area_upl'];
$fehler = ($fm->errno) ? $cms_lang["err_$fm->errno"]: '';

if ($action == "10") {
 // check if directory exists ... avoid config errors in cms_values
 $fehler = '';
 if ($iddirectory > 0) {
  $dir_data = $fm->get_directory( (int) $iddirectory, $client);
 } else {
  $dir_data['dirname'] = '';
  $dir_data['iddirectory'] = '0';
 }

 if (empty($dir_data)) {
  // Fehler Verzeichnis nicht in der Datenbank gefunden
  $fehler = $cms_lang["err_1425"];
 } else {
  // Prüfe ob directory auf Festplatte existiert
  if (!is_dir($cfg_client['upl_path'] . $dir_data['dirname'])) {
   $fehler = $cms_lang["err_1426"];
  }
 }
 
 // new scan - show property window
 $url = $sess->urlRaw('main.php?area=scan&amp;action=scandir&amp;iddirectory=' . $iddirectory . '&amp;viewtype=' . $viewtype);
?>
<?PHP echo "<"; ?>?xml version="1.0" encoding="<?PHP echo $lang_charset ?>" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
   <link rel="stylesheet" type="text/css" href="tpl/<?PHP echo $cfg_cms['skin'] ?>/css/styles.css" />
   <meta http-equiv="content-type" content="text/html; charset=<?PHP echo $lang_charset ?>" />
  </head>
  <body id="con-edit">
<?PHP
 if (empty($fehler)) {
?>
   <form action="<?PHP echo $url ?>" method="post" target="progresscontrol">
    <div id="scan">
     <h5><?PHP echo $cms_lang['scan_options']; ?></h5>
    </div>
     <p class="check">
     <input type="checkbox" name="updatethumbs" id="updatethumbs" value="1" />
     <label for="updatethumbs"><?PHP echo $cms_lang['scan_thumbs_checkbox']; ?></label>
     </p>
     <p class="check">
     <input type="checkbox" name="nosubdirscan" id="nosubdirscan" value="1" />
     <label for="nosubdirscan"><?PHP echo $cms_lang['scan_nosubdir_checkbox']; ?></label>
     </p> 
      <p id='submitscan'>
      <input type='submit' name='sf_save' value='<?PHP echo $cms_lang['scan_start_dirscan']; ?>' class="sf_buttonAction" onmouseover="this.className='sf_buttonActionOver'" onmouseout="this.className='sf_buttonAction'" />
      <input type='button' name='sf_cancel' id='sf_cancel' value='<?PHP echo $cms_lang['upl_cancel']; ?>' class="sf_buttonAction space" onclick="top.close()" onmouseover="this.className='sf_buttonActionCancelOver'" onmouseout="this.className='sf_buttonAction'" />
     </p>
   </form>
<?PHP
 } else {
?>
    <p class="errormsg"><?PHP echo $fehler; ?></p>
    <p id="submitscan">
	<input name="sf_cancel" id="sf_cancel" value="<?PHP echo $cms_lang['scan_close_now']; ?>" class="sf_buttonAction" onclick="top.close()" onmouseover="this.className='sf_buttonActionCancelOver'" onmouseout="this.className='sf_buttonAction'" type="button">
	</p>
<?PHP 
 }
?>
  </body>
 </html>
<?PHP
} else {
 // running scan
 $closescript = '';
 $urladdon    = (!empty($updatethumbs)) ? "&updatethumbs=1": "";
 $urladdon   .= (!empty($nosubdirscan)) ? "&nosubdirscan=1": "";
 $urladdon   .= '&amp;viewtype=' . $viewtype;
 
 if (!$scanned_dirs_complete) {
  $url = $sess->urlRaw('main.php?area=scan&amp;action=scandir&amp;iddirectory=-1' . $urladdon);
  $arrFileErrors = (count($fm->error_dirs) > 0) ? '"'.implode( '<strong> (V)</strong>","', $fm->error_dirs ).'<strong> (V)</strong>"':'';
  $scan_active = 0;
 } elseif (!$scanned_files_complete) {
  if ($action == 'scandir') {
   $arrFileErrors = (count($fm->error_dirs) > 0) ? '"'.implode( '<strong> (V)</strong>","', $fm->error_dirs ).'<strong> (V)</strong>"':'';
  } else {
   $arrFileErrors = (count($fm->error_files) > 0) ? '"'.implode( '<strong> (D)</strong>","', $fm->error_files ).'<strong> (D)</strong>"':'';
  }
  $url = $sess->urlRaw('main.php?area=scan&amp;action=scanfiles' . $urladdon);
  $scan_active = 1;
 } elseif (!$scanned_thumbs_complete) {
  $arrFileErrors = (count($fm->error_files) > 0) ? '"'.implode( '<strong> (D)</strong>","', $fm->error_files ).'<strong> (D)</strong>"':'';
  $url = $sess->urlRaw('main.php?area=scan&amp;action=scanthumbs' . $urladdon);
  $scan_active = 2;
 } else {
  $arrFileErrors = '';
  $scan_active = 3;
  $closescript = "window.setTimeout('top.close()', 10000);";
 }
 if (empty($closescript)) $closescript = "window.document.location.href = '" . $url . "';";
?>
 <html>
  <head>
   <meta http-equiv="content-type" content="text/html; charset=<?PHP echo $lang_charset ?>">
  </head>
  <script language="JavaScript1.2">
   function update () {
    <?PHP
     echo "window.parent.setDirValues($found_dirs, $scanned_dirs);\n";
     echo "window.parent.setFileValues($found_files, $scanned_files );\n";
     echo "window.parent.setThumbValues($found_thumbs, $scanned_thumbs);\n";
     echo "window.parent.setMiscValues([ $arrFileErrors ], '$fehler', $scan_active );\n";
     echo "window.setTimeout('recall()', 250);";
    ?>
    return true;
   }
   function recall() {
    <?PHP echo $closescript; ?>
   }
  </script>
  <body onload="update();">
  </body>
 </html>
<?PHP
}
?>
