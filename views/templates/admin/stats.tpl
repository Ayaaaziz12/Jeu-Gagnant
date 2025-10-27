<div class="row">
    <!-- Cartes statistiques générales -->
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-3x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{$stats_general.total_participations|default:0}</div>
                        <div>Participations totales</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <span class="pull-left">Détails</span>
                <span class="pull-right">
                    <i class="fa fa-arrow-circle-right"></i>
                </span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-trophy fa-3x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{$stats_general.total_gagnants|default:0}</div>
                        <div>Gagnants</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <span class="pull-left">{$stats_general.taux_reussite|default:0}% de réussite</span>
                <span class="pull-right">
                    <i class="fa fa-arrow-circle-right"></i>
                </span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-times fa-3x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{$stats_general.total_perdus|default:0}</div>
                        <div>Perdants</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <span class="pull-left">{math equation="100-x" x=$stats_general.taux_reussite|default:0}% d'échec</span>
                <span class="pull-right">
                    <i class="fa fa-arrow-circle-right"></i>
                </span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-percent fa-3x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{$stats_general.taux_reussite|default:0}%</div>
                        <div>Taux de réussite</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <span class="pull-left">Numéro gagnant: {$winning_number}</span>
                <span class="pull-right">
                    <i class="fa fa-arrow-circle-right"></i>
                </span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques par numéro -->
<div class="panel">
    <div class="panel-heading">
        <i class="icon-bar-chart"></i> Statistiques par numéro choisi
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Total choix</th>
                    <th>Gagnants</th>
                    <th>% des choix</th>
                    <th>Taux réussite</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                {if $stats_par_numero}
                    {foreach $stats_par_numero as $stat}
                        <tr>
                            <td class="text-center">
                                <strong>{$stat.numero}</strong>
                                {if $stat.numero == $winning_number}
                                    <br><small class="text-success">🎯 Gagnant</small>
                                {/if}
                            </td>
                            <td class="text-center">{$stat.total_choix}</td>
                            <td class="text-center">{$stat.gagnants}</td>
                            <td class="text-center">{$stat.pourcentage_choix}%</td>
                            <td class="text-center">
                                {if $stat.numero == $winning_number}
                                    <span class="label label-success">{$stat.taux_reussite}%</span>
                                {else}
                                    <span class="label label-danger">{$stat.taux_reussite}%</span>
                                {/if}
                            </td>
                            <td class="text-center">
                                {if $stat.numero == $winning_number}
                                    <span class="label label-success">Numéro gagnant</span>
                                {else}
                                    <span class="label label-default">Numéro perdant</span>
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                {else}
                    <tr>
                        <td colspan="6" class="text-center">Aucune donnée statistique</td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>
</div>

<!-- Participations récentes -->
<div class="panel">
    <div class="panel-heading">
        <i class="icon-clock-o"></i> 10 dernières participations
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Numéro</th>
                    <th>Résultat</th>
                    <th>Code promo</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                {if $recent_participations}
                    {foreach $recent_participations as $participation}
                        <tr>
                            <td>{$participation.email}</td>
                            <td class="text-center">
                                <strong>{$participation.number_chosen}</strong>
                                {if $participation.number_chosen == $winning_number}
                                    <br><small class="text-success">🎯 Gagnant</small>
                                {/if}
                            </td>
                            <td class="text-center">
                                {if $participation.result == 'gagne'}
                                    <span class="label label-success">🎉 Gagné</span>
                                {else}
                                    <span class="label label-danger">😢 Perdu</span>
                                {/if}
                            </td>
                            <td class="text-center">
                                {if $participation.code_promo}
                                    <code>{$participation.code_promo}</code>
                                {else}
                                    <span class="text-muted">-</span>
                                {/if}
                            </td>
                            <td>{$participation.date_participation}</td>
                        </tr>
                    {/foreach}
                {else}
                    <tr>
                        <td colspan="5" class="text-center">Aucune participation pour le moment</td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>
</div>

<!-- Top emails multiples -->
{if $top_emails}
<div class="panel">
    <div class="panel-heading">
        <i class="icon-warning"></i> Emails avec participations multiples
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Participations</th>
                    <th>Gagnants</th>
                    <th>Taux réussite</th>
                </tr>
            </thead>
            <tbody>
                {foreach $top_emails as $email}
                    <tr>
                        <td>{$email.email}</td>
                        <td class="text-center">{$email.participations}</td>
                        <td class="text-center">{$email.gagnants}</td>
                        <td class="text-center">
                            {math equation="round((x/y)*100, 2)" x=$email.gagnants y=$email.participations assign="taux"}
                            {if $taux > 0}
                                <span class="label label-success">{$taux}%</span>
                            {else}
                                <span class="label label-danger">0%</span>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{/if}