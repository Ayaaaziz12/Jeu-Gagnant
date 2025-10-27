<?php

class AdminJeuGagnantController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'jeu_gagnant_participations';
        $this->className = 'ObjectModel'; 
        $this->identifier = 'id_participation';
        $this->list_id = 'jeu_gagnant_participations';
        
        parent::__construct();

        $this->fields_list = [
            'id_participation' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'email' => [
                'title' => $this->l('Email'),
                'filter_key' => 'a!email',
            ],
            'number_chosen' => [
                'title' => $this->l('Numéro choisi'),
                'align' => 'center',
            ],
            'result' => [
                'title' => $this->l('Résultat'),
                'align' => 'center',
                'callback' => 'displayResult',
            ],
            'code_promo' => [
                'title' => $this->l('Code promo'),
                'align' => 'center',
            ],
            'date_participation' => [
                'title' => $this->l('Date participation'),
                'type' => 'datetime',
            ],
        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Supprimer la sélection'),
                'confirm' => $this->l('Supprimer les éléments sélectionnés ?'),
            ],
        ];
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['export_csv'] = [
                'href' => self::$currentIndex . '&exportcsv&token=' . $this->token,
                'desc' => $this->l('Export CSV'),
                'icon' => 'process-icon-export'
            ];
            
            $this->page_header_toolbar_btn['stats'] = [
                'href' => $this->context->link->getAdminLink('AdminModules') . '&configure=jeugagnant&tab=stats',
                'desc' => $this->l('Statistiques'),
                'icon' => 'process-icon-bar-chart'
            ];
        }
        
        parent::initPageHeaderToolbar();
    }

    public function renderList()
    {
        $this->addRowAction('delete');
        
        return parent::renderList();
    }

    public function displayResult($value, $row)
    {
        if ($value == 'gagne') {
            return '<span class="badge badge-success">🎉 ' . $this->l('Gagné') . '</span>';
        } else {
            return '<span class="badge badge-danger">😢 ' . $this->l('Perdu') . '</span>';
        }
    }

    public function postProcess()
    {
        if (Tools::getValue('exportcsv')) {
            $this->exportCSV();
        }
        
        parent::postProcess();
    }

    private function exportCSV()
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=participations_jeu_gagnant_' . date('Y-m-d_His') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        // En-têtes
        fputcsv($output, [
            'ID', 'Email', 'Numéro choisi', 'Résultat', 'Code promo', 'Date participation'
        ], ';');
        
        // Données
        $participations = Db::getInstance()->executeS('
            SELECT * FROM `' . _DB_PREFIX_ . 'jeu_gagnant_participations`
            ORDER BY date_participation DESC
        ');
        
        foreach ($participations as $participation) {
            fputcsv($output, [
                $participation['id_participation'],
                $participation['email'],
                $participation['number_chosen'],
                $participation['result'] == 'gagne' ? 'Gagné' : 'Perdu',
                $participation['code_promo'] ?: 'N/A',
                $participation['date_participation']
            ], ';');
        }
        
        fclose($output);
        exit;
    }
}