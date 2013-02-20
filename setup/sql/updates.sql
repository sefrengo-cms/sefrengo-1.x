# Tabellen reparieren - falls kaputt 08.10.2003
REPAIR TABLE `cms_backendmenu`;
REPAIR TABLE `cms_cat`;
REPAIR TABLE `cms_cat_expand`;
REPAIR TABLE `cms_cat_lang`;
REPAIR TABLE `cms_cat_side`;
REPAIR TABLE `cms_clients`;
REPAIR TABLE `cms_clients_lang`;
REPAIR TABLE `cms_code`;
REPAIR TABLE `cms_container`;
REPAIR TABLE `cms_container_conf`;
REPAIR TABLE `cms_content`;
REPAIR TABLE `cms_content_external`;
REPAIR TABLE `cms_css`;
REPAIR TABLE `cms_css_upl`;
REPAIR TABLE `cms_directory`;
REPAIR TABLE `cms_filetype`;
REPAIR TABLE `cms_groups`;
REPAIR TABLE `cms_js`;
REPAIR TABLE `cms_lang`;
REPAIR TABLE `cms_lay`;
REPAIR TABLE `cms_lay_upl`;
REPAIR TABLE `cms_mod`;
REPAIR TABLE `cms_perms`;
REPAIR TABLE `cms_sessions`;
REPAIR TABLE `cms_side`;
REPAIR TABLE `cms_side_lang`;
REPAIR TABLE `cms_tpl`;
REPAIR TABLE `cms_tpl_conf`;
REPAIR TABLE `cms_type`;
REPAIR TABLE `cms_upl`;
REPAIR TABLE `cms_uplcontent`;
REPAIR TABLE `cms_users`;
REPAIR TABLE `cms_users_groups`;
REPAIR TABLE `cms_values`;

#delete values
DELETE FROM cms_values WHERE group_name = 'cfg' AND key1 IN ('path_frontend_tmp', 'version', 'path_help', 'path_img', 'path_inc', 'path_js', 'path_css', 'path_tpl', 'path_lang', 'path_external', 'path_phplib', 'path_wysiwyg', 'path_frontend_img', 'path_frontend_css', 'path_frontend_js', 'skin');
DELETE FROM cms_values WHERE group_name = 'cfg_client' AND key1 IN ('cssfile', 'jsfile', 'session_enabled', 'upl_file', 'upl_addon', 'remove_files_404', 'css_sort_original', 'stat_enabled');

#insert new values
#cfg update
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'version', '', '', '', '00.97.00', '0', NULL, NULL, '', NULL, NULL, '0');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'skin', '', '', '', 'standard', 103, 'set_skin', NULL, 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'chmod_value', '', '', '', '777', 110, 'set_chmod_value', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'chmod_enabled', '', '', '', '1', 105, 'set_chmod_enable', NULL, 'txt', NULL, NULL, 1);

#cfg_client updates
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'session_enabled', '', '', '', '1', 201, 'set_session_frontend_enabled', 'setuse_general', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'upl_addon', '', '', '', 'gif,jpg,jpeg,png', 310, 'setuse_upl_addon', '', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'remove_files_404', '', '', '', 'true', 315, 'setuse_remove_files_404', '', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'css_sort_original', '', '', '', '0', 320, 'setuse_css_sort_original', '', 'txt', '', '', '1');

INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'session_enabled', '', '', '', '1', 201, 'set_session_frontend_enabled', 'setuse_general', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'upl_addon', '', '', '', 'gif,jpg,jpeg,png', 310, 'setuse_upl_addon', '', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'remove_files_404', '', '', '', 'true', 315, 'setuse_remove_files_404', '', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'css_sort_original', '', '', '', '0', 320, 'setuse_css_sort_original', '', 'txt', '', '', '1');

INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'session_enabled', '', '', '', '1', 201, 'set_session_frontend_enabled', 'setuse_general', 'txt', NULL, NULL, '1');
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'upl_addon', '', '', '', 'gif,jpg,jpeg,png', 310, 'setuse_upl_addon', '', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'remove_files_404', '', '', '', 'true', 315, 'setuse_remove_files_404', '', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'css_sort_original', '', '', '', '0', 320, 'setuse_css_sort_original', '', 'txt', '', '', '1');

#perms update
DELETE FROM cms_values WHERE group_name = 'perms_backend_general';

# Backendmenu update
DELETE FROM cms_backendmenu WHERE entry_langstring = 'nav_4_2' AND entry_url = 'main.php?area=log';
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_plugin\')' WHERE entry_langstring = 'nav_4_0' AND entry_validate = 'root';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '5' AND entry_langstring = 'nav_5_0';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '6' AND entry_langstring = 'nav_1_1';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '7' AND entry_langstring = 'nav_1_2';
INSERT INTO cms_backendmenu VALUES (8, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '9' AND entry_langstring = 'nav_2_1';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '10' AND entry_langstring = 'nav_2_2';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '11' AND entry_langstring = 'nav_2_3';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '12' AND entry_langstring = 'nav_2_4';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '13' AND entry_langstring = 'nav_2_5';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '14' AND entry_langstring = 'nav_3_1';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '15' AND entry_langstring = 'nav_3_2';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '16' AND entry_langstring = 'nav_3_3';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '17' AND entry_langstring = 'nav_3_4';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '18' AND entry_langstring = 'nav_4_1';
UPDATE cms_backendmenu SET sortindex = '40', entry_langstring = 'empty_dummy', entry_url = 'root', url_target = 'single', entry_validate = 'root' WHERE idbackendmenu = '19' AND entry_langstring = 'nav_4_2';
UPDATE cms_backendmenu SET parent = '1', sortindex = '10', entry_langstring = 'nav_1_1', entry_url = 'main.php?area=con', url_target = 'single', entry_validate = '$perm->have_perm(\'1\', \'area_con\')' WHERE idbackendmenu = '20' AND entry_langstring = 'nav_5_1';
UPDATE cms_backendmenu SET parent = '1', sortindex = '32', entry_langstring = 'nav_1_2', entry_url = 'main.php?area=upl', url_target = 'single', entry_validate = '$perm->have_perm(\'1\', \'area_upl\')' WHERE idbackendmenu = '21' AND entry_langstring = 'nav_5_2';
INSERT INTO cms_backendmenu VALUES (22, 2, 0, 10, 'nav_2_1', 'main.php?area=lay&idclient=$client', 'single', '$perm->have_perm(\'1\', \'area_lay\')');
INSERT INTO cms_backendmenu VALUES (23, 2, 0, 20, 'nav_2_2', 'main.php?area=css&idclient=$client', 'single', '$perm->have_perm(\'1\', \'area_css\')');
INSERT INTO cms_backendmenu VALUES (24, 2, 0, 40, 'nav_2_4', 'main.php?area=mod&idclient=$client', 'single', '$perm->have_perm(\'1\', \'area_mod\')');
INSERT INTO cms_backendmenu VALUES (25, 2, 0, 30, 'nav_2_3', 'main.php?area=js&idclient=$client', 'single', '$perm->have_perm(\'1\', \'area_js\')');
INSERT INTO cms_backendmenu VALUES (26, 2, 0, 50, 'nav_2_5', 'main.php?area=tpl', 'single', '$perm->have_perm(\'1\', \'area_tpl\')');
INSERT INTO cms_backendmenu VALUES (27, 3, 0, 10, 'nav_3_1', 'main.php?area=user', 'single', '$perm->is_admin()');
INSERT INTO cms_backendmenu VALUES (28, 3, 0, 20, 'nav_3_2', 'main.php?area=group', 'single', '$perm->have_perm(\'1\', \'area_group\')');
INSERT INTO cms_backendmenu VALUES (29, 3, 0, 30, 'nav_3_3', 'main.php?area=clients', 'single', '$perm->have_perm(\'1\', \'area_clients\')');
INSERT INTO cms_backendmenu VALUES (30, 3, 0, 40, 'nav_3_4', 'main.php?area=settings', 'single', '$perm->have_perm(\'1\', \'area_settings\')');
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_con\')' WHERE entry_validate = '$perm->have_perm(\'area_con\')';
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_upl\')' WHERE entry_validate = '$perm->have_perm(\'area_upl\')';
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_lay\')' WHERE entry_validate = '$perm->have_perm(\'area_lay\')';
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_css\')' WHERE entry_validate = '$perm->have_perm(\'area_css\')';
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_mod\')' WHERE entry_validate = '$perm->have_perm(\'area_mod\')';
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_js\')' WHERE entry_validate = '$perm->have_perm(\'area_js\')';
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_tpl\')' WHERE entry_validate = '$perm->have_perm(\'area_tpl\')';
UPDATE cms_backendmenu SET entry_url = 'main.php?area=user' WHERE entry_url = 'main.php?area=users';
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_group\')', entry_url = 'main.php?area=group'  WHERE entry_validate = '$perm->have_perm(\'area_groups\')';
UPDATE cms_backendmenu SET entry_url = 'main.php?area=clients', entry_validate = '$perm->have_perm(\'1\', \'area_clients\')' WHERE entry_validate = '$perm->have_perm(\'area_lang\')';
UPDATE cms_backendmenu SET entry_validate = '$perm->have_perm(\'1\', \'area_settings\')' WHERE entry_validate = '$perm->have_perm(\'area_settings\')';

# Berechtigung f¸r Modulerstellung entfernen 01.08.2003
ALTER TABLE `cms_container_conf` DROP `copy`;

# echo durch $code ersetzt und SPAW-Editor entfernt 01.08.2003
DELETE FROM cms_type;
INSERT INTO cms_type VALUES (1, 'text', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_text($formname, $content, $type_config, $cms_side);', '', 'Text', '', '2002-05-13 19:02:34', '2002-05-13 19:02:34');
INSERT INTO cms_type VALUES (2, 'wysiwyg', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_wysiwyg($formname, $content, $type_config, $cms_side);', '', 'Text/HTML', '', '2002-05-13 19:04:13', '2002-05-13 19:04:13');
INSERT INTO cms_type VALUES (3, 'textarea', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_textarea($formname, $content, $type_config, $cms_side);', '', 'Textarea', '', '2002-05-13 19:04:13', '2002-05-13 19:04:13');
INSERT INTO cms_type VALUES (4, 'image', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_img($formname, $content, $type_config, $cms_side);', '', 'Bild', '', '2002-05-13 19:04:21', '2002-05-13 19:04:21');
INSERT INTO cms_type VALUES (5, 'imgdescr', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_imgdescr($formname, $content, $type_config, $cms_side);', '', 'Bildbeschreibung', '', '2002-05-13 19:04:28', '2002-05-13 19:04:28');
INSERT INTO cms_type VALUES (6, 'link', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_link($formname, $content, $type_config, $cms_side);', '', 'Linkadresse (URL)', '', '2002-05-13 19:04:36', '2002-05-13 19:04:36');
INSERT INTO cms_type VALUES (7, 'linkdescr', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_linkdescr($formname, $content, $type_config, $cms_side);', '', 'Linkname', '', '2002-05-13 19:05:00', '2002-05-13 19:05:00');
INSERT INTO cms_type VALUES (8, 'linktarget', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_linktarget($formname, $content, $type_config, $cms_side);', '', 'Zielfenster', '', '2002-05-13 19:04:43', '2002-05-13 19:04:43');
INSERT INTO cms_type VALUES (9, 'sourcecode', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_sourcecode($formname, $content, $type_config, $cms_side);', '', 'Sourcecode', '', '2002-05-13 19:05:00', '2002-05-13 19:05:00');
INSERT INTO cms_type VALUES (10, 'file', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_file($formname, $content, $type_config, $cms_side);', '', 'Dateiauswahl', 'bjoern@project-gooseberry.de', '2002-05-13 19:04:36', '2002-05-13 19:04:36');
INSERT INTO cms_type VALUES (11, 'filedescr', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_filedescr($formname, $content, $type_config, $cms_side);', '', 'Beschreibung', 'bjoern@project-gooseberry.de', '2003-07-05 15:42:05', '2003-07-05 15:42:05');
INSERT INTO cms_type VALUES (12, 'filetarget', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_filetarget($formname, $content, $type_config, $cms_side);', '', 'Zielfenster', 'bjoern@project-gooseberry.de', '2002-05-13 19:04:43', '2002-05-13 19:04:43');
INSERT INTO cms_type VALUES (13, 'wysiwyg2', '$type_config = $cmstag_config[$con_type[$con_contype][\'type\']][$con_typenumber];\r\n$code .= type_form_wysiwyg2($formname, $content, $type_config, $cms_side);', '', 'Text/HTML', 'bjoern@project-gooseberry.de', '2003-08-03 08:49:37', '2003-08-03 08:49:37');

# neue Tabelle erstellen 13.08.2003
CREATE TABLE cms_uplcontent (
  idupl int(6) NOT NULL default '0',
  uplcontent mediumblob,
  PRIMARY KEY  (idupl)
) ENGINE=MyISAM;

# neue Values 13.08.2003
DELETE FROM cms_values WHERE group_name = 'cfg_client' AND conf_sortindex IN ('305', '306', '701', '702');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'thumbext', '', '', '', '_cms_thumb', 305, 'setuse_thumb_ext', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'fm_delete_ignore_404', '', '', '', 'true', 306, 'setuse_fm_delete_ignore_404', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'css_checking', '', '', '', '1', 701, 'setuse_csschecking', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'css_ignore_rules_with_errors', '', '', '', '0', 702, 'setuse_css_ignore_error_rules', '', 'txt', NULL, NULL, 1);

INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'thumbext', '', '', '', '_cms_thumb', 305, 'setuse_thumb_ext', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'fm_delete_ignore_404', '', '', '', 'true', 306, 'setuse_fm_delete_ignore_404', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'css_checking', '', '', '', 'true', 701, 'setuse_csschecking', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'css_ignore_rules_with_errors', '', '', '', 'true', 702, 'setuse_css_ignore_error_rules', '', 'txt', NULL, NULL, 1);

INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'thumbext', '', '', '', '_cms_thumb', 305, 'setuse_thumb_ext', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'fm_delete_ignore_404', '', '', '', 'true', 306, 'setuse_fm_delete_ignore_404', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'css_checking', '', '', '', 'true', 701, 'setuse_csschecking', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'css_ignore_rules_with_errors', '', '', '', 'true', 702, 'setuse_css_ignore_error_rules', '', 'txt', NULL, NULL, 1);

# Eigenen Punkt CSSeditor-Einstellungen eingef¸hrt 31.08.2003
DELETE FROM cms_values WHERE group_name = 'cfg_client' AND  key1 IN ('css_sort_original', 'css_checking', 'css_ignore_rules_with_errors');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'css_sort_original', '', '', '', '0', 400, 'setuse_css_sort_original', 'setuse_css', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'css_checking', '', '', '', '1', 405, 'setuse_csschecking', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'css_ignore_rules_with_errors', '', '', '', '0', 410, 'setuse_css_ignore_error_rules', '', 'txt', NULL, NULL, 1);

INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'css_sort_original', '', '', '', '0', 400, 'setuse_css_sort_original', 'setuse_css', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'css_checking', '', '', '', '1', 405, 'setuse_csschecking', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'css_ignore_rules_with_errors', '', '', '', '0', 410, 'setuse_css_ignore_error_rules', '', 'txt', NULL, NULL, 1);

INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'css_sort_original', '', '', '', '0', 400, 'setuse_css_sort_original', 'setuse_css', 'txt', '', '', '1');
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'css_checking', '', '', '', '1', 405, 'setuse_csschecking', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'css_ignore_rules_with_errors', '', '', '', '0', 410, 'setuse_css_ignore_error_rules', '', 'txt', NULL, NULL, 1);

# Neues Feld f¸r Modulkonfiguration 3.9.2003
ALTER TABLE `cms_mod` ADD `config` TEXT AFTER `output`;

# Zeiger f¸r permklassen 3.9.2003
INSERT INTO cms_perms VALUES ( '', 0, NULL, 0, 'backend', 'intern', 'side', 'allow');
INSERT INTO cms_perms VALUES ( '', 0, NULL, 0, 'backend', 'intern', 'cat', 'allow');

# DM Updates 6.10.2003
UPDATE cms_values SET conf_sortindex = 321 WHERE group_name = 'cfg_client' AND key1 = 'css_checking';
UPDATE cms_values SET conf_sortindex = 322 WHERE group_name = 'cfg_client' AND key1 = 'css_ignore_rules_with_errors';
UPDATE cms_values SET key1 = 'chmod_enabled' WHERE group_name = 'cfg' AND key1 = 'chmod_enable';
UPDATE cms_values SET value = '[\\d\\.]*?\\d+(px|pt|cm|mm|in|em|ex)|0 +' WHERE group_name = 'css_units' AND key1 = 'length' AND key2 = 'u';

DELETE FROM cms_values WHERE group_name = 'cfg_client' AND key1 = 'remove_empty_directories';
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'remove_empty_directories', '', '', '', 'false', 316, 'setuse_remove_empty_directories', '', 'txt', '', '', 1);
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'remove_empty_directories', '', '', '', 'false', 316, 'setuse_remove_empty_directories', '', 'txt', '', '', 1);
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'remove_empty_directories', '', '', '', 'false', 316, 'setuse_remove_empty_directories', '', 'txt', '', '', 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'errorpage', '', '', '', '0', 209, 'setuse_errorpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'errorpage', '', '', '', '0', 209, 'setuse_errorpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'errorpage', '', '', '', '0', 209, 'setuse_errorpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'loginpage', '', '', '', '0', 210, 'setuse_loginpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'loginpage', '', '', '', '0', 210, 'setuse_loginpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'loginpage', '', '', '', '0', 210, 'setuse_loginpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'cache', '', '', '', '1', 211, 'setuse_cache', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 2, 0, 'cfg_client', 'cache', '', '', '', '1', 211, 'setuse_cache', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 3, 0, 'cfg_client', 'cache', '', '', '', '1', 211, 'setuse_cache', NULL, 'txt', NULL, NULL, 1);

# Update der Updateroutine - Fehlerbereinigung 08.10.2003
UPDATE cms_values SET conf_sortindex = 405 WHERE conf_sortindex = '321' AND group_name = 'cfg_client' AND key1 = 'css_checking';
UPDATE cms_values SET conf_sortindex = 410 WHERE conf_sortindex = '322' AND group_name = 'cfg_client' AND key1 = 'css_ignore_rules_with_errors';
UPDATE cms_values SET conf_sortindex = 105 WHERE conf_sortindex = '100' AND group_name = 'cfg' AND key1 = 'gzip';
UPDATE cms_values SET conf_sortindex = 100, conf_head_langstring = NULL WHERE conf_sortindex = '200' AND group_name = 'cfg' AND key1 = 'cms_path';
UPDATE cms_values SET conf_sortindex = 101 WHERE conf_sortindex = '201' AND group_name = 'cfg' AND key1 = 'cms_html_path';
UPDATE cms_values SET value='deutsch', conf_sortindex = 102, conf_head_langstring = NULL WHERE group_name = 'cfg' AND key1 = 'backend_lang';
UPDATE cms_values SET conf_sortindex = 104 WHERE conf_sortindex = '101' AND group_name = 'cfg' AND key1 = 'backend_cache';
UPDATE cms_values SET conf_sortindex = 106 WHERE conf_sortindex = '102' AND group_name = 'cfg' AND key1 = 'manipulate_output';
UPDATE cms_values SET conf_sortindex = 301 WHERE conf_sortindex = '105' AND group_name = 'cfg' AND key1 = 'chmod_enabled';
UPDATE cms_values SET conf_sortindex = 300, conf_head_langstring = 'set_filebrowser' WHERE conf_sortindex = '110' AND group_name = 'cfg' AND key1 = 'chmod_value';
DELETE FROM cms_values WHERE group_name = 'cfg_client' AND key1 = 'log_enabled';
DELETE FROM cms_values WHERE group_name = 'cfg_client' AND key1 = 'log_prunetime';
DELETE FROM cms_values WHERE group_name = 'cfg_client' AND key1 = 'log_entries';
DELETE FROM cms_perms WHERE permclass = 'backend' AND permcat = 'intern' AND permsubcat = '0' AND perm = 'invert_cats';
DELETE FROM cms_perms WHERE permclass = 'backend' AND permcat = 'intern' AND permsubcat = '0' AND perm = 'invert_sides';
ALTER TABLE `cms_cat_side` CHANGE `sortindex` `sortindex` INT(6) UNSIGNED NOT NULL;
ALTER TABLE `cms_code` CHANGE `code` `code` MEDIUMTEXT NOT NULL;
ALTER TABLE `cms_content` CHANGE `value` `value` TEXT NOT NULL;
ALTER TABLE `cms_content_external` CHANGE `value` `value` TEXT NOT NULL;
ALTER TABLE `cms_cat` DROP INDEX `idcat`;
ALTER TABLE `cms_cat_lang` DROP INDEX `idcatnames`;
ALTER TABLE `cms_cat_side` DROP INDEX `id`;
ALTER TABLE `cms_clients` DROP INDEX `idclient`;
ALTER TABLE `cms_clients_lang` DROP INDEX `id`;
ALTER TABLE `cms_code` DROP INDEX `idcode`;
ALTER TABLE `cms_container` DROP INDEX `idtpl`;
ALTER TABLE `cms_container_conf` DROP INDEX `idtpl`;
ALTER TABLE `cms_content` DROP INDEX `idcontent`;
ALTER TABLE `cms_content_external` DROP INDEX `idcontent`;
ALTER TABLE `cms_content_external` DROP INDEX `idtype`;
ALTER TABLE `cms_css` DROP INDEX `idstyle`;
ALTER TABLE `cms_lang` DROP INDEX `idlang`;
ALTER TABLE `cms_lay` DROP INDEX `idmod`;
ALTER TABLE `cms_mod` DROP INDEX `idmod`;
ALTER TABLE `cms_perms` DROP INDEX `permclass_2`;
ALTER TABLE `cms_side` DROP INDEX `idside`;
ALTER TABLE `cms_side_lang` DROP INDEX `idsidelang`;
ALTER TABLE `cms_tpl` DROP INDEX `idtpl`;
ALTER TABLE `cms_tpl_conf` DROP INDEX `idtpl`;
ALTER TABLE `cms_type` DROP INDEX `idtype`;
ALTER TABLE `cms_upl` DROP INDEX `idupl`;

# Update des Rechtesystems 16.10.2003
ALTER TABLE `cms_users_groups` CHANGE `group_id` `idgroup` INT(11) DEFAULT '0' NOT NULL;
ALTER TABLE `cms_groups` DROP `personal_user`;
ALTER TABLE `cms_groups` DROP `backend`;
ALTER TABLE `cms_groups` DROP `frontend`;
ALTER TABLE `cms_groups` CHANGE `group_id` `idgroup` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `cms_groups` CHANGE `group_name` `name` VARCHAR(255) NOT NULL;
ALTER TABLE `cms_groups` CHANGE `group_desc` `description` TEXT DEFAULT NULL;
ALTER TABLE `cms_perms` DROP `client_id`;
ALTER TABLE `cms_perms` CHANGE `perm_id` `idperm` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `cms_perms` CHANGE `group_id` `idgroup` INT(11) DEFAULT '0' NOT NULL;
ALTER TABLE `cms_perms` CHANGE `lang_id` `idlang` INT(11) DEFAULT '0' NOT NULL;
ALTER TABLE `cms_perms` CHANGE `permcat` `type` VARCHAR(63) NOT NULL;
ALTER TABLE `cms_perms` CHANGE `permsubcat` `id` VARCHAR(63) DEFAULT '0' NOT NULL;
UPDATE cms_perms SET id = perm, perm = '0' WHERE type IN ('side','cat') AND id = '0';
UPDATE cms_perms SET idlang = '0' WHERE type IN ('side','cat') AND idgroup = '3';
UPDATE cms_perms SET id = perm, perm = '1' WHERE type='lang' AND id = '0';
#1 noch in maximalzahl ‰ndern
UPDATE cms_perms SET type = perm, perm = '8388607' WHERE perm = 'area_con';
UPDATE cms_perms SET type = perm, perm = '65535' WHERE perm = 'area_upl';
UPDATE cms_perms SET type = perm, perm = '127' WHERE perm = 'area_lay';
UPDATE cms_perms SET type = perm, perm = '1' WHERE perm = 'area_css';
UPDATE cms_perms SET type = perm, perm = '1' WHERE perm = 'area_js';
UPDATE cms_perms SET type = perm, perm = '1023' WHERE perm = 'area_mod';
UPDATE cms_perms SET type = perm, perm = '1' WHERE perm = 'area_tpl';
UPDATE cms_perms SET type = perm, perm = '1' WHERE perm = 'area_settings';
UPDATE cms_perms SET type = 'area_clients', perm = '1' WHERE perm = 'area_lang';
UPDATE cms_perms SET type = 'area_group', perm = '1' WHERE perm = 'area_groups';
DELETE FROM cms_perms WHERE perm IN ('frontend_edit', 'area_con_popup', 'area_con_popup_extended', 'area_con_configcat', 'area_con_cat_new', 'area_con_configcat_tpl', 'area_con_cat_delete', 'area_con_configside', 'area_con_config_side_new', 'area_con_config_side_protect', 'area_con_config_side_move', 'area_con_config_side_meta', 'area_con_config_side_tpl_choose', 'area_con_configside_tpl', 'area_con_edit', 'area_con_startside', 'area_con_delete_side', 'area_con_sort', 'area_con_publish', 'area_upl_edit_folder', 'area_upl_scan_folder', 'area_upl_scan_rootfolder', 'area_upl_edit_file', 'editfile_lastmodified', 'editfile_created', 'editfile_editor', 'area_upl_upload_file', 'area_lay_edit', 'area_lay_import', 'area_lay_export', 'area_css_import', 'area_css_export', 'area_css_edit', 'editcss_lastmodified', 'editcss_created', 'editcss_editor', 'area_css_edit_file', 'editcssfile_lastmodified', 'editcssfile_created', 'area_js_import', 'area_js_export', 'area_js_edit_file', 'editjsfile_lastmodified', 'editjsfile_created', 'editjsfile_editor', 'area_mod_edit', 'area_mod_conf', 'area_mod_download', 'area_mod_upload', 'area_mod_import', 'area_mod_export', 'area_mod_delete', 'area_tpl_edit', 'area_groups_edit');
UPDATE cms_perms SET type = 'plugin', id = perm, perm = '1' WHERE type IN ('general', 'plugin') AND permclass= 'backend';
DELETE FROM cms_perms WHERE type = 'intern' AND permclass= 'backend';
ALTER TABLE `cms_perms` DROP `permclass`;


# Usertabelle erweitern 31.10.2003
ALTER TABLE `cms_users` ADD `firm` VARCHAR(32) NOT NULL default '';
ALTER TABLE `cms_users` ADD `position` VARCHAR(32) NOT NULL default '';
ALTER TABLE `cms_users` ADD `salutation` VARCHAR(32) NOT NULL default '';
ALTER TABLE `cms_users` ADD `street` VARCHAR(255) NOT NULL default '';
ALTER TABLE `cms_users` ADD `zip` varchar(15) default NULL;
ALTER TABLE `cms_users` ADD `location` VARCHAR(32) NOT NULL default '';
ALTER TABLE `cms_users` ADD `phone` VARCHAR(32) NOT NULL default '';
ALTER TABLE `cms_users` ADD `fax` VARCHAR(32) NOT NULL default '';
ALTER TABLE `cms_users` ADD `comment` VARCHAR(255) NOT NULL default '';

#Neu hinzugekommen f¸r Administration->Projekte
ALTER TABLE `cms_lang` ADD `description` VARCHAR( 255 ) AFTER `name` ;
ALTER TABLE `cms_lang` ADD `charset` VARCHAR( 255 ) AFTER `description` ;
ALTER TABLE `cms_clients` ADD `description` VARCHAR( 255 ) AFTER `name` ;

# chmod-enabled umstellen auf 0/1 statt false/true
UPDATE cms_values SET value = '0' WHERE value = 'false' AND group_name = 'cfg' AND key1 = 'chmod_enabled';
UPDATE cms_values SET value = '1' WHERE value = 'true' AND group_name = 'cfg' AND key1 = 'chmod_enabled';

# mod expansion by STam
ALTER TABLE `cms_mod` ADD `repository_id` VARCHAR( 16 ) ,ADD `install_sql` TEXT,ADD `uninstall_sql` TEXT,ADD `update_sql` TEXT;

#pluginsystem by STam
DELETE FROM cms_backendmenu WHERE entry_langstring = 'nav_3_5' AND entry_url = 'main.php?area=plug&idclient=$client';
INSERT INTO cms_backendmenu (idbackendmenu, parent, idclient, sortindex, entry_langstring, entry_url, url_target, entry_validate) VALUES("", "3", "0", "50", "nav_3_5", "main.php?area=plug&idclient=$client", "single", "$perm->have_perm(\'area_plug\')");
CREATE TABLE cms_plug (
  idplug int(6) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  version varchar(63) default NULL,
  cat varchar(63) default NULL,
  description text,
  config text,
  idclient int(6) unsigned NOT NULL default '0',
  root_name varchar(150) default NULL,
  index_file varchar(50) default NULL,
  deletable tinyint(1) NOT NULL default '1',
  author int(6) NOT NULL default '0',
  created int(10) NOT NULL default '0',
  lastmodified int(10) NOT NULL default '0',
  repository_id varchar(16) default NULL,
  source_id int(6) unsigned NOT NULL default '0',
  is_install enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (idplug)
) ENGINE=MyISAM;
CREATE TABLE cms_repository (
  idrepository int(6) unsigned NOT NULL auto_increment,
  source int(6) unsigned NOT NULL default '0',
  idclient int(6) NOT NULL default '0',
  type varchar(25) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY  (idrepository),
  KEY idclient (idclient)
) ENGINE=MyISAM;


# 24.11. usertable modification by symap
ALTER TABLE `cms_users` ADD `street_alt` VARCHAR(255) ;
ALTER TABLE `cms_users` ADD `state` VARCHAR(255) ;
ALTER TABLE `cms_users` ADD `country` VARCHAR(255);
ALTER TABLE `cms_users` ADD `mobile` VARCHAR(50) ;
ALTER TABLE `cms_users` ADD `pager` VARCHAR(50) ;
ALTER TABLE `cms_users` ADD `homepage` VARCHAR(255);
ALTER TABLE `cms_users` ADD `birthday` VARCHAR(15) ;
ALTER TABLE `cms_users` ADD `firm_street` VARCHAR(255);
ALTER TABLE `cms_users` ADD `firm_street_alt` VARCHAR(255);
ALTER TABLE `cms_users` ADD `firm_zip` VARCHAR(15);
ALTER TABLE `cms_users` ADD `firm_location` VARCHAR(255);
ALTER TABLE `cms_users` ADD `firm_state` VARCHAR(255);
ALTER TABLE `cms_users` ADD `firm_country` VARCHAR(255);
ALTER TABLE `cms_users` ADD `firm_email` VARCHAR(255);
ALTER TABLE `cms_users` ADD `firm_phone` VARCHAR(50);
ALTER TABLE `cms_users` ADD `firm_fax` VARCHAR(50);
ALTER TABLE `cms_users` ADD `firm_mobile` VARCHAR(50);
ALTER TABLE `cms_users` ADD `firm_pager` VARCHAR(50);
ALTER TABLE `cms_users` ADD `firm_homepage` VARCHAR(255);

# 17.12.2003
#Alte Rechte lˆschen
DELETE FROM cms_values WHERE group_name = 'perms_backend_general' OR group_name = 'perms_backend_plugin';

# 03.12.2003 - Sortierung der perms
DELETE FROM cms_values WHERE group_name = 'user_perms';
# 17.12.03 Verk¸pfung rechte - sprachstrings f¸r Gruppen-> Rechte bearbeiten

# user perms dateimanager
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '1', NULL, NULL, '1', 10, 'group_area_upl_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '2', NULL, NULL, '2', 20, 'group_area_upl_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '3', NULL, NULL, '4', 30, 'group_area_upl_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '5', NULL, NULL, '16', 40, 'group_area_upl_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '6', NULL, NULL, '32', 50, 'group_area_upl_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '8', NULL, NULL, '128', 60, 'group_area_upl_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '9', NULL, NULL, '256', 70, 'group_area_upl_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '11', NULL, NULL, '1024', 80, 'group_area_upl_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '17', NULL, NULL, '65536', 90, 'group_area_upl_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '19', NULL, NULL, '262144', 100, 'group_area_upl_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '21', NULL, NULL, '1048576', 110, 'group_area_upl_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '22', NULL, NULL, '2097152', 120, 'group_area_upl_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '24', NULL, NULL, '8388608', 130, 'group_area_upl_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_upl', '25', NULL, NULL, '16777216', 140, 'group_area_upl_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '1', NULL, NULL, '1', 10, 'group_area_upl_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '2', NULL, NULL, '2', 20, 'group_area_upl_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '3', NULL, NULL, '4', 30, 'group_area_upl_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '5', NULL, NULL, '16', 40, 'group_area_upl_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '6', NULL, NULL, '32', 50, 'group_area_upl_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '8', NULL, NULL, '128', 60, 'group_area_upl_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '9', NULL, NULL, '256', 70, 'group_area_upl_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '11', NULL, NULL, '1024', 80, 'group_area_upl_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '17', NULL, NULL, '65536', 90, 'group_area_upl_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '19', NULL, NULL, '262144', 100, 'group_area_upl_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '21', NULL, NULL, '1048576', 110, 'group_area_upl_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '22', NULL, NULL, '2097152', 120, 'group_area_upl_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '24', NULL, NULL, '8388608', 130, 'group_area_upl_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'folder', '25', NULL, NULL, '16777216', 140, 'group_area_upl_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '17', NULL, NULL, '65536', 10, 'group_area_upl_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '19', NULL, NULL, '262144', 20, 'group_area_upl_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '21', NULL, NULL, '1048576', 30, 'group_area_upl_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '22', NULL, NULL, '2097152', 40, 'group_area_upl_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '24', NULL, NULL, '8388608', 50, 'group_area_upl_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'file', '25', NULL, NULL, '16777216', 60, 'group_area_upl_25', '', 'txt', NULL, NULL, 0);
# user perms layout
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '1', NULL, NULL, '1', 10, 'group_area_lay_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '2', NULL, NULL, '2', 20, 'group_area_lay_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '3', NULL, NULL, '4', 30, 'group_area_lay_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '5', NULL, NULL, '16', 50, 'group_area_lay_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '6', NULL, NULL, '32', 60, 'group_area_lay_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '7', NULL, NULL, '64', 70, 'group_area_lay_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_lay', '8', NULL, NULL, '128', 80, 'group_area_lay_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '1', NULL, NULL, '1', 10, 'group_lay_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '3', NULL, NULL, '4', 40, 'group_lay_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '5', NULL, NULL, '16', 50, 'group_lay_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '6', NULL, NULL, '32', 60, 'group_lay_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '7', NULL, NULL, '64', 70, 'group_lay_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'lay', '8', NULL, NULL, '128', 80, 'group_lay_8', '', 'txt', NULL, NULL, 0);
# user perms frontend
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_frontend', '2', NULL, NULL, '2', 0, 'group_area_frontend_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_frontend', '3', NULL, NULL, '4', 0, 'group_area_frontend_3', '', 'txt', NULL, NULL, 0);
# user perms backend
# nothing yet
# user perms css
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '1', NULL, NULL, '1', 10, 'group_area_css_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '2', NULL, NULL, '2', 20, 'group_area_css_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '3', NULL, NULL, '4', 30, 'group_area_css_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '5', NULL, NULL, '16', 40, 'group_area_css_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '6', NULL, NULL, '32', 50, 'group_area_css_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '8', NULL, NULL, '128', 60, 'group_area_css_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '9', NULL, NULL, '256', 70, 'group_area_css_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '13', NULL, NULL, '4096', 80, 'group_area_css_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '14', NULL, NULL, '8192', 90, 'group_area_css_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '17', NULL, NULL, '65536', 100, 'group_area_css_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '18', NULL, NULL, '131072', 110, 'group_area_css_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '19', NULL, NULL, '262144', 120, 'group_area_css_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '21', NULL, NULL, '1048576', 130, 'group_area_css_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '22', NULL, NULL, '2097152', 140, 'group_area_css_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '29', NULL, NULL, '268435456', 150, 'group_area_css_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_css', '30', NULL, NULL, '536870912', 160, 'group_area_css_30', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '1', NULL, NULL, '1', 10, 'group_area_css_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '2', NULL, NULL, '2', 20, 'group_area_css_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '3', NULL, NULL, '4', 30, 'group_area_css_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '5', NULL, NULL, '16', 40, 'group_area_css_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '6', NULL, NULL, '32', 50, 'group_area_css_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '8', NULL, NULL, '128', 60, 'group_area_css_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '9', NULL, NULL, '256', 70, 'group_area_css_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '13', NULL, NULL, '4096', 80, 'group_area_css_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '14', NULL, NULL, '8192', 90, 'group_area_css_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '17', NULL, NULL, '65536', 100, 'group_area_css_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '18', NULL, NULL, '131072', 110, 'group_area_css_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '19', NULL, NULL, '262144', 120, 'group_area_css_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '21', NULL, NULL, '1048576', 130, 'group_area_css_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '22', NULL, NULL, '2097152', 140, 'group_area_css_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '29', NULL, NULL, '268435456', 150, 'group_area_css_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'css_file', '30', NULL, NULL, '536870912', 160, 'group_area_css_30', '', 'txt', NULL, NULL, 0);
# user perms mod
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '1', NULL, NULL, '1', 10, 'group_area_mod_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '2', NULL, NULL, '2', 20, 'group_area_mod_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '3', NULL, NULL, '4', 30, 'group_area_mod_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '5', NULL, NULL, '16', 50, 'group_area_mod_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '6', NULL, NULL, '32', 60, 'group_area_mod_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '7', NULL, NULL, '64', 70, 'group_area_mod_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '8', NULL, NULL, '128', 80, 'group_area_mod_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '9', NULL, NULL, '256', 90, 'group_area_mod_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '10', NULL, NULL, '512', 100, 'group_area_mod_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '11', NULL, NULL, '1024', 110, 'group_area_mod_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '12', NULL, NULL, '2048', 120, 'group_area_mod_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '13', NULL, NULL, '4096', 130, 'group_area_mod_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '14', NULL, NULL, '8192', 140, 'group_area_mod_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_mod', '15', NULL, NULL, '16384', 150, 'group_area_mod_15', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '1', NULL, NULL, '1', 10, 'group_mod_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '3', NULL, NULL, '4', 40, 'group_mod_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '5', NULL, NULL, '16', 50, 'group_mod_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '6', NULL, NULL, '32', 60, 'group_mod_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '7', NULL, NULL, '64', 70, 'group_mod_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '8', NULL, NULL, '128', 80, 'group_mod_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '10', NULL, NULL, '512', 100, 'group_mod_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'mod', '12', NULL, NULL, '2048', 120, 'group_mod_12', '', 'txt', NULL, NULL, 0);
# user perms js
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '1', NULL, NULL, '1', 10, 'group_area_js_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '2', NULL, NULL, '2', 20, 'group_area_js_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '3', NULL, NULL, '4', 30, 'group_area_js_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '5', NULL, NULL, '16', 40, 'group_area_js_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '6', NULL, NULL, '32', 50, 'group_area_js_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '8', NULL, NULL, '128', 60, 'group_area_js_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '9', NULL, NULL, '256', 70, 'group_area_js_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '13', NULL, NULL, '4096', 80, 'group_area_js_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_js', '14', NULL, NULL, '8192', 90, 'group_area_js_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '1', NULL, NULL, '1', 10, 'group_area_js_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '2', NULL, NULL, '2', 20, 'group_area_js_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '3', NULL, NULL, '4', 30, 'group_area_js_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '5', NULL, NULL, '16', 40, 'group_area_js_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '6', NULL, NULL, '32', 50, 'group_area_js_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '8', NULL, NULL, '128', 60, 'group_area_js_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '9', NULL, NULL, '256', 70, 'group_area_js_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '13', NULL, NULL, '4096', 80, 'group_area_js_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'js_file', '14', NULL, NULL, '8192', 90, 'group_area_js_14', '', 'txt', NULL, NULL, 0);

#14.12. update dateiabgleich
DELETE FROM cms_values WHERE group_name = 'cfg_client' AND key1 IN ('max_count_scandir', 'extend_time_scandir', 'max_count_scanfile', 'max_count_scanthumb');
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'max_count_scandir', '', '', '', '10', 0, '', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'extend_time_scandir', '', '', '', '60', 0, '', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'max_count_scanfile', '', '', '', '2', 0, '', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'max_count_scanthumb', '', '', '', '10', 0, '', '', 'txt', NULL, NULL, 0);

#17.12. neues feld fuer css_upl, sortierung der regeln
ALTER TABLE `cms_css_upl` ADD `intSort` INT(4) DEFAULT '0' NOT NULL;

#18.12. update filetypes: andere icons und mime-types nach rfc
DELETE FROM cms_filetype;
INSERT INTO cms_filetype VALUES (1, 'css', 'Cascading Style Sheet', 'css.gif', 1, 'Style', 'text/css', '', 1, 20030610005436, 20030305185033);
INSERT INTO cms_filetype VALUES (2, 'js', 'Javascript', 'unknown.gif', 1, 'Script', 'text/javascript', '', 1, 20030610005454, 20030305185136);
INSERT INTO cms_filetype VALUES (3, 'jpg', 'JPEG', 'jpg.gif', 0, 'Bilder', 'image/jpeg', 'width,height', 1, 20030610005505, 20030305185214);
INSERT INTO cms_filetype VALUES (4, 'gif', 'GIF', 'gif.gif', 0, 'Bilder', 'image/gif', 'width,height', 1, 20030610005535, 20030305185237);
INSERT INTO cms_filetype VALUES (5, 'php', 'PHP-Script', 'php.gif', 1, 'Text', 'text/plain', '', 1, 20030609232705, 20030330162104);
INSERT INTO cms_filetype VALUES (6, 'html', 'HTML-Dokument', 'html.gif', 1, 'Text', 'text/html', '', 1, 20030610005521, 20030330162104);
INSERT INTO cms_filetype VALUES (7, 'pdf', 'Acrobat PDF-Dokument', 'pdf.gif', 0, 'Asset', 'application/pdf', '', 1, 20030609234945, 20030330162125);
INSERT INTO cms_filetype VALUES (8, 'ico', 'Icon', 'image.gif', 1, 'Bilder', 'image/vnd.microsoft.icon', '', 1, 20030609232618, 20030330180911);
INSERT INTO cms_filetype VALUES (9, 'txt', 'Textdatei', 'txt.gif', 0, 'Text', 'text/plain', '', 1, 20030610005552, 20030330180911);
INSERT INTO cms_filetype VALUES (10, 'zip', 'ZIP-Archiv', 'zip.gif', 0, 'Asset', 'application/zip', '', 1, 20030610004229, 20030516004100);
INSERT INTO cms_filetype VALUES (11, 'htm', 'HTML-Dokument', 'html.gif', 0, 'Text', 'text/html', '', 1, 20030610005527, 20030519000527);
INSERT INTO cms_filetype VALUES (12, 'png', 'Portable Network Graphic', 'png.gif', 0, 'Bilder', 'image/png', 'width,height', 1, 20030610005605, 20030601003713);
INSERT INTO cms_filetype VALUES (13, 'jpeg', 'JPEG', 'jpg.gif', 0, 'Bilder', 'image/jpeg', 'width,height', 1, 20030610005510, 20030305185214);
INSERT INTO cms_filetype VALUES (14, 'doc', 'Word-Dokument', 'word.gif', 0, 'Text', 'application/msword', '', 1, 20030610004315, 20030610004315);
INSERT INTO cms_filetype VALUES (15, 'xls', 'Excel-Dokument', 'xls.gif', 0, 'Asset', 'application/vnd.ms-excel', '', 1, 20030610004352, 20030610004352);
INSERT INTO cms_filetype VALUES (16, 'vsd', 'Visio-Zeichnung', 'unknown.gif', 0, 'Asset', 'application/vnd.visio', '', 1, 20030610004424, 20030610004424);
INSERT INTO cms_filetype VALUES (17, 'ppt', 'PowerPoint-Pr‰sentation', 'ppt.gif', 0, 'Asset', 'application/vnd.ms-powerpoint', '', 1, 20030610004533, 20030610004533);

#20.12. neuer config wert zum ersetzen von gef‰hrlichen zeichen in datei- oder verzeichnisnamen
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'trouble_chars', '', '', '', '‰ˆ¸ﬂÈË·‡<>|+', 0, 'set_trouble_chars', 'set_filemanager', 'txt', NULL, NULL, 0);

#28.12. Neue Rechte area_tpl
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 IN ('area_tpl', 'tpl');
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 IN ('cms_access') AND key2 IN ('area_tpl');
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_tpl', NULL, NULL, '0', 0, 'group_area_tpl', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '1', NULL, NULL, '1', 10, 'group_area_tpl_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '2', NULL, NULL, '2', 20, 'group_area_tpl_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '3', NULL, NULL, '4', 30, 'group_area_tpl_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '5', NULL, NULL, '16', 50, 'group_area_tpl_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_tpl', '6', NULL, NULL, '32', 60, 'group_area_tpl_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '1', NULL, NULL, '1', 10, 'group_tpl_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '3', NULL, NULL, '4', 40, 'group_tpl_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '5', NULL, NULL, '16', 50, 'group_tpl_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'tpl', '6', NULL, NULL, '32', 60, 'group_tpl_6', '', 'txt', NULL, NULL, 0);

#28.12. Neue Rechte area_clients
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 IN ('area_clients', 'clients', 'clientlangs');
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 IN ('cms_access') AND key2 IN ('area_clients');
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_clients', NULL, NULL, '0', 0, 'group_area_clients', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '1', NULL, NULL, '1', 10, 'group_area_clients_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '2', NULL, NULL, '2', 20, 'group_area_clients_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '3', NULL, NULL, '4', 30, 'group_area_clients_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '4', NULL, NULL, '8', 40, 'group_area_clients_4', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '5', NULL, NULL, '16', 50, 'group_area_clients_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '6', NULL, NULL, '32', 60, 'group_area_clients_6', '', 'txt', NULL, NULL, 0);

INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '17', NULL, NULL, '65536', 170, 'group_area_clients_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '18', NULL, NULL, '131072', 180, 'group_area_clients_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '19', NULL, NULL, '262144', 190, 'group_area_clients_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '21', NULL, NULL, '1048576', 210, 'group_area_clients_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_clients', '22', NULL, NULL, '2097152', 220, 'group_area_clients_22', '', 'txt', NULL, NULL, 0);


INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '1', NULL, NULL, '1', 10, 'group_clients_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '3', NULL, NULL, '4', 30, 'group_clients_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '4', NULL, NULL, '8', 40, 'group_clients_4', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '5', NULL, NULL, '16', 50, 'group_clients_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '6', NULL, NULL, '32', 60, 'group_clients_6', '', 'txt', NULL, NULL, 0);

INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '17', NULL, NULL, '65536', 170, 'group_clients_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '18', NULL, NULL, '131072', 180, 'group_clients_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '19', NULL, NULL, '262144', 190, 'group_clients_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '21', NULL, NULL, '1048576', 210, 'group_clients_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clients', '22', NULL, NULL, '2097152', 220, 'group_clients_22', '', 'txt', NULL, NULL, 0);


INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '17', NULL, NULL, '65536', 170, 'group_clientlangs_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '19', NULL, NULL, '262144', 190, 'group_clientlangs_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '21', NULL, NULL, '1048576', 210, 'group_clientlangs_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'clientlangs', '22', NULL, NULL, '2097152', 220, 'group_clientlangs_22', '', 'txt', NULL, NULL, 0);

#28.12. Neue Rechte area_con
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 IN ('area_con', 'cat', 'side');
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 IN ('cms_access') AND key2 IN ('area_con');
#area_con
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_con', NULL, NULL, '0', 0, 'group_area_con', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '1', NULL, NULL, '1', 10, 'group_area_con_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '2', NULL, NULL, '2', 20, 'group_area_con_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '3', NULL, NULL, '4', 30, 'group_area_con_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '5', NULL, NULL, '16', 50, 'group_area_con_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '6', NULL, NULL, '32', 60, 'group_area_con_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '7', NULL, NULL, '64', 70, 'group_area_con_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '8', NULL, NULL, '128', 80, 'group_area_con_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '9', NULL, NULL, '256', 90, 'group_area_con_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '11', NULL, NULL, '1024', 110, 'group_area_con_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '17', NULL, NULL, '65536', 170, 'group_area_con_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '18', NULL, NULL, '131072', 180, 'group_area_con_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '19', NULL, NULL, '262144', 190, 'group_area_con_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '20', NULL, NULL, '524288', 200, 'group_area_con_20', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '21', NULL, NULL, '1048576', 210, 'group_area_con_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '22', NULL, NULL, '2097152', 220, 'group_area_con_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '23', NULL, NULL, '4194304', 230, 'group_area_con_23', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '24', NULL, NULL, '8388608', 240, 'group_area_con_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '25', NULL, NULL, '16777216', 250, 'group_area_con_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '26', NULL, NULL, '33554432', 260, 'group_area_con_26', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '27', NULL, NULL, '67108864', 270, 'group_area_con_27', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '28', NULL, NULL, '134217728', 280, 'group_area_con_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '29', NULL, NULL, '268435456', 290, 'group_area_con_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '30', NULL, NULL, '536870912', 300, 'group_area_con_30', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '31', NULL, NULL, '1073741824', 310, 'group_area_con_31', '', 'txt', NULL, NULL, 0);
#cat
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '1', NULL, NULL, '1', 10, 'group_cat_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '2', NULL, NULL, '2', 20, 'group_cat_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '3', NULL, NULL, '4', 30, 'group_cat_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '5', NULL, NULL, '16', 50, 'group_cat_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '6', NULL, NULL, '32', 60, 'group_cat_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '7', NULL, NULL, '64', 70, 'group_cat_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '8', NULL, NULL, '128', 80, 'group_cat_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '9', NULL, NULL, '256', 90, 'group_cat_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '11', NULL, NULL, '1024', 110, 'group_cat_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '17', NULL, NULL, '65536', 170, 'group_cat_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '18', NULL, NULL, '131072', 180, 'group_cat_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '19', NULL, NULL, '262144', 190, 'group_cat_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '20', NULL, NULL, '524288', 200, 'group_cat_20', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '21', NULL, NULL, '1048576', 210, 'group_cat_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '22', NULL, NULL, '2097152', 220, 'group_cat_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '23', NULL, NULL, '4194304', 230, 'group_cat_23', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '24', NULL, NULL, '8388608', 240, 'group_cat_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '25', NULL, NULL, '16777216', 250, 'group_cat_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '26', NULL, NULL, '33554432', 260, 'group_cat_26', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '27', NULL, NULL, '67108864', 270, 'group_cat_27', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '28', NULL, NULL, '134217728', 280, 'group_cat_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '29', NULL, NULL, '268435456', 290, 'group_cat_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '30', NULL, NULL, '536870912', 300, 'group_cat_30', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '31', NULL, NULL, '1073741824', 310, 'group_cat_31', '', 'txt', NULL, NULL, 0);
#side
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '17', NULL, NULL, '65536', 170, 'group_side_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '19', NULL, NULL, '262144', 190, 'group_side_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '20', NULL, NULL, '524288', 200, 'group_side_20', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '21', NULL, NULL, '1048576', 210, 'group_side_21', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '22', NULL, NULL, '2097152', 220, 'group_side_22', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '23', NULL, NULL, '4194304', 230, 'group_side_23', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '24', NULL, NULL, '8388608', 240, 'group_side_24', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '25', NULL, NULL, '16777216', 250, 'group_side_25', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '26', NULL, NULL, '33554432', 260, 'group_side_26', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '27', NULL, NULL, '67108864', 270, 'group_side_27', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '28', NULL, NULL, '134217728', 280, 'group_side_28', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '29', NULL, NULL, '268435456', 290, 'group_side_29', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '30', NULL, NULL, '536870912', 300, 'group_side_30', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '31', NULL, NULL, '1073741824', 310, 'group_side_31', '', 'txt', NULL, NULL, 0);

# user perms plug
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '1', NULL, NULL, '1', 10, 'group_area_plug_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '2', NULL, NULL, '2', 20, 'group_area_plug_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '3', NULL, NULL, '4', 30, 'group_area_plug_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '4', NULL, NULL, '8', 40, 'group_area_plug_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '5', NULL, NULL, '16', 50, 'group_area_plug_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '6', NULL, NULL, '32', 60, 'group_area_plug_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '7', NULL, NULL, '64', 70, 'group_area_plug_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '8', NULL, NULL, '128', 80, 'group_area_plug_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '9', NULL, NULL, '256', 90, 'group_area_plug_9', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '10', NULL, NULL, '512', 100, 'group_area_plug_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '11', NULL, NULL, '1024', 110, 'group_area_plug_11', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '12', NULL, NULL, '2048', 120, 'group_area_plug_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '13', NULL, NULL, '4096', 130, 'group_area_plug_13', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '14', NULL, NULL, '8192', 140, 'group_area_plug_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '15', NULL, NULL, '16384', 150, 'group_area_plug_15', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '16', NULL, NULL, '32768', 160, 'group_area_plug_16', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '17', NULL, NULL, '65536', 170, 'group_area_plug_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_plug', '18', NULL, NULL, '131072', 180, 'group_area_plug_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '1', NULL, NULL, '1', 10, 'group_plug_1', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '3', NULL, NULL, '4', 30, 'group_plug_3', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '4', NULL, NULL, '8', 40, 'group_plug_4', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '5', NULL, NULL, '16', 50, 'group_plug_5', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '6', NULL, NULL, '32', 60, 'group_plug_6', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '7', NULL, NULL, '64', 70, 'group_plug_7', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '8', NULL, NULL, '128', 80, 'group_plug_8', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '10', NULL, NULL, '512', 100, 'group_plug_10', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '12', NULL, NULL, '2048', 120, 'group_plug_12', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '16', NULL, NULL, '32768', 160, 'group_plug_16', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '17', NULL, NULL, '65536', 170, 'group_plug_17', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'plug', '18', NULL, NULL, '131072', 180, 'group_plug_18', '', 'txt', NULL, NULL, 0);

#28.12. Neue Rechte area_settings
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 IN ('area_settings');
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 IN ('cms_access') AND key2 IN ('area_settings');
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_settings', NULL, NULL, '0', 0, 'group_area_settings', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_settings', '1', NULL, NULL, '1', 10, 'group_area_settings_1', '', 'txt', NULL, NULL, 0);

#30.12 Einstellung der GD- Version
DELETE FROM cms_values WHERE group_name = 'cfg' AND key1 = 'gd_version';
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'gd_version', '', '', '', '2', 405, 'set_gd_version', '', 'txt', '', '', 1);

#08.01 Neue Modulfelder source_id und is_install hinzuf¸gen
ALTER TABLE `cms_mod` ADD `source_id` int(6) unsigned NOT NULL default '0' AFTER `update_sql`;
ALTER TABLE `cms_mod` ADD `is_install` enum('0','1') NOT NULL default '0' AFTER `source_id`;

#01.02 - Vererbung der perms
DELETE FROM cms_values WHERE group_name = 'user_perms' AND key1 = 'cms_access';
# zugriffsrechte cms_access, mit vererbungshierarchie
# user perms plug
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_frontend', NULL, NULL, '', 10, 'group_area_frontend', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_backend', NULL, NULL, '', 20, 'group_area_backend', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_con', NULL, NULL, 'cat,side', 30, 'group_area_con', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_upl', NULL, NULL, 'folder,file', 40, 'group_area_upl', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_lay', NULL, NULL, 'lay', 50, 'group_area_lay', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_css', NULL, NULL, 'css_file,css_rule', 60, 'group_area_css', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_js', NULL, NULL, 'js_file,js_func', 70, 'group_area_js', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_mod', NULL, NULL, 'mod', 80, 'group_area_mod', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_tpl', NULL, NULL, 'tpl', 90, 'group_area_tpl', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_clients', NULL, NULL, 'clients,clientslang', 100, 'group_area_clients', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_settings', NULL, NULL, '', 110, 'group_area_settings', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_plug', NULL, NULL, 'plug', 120, 'group_area_plug', '', 'txt', NULL, NULL, 0);

#04.02 backendmenu with proper perm-checks for cms_access
DROP TABLE IF EXISTS cms_backendmenu;
CREATE TABLE cms_backendmenu (
  idbackendmenu int(11) NOT NULL auto_increment,
  parent int(11) NOT NULL default '0',
  idclient int(11) NOT NULL default '0',
  sortindex int(11) default NULL,
  entry_langstring varchar(63) NOT NULL default '',
  entry_url varchar(255) NOT NULL default '',
  url_target enum('single','frame') NOT NULL default 'single',
  entry_validate varchar(255) default NULL,
  PRIMARY KEY  (idbackendmenu),
  KEY idclient (idclient)
) ENGINE=MyISAM;
INSERT INTO cms_backendmenu VALUES (1, 0, 0, 10, 'nav_1_0', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (2, 0, 0, 20, 'nav_2_0', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (3, 0, 0, 30, 'nav_3_0', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (4, 0, 0, 40, 'nav_4_0', 'root', 'single', '$perm->have_perm(\'area_plugin\')');
INSERT INTO cms_backendmenu VALUES (5, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (6, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (7, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (8, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (9, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (10, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (11, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (12, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (13, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (14, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (15, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (16, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (17, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (18, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (19, 0, 0, 40, 'empty_dummy', 'root', 'single', 'root');
INSERT INTO cms_backendmenu VALUES (20, 1, 0, 10, 'nav_1_1', 'main.php?area=con', 'single', '$perm->have_perm(\'area_con\')');
INSERT INTO cms_backendmenu VALUES (21, 1, 0, 32, 'nav_1_2', 'main.php?area=upl', 'single', '$perm->have_perm(\'area_upl\')');
INSERT INTO cms_backendmenu VALUES (22, 2, 0, 10, 'nav_2_1', 'main.php?area=lay&idclient=$client', 'single', '$perm->have_perm(\'area_lay\')');
INSERT INTO cms_backendmenu VALUES (23, 2, 0, 20, 'nav_2_2', 'main.php?area=css&idclient=$client', 'single', '$perm->have_perm(\'area_css\')');
INSERT INTO cms_backendmenu VALUES (24, 2, 0, 40, 'nav_2_4', 'main.php?area=mod&idclient=$client', 'single', '$perm->have_perm(\'area_mod\')');
INSERT INTO cms_backendmenu VALUES (25, 2, 0, 30, 'nav_2_3', 'main.php?area=js&idclient=$client', 'single', '$perm->have_perm(\'area_js\')');
INSERT INTO cms_backendmenu VALUES (26, 2, 0, 50, 'nav_2_5', 'main.php?area=tpl', 'single', '$perm->have_perm(\'area_tpl\')');
INSERT INTO cms_backendmenu VALUES (27, 3, 0, 10, 'nav_3_1', 'main.php?area=user', 'single', '$perm->is_admin()');
INSERT INTO cms_backendmenu VALUES (28, 3, 0, 20, 'nav_3_2', 'main.php?area=group', 'single', '$perm->have_perm(\'area_group\')');
INSERT INTO cms_backendmenu VALUES (29, 3, 0, 30, 'nav_3_3', 'main.php?area=clients', 'single', '$perm->have_perm(\'area_clients\')');
INSERT INTO cms_backendmenu VALUES (30, 3, 0, 40, 'nav_3_4', 'main.php?area=settings', 'single', '$perm->have_perm(\'area_settings\')');
INSERT INTO cms_backendmenu VALUES (31, 3, 0, 50, 'nav_3_5', 'main.php?area=plug&idclient=$client', 'single', '$perm->have_perm(\'area_plug\')');

# Alten Code entfernen und Tabellen optimieren
DELETE FROM cms_code;
OPTIMIZE TABLE `cms_backendmenu`;
OPTIMIZE TABLE `cms_cat`;
OPTIMIZE TABLE `cms_cat_expand`;
OPTIMIZE TABLE `cms_cat_lang`;
OPTIMIZE TABLE `cms_cat_side`;
OPTIMIZE TABLE `cms_clients`;
OPTIMIZE TABLE `cms_clients_lang`;
OPTIMIZE TABLE `cms_code`;
OPTIMIZE TABLE `cms_container`;
OPTIMIZE TABLE `cms_container_conf`;
OPTIMIZE TABLE `cms_content`;
OPTIMIZE TABLE `cms_content_external`;
OPTIMIZE TABLE `cms_css`;
OPTIMIZE TABLE `cms_css_upl`;
OPTIMIZE TABLE `cms_directory`;
OPTIMIZE TABLE `cms_filetype`;
OPTIMIZE TABLE `cms_groups`;
OPTIMIZE TABLE `cms_js`;
OPTIMIZE TABLE `cms_lang`;
OPTIMIZE TABLE `cms_lay`;
OPTIMIZE TABLE `cms_lay_upl`;
OPTIMIZE TABLE `cms_mod`;
OPTIMIZE TABLE `cms_perms`;
OPTIMIZE TABLE `cms_sessions`;
OPTIMIZE TABLE `cms_side`;
OPTIMIZE TABLE `cms_side_lang`;
OPTIMIZE TABLE `cms_tpl`;
OPTIMIZE TABLE `cms_tpl_conf`;
OPTIMIZE TABLE `cms_type`;
OPTIMIZE TABLE `cms_upl`;
OPTIMIZE TABLE `cms_uplcontent`;
OPTIMIZE TABLE `cms_users`;
OPTIMIZE TABLE `cms_users_groups`;
OPTIMIZE TABLE `cms_values`;
