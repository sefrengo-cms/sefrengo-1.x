<?php
	require_once('../../inc/config.php');
	define ('SF_SKIP_HEADER', true);
	
	header('Content-Type: text/css');
	
	require_once('../../../'.$cms_path.'inc/inc.init_external.php');
	
	//echo "/*";
	$cfg = sf_api('LIB', 'Config');
	$adodb = sf_api('LIB', 'Ado');
	
	$idlay = $_GET['sf_idlay'];
	$content = '';
	
	if(is_numeric($idlay) === TRUE)
	{
	
		//CSS and JS file include
		$sql = "SELECT
					D.dirname, B.filename
				FROM
					". $cfg->db('lay_upl') ." A
					LEFT JOIN ". $cfg->db('upl') ." B USING(idupl)
					LEFT JOIN ". $cfg->db('filetype') ." C USING(idfiletype)
					LEFT JOIN ". $cfg->db('directory') ." D on B.iddirectory=D.iddirectory
				WHERE
					A.idlay='$idlay' AND
					C.filetype = 'css'
				ORDER BY
					A.sortindex ASC";
		//echo $sql."<br />\n";
		$rs = $adodb->Execute($sql);
		
		if ($rs === FALSE || $rs->EOF )
		{
			return FALSE;
		}
		
		while (! $rs->EOF) 
		{
			//TODO BUG: JS and CSS files have not an iddirectory
			$file = $cfg_cms['path_base'] . $cfg_client['path_rel'] . 'cms/css/' . $rs->fields['dirname'] . $rs->fields['filename'];
			if(file_exists($file) == TRUE)
			{
				$content .= implode('', file($file));
			}
			$rs->MoveNext();
		}
		$rs->Close();
		
		$content = trim(str_replace("\x0A", "\x0D\x0A", $content));
		//echo "*/\n";
		
		if(strlen($content) > 0)
		{
			echo $content;
			
			exit;
		}
	}
	
	echo "
		body 
		{
			font-family: Arial, Verdana, Sans-Serif;
			font-size: 13px;
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
	";
?>