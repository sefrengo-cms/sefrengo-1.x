<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

	include_once($GLOBALS['cfg_cms']['cms_path'] .'tpl/standard/lang/'.$GLOBALS['cfg_cms']['backend_lang'].'/lang_user.php');

$cms_lang['upl_action']				= 'Action';
$cms_lang['upl_directoriesandfiles']	= 'Folder / Files';
$cms_lang['upl_description']			= 'Description';
$cms_lang['upl_cancel']				= 'Cancel';
$cms_lang['upl_popupclose']			= 'Close window';
$cms_lang['upl_dirisempty']			= 'Empty folder';
$cms_lang['upl_upload']				= 'Upload files';
$cms_lang['upl_editvisible']			= 'Hidden';
$cms_lang['upl_editprotected']			= 'Protected';
$cms_lang['upl_opendir']				= 'Open directory';
$cms_lang['upl_closedir']				= 'Close directory';
$cms_lang['upl_createdir']				= 'Create directory';
$cms_lang['upl_editdir']				= 'Edit directory';
$cms_lang['upl_movedirtodir']			= 'Move directory (incl. all files)';
$cms_lang['upl_movedirname']			= 'Directory name';
$cms_lang['upl_copydirtodir']			= 'Copy directory (incl. alls files)';
$cms_lang['upl_deletedir']				= 'Delete directory including all subdirectory and files';
$cms_lang['upl_scandir']				= 'Synchronize directory with database';
$cms_lang['upl_scandir_root']			= 'Import root directory';
$cms_lang['upl_deletefile']			= 'Delete file';
$cms_lang['upl_editfile']				= 'Edit file';
$cms_lang['upl_movefiletodir']			= 'Move file to';
$cms_lang['upl_copyfiletodir']			= 'Copy file to';
$cms_lang['upl_copyfilename']			= 'File name';
$cms_lang['upl_file']					= 'File';
$cms_lang['upl_fileopen']				= 'Open file';
$cms_lang['upl_delete']				= 'Delete';
$cms_lang['upl_changeviewdetail']		= 'Change to detailed view';
$cms_lang['upl_changeviewcompact']		= 'Change to simple view';

$cms_lang['upl_downloadfile']  		= 'Download file';
$cms_lang['upl_copyfilename']			= 'Filename';

$cms_lang['upl_showfilesindir']		= 'Show files of directory ...';
$cms_lang['upl_selectuploaddir']		= 'Select directory ...';
$cms_lang['upl_bulkupload']			= 'Bulk-upload ZIP (max. ' . get_cfg_var('upload_max_filesize') . 'bytes)';
$cms_lang['upl_tarupload']			    = 'Bulk-upload TAR (max. ' . get_cfg_var('upload_max_filesize') . 'bytes)';
$cms_lang['upl_openfileinnewwindow']   = 'Show file in full size';
$cms_lang['upl_titel']			   		= 'Title';
$cms_lang['upl_download']  			= 'Download';
$cms_lang['upl_edit']  				= 'Edit';
$cms_lang['upl_copy']					= 'Copy';
$cms_lang['upl_move']					= 'Move';
$cms_lang['upl_del']					= 'Delete';
$cms_lang['upl_editrights']			= 'Edit rights';
$cms_lang['upl_confirm_delete']		= 'Confirm file delete!';
$cms_lang['upl_newplace']				= 'to';
$cms_lang['upl_root_dir']				= 'Root directory';

$cms_lang['scan_title']			  = 'Synchronize directories with database';
$cms_lang['scan_error']			  = 'Error:';
$cms_lang['scan_done_start']         = 'Work in progress: ';
$cms_lang['scan_done_end']			  = '% done';
$cms_lang['scan_status']			  = 'Scanning: ';
$cms_lang['scan_status_done_start']  = 'Done<br>Duration: ';
$cms_lang['scan_status_done_end']	  = ' seconds.';
$cms_lang['scan_status_active']      = 'Active';
$cms_lang['scan_status_total']		  = 'Total';
$cms_lang['scan_status_processed']	  = 'Done';
$cms_lang['scan_status_todo']        = 'Open';
$cms_lang['scan_directroies']		  = 'Directories';
$cms_lang['scan_files']			  = 'Files';
$cms_lang['scan_thumbs']			  = 'Thumbnails';
$cms_lang['scan_errors']			  = 'Directories or files failed:';
$cms_lang['scan_errors_none']		  = 'None';
$cms_lang['scan_closing_time']		  = 'Window will be closed in ten seconds.';
$cms_lang['scan_close_now']		  = 'Close';

$cms_lang['scan_thumbs_checkbox']	  = 'Recreate thumbnails';
$cms_lang['scan_nosubdir_checkbox']  = 'Do not synchronize subdirectories';
$cms_lang['scan_options']            = 'Synchronize options';
$cms_lang['scan_start_dirscan']      = 'Synchronize now!';

$cms_lang['upl_js_texte_pp_title']			= 'File&nbsp;information<br>&nbsp;click here to view';
$cms_lang['upl_js_texte_pp_header_bild']	= 'Picture&nbsp;preview&nbsp;and&nbsp;information<br>&nbsp;click&nbsp;here&nbsp;for&nbsp;original&nbsp;size';
$cms_lang['upl_js_texte_pp_header_datei']	= 'Picture&nbsp;preview&nbsp;and&nbsp;information<br>&nbsp;original&nbsp;size';
$cms_lang['upl_js_texte_pp_created']		= 'Created: ';
$cms_lang['upl_js_texte_pp_modified']		= 'Last changed: ';
$cms_lang['upl_js_texte_pp_author']		= 'Editor: ';
$cms_lang['upl_js_texte_pp_size']			= 'Size: ';

// 14xx = Filemanager
$cms_lang['err_1400']					= 'File name is missing or file name contains illegal characters!';
$cms_lang['err_1401']					= 'File name is already in use!';
$cms_lang['err_1402']					= 'File not found!';
$cms_lang['err_1403']					= 'File not copied!';
$cms_lang['err_1404']					= 'File not moved!';
$cms_lang['err_1405']					= 'Parameter missing in function call!';
$cms_lang['err_1406']					= 'Directory name is missing or directory name contains illegal characters!';
$cms_lang['err_1407']					= 'Change of file extension not allowed!';
$cms_lang['err_1408']					= 'Directory not deleted!';
$cms_lang['err_1409']					= 'File not deleted!';
$cms_lang['err_1410']					= $cms_lang['err_1409'].' File is already in use.';
$cms_lang['err_1411']					= 'File not deleted!'; //DOPPELTER EINTRAG
$cms_lang['err_1412']					= 'File not renamed!';
$cms_lang['err_1413']					= 'Directory name is already in use!';
$cms_lang['err_1414']					= 'Directory name not renamed!';
$cms_lang['err_1415']	   				= 'Illegal parameter. Function abborted!';
$cms_lang['err_1416']	   				= 'File could not be replaced. Function abborted!';
$cms_lang['err_1417']					= 'File not created!';
$cms_lang['err_1418']					= 'Directory not created!';
$cms_lang['err_1419']					= 'File not moved. File access deny by underlying file system!';
$cms_lang['err_1420']					= 'File name contains illegal characters!';
$cms_lang['err_1421']					= 'Upload failed!';
$cms_lang['err_1422']					= $cms_lang['err_1421'] . ' Archive contains illegal directories!';
$cms_lang['err_1423']					= 'Upload successful, file could not be written into the database!';
$cms_lang['err_1424']					= $cms_lang['err_1421'] . ' Could not move the file to selected directory!';
$cms_lang['err_1425']					= 'Directory to synchronize is missing in database!';
$cms_lang['err_1426']					= 'Directory to synchronize is missing, please review your configuration!';

?>