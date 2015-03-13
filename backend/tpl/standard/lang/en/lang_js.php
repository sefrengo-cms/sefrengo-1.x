<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

	include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_upl.php');

$cms_lang['js_action']					= 'Action';
$cms_lang['js_cancel_form']			= 'Cancel, back to last screen';
$cms_lang['js_created']				= 'Created';
$cms_lang['js_description']			= 'Description';
$cms_lang['js_editor']					= 'Editor';
$cms_lang['js_filename']				= 'Filename / Url';
$cms_lang['js_file_content']			= 'Code';
$cms_lang['js_file_delete']			= 'Delete file';
$cms_lang['js_file_delete_confirm']	= 'Confirm delete';
$cms_lang['js_file_delete_cancel']		= 'Cancel';
$cms_lang['js_file_duplicate']			= 'Copy file';
$cms_lang['js_file_edit']				= 'Edit file';
$cms_lang['js_file_new']				= 'New file';
$cms_lang['js_fileimport']				= 'Upload file';
$cms_lang['js_import']					= 'Import file';
$cms_lang['js_lastmodified']			= 'Last modified';
$cms_lang['js_nofile']					= 'No file found.';
$cms_lang['js_nofiles']				= 'No files found.';
$cms_lang['js_submit_form']			= 'Save changes';
$cms_lang['js_file_download']         	= 'Download file';
$cms_lang['js_export']					= 'Export file';
$cms_lang['js_edit_rights']         	= 'Edit rights';

// 12xx = JS
$cms_lang['err_1201']					= 'Filename is missing or contains illegal characters.';
$cms_lang['err_1202']					= 'Filename already existing. Please try another one.';
$cms_lang['err_1203']					= 'File could not be created.';
$cms_lang['err_1204']					= 'File not found.';
$cms_lang['err_1205']					= 'File could not be exported.';
$cms_lang['err_1206']					= 'File successfully exported.';
$cms_lang['err_1207']					= 'File could not be imported.';
$cms_lang['err_1208']					= $cms_lang['err_1207']. ' No data found.';
$cms_lang['err_1209']					= $cms_lang['err_1207']. ' Filename already existing.';
$cms_lang['err_1210']					= 'File successfully imported.';
$cms_lang['err_1211']					= 'File in use, delete aborted.';
$cms_lang['err_1212']					= 'File not found, delete aborted.';
$cms_lang['err_1213']					= $cms_lang['err_1205']. ' No data found.';
$cms_lang['err_1214']					= $cms_lang['err_1205']. ' Filename already existing.';
$cms_lang['err_1215']					= 'Unable to update file! Please check userrights in filesystem.';
$cms_lang['err_1216']				   	= 'Unable to delete file! Please check userrights in filesystem.';
$cms_lang['err_1217']				   	= 'Upload of file failed. Insert into database failed.';
$cms_lang['err_1218']					= 'Update of file failed!';
?>