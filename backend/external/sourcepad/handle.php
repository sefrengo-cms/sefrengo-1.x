<?PHP

include_once 'gb_source_pad.php';

/*
 * register globals = off
 * Registriert werden können die folgenden Variablen:
 * $gp_user['action'] = $gp_action;
 * $gp_user['lang'] = $gp_lang;
 * $gp_user['unique_nr'] = $gp_unique_nr;
 * $gp_user['color_opener'] = $gp_color_opener;
 * $gp_user['image_name'] = $gp_image_name;
 * $gp_user['tpl_set'] = $gp_tpl_set;
 */
while (list($key, $val) = @each($_GET))
{
	$key = substr ($key , 3);
	$gp_user[$key] = $val;
}



if ($gp_user['action'] == 'make_image'){
	gb_source_pad::handle_make_image($gp_user['image_name'], $gp_user['tpl_set']);

}
else{
	$handle = new gb_source_pad('*handle*');
	
	header('Content-type: text/html; charset=UTF-8');

	switch ($gp_user['action'])
	{
	    case 'make_js_pad':
	         $gp_return = $handle -> handle_make_js_pad($gp_user['unique_nr'], $gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_js_functions':
	         $gp_return = $handle -> handle_make_js_functions($gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_popup_color':
	         $gp_return = $handle -> handle_make_popup_color($gp_user['color_opener'], $gp_user['unique_nr'], $gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_popup_search':
	         $gp_return = $handle -> handle_make_popup_search($gp_user['unique_nr'], $gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_popup_search_replace':
	         $gp_return = $handle -> handle_make_popup_search_replace($gp_user['unique_nr'], $gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_popup_link':
	         $gp_return = $handle -> handle_make_popup_link($gp_user['unique_nr'], $gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_popup_image':
	         $gp_return = $handle -> handle_make_popup_image($gp_user['unique_nr'], $gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_popup_list':
	         $gp_return = $handle -> handle_make_popup_list($gp_user['unique_nr'], $gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_popup_table':
	         $gp_return = $handle -> handle_make_popup_table($gp_user['unique_nr'], $gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_popup_special_chars':
	         $gp_return = $handle -> handle_make_popup_special_chars($gp_user['unique_nr'], $gp_user['tpl_set'], $gp_user['lang']);
	         break;
	    case 'make_css':
	         $gp_return = $handle -> handle_make_css($gp_user['tpl_set']);
	         break;
	}
}

echo $gp_return;

?>