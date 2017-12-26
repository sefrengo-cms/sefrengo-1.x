<?php

if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Version 1.1.0.0
// (c) 2005 by Reto Haldemann v/o Mistral
// Version 1.2.0 - 1.n.n
// by Alexander M. Korn (amk@gmx.info)

//  zur Fehlersuche
//if(isset($flextest))
//{
//    $mod['test'] = "true";
//    $modv['version'] = "true";
//}

$modv['contentflex_cache']= "contentflex_cache: Version 1.8.6";

//if ($mod['test'] == "true") { echo "<br />mod:";	print_r($mod); }
//if ($modv['test'] == "true") { echo "<br />modv:";	print_r($modv); }

// preserve Sefrengo backward compatibility
if (empty($mod_lang['cf_add_first_pos']) && empty($mod_lang['cf_insert_p1'])) {
	$mod_lang['cf_add_first_pos']		= 'Als erstes Element einf&uuml;gen';
	$mod_lang['cf_insert_p1']				= 'Nach Element';
	$mod_lang['cf_insert_p2']				= 'einf&uuml;gen';
}

if(! function_exists(get_val)){
  function get_val($value_name)
  {
   global $idcatside, $cms_db, $db, $con_side, $cms_mod;

   $sql = "SELECT
      *
     FROM
      ".$cms_db['content_external'] ."
     WHERE
      idsidelang='".$con_side[$idcatside]['idsidelang']."'
      AND container='".$cms_mod['container']['id']."'
      AND idtype='$value_name'";
   $db->query($sql);
   $db->next_record();

   return $db->f('value');
  }
}

if(! function_exists(set_val)){
  function set_val($value_name, $value)
  {
   global $idcatside, $cms_db, $db, $con_side, $cms_mod;
   //Schauen, ob es den Wert schon gibt
   $sql = "SELECT
      *
     FROM
      ".$cms_db['content_external'] ."
     WHERE
      idsidelang='".$con_side[$idcatside]['idsidelang']."'
      AND container='".$cms_mod['container']['id']."'
      AND idtype='$value_name'";
   $db->query($sql);

   //Es gibt den Wert schon -> wert aktuallisieren
   if ($db->next_record()){
    $sql = "UPDATE
       ". $cms_db['content_external'] ."
      SET
       value='$value'
      WHERE
       idsidelang='".$con_side[$idcatside]['idsidelang']."'
       AND container='".$cms_mod['container']['id']."'
       AND idtype='$value_name'";
   }
   //Es gibt den Wert noch nicht, neu in tabelle einf�gen
   else{
    $sql = "INSERT INTO
       ". $cms_db['content_external'] ."
       (idsidelang, container, idtype, value)
      VALUES
       ('".$con_side[$idcatside]['idsidelang']."',
        '".$cms_mod['container']['id']."',
        '$value_name', '$value')";
   }

   $db->query($sql);

  //Letzte �nderung Datum �ndern!
  $sql = "UPDATE ". $cms_db['side_lang']. " SET lastmodified='".time()."' WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' ";
                  $db->query($sql);

   //Cache l�schen
   //Seite f�rs frontend neu generieren, da sich ein Wert ge�ndert hat
   change_code_status($idcatside, 1, 'idcatside');

  }
}
		
if(! function_exists(getstringparts)) {
	function getstringparts($string, $tagin, $tagout)
	{
		/**
		* Splits a string into 3 parts dependent on tagin & tagout.
		*
		* @author			Alexander M. Korn <amk@gmx.info>
		* @version		1.0.0
		*/
		$tagin_pos 	= strpos($string,$tagin);
		$tagout_pos =	strpos($string,$tagout);
		if ( $tagin_pos !== false || $tagout_pos !== false || $tagout_pos > $tagin_pos ) {
			$tagin_len	=	strlen($tagin);
			$tagout_len	=	strlen($tagout);
			$strparts['first']		=	substr($string,0,$tagin_pos);
			$strparts['middle']		=	substr($string,$tagin_pos+$tagin_len,$tagout_pos-($tagin_pos+$tagin_len));
			$strparts['last']			=	substr($string,$tagout_pos+$tagout_len);
		} else {
			$strparts['first']		=	$string;
			$strparts['middle']		=	'';
			$strparts['last']			=	'';
		}
		return $strparts;	 		
	}		
}	

// ************************************************************************************************
// ContentFlexFileList Function 1.2.0
// by Alexander M. Korn (amk@gmx.info)
// License: GPL

if(! function_exists(contentflexfilelist)){
	function contentflexfilelist(	$file='',
																$tpl_head='',
																$tpl_dir_start='',
																$tpl_row='',
																$tpl_between_row='',
																$tpl_dir_end='',
																$tpl_foot='',
																$frontendsubfolders='false',
																$modconfsubfolders='true',
																$modconfavailfolders='',
																$modconfavailfiletypes='',
																$modconftplbetweenrowcounter='1',
																$modconftplbetweenrowcounterreset='',
																$modconfdirtplon1stlevel='true',
																$modconflabelselection='',
																$modconflabelsubfolders='',
																$modconfslashreplacement='/',
																$modconfdatetimeformat='Y-m-d',
																$modconfcomplfolderstring='true',
																$modconftreemode='false',
																$modconffiletypeiconpath='',
																$modconfselectmode='',
																$modconflistselectedfile='true',
																$modconfselectrights='',
																$modconffilelimit='',
																$modconffilesort='filename > ASC') {


		$modconffilelimit=(int) $modconffilelimit;
		$modconftplbetweenrowcounter=(int) $modconftplbetweenrowcounter; 
		//
		// sorting preperation
		//

		if(empty($modconffilesort))
			$modconffilesort='filename > ASC';

		$filelist_filesort['array'] = array();		
	
		$filelist_filesort['raw'] = trim( str_replace(' > ', '>',$modconffilesort));
	
	  $filelist_filesort['raw_vals'] = explode("\n", $filelist_filesort['raw']);
	  foreach ($filelist_filesort['raw_vals'] AS $v) {
	    $filelist_filesort['sorting_pieces'] = explode('>', trim($v));
	    $filelist_filesort['array'][]=$filelist_filesort['sorting_pieces']['0'].' '.$filelist_filesort['sorting_pieces']['1'];
	  }
		


		global $cfg_client,$modtemp,$db,$cms_db,$modv,$cms_mod,$con_side,$idcatside,$cfg_cms,$lang,	
		       $client,$sess,$view,$entry;

		// get content id
		$sql = "SELECT idcontent FROM ".$cms_db['content']." WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='".$cms_mod['container']['id']."' AND number='".$cms_mod['modul']['id']."'";
		$db->query($sql);
		$db->next_record();
		$entry_content_id="id".$db->f('idcontent');

		global ${$entry_content_id.'cf_fl_dir'},${$entry_content_id.'cf_fl_subdirs'};
		
		$filelist_output='';

		if($modconfselectmode=='fsb'){
		$file='';
			$mod['cf_fl_dir']=get_val('cf_fl_folder_'.$entry_content_id);	
			$mod['cf_fl_subdirs']=get_val('cf_fl_show_subfolders_'.$entry_content_id);
			if($modconfsubfolders!='true')
				$mod['cf_fl_subdirs']=0;
		}

		$filelist_dirs_all=Array();
		$sql = "SELECT * FROM ".$cms_db['directory']." WHERE idclient=".$client." AND status=0 ";
		$db->query($sql);
		while ($db->next_record()) 
			$filelist_dirs_all[$db->f('iddirectory')]=$db->f('dirname');
		
		if(!empty($file) && $modconfselectmode=='rb' )
			$selected_file=basename($file);

	  // get base-path
	  if (!empty($file))
			$filelist_path=str_replace($cfg_client['upl_htmlpath'],'',dirname($file))."/";

		if(empty($file) || $modconfselectmode=='fsb' )
			$sql = "SELECT * FROM ".$cms_db['directory']." WHERE idclient=".$client." AND status=0 ";
		elseif($frontendsubfolders=="true")
			$sql = "SELECT * FROM ".$cms_db['directory']." WHERE idclient=".$client." AND dirname like '$filelist_path%' AND  status=0 ";
		else
			$sql = "SELECT * FROM ".$cms_db['directory']." WHERE idclient=".$client." AND dirname like '$filelist_path' AND  status=0 ";

		// filter modulconfig selected pathes
		if($modconfavailfolders!='' && $modconfavailfolders!='true') {
	
			$thisdirsonly=Array();
			$thisdirsonly=explode(',',$modconfavailfolders);
		
			$sql_addondirs=Array();
			foreach($thisdirsonly as $k => $v){
		
					$sql_addondirs[]=" dirname like '".$filelist_dirs_all[$v].(($modconfsubfolders=='true')?'%':'')."' ";
			}
			$sql_addon=implode ('OR',$sql_addondirs);
		
		} else
			$sql_addon='';



		// get dir(s)
		$filelist_dirs=Array();

		// filter modulconfig selected pathes
		if (!empty($sql_addon))
			$sql.=" AND (".$sql_addon.")";

		$db->query($sql);

		while ($db->next_record()) 
			$filelist_dirs[$db->f('iddirectory')]=$db->f('dirname');
	
		natsort($filelist_dirs);

		// frontendselectbox 
		if($modconfselectmode=='fsb'){

			if ($sess->name=='sefrengo' && $view == 'edit' && _type_check_editable(true,$modconfselectrights,'edit')) {
		
			  if (${$entry_content_id.'cf_fl_dir'}!='' && $mod['cf_fl_dir']!=${$entry_content_id.'cf_fl_dir'}) {
			   		set_val('cf_fl_folder_'.$entry_content_id, ${$entry_content_id.'cf_fl_dir'});
			   		$mod['cf_fl_dir']=${$entry_content_id.'cf_fl_dir'};
			  }

					if ($_POST[$entry_content_id.'cf_fl_dir']!='' && ${$entry_content_id.'cf_fl_subdirs'}=='' && $mod['cf_fl_subdirs']!=0) {
							set_val('cf_fl_show_subfolders_'.$entry_content_id, 0);
							$mod['cf_fl_subdirs']=0;
					}	elseif ($_POST[$entry_content_id.'cf_fl_dir']!='' && ${$entry_content_id.'cf_fl_subdirs'}!='' && $mod['cf_fl_subdirs']!=1 ) {
							set_val('cf_fl_show_subfolders_'.$entry_content_id, 1);
							$mod['cf_fl_subdirs']=1;
					}

				// form				
				$fsb_out = "\n".'<form style="font-size:8pt;" class="contentflex_fl_form" method="post" name="'.$entry_content_id.'" action="'.$con_side[$idcatside]['link'].'">'."\n";
				$fsb_out.= '<label class="contentflex_fl_select_label" for="'.$entry_content_id.'cf_fl_dir">'.$modconflabelselection.'</label>'."\n".'
										<select id="'.$entry_content_id.'cf_fl_dir"  class="contentflex_fl_select" name="'.$entry_content_id.'cf_fl_dir" size="1" onchange="document.'.$entry_content_id.'.submit();">'."\n";
			
				if ($mod['cf_fl_dir'] == '0')
					$fsb_out.= '<option value="0" selected>----------------</option>'."\n";
				else
					$fsb_out.= '<option value="0">----------------</option>'."\n";
	
				foreach($filelist_dirs as $k => $v)
					if ($v == $mod['cf_fl_dir']) 
						$fsb_out.= '<option value="'. $v .'" selected>'.htmlentities( $v , ENT_COMPAT, 'UTF-8').'</option>'."\n";
			    else
			    	$fsb_out.= '<option value="'. $v .'">'.htmlentities( $v , ENT_COMPAT, 'UTF-8').'</option>'."\n";
		
				$fsb_out.= '</select>'."\n";

				if ($modconfsubfolders=='true')
					$fsb_out.= '<input id="'.$entry_content_id.'cf_fl_subdirs" class="contentflex_fl_checkbox" type="checkbox" name="'.
											$entry_content_id.'cf_fl_subdirs" value="checkbox" '.(($mod['cf_fl_subdirs']==1)?'checked="checked"':'').
											' onclick="document.'.$entry_content_id.'.submit();"/>'."\n".
											'<label class="contentflex_fl_checkbox_label" for="'.$entry_content_id.'cf_fl_subdirs">'.
											$modconflabelsubfolders.'</label>'."\n";
			
				$fsb_out.= '</form>'."\n";
			}
		}

		if (empty($mod['cf_fl_dir']) && $modconfselectmode=='fsb')
			return $fsb_out;
		elseif (empty($file) && $modconfselectmode!='fsb')
			return;

			
		// get files
		if (!empty($filelist_dirs)){		
		
			$sql_addondirs=Array();
		
			foreach($filelist_dirs as $k => $v)
					$sql_addondirs[]=" iddirectory='".$k."' ";
		
			$sql_addon=implode ('OR',$sql_addondirs);
				
			$sql = "SELECT *,UNIX_TIMESTAMP(lastmodified) AS lastmodified,UNIX_TIMESTAMP(created) AS created FROM ".$cms_db['upl']." WHERE".$sql_addon." ORDER BY ".implode(',',$filelist_filesort['array']);



			$db->query($sql);
			
			while ($db->next_record()) {
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['filename']=$db->f('filename');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['titel']=$db->f('titel');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['description']=$db->f('description');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['filesize']=$db->f('filesize');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['pictwidth']=$db->f('pictwidth');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['pictheight']=$db->f('pictheight');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['pictthumbwidth']=$db->f('pictthumbwidth');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['pictthumbheight']=$db->f('pictthumbheight');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['iddirectory']=$db->f('iddirectory');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['idfiletype']=$db->f('idfiletype');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['idfile']=$db->f('idupl');
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['created']=date($modconfdatetimeformat,$db->f('created'));
				$filelist_files[$db->f('iddirectory')][$db->f('idupl')]['lastmodified']=date($modconfdatetimeformat,$db->f('lastmodified'));
			}

			// get filetypes	
			$sql = "SELECT * FROM ".$cms_db['filetype'];
			
			$db->query($sql);

			$filelist_filetypes=Array();
		
			while ($db->next_record()) {
				$filelist_filetypes[$db->f('idfiletype')]['filetype']=$db->f('filetype');
				$filelist_filetypes[$db->f('idfiletype')]['filetypepict']=$db->f('filetypepict');
				$filelist_filetypes[$db->f('idfiletype')]['filetypedesc']=$db->f('description');
				$filelist_filetypes[$db->f('idfiletype')]['filemimetype']=$db->f('mimetype');
			}

			// ouput creation	
			$filelist_output=$fsb_out.$tpl_head;
			$tpl_dir_end_collected='';
			$dir_count=0;
			$file_count=0;
			$filelist_dir_level_mem=0;
			$all_count=0;
			$all_file_count=0;
			$dirname_mem='';
			$new_dir_name='';
			if(strpos($modconfavailfiletypes,',')!==false)
				$filetypes=explode(',',strtolower($modconfavailfiletypes));

			// list creation
			foreach($filelist_dirs as $k => $v){
			
			// filtering
			if ((strpos($v,$mod['cf_fl_dir'])!==false && $modconfselectmode=='fsb' && ($mod['cf_fl_subdirs']==1)) ||
					($modconfselectmode=='fsb' && ($mod['cf_fl_subdirs']==0) && strpos($v,$mod['cf_fl_dir'])!==false && strlen($v)==strlen($mod['cf_fl_dir'])) ||
					!empty($file)) {

				$dir_count++;
				$all_count++;
				if ($dir_count>1 || $modconfdirtplon1stlevel!='false'){
				
					$tpl_dir_output=str_replace('{dir_no}',$dir_count,$tpl_dir_start);
					$tpl_dir_output=str_replace('{all_no}',$all_count,$tpl_dir_output);
					
					if ($modconfcomplfolderstring=="false" && substr_count($filelist_dirs[$k],'/')>1)
							$new_dir_name=substr(strrchr(substr($filelist_dirs[$k],0,-1),"/"),1).'/';
					else 
							$new_dir_name=$filelist_dirs[$k];
							
					$filelist_output.=str_replace('{dirname}',str_replace('/',$modv['MOD_VALUE_2005'],$new_dir_name),$tpl_dir_output);

				}




				if (is_array($filelist_files[$k]))
					foreach($filelist_files[$k] as $v1){
					 
				  		$mod['thumbfile_ext'] = substr (strrchr ($v1['filename'], "."), 1);
				  		if (	(	strpos($modconfavailfiletypes,strtolower($mod['thumbfile_ext']))!==false || $modconfavailfiletypes=="true" || empty($modconfavailfiletypes) ) &&
				  					(	(	$modconfselectmode!='rb' || $modconflistselectedfile!='false' ) || (	$modconflistselectedfile=='false' && strpos($v1['filename'],$selected_file)===false	) ) ) {	

									$file_count++;
									$all_file_count++;
									$all_count++;
					
								if( $all_file_count<=$modconffilelimit || $modconffilelimit==0 ){
									
									$filelist_output_row=$tpl_row;
									$filelist_output_row_array=array();
									$filelist_output_row_array['all_no'] = $all_count;
									$filelist_output_row_array['file_no'] = $file_count;
									$filelist_output_row_array['file_no_rev'] = count($filelist_files[$k])-$file_count;
									$filelist_output_row_array['all_file_no'] = $all_file_count;
									$filelist_output_row_array['dir_no'] = $dir_count;
									$filelist_output_row_array['dirname'] = str_replace($dirname_mem,'',str_replace('/',$modv['MOD_VALUE_2005'],$new_dir_name));
									$filelist_output_row_array['filepath'] = $cfg_client['upl_htmlpath'].$filelist_dirs[$k];
									$filelist_output_row_array['filename'] = $v1['filename'];
									$filelist_output_row_array['fileurl'] = 'cms://idfile='.$v1['idfile'];
									$filelist_output_row_array['file'] = '<a href="cms://idfile='.$v1['idfile'].'">cms://idfile='.$v1['idfile'].'</a>';
									$filelist_output_row_array['filefmtitle'] = $v1['titel'];
									$filelist_output_row_array['filefmdesc'] = $v1['description'];
									$filelist_output_row_array['filecreated'] = $v1['created'];
									$filelist_output_row_array['filemodified'] = $v1['lastmodified'];
			
									// filesize
						      if ($v1['filesize'] > 1048576) $v1['filesize'] = sprintf( "%01.".$modv['val_fsplaces']."f", $v1['filesize']/1048576).' '.$modv['str_megabyte'];
						     		else $v1['filesize'] = ($v1['filesize'] > 1024) ? sprintf( "%01.".$modv['val_fsplaces']."f", $v1['filesize']/1024).' '.$modv['str_kilobyte']: $v1['filesize'].' '.$modv['str_byte'];
									$filelist_output_row_array['filesize'] = $v1['filesize'];

									//thumburl	
						  		$mod['thumbfile'] = substr ($v1['filename'], 0, -(strlen( $mod['thumbfile_ext'] ))-1 ).$cfg_client["thumbext"].'.'.$mod["thumbfile_ext"];
			
									$filelist_output_row_array['imagethumb'] = '<img src="'.$cfg_client['upl_htmlpath'].	$filelist_dirs[$k].$mod['thumbfile'].'" height="'.$v1['pictthumbheight'].'" width="'.$v1['pictthumbwidth'].'"/>'; 		
									$filelist_output_row_array['imagethumburl'] = $cfg_client['upl_htmlpath'].$filelist_dirs[$k].$mod['thumbfile']; 		
								
									$filelist_output_row_array['imagewidth'] = $v1['pictwidth'];
									$filelist_output_row_array['imageheight'] = $v1['pictheight'];
									$filelist_output_row_array['imagethumbwidth'] = $v1['pictthumbwidth'];
									$filelist_output_row_array['imagethumbheight'] = $v1['pictthumbheight'];
									$filelist_output_row_array['imagethumbhalfwidth'] = round($v1['pictthumbwidth']/2);
									$filelist_output_row_array['imagethumbhalfheight'] = round($v1['pictthumbheight']/2);
									$filelist_output_row_array['iddirectory'] = $v1['iddirectory'];
		
									$filelist_output_row_array['filetypeicon'] = '<img src="'.$modconffiletypeiconpath.$filelist_filetypes[$v1['idfiletype']]['filetypepict'].'" alt=""/>';
									$filelist_output_row_array['filetypeiconurl'] = $modconffiletypeiconpath.$filelist_filetypes[$v1['idfiletype']]['filetypepict'];
			
									$filelist_output_row_array['filetype'] = $filelist_filetypes[$v1['idfiletype']]['filetype'];
									$filelist_output_row_array['filetypeiconname'] = $filelist_filetypes[$v1['idfiletype']]['filetypepict'];
									$filelist_output_row_array['filetypedesc'] = $filelist_filetypes[$v1['idfiletype']]['filetypedesc'];
									$filelist_output_row_array['filemimetype'] = $filelist_filetypes[$v1['idfiletype']]['filemimetype'];
	
									foreach($filelist_output_row_array AS $k2 => $v2){
									
											// global if-statement
											if(strpos($filelist_output_row,'{if_'.$k2.'}')!==false)
												if (empty($v2)) {
												  $filelist_output_row = preg_replace('#\{if_'.$k2.'\}(.*)\{/if_'.$k2.'\}#sU','',$filelist_output_row);
												} else {
												  $filelist_output_row = str_replace(array('{if_'.$k2.'}','{/if_'.$k2.'}'), array('',''), $filelist_output_row);
												}
													
											// global if-not-statement
											if(strpos($filelist_output_row,'{if_not_'.$k2.'}')!==false)
												if (empty($v2)) {
											  	$filelist_output_row = str_replace(array('{if_not_'.$k2.'}','{/if_not_'.$k2.'}'), array('',''), $filelist_output_row);
												} else {
												 	$filelist_output_row = preg_replace('#\{if_not_'.$k2.'\}(.*)\{/if_not_'.$k2.'\}#sU','',$filelist_output_row);
												}
									
											// global if-statement
											if(strpos($filelist_output_row,'{if_'.$k2.'=')!==false) {
												preg_match_all('/\{if_'.$k2.'=(.*?)\}/',$filelist_output_row,$modv['temp_results']);
												foreach ($modv['temp_results'][0] as $ek => $ev) {
													if ($v2!=$modv['temp_results'][1][$ek]) {
													  $filelist_output_row = preg_replace('#\{if_'.$k2.'='.$modv['temp_results'][1][$ek].'\}(.*)\{/if_'.$k2.'='.$modv['temp_results'][1][$ek].'\}#sU','',$filelist_output_row);
													} else {
													  $filelist_output_row = str_replace(array('{if_'.$k2.'='.$modv['temp_results'][1][$ek].'}','{/if_'.$k2.'='.$modv['temp_results'][1][$ek].'}'), array('',''), $filelist_output_row);
													}
												}
											}		
									
											// global if-not-statement
											if(strpos($filelist_output_row,'{if_not_'.$k2.'=')!==false) {
												preg_match_all('/\{if_not_'.$k2.'=(.*?)\}/',$filelist_output_row,$modv['temp_results']);
												foreach ($modv['temp_results'][0] as $ek => $ev) {
													if ($v2!=$modv['temp_results'][1][$ek]) {
													  $filelist_output_row = str_replace(array('{if_not_'.$k2.'='.$modv['temp_results'][1][$ek].'}','{/if_not_'.$k2.'='.$modv['temp_results'][1][$ek].'}'), array('',''), $filelist_output_row);
													} else {
													  $filelist_output_row = preg_replace('#\{if_not_'.$k2.'='.$modv['temp_results'][1][$ek].'\}(.*)\{/if_not_'.$k2.'='.$modv['temp_results'][1][$ek].'\}#sU','',$filelist_output_row);
													}
												}
											}
									
								    	$filelist_output_row = str_replace('{'.$k2.'}', $v2, $filelist_output_row);										
								
									}
									
									$filelist_output.=$filelist_output_row;
									if ($modconftplbetweenrowcounter>0)
										if ($modconftplbetweenrowcounterreset==1) {
											if ((($file_count)%$modconftplbetweenrowcounter)==0)
												$filelist_output.=$tpl_between_row;
										} else {
											if ((($all_file_count)%$modconftplbetweenrowcounter)==0)
												$filelist_output.=$tpl_between_row;										
										}
								}
							}
					}

				$filelist_output = str_replace('{file_count}',$file_count,$filelist_output);

				$file_count=0;
				// tree mode?		
				if ($dir_count>(($modconftreemode=='false')?1:0) || $modconfdirtplon1stlevel!='false'){
					if($modconftreemode!="false") {
						if (substr_count($filelist_dirs[$k],'/')-$filelist_dir_level_mem!=0 && $filelist_dir_level_mem!=0) 
							$filelist_output.=$tpl_dir_end;
						else 
							$tpl_dir_end_collected.=$tpl_dir_end;
					} else
						$filelist_output.=$tpl_dir_end;
					
					$filelist_dir_level_mem=substr_count($filelist_dirs[$k],'/');
				}
				unset($mod['thumbfile_ext'],$mod['thumbfile']);
				}
			}
			$filelist_output.=$tpl_dir_end_collected;
			$filelist_output.=$tpl_foot;
	
		}

		return $filelist_output;
	}
}
// ************************************************************************************************
// TXT2HTMLTable Function 1.1.0
// by Alexander M. Korn (amk@gmx.info)
// License: GPL

if(! function_exists(txt2htmltable)){
	function txt2htmltable($headline='',$head='',$foot='',$data='',$separator='|') {
	$data=str_replace('<br />','',$data);
	$data=trim($data);
	if (empty($data))
		return;

	// init
	global $modv;


	$options=Array();
	$tpl=Array();
	
	$options['rowclass0']=$modv['MOD_VALUE_10030'];
	$options['rowclass1']=$modv['MOD_VALUE_10031'];
	$options['rowstyle0']=stripslashes($modv['MOD_VALUE_10032']);
	$options['rowstyle1']=stripslashes($modv['MOD_VALUE_10033']);
	$options['colstyle']=stripslashes($modv['MOD_VALUE_10034']);
	if (!empty($options['colstyle'])) {
		$options['colstyles']=Array();
		$options['colstyles']=explode('|',$options['colstyle']);
	}

	$options['tableattributes']=stripslashes($modv['MOD_VALUE_10060']);
	$options['separator']=$modv['MOD_VALUE_10050'];
	$options['tablefoot']=$modv['MOD_VALUE_100402'];
	$options['charreplacementFROM']=$modv['MOD_VALUE_10070'];
	$options['charreplacementTO']=$modv['MOD_VALUE_10071'];

	$tablehead=Array();
	$tablefoot=Array();
	$tabledata=Array();
	$tablerows=Array();
	$rowclasses=Array();
	$chreplfrom=Array();
	$chreplto=Array();	
	
	$chreplfrom=explode(",",$options['charreplacementFROM']);
	$chreplto=explode(",",$options['charreplacementTO']);

	// get some mod-vars	
	if (!empty($options['separator']))
		$separator=trim($options['separator']);

	if (!empty($options['rowclass0']))
		$rowclass=' class="'.$options['rowclass0'].'"';
	else
		$rowclass='';
	
	$rowclasses[]=$rowclass." ".$options['rowstyle0'];

	if (!empty($options['rowclass1']))
		$rowclass=' class="'.$options['rowclass1'].'"';	
	else
		$rowclass='';	

	$rowclasses[]=$rowclass." ".$options['rowstyle1'];

	// split table head string
	if (!empty($head))
		$tablehead=explode($separator, $head);

	// split table head string
	if (!empty($foot))
		$tablefoot=explode($separator, $foot);
	elseif($options['tablefoot']=='head')
		$tablefoot=$tablehead;

		
	// split table data text
	$tablerows=@preg_split("/[\r\n]+/", $data);
	foreach ($tablerows as $v)
		$tabledata[]=explode($separator, $v);

	// calc max column number from head, foot & data inputs
	$maxcols=count($tablehead);

	if (count($tablefoot)>$maxcols)
		$maxcols=count($tablefoot);
	
	for($i=0;$i<count($tabledata);$i++) 
		if (count($tabledata[$i])>$maxcols)
			$maxcols=count($tabledata[$i]);

	// start table
	$table ="\n";
	$table.='<table '.$options['tableattributes'].' class="sortable" id="sortabletable'.$modv['repeat_id'].'">'."\n";
	
	// create table headline if given
	if (!empty($headline))
		$table.='	<caption>'.$headline.'</caption>'."\n";

	// create head if given
	if (!empty($head)) {
		$table.='	<thead>'."\n";
		$table.='		<tr>'."\n";
		for ($i=0;$i<$maxcols;$i++) {
			// check if nosort-mark exists or empty and add nosort-class - remove the marks
			if (substr($tablehead[$i],0,1)=="[" && substr($tablehead[$i],-1,1)=="]") {
				$nosort=' class="notsortablecol"';		
				$tablehead[$i]=substr($tablehead[$i],1,strlen($tablehead[$i])-2);
				// if foot like head remove the marks within the foot too
				if($options['tablefoot']=='head')
					$tablefoot[$i]=substr($tablefoot[$i],1,strlen($tablefoot[$i])-2);
			}	elseif (empty($tablehead[$i]))
					$nosort=' class="notsortablecol"';
			else
				$nosort='';
			
			// char replacement
			if (!empty($chreplfrom) && !empty($chreplto) && count($chreplto)==count($chreplfrom))
				$cellval=str_replace($chreplfrom,$chreplto,$tablehead[$i]);
			else
				$cellval=$tablehead[$i];

			if (!empty($options['colstyles'][$i]) && strpos($options['colstyles'][$i],'class=')!==false && !empty($nosort)){
				preg_match_all('/class\=\"(.*?)\"/',$options['colstyles'][$i],$results);
				$options['colstyles'][$i]=str_replace($results[0][0],'',$options['colstyles'][$i]);
				$nosort=str_replace('class="','class="'.$results[1][0].' ',$nosort);		
			}

			$table.='			<th'.$nosort.' '.$options['colstyles'][$i].'>'.(!empty($cellval)?$cellval:'&nbsp;').'</th>'."\n";
		}
		$table.='		</tr>'."\n";
		$table.='	</thead>'."\n";
	}

	// create foot if given
	if (!empty($foot) || $options['tablefoot']=='head') {
		$table.='	<tfoot>'."\n";
		$table.='		<tr>'."\n";
		for ($i=0;$i<$maxcols;$i++) {
		
			// char replacement
			if (!empty($chreplfrom) && !empty($chreplto) && count($chreplto)==count($chreplfrom))
				$cellval=str_replace($chreplfrom,$chreplto,$tablefoot[$i]);
			else
				$cellval=$tablefoot[$i];
				
			$table.='			<td '.$options['colstyles'][$i].'>'.(!empty($cellval)?$cellval:'&nbsp;').'</td>'."\n";
		}
		$table.='		</tr>'."\n";
		$table.='	</tfoot>'."\n";
	}	
	
	// create body
	$table.='	<tbody>'."\n";
	foreach ($tabledata as $k => $v) {
		$table.='		<tr'.$rowclasses[(($k%2)?0:1)].'>'."\n";
		for ($i=0;$i<$maxcols;$i++) {
			// check if nosort-mark exists or empty and add nosort-class - remove the marks
			if (substr($v[$i],0,1)=="[" && substr($v[$i],-1,1)=="]" && $k==0) {
				$nosort=' class="notsortablecol"';		
				$v[$i]=substr($v[$i],1,strlen($v[$i])-2);
			}	elseif (empty($v[$i]))
				$nosort=' class="notsortablecol"';
			else
				$nosort='';
				
			// char replacement
			if (!empty($chreplfrom) && !empty($chreplto) && count($chreplto)==count($chreplfrom))
				$cellval=str_replace($chreplfrom,$chreplto,$v[$i]);
			else
				$cellval=$v[$i];

			if (!empty($options['colstyles'][$i]) && strpos($options['colstyles'][$i],'class=')!==false && !empty($nosort)){
				preg_match_all('/class\=\"(.*?)\"/',$options['colstyles'][$i],$results);
				$options['colstyles'][$i]=str_replace($results[0][0],'',$options['colstyles'][$i]);
				$nosort=str_replace('class="','class="'.$results[1][0].' ',$nosort);		
			}
	
			$table.='			<td'.$nosort.' '.$options['colstyles'][$i].'>'.(!empty($cellval)?$cellval:'&nbsp;').'</td>'."\n";
		}
		$table.='		</tr>'."\n";
	}
	$table.='	</tbody>'."\n";

	// quit table
	$table.='</table>'."\n";
	
	return $table;

	}
}
// ************************************************************************************************
// TXT2HTMLList Function 1.0.0
// by Alexander M. Korn (amk@gmx.info)
// License: GPL

if(! function_exists(txt2htmllist)){
	function txt2htmllist($tpl1='',$tpl2='',$data='') {
		$data=str_replace('<br />','',$data);
		$data=trim($data);
		if (empty($data))
			return;

		// init
		global $modv;
		$list='';
		$listentries=Array();
		
		$options['charreplacementFROM']=$modv['MOD_VALUE_10070'];
		$options['charreplacementTO']=$modv['MOD_VALUE_10071'];

		$chreplfrom=explode(",",$options['charreplacementFROM']);
		$chreplto=explode(",",$options['charreplacementTO']);
	
		// split list data text
		$listentries=@preg_split("/[\r\n]+/", $data);

		// create
		foreach ($listentries as $v) {
			// char replacement
			if (!empty($chreplfrom) && !empty($chreplto) && count($chreplto)==count($chreplfrom))
				$val=str_replace($chreplfrom,$chreplto,$v);
			else
				$val=$v;
			$list.=str_replace('{content}',$val,$tpl2);
	
		}

		return str_replace('{entries}',$list,$tpl1);

	}
}


if(! function_exists(element_ifstatements)){
  /**
  * 
  *
  * @param		
  * @return		
  * @access		
  */
	function element_ifstatements($tpl='',$elarray,$elkey,$elvalue) {

		global $cfg_client;

		if (strpos($elkey,'image:')!==false)
			if (strpos($elvalue,$cfg_client['space'])!==false)
				echo $elvalue='';
		
			$_AS['temp']=array();

			if(strpos($tpl,'{if_')===false || empty($tpl))
				return $tpl;
			
			// global if-value-statement
			if(strpos($tpl,'{if_'.$elkey.'=')!==false) {
				preg_match_all('/\{if_'.$elkey.'=(.*?)\}/',$tpl,$_AS['temp']['temp_results']);
				foreach ($_AS['temp']['temp_results'][0] as $ek => $ev) {
					$_AS['temp']['compval']=$_AS['temp']['temp_results'][1][$ek];
					$_AS['temp']['compelement']=$_AS['temp']['temp_results'][1][$ek];
					if (array_key_exists(trim($_AS['temp']['compval'],'[]'),$elarray)) {
						$_AS['temp']['compval']=$elarray[trim($_AS['temp']['compval'],'[]')];
						$_AS['temp']['compelement']=str_replace(array('[',']'),array('\[','\]'),$_AS['temp']['temp_results'][1][$ek]);							
					}
						
					if ($elvalue!=$_AS['temp']['compval'] || (trim($elvalue)=='' && trim($_AS['temp']['compval'])=='')) {
					  $tpl = preg_replace('#\{if_'.$elkey.'='.$_AS['temp']['compelement'].'\}(.*)\{/if_'.$elkey.'='.$_AS['temp']['compelement'].'\}#sU','',$tpl);
					} else {
					  $tpl = str_replace(array('{if_'.$elkey.'='.$_AS['temp']['temp_results'][1][$ek].'}','{/if_'.$elkey.'='.$_AS['temp']['temp_results'][1][$ek].'}'), array('',''), $tpl);
					}
				}
			}		
	
			// global if-value-statement
			if(strpos($tpl,'{if_not_'.$elkey.'=')!==false) {
				preg_match_all('/\{if_not_'.$elkey.'=(.*?)\}/',$tpl,$_AS['temp']['temp_results']);
				foreach ($_AS['temp']['temp_results'][0] as $ek => $ev) {
					$_AS['temp']['compval']=$_AS['temp']['temp_results'][1][$ek];
					$_AS['temp']['compelement']=$_AS['temp']['temp_results'][1][$ek];
					if (array_key_exists(trim($_AS['temp']['compval'],'[]'),$elarray)) {
						$_AS['temp']['compval']=$elarray[trim($_AS['temp']['compval'],'[]')];
						$_AS['temp']['compelement']=str_replace(array('[',']'),array('\[','\]'),$_AS['temp']['temp_results'][1][$ek]);							
					}
			
					if ($elvalue!=$_AS['temp']['compval'] || (trim($elvalue)=='' && trim($_AS['temp']['compval'])=='')) {
					  $tpl = str_replace(array('{if_not_'.$elkey.'='.$_AS['temp']['temp_results'][1][$ek].'}','{/if_not_'.$elkey.'='.$_AS['temp']['temp_results'][1][$ek].'}'), array('',''), $tpl);
					} else {
					  $tpl = preg_replace('#\{if_not_'.$elkey.'='.$_AS['temp']['compelement'].'\}(.*)\{/if_not_'.$elkey.'='.$_AS['temp']['compelement'].'\}#sU','',$tpl);
					}
				}
			}

			// global if-value-statement
			if(strpos($tpl,'{if_'.$elkey.'>')!==false) {
				preg_match_all('/\{if_'.$elkey.'>(.*?)\}/',$tpl,$_AS['temp']['temp_results']);
				foreach ($_AS['temp']['temp_results'][0] as $ek => $ev) {
					$_AS['temp']['compval']=$_AS['temp']['temp_results'][1][$ek];
					$_AS['temp']['compelement']=$_AS['temp']['temp_results'][1][$ek];
				
					if (array_key_exists(trim($_AS['temp']['compval'],'[]'),$elarray)) {
						$_AS['temp']['compval']=$elarray[trim($_AS['temp']['compval'],'[]')];
						$_AS['temp']['compelement']=str_replace(array('[',']'),array('\[','\]'),$_AS['temp']['temp_results'][1][$ek]);				
						
					}				
					if ($elvalue<=$_AS['temp']['compval'] || (trim($elvalue)=='' && trim($_AS['temp']['compval'])=='')) {
					  $tpl = preg_replace('#\{if_'.$elkey.'>'.$_AS['temp']['compelement'].'\}(.*)\{/if_'.$elkey.'>'.$_AS['temp']['compelement'].'\}#sU','',$tpl);
					} else {
					  $tpl = str_replace(array('{if_'.$elkey.'>'.$_AS['temp']['temp_results'][1][$ek].'}','{/if_'.$elkey.'>'.$_AS['temp']['temp_results'][1][$ek].'}'), array('',''), $tpl);
					}
				}
			}		
	
			// global if-value-statement
			if(strpos($tpl,'{if_not_'.$elkey.'>')!==false) {
				preg_match_all('/\{if_not_'.$elkey.'>(.*?)\}/',$tpl,$_AS['temp']['temp_results']);
				foreach ($_AS['temp']['temp_results'][0] as $ek => $ev) {
					$_AS['temp']['compval']=$_AS['temp']['temp_results'][1][$ek];
					$_AS['temp']['compelement']=$_AS['temp']['temp_results'][1][$ek];
					if (array_key_exists(trim($_AS['temp']['compval'],'[]'),$elarray)) {
						$_AS['temp']['compval']=$elarray[trim($_AS['temp']['compval'],'[]')];
						$_AS['temp']['compelement']=str_replace(array('[',']'),array('\[','\]'),$_AS['temp']['temp_results'][1][$ek]);							
					}
					if ($elvalue<=$_AS['temp']['compval'] || (trim($elvalue)=='' && trim($_AS['temp']['compval'])=='')) {
					  $tpl = str_replace(array('{if_not_'.$elkey.'>'.$_AS['temp']['temp_results'][1][$ek].'}','{/if_not_'.$elkey.'>'.$_AS['temp']['temp_results'][1][$ek].'}'), array('',''), $tpl);
					} else {
					  $tpl = preg_replace('#\{if_not_'.$elkey.'>'.$_AS['temp']['compelement'].'\}(.*)\{/if_not_'.$elkey.'>'.$_AS['temp']['compelement'].'\}#sU','',$tpl);
					}
				}
			}		
		
			// global if-value-statement
			if(strpos($tpl,'{if_'.$elkey.'<')!==false) {
				preg_match_all('/\{if_'.$elkey.'<(.*?)\}/',$tpl,$_AS['temp']['temp_results']);
				foreach ($_AS['temp']['temp_results'][0] as $ek => $ev) {
					$_AS['temp']['compval']=$_AS['temp']['temp_results'][1][$ek];
					$_AS['temp']['compelement']=$_AS['temp']['temp_results'][1][$ek];
					if (array_key_exists(trim($_AS['temp']['compval'],'[]'),$elarray)) {
						$_AS['temp']['compval']=$elarray[trim($_AS['temp']['compval'],'[]')];
						$_AS['temp']['compelement']=str_replace(array('[',']'),array('\[','\]'),$_AS['temp']['temp_results'][1][$ek]);		
			
					}
					if ($elvalue>=$_AS['temp']['compval'] || (trim($elvalue)=='' && trim($_AS['temp']['compval'])=='')) {
					  $tpl = preg_replace('#\{if_'.$elkey.'<'.$_AS['temp']['compelement'].'\}(.*)\{/if_'.$elkey.'<'.$_AS['temp']['compelement'].'\}#sU','',$tpl);
					} else {
					  $tpl = str_replace(array('{if_'.$elkey.'<'.$_AS['temp']['temp_results'][1][$ek].'}','{/if_'.$elkey.'<'.$_AS['temp']['temp_results'][1][$ek].'}'), array('',''), $tpl);
					}
				}
			}		
	
			// global if-value-statement
			if(strpos($tpl,'{if_not_'.$elkey.'<')!==false) {
				preg_match_all('/\{if_not_'.$elkey.'<(.*?)\}/',$tpl,$_AS['temp']['temp_results']);
				foreach ($_AS['temp']['temp_results'][0] as $ek => $ev) {
					$_AS['temp']['compval']=$_AS['temp']['temp_results'][1][$ek];
					$_AS['temp']['compelement']=$_AS['temp']['temp_results'][1][$ek];
					if (array_key_exists(trim($_AS['temp']['compval'],'[]'),$elarray)) {
						$_AS['temp']['compval']=$elarray[trim($_AS['temp']['compval'],'[]')];
						$_AS['temp']['compelement']=str_replace(array('[',']'),array('\[','\]'),$_AS['temp']['temp_results'][1][$ek]);							
					}
					if ($elvalue>=$_AS['temp']['compval'] || (trim($elvalue)=='' && trim($_AS['temp']['compval'])=='')) {
					  $tpl = str_replace(array('{if_not_'.$elkey.'<'.$_AS['temp']['temp_results'][1][$ek].'}','{/if_not_'.$elkey.'<'.$_AS['temp']['temp_results'][1][$ek].'}'), array('',''), $tpl);
					} else {
					  $tpl = preg_replace('#\{if_not_'.$elkey.'<'.$_AS['temp']['compelement'].'\}(.*)\{/if_not_'.$elkey.'<'.$_AS['temp']['compelement'].'\}#sU','',$tpl);
					}
				}
			}		

			// global if-statement
			if(strpos($tpl,'{if_'.$elkey.'}')!==false)
				if (empty($elvalue))
				  $tpl = preg_replace('#\{if_'.$elkey.'\}(.*)\{/if_'.$elkey.'\}#sU','',$tpl);
				else
				  $tpl = str_replace(array('{if_'.$elkey.'}','{/if_'.$elkey.'}'), array('',''), $tpl);

			// global if-not-statement
			if(strpos($tpl,'{if_not_'.$elkey.'}')!==false)
				if (empty($elvalue))
			  	$tpl = str_replace(array('{if_not_'.$elkey.'}','{/if_not_'.$elkey.'}'), array('',''), $tpl);
				else
				 	$tpl = preg_replace('#\{if_not_'.$elkey.'\}(.*)\{/if_not_'.$elkey.'\}#sU','',$tpl);
				 	
			return $tpl;
	
	
	}
}

// ************************************************************************************************
// Seite neu laden
if (!function_exists('clear_cache')) {
	function clear_cache () {
		global $mod, $cms_mod, $con_side, $cfg_cms,$db, $cms_db, $idcatside;

		// Seitenkopien suchen
		$sql = "SELECT idcatside
                 	FROM ".$cms_db['side_lang']." A
                         LEFT JOIN ".$cms_db['cat_side']." B USING(idside)
                         WHERE A.idsidelang='".$con_side[$idcatside]['idsidelang']."'";
		$db->query($sql);
		while ($db->next_record()) $list[] = $db->f('idcatside');

		// Status der Seite auf geaendert stellen
		change_code_status($list, '1', 'idcatside');
	}
}

// Version 0.0.1
if(! function_exists(flex2_get_edit_string)){
    function flex2_get_edit_string($type_name, $id)
    {
        switch($type_name)
        {
         case 'table_caption':
          $out = '1-'.$id.',';
          break;
         case 'table_head':
          $out = '1-'.$id.',';
          break;
         case 'table_foot':
          $out = '1-'.$id.',';
          break;
         case 'text':
          $out = '1-'.$id.',';
          break;
         case 'table_data':
          $out = '3-'.$id.',';
          break;
         case 'list_data':
          $out = '3-'.$id.',';
          break;
         case 'textarea':
          $out = '3-'.$id.',';
          break;
         case 'wysiwyg':
          $out = '2-'.$id.',';
          break;
         case 'wysiwyg2':
          $out = '13-'.$id.',';
          break;
         case 'editimage':
          $out = '4-'.$id.',5-'.$id.',';
          break;
         case 'editlink':
          $out = '6-'.$id.',7-'.$id.',8-'.$id.',';
          break;
         case 'filelist':
          $out = '10-'.$id.',11-'.$id.',12-'.$id.',';
          break;  
         case 'editfile':
          $out = '10-'.$id.',11-'.$id.',12-'.$id.',';
          break;  
         case 'sourcecode':
          $out = '9-'.$id.',';
          break;  
         case 'select':
          $out = '14-'.$id.',';
          break;  
         case 'hidden':
          $out = '15-'.$id.',';
          break;  
         case 'checkbox':
          $out = '16-'.$id.',20-'.$id.',';
          break;  
         case 'radio':
          $out = '17-'.$id.',';
          break;  
         case 'date':
          $out = '18-'.$id.',';
          break;  
        }
        
        return $out;
    }
}

if(! function_exists(flex2_get_typegroup)){
    function flex2_get_typegroup($type_name, $id)
    {
        switch($type_name)
        {
         case 'text':
          $out = 'text['.$id.']'.',';
          break;
         case 'table_caption':
          $out = 'text['.$id.']'.',';
          break;
         case 'table_head':
          $out = 'text['.$id.']'.',';
          break;
         case 'table_foot':
          $out = 'text['.$id.']'.',';
          break;
         case 'table_data':
          $out = 'textarea['.$id.']'.',';
          break;
         case 'list_data':
          $out = 'textarea['.$id.']'.',';
          break;
         case 'textarea':
          $out = 'textarea['.$id.']'.',';
          break;
         case 'wysiwyg':
          $out = 'wysiwyg['.$id.']'.',';
          break;
         case 'wysiwyg2':
          $out = 'wysiwyg2['.$id.']'.',';
          break;
         case 'editimage':
          $out = 'image['.$id.']'.',';
          break;
         case 'editlink':
          $out = 'link['.$id.']'.',';
          break;
         case 'filelist':
          $out = 'file['.$id.']'.',';
          break;           
         case 'editfile':
          $out = 'file['.$id.']'.',';
          break;  
         case 'sourcecode':
          $out = 'sourcecode['.$id.']'.',';
          break;  
         case 'select':
          $out = 'select['.$id.']'.',';
          break;  
         case 'hidden':
          $out = 'hidden['.$id.']'.',';
          break;  
         case 'checkbox':
          $out = 'checkbox['.$id.']'.',';
          break;  
         case 'radio':
          $out = 'radio['.$id.']'.',';
          break;  
         case 'date':
          $out = 'date['.$id.']'.',';
          break;  
        }
        
        return $out;
    }
}


if(! function_exists(flex2_get_val)){
    function flex2_get_val($value_name)
    {
        global $idcatside, $cms_db, $db, $con_side, $cms_mod;
        
        $sql = "SELECT
           *
          FROM
           ".$cms_db['content'] ."
          WHERE
           idsidelang='".$con_side[$idcatside]['idsidelang']."'
           AND container='".$cms_mod['container']['id']."'
           AND number='".$cms_mod['modul']['id']."' 
           AND idtype='$value_name'";
        $db->query($sql);
        $db->next_record();
        
        return $db->f('value');
    }
}

if(! function_exists(flex2_set_val)){
    function flex2_set_val($value_name, $value, $offset)
    {
        global $idcatside, $cms_db, $db, $con_side, $cms_mod;
        //Schauen, ob es den Wert schon gibt
        if ($offset==0) {
        	$offset=$cms_mod['modul']['id'];
        }
        $sql = "SELECT
           *
          FROM
           ".$cms_db['content'] ."
          WHERE
           idsidelang='".$con_side[$idcatside]['idsidelang']."'
           AND container='".$cms_mod['container']['id']."'
           AND number='".$offset."' 
           AND idtype='$value_name'";
        $db->query($sql);
        
        //Es gibt den Wert schon -> wert aktuallisieren
        if ($db->next_record()){
         $sql = "UPDATE
            ". $cms_db['content'] ."
           SET
            value='$value'
           WHERE
            idsidelang='".$con_side[$idcatside]['idsidelang']."'
            AND container='".$cms_mod['container']['id']."'
            AND number='".$offset."' 
            AND idtype='$value_name'";
        }
        //Es gibt den Wert noch nicht, neu in tabelle einfuegen
        else{
         $sql = "INSERT INTO
            ". $cms_db['content'] ."
            (idsidelang, container, number, idtype, value)
           VALUES
            ('".$con_side[$idcatside]['idsidelang']."',
             '".$cms_mod['container']['id']."',
             '".$offset."',
             '$value_name', '$value')";
        }
        
        $db->query($sql);
        
        //Letzte Aenderung Datum aendern!
        $sql = "UPDATE ". $cms_db['side_lang']. " SET lastmodified='".time()."' WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' ";
        			$db->query($sql);
        
        //Cache loeschen
        //Seite fuers frontend neu generieren, da sich ein Wert geaendert hat
        change_code_status($idcatside, 1, 'idcatside');
        
        return "<br />Set_Val:".$value_name." -> ".$value." at ".$offset."</h3>"; 
        
    }
}

if (!function_exists('flex2_popup_menu')) {
	function flex2_popup_menu() {
		global $modtemp, $cms_mod, $con_side, $cfg_cms, $idcatside, $perm;
		if(! is_object($pop_menu)){
			include_once($cfg_cms['cms_path'].'inc/class.popupmenubuilder_js.php');
			$pop_menu = new popupmenubuilder_js();
			$pop_menu->set_image($cfg_cms['cms_html_path'].'tpl/'.$cfg_cms['skin'].'/img/but_addelement.gif', 16, 16);
		}

		//generate menu
		$pop_menu->add_title('{entry_txt}');

		$modtemp['el_nr'] = $cms_mod['value']['100'];

		for ($modtemp['i'] = 1; $modtemp['i'] <= $modtemp['el_nr']; $modtemp['i']++) {
			$modtemp['el_title'] = $cms_mod['value'][round($modtemp['i']*10+100)];
			$modtemp['el_tpl'] = $cms_mod['value'][round($modtemp['i']*10+101)];
			$modtemp['el_unique_id'] = $cms_mod['value'][round($modtemp['i']*10+102)];
			$modtemp['el_activ'] = $cms_mod['value'][round($modtemp['i']*10+103)];
			if (($modtemp['el_activ'] == 'active') && (_type_check_editable(true,$cms_mod['value'][round($modtemp['i']*10+104)],'edit')) ) {
				// index.php?idcatside=17&view=edit&action=new&entry=3&content=30.new.3-1,1-1
				$pop_menu->add_entry(
					$modtemp['el_title'],
					$con_side[$idcatside]['link'] .'&newflex2_'.$cms_mod['container']['id'].'_'
					.$cms_mod['modul']['id'].'=insert&entry={entry}&flex2tpl='.$modtemp['el_unique_id'], 
					'_self', $modtemp['el_title']);
			}
		}

		return $pop_menu->get_menu_and_flush();
}
}

if (!function_exists('flex2_edit_menu')) {
	function flex2_edit_menu($title, $full_edit_link, $first, $last, $group) {
		global $cms_mod,$con_side,$idcatside,$perm,$modv;

		$returnediticon=false;
		
    $flex2_title = $title;
    $flex2_type_config = "advanced";

		if (_type_check_editable(true,$group,'edit') || (!_type_check_editable(true,$group,'edit') && $modv['MOD_VALUE_1001']=='true')){
			$flex2_full_edit_link = $full_edit_link;
			$returnediticon=true;
		} else
			$flex2_full_edit_link = '';
				
    $flex2_new = false;
    
    if (_type_check_editable(true,$group,'edit') || (!_type_check_editable(true,$group,'edit') && $modv['MOD_VALUE_1002']=='true')){
    	$flex2_delete = true;
    	$returnediticon=true;
    }
   	if (_type_check_editable(true,$group,'edit') || (!_type_check_editable(true,$group,'edit') && $modv['MOD_VALUE_1003']=='true')){
   	 	$flex2_up = !$first;
    	$flex2_down = !$last;
    	$returnediticon=true;
    } 

		// preserve include file backward compatibility (ContentFlex module output code versions < 1.3.5)
		if (empty($modv['MOD_VALUE_1001']) && empty($modv['MOD_VALUE_1002']) && empty($modv['MOD_VALUE_1003'])) {
      $flex2_new = false;
      $flex2_delete = true;
      $flex2_up = !$first;
      $flex2_down = !$last;
      $flex2_ids = array(NULL);
			$returnediticon=true;
		}  
    
    if ($returnediticon===true){
	    $flex2_ids = array(NULL);
			$flex2_infos = array(
	                    'container_number' => $cms_mod['container']['id'],
	                    'pre_compiled' => $flex2_full_edit_link,
	                    'mod_repeat_id' => $cms_mod['modul']['id'],
	                    'title' => $flex2_title,
	                    'base_url' => $con_side[$idcatside]['link'],
	                    'mode' => $flex2_type_config
					);
	
			//menu erstellen
			$layer_menu = _type_get_layer_menu($flex2_ids, $flex2_infos, $flex2_delete, $flex2_new, $flex2_up, $flex2_down);
			return $layer_menu;
		} else
			return '';
	}
}


// **** END FUNCTION **************************************************************


// **** START PARAMETER **************************************************************

// 
// Element hinzufuegene ausfuehren
if (${'newflex2_'.$cms_mod['container']['id'].'_'.$cms_mod['modul']['id']} == 'insert') {
	// Modul verschieben
	if ($mod['test'] == "true") { echo "<h3>New Element: ".$flex2tpl." at ".$entry."</h3>"; }
	if (is_numeric($entry) ) {
		$sql = "UPDATE $cms_db[content] SET number=number+1 WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='".$cms_mod['container']['id']."' AND number>'$entry'";
		$db->query($sql);
		$sql = "UPDATE $cms_db[content_external] SET number=number+1 WHERE idsidelang='".$con_side[$idcatside]['idsidelang']."' AND container='".$cms_mod['container']['id']."' AND number>'$entry'";
		$db->query($sql);
	}
	if (is_numeric($flex2tpl)) {
		echo flex2_set_val("flex2",$flex2tpl,$entry+1);
	}

	// Cache loeschen
	clear_cache();
	
	// Seite neu laden
	$entry++;
	sf_header_redirect($con_side[$idcatside]['link'] ."&entry=$entry#flex2expander");
}


// **** END PARAMETER **************************************************************


// neues Template?
if(isset($action)) {
	if ($action == "save") {
		foreach ($con_content as $modtemp['value']) {
			if (substr_count($modtemp['value'],"new") >= 1) {
				$modtemp['con_config'] = explode ('.', $modtemp['value']);
				if ($mod['test'] == "true") { echo "<br />value:";	print_r($modtemp['value']); }
				if ($mod['test'] == "true") { echo "<br />entry:".$entry; }
				if (($modtemp['con_config'][0]==$cms_mod['container']['id']) && (($entry+1)==$cms_mod['modul']['id'])) {
					$modtemp['new_tpl_nr']=substr($modtemp['con_config'][1],3);
					flex2_set_val("flex2",$modtemp['new_tpl_nr'],0);
					if ($mod['test'] == "true") { echo "<br />new tpl Nr!".$modtemp['new_tpl_nr']; }
				}
			}
		}
	}
}


// ****  Template **************************************************************

//$modv['tpl_title'] = "no unique key found";
$modv['tpl_title'] = "Kein Template";
$modv['tpl_activ'] = "active";
$modv['tpl_group'] = 2;
$modv['tpl_outer'] = '';
$modtemp['tpl_nr'] = flex2_get_val("flex2");
if ($mod['test'] == "true") echo "<br />tpl Nr:".$modtemp['tpl_nr'];
if ($modtemp['tpl_nr'] != "") {
	if ($mod['test'] == "true") echo "<br />Search unique id";
	for($i=112; array_key_exists($i, $cms_mod['value']);$i=$i+10 ){
		if ($mod['test'] == "true") echo $i."->".$cms_mod['value'][$i].",";
		if($modtemp['tpl_nr'] == $cms_mod['value'][$i]) {
			$modtemp['tpl_key'] = $i;
		}
	}
	if($modtemp['tpl_key'] != 0){
		$modv['tpl_inner'] = $cms_mod['value'][$modtemp['tpl_key']-1 ];
		$modv['tpl_title'] = $cms_mod['value'][$modtemp['tpl_key']-2 ];
		$modv['tpl_activ'] = $cms_mod['value'][$modtemp['tpl_key']+1 ];
		$modv['tpl_group'] = $cms_mod['value'][$modtemp['tpl_key']+2 ];
		$modv['tpl_outer'] = $cms_mod['value'][$modtemp['tpl_key']+3 ];
	} else {
		$modv['tpl_title'] = "no unique key found";
		if ($mod['test'] == "true") echo "<br />no unique key found for ".$modtemp['tpl_nr']."; tpl_key ".$modtemp['tpl_key'];
	} 
	
} else {
	if ($cms_mod['modul']['id'] == '1') {
		$modv['tpl_inner'] = '';
	} 
	//$modv['tpl_title'] = "no unique key found";
	if ($mod['test'] == "true") echo "<br />no unique key found";
}

// **** Allgemeine Tags definieren **************************************************************

$elements1['author'] = $con_side[$idcatside]['meta_author'];
$elements1['date']   = date('d.m.Y',$con_side[$idcatside]['lastmodified']);
$elements1['created_date'] = date('d.m.Y',$con_side[$idcatside]['created']);
if (strpos($modv['tpl_inner'],'{username}')!==FALSE || strpos($modv['tpl_inner'],'{name}')!==FALSE || strpos($modv['tpl_inner'],'{surname}')!==FALSE || strpos($modv['tpl_inner'],'{email}')!==FALSE) {
	$sql = 'SELECT username, name, surname, email FROM '.$cms_db['users'].' WHERE user_id='.$con_side[$idcatside]['author'];
	$db->query($sql);
	$db->next_record();
	$elements1['username'] = $db->f('username');
	$elements1['name'] = $db->f('name');
	$elements1['surname'] = $db->f('surname');
	$elements1['email'] = $db->f('email');
}


// **** spezial Tags definieren und ersetzen **************************************************************

 
$spezelements['popup'] = "<a href='".$modv['MOD_VALUE_10001']."' onclick='".$modv['MOD_VALUE_10000']."'>".$modv['MOD_VALUE_10002']."</a>";

foreach($spezelements AS $k => $v){
    $modv['tpl_inner'] = str_replace('{'.$k.'}', $v, $modv['tpl_inner']);
}


if($cms_mod['modul']['id'] == $entry){
	if ($mod['test'] == "true") echo "<br />Anker ausgegeben";
	$modv['tpl_inner'] = '<a name="flex2expander" class="flex2expander"><!-- expander //--></a>'.$modv['tpl_inner'];
}

// get all elements in if/if_not-statements to make them editable (if the single element isn't placed in the template)
unset($modv['tpl_addon_inner']);

if (strpos($modv['tpl_inner'],'{if_')!==false) {
	unset($modv['statement_elements']);
	preg_match_all('#\{if_(.*)\}#sU',$modv['tpl_inner'],$modv['statement_elements']);
	foreach ($modv['statement_elements'][1] as $v){
		if (strpos($v,'=')!==false)
			$v=substr($v,0,strpos($v,'='));
		if (strpos($v,'<')!==false)
			$v=substr($v,0,strpos($v,'<'));
		if (strpos($v,'>')!==false)
			$v=substr($v,0,strpos($v,'>'));

	 if (strpos($modv['tpl_inner'],'{'.str_replace('not_','',$v).'}')===false &&
	     strpos($modv['tpl_addon_inner'],'{'.str_replace('not_','',$v).'}')===false)
		$modv['tpl_addon_inner'].='{'.str_replace('not_','',$v).'}';
	}
}


// **** Editbutton einfuegen, wenn ein File-Element Image-Element vorhanden ist.
// adding statements elements temporarly
$modv['tpl_inner_temp'] = $modv['tpl_inner'].$modv['tpl_addon_inner'];
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):1\}/" ,"{editfile:1}{file\\1:1}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):2\}/" ,"{editfile:2}{file\\1:2}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):3\}/" ,"{editfile:3}{file\\1:3}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):4\}/" ,"{editfile:4}{file\\1:4}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):5\}/" ,"{editfile:5}{file\\1:5}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):6\}/" ,"{editfile:6}{file\\1:6}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):7\}/" ,"{editfile:7}{file\\1:7}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):8\}/" ,"{editfile:8}{file\\1:8}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):9\}/" ,"{editfile:9}{file\\1:9}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{file(\S{0,12}):10\}/" ,"{editfile:10}{file\\1:10}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):1\}/","{editimage:1}{image\\1:1}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):2\}/","{editimage:2}{image\\1:2}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):3\}/","{editimage:3}{image\\1:3}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):4\}/","{editimage:4}{image\\1:4}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):5\}/","{editimage:5}{image\\1:5}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):6\}/","{editimage:6}{image\\1:6}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):7\}/","{editimage:7}{image\\1:7}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):8\}/","{editimage:8}{image\\1:8}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):9\}/","{editimage:9}{image\\1:9}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{image(\S{0,15}):10\}/","{editimage:10}{image\\1:10}",$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):1\}/" ,"{editlink:1}{link\\1:1}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):2\}/" ,"{editlink:2}{link\\1:2}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):3\}/" ,"{editlink:3}{link\\1:3}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):4\}/" ,"{editlink:4}{link\\1:4}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):5\}/" ,"{editlink:5}{link\\1:5}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):6\}/" ,"{editlink:6}{link\\1:6}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):7\}/" ,"{editlink:7}{link\\1:7}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):8\}/" ,"{editlink:8}{link\\1:8}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):9\}/" ,"{editlink:9}{link\\1:9}"  ,$modv['tpl_inner_temp'],1);
$modv['tpl_inner_temp'] = preg_replace("/\{link(\S{0,11}):10\}/" ,"{editlink:10}{link\\1:10}"  ,$modv['tpl_inner_temp'],1);

$modv['tpl_inner']=str_replace($modv['tpl_addon_inner'],'',$modv['tpl_inner_temp']);

// fals nicht vorhanden editbutton ansetzen
if (substr_count($modv['tpl_inner'],"{editbutton}")==0 && substr_count($modv['tpl_inner'],"{edit}")==0 && substr_count($modv['tpl_inner'],"{insert}")==0 ) {
    if ($modv['MOD_VALUE_60'] == 'true') {
        $modv['tpl_inner'] = "{editbutton}" . $modv['tpl_inner'];    
    } else {
        $modv['tpl_inner'] .= "{editbutton}";    
    }
}

if ($mod['test'] == "true") echo "<br />tpl:".$modv['tpl_inner'];

// **** speicher ob es sich um das erste oder um das letzt Modul handelt
$modv['is_first']  = ($cms_mod['modul']['id'] == '1') ? true:false;
$modv['is_last']   = (bool) $cms_mod['modul']['lastentry'];
// ID die wievielte Wiederholung des Modules es ist
$modv['repeat_id'] = $cms_mod['modul']['id'];
// Nummer des 1 Eintrags der auf diese Seite dargestellt werden soll (wird nicht verwendet ???)
$modv['page'] = (is_numeric($_REQUEST['page'])) ? 1:$_REQUEST['page'];


// ****
// Fuer alle Elemente: preg_match_all('/\{([^\}]+)\}/im', $modv['tpl_inner'], $test);
// Fuer alle Elemente mit : und einer Zahl: preg_match_all('/\{(([^\}]+):\d)\}/im', $modv['tpl_inner'], $test);

preg_match_all('/\{(([^\}]+):\d+)\}/im', $modv['tpl_inner'].$modv['tpl_addon_inner'], $modtemp['tags']);

// looking for {table} and add to modtemp-pseudo-element
if (strpos($modv['tpl_inner'],"{table}")!==false) {
  
  if ($modv['MOD_VALUE_100400']=='true')
  	$modtemp['tags'][1][]='table_caption:100';
  
  if ($modv['MOD_VALUE_100401']=='true')
  	$modtemp['tags'][1][]='table_head:101';
  
  $modtemp['tags'][1][]='table_data:102';
  
  if ($modv['MOD_VALUE_100402']=='true')
    $modtemp['tags'][1][]='table_foot:103';

}    
// looking for {list:x} and add to modtemp-pseudo-element
if (strpos($modv['tpl_inner'],"{list:1}")!==false) {
  $modtemp['tags'][1][]='list_data:300';
}    

if (strpos($modv['tpl_inner'],"{list:2}")!==false) {
  $modtemp['tags'][1][]='list_data:310';
}  

// looking for {filelist} and add to modtemp-pseudo-element
if (strpos($modv['tpl_inner'],"{filelist}")!==false && $modv['MOD_VALUE_2003']!='fsb') {

  $modtemp['tags'][1][]='filelist:200';
  if ($modv['MOD_VALUE_2019']=='true')
  	$modtemp['tags'][1][]='checkbox:201';

} 


// **** Template der Elemente  **************************************************************
if ($cms_side['edit'] || $cms_side['edit_all']) {
		
    // Menus nur beim ersten Aufruf generieren:
    if ( !isset(${'flex2'.$cms_mod['container']['id']}) || $cms_mod['modul']['id'] == 1) {
    	if ($mod['test'] == "true") { echo "<br />Menu bauen"; }
    	
    	if(_type_check_editable(true,$modv['MOD_VALUE_1004'],'edit'))
    		${'flex2'.$cms_mod['container']['id']} = flex2_popup_menu();
    	else
    		${'flex2'.$cms_mod['container']['id']} ='';
    	
    } else {
    	if ($mod['test'] == "true") { echo "<br />Menu vorhanden"; }
    
    }
 
    // ****  Link fuer "an 1. Position einfuegen" erstellen **************************************************************
    $modtemp['editbutton_menu'] = '';
    $typegroup_conf = '';
    
    // Doppelte Eintr�ge l�schen
    $modtemp['tags'][1]=array_unique($modtemp['tags'][1]); 

    foreach($modtemp['tags'][1] AS $v){
        $k = explode(":",$v);
        $modtemp['editbutton_menu'] .= flex2_get_edit_string($k[0],$k[1]);
        $typegroup_conf .= flex2_get_typegroup($k[0],$k[1]);
    }

  $modtemp['editbutton_menu'] = substr($modtemp['editbutton_menu'], 0, -1);
  if ($mod['test'] == "true") { echo "<br />editbutton_at_top Edit_Link:".$modtemp['editbutton_menu']; }

    // Wenn 1 Ausgabe 
	if ($modv['is_first']) {
			if (empty($modv['editbutton_at_top']))
    		$modv['editbutton_at_top'] = $modv['MOD_editbutton_at_top'];
    	else
    		$modv['editbutton_at_top'] = $modv['MOD_editbutton'];
    	
    	$modv['editbutton_at_top'] = str_replace("{elements}", '', $modv['editbutton_at_top']);
    	$modv['editbutton_at_top'] = str_replace("{title}", '', $modv['editbutton_at_top']);
    	$modv['editbutton_at_top'] = str_replace("{edit}", '<img src="cms/img/space.gif" border="0" height="16" width="16" />', $modv['editbutton_at_top']);
    	$modtemp['editbutton_menu_tmp'] = str_replace('{entry_txt}', $mod_lang['cf_add_first_pos'] ,${'flex2'.$cms_mod['container']['id']});
    	$modtemp['editbutton_menu_tmp'] = str_replace('{entry}', 0 ,$modtemp['editbutton_menu_tmp']);
    	$modv['editbutton_at_top'] = str_replace("{insert}", $modtemp['editbutton_menu_tmp'], $modv['editbutton_at_top']);
    	//if ($mod['test'] == "true") { echo "<br />Edit_Top:".$modv['editbutton_at_top']; }
	}
	if ($modtemp['tpl_nr'] == '' && $modv['is_first']) {
    	if ($mod['test'] == "true") { echo "<br />Kein Template und erster Eintrag "; }
		// wenn noch kein Element vorhanden 	
 		$modv['editbutton'] = '';
	} else {
	    $modv['editbutton'] = $modv['MOD_editbutton'];
	    $modv['insert_single'] = $modv['MOD_insertbtn_tpl'];
	    $modv['edit_single'] = $modv['MOD_editbtn_tpl'];
    	// Element Title
    	if ($modv['MOD_VALUE_14'] == 'true') {
    	    $modv['editbutton'] = str_replace("{title}", $modv['tpl_title'], $modv['editbutton']);
    	} else {
    	    $modv['editbutton'] = str_replace("{title}", '', $modv['editbutton']);
    	}
    	// PopUp Element hinzufuegen 
    	$modv['editbutton'] = str_replace("{edit}", flex2_edit_menu($modv['tpl_title'], $modtemp['editbutton_menu'], $modv['is_first'], $modv['is_last'], $modv['tpl_group']), $modv['editbutton']);
    	$modtemp['editbutton_menu_tmp'] = str_replace('{entry_txt}', $mod_lang['cf_insert_p1'].' {entry} ({title}) '.$mod_lang['cf_insert_p2'] ,${'flex2'.$cms_mod['container']['id']});
    	$modtemp['editbutton_menu_tmp'] = str_replace('{entry}', $cms_mod['modul']['id'] ,$modtemp['editbutton_menu_tmp']);
    	$modtemp['editbutton_menu_tmp']  = str_replace("{title}", $modv['tpl_title'], $modtemp['editbutton_menu_tmp']);
    	$modv['editbutton'] = str_replace("{insert}", $modtemp['editbutton_menu_tmp'], $modv['editbutton']);


    	$modv['insert_single'] = str_replace("{insert}", $modtemp['editbutton_menu_tmp'], $modv['insert_single']);
    	$modv['insert_single'] = str_replace("{title}", $modv['tpl_title'], $modv['insert_single']);
    	$modv['edit_single'] = str_replace("{edit}", flex2_edit_menu($modv['tpl_title'], $modtemp['editbutton_menu'], $modv['is_first'], $modv['is_last'], $modv['tpl_group']), $modv['edit_single']);
    	$modv['edit_single'] = str_replace("{title}", $modv['tpl_title'], $modv['edit_single']);
  	
    	// PopUp Element bearbeiten
    	
    	if ($mod['test'] == "true") { echo "<br />Edit:".$modv['editbutton']; }
    	
     	
    	
	}
    // wegen den alten Version {elements} l�schen
    $modv['editbutton'] = str_replace("{elements}", '', $modv['editbutton']);
}




?>