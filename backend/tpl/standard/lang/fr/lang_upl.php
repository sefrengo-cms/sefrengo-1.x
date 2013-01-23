<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

	include_once($cfg_cms['cms_path'] .'tpl/standard/lang/'.$cfg_cms['backend_lang'].'/lang_user.php');

$cms_lang['upl_action']			  = 'Actions';
$cms_lang['upl_directoriesandfiles'] = 'R&eacute;pertoires / Fichiers';
$cms_lang['upl_description']		  = 'Description';
$cms_lang['upl_cancel']			  = 'Annuler';
$cms_lang['upl_popupclose']		  = 'Fermer la fen&ecirc;tre';
$cms_lang['upl_dirisempty']		  = 'Le r&eacute;pertoire est vide';
$cms_lang['upl_upload']			  = 'T&eacute;l&eacute;chargement vers le serveur des fichiers';
$cms_lang['upl_editvisible']		  = 'cach&eacute;';
$cms_lang['upl_editprotected']		  = 'proteg&eacute;';
$cms_lang['upl_opendir']			  = 'Ouvrir le r&eacute;pertoire';
$cms_lang['upl_closedir']			  = 'Fermer le r&eacute;pertoire';
$cms_lang['upl_createdir']			  = 'Cr&eacute;er le r&eacute;pertoire';
$cms_lang['upl_editdir']			  = '&Eacute;diter le r&eacute;pertoire';
$cms_lang['upl_movedirtodir']		  = 'D&eacute;placer le r&eacute;pertoire avec tous les fichiers vers';
$cms_lang['upl_movedirname']		  = 'Nom du r&eacute;pertoire';
$cms_lang['upl_copydirtodir']		  = 'Copier le r&eacute;pertoire avec tous les fichiers vers';
$cms_lang['upl_deletedir']			  = 'Supprimer le r&eacute;pertoire avec tous les fichiers et sous-r&eacute;pertoires';
$cms_lang['upl_scandir']			  = 'Synchroniser le r&eacute;pertoire avec la base de donn&eacute;es';
$cms_lang['upl_scandir_root']		  = 'Importer le r&eacute;pertoire de base';
$cms_lang['upl_deletefile']		  = 'Supprimer le fichier';
$cms_lang['upl_editfile']			  = '&Eacute;diter le fichier';
$cms_lang['upl_movefiletodir']		  = 'D&eacute;placer le fichier vers';
$cms_lang['upl_copyfiletodir']		  = 'Copier le fichier vers';
$cms_lang['upl_downloadfile']  	  = 'T&eacute;l&eacute;charger le fichier';
$cms_lang['upl_copyfilename']		  = 'Nom de fichier';
$cms_lang['upl_file']				  = 'Fichier';
$cms_lang['upl_fileopen']			  = 'Ouvrir le fichier';
$cms_lang['upl_delete']			  = 'supprimer';
$cms_lang['upl_changeviewdetail']	  = 'Changer &agrave; la vue d&eacute;taill&eacute;e';
$cms_lang['upl_changeviewcompact']	  = 'Changer &agrave; la vue compacte';
$cms_lang['upl_showfilesindir']	  = 'Montrer fichiers dans r&eacute;pertoire ...';
$cms_lang['upl_selectuploaddir']	  = 'Choisir r&eacute;pertoire ...';
$cms_lang['upl_bulkupload']		  = 'Volume de t&eacute;l&eacute;chargement du fichier ZIP vers le serveur (max. ' . get_cfg_var('upload_max_filesize') . 'octet)';
$cms_lang['upl_tarupload']			  = 'Volume de t&eacute;l&eacute;chargement du fichier TAR vers le serveur (max. ' . get_cfg_var('upload_max_filesize') . 'octet)';
$cms_lang['upl_openfileinnewwindow'] = 'Afficher le fichier dans sa taille originale';
$cms_lang['upl_titel']			   	  = 'Titre';
$cms_lang['upl_download']  		  = 'T&eacute;l&eacute;chargement';
$cms_lang['upl_edit']  			  = '&Eacute;diter';
$cms_lang['upl_copy']				  = 'Copier';
$cms_lang['upl_move']				  = 'D&eacute;placer';
$cms_lang['upl_del']				  = 'Supprimer';
$cms_lang['upl_editrights']		  = '&Eacute;diter les droits';
$cms_lang['upl_confirm_delete']	  = 'Voulez-vous vraiment effacer le fichier?';
$cms_lang['upl_newplace']			  = 'vers';
$cms_lang['upl_root_dir']			  = 'R&eacute;pertoire racine';

$cms_lang['scan_title']			  = 'Synchronisation des r&eacute;pertoires';
$cms_lang['scan_error']			  = 'Erreur:';
$cms_lang['scan_done_start']         = 'Progr&egrave;s en cours en pourcentage: ';
$cms_lang['scan_done_end']			  = '% fini';
$cms_lang['scan_status']			  = 'Status du balayage: ';
$cms_lang['scan_status_done_start']  = 'fini<br>Dur&eacute;e: ';
$cms_lang['scan_status_done_end']	  = ' seconde.';
$cms_lang['scan_status_active']      = 'actif';
$cms_lang['scan_status_total']		  = 'Total';
$cms_lang['scan_status_processed']	  = 'Transform&eacute;';
$cms_lang['scan_status_todo']        = 'Ouvert';
$cms_lang['scan_directroies']		  = 'R&eacute;pertoire';
$cms_lang['scan_files']			  = 'Fichiers';
$cms_lang['scan_thumbs']			  = 'Vignettes';
$cms_lang['scan_errors']			  = 'R&eacute;pertoires ou fichiers erron&eacute;s:';
$cms_lang['scan_errors_none']		  = 'aucune';
$cms_lang['scan_closing_time']		  = 'Fen&ecirc;tre ferme dans environ 10 secondes.';
$cms_lang['scan_close_now']		  = 'Close';

$cms_lang['scan_options']            = 'R&eacute;glages du scannage du r&eacute;pertoire';
$cms_lang['scan_start_dirscan']      = 'Lancer le scannage du r&eacute;pertoire';
$cms_lang['scan_thumbs_checkbox']	  = 'Reconstituer les vignettes';
$cms_lang['scan_nosubdir_checkbox']  = 'Ne pas balayer les sous-r&eacute;pertoires';

$cms_lang['upl_js_texte_pp_title']			= 'Informations du fichier<br>&nbsp;cliquer ici pour le voir';
$cms_lang['upl_js_texte_pp_header_bild']	= 'Pr&eacute;visualisation et information de l\'image<br>&nbsp;cliquer ici pour le voir en taille originale';
$cms_lang['upl_js_texte_pp_header_datei']	= 'Pr&eacute;visualisation et information de l\'image<br>&nbsp;taille originale';
$cms_lang['upl_js_texte_pp_created']		= 'Cr&eacute;&eacute; le : ';
$cms_lang['upl_js_texte_pp_modified']		= 'Derni&egrave;re modification: ';
$cms_lang['upl_js_texte_pp_author']		= 'R&eacute;dacteur: ';
$cms_lang['upl_js_texte_pp_size']			= 'Taille: ';

// 14xx = Filemanager
$cms_lang['err_1400']					= 'Le nom du fichier manque ou contient des caract&egrave;res non autoris&eacute;s!';
$cms_lang['err_1401']					= 'Il existe d&eacute;j&agrave; un fichier avec ce nom!';
$cms_lang['err_1402']					= 'Le fichier n\'a pas pu &ecirc;tre trouv&eacute;!';
$cms_lang['err_1403']					= 'Le fichier n\'a pas pu &ecirc;tre copi&eacute;!';
$cms_lang['err_1404']					= 'Le fichier n\'a pas pu &ecirc;tre d&eacute;plac&eacute;!';
$cms_lang['err_1405']					= 'Il manque des param&egrave;tres pour cette fonction!';
$cms_lang['err_1406']					= 'Le nom du r&eacute;pertoire manque ou contient des caract&egrave;res non autoris&eacute;s!';
$cms_lang['err_1407']					= 'L\'extension du fichier ne peut pas &ecirc;tre modifi&eacute;e!';
$cms_lang['err_1408']					= 'Le r&eacute;pertoire n\'a pas pu &ecirc;tre supprim&eacute;!';
$cms_lang['err_1409']					= 'Le fichier n\'a pas pu &ecirc;tre supprim&eacute;!';
$cms_lang['err_1410']					= $cms_lang['err_1409'].' Fichier utilis&eacute;.';
$cms_lang['err_1411']					= 'Le fichier n\'a pas pu &ecirc;tre supprim&eacute;!';
$cms_lang['err_1412']					= 'Le fichier n\'a pas pu &ecirc;tre renomm&eacute;!';
$cms_lang['err_1413']					= 'Il existe d&eacute;j&agrave; un r&eacute;pertoire avec ce nom!';
$cms_lang['err_1414']					= 'Le r&eacute;pertoire n\'a pas pu &ecirc;tre renomm&eacute;!';
$cms_lang['err_1415']	   				= 'Parametre erron&eacute;. La fonction n\'est pas ex&eacute;cut&eacute;e!';
$cms_lang['err_1416']	   		  		= 'Fichier n\'a pas pu &ecirc;tre surimprimer. Fonction interrompue!';
$cms_lang['err_1417']					= 'Fichier n\'a pas pu &ecirc;tre cr&eacute;er!';
$cms_lang['err_1418']					= 'R&eacute;pertoire n\'a pas pu &ecirc;tre cr&eacute;er!';
$cms_lang['err_1419']					= 'Fichier n\'a pas pu &ecirc;tre d&eacute;palcer. Le fichier est prot&eacute;g&eacute; par le syst&egrave;me de fichiers!';
$cms_lang['err_1420']					= 'Le nom du fichier contient des caract&egrave;res non autoris&eacute;s!';
$cms_lang['err_1421']					= 'T&eacute;l&eacute;chargement vers le serveur &eacute;chou&eacute;!';
$cms_lang['err_1422']					= $cms_lang['err_1421'] . ' L\'archive contient des r&eacute;pertoires avec des caract&egrave;res non autoris&eacute;s! ';
$cms_lang['err_1423']					= 'T&eacute;l&eacute;chargement vers le serveur &eacute;ffectu&eacute;. Le fichier n\'a pas pu &ecirc;tre &eacute;cris dans la base de donn&eacute;es!';
$cms_lang['err_1424']					= $cms_lang['err_1421'] . ' Le fichier n\'a pas pu &ecirc;tre sauvegarder dans le r&eacute;pertoire d&eacute;sir&eacute;!';



?>
