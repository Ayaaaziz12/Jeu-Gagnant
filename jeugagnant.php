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
        && $this->installDb()
        && $this->installAdminTab();
}
private function installAdminTab()
{
    $tab = new Tab();
    $tab->class_name = 'AdminJeuGagnant';
    $tab->module = $this->name;
    $tab->id_parent = (int)Tab::getIdFromClassName('SELL'); // Ou 'CONFIGURE' selon votre PrestaShop
    $tab->name = array();
    
    foreach (Language::getLanguages(true) as $lang) {
        $tab->name[$lang['id_lang']] = 'Jeu Gagnant';
    }
    
    return $tab->add();
}

public function uninstall()
{
    return parent::uninstall() 
        && $this->uninstallDb()
        && $this->uninstallAdminTab();
}
private function uninstallAdminTab()
{
    $id_tab = (int)Tab::getIdFromClassName('AdminJeuGagnant');
    if ($id_tab) {
        $tab = new Tab($id_tab);
        return $tab->delete();
    }
    return true;
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
        <h3> TEST HOOK DISPLAYHOME - VISIBLE</h3>
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
    $output = '';
    
    // Traitement du formulaire de configuration
    if (Tools::isSubmit('saveConfig')) {
        $output .= $this->saveConfiguration();
    }
    
    // Afficher TOUT sur une seule page
    $output .= $this->renderConfigurationForm();
    $output .= $this->renderQuickStats();
    $output .= $this->renderRecentParticipations();
    
    return $output;
}
/**
 * Affiche les statistiques rapides
 */
private function renderQuickStats()
{
    $stats = Db::getInstance()->getRow('
        SELECT 
            COUNT(*) as total_participations,
            SUM(IF(result = "gagne", 1, 0)) as total_gagnants,
            ROUND(SUM(IF(result = "gagne", 1, 0)) / COUNT(*) * 100, 2) as taux_reussite
        FROM `' . _DB_PREFIX_ . 'jeu_gagnant_participations`
    ');
    
    $this->context->smarty->assign([
        'stats' => $stats,
        'admin_participations_url' => $this->context->link->getAdminLink('AdminJeuGagnant')
    ]);
    
    return $this->display(__FILE__, 'views/templates/admin/quick_stats.tpl');
}

/**
 * Affiche les participations récentes
 */
private function renderRecentParticipations()
{
    $participations = Db::getInstance()->executeS('
        SELECT * FROM `' . _DB_PREFIX_ . 'jeu_gagnant_participations`
        ORDER BY date_participation DESC
        LIMIT 5
    ');
    
    $this->context->smarty->assign([
        'participations' => $participations,
        'winning_number' => (int)Configuration::get('JEU_GAGNANT_WINNING_NUMBER') ?: 5
    ]);
    
    return $this->display(__FILE__, 'views/templates/admin/recent_participations.tpl');
}
private function renderTabs()
{
    $admin_config_url = $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name;
    $admin_participations_url = $this->context->link->getAdminLink('AdminJeuGagnant');
    
    $current_tab = Tools::getValue('tab', 'config');
    
    $tabs = [
        'config' => [
            'name' => $this->l('Configuration'),
            'icon' => 'icon-cogs',
            'url' => $admin_config_url . '&tab=config',
            'active' => ($current_tab == 'config'),
            'content' => $this->renderConfigurationForm()
        ],
        'participations' => [
            'name' => $this->l('Participations'),
            'icon' => 'icon-users', 
            'url' => $admin_participations_url,
            'active' => ($current_tab == 'participations'),
            'content' => ''
        ],
        'stats' => [
            'name' => $this->l('Statistiques'),
            'icon' => 'icon-bar-chart',
            'url' => $admin_config_url . '&tab=stats',
            'active' => ($current_tab == 'stats'),
            'content' => $this->renderStats()
        ]
    ];
    
    $this->context->smarty->assign([
        'tabs' => $tabs,
        'current_tab' => $current_tab,
        'module_dir' => $this->_path
    ]);
    
    return $this->display(__FILE__, 'views/templates/admin/tabs.tpl');
}

private function renderParticipationsList()
{
    // Rediriger vers le contrôleur admin
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminJeuGagnant'));
}

private function renderStats()
{
    // Statistiques générales
    $stats_general = Db::getInstance()->getRow('
        SELECT 
            COUNT(*) as total_participations,
            SUM(IF(result = "gagne", 1, 0)) as total_gagnants,
            SUM(IF(result = "perdu", 1, 0)) as total_perdus,
            ROUND(SUM(IF(result = "gagne", 1, 0)) / COUNT(*) * 100, 2) as taux_reussite,
            MIN(date_participation) as date_premiere,
            MAX(date_participation) as date_derniere
        FROM `' . _DB_PREFIX_ . 'jeu_gagnant_participations`
    ');
    
    // Statistiques par jour
    $stats_par_jour = Db::getInstance()->executeS('
        SELECT 
            DATE(date_participation) as date,
            COUNT(*) as participations,
            SUM(IF(result = "gagne", 1, 0)) as gagnants,
            ROUND(SUM(IF(result = "gagne", 1, 0)) / COUNT(*) * 100, 2) as taux
        FROM `' . _DB_PREFIX_ . 'jeu_gagnant_participations`
        GROUP BY DATE(date_participation)
        ORDER BY date DESC
        LIMIT 15
    ');
    
    // Statistiques par numéro choisi
    $stats_par_numero = Db::getInstance()->executeS('
        SELECT 
            number_chosen as numero,
            COUNT(*) as total_choix,
            SUM(IF(result = "gagne", 1, 0)) as gagnants,
            ROUND(COUNT(*) / (SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'jeu_gagnant_participations`) * 100, 2) as pourcentage_choix,
            ROUND(SUM(IF(result = "gagne", 1, 0)) / COUNT(*) * 100, 2) as taux_reussite
        FROM `' . _DB_PREFIX_ . 'jeu_gagnant_participations`
        GROUP BY number_chosen
        ORDER BY total_choix DESC
    ');
    
    // Top 10 des emails (participations multiples)
    $top_emails = Db::getInstance()->executeS('
        SELECT 
            email,
            COUNT(*) as participations,
            SUM(IF(result = "gagne", 1, 0)) as gagnants
        FROM `' . _DB_PREFIX_ . 'jeu_gagnant_participations`
        GROUP BY email
        HAVING COUNT(*) > 1
        ORDER BY participations DESC
        LIMIT 10
    ');
    
    // Dernières participations
    $recent_participations = Db::getInstance()->executeS('
        SELECT * FROM `' . _DB_PREFIX_ . 'jeu_gagnant_participations`
        ORDER BY date_participation DESC
        LIMIT 10
    ');
    
    $this->context->smarty->assign([
        'stats_general' => $stats_general,
        'stats_par_jour' => $stats_par_jour,
        'stats_par_numero' => $stats_par_numero,
        'top_emails' => $top_emails,
        'recent_participations' => $recent_participations,
        'winning_number' => (int)Configuration::get('JEU_GAGNANT_WINNING_NUMBER') ?: 5,
        'module_dir' => $this->_path
    ]);
    
    return $this->display(__FILE__, 'views/templates/admin/stats.tpl');
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