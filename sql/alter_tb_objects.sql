USE `fabtotum`;
SELECT count(*)
INTO @exist
FROM information_schema.columns 
WHERE table_schema = 'fabtotum'
and COLUMN_NAME = 'private'
AND table_name = 'sys_objects';

set @query = IF(@exist <= 0, 'ALTER TABLE `sys_objects` ADD `private` INT(1) NOT NULL DEFAULT 1 AFTER `date_updated`', 
'select \'Column Exists\' status');

prepare stmt from @query;
EXECUTE stmt;
