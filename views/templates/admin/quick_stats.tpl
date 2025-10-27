<div class="panel">
    <div class="panel-heading">
        <i class="icon-bar-chart"></i> Statistiques Rapides
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-body text-center">
                    <h3>{$stats.total_participations|default:0}</h3>
                    <p>Participations totales</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-success">
                <div class="panel-body text-center">
                    <h3>{$stats.total_gagnants|default:0}</h3>
                    <p>Gagnants</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-body text-center">
                    <h3>{$stats.taux_reussite|default:0}%</h3>
                    <p>Taux de réussite</p>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <a href="{$admin_participations_url}" class="btn btn-default">
            <i class="icon-list"></i> Voir toutes les participations
        </a>
        <a href="{$admin_participations_url}&exportcsv" class="btn btn-success">
            <i class="icon-download"></i> Export CSV
        </a>
    </div>
</div>