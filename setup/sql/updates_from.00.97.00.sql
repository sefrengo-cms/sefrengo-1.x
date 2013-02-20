# 02.03 backendmenu rechte für plugins entfernen
UPDATE cms_backendmenu SET entry_validate = 'root' WHERE entry_langstring = 'nav_4_0' AND entry_validate = '$perm->have_perm(\'area_plugin\')';

# 02.03 alle Module löschbar machen
UPDATE cms_mod SET deletable='1' WHERE deletable='0';

# 02.03 doppelte Konfigurationseinträge entfernen
DELETE FROM cms_values WHERE group_name='cfg' AND key1='chmod_enabled';
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'chmod_enabled', '', '', '', '1', 301, 'set_chmod_enable', NULL, 'txt', NULL, NULL, 1);
DELETE FROM cms_values WHERE group_name='cfg_client' AND key1 IN ('errorpage', 'loginpage', 'cache') AND idclient='1';
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'errorpage', '', '', '', '0', 209, 'setuse_errorpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'loginpage', '', '', '', '0', 210, 'setuse_loginpage', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 1, 0, 'cfg_client', 'cache', '', '', '', '1', 211, 'setuse_cache', NULL, 'txt', NULL, NULL, 1);

# 13.03 Ordnerbeschreibung hinzugefügt
ALTER TABLE cms_cat_lang ADD `description` TEXT AFTER `name`;

# 15.03 Update Repository
UPDATE cms_values SET `value` = 'service.das-repository.de',`conf_input_type_val` = NULL ,`conf_input_type_langstring` = NULL WHERE `key1` = 'repository_server';
UPDATE cms_values SET `value` = '1',`conf_input_type_val` = NULL ,`conf_input_type_langstring` = NULL WHERE `key1` = 'repository_show_up2date';
ALTER TABLE cms_mod CHANGE `repository_id` `repository_id` VARCHAR( 255 ) DEFAULT NULL;
ALTER TABLE cms_plug CHANGE `repository_id` `repository_id` VARCHAR( 255 ) DEFAULT NULL;
UPDATE cms_mod SET `repository_id` = NULL;
UPDATE cms_plug SET `repository_id` = NULL;

# 18.03. filetypepict geändert
UPDATE `cms_filetype` SET filetypepict='unknown.gif' WHERE filetypepict='upl_unknown.gif';

# 18.03. db_cache aktivieren
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_enabled', '', '', '', '0', 800, 'set_db_cache_enabled', 'set_db_cache', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_name', '', '', '', 'db_cache', 805, 'set_db_cache_name', NULL, 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_groups', 'default', '', '', '60', 810, 'set_db_cache_group_default', 'set_db_cache_groups', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_groups', 'standard', '', '', '3600', 820, 'set_db_cache_group_standard', NULL, 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'db_cache_groups', 'frontend_tree', '', '', '15', 830, 'set_db_cache_group_frontend_tree', NULL, 'txt', NULL, NULL, 1);

# 18.03. db_cache tabelle einfügen
DROP TABLE IF EXISTS cms_db_cache;
CREATE TABLE cms_db_cache (
  sid varchar(32) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  val mediumtext,
  changed varchar(14) NOT NULL default '',
  release varchar(14) default '0',
  groups varchar(32) NOT NULL default '',
  PRIMARY KEY  (name,sid),
  KEY changed (changed)
) ENGINE=MyISAM;

#21.03.2004 correct permnames - toggle clientslang to clientlangs
UPDATE cms_values set value = 'clients,clientlangs' where group_name = 'user_perms' AND key1 ='cms_access' AND key2 = 'area_clients';

#24.03.2004 turn up sessions to mediumtext
ALTER TABLE `cms_sessions` CHANGE `val` `val` MEDIUMTEXT;




#26.03.2004 set new versionnumber
UPDATE cms_values  SET value =  '00.98.00' WHERE group_name =  'cfg' AND key1 =  'version';

#21.05.2004 alte rechte wegen neuen rechtemanagment löschen
DELETE FROM cms_perms;