# 19.11.2007 remove 
DELETE FROM cms_values WHERE group_name =  'cfg' AND key1 =  'sidelock_time';

# 17.12.2007 new fields for cms_users
ALTER TABLE cms_users ADD registration_hash varchar(63) default NULL;
ALTER TABLE cms_users ADD accept_agreement tinyint(1) unsigned default '0';
ALTER TABLE cms_users ADD accept_agreement_timestamp INT( 10 ) default NULL;
ALTER TABLE cms_users ADD registers_timestamp INT( 10 ) default NULL;
ALTER TABLE cms_users ADD registration_valid tinyint(1) unsigned default '0'; 

# 19.11.2007 set new versionnumber - sefrengo 1.4.1
UPDATE cms_values  SET value =  '01.04.01' WHERE group_name =  'cfg' AND key1 =  'version';
