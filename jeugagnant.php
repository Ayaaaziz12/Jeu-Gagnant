<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Jeugagnant extends Module
{
    public function __construct()
    {
        $this->name = 'jeugagnant';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Aya Aziz';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Jeu Gagnant');
        $this->description = $this->l('Module de jeu avec popup pour gagner des codes promo');
        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller ce module ?');
    }

    public function install()
    {
        return parent::install() &&
            $this->installDb() &&
            $this->registerHook('header') &&
            $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    private function installDb()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'jeugagnant_participations` (
            `id_participation` int(11) NOT NULL AUTO_INCREMENT,
            `email` varchar(255) NOT NULL,
            `chosen_number` int(2) NOT NULL,
            `result` varchar(20) NOT NULL,
            `date_add` datetime NOT NULL,
            PRIMARY KEY (`id_participation`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'jeugagnant_participations`
        ');
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitConfig')) {
            Configuration::updateValue('JEUGAGNANT_WINNING_NUMBER', (int)Tools::getValue('winning_number'));
            Configuration::updateValue('JEUGAGNANT_PROMO_CODE', Tools::getValue('promo_code'));
            Configuration::updateValue('JEUGAGNANT_DATE_START', Tools::getValue('date_start'));
            Configuration::updateValue('JEUGAGNANT_DATE_END', Tools::getValue('date_end'));
            Configuration::updateValue('JEUGAGNANT_STATUS', (int)Tools::getValue('status'));
            
            $this->context->smarty->assign('confirmation', 'Configuration sauvegardée !');
        }

        $config = $this->getModuleConfig();
        
        $this->context->smarty->assign([
            'config' => $config,
            'module_dir' => $this->_path,
            'link' => $this->context->link,
            'action_url' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name
        ]);

        return $this->display(__FILE__, 'configure.tpl');
    }

    public function hookHeader()
    {
        $this->context->controller->registerStylesheet(
            'jeugagnant-css',
            $this->_path . 'views/css/jeugagnant.css',
            ['media' => 'all', 'priority' => 150]
        );

        $this->context->controller->registerJavascript(
            'jeugagnant-js',
            $this->_path . 'views/js/jeugagnant.js',
            ['position' => 'bottom', 'priority' => 150]
        );
    }

    public function hookDisplayHome($params)
{
    // FORCER l'affichage pour tester
    $test_mode = true;
    
    if ($test_mode) {
        // Mode test - afficher quelque chose de visible
        $this->context->smarty->assign([
            'jeugagnant_url' => $this->context->link->getModuleLink('jeugagnant', 'try')
        ]);
        
        $test_content = $this->display(__FILE__, 'views/templates/front/test.tpl');
        return $test_content;
    }

    $config = $this->getModuleConfig();
    
    if (!$config['status']) {
        return '<!-- JeuGagnant: Module désactivé -->';
    }

    $current_time = time();
    $start_time = strtotime($config['date_start']);
    $end_time = strtotime($config['date_end']);

    if ($current_time < $start_time) {
        return '<!-- JeuGagnant: Pas encore commencé -->';
    }

    if ($current_time > $end_time) {
        return '<!-- JeuGagnant: Terminé -->';
    }

    $this->context->smarty->assign([
        'jeugagnant_url' => $this->context->link->getModuleLink('jeugagnant', 'try'),
        'module_config' => $config
    ]);

    return $this->display(__FILE__, 'views/templates/front/popup.tpl');
}
    private function getModuleConfig()
    {
        return [
            'winning_number' => Configuration::get('JEUGAGNANT_WINNING_NUMBER') ?: 5,
            'promo_code' => Configuration::get('JEUGAGNANT_PROMO_CODE') ?: 'PROMO10',
            'date_start' => Configuration::get('JEUGAGNANT_DATE_START') ?: date('Y-m-d'),
            'date_end' => Configuration::get('JEUGAGNANT_DATE_END') ?: date('Y-m-d', strtotime('+30 days')),
            'status' => (bool)Configuration::get('JEUGAGNANT_STATUS')
        ];
    }
}