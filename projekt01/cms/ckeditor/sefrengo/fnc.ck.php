<?php
function ckConfigStringToArray($string) {
	if (! empty($string) && $string != 'true' && $string != 'false' ) {
		if ( substr($string,0,1) == ',') {
			$string = substr($string,1);
		}
	} else {
		return array();
	}
	
	$string = str_replace(' ', '', $string);
	$arr = explode(',', $string);
	foreach($arr AS $k=>$v) {
		if($v == '') {
			unset($arr[$k]);
		}
	}
	
	return $arr;
}

function ckBuildMenu($featurestring, $selectablestyles) {
	$featurestring = str_replace(' ', '', strtolower($featurestring) );
	$features = explode(',', $featurestring );
	$rows = array();

	// 1. Zeile
	if (in_array('reset', $features)) $sub[] .= "'NewPage'";
	if (in_array('preview', $features)) $sub[] .= "'Preview'";
	if (in_array('print', $features)) $sub[] .= "'Print'";
	if (count($sub) > 0) $row1 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (in_array('clipboardtools', $features)) $sub[] .= "'Cut','Copy','Paste','PasteText','PasteFromWord'";
	if (in_array('striptag', $features)) $sub[] .= "'RemoveFormat'";
	if (count($sub) > 0) $row1 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (in_array('undo', $features)) $sub[] .= "'Undo','Redo'";
	if (count($sub) > 0) $row1 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (in_array('search', $features)) $sub[] .= "'Find','Replace'";
	//if (in_array('changecase', $features)) $sub[] .= "'changecase'"; CHANGECASE DEPRECATED
	if (count($sub) > 0) $row1 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (in_array('table', $features)) $sub[] .= "'Table'";	
	if (count($sub) > 0) $row1 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (!empty($row1)) array_push($rows, $row1);// .= "'/',\n";

	// 2. Zeile
	if (in_array('bold', $features)) $sub[] .= "'Bold'";
	if (in_array('italic', $features)) $sub[] .= "'Italic'";
	if (in_array('underline', $features)) $sub[] .= "'Underline'";
	if (in_array('strikethrough', $features)) $sub[] .= "'Strike'";
	if (in_array('subscript', $features)) $sub[] .= "'Subscript'";
	if (in_array('superscript', $features)) $sub[] .= "'Superscript'";
	if (count($sub) > 0) $row2 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (in_array('align', $features) ) $sub[] .= "'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'";
	if (count($sub) > 0) $row2 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (in_array('fontcolor', $features)) $sub[] .= "'TextColor'";
	if (in_array('backgroundcolor', $features)) $sub[] .= "'BGColor'";
	if (count($sub) > 0) $row2 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (in_array('list', $features)) $sub[] .= "'NumberedList','BulletedList','-'";
	if (in_array('indent', $features)) $sub[] .= "'Outdent','Indent','Blockquote'";
	if (count($sub) > 0) $row2 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (in_array('link', $features) || in_array('file', $features) ) $sub[] .= "'Link'";
	if (in_array('link', $features) || in_array('file', $features)) $sub[] .= "'Unlink','Anchor'";
	if (in_array('image', $features)) $sub[] .= "'Image'";
	//if (in_array('liveresize', $features)) $sub[] .= "'liveresize'"; DEPRECATED
	//if (in_array('multipleselect', $features)) $sub[] .= "'multipleselect'"; DEPRECATED
	if (in_array('hr', $features)) $sub[] .= "'HorizontalRule'";
	if (in_array('specialchars', $features)) $sub[] .= "'SpecialChar'";
	if (count($sub) > 0) $row2 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	//if (in_array('date', $features)) $sub[] .= "'today'"; DEPRECATED
	//if (in_array('marquee', $features)) $sub[] .= "'marquee'"; DEPRECATED
	if (count($sub) > 0) $row2 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (!empty($row2)) array_push($rows, $row2);// .= "'/',\n";

	// 3. Zeile
	if (in_array('styles', $features) && !empty($selectablestyles)) $sub[] .= "'Styles'";
	if (in_array('font', $features)) $sub[] .= "'Format'";
	if (in_array('fontsize', $features)) $sub[] .= "'FontSize'";
	if (count($sub) > 0) $row3 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (in_array('changemode', $features)) $sub[] .= "'Source'";
	//if (in_array('popupeditor', $features)) $sub[] .= "'popupeditor'";DEPRECATED
	if (count($sub) > 0) $row3 .= '['.implode (', ', $sub)."],\n";
	unset($sub);
	if (!empty($row3)) array_push($rows, $row3);// .= "'/',\n";
	
	if (count($rows) > 0) {
		$final = "[\n".implode("'/',\n", $rows)."]";
		return str_replace("],\n]", "]]", $final);
	} else {
		return "''";
	}
}
?>
