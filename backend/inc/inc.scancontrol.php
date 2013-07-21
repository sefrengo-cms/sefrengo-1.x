<?PHP
// File: $Id: inc.scancontrol.php 28 2008-05-11 19:18:49Z mistral $
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

/******************************************************************************
 2. Eventuelle Actions/ Funktionen abarbeiten
******************************************************************************/
$perm->check('area_upl');
if ($idclient != 0 && $idclient != (int) $client || !isset($idclient)) $idclient = (int) $client;

/******************************************************************************
 3. Eventuelle Dateien zur Darstellung includieren
******************************************************************************/

/******************************************************************************
 4. Bildschirmausgabe aufbereiten und ausgeben
******************************************************************************/

$url         = 'main.php?area=scan&action=10&iddirectory=' . $iddirectory . '&viewtype=' . $viewtype;
$url2        = 'main.php?area=upl&idexpand=' . $idexpand . '&viewtype=' . $viewtype;
$progressbar = 'tpl/' . $cfg_cms['skin'] . '/img/blue.gif';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
  <head>
	<meta http-equiv="content-type" content="text/html; charset=<?PHP echo $lang_charset ?>" />
	<title>Sefrengo <?PHP echo $cfg_cms['version']; ?> - <?PHP echo $cms_lang['scan_title']; ?></title>
	<script  type="text/javascript">
		var intActive       = 0
		var intDirs         = 0
		var intFiles        = 0
		var intThumbs       = 0
		var intDirsDone     = -1
		var intFilesDone    = 0
		var intThumbsDone   = 0
		var intDirsOpen     = 0
		var intFilesOpen    = 0
		var intThumbsOpen   = 0
		var intTotalProzent = 0
		var strError        = ""
		var arrErrorFiles   = [];
		var dtmStart        = new Date();
		var intDuration     = 0
		var dtmEnde         = '';
		
		function updateprogressbar() {
			var strText
			strText  = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\""
			strText += "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">"
			strText += "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"de\" lang=\"de\">"
			strText += "<head>"
			strText += "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />"
			strText += "<title>Sefrengo<\/title>"
			strText += "<link rel=\"stylesheet\" type=\"text/css\" href=\"tpl/<?PHP echo $cfg_cms['skin'] ?>/css/styles.css\" />"
			strText += "<\/head>"
			strText += "<body id=\"con-edit\">";
			strText += "<div id=\"scan\">";
			strText += "<h5><?PHP echo $cms_lang['scan_options']; ?><\/h5>";
			strText += "<\/div>";
			strText += "<p class=\"gang\"><?PHP echo $cms_lang['scan_status']; ?>" + ((intActive == 3) ? "<?PHP echo $cms_lang['scan_status_done_start']; ?>" + intDuration + "<?PHP echo $cms_lang['scan_status_done_end']; ?>": "<?PHP echo $cms_lang['scan_status_active']; ?>") + "<\/p>"
			if (intTotalProzent > 0) {
				strText += "<p class=\"scanimg\"><img src=\"<?PHP echo $progressbar; ?>\" height=\"24\" width=\"" + parseInt(390*intTotalProzent/100) + "\" alt=\"<?PHP echo $cms_lang['scan_done_start']; ?>" + intTotalProzent + "<?PHP echo $cms_lang['scan_done_end']; ?>\"><\/p>"
			} else {
				strText += "<p class=\"scanimg\"><img src=\"<?PHP echo $progressbar; ?>\" height=\"24\" width=\"0\" alt=\"<?PHP echo $cms_lang['scan_done_start']; ?>" + intTotalProzent + "<?PHP echo $cms_lang['scan_done_end']; ?>\"><\/p>"
			}
			strText += "<p class=\"percent\">" + intTotalProzent + "<?PHP echo $cms_lang['scan_done_end']; ?><\/p>"
			strText += (strError != "") ? "<p class=\errormsg\"><?PHP echo $cms_lang['scan_error']; ?>" + strError + "<\/p>": "";
			strText += "<table id=\"scantab\">"
			strText += "<tr>"
			strText += "<th><\/th><th align=\"center\"><?PHP echo $cms_lang['scan_status_total']; ?><\/th><th align=\"center\"><?PHP echo $cms_lang['scan_status_processed']; ?><\/th><th align=\"center\"><?PHP echo $cms_lang['scan_status_todo']; ?><\/th>"
			strText += "<\/tr>"
			strText += (intActive == 0) ? "<tr>": "<tr>";
			strText += "<th><?PHP echo $cms_lang['scan_directroies']; ?><\/th><td>" + intDirs + "<\/td><td>" + intDirsDone + "<\/td><td>" + intDirsOpen + "<\/td>"
			strText += "<\/tr>"
			strText += (intActive == 1) ? "<tr>": "<tr>";
			strText += "<th><?PHP echo $cms_lang['scan_files']; ?><\/th><td>" + intFiles + "<\/td><td>" + intFilesDone + "<\/td><td>" + intFilesOpen + "<\/td>"
			strText += "<\/tr>"
			strText += (intActive == 2) ? "<tr>": "<tr>";
			strText += "<th><?PHP echo $cms_lang['scan_thumbs']; ?><\/th><td>" + intThumbs + "<\/td><td>" + intThumbsDone + "<\/td><td>" + intThumbsOpen + "<\/td>"
			strText += "<\/tr>"
			strText += "<tr>"
			strText += "<\/table>"
			strText += "<p class=\"msg\"><?PHP echo $cms_lang['scan_errors']; ?><br \/>"
			strText += (arrErrorFiles.length > 0) ? arrErrorFiles.join("<br \/>"): "<?PHP echo $cms_lang['scan_errors_none']; ?>";
			strText += "<\/p>"
			if (intActive == 3) {
				strText += "<p id=\"submitscan\"><span><?PHP echo $cms_lang['scan_closing_time']; ?><\/span><input type=\"button\" name=\"sf_cancel\" id=\"sf_cancel\" value=\"<?PHP echo $cms_lang['scan_close_now']; ?>\" class=\"sf_buttonAction space\" onclick=\"top.close()\"\/><\/p>"
				if (window.opener) window.opener.location.href = "<?PHP echo $sess->urlRaw($url2); ?>";
			}
			strText += "<\/body><\/html>"
			var preview = window.frames["progress"].document
			preview.open();
			preview.write(strText);
  			preview.close();
		}
		function setDirValues() {
			intDirs     += arguments[0];
			intDirsDone += arguments[1];
			intDirsOpen  = intDirs - intDirsDone;
		}
		function setFileValues() {
			intFiles     += arguments[0];
			intFilesDone += arguments[1];
			intFilesOpen  = intFiles - intFilesDone;
		}
		function setThumbValues() {
			if (arguments[0] > intThumbs) intThumbs = arguments[0];
			intThumbsDone += arguments[1];
			intThumbsOpen  = intThumbs - intThumbsDone;
		}
		function setMiscValues() {
			if (arguments.length >= 1) arrErrorFiles = arrErrorFiles.concat(arguments[0]);
			if (arguments.length >= 2) strError     = arguments[1];
			if (arguments.length >= 3) intActive    = arguments[2];
			
			switch (intActive) {
				case 0:
					intTotalProzent  = (intDirs > 0)   ? 33.33*(intDirsDone/intDirs)    : 0.00;
					break;
				case 1:
					intTotalProzent  = 33.33
					intTotalProzent += (intFiles > 0)  ? 33.33*(intFilesDone/intFiles)  : ((intFilesDone >= intFiles) ? 33.33 : 0.00);
					break;
				case 2:
					intTotalProzent  = 66.66
					intTotalProzent += (intThumbs > 0) ? 33.34*(intThumbsDone/intThumbs): ((intThumbsDone >= intThumbs && intThumbs > 0) ? 33.34 : 0.00);
					break;
				default:
					dtmEnde = new Date();
					intDuration = dtmEnde.getTime() - dtmStart.getTime()
					intDuration /= 1000;
					intTotalProzent = 100.00;
					break;
			}
			intTotalProzent = Math.round(intTotalProzent)
			updateprogressbar();
		}
	</script>
	<style type="text/css">
		* { margin: 0px; padding: 0px; }
  body,html{
   width:100%;
   height:100%;
   overflow:hidden;
  }
	</style>
 </head>
	<body>
		<iframe src="<?PHP echo $sess->url($url); ?>" name="progress" id="progress" width="100%" height="100%" marginwidth="0" marginheight="0" scrolling="no" frameborder="0"></iframe>
		<iframe src="about:blank" name="progresscontrol" id="progresscontrol" width="100%" height="1" marginwidth="0" marginheight="0" scrolling="no" frameborder="0"></iframe>
	</body>
</html>