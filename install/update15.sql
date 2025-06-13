ALTER TABLE `shua_kms`
ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT '0';

UPDATE `shua_kms` SET `status`=1 WHERE `usetime` IS NOT NULL;

ALTER TABLE `shua_qiandao`
ADD COLUMN `ip` varchar(50) DEFAULT NULL;

ALTER TABLE `shua_qiandao`
ADD INDEX `ip` (`ip`),
ADD INDEX `date` (`date`);
