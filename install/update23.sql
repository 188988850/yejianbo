ALTER TABLE `shua_shequ`
ADD COLUMN `remark` varchar(255) DEFAULT NULL,
ADD COLUMN `protocol` tinyint(1) NOT NULL DEFAULT 0,
ADD COLUMN `monitor` tinyint(1) NOT NULL DEFAULT 0;

ALTER TABLE `shua_faka`
ADD INDEX `tid` (`tid`),
ADD INDEX `getleft` (`tid`,`orderid`);

ALTER TABLE `shua_kms`
ADD COLUMN `num` int(11) unsigned NOT NULL DEFAULT 1;