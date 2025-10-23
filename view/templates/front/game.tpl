<div class="jeugagnant-game-container">
    {if isset($game_result)}
        {if $game_result == 'gagne'}
            <div class="jeugagnant-result success">
                <h2>🎉 Bravo ! Vous avez gagné !</h2>
                <p>Votre code promo : <strong>{$promo_code}</strong></p>
                <p>Le numéro gagnant était bien le {$winning_number} !</p>
            </div>
        {else}
            <div class="jeugagnant-result error">
                <h2>😢 Perdu, retentez votre chance la prochaine fois.</h2>
                <p>Vous avez choisi le {$chosen_number}, mais le numéro gagnant était le {$winning_number}.</p>
            </div>
        {/if}
        <a href="{$urls.base_url}" class="btn btn-primary">Retour à l'accueil</a>
    {else}
        <h2>Trouvez le numéro gagnant entre 1 et 10</h2>
        <p>Email : <strong>{$email}</strong></p>
        
        <form method="POST" class="jeugagnant-game-form">
            <div class="form-group">
                <label for="guess_number">Votre numéro :</label>
                <select name="guess_number" id="guess_number" class="form-control" required>
                    <option value="">Choisissez un numéro</option>
                    {for $i=1 to 10}
                        <option value="{$i}">{$i}</option>
                    {/for}
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Valider</button>
        </form>
    {/if}
</div>

<style>
.jeugagnant-game-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    text-align: center;
}

.jeugagnant-result.success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.jeugagnant-result.error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.jeugagnant-game-form {
    max-width: 300px;
    margin: 0 auto;
}
</style>