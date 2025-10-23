<?php

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'jeugagnant_participations` (
    `id_participation` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `chosen_number` int(2) NOT NULL,
    `result` varchar(20) NOT NULL,
    `date_add` datetime NOT NULL,
    PRIMARY KEY (`id_participation`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'INSERT INTO `' . _DB_PREFIX_ . 'configuration` (`name`, `value`, `date_add`, `date_upd`) VALUES
    ("JEUGAGNANT_WINNING_NUMBER", "5", NOW(), NOW()),
    ("JEUGAGNANT_PROMO_CODE", "PROMO10", NOW(), NOW()),
    ("JEUGAGNANT_DATE_START", "' . date('Y-m-d') . '", NOW(), NOW()),
    ("JEUGAGNANT_DATE_END", "' . date('Y-m-d', strtotime('+30 days')) . '", NOW(), NOW()),
    ("JEUGAGNANT_STATUS", "1", NOW(), NOW());';

return implode('', $sql);