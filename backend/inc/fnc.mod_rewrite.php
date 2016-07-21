<?php

//
// Common
//
function rewriteGetPath($idcat, $idlang, $highlight_cat = false) {
	global $cfg_client;
	
	$lang_prefix = '';
	if ($cfg_client['url_langid_in_defaultlang'] == '1') {
		$lang_prefix = rewriteGetLang($idlang) . '/';
	}
	
	return  $lang_prefix . rewriteCatGetPath($idcat, $idlang, $highlight_cat);
}

function rewriteManualUrlMatchAutoUrl($manual) {
	global $db, $cms_db, $cfg_client;
	
	$pieces = explode('/', $manual);
	//print_r($pieces);
	
	$pieces_rev = array_reverse($pieces);
	//test cat
	$sql = "SELECT 
				*
			FROM ".$cms_db['cat_lang']." 
			WHERE 
				rewrite_use_automatic = '1'
				AND rewrite_alias = '".$pieces_rev['0']."'
				AND idlang='".$GLOBALS['lang']."'";
	$db->query($sql);
	$matches = array();
	while ($db->next_record() ) {
		array_push($matches, $db->f('idcat'));
	}
	
	if (count($matches) > 0) {
		$pieces_temp = $pieces;
		//array_pop($pieces_temp);
		$search = implode('/', $pieces_temp);
		
		foreach($matches AS $v) {
			 $path = rewriteGetPath($v, $GLOBALS['lang']);
			 //echo "'$path' == '$search'";
			 if ($path == $search.'/') {
			 	return true;
			 }
		}
	}				
					
	//test page
	$sufix_preg = str_replace('.', '\.',$cfg_client['url_rewrite_suffix']);
	$search_clean = preg_replace('#'.$sufix_preg.'$#', '', $pieces_rev['0']);
	$sql = "SELECT B.idcat 
			FROM 
				".$cms_db['side_lang']." A 
				LEFT JOIN ".$cms_db['cat_side']." B USING (idside)
				LEFT JOIN ".$cms_db['clients_lang']." CS ON CS.idlang = A.idlang
			WHERE 
				A.rewrite_use_automatic = '1'
				AND CS.idlang = '".$GLOBALS['lang']."'
				AND A.rewrite_url='".$search_clean."'";
	
	$db->query($sql);
	$matches = array();
	while ($db->next_record() ) {
		array_push($matches, $db->f('idcat'));
	}
	
	if (count($matches) > 0) {
		$pieces_temp = $pieces;
		array_pop($pieces_temp);
		$search = implode('/', $pieces_temp);
		
		foreach($matches AS $v) {
			 $path = rewriteGetPath($v, $GLOBALS['lang']);
			 //echo "$path == $search/";
			 if($path == $search.'/') {
			 	return true;
			 }
		}
	}
	
	return false;
	
}

function rewriteCatGetPath($idcat, $idlang, $highlight_cat = false) {
	global $db, $cms_db;
	
	if ( $idcat < 1) {
		return false;
	}
	
	$sql = "SELECT 
				CL.rewrite_alias, C.parent 
			FROM
				".$cms_db['cat']." C
				LEFT JOIN ".$cms_db['cat_lang']." CL USING(idcat)
			WHERE 
				CL.idlang = '".$idlang."'
				AND C.idcat = '$idcat'";
	$db->query($sql);
	
	if ($db->next_record()) {
		$parent = $db->f('parent'); 
		$rewrite_alias = $db->f('rewrite_alias');
		if ($parent == 0 || $parent == '') {
			if ($highlight_cat) {
				return '<strong>'. $rewrite_alias .'/</strong>';
			} 
			
			return $rewrite_alias .'/';
		}
		
		if ($highlight_cat) {
				$rewrite_alias = '<strong>'. $rewrite_alias .'</strong>';
		} 
		
		return  rewriteCatGetPath($parent, $idlang, false). $rewrite_alias . '/';
	}  		
	
	return false;
}

function rewriteGetLang($idlang) {
	global $db, $cms_db;
	
	$sql  = 'SELECT 
				L.rewrite_key
			FROM 
				' . $cms_db['lang'] . ' L 
				LEFT JOIN '. $cms_db['clients_lang'] . ' CL USING(idlang)
			WHERE 
				L.idlang = ' . $idlang ;
	$db->query($sql);
	$db->next_record();
	return $db->f('rewrite_key');
}

function rewriteGetMapping($idlang) {
	global $db, $cms_db;
	
	$sql  = 'SELECT 
				L.rewrite_mapping
			FROM 
				' . $cms_db['lang'] . ' L 
				LEFT JOIN '. $cms_db['clients_lang'] . ' CL USING(idlang)
			WHERE 
				L.idlang = ' . $idlang ;
	$db->query($sql);
	$db->next_record();
	return $db->f('rewrite_mapping');
}

function rewriteGenerateMapping($idlang = false) {
	global $cfg_cms, $rewrite_mapping_search, $rewrite_mapping_replace;
	
	$idlang = ((int) $idlang > 0) ? $idlang : $GLOBALS['lang'];
	
	//rewritemapping is generated
	if (is_array($rewrite_mapping_search)) {
		return true;
	} 
	
	$mapping_file = rewriteGetMapping($idlang). '.php';
	//echo $cfg_cms['cms_path'].'tpl/standard/lang/urlfilter/'.$mapping_file;
	if (is_file($cfg_cms['cms_path'].'tpl/standard/lang/urlfilter/'.$mapping_file)) {
		$rewrite_mapping_raw = file($cfg_cms['cms_path'].'tpl/standard/lang/urlfilter/'.$mapping_file);
	} else {
		$rewrite_mapping_raw = file($cfg_cms['cms_path'].'tpl/standard/lang/urlfilter/standard.php');
	}
	
	$rewrite_mapping_search = array();
	$rewrite_mapping_replace = array();
	
	foreach ($rewrite_mapping_raw AS $v) {
		if (strstr($v, '||')) {
			$temp = explode('||', $v);
			array_push($rewrite_mapping_search, trim($temp['0']));
			array_push($rewrite_mapping_replace, trim($temp['1']));
		}
	}
}

function rewriteSaveUrlString($idlang, $what, $id, $string) {
	global $db, $cms_db;
	
	$id = (int) $id;
	
	if ($what == 'idcatside') {
		
		$sql = "SELECT 
					idside
				FROM 
					".$cms_db['cat_side']."
				WHERE 
					idcatside = '$id'";
		$db->query($sql);
		if ($db->next_record()) {
			$idside = $db->f('idside');
		} else {
			return false;
		}
		
		$sql = "UPDATE 
					".$cms_db['side_lang']."
				SET 
					rewrite_url = '$string' 
				WHERE 
					idside ='$idside' 
					AND idlang='$idlang'";
		$db->query($sql);
	} else if ($what == 'idcat') {
		$sql = "UPDATE 
					".$cms_db['cat_lang']."
				SET 
					rewrite_alias = '$string' 
				WHERE 
					idcat ='$id' 
					AND idlang='$idlang'";
		$db->query($sql);
	}
}

function rewriteGenerateUrlString($in, $allow_slash = false) {
	global $rewrite_mapping_search, $rewrite_mapping_replace;
	$slash = ($allow_slash) ? '/': '';
	$out = str_replace($rewrite_mapping_search, $rewrite_mapping_replace, $in);
	$out = preg_replace('#[^'.$slash.'a-z0-9_\.,-]#ium', '-', $out);
	$out = str_replace($rewrite_mapping_search, $rewrite_mapping_replace, $out);
	$out = preg_replace('#(-|\.)*$#um', '', $out);
	$out = strtolower($out);
	
	return $out;
}

function rewriteUrlIsAllowed($in, $allow_slash = false) {
	$slash = ($allow_slash) ? '/': '';
	if ($allow_slash) {
		if (preg_match('#^/#iu', $in)) {
			return false;
		}
		
		if(preg_match('#//#iu', $in)) {
			return false;
		}
	}
	return preg_match('#^['.$slash.'a-z0-9_\.,-]+$#iu', $in);
}

function rewriteUrlIsUnique($what, $id, $string) {
	global $db, $cms_db;
	
	if ($string == '') {
		return false;
	}
	
	if ($what == 'idcatside') {
		$sql = "SELECT rewrite_url 
				FROM 
					".$cms_db['side_lang']." A 
					LEFT JOIN ".$cms_db['cat_side']." B USING (idside)
					LEFT JOIN ".$cms_db['clients_lang']." CS ON CS.idlang = A.idlang
				WHERE 
					B.idcatside !='$id' 
					AND CS.idclient ='".$GLOBALS['client']."'
					AND A.rewrite_url='$string'";
	} else if ($what == 'idcat') {
		$sql = "SELECT 
					name, description, rewrite_use_automatic, rewrite_alias 
				FROM ".$cms_db['cat_lang']." 
				WHERE 
					idcat !='$id' 
					AND rewrite_alias = '$string'
					AND idlang='".$GLOBALS['lang']."'";
	} else {
		return false;
	}
	
	$db->query($sql);
		
	if ($db->next_record()) {
		return false;
	} 
	
	return true;
}

function rewriteMakeUniqueStringForLang($what, $id, $string, $idlang= '', $parentidcat = '') {
	global $db, $cms_db;
	
	preg_match('/([1-9][0-9]*)$/', $string, $num);
	$num = ($num['0'] >0) ? $num['0'] : 1;
	$idlang = ($idlang == '') ? $GLOBALS['perm']->get_lang() : (int) $idlang;
	$parentidcat = ($parentidcat == '') ? $GLOBALS['idcat'] : (int) $parentidcat;

	if($what == 'idcatside') {
		$sql = "SELECT rewrite_url 
				FROM 
					".$cms_db['side_lang']." A 
					LEFT JOIN ".$cms_db['cat_side']." B USING (idside)
				WHERE 
					B.idcatside !='$id' 
					AND A.idlang='".$idlang."' 
					AND A.rewrite_url='$string'
					AND B.idcat = '".$parentidcat."'";
	} else if ($what == 'idcat') {
		$sql = "SELECT 
					name, description, rewrite_use_automatic, rewrite_alias 
				FROM ".$cms_db['cat_lang']." 
				WHERE 
					idcat !='$id' 
					AND rewrite_alias = '$string'
					AND idlang='".$idlang."'";
	} else {
		return 'errorByGeneratingURLinModRewrite';
	}
	
	$db->query($sql);
		
	if ($db->next_record()) {
		$string = preg_replace('/([1-9][0-9]*)$/', ++$num, $string);
		if (! preg_match('/([1-9][0-9]*)$/', $string)) {
			$string = $string.$num;
		}
		//echo $string;exit;
		$string = rewriteMakeUniqueStringForLang($what, $id, $string);
	}
	
	return $string;
}

function rewriteIdcatIsUniqueToPath($idcat, $idlang, $rewrite_stack) {
	global $db, $cms_db;
	//	print_r($rewrite_stack);echo'<hr>';
	if ( count($rewrite_stack) < 1) {
		return false;
	}
	//	echo' pass <hr>';
	
	$sql = "SELECT 
				C.idcat, C.parent 
			FROM
				".$cms_db['cat']." C
				LEFT JOIN ".$cms_db['cat_lang']." CL USING(idcat)
			WHERE 
				CL.idlang = '".$idlang."'
				AND C.idcat = '$idcat'
				AND rewrite_alias = '".array_shift($rewrite_stack)."'";
	$db->query($sql);
	
	if ($db->next_record()) {
		$parent = $db->f('parent'); 
		//rewrite count stack ist to only accept the exact url
		if (($parent == 0 || $parent == '') && count($rewrite_stack) === 0) {
			return true;
		}
		return rewriteIdcatIsUniqueToPath($parent, $idlang, $rewrite_stack);
	}  		
	
	return false;
}

function rewriteAutoForAll($idlang) {
	global $db, $cms_db;
	
	$idlang = (int) $idlang;
	rewriteGenerateMapping($idlang);
	
	$sf_catinfos =& sf_factoryGetObject('PAGE', 'Catinfos');
	$sf_catinfos->setIdlang($idlang);
	$sf_catinfos->setCheckFrontendperms(false);
	$sf_catinfos->generate();
	$catinfo_array =& $sf_catinfos->getCatinfoDataArrayByRef();

	$sf_pageinfos =& sf_factoryGetObject('PAGE', 'Pageinfos');
	$sf_pageinfos->setIdlang($idlang);
	$sf_pageinfos->setCheckFrontendperms(false);	
	$sf_pageinfos->generate();
	$pageinfo_array =& $sf_pageinfos->getPageinfoDataArrayByRef();

	$sql = "UPDATE 
				".$cms_db['cat_lang']."
			SET 
				rewrite_alias = '' 
			WHERE 
				idlang='$idlang'
				AND rewrite_use_automatic = '1'";
	$db->query($sql);
	
	foreach($catinfo_array AS $k=>$v) {
		if( $v['rewrite_alias'] == '' ) {
			$string = rewriteGenerateUrlString($v['name']);
			$string = rewriteMakeUniqueStringForLang('idcat', $k, $string);
			rewriteSaveUrlString($idlang, 'idcat', $k, $string);
		}
	}
	
	$sql = "UPDATE 
				".$cms_db['side_lang']."
			SET 
				rewrite_url = ''
			WHERE 
				idlang='$idlang'
				AND rewrite_use_automatic = '1'";
	$db->query($sql);
	
	foreach($pageinfo_array AS $k=>$v) {
		if( $v['rewrite_url'] == '' ) {
			$string = rewriteGenerateUrlString($v['name']);
			$string = rewriteMakeUniqueStringForLang('idcatside', $k, $string);
			rewriteSaveUrlString($idlang, 'idcatside', $k, $string);	
		}
	}
}

//
// THE FOLLOWS NEEDS ONE OR MORE OF THIS: $con_side, $con_tree, $sf_lang_stack
//


function rewriteHandle($match){
	//echo '<pre>';
	//print_r($match);
	//echo '</pre>';
	$c = count($match);
	$is_idcatside = (strstr($match['0'], 'idcatside='));
	if ($c < 3) {
		//without lang var
		if ($is_idcatside) {
			$out = rewriteGetPageUrl($match['1'], $GLOBALS['lang']);
		} else {
			$out = rewriteGetCatUrl($match['1'], $GLOBALS['lang']);
		}
		
	} else {
		//with langvar
		if ($is_idcatside) {
			$out = rewriteGetPageUrl($match['3'], $match['1']);
		} else {
			$out = rewriteGetCatUrl($match['3'], $match['1']);
		}
	}
	//echo $out.'<br>';
	return $out;
}

function rewriteGetPageUrl($idcatside, $idlang, $force_langprefix = false) {
	global $con_side, $cfg_client, $cms_db, $sess, $lang;
	
	$rewrite_use_automatic = false;
	
	//look if automatic is in use
	if (is_array($con_side) && $idlang == $lang) {
		$rewrite_use_automatic = $con_side[$idcatside]['rewrite_use_automatic'];
	} else {
		$ado =& sf_factoryGetObjectCache('DATABASE', 'Ado');
		$sql = "SELECT *
				FROM
					".$cms_db['cat_side']." CS LEFT JOIN
					".$cms_db['side_lang']." SL USING(idside)
				WHERE 
					CS.idcatside = '".$idcatside."'
					AND  SL.idlang   = '".$idlang."'";
		
		$rs = $ado->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
		
		$rewrite_use_automatic = $rs->fields['rewrite_use_automatic'];
	}
	
	if ($rewrite_use_automatic == 1) {
		$alias = rewriteGetCatUrl($con_side[$idcatside]['idcat'], $idlang, $force_langprefix);	
		$string = rewriteGetPageAlias($idcatside, $idlang);
		return $cfg_client['htmlpath'].$alias. $string. $cfg_client['url_rewrite_suffix'];
	} else {
		$sessionstring = ($sess->mode=='getrewrite') ? $sess->id.'/':''; 
		return $cfg_client['htmlpath'].$sessionstring . rewriteGetPageAlias($idcatside, $idlang);
	}
}

function rewriteGetCatUrl($idcat, $idlang, $force_langprefix = false) {
	global $con_tree, $sf_lang_stack, $sess, $cfg_client, $lang, $startlang;
	
	$alias = '';
	
	while ($con_tree[$idcat]['parent'] != 0) {
		$alias = rewriteGetCatRewriteAlias($idcat, $idlang) . '/'. $alias;
		$idcat = $con_tree[$idcat]['parent'];
	}
	
	$alias = rewriteGetCatRewriteAlias($idcat, $idlang) . '/'. $alias;
	
	$sessionstring = ($sess->mode=='getrewrite') ? $sess->id.'/':''; 
	
	$langprefix = ( ($lang == $startlang && $cfg_client['url_langid_in_defaultlang'] == '0') && $force_langprefix != true ) ? '':$sf_lang_stack[$idlang]['rewrite_key']. '/';
	
	$alias = $sessionstring . $langprefix . $alias;
	return $cfg_client['htmlpath'].$alias;
	
}

function rewriteGetPageAlias($idcatside, $idlang) {
	global $con_side, $cms_db, $lang;
	
	$out = '';
	
	if (is_array($con_side) && $lang == $idlang) {
		if ($con_side[$idcatside]['rewrite_url'] == '') {
			$string = rewriteGenerateUrlString($con_side[$idcatside]['name']);
			$string = rewriteMakeUniqueStringForLang('idcatside', $idcatside, $string);
			rewriteSaveUrlString($idlang, 'idcatside', $idcatside, $string);
			$con_side[$idcatside]['rewrite_url'] = $string;
		}
		
		$out = $con_side[$idcatside]['rewrite_url'];
	} else {
		$ado =& sf_factoryGetObjectCache('DATABASE', 'Ado');
		$sql = "SELECT *
				FROM
					".$cms_db['cat_side']." CS LEFT JOIN
					".$cms_db['side_lang']." SL USING(idside)
				WHERE 
					CS.idcatside = '".$idcatside."'
					AND  SL.idlang   = '".$idlang."'";
		
		$rs = $ado->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
		
		$out = $rs->fields['rewrite_url'];
	}
	 
	
	return $out;
}

function rewriteGetCatRewriteAlias($idcat, $idlang) {
	global $con_tree, $cms_db, $sf_lang_stack, $lang;
	
	$out = '';
	
	if (is_array($con_tree) && $lang == $idlang) {
		if ($con_tree[$idcat]['rewrite_alias'] == '') {
			$string = rewriteGenerateUrlString($con_tree[$idcat]['name']);
			$string = rewriteMakeUniqueStringForLang('idcat', $idcat, $string);
			rewriteSaveUrlString($idlang, 'idcat', $idcat, $string);
			$con_tree[$idcat]['rewrite_alias'] = $string;
		}		
		$out = $con_tree[$idcat]['rewrite_alias'];	
	} else {
		$ado =& sf_factoryGetObjectCache('DATABASE', 'Ado');
		$sql = "SELECT *
				FROM
					".$cms_db['cat_lang']." CL LEFT JOIN
					".$cms_db['cat']." C USING(idcat)
				WHERE 
					CL.idcat = '".$idcat."'
					AND CL.idlang   = '".$idlang."'";
		
		$rs = $ado->Execute($sql);
		
		if ($rs === false) {
			return false;
		}
		
		if ($rs->EOF ) {
			return false;
		}
		
		$out = $rs->fields['rewrite_alias'];
	}
	
	return $out;
}
?>