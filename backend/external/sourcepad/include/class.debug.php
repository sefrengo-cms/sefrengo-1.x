<?
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


class gb_debug
{
	
	function die_nice($error_string)
	{
		global $gb_lang;
		
		echo '<html><head><title>'. $gb_lang['error'] . '</title></head>';
		echo '<body><table width ="100%" border ="1"><tr><td><div align="center"><b>' . $gb_lang['error'] .'</b>';
		echo '<br><br>' . $error_string . '<br><br></div></td></tr></table></body></html>';
		exit;
	}
}