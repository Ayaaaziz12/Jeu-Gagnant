<?php
include('config/config.inc.php');

// Forcer l'URL actuelle
$new_url = 'https://prestashop.test.com'; // REMPLACEZ PAR VOTRE VRAI DOMAINE
Configuration::updateValue('PS_SHOP_DOMAIN', $new_url);
Configuration::updateValue('PS_SHOP_DOMAIN_SSL', $new_url);

echo "URL forcée à : " . $new_url;