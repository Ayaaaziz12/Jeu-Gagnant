<?php
include('config/config.inc.php');

// Forcer le domaine
$domain = 'prestashop.test';

// Mise à jour configuration
Configuration::updateValue('PS_SHOP_DOMAIN', $domain);
Configuration::updateValue('PS_SHOP_DOMAIN_SSL', $domain);

// Mise à jour shop_url
Db::getInstance()->execute("
    UPDATE " . _DB_PREFIX_ . "shop_url 
    SET domain = '" . pSQL($domain) . "', 
        domain_ssl = '" . pSQL($domain) . "',
        physical_uri = '/'
");

// Vider le cache
Tools::clearAllCache();

echo "✅ Domaine forcé à : " . $domain;
echo "<br><a href='http://prestashop.test/'>Aller à la boutique</a>";
?>