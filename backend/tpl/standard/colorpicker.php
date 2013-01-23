<?php
/******************************************************************************
Description : colorpicker for css-editor
Copyright   : Jürgen Brändle, 2002-2003
Author      : Jürgen Brändle, braendle@web.de
Urls        :
Create date : 2003-01-08
Last change : 2003-02-04

Browser: IE6 / Mozilaa 1.2+ tested
Should be working in all Browser starting with Netscape 4 and IE 4 - NOT TESTED
********************************************************************************/
// function names to support diferrent function-calls
// $colorpicker['func_select']		name of the function to be called when user selects a color, defaults to "ColorPalette_OnClick"
// $colorpicker['func_over']	name of the function to be called when user is over a color, no default
// $colorpicker['func_out']		name of the function to be called when user leaves a color, no default
//
// set your function in the php-file which includes the colorpicker before the file
// is included
//
if (!$colorpicker['func_select']) $colorpicker['func_select'] = "ColorPalette_OnClick";

// set styling
// $colorpicker['linked']		if true, use linked images for colorpicker, default: false
//							use this to get a NS4 or IE4 compatible colorpicker, or if
//							you're unsure about css-support of the browser
// $colorpicker['type']		type of the colorpicker, default: long
//							long  : 6 cells, 36 rows, width 72px
//							short0: 18 cells, 12 rows, width 216px
//							short1: 21 cells, 12 rows, width 252px (a bit like dreamweaver ...)
// $colorpicker['width']		width of the colorpicker table, default: 72
// $colorpicker['cell_width']	width of a colorpicker cell, default: colorpicker_width/cp_default_cell
// $colorpicker['cell_height']	height of a colorpicker cell, default: colorpicker_width/cp_default_cell
// $colorpicker['visible']		visibility of the colorpicker table, default: visible
// $colorpicker['bgcolor']		backgroundcolor for table, default: #F0F0F0
//
// set your values in the php-file which includes the colorpicker before the file
// is included
//
if (!$colorpicker['type']) $colorpicker['type'] = "long";
switch ($colorpicker['type']) {
	case 'short0':
		$cp_default_width = 216;
		$cp_default_cell = 18;
		break;
	case 'short1':
		$cp_default_width = 252;
		$cp_default_cell = 21;
		break;
	default:
		$cp_default_width = 72;
		$cp_default_cell = 6;
		break;
}
if (!$colorpicker['width'])       $colorpicker['width']       = $cp_default_width;
if (!$colorpicker['cell_width'])  $colorpicker['cell_width']  = $colorpicker['width']/$cp_default_cell;
if (!$colorpicker['cell_height']) $colorpicker['cell_height'] = $colorpicker['width']/$cp_default_cell;
if (!$colorpicker['visible'])     $colorpicker['visible']     = "visible";
if (!$colorpicker['bgcolor'])     $colorpicker['bgcolor']     = "#f0f0f0";
$colorpicker['width'] += $cp_default_cell*2;

function writeColorCell( $colorstring, $showtype ) {
	global $cfg_cms, $colorpicker;

	$cellstring = '';

	$htmlstyle = ($colorpicker['linked']) ? ' width="'.$colorpicker['cell_width'].'" height="'.$colorpicker['cell_height'].'"': '';

	if ($colorstring == "blank") {
		$cellstring .= '<td style="cursor:default;">';
	} else {
		$events  = 'onclick="'.$colorpicker['func_select'].'(\''.$colorstring.'\')"';
		$events .= ($colorpicker['func_over']) ? ' onmouseover="'.$colorpicker['func_over'].'(\''.$colorstring.'\')"': '';
		$events .= ($colorpicker['func_out'])  ?   ' onmouseout="'.$colorpicker['func_out'].'(\''.$colorstring.'\')"': '';
		$quicktip = ' title="'.$colorstring.'"';

		$cellstring .= '<td';
		if ($colorpicker['linked']) {
			$cellstring .= '><a style="background:'.$colorstring.'" href="javascript:void(0)" '.$events.''.$quicktip.'> </a>';
		} else {
			$cellstring .= $events.$quicktip.'>';
		}
	}
	$cellstring .= "</td>\n";
	if ($showtype) echo $cellstring;
	else return $cellstring;
}

function getColorstring( $r, $g, $b ) {
	$colorstring = '#';
	$colorstring .=  ($r < 0x10) ? '0'.dechex($r): dechex($r);
	$colorstring .=  ($g < 0x10) ? '0'.dechex($g): dechex($g);
	$colorstring .=  ($b < 0x10) ? '0'.dechex($b): dechex($b);
	return $colorstring;
}

function createColorPicker() {
	global $colorpicker;
	$htmlcolorpicker = '';
	// write the style-block
	$htmlcolorpicker .=  '
';

	//
	// table of selectable colors
	// needs a browser with support for css in tables and javascript
	//
	// type 1: 36 Zeilen,  6 Felder
	// type 2: 12 Zeilen, 18 Felder
	// type 3: 12 Zeilen, 21 Felder

	$htmlcolorpicker .=  '<table id="colorpicker" class="colorpick" cellpadding="0" width="'.$colorpicker['width'].'">'."\n";
	switch ($colorpicker['type']) {
		case "long":
			for ($i = 0xFF; $i >= 0x00; $i -= 0x33) {
				for ($j = 0xFF; $j >= 0x00; $j -= 0x33) {
					$htmlcolorpicker .=  "<tr>\n";
					for ($k = 0xFF; $k >= 0x00; $k -= 0x33) {
						$htmlcolorpicker .= writeColorCell( getColorstring( $i, $j, $k ), '0' );
					}
					$htmlcolorpicker .=  "</tr>\n";
				}
			}
			break;
		case "short0":
			for ($off = 0x00; $off < 0xFF; $off += 0x99) {
				for ($i = 0x00; $i < 0x100; $i += 0x33) {
					$htmlcolorpicker .=  "<tr>\n";
					for ($j = 0x00; $j < 0x99; $j += 0x33) {
						$j1 = $j + $off;
						for ($k = 0x00; $k < 0x100; $k += 0x33) {
							$htmlcolorpicker .=  writeColorCell( getColorstring( $j1, $k, $i ), '0' );
						}
					}
					$htmlcolorpicker .=  "</tr>\n";
				}
			}
			break;
		case "short1":
			for ($off = 0x00; $off < 0xFF; $off += 0x99) {
				for ($i = 0x00; $i < 0x100; $i += 0x33) {
					$htmlcolorpicker .=  "<tr>\n";
					$htmlcolorpicker .=  writeColorCell("blank", '0');
					if ($off == 0x00) { // greyscale
						$r = $g = $b = $i;
					} else { // main colors
						$r = ($i == 0x00 || $i == 0x99 || $i == 0xcc) ? 0xff: 0x00;
						$g = ($i == 0x33 || $i == 0x99 || $i == 0xff) ? 0xff: 0x00;
						$b = ($i == 0x66 || $i >= 0xcc ) ? 0xff: 0x00;
					}
					$htmlcolorpicker .=  writeColorCell( getColorstring( $r, $g, $b ), '0' );
					$htmlcolorpicker .=  writeColorCell("blank", '0');
					for ($j = 0x00; $j < 0x99; $j += 0x33) {
						for ($k = 0x00; $k < 0x100; $k += 0x33) {
							$htmlcolorpicker .=  writeColorCell( getColorstring( $j + $off, $k, $i ), '0' );
						}
					}
					$htmlcolorpicker .=  "</tr>\n";
				}
			}
			break;
	}
	$htmlcolorpicker .=  "</table>\n";
	return $htmlcolorpicker;
}
?>