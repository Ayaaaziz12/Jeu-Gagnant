<?php
/**
 * Module Jeu Gagnant - Jeu concours avec popup
 * 
 * @author Aya Aziz
 * @version 1.0.0
 */

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
        $this->description = $this->l('Module de jeu concours avec popup et code promo.');
        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller ce module ?');
    }

    public function install()
    {
        return parent::install() 
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayHome')
            && $this->registerHook('displayFooter')
            && $this->installDb();
    }

    public function uninstall()
    {
        return parent::uninstall() 
            && $this->uninstallDb();
    }

    private function installDb()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'jeu_gagnant_participations` (
            `id_participation` INT(11) NOT NULL AUTO_INCREMENT,
            `email` VARCHAR(255) NOT NULL,
            `number_chosen` INT(2) NOT NULL,
            `result` ENUM("gagne", "perdu") NOT NULL,
            `code_promo` VARCHAR(50) NULL,
            `date_participation` DATETIME NOT NULL,
            PRIMARY KEY (`id_participation`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'jeu_gagnant_participations`');
    }

    /**
     * Vérifie si le module est activé
     */
    private function isModuleActive()
    {
        // POUR TEST - toujours actif
        return true;
    }

    public function hookDisplayHeader($params)
    {
        // Ajouter le CSS seulement sur la page d'accueil
        if ($this->context->controller->php_self == 'index') {
            $this->context->controller->registerStylesheet(
                'jeugagnant-css',
                'modules/'.$this->name.'/views/css/jeugagnant.css',
                ['media' => 'all', 'priority' => 150]
            );
        }
    }

public function hookDisplayHome($params)
{
    // FORCER l'affichage pour test
    $force_display = true;
    
    $test_content = '
    <div style="background: red; color: white; padding: 20px; margin: 20px 0; text-align: center; border: 3px solid yellow;">
        <h3>🎯 TEST HOOK DISPLAYHOME - VISIBLE</h3>
        <p>Si vous voyez ce message ROUGE, le hook displayHome fonctionne</p>
        <p>Module: ' . $this->name . ' | Actif: ' . ($this->isModuleActive() ? 'OUI' : 'NON') . '</p>
    </div>
    ';
    
    if ($force_display) {
        return $test_content;
    }
    
    // Logique normale (commentée pour le test)
    if (!$this->isModuleActive()) {
        return '';
    }
    
    if (isset($this->context->cookie->has_played_jeu) && $this->context->cookie->has_played_jeu) {
        return '';
    }
    
    return $this->display(__FILE__, 'views/templates/front/popup.tpl');
}

    /**
     * Sauvegarder une participation en base de données
     */
    public function saveParticipation($email, $number_chosen, $is_winner)
    {
        // Marquer que l'utilisateur a déjà joué
        $this->context->cookie->__set('has_played_jeu', true);
        $this->context->cookie->write();
        
        // Enregistrer en base de données
        $data = [
            'email' => pSQL($email),
            'number_chosen' => (int)$number_chosen,
            'result' => $is_winner ? 'gagne' : 'perdu',
            'code_promo' => $is_winner ? pSQL('PROMO10') : null,
            'date_participation' => date('Y-m-d H:i:s')
        ];
        
        return Db::getInstance()->insert('jeu_gagnant_participations', $data);
    }

    public function getContent()
    {
        // Traitement du formulaire de configuration
        if (Tools::isSubmit('saveConfig')) {
            $result = $this->saveConfiguration();
            if ($result) {
                return $result . $this->renderConfigurationForm();
            }
        }
        
        // Affichage du formulaire de configuration
        return $this->renderConfigurationForm();
    }

    /**
     * Sauvegarde la configuration
     */
    private function saveConfiguration()
    {
        $winning_number = (int)Tools::getValue('winning_number');
        $code_promo = Tools::getValue('code_promo');
        $date_start = Tools::getValue('date_start');
        $date_end = Tools::getValue('date_end');
        $active = (bool)Tools::getValue('active');

        // Validation
        if ($winning_number < 1 || $winning_number > 10) {
            return $this->displayError('Le numéro gagnant doit être entre 1 et 10');
        }

        // Sauvegarde en base
        Configuration::updateValue('JEU_GAGNANT_WINNING_NUMBER', $winning_number);
        Configuration::updateValue('JEU_GAGNANT_CODE_PROMO', $code_promo);
        Configuration::updateValue('JEU_GAGNANT_DATE_START', $date_start);
        Configuration::updateValue('JEU_GAGNANT_DATE_END', $date_end);
        Configuration::updateValue('JEU_GAGNANT_ACTIVE', $active);

        return $this->displayConfirmation('Configuration sauvegardée avec succès');
    }

    /**
     * Affiche le formulaire de configuration
     */
    private function renderConfigurationForm()
    {
        // Récupérer les valeurs actuelles
        $winning_number = Configuration::get('JEU_GAGNANT_WINNING_NUMBER') ?: 5;
        $code_promo = Configuration::get('JEU_GAGNANT_CODE_PROMO') ?: 'PROMO10';
        $date_start = Configuration::get('JEU_GAGNANT_DATE_START');
        $date_end = Configuration::get('JEU_GAGNANT_DATE_END');
        $active = (bool)Configuration::get('JEU_GAGNANT_ACTIVE');

        // Assigner les variables au template
        $this->context->smarty->assign([
            'winning_number' => $winning_number,
            'code_promo' => $code_promo,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'active' => $active,
            'module_dir' => $this->_path,
            'form_action' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    /**
     * Get module public path for URLs
     */
    public function getPathUri()
    {
        return $this->_path;
    }

    public function hookDisplayFooter($params)
{
    $debug_info = '
    <div style="background: green; color: white; padding: 10px; margin: 10px 0;">
        <strong>DEBUG Module Jeu Gagnant</strong><br>
        Module actif: OUI<br>
        <a href="' . $this->context->link->getModuleLink('jeugagnant', 'game') . '" style="color: white; text-decoration: underline;">
            Aller à la page du jeu
        </a>
    </div>
    ';
    
    // Afficher le popup dans le footer aussi
    $popup_content = $this->display(__FILE__, 'views/templates/front/popup.tpl');
    
    return $debug_info . $popup_content;
}
}