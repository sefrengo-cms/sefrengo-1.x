# 30.06.2006 set new versionnumber - sefrengo 1.3.1 DEV
UPDATE cms_values  SET value =  '01.03.01' WHERE group_name =  'cfg' AND key1 =  'version';

# 22.07.2006 new values for the user table
ALTER TABLE cms_users 
ADD `title` VARCHAR( 255 ) default NULL AFTER `password` ,
ADD `author` INT( 6 ) NOT NULL AFTER `firm_homepage` ,
ADD `created` INT( 10 ) NOT NULL AFTER `author` ,
ADD `lastmodified` INT( 10 ) NOT NULL AFTER `created` ,
ADD `lastmodified_author` INT( 10 ) NOT NULL AFTER `lastmodified` ,
ADD `currentlogin` INT( 10 ) NOT NULL AFTER `lastmodified` ,
ADD `lastlogin` INT( 10 ) NOT NULL AFTER `currentlogin` ,
ADD `lastlogin_failed` INT( 10 ) NOT NULL AFTER `lastlogin` ,
ADD `failed_count` INT( 6 ) NOT NULL AFTER `lastlogin_failed`,
ADD `password_recover_hash` VARCHAR(32) default NULL AFTER `failed_count` ;

ALTER TABLE `cms_users` CHANGE `lastlogin_failed` `lastlogin_failed` INT( 10 ) NOT NULL DEFAULT '0';
ALTER TABLE `cms_users` CHANGE `failed_count` `failed_count` INT( 6 ) NOT NULL DEFAULT '0';

#02.08.2006 - new perms for area user and area user
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_user', NULL, NULL, '', 94, 'group_area_user', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'cms_access', 'area_group', NULL, NULL, '', 97, 'group_area_group', '', 'txt', NULL, NULL, 0);
UPDATE `cms_backendmenu` SET `entry_validate` = '$perm->have_perm(\'area_user\')' WHERE  entry_langstring = 'nav_3_1';
UPDATE `cms_backendmenu` SET `entry_validate` = '$perm->have_perm(\'area_group\')' WHERE entry_langstring = 'nav_3_2';

#25.08.2006 - new frontendperm interactions
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'area_frontend', '19', NULL, NULL, '262144', 190, 'group_area_frontend_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendcat', '19', NULL, NULL, '262144', 190, 'group_frontendcat_19', '', 'txt', NULL, NULL, 0);
INSERT INTO cms_values VALUES ('', 0, 0, 'user_perms', 'frontendpage', '19', NULL, NULL, '262144', 190, 'group_frontendpage_19', '', 'txt', NULL, NULL, 0);

#25.08.2006 - new table tracker
CREATE TABLE cms_tracker (
  `idtracker` int(11) unsigned NOT NULL auto_increment,
  `idclient` int(11) default NULL,
  `idlang` int(6) default NULL,
  `iduser` int(11) default NULL,
  `created` int(11) default NULL,
  `ip` varchar(32) default NULL,
  `groupname` varchar(32) default NULL,
  `action` varchar(64) default NULL,
  `value1` varchar(255) default NULL,
  `value2` varchar(255) default NULL,
  `value3` varchar(255) default NULL,
  `value4` varchar(255) default NULL,
  `value5` varchar(255) default NULL,
  PRIMARY KEY  (`idtracker`),
  KEY `idclient` (`idclient`),
  KEY `group` (`groupname`)
) ENGINE=MyISAM;
ALTER TABLE cms_tracker ADD INDEX ( `idclient` );
ALTER TABLE cms_tracker ADD INDEX ( `idlang` );
ALTER TABLE cms_tracker ADD INDEX ( `iduser` );
ALTER TABLE cms_tracker ADD INDEX ( `created` );
ALTER TABLE cms_tracker ADD FULLTEXT (`ip`);
ALTER TABLE cms_tracker ADD FULLTEXT (`groupname`);
ALTER TABLE cms_tracker ADD FULLTEXT (`action`);

#25.08.2006 - new system setting paging per page
INSERT INTO cms_values VALUES ('', 0, 0, 'cfg', 'paging_items_per_page', '', '', '', '20', 200, 'set_paging_items_per_page', NULL, 'txt', NULL, NULL, '1');

#27.08.2006 - update backendmenu 
UPDATE `cms_backendmenu` SET `entry_validate` = '$perm->have_perm(\'area_user\')' WHERE `entry_langstring` = 'nav_3_1' ;

#18.09.2006 - delete users group relation for idgroup 1 (nobody) - this is not needed anymore 
DELETE FROM cms_users_groups WHERE `idgroup` =1;

#26.09.2006 - remove unused service.der-dirigent.de and replace it with unused service.sefrengo.org :)
UPDATE cms_values  SET value =  'service.sefrengo.org' WHERE group_name =  'rep' AND value =  'service.der-dirigent.de';
UPDATE cms_values  SET value =  'service.sefrengo.org' WHERE group_name =  'rep' AND value =  'service.sefrengo.de';

#27.09.2006 - make automatic updates from dedi guestbook to sefrengo guestbook possible
UPDATE cms_mod SET repository_id = 'mod:16b89158a45ba49bca19216d43caa5ae:00000000' WHERE repository_id LIKE ('mod:4c4cd3881d16a5d56745d8735eab619f:%');

