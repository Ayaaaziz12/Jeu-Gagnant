<div class="panel">
    <div class="panel-heading">
        <i class="icon-clock-o"></i> 5 dernières participations
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Numéro</th>
                    <th>Résultat</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                {if $participations}
                    {foreach $participations as $participation}
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
                            <td>{$participation.date_participation}</td>
                        </tr>
                    {/foreach}
                {else}
                    <tr>
                        <td colspan="4" class="text-center">Aucune participation pour le moment</td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>
</div>