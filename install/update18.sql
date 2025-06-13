INSERT INTO `shua_config` VALUES ('fenzhan_edithtml', '1');
INSERT INTO `shua_config` VALUES ('workorder_open', '1');

ALTER TABLE `shua_site`
ADD COLUMN `qq_openid` VARCHAR(64) DEFAULT NULL,
ADD COLUMN `nickname` VARCHAR(64) DEFAULT NULL,
ADD COLUMN `faceimg` VARCHAR(150) DEFAULT NULL;

ALTER TABLE `shua_site`
ADD INDEX `qq_openid` (`qq_openid`);

ALTER TABLE `shua_tools`
MODIFY COLUMN `goods_param` TEXT DEFAULT NULL;