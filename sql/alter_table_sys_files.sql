USE `fabtotum`;
SELECT count(*)
INTO @exist
FROM information_schema.columns 
WHERE table_schema = 'fabtotum'
and COLUMN_NAME = 'attributes'
AND table_name = 'sys_files';

set @query = IF(@exist <= 0, 'ALTER TABLE `sys_files` ADD `attributes` TEXT NOT NULL AFTER `note`', 
'select \'Column Exists\' status');

prepare stmt from @query;
EXECUTE stmt;
