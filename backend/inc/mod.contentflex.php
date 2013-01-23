<?php

if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Version 1.1.0.0
// (c) 2005 by Reto Haldemann v/o Mistral
// Version 1.2.0 - 1.n.n
// by Alexander M. Korn (amk@gmx.info)

$mvars['contentflex']= "contentflex: Version 1.8.6";

//if ($mvars['test'] == "true") { echo "<br />mvars:";	print_r($mvars); }
// Template mit den CMS-Tags ersetzen

// ************************************************************************************************
if (!function_exists('edit_outer_tpl')) {
	function edit_outer_tpl($last, $now) {
	    global $mvars;
        if (($last == '') && ($now == '')) return '';
        if ((($last == '10') || ($last == '11')) && ($now == '10')) return '';
        if ((($last == '20') || ($last == '21')) && ($now == '20')) return '';
        if ((($last == '30') || ($last == '31')) && ($now == '30')) return '';
        if ((($last == '40') || ($last == '41')) && ($now == '40')) return '';
        if ((($last == '50') || ($last == '51')) && ($now == '50')) return '';
        if ((($last == '60') || ($last == '61')) && ($now == '60')) return '';
        
        $outer_tpl = '';
        // Ende
        if (($last == '10') || ($last == '11')) {
            $outer_tpl .= $mvars['MOD_VALUE_62'];
        }
        if (($last == '20') || ($last == '21')) {
            $outer_tpl .= $mvars['MOD_VALUE_64'];
        }
        if (($last == '30') || ($last == '31')) {
            $outer_tpl .= $mvars['MOD_VALUE_67'];
        }
        if (($last == '40') || ($last == '41')) {
            $outer_tpl .= $mvars['MOD_VALUE_69'];
        }
        if (($last == '50') || ($last == '51')) {
            $outer_tpl .= $mvars['MOD_VALUE_6067'];
        }
        if (($last == '60') || ($last == '61')) {
            $outer_tpl .= $mvars['MOD_VALUE_6069'];
        }
        
        // Start
        if (($now == '10') || ($now == '11')) {
            $outer_tpl .= $mvars['MOD_VALUE_61'];
        }
        if (($now == '20') || ($now == '21')) {
            $outer_tpl .= $mvars['MOD_VALUE_63'];
        }
        if (($now == '30') || ($now == '31')) {
            $outer_tpl .= $mvars['MOD_VALUE_66'];
        }
        if (($now == '40') || ($now == '41')) {
            $outer_tpl .= $mvars['MOD_VALUE_68'];
        }
        if (($now == '50') || ($now == '51')) {
            $outer_tpl .= $mvars['MOD_VALUE_6066'];
        }
        if (($now == '60') || ($now == '61')) {
            $outer_tpl .= $mvars['MOD_VALUE_6068'];
        }
               
        $outer_tpl = stripslashes($outer_tpl);
		return $outer_tpl;
	}
}

// **** END FUNCTION **************************************************************

// ************************************************************************************************
// Fals eine Nummer fuer den ersten darzustellenden Eintrag uebergeben wurde dies in $page speichern
if(isset($page)) {
    $page = (! is_numeric($_REQUEST['page'])) ? 1:$_REQUEST['page'];
} else {
    $page = 1;
}

// Fals eine Nummer fuer die Anzahl darzustellenden Eintraege uebergeben wurde die Modulvorgabe ueberschreiben
if(isset($items)) {
    $mvars['max_items'] = (! is_numeric($_REQUEST['items'])) ? 1:$_REQUEST['items'];
}

#if ($mvars['max_items']!=0 && empty($cflex_items_mem)) {
#	$cflex_items_mem=($mvars['max_items']-1)+$page;
#}
#
#if ((bool) $cms_mod['modul']['lastentry']){
#	$page=($mvars['max_items']-1)-$page;
#	unset($cflex_items_mem);
#}
#echo $mvars['repeat_id'];
#
#$mvars['max_items']=$cflex_items_mem;

// Nur wenn die Wiederholung im Bereich ist der augegeben werden soll.
if($cms_mod['modul']['id'] >= $page
&& ($cms_mod['modul']['id'] < ($mvars['max_items']+$page)
||  $mvars['max_items'] == 0)) {

    //Insert Element at top
    echo stripslashes($mvars['editbutton_at_top']);

    //Inhalt vorbereiten
    $mvars['tpl_inner'] = stripslashes($mvars['tpl_inner']);
    $mvars['tpl_inner'] = str_replace("{editbutton}",stripslashes($mvars['editbutton']),$mvars['tpl_inner']);
  	$mvars['tpl_inner'] = str_replace("{edit}",stripslashes($mvars['edit_single']),$mvars['tpl_inner']);
    $mvars['tpl_inner'] = str_replace("{insert}",stripslashes($mvars['insert_single']),$mvars['tpl_inner']);
    // **** Umschließendes Template  *************************************************************
    if ( !isset(${'flex2_outer_tpl'.$cms_mod['container']['id']}) || $cms_mod['modul']['id'] == 1) {
        ${'flex2_outer_tpl'.$cms_mod['container']['id']} = '';
    }
    $mvars['tpl_outer'] = stripslashes($mvars['tpl_outer']);
    $temp = edit_outer_tpl(${'flex2_outer_tpl'.$cms_mod['container']['id']}, $mvars['tpl_outer']);
    $mvars['tpl_inner'] = $temp .$mvars['tpl_inner'];
    //neuwe tpl speichern
    ${'flex2_outer_tpl'.$cms_mod['container']['id']} = $mvars['tpl_outer'];
	
	// Am Ende schliessen
   	if($mvars['is_last'] == true || (($mvars['max_items'] != 0) && ($mvars['repeat_id'] == ($mvars['max_items']+$page-1)))) {
//   	if($mvars['is_last'] == true || ($mvars['repeat_id'] == ($mvars['max_items']+$page-1))) {
        $mvars['tpl_inner'] .= edit_outer_tpl(${'flex2_outer_tpl'.$cms_mod['container']['id']}, '') ;
    }
    
    
    // **** Ende Umschließendes Template  ********************************************************

    //Inhalt ausgeben
    eval('?>'.$mvars['tpl_inner']);
    // Versionsausgabe
    if ($mvars['version'] == "true") 
    { 
        echo "<hr />Version: ".$mvars['contentflex_cache']."<br />"; 
        echo "Version: ".$mvars['contentflex']."<hr />"; 
    }
    // echo $mvars['tpl_inner'];
	
	// Navigation nur Ausgeben wenn noetig
	if ($mvars['max_items'] != 0) { 
		$mvars['nav'] = false;
    	// Navigation auf die anderen Seiten einfuegen
    	$mvars['tpl_nav'] = stripslashes($mvars['tpl_nav']);
    	if($mvars['is_last'] == true || $mvars['repeat_id'] == ($mvars['max_items']+$page-1)) {
        	if($page > 1) {
            	if(($page-$mvars['max_items']) < 1)
                	$last = 1;
	            else
    	            $last = $page-$mvars['max_items'];
            
        	    //echo $mod['repl_string'] = '<a href="'.$con_side[$idcatside]['link'].'&page='.$last.'">Zur&#252;ck</a>';
            	$mvars['repl_string'] = '<a href="'.$con_side[$idcatside]['link'].'&amp;page='.$last.'">'.$mvars['MOD_VALUE_12'].'</a>';
    	        $mvars['tpl_nav'] = str_replace('{prev}', $mvars['repl_string'], $mvars['tpl_nav']);
				$mvars['nav'] = true;
	        }
    	    else 
        	    $mvars['tpl_nav'] = str_replace('{prev}', '', $mvars['tpl_nav']);
        
	        if($mvars['is_last'] != true) {
    	        $next = $page+$mvars['max_items'];
        	    
            	//echo $mod['repl_string'] = '<a href="'.$con_side[$idcatside]['link'].'&page='.$next.'">Vor</a>';
	            $mvars['repl_string'] = '<a href="'.$con_side[$idcatside]['link'].'&amp;page='.$next.'">'.$mvars['MOD_VALUE_11'].'</a>';
    	        $mvars['tpl_nav'] = str_replace('{next}', $mvars['repl_string'], $mvars['tpl_nav']);
				$mvars['nav'] = true;
	        } 
    	    else
        	    $mvars['tpl_nav'] = str_replace('{next}', '', $mvars['tpl_nav']);
       		
	       if (	$mvars['nav'] )
 		        echo $mvars['tpl_nav'];
	    }
	}
}

unset($mvars);
?>