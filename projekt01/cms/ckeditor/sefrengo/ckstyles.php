<?php
header( 'Content-Type:text/xml; charset=utf-8' );
echo '<?xml version="1.0" encoding="utf-8" ?>'. "\n";

echo "<!--";
require_once('../../inc/config.php');
require_once ('../../../'.$cms_path.'inc/fnc.type_common.php');
define ('SF_SKIP_HEADER', true);
require_once('../../../'.$cms_path.'inc/inc.init_external.php');


$arr_styles = _type_get_stylelist( $_GET['selectablestyles'] );

$out_s = '';
$out_s .= '<Style name="stylenbame" element="span">
			          <Attribute name="class" value="classname" />
		         </Style>'."\n";
if (is_array($arr_styles)) {
	foreach ($arr_styles AS $v) {
		$out_s .= '<Style name="'.$v['autodesc'].'" element="span">
			          <Attribute name="class" value="'.$v['name'].'" />
		         </Style>'."\n";
	}
}
echo "-->";

echo  "<Styles>\n" . $out_s . "</Styles>";
?>