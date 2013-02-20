#25.11.2005 set new versionnumber - sefrengo 1.2 beta
UPDATE cms_values  SET value =  '01.02.00' WHERE group_name =  'cfg' AND key1 =  'version';

#26.11.2005 update image driver settings
UPDATE cms_values  SET value =  'GD' WHERE group_name =  'cfg' AND key1 =  'image_mode' AND value =  'gd';
UPDATE cms_values  SET value =  'IM' WHERE group_name =  'cfg' AND key1 =  'image_mode' AND value =  'im';
UPDATE cms_values  SET value =  'Imagick' WHERE group_name =  'cfg' AND key1 =  'image_mode' AND value =  'imagick';
UPDATE cms_values  SET value =  'NetPBM' WHERE group_name =  'cfg' AND key1 =  'image_mode' AND value =  'netpbm';
DELETE FROM cms_values  WHERE group_name =  'cfg' AND key1 =  'gd_version';
DELETE FROM cms_values  WHERE group_name =  'cfg' AND key1 =  'path_imagelib';

#27.11.2005 rename old dedi langstring in system settings
UPDATE cms_values  SET conf_desc_langstring =  'set_cms_path' WHERE conf_desc_langstring =  'set_dedi_path';
