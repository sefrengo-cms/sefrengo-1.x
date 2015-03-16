# xx.03.2015 set new versionnumber - Sefrengo 1.6.5
UPDATE cms_values SET value = '01.06.05' WHERE group_name = 'cfg' AND key1 = 'version';

# update for social media meta tags
ALTER TABLE `cms_side_lang` ADD `metasocial_title` VARCHAR( 255 ) NULL ,
ADD `metasocial_image` VARCHAR( 255 ) NULL ,
ADD `metasocial_description` VARCHAR( 255 ) NULL ,
ADD `metasocial_author` VARCHAR( 255 ) NULL 
