<?php
if(! defined('CMS_CONFIGFILE_INCLUDED')){
	die('NO CONFIGFILE FOUND');
}

// Navigation
$cms_lang['nav_1_0']					= 'R&eacute;daction';
$cms_lang['nav_1_1']					= 'Pages';
$cms_lang['nav_1_2']					= 'Gestionnaire de fichiers';

$cms_lang['nav_2_0']					= 'Conception';
$cms_lang['nav_2_1']					= 'Mise en pages';
$cms_lang['nav_2_2']					= 'Feuilles de style';
$cms_lang['nav_2_3']					= 'Javascript';
$cms_lang['nav_2_4']					= 'Modules';
$cms_lang['nav_2_5']					= 'Mod&egrave;les';

$cms_lang['nav_3_0']					= 'Administration';
$cms_lang['nav_3_1']					= 'Utilisateurs';
$cms_lang['nav_3_2']					= 'Groupes';
$cms_lang['nav_3_3']					= 'Projets';
$cms_lang['nav_3_4']					= 'Syst&egrave;me';
$cms_lang['nav_3_5']					= 'Modules d\'extension';

$cms_lang['nav_4_0']					= 'Modules d\'extension';

$cms_lang['login_pleaselogin']			= 'Veuillez introduire votre identifiant et votre mot de passe.';
$cms_lang['login_username']			= 'Identifiant';
$cms_lang['login_password']			= 'Mot de passe';
$cms_lang['login_invalidlogin']		= 'Identifiant et/ou mot de passe invalide. Veuillez r&eacute;essayer!';
$cms_lang['login_nolang']				= 'Aucune langue vous a &eacute;t&eacute; attribu&eacute;e.';
$cms_lang['login_nojs']				= '!! Veuillez activer Javascript dans votre browser, afin que le backend puisse fonctionner correctement.'; 
$cms_lang['login_licence']				= 'Sefrengo &copy; 2005 - 2006 <a href="http://www.sefrengo.org" target="_blank">sefrengo.org</a>. This is free software, and you may redistribute it under the GPL. Sefrengo comes with absolutely no warranty; for details, see the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">license</a>.';

$cms_lang['logout_thanksforusingcms']	= 'Merci d\'avoir utilis&eacute; \'Sefrengo\' A bient&ocirc;t.';
$cms_lang['logout_youareloggedout']    = 'Vous &ecirc;tes maintenant d&eacute;connect&eacute;.';
$cms_lang['logout_backtologin1']		= 'Ici vous revenez de nouveau &agrave; l\'';
$cms_lang['logout_backtologin2']	    = 'inscription';

$cms_lang['area_mod']					= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_4'].'</b>';
$cms_lang['area_mod_new']				= '<b>'.$cms_lang['nav_2_0'].' - nouveau module</b>';
$cms_lang['area_mod_edit']				= '<b>'.$cms_lang['nav_2_0'].' - &Eacute;diter le module</b>';
$cms_lang['area_mod_edit_sql']	     	= '<b>'.$cms_lang['nav_2_0'].' - &Eacute;diter le module-SQL</b>';
$cms_lang['area_mod_config']	     	= '<b>'.$cms_lang['nav_2_0'].' - Configurer le module</b>';
$cms_lang['area_mod_import']	    	= '<b>'.$cms_lang['nav_2_0'].' - Importer un module</b>';
$cms_lang['area_mod_duplicate']	    = '<b>'.$cms_lang['nav_2_0'].' - Copier un module</b>';
$cms_lang['area_mod_xmlimport']	    = '<b>'.$cms_lang['nav_2_0'].' - Importer un module XML</b>';
$cms_lang['area_mod_xmlexport']	   	= '<b>'.$cms_lang['nav_2_0'].' - Exporter un module comme XML</b>';
$cms_lang['area_mod_database']		    = $cms_lang['area_mod_import'] . ' <b>- Base de donn&eacute;es</b>';
$cms_lang['area_mod_repository']		= $cms_lang['area_mod_import'] . ' <b>- R&eacute;f&eacute;rentiel</b>';

$cms_lang['area_plug']					= '<b>'.$cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_5'].'</b>';
$cms_lang['area_plug_new']		      	= '<b>'.$cms_lang['nav_3_0'].' - nouvelle extension</b>';
$cms_lang['area_plug_new_import']	  	= $cms_lang['area_plug_new'].' <b>importer</b>';
$cms_lang['area_plug_new_create']	 	= $cms_lang['area_plug_new'].' <b>cr&eacute;er</b>';
$cms_lang['area_plug_edit']		 	= '<b>'.$cms_lang['nav_3_0'].' - &Eacute;diter l\'extension</b>';
$cms_lang['area_plug_edit_sql']		= '<b>'.$cms_lang['nav_3_0'].' - &Eacute;diter l\'extension-SQL</b>';
$cms_lang['area_plug_config']		  	= '<b>'.$cms_lang['nav_3_0'].' - Configurer l\'extension</b>';
$cms_lang['area_plug_import']	        = '<b>'.$cms_lang['nav_3_0'].' - Importer l\'extension</b>';
$cms_lang['area_plug_folder']			= $cms_lang['area_plug_import'] . ' <b>- R&eacute;pertoire</b>';
$cms_lang['area_plug_repository']      = $cms_lang['area_plug_import'] . ' <b>- R&eacute;f&eacute;rentiel</b>';

$cms_lang['area_con']					= '<b>'.$cms_lang['nav_1_0'].' - '.$cms_lang['nav_1_1'].'</b>';
$cms_lang['area_con_configcat']	    = '<b>'.$cms_lang['nav_1_0'].' - Configurer le dossier</b>';
$cms_lang['area_con_configside']	   	= '<b>'.$cms_lang['nav_1_0'].' - Configurer la page</b>';
$cms_lang['area_con_edit']				= ''.$cms_lang['nav_1_0'].' - &Eacute;diter la page';

$cms_lang['area_upl']					= '<b>'.$cms_lang['nav_1_0'].' - '.$cms_lang['nav_1_2'].'</b>';

$cms_lang['area_lay']					= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_1'].'</b>';
$cms_lang['area_lay_edit']				= '<b>'.$cms_lang['nav_2_0'].' - &Eacute;diter la mise en page</b>';

$cms_lang['area_css']					= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].'</b>';
$cms_lang['area_css_edit']				= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - &Eacute;diter les r&egrave;gles CSS</b>';
$cms_lang['area_css_edit_file']	    = '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - &Eacute;diter le fichier CSS</b>';
$cms_lang['area_css_new_file']		 	= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - Cr&eacute;er un nouveau fichier CSS</b>';
$cms_lang['area_css_import']			= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_2'].' - Importer r&egrave;gles CSS</b>';

$cms_lang['area_js']					= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_3'].'</b>';
$cms_lang['area_js_edit_file']			= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_3'].' - &Eacute;diter fichier Javascript</b>';
$cms_lang['area_js_import']			= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_3'].' - Importer fichier Javascript</b>';

$cms_lang['area_tpl']					= '<b>'.$cms_lang['nav_2_0'].' - '.$cms_lang['nav_2_5'].'</b>';
$cms_lang['area_tpl_edit']				= '<b>'.$cms_lang['nav_2_0'].' - &Eacute;diter le mod&egrave;le</b>';

$cms_lang['area_user']					= '<b>'.$cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_1'].'</b>';
$cms_lang['area_user_edit']   		   	= '<b>'.$cms_lang['nav_3_0'].' - &Eacute;diter l\'utilisateur</b>';
$cms_lang['area_group']			 	= '<b>'.$cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_2'].'</b>';
$cms_lang['area_group_edit']		 	= '<b>'.$cms_lang['nav_3_0'].' - &Eacute;diter le groupe</b>';
$cms_lang['area_group_config']		 	= '<b>'.$cms_lang['nav_3_0'].' - Configurer le groupe</b>';
$cms_lang['area_lang']					= '<b>'.$cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_3'].'</b>';
$cms_lang['area_settings']				= '<b>'.$cms_lang['nav_3_0'].' - R&egrave;glages du syst&egrave;me</b>';
$cms_lang['area_settings_general']	  	= '<b>'.$cms_lang['nav_3_0'].' - '.$cms_lang['nav_3_4'].'</b>';

$cms_lang['lay_action']				= 'Actions';
$cms_lang['lay_edit']					= '&Eacute;diter';
$cms_lang['lay_export']			 	= 'Exporter la mise en page';
$cms_lang['lay_defaultname']			= 'Nouvelle mise en page';
$cms_lang['lay_delete']			    = 'Supprimer';
$cms_lang['lay_import']				= 'Importer la mise en page';
$cms_lang['lay_layoutname']			= 'Nom de la mise en page';
$cms_lang['lay_description']		 	= 'D&eacute;scription';
$cms_lang['lay_doctype']		 	= 'Type de document';
$cms_lang['lay_doctype_autoinsert']		 	= 'Ins&eacute;rer type de document automatiquement en haut du document';
$cms_lang['lay_doctype_none']		 	= 'no du type de document';
$cms_lang['lay_code']					= 'Code source';
$cms_lang['lay_new']					= 'Nouvelle mise en page';
$cms_lang['lay_nolayouts']				= 'Il n\'y pas de mises en pages.';
$cms_lang['lay_cmshead']				= 'Fichiers Meta';
$cms_lang['lay_css']					= 'Feuilles de style';
$cms_lang['lay_js']					= 'Javascript';
$cms_lang['lay_nofile']			    = 'pas de fichier';
$cms_lang['lay_duplicate']				= 'Dupliquer la mise en page';
$cms_lang['lay_copy_of']				= 'Copie de ';
$cms_lang['lay_nofile']			    = 'pas de fichier';
$cms_lang['lay_used']			    = 'utilis&eacute;';

$cms_lang['tpl_action']			 	= 'Actions';
$cms_lang['tpl_actions']['10']			= 'Nouveau mod&egrave;le';
$cms_lang['tpl_edit']					= '&Eacute;diter';
$cms_lang['tpl_defaultname']		  	= 'Nouveau mod&egrave;le';
$cms_lang['tpl_is_start']		  	= 'Is starttemplate';
$cms_lang['tpl_delete']			  	= 'Supprimer';
$cms_lang['tpl_templatename']		  	= 'Nom du mod&egrave;le';
$cms_lang['tpl_description']		  	= 'Description';
$cms_lang['tpl_container']				= 'Conteneur';
$cms_lang['tpl_notemplates']		 	= 'Il n\'y a pas de mod&egrave;les.';
$cms_lang['tpl_layout']			 	= 'Mise en page';
$cms_lang['tpl_duplicate']				= 'Dupliquer le mod&egrave;le';
$cms_lang['tpl_copy_of']				= 'Copie de ';
$cms_lang['tpl_overwrite_all']		  	= 'Reprendre les modifications dans les copies de mod&egrave;les dans le dossiers et pages.';
$cms_lang['tpl_devmessage']		 	= 'C\'est une version de d&eacute;veloppement. Elle n\'est pas faite pour un environnement de production!';

$cms_lang['lang_language']				= 'Langue';
$cms_lang['lang_actions']				= 'Actions';
$cms_lang['lang_rename']				= 'renommer';
$cms_lang['lang_delete']				= 'supprimer';
$cms_lang['lang_newlanguage']		  	= 'Nouvelle langue';

$cms_lang['form_nothing']				= '--- pas ---';
$cms_lang['form_select']				= '--- veuillez choisir ---';

$cms_lang['gen_back']					= 'Retour';
$cms_lang['gen_abort']					= 'annuler';
$cms_lang['gen_sort']					= 'Trier les saisies';
$cms_lang['gen_default']				= 'd&eacute;fault';
$cms_lang['gen_welcome']                = 'Welcome';
$cms_lang['gen_logout']			  	= 'D&eacute;connexion';
$cms_lang['gen_deletealert']		  	= 'Vraiment supprimer?';
$cms_lang['gen_mod_active']		  	= 'Module est activ&eacute;';
$cms_lang['gen_mod_deactive']		 	= 'Module est d&eacute;sactiv&eacute;';
$cms_lang['gen_mod_edit_allow']	    = 'Le contenu peut &ecirc;tre &eacute;dit&eacute;';
$cms_lang['gen_mod_edit_disallow']	 	= 'Le contenu ne peut pas &ecirc;tre &eacute;dit&eacute;';
$cms_lang['gen_rights']			 	= 'Droits';
$cms_lang['gen_overide']				= 'Prise en charge';
$cms_lang['gen_reinstall']				= 'R&eacute;installation';
$cms_lang['gen_expand']			 	= '&Eacute;largi';
$cms_lang['gen_parent']				= 'D&eacute;pendances';
$cms_lang['gen_delete']				= 'Supprimer';
$cms_lang['gen_update']				= 'Mise &agrave; jour';
$cms_lang['gen_fundamental']           = 'Param&egrave;tre de base';
$cms_lang['gen_configuration']			= 'Configuration';
$cms_lang['gen_version']				= 'Version';
$cms_lang['gen_description']			= 'Description';
$cms_lang['gen_verbosename']			= 'Alternative';
$cms_lang['gen_name']			        = 'Nom';
$cms_lang['gen_author']			    = 'Auteur';
$cms_lang['gen_cat']			        = 'Cat&eacute;gorie';
$cms_lang['gen_original']			    = 'Original';
$mod_lang['gen_font']					= 'Caract&egrave;re';
$mod_lang['gen_errorfont']				= 'Caract&egrave;re pour message d\'erreur';
$mod_lang['gen_inputformfont']		  	= 'Caract&egrave;re pour formule de saisie';
$mod_lang['gen_picforsend']				= 'Image pour le bouton \'Valider\'';
$mod_lang['gen_select']					= 'Possibilit&eacute;s de choix';
$cms_lang['gen_select_actions']	    = 'Actions...';
$cms_lang['gen_select_view'] 		    = 'Vue...';
$cms_lang['gen_select_change_to']	  	= 'Changer...';
$cms_lang['gen_help']                  = 'Aide';			// to translate
$cms_lang['gen_logout_wide']           = 'D&eacute;connexion';
$cms_lang['gen_user']			        = 'Utilisateur: ';			// to translate
$cms_lang['gen_licence']			     = 'Licence';			// to translate

$cms_lang['gen_save']			        = 'Sauvegarder';// to translate
$cms_lang['gen_apply']			        = 'Reprendre';// to translate
$cms_lang['gen_cancel']			        = 'Abandonner';// to translate

$cms_lang['gen_save_titletext']			= 'Sauvegarder les donn&eacute;es et retour &agrave; la vue pr&eacute;c&eacute;dente';// to translate
$cms_lang['gen_apply_titletext']		= 'Sauvegarder les donn&eacute;es et rester dans cette vue';// to translate
$cms_lang['gen_cancel_titletext']		= 'Abandonner et retour &agrave; la vue pr&eacute;c&eacute;dente';// to translate

//contentflex
$mod_lang['cf_add_first_pos']			= 'Ins&eacute;rer comme 1er &eacute;l&eacute;ment';
$mod_lang['cf_insert_p1']			    = 'Ins&eacute;rer apr&egrave;s &eacute;l&eacute;ment';
$mod_lang['cf_insert_p2']			    = '';


$mod_lang['news_inputname']				= 'Champ pour les noms';
$mod_lang['news_email']					= 'Adresse de courrier &eacute;lectronique';
$mod_lang['news_name']					= 'Nom (volontaire)';
$mod_lang['news_subcribe']				= 'connecter';
$mod_lang['news_unsubcribe']		    = 'd&eacute;connecter';
$mod_lang['news_both']					= 'les deux';
$mod_lang['news_headline']				= 'Envoyer les derni&egrave;res nouvelles par courriel.';
$mod_lang['news_headlineback']		    = 'Titre';
$mod_lang['news_subcribemessageback']	= 'Confirmation de connexion';
$mod_lang['news_unsubcribemessageback'] = 'Confirmation de d&eacute;connexion';
$mod_lang['news_subcribemessage']	    = 'Nous avons sauvegarder vos donn&eacute;es dans notre base de donn&eacute;es.';
$mod_lang['news_unsubcribemessage']	   	= 'Vous &ecirc;tes supprim&eacute; de notre lettre d\'information';
$mod_lang['news_stopmessage']		   	= 'La r&eacute;ception de notre lettre d\'information a &eacute;t&eacute; d&eacute;sactiv&eacute;e.';
$mod_lang['news_goonmessage']		   	= 'La r&eacute;ception de notre lettre d\'information a &eacute;t&eacute; activ&eacute;e.';
$mod_lang['news_err_1001']				= 'Vous n\'avez pas entr&eacute; d\'adresse de courrier &eacute;lectronique.';
$mod_lang['news_err_1002']				= 'Le format de l\'adresse de courrier &eacute;lectronique n\'est pas correct.';
$mod_lang['news_err_1003']				= 'Cette adresse de courrier &eacute;lectronique est d&eacute;j&agrave; enregistr&eacute;e.';
$mod_lang['news_err_1004']				= 'L\'adresse de courrier &eacute;lectronique n\'a pas &eacute;t&eacute; trouv&eacute;e.';

$mod_lang['login_error']			    = 'Message d\'erreur';
$mod_lang['login_errormsg']				= 'Donn&eacute;es de connexion incorrectes.';
$mod_lang['login_send']					= 'Connexion';
$mod_lang['login_sendout']				= 'D&eacute;connexion';
$mod_lang['login_name']					= 'Veuillez introduire votre identifiant';
$mod_lang['login_password']				= 'Veuillez introduire votre mot de passe';
$mod_lang['login_login']			    = 'Veuillez cliquer pour vous connecter';
$mod_lang['login_logout']				= 'Veuillez cliquer pour vous d&eacute;connecter';
$mod_lang['login_picforlogout']		    = 'Image pour d&eacute;connexion';

$mod_lang['link_extern']			    = 'lien externe:';
$mod_lang['link_intern']			    = 'ou lien interne:';
$mod_lang['link_blank']					= 'Ouvrir le lien dans une nouvelle fen&ecirc;tre';
$mod_lang['link_self']					= 'Ouvrir le lien dans la m&ecirc;me fen&ecirc;tre';
$mod_lang['link_parent']	  		    = 'Terminer le jeu de cadres actuel lors de l\'ex&eacute;cution du lien';
$mod_lang['link_top']					= 'Terminer tous les jeux de cadres lors de l\'ex&eacute;cution du lien';
$mod_lang['link_edit']					= '&Eacute;diter le lien';

$mod_lang['type_text']					= 'Texte';
$mod_lang['type_textarea']				= 'Textarea';
$mod_lang['type_wysiwyg']				= 'WYSIWYG';
$mod_lang['type_link']					= 'Lien';
$mod_lang['type_file']					= 'Lien de fichier';
$mod_lang['type_image']					= 'Image';
$mod_lang['type_sourcecode']		    = 'Code source';
$mod_lang['type_typegroup']				= 'Contenu';
$mod_lang['type_container']				= 'Contenu';
$mod_lang['type_edit_container']	    = 'Module';
$mod_lang['type_edit_side']				= 'Page';
$mod_lang['type_edit_folder']		    = 'Dossier';
$mod_lang['type_save']					= 'sauvegarder';
$mod_lang['type_edit']					= '&eacute;diter';
$mod_lang['type_new']					= 'nouveau';
$mod_lang['side_new']					= 'cr&eacute;er';
$mod_lang['side_config']			    = 'configurer';
$mod_lang['side_mode']					= 'Mode de vue';
$mod_lang['side_publish']				= 'publier';
$mod_lang['side_delete']			    = 'supprimer';
$mod_lang['side_edit']					= '&Eacute;diter la page';
$mod_lang['side_overview']				= 'Aper&ccedil;u de la page';
$mod_lang['side_preview']				= 'Pr&eacute;visualisation';
$mod_lang['type_delete']			    = 'supprimer';
$mod_lang['type_up']					= 'vers le haut';
$mod_lang['type_down']					= 'vers le bas';

$mod_lang['img_edit']					= 'Changer l\'image';
$mod_lang['imgdescr_edit']				= 'Changer la description de l\'image';
$mod_lang['link_intern']			    = 'lien interne';
$mod_lang['link_extern']			    = 'lien externe';

$mod_lang['err_id']					    = 'Mauvaise ID.';
$mod_lang['err_type']					= 'Mauvais type.';

$cms_lang['title_rp_popup']			= '&Eacute;diter les droits';

// 01xx = Con

// 02xx = Str
$cms_lang['err_0201']					= 'Le dossier &agrave; supprimer contient des sous-dossiers. Suppression impossible.';
$cms_lang['err_0202']					= 'Il y a encore des pages dans ce dossier. Suppression impossible.';

// 03xx = Lay
$cms_lang['err_0301']					= 'Mise en page utilis&eacute;e. Suppression impossible.';
$cms_lang['err_0302']					= '<font color="black">La mise en page a &eacute;t&eacute; copi&eacute; avec succ&egrave;s.</font>';

// 05xx = Tpl
$cms_lang['err_0501']					= 'Mod&egrave;le utilis&eacute;. Suppression impossible.';

// 06xx = Dis
$cms_lang['err_0601']					= 'La page n\'a pas pu &ecirc;tre cr&eacute;&eacute;e. Veuillez attribuer un mod&egrave;le &agrave; chaque dossier.';

// 07xx = Upl
$cms_lang['err_0701']					= 'Fichier utilis&eacute;. Suppression impossible.';
$cms_lang['err_0702']					= 'Le r&eacute;pertoire existe d&eacute;j&agrave;.';
$cms_lang['err_0703']					= 'Le fichier n\'a pas pu &ecirc;tre transfer&eacute; sur le serveur.';
$cms_lang['err_0704']					= 'Ce nom n\'est pas valide.';
$cms_lang['err_0705']					= 'Ce type de fichier n\'est pas autoris&eacute;.';
$cms_lang['err_0706']					= 'Le r&eacute;pertoire cible n\'a pas pu &ecirc;tre trouv&eacute;.';
$cms_lang['err_0707']					= 'Le t&eacute;l&eacute;chargement sur le serveur a &eacute;t&eacute; interrompu.';

// 08xx = Lang
$cms_lang['err_0801']					= 'Il existe d&eacute;j&agrave; une langue avec ce nom. Aucune langue a &eacute;t&eacute; cr&eacute;&eacute;.';
$cms_lang['err_0802']					= 'Il y a encore des pages ou des dossiers qui sont en ligne. Si vous voulez vraiment supprimer cette langue, mettez toutes les pages et dossiers en mode autonome.<br> ATTENTION/REMARQUE: La suppression ne peut pas &ecirc;tre annul&eacute;e.';

// 09xx = Projekte
$cms_lang['err_0901']					= '';
$cms_lang['err_0902']					= '';

// 13xx = SQL-Schicht
$cms_lang['err_1310']					= 'Pas assez de valeurs pour un query INSERT!';
$cms_lang['err_1320']					= 'Pas assez de valeurs pour un query UPDATE!';
$cms_lang['err_1321']					= 'L\'indication WHERE manque. La query UPDATE n\'a pas &eacute;t&eacute; ex&eacute;cut&eacute;.';
$cms_lang['err_1330']					= 'Pas assez de valeurs pour un query DELETE!';
$cms_lang['err_1331']					= 'L\'indication WHERE manque. La query DELETE n\'a pas &eacute;t&eacute; ex&eacute;cut&eacute;.';
$cms_lang['err_1340']					= 'Pas assez de valeurs pour un query SELECT!';
$cms_lang['err_1350']					= 'Pas de param&egrave;tres pour la cr&eacute;ation de donn&eacute;es SQL!';
$cms_lang['err_1360']					= 'Erreur de la base de donn&eacute;es. Les donn&eacute;es des paquets sont inconsistant!';

// 15xx = Repository
$cms_lang['err_1500']	   				= 'Erreur du r&eacute;f&eacute;rentiel. La function n\'a pas &eacute;t&eacute; ex&eacute;cut&eacute;.!';
$cms_lang['err_1501']	   				= 'Erreur du r&eacute;f&eacute;rentiel. Le fichier n\'existe pas!';
$cms_lang['err_1502']	   				= 'Erreur du r&eacute;f&eacute;rentiel. Format XML inconnu!';
$cms_lang['err_1503']	   				= 'Erreur du r&eacute;f&eacute;rentiel. Format XML erron&eacute;!';
$cms_lang['err_1504']	   				= 'Erreur du r&eacute;f&eacute;rentiel. Analyseur XML ne peut pas &ecirc;tre lancer!';
$cms_lang['err_1505']	   				= 'Erreur du r&eacute;f&eacute;rentiel. Le fichier ne peut pas &ecirc;tre cr&eacute;er!';
$cms_lang['err_1506']	   				= 'Erreur du r&eacute;f&eacute;rentiel. Le fichier ne peut pas &ecirc;tre lu!';
$cms_lang['err_1507']	   				= 'Erreur du r&eacute;f&eacute;rentiel. Doublons d\'ID du r&eacute;f&eacute;rentiel!';

// 17xx = Allgemeine Rechtefehler
$cms_lang['err_1701']					= 'Pas suffisament de droit pour cette function!';
?>
