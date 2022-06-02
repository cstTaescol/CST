USE inventarios_taescol;
UPDATE configuracion SET VERSION = '3.3.2' WHERE id = 1;
ALTER TABLE `pallet` CHANGE `numero` `numero` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;