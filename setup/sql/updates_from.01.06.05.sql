UPDATE cms_values SET value = '01.06.06' WHERE group_name = 'cfg' AND key1 = 'version';

# update for additional Meta-Tags
ALTER TABLE `cms_side_lang` ADD `meta_title` TEXT NOT NULL default '';
ALTER TABLE `cms_side_lang` ADD `meta_other` TEXT NOT NULL default '';
