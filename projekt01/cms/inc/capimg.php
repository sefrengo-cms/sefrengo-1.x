<?php

	/*
		Jax Captcha Class v1.o1 - Copyright (c) 2005, Andreas John aka Jack (tR)
		This program and it's moduls are Open Source in terms of General Public License (GPL) v2.0
	
		captcha_image.php 		(captcha image service)
		
		Last modification: 2005-09-05
	*/
	$tmp_dir_path               = '../files/tmp/captcha/';
	$captcha_expires_after = 420;

	// deactivate Cache
	header("Expires: Mon, 01 Jul 1990 00:00:00 GMT"); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT"); 
	header("Pragma: no-cache"); 
	header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
	header("Content-Type: image/jpeg", true);

	if (!empty( $_GET['img'] ) )
		$img = $_GET['img'];
	else
	{
		echo 'no image file specified via &img=...';
		exit;
	}
	
	if (!$fh = fopen( $tmp_dir_path.addslashes($_GET['nadd']).'cap_'.$img.'.jpg', 'rb'))
	{
		echo 'could not open image file!';
	}
	else 
	{	
		fpassthru( $fh );
		fclose( $fh );
	}

	
	// clean up
	$tmp_dir = dir( $tmp_dir_path );
	while( $entry = $tmp_dir->read()) 
	{
		if ( is_file( $tmp_dir_path . $entry ) )
		{
			if ( mktime() - filemtime( $tmp_dir_path . $entry ) > $captcha_expires_after ) 
			{
				unlink( $tmp_dir_path . $entry );
			}
		}
	}
	
?>
