<?php
require_once('../../../inc/config.php');
define ('SF_SKIP_HEADER', true);
require_once('../../../../'.$cms_path.'inc/inc.init_external.php');
header('Content-Type: text/css');
$idlay = $_GET['sf_idlay'];
$sql = "SELECT
			C.filetype, D.dirname, B.filename
		FROM
			". $cms_db['lay_upl'] ." A
			LEFT JOIN ". $cms_db['upl'] ." B USING(idupl)
			LEFT JOIN ". $cms_db['filetype'] ." C USING(idfiletype)
			LEFT JOIN ". $cms_db['directory'] ." D on B.iddirectory=D.iddirectory
		WHERE
			idlay='$idlay'
			AND C.filetype = 'css'";
$db->query($sql);
while ($db->next_record()) {
	$file = $cfg_client['path'] . $db->f('dirname') . $db->f('filename');
	$f .= implode('', (file($file)));
	//$filename = $file;
	//$handle = fopen ($filename, "rb");
	//$f .= fread ($handle, filesize ($filename));
	//fclose ($handle);
	
}
//$handle = fopen ('C:/Programme/Apache Group/Apache/htdocs/cms_dev/cvs-dev/1.0/backend/external/fckEditor/temp.css', "wb");
//fwrite($handle, trim(str_replace("\x0A","\x0D\x0A",$f)));
//fclose ($handle);
////
//include 'C:/Programme/Apache Group/Apache/htdocs/cms_dev/cvs-dev/1.0/backend/external/fckEditor/temp.css'; 

echo trim(str_replace("\x0A","\x0D\x0A",$f));
?>
body 
{
	font-family: Arial, Verdana, Sans-Serif;
	font-size: 12px;
	padding: 5px 5px 5px 5px;
	margin: 0px;
	border-style: none;
	background-color: #ffffff;
}

.Bold
{
	font-weight: bold;
}



.Code
{
	border: #8b4513 1px solid;
	padding-right: 5px;
	padding-left: 5px;
	color: #000066;
	font-family: 'Courier New' , Monospace;
	background-color: #ff9933;
}
