CREATE TABLE IF NOT EXISTS `PREFIX_jeugagnant_participants` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `chosen_number` int(2) NOT NULL,
    `result` enum('gagne','perdu') NOT NULL,
    `participation_date` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;