<?php

class AdminJeuGagnantController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'jeugagnant_participations';
        $this->className = 'JeuGagnantParticipation';
        $this->identifier = 'id_participation';
        $this->list_no_link = true;

        parent::__construct();

        $this->fields_list = [
            'id_participation' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'email' => [
                'title' => $this->l('Email')
            ],
            'chosen_number' => [
                'title' => $this->l('Numéro choisi'),
                'align' => 'center'
            ],
            'result' => [
                'title' => $this->l('Résultat'),
                'callback' => 'displayResult'
            ],
            'date_add' => [
                'title' => $this->l('Date de participation'),
                'type' => 'datetime'
            ]
        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            ]
        ];
    }

    public function displayResult($value, $row)
    {
        return $value === 'gagne' ? 
            '<span class="badge badge-success">' . $this->l('Gagné') . '</span>' :
            '<span class="badge badge-danger">' . $this->l('Perdu') . '</span>';
    }

    public function renderList()
    {
        $this->toolbar_title = $this->l('Participations au jeu');
        
        // Ajouter le bouton de configuration
        $this->toolbar_btn['configure'] = [
            'href' => $this->context->link->getAdminLink('AdminModules') . '&configure=jeugagnant',
            'desc' => $this->l('Configurer le module')
        ];

        return parent::renderList();
    }
}

// Classe fictive pour la liste
class JeuGagnantParticipation extends ObjectModel
{
    public $id_participation;
    public $email;
    public $chosen_number;
    public $result;
    public $date_add;
}