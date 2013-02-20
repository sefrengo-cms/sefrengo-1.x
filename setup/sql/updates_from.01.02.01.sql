# 04.04.2006 set new versionnumber - sefrengo 1.2.2
UPDATE cms_values  SET value =  '01.02.02' WHERE group_name =  'cfg' AND key1 =  'version';

# 08.04.2006 replace old <dedl:lay in some cms_values, like the layout template
UPDATE cms_values SET value = REPLACE (value, '<dedi:lay', '<cms:lay');