INSERT INTO `shua_config` VALUES ('shopdesc_editor', '1');

ALTER TABLE `shua_tools`
ADD COLUMN `sales` INT(11) NOT NULL DEFAULT 0;

ALTER TABLE `shua_shequ`
MODIFY COLUMN `type` varchar(20) NOT NULL;

ALTER TABLE `shua_shequ`
ADD COLUMN `result` tinyint(1) NOT NULL DEFAULT '1';

UPDATE `shua_shequ` SET `type`='jiuwu' WHERE `type`='0';
UPDATE `shua_shequ` SET `type`='yile' WHERE `type`='1';
UPDATE `shua_shequ` SET `type`='kayixin' WHERE `type`='6';
UPDATE `shua_shequ` SET `type`='kayisu' WHERE `type`='5';
UPDATE `shua_shequ` SET `type`='kalegou' WHERE `type`='7';
UPDATE `shua_shequ` SET `type`='kashangwl' WHERE `type`='9';
UPDATE `shua_shequ` SET `type`='shangmeng' WHERE `type`='8';
UPDATE `shua_shequ` SET `type`='liuliangka' WHERE `type`='10';
UPDATE `shua_shequ` SET `type`='daishua' WHERE `type`='12';
UPDATE `shua_shequ` SET `type`='extend' WHERE `type`='20';
DELETE FROM `shua_shequ` WHERE `type`='2';
DELETE FROM `shua_shequ` WHERE `type`='3';
DELETE FROM `shua_shequ` WHERE `type`='4';
DELETE FROM `shua_shequ` WHERE `type`='11';
