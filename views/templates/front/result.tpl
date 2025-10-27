<div class="jeu-gagnant-result" style="text-align: center; padding: 40px 20px;">
    {if $result == 'gagne'}
        <div class="result-win">
            <h2 style="color: #28a745;">🎉 Bravo ! Vous avez gagné !</h2>
            <p>Vous avez choisi le numéro <strong>{$user_number}</strong></p>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
                <h3>Votre code promo :</h3>
                <div style="font-size: 24px; font-weight: bold; color: #25b9d7;">{$code_promo}</div>
            </div>
            <p>Utilisez-le lors de votre commande !</p>
        </div>
    {else}
        <div class="result-lose">
            <h2 style="color: #dc3545;">😢 Perdu !</h2>
            <p>Vous avez choisi le numéro <strong>{$user_number}</strong></p>
            <p>Le numéro gagnant était : <strong style="font-size: 20px;">{$winning_number}</strong></p>
            <p>Retentez votre chance la prochaine fois !</p>
        </div>
    {/if}
    
    <a href="{$urls.base_url}" class="btn-home" style="display: inline-block; margin-top: 30px; padding: 12px 30px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
        Retour à l'accueil
    </a>
</div>