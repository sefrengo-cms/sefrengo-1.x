# 09.01.2006 set new versionnumber - sefrengo 1.3 DEV
UPDATE cms_values  SET value =  '01.03.00' WHERE group_name =  'cfg' AND key1 =  'version';

# 09.01.2006 update for new rewriteurls/ frontendperms
DELETE FROM cms_values WHERE group_name = 'user_perms' AND `key1` IN ('cms_access') AND `key2` IN ('area_frontend');
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_frontend', NULL, NULL, 'frontendcat,frontendpage', 10, 'group_area_frontend', '', 'txt', NULL, NULL, 0);

DELETE FROM cms_values WHERE group_name = 'user_perms' AND `key1` IN ('area_con') AND `key2` IN ('group_area_con_14', 'group_area_con_15', 'group_area_con_31');
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '31', NULL, NULL, '1073741824', 310, 'group_area_con_31', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '14', NULL, NULL, '8192', 65, 'group_area_con_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_con', '15', NULL, NULL, '16384', 150, 'group_area_con_15', '', 'txt', NULL, NULL, 0);

DELETE FROM cms_values WHERE group_name = 'user_perms' AND `key1` IN ('cat') AND `key2` IN ('group_area_con_14', 'group_area_con_15', 'group_area_con_31');
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '31', NULL, NULL, '1073741824', 310, 'group_cat_31', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '14', NULL, NULL, '8192', 65, 'group_cat_14', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cat', '15', NULL, NULL, '16384', 150, 'group_cat_15', '', 'txt', NULL, NULL, 0);

DELETE FROM cms_values WHERE group_name = 'user_perms' AND `key1` IN ('side') AND `key2` IN ('31');
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'side', '31', NULL, NULL, '1073741824', 310, 'group_side_31', '', 'txt', NULL, NULL, 0);
DELETE FROM cms_values WHERE group_name = 'user_perms' AND `key1` IN ('area_frontend');
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_frontend', '2', NULL, NULL, '2', 20, 'group_area_frontend_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_frontend', '18', NULL, NULL, '131072', 180, 'group_area_frontend_18', '', 'txt', NULL, NULL, 0);
DELETE FROM cms_values WHERE group_name = 'user_perms' AND `key1` IN ('frontendcat', 'frontendpage');
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendcat', '2', NULL, NULL, '2', 20, 'group_frontendcat_2', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendcat', '18', NULL, NULL, '131072', 180, 'group_frontendcat_18', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendpage', '18', NULL, NULL, '131072', 180, 'group_frontendpage_18', '', 'txt', NULL, NULL, 0);

# 08.05.2006 change db_cache release field to relesetime
ALTER TABLE `cms_db_cache` CHANGE `release` `releasetime` VARCHAR( 14 ) NULL DEFAULT '0';

# 09.05.2006 values for new mod_rewrite
# cms_lang
# add rewrite_key, rewrite_mapping
ALTER TABLE `cms_lang` ADD `rewrite_key` VARCHAR( 255 ) NOT NULL AFTER `iso_3166_code` ,
ADD `rewrite_mapping` VARCHAR( 15 ) NOT NULL AFTER `rewrite_key` ;
ALTER TABLE `cms_lang` ADD INDEX ( `rewrite_key` ) ;

# cms_side_lang
# add rewrite_use_automatic, rewrite_url
ALTER TABLE `cms_side_lang` ADD `rewrite_use_automatic` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `edit_ttl` ,
ADD `rewrite_url` VARCHAR( 255 ) NOT NULL AFTER `rewrite_use_automatic` ;
ALTER TABLE `cms_side_lang` ADD INDEX ( `rewrite_use_automatic` , `rewrite_url` ) ;

# cms_cat_lang
# add rewrite_use_automatic, rewrite_alias
ALTER TABLE `cms_cat_lang` ADD `rewrite_use_automatic` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `description` ,
ADD `rewrite_alias` VARCHAR( 255 ) NOT NULL AFTER `rewrite_use_automatic` ;
ALTER TABLE `cms_cat_lang` ADD INDEX ( `rewrite_use_automatic` , `rewrite_alias` ) ;

# 10.05.2006 new backend_lang keys 
UPDATE cms_values  SET value =  'de' WHERE group_name =  'cfg' AND key1 =  'backend_lang' AND value =  'deutsch';
UPDATE cms_values  SET value =  'en' WHERE group_name =  'cfg' AND key1 =  'backend_lang' AND value =  'englisch';
UPDATE cms_values  SET value =  'fr' WHERE group_name =  'cfg' AND key1 =  'backend_lang' AND value =  'francais';

# 28.05.2006 backendmenu update dedi_plugin -> cms_plugin
UPDATE cms_backendmenu SET entry_url = REPLACE (entry_url, 'dedi_plugin', 'cms_plugin');
