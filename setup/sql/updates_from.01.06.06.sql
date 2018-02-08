# xx.03.2015 set new versionnumber - Sefrengo 1.6.5
UPDATE cms_values SET value = '01.06.05' WHERE group_name = 'cfg' AND key1 = 'version';

ALTER TABLE `cms_container_conf` CHANGE `config` `config` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `cms_mod` CHANGE `config` `config` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
