<div class="panel">
    <div class="panel-heading">
        <i class="icon-cogs"></i> Configuration du module Jeu Gagnant
    </div>
    
    {if isset($confirmation)}
        <div class="alert alert-success">{$confirmation}</div>
    {/if}
    
    <form method="POST" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-lg-3">Numéro gagnant :</label>
            <div class="col-lg-9">
                <select name="winning_number" class="form-control fixed-width-sm">
                    {for $i=1 to 10}
                        <option value="{$i}" {if $config.winning_number == $i}selected{/if}>{$i}</option>
                    {/for}
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-lg-3">Code promo :</label>
            <div class="col-lg-9">
                <input type="text" name="promo_code" value="{$config.promo_code}" class="form-control fixed-width-lg">
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-lg-3">Date de début :</label>
            <div class="col-lg-9">
                <input type="date" name="date_start" value="{$config.date_start}" class="form-control fixed-width-lg">
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-lg-3">Date de fin :</label>
            <div class="col-lg-9">
                <input type="date" name="date_end" value="{$config.date_end}" class="form-control fixed-width-lg">
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-lg-3">Statut :</label>
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="status" id="status_on" value="1" {if $config.status}checked{/if}>
                    <label for="status_on">Activé</label>
                    <input type="radio" name="status" id="status_off" value="0" {if !$config.status}checked{/if}>
                    <label for="status_off">Désactivé</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
        
        <div class="panel-footer">
            <button type="submit" name="submitConfig" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<div class="panel">
    <div class="panel-heading">
        <i class="icon-list"></i> Liste des participants
    </div>
    <div class="panel-body">
        <a href="{$link->getAdminLink('AdminJeuGagnant')}" class="btn btn-primary">
            Voir les participations
        </a>
    </div>
</div>