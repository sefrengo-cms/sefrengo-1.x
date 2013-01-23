<?PHP
/*
 +----------------------------------------------------------------------+
 | GOOSEBERRY SOURCEPAD --> Advanced Sourcecode - Editor                |
 +----------------------------------------------------------------------+
 | Copyright (c) 2002 Björn Brockmann. All rights reserved.             |
 +----------------------------------------------------------------------+
 | This source file is subject to the QPL Software License, Version 1.0,|
 | that is bundled with this package in the file QPL.txt. If you did    |
 | not receive a copy of this file, you may send an email to            |
 | license@project-gooseberry.de, so I can mail you a copy.             |
 | In short this license allows for free use of GOOSEBERRY SOURCEPAD    |
 | for all free open-source projects. Please note that QPL 1.0 does not |
 | allow you to use GOOSEBERRY SOURCEPAD in a closed source commercial  |
 | product/project. If you would like to use GOOSEBERRY SOURCEPAD in a  |
 | commercial context please contact me for further details.            |
 +----------------------------------------------------------------------+
 | Author: Björn Brockmann < bjoern@project-gooseberry.de >             |
 | -------------------------------------------------------------------- |
 | Homepage: http://www.project-gooseberry.de                           |
 +----------------------------------------------------------------------+
*/


$gb_conf['handle_file'] = 'handle.php';
$gb_conf['handle_http_path'] = $con_cfg['HtmlPathContenido'] . 'external/gb_source_pad/';

$gb_conf['templateset'] = 'basic';

$gb_conf['language'] = 'german';

$gb_conf['debug'] = '1';

//extensible parameter string
$gb_conf['ext_para_str'] = 'testvar1=testval1&testvar2=tesval2';

//Make text outside the editor unselectable (for better
//compatiblity with sourcepad)IE 5.5 or higher
//'on' or 'off'
$gb_conf['unselectable'] = 'on';

//Formname
$gb_conf['form_name'] = '';
//Editorname
$gb_conf['textfield_name'] = 'my_editor';


//Editor DEFAULT - Settings
//1 = allow, 0 = disallow
$gb_conf['allow_ico_save'] = '1';
$gb_conf['allow_ico_set_back'] = '1';
$gb_conf['allow_ico_font'] = '1';
$gb_conf['allow_ico_fontsize'] = '1';
$gb_conf['allow_ico_txtcolor'] = '1';
$gb_conf['allow_ico_txtcolor_ext'] = '1';
$gb_conf['allow_ico_txtbgcolor'] = '1';
$gb_conf['allow_ico_txtbgcolor_ext'] = '1';
$gb_conf['allow_ico_special_chars'] = '1';
$gb_conf['allow_ico_color_ext'] = '1';
$gb_conf['allow_ico_print'] = '1';
$gb_conf['allow_ico_preview'] = '1';
$gb_conf['allow_ico_search'] = '1';
$gb_conf['allow_ico_search_replace'] = '1';
$gb_conf['allow_ico_undo'] = '1';
$gb_conf['allow_ico_redo'] = '1';
$gb_conf['allow_ico_bold'] = '1';
$gb_conf['allow_ico_italic'] = '1';
$gb_conf['allow_ico_underline'] = '1';
$gb_conf['allow_ico_align_left'] = '1';
$gb_conf['allow_ico_align_center'] = '1';
$gb_conf['allow_ico_align_right'] = '1';
$gb_conf['allow_ico_align_justify'] = '1';
$gb_conf['allow_ico_close_open_tags'] = '1';
$gb_conf['allow_ico_hr'] = '1';
$gb_conf['allow_ico_br'] = '1';
$gb_conf['allow_ico_margin'] = '1';
$gb_conf['allow_ico_link'] = '1';
$gb_conf['allow_ico_image'] = '1';
$gb_conf['allow_ico_list'] = '1';
$gb_conf['allow_ico_table'] = '1';
$gb_conf['allow_ico_tablerow'] = '1';
$gb_conf['allow_ico_tabledesk'] = '1';

//How many undos are possible? It's recommended to chose
//a value <= 10, cause IE crashes on my machine by a value of > 15
//I think a value of 10 works fine. I had no problems with this setting.
$gb_conf['undo_limit'] = '10';

//print text as html or source
$gb_conf['print_as'] = 'source';
$gb_conf['print_nl2br'] = '0';
//print previewtext as html or source
$gb_conf['preview_as'] = 'html';
$gb_conf['preview_nl2br'] = '0';

$gb_conf['editorheight'] = '15';
$gb_conf['editorwidth'] = '300';

$gb_conf['editorheight_css'] = '9cm';
$gb_conf['editorwidth_css'] = '100%';
$gb_conf['wrap'] = 'off';
$gb_conf['content'] = '';
?>