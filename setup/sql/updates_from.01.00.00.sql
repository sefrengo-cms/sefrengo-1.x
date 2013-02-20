
# 08.08. cms_values für CSS-Test angepasst
UPDATE cms_values SET value = '(?:[\\d\\.]*?\\d(pt|px|cm|mm|in|em|ex))|0+' WHERE group_name = 'css_units' AND key1 = 'length';
UPDATE cms_values SET value = '(?:[\\d\\.]*?\\d\\%)' WHERE group_name = 'css_units' AND key1 = 'percent';
# 08.08 Vertauschen der Reihenfolge der Prüfung auf Prozent- und Längenangaben, wegen 0-Werten
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'font-size' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'font-size' AND key2 = 'units' AND key3 = '2';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'height' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'height' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'line-height' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'line-height' AND key2 = 'units' AND key3 = '2';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'margin' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'margin' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'margin-bottom' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'margin-bottom' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'margin-left' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'margin-left' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'margin-right' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'margin-right' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'margin-top' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'margin-top' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'padding' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'padding' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'padding-bottom' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'padding-bottom' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'padding-left' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'padding-left' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'padding-right' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'padding-right' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'padding-top' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'padding-top' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'text-indent' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'text-indent' AND key2 = 'units' AND key3 = '1';
UPDATE cms_values SET value = 'percent' WHERE group_name = 'css_elements' AND key1 = 'width' AND key2 = 'units' AND key3 = '0';
UPDATE cms_values SET value = 'length' WHERE group_name = 'css_elements' AND key1 = 'width' AND key2 = 'units' AND key3 = '1';
#29.08.2004 set new versionnumber - first bugfix-version for dedi 1.0
UPDATE cms_values  SET value =  '01.00.01' WHERE group_name =  'cfg' AND key1 =  'version';

#21.09.2004 delete old or missing items - create new
DELETE FROM cms_values WHERE group_name = 'rep';
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_init_plugins', '', '', '', '1', 150, 'rep_repository_init_plugins', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_lastupdate', '', '', '', 0, 100, 'rep_repository_lastupdate', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_updatetime', '', '', '', '39600', 20, 'set_repository_updatetime', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_show_up2date', '', '', '', '1', 50, 'set_repository_show_up2date', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_show_offline', '', '', '', '0', 60, 'set_repository_show_offline', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_service_list', '', '', '', '', 110, 'rep_repository_service_list', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_server', '', '', '', 'service.sefrengo.de', 10, 'set_repository_server', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_service_path', '', '', '', '/', 15, 'set_repository_path', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_lastping', '', '', '', 0, 200, 'rep_repository_lastping', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_pingtime', '', '', '', '3600', 20, 'set_repository_pingtime', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_service_message', '', '', '', '', 130, 'rep_repository_service_message', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_loopback', '', '', '', '1', 120, 'set_repository_loopback', '', 'txt', NULL, NULL, 1);
INSERT INTO cms_values VALUES ('', 0, 0, 'rep', 'repository_auto_repair_dependency', '', '', '', '1', 160, 'set_auto_repair_dependency', '', 'txt', NULL, NULL, 1);

#22.09.2004
ALTER TABLE `cms_mod` CHANGE `input` `input` LONGTEXT NOT NULL;
ALTER TABLE `cms_mod` CHANGE `output` `output` LONGTEXT NOT NULL;

#26.09.2004 add custum field 'checked'
ALTER TABLE `cms_mod` ADD `checked` ENUM( '1', '0' ) DEFAULT '0' NOT NULL ;
ALTER TABLE `cms_plug` ADD `checked` ENUM( '1', '0' ) DEFAULT '0' NOT NULL ;
UPDATE `cms_mod` set `checked` = '1';

#27.09.2004 reinit all repid´s
DELETE FROM cms_values WHERE group_name = 'cfg' AND key1 = 'repository_auto_version';
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'repository_auto_version', '', '', '', '1', 610, 'set_repository_auto_version', '', 'txt', NULL, NULL, 0);
DELETE FROM cms_values WHERE group_name = 'cfg' AND key1 = 'repository_enabled';
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'repository_enabled', '', '', '', '0', 600, 'set_repository_enabled', 'set_repository', 'txt', NULL, NULL, 0);
