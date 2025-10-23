<?php

class JeuGagnantTryModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        if (Tools::isSubmit('email')) {
            $email = Tools::getValue('email');
            
            if (!Validate::isEmail($email)) {
                $this->errors[] = $this->module->l('Email invalide');
                return $this->setTemplate('module:jeugagnant/views/templates/front/game.tpl');
            }

            // Enregistrer l'email en session
            $this->context->cookie->jeugagnant_email = $email;
        }

        if (Tools::isSubmit('guess_number')) {
            $this->processGame();
        }

        $this->context->smarty->assign([
            'email' => $this->context->cookie->jeugagnant_email ?? '',
            'winning_number' => Configuration::get('JEUGAGNANT_WINNING_NUMBER'),
            'promo_code' => Configuration::get('JEUGAGNANT_PROMO_CODE')
        ]);

        $this->setTemplate('module:jeugagnant/views/templates/front/game.tpl');
    }

    private function processGame()
    {
        $email = $this->context->cookie->jeugagnant_email;
        $chosen_number = (int)Tools::getValue('guess_number');
        $winning_number = (int)Configuration::get('JEUGAGNANT_WINNING_NUMBER');

        $result = ($chosen_number === $winning_number) ? 'gagne' : 'perdu';

        // Enregistrer la participation
        Db::getInstance()->insert('jeugagnant_participations', [
            'email' => pSQL($email),
            'chosen_number' => (int)$chosen_number,
            'result' => pSQL($result),
            'date_add' => date('Y-m-d H:i:s')
        ]);

        $this->context->smarty->assign([
            'game_result' => $result,
            'chosen_number' => $chosen_number,
            'winning_number' => $winning_number,
            'promo_code' => Configuration::get('JEUGAGNANT_PROMO_CODE')
        ]);
    }
}