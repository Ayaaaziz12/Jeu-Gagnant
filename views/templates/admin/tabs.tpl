<div class="panel">
    <div class="panel-heading">
        <i class="icon-trophy"></i> {l s='Jeu Gagnant - Administration' mod='jeugagnant'}
    </div>
    
    <!-- Navigation par onglets -->
    <ul class="nav nav-tabs" role="tablist">
        {foreach $tabs as $tab_id => $tab}
            <li role="presentation" class="{if $tab.active}active{/if}">
                <a href="{$tab.url}" aria-controls="{$tab_id}" role="tab">
                    <i class="{$tab.icon}"></i> {$tab.name}
                </a>
            </li>
        {/foreach}
    </ul>
    
    <!-- Contenu des onglets -->
    <div class="tab-content" style="padding: 15px;">
        {foreach $tabs as $tab_id => $tab}
            {if $tab.active && $tab.content}
                <div role="tabpanel" class="tab-pane active">
                    {$tab.content}
                </div>
            {/if}
        {/foreach}
        
        {if $current_tab == 'participations'}
            <div role="tabpanel" class="tab-pane active">
                <div class="alert alert-info">
                    <i class="icon-info-circle"></i> 
                    Redirection vers la liste complète des participations...
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = '{$tabs.participations.url}';
                    }, 1000);
                </script>
            </div>
        {/if}
    </div>
</div>