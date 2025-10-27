<?php
class JeugagnantGameModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        
        // DEBUG: Afficher les informations de session
        error_log("=== DEBUG JEU GAGNANT ===");
        error_log("Cookie jeu_email: " . ($this->context->cookie->jeu_email ?? 'NULL'));
        error_log("POST email: " . (Tools::getValue('email') ?? 'NULL'));
        error_log("========================");
        
        // Vérifier si on vient du formulaire popup
        if (Tools::getValue('email')) {
            $email = Tools::getValue('email');
            if (Validate::isEmail($email)) {
                // Sauvegarder l'email en session
                $this->context->cookie->jeu_email = $email;
                $this->context->cookie->write(); // IMPORTANT: Sauvegarder le cookie
                
                // Afficher la page du jeu
                $this->displayGame($email);
                return;
            } else {
                // Email invalide, rediriger vers l'accueil
                Tools::redirect($this->context->link->getPageLink('index'));
            }
        }
        
        // Vérifier si l'email est en session
        $email = $this->context->cookie->jeu_email ?? null;
        
        if (!$email) {
            // Pas d'email, rediriger vers l'accueil
            Tools::redirect($this->context->link->getPageLink('index'));
        }
        
        // Vérifier si le formulaire de jeu a été soumis
        if (Tools::getValue('jeu_action') == 'play') {
            $this->processGame();
        } else {
            $this->displayGame($email);
        }
    }
    
    private function displayGame($email)
    {
        $this->context->smarty->assign([
            'email' => $email,
            'urls' => $this->getTemplateVarUrls(),
            'module_dir' => $this->module->getPathUri()
        ]);
        
        $this->setTemplate('module:jeugagnant/views/templates/front/game.tpl');
    }
    
    private function processGame()
    {
        $email = $this->context->cookie->jeu_email;
        $number_chosen = (int)Tools::getValue('number_chosen');
        
        // Récupérer la configuration
        $winning_number = (int)Configuration::get('JEU_GAGNANT_WINNING_NUMBER') ?: 5;
        $code_promo = Configuration::get('JEU_GAGNANT_CODE_PROMO') ?: 'PROMO10';
        
        $is_winner = ($number_chosen === $winning_number);
        
        // Enregistrer la participation
        $this->module->saveParticipation($email, $number_chosen, $is_winner);
        
        // Préparer les données pour le template
        $this->context->smarty->assign([
            'result' => $is_winner ? 'gagne' : 'perdu',
            'code_promo' => $is_winner ? $code_promo : null,
            'winning_number' => $winning_number,
            'user_number' => $number_chosen,
            'urls' => $this->getTemplateVarUrls()
        ]);
        
        $this->setTemplate('module:jeugagnant/views/templates/front/result.tpl');
    }
    
    public function setMedia()
    {
        parent::setMedia();
        $this->addCSS($this->module->getPathUri() . 'views/css/jeugagnant.css');
    }
}