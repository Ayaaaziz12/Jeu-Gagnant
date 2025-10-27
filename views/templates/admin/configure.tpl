<div class="panel">
    <div class="panel-heading">
        <i class="icon-cogs"></i> Configuration du Jeu
    </div>

    {if isset($confirmation)}
        <div class="alert alert-success">{$confirmation}</div>
    {/if}

    <form action="{$form_action}" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-lg-3">Numéro gagnant (1-10) :</label>
            <div class="col-lg-9">
                <input type="number" name="winning_number" value="{$winning_number|escape:'html':'UTF-8'}" min="1" max="10" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-lg-3">Code promo à gagner :</label>
            <div class="col-lg-9">
                <input type="text" name="code_promo" value="{$code_promo|escape:'html':'UTF-8'}" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-lg-3">Activer le module :</label>
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="active" id="active_on" value="1" {if $active}checked{/if}>
                    <label for="active_on">Oui</label>
                    <input type="radio" name="active" id="active_off" value="0" {if !$active}checked{/if}>
                    <label for="active_off">Non</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>

        <div class="panel-footer">
            <button type="submit" name="saveConfig" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>